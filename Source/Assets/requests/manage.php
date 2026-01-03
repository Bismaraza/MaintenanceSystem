<?php
require_once("../db/connection.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function todayDate() { return date("Y-m-d"); }
function nowDT() { return date("Y-m-d H:i:s"); }

$err = "";
$success = "";

/* =========================
   CREATE REQUEST
========================= */
if (isset($_POST["action"]) && $_POST["action"] === "create_request") {

  $userId = ($_POST["UserID"] === "" ? null : (int)$_POST["UserID"]); // optional
  $issueTypeId = (int)($_POST["IssueTypeID"] ?? 0);
  $locationId  = (int)($_POST["LocationID"] ?? 0);
  $desc = trim($_POST["RequestDescription"] ?? "");
  $date = trim($_POST["RequestDate"] ?? todayDate());

  if ($issueTypeId <= 0 || $locationId <= 0 || $date === "") {
    $err = "Issue Type, Location and Request Date are required.";
  } else {

    $status = "Pending";

    if ($userId === null) {
      $stmt = $conn->prepare("
        INSERT INTO maintenancerequest
        (UserID, IssueTypeID, LocationID, RequestDescription, RequestDate, Status, AssignedStaffID)
        VALUES (NULL, ?, ?, ?, ?, ?, NULL)
      ");
      $stmt->bind_param("iisss", $issueTypeId, $locationId, $desc, $date, $status);
    } else {
      $stmt = $conn->prepare("
        INSERT INTO maintenancerequest
        (UserID, IssueTypeID, LocationID, RequestDescription, RequestDate, Status, AssignedStaffID)
        VALUES (?, ?, ?, ?, ?, ?, NULL)
      ");
      $stmt->bind_param("iiisss", $userId, $issueTypeId, $locationId, $desc, $date, $status);
    }

    if(!$stmt->execute()){
      $err = "Create request failed: " . $conn->error;
    } else {
      $newId = $conn->insert_id;

      // Log (StaffID 0 = System)
      $log = $conn->prepare("INSERT INTO maintenancelogs (RequestID, StaffID, LogDate, LogDetails) VALUES (?, ?, ?, ?)");
      $staffIdForLog = 0;
      $dt = nowDT();
      $logText = "Request created (Status: Pending)";
      $log->bind_param("iiss", $newId, $staffIdForLog, $dt, $logText);
      $log->execute();

      $success = "Request created successfully.";
    }
  }
}

/* =========================
   ASSIGN STAFF
========================= */
if (isset($_POST["action"]) && $_POST["action"] === "assign_staff") {

  $requestId = (int)($_POST["RequestID"] ?? 0);
  $staffId   = (int)($_POST["StaffID"] ?? 0);

  if ($requestId <= 0 || $staffId <= 0) {
    $err = "Select a request and staff to assign.";
  } else {

    $assignedDate = todayDate();
    $assignmentStatus = "Assigned";

    $stmt = $conn->prepare("
      INSERT INTO requestassignment (RequestID, StaffID, AssignedDate, CompletionDate, AssignmentStatus)
      VALUES (?, ?, ?, NULL, ?)
    ");
    $stmt->bind_param("iiss", $requestId, $staffId, $assignedDate, $assignmentStatus);

    if(!$stmt->execute()){
      $err = "Assignment failed: " . $conn->error;
    } else {
      // Update main request
      $status = "In Progress";
      $up = $conn->prepare("UPDATE maintenancerequest SET AssignedStaffID=?, Status=? WHERE RequestID=?");
      $up->bind_param("isi", $staffId, $status, $requestId);
      $up->execute();

      // Log
      $log = $conn->prepare("INSERT INTO maintenancelogs (RequestID, StaffID, LogDate, LogDetails) VALUES (?, ?, ?, ?)");
      $dt = nowDT();
      $logText = "Assigned to StaffID=$staffId (Status: In Progress)";
      $log->bind_param("iiss", $requestId, $staffId, $dt, $logText);
      $log->execute();

      $success = "Staff assigned successfully.";
    }
  }
}

/* =========================
   MARK COMPLETED (stable method)
========================= */
if (isset($_GET["complete"])) {

  $requestId = (int)$_GET["complete"];
  $completionDate = todayDate();
  $status = "Completed";

  // Update request status
  $up = $conn->prepare("UPDATE maintenancerequest SET Status=? WHERE RequestID=?");
  $up->bind_param("si", $status, $requestId);
  $up->execute();

  // Find latest assignment id (safe)
  $aidQ = $conn->prepare("SELECT AssignmentID, StaffID FROM requestassignment WHERE RequestID=? ORDER BY AssignmentID DESC LIMIT 1");
  $aidQ->bind_param("i", $requestId);
  $aidQ->execute();
  $aidRes = $aidQ->get_result()->fetch_assoc();

  $staffIdForLog = 0;

  if ($aidRes) {
    $assignmentId = (int)$aidRes["AssignmentID"];
    $staffIdForLog = (int)$aidRes["StaffID"];

    $up2 = $conn->prepare("UPDATE requestassignment SET CompletionDate=?, AssignmentStatus='Completed' WHERE AssignmentID=?");
    $up2->bind_param("si", $completionDate, $assignmentId);
    $up2->execute();
  }

  // Log
  $log = $conn->prepare("INSERT INTO maintenancelogs (RequestID, StaffID, LogDate, LogDetails) VALUES (?, ?, ?, ?)");
  $dt = nowDT();
  $logText = "Marked Completed (CompletionDate: $completionDate)";
  $log->bind_param("iiss", $requestId, $staffIdForLog, $dt, $logText);
  $log->execute();

  header("Location: manage.php");
  exit;
}

/* =========================
   DROPDOWNS
========================= */
$issueTypes = $conn->query("SELECT IssueTypeID, TypeName FROM issuetype ORDER BY TypeName");
$locations  = $conn->query("SELECT LocationID, BuildingName, RoomNumber FROM location ORDER BY BuildingName");
$staff      = $conn->query("SELECT StaffID, StaffName FROM maintenancestaff ORDER BY StaffName");

// View listing (names)
$requestsView = $conn->query("SELECT * FROM vw_requestdetails ORDER BY RequestID DESC");

// Assign dropdown
$reqDD = $conn->query("SELECT RequestID, Status FROM maintenancerequest ORDER BY RequestID DESC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Requests</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="topbar">
  <div class="container">
    <h1 class="main-heading">Maintenance Request System</h1>
    <p class="sub-heading">Requests</p>

    <div class="nav">
      <a href="../index.php">Dashboard</a>
      <a href="../staff/manage.php">Staff</a>
      <a href="../location/manage.php">Locations</a>
      <a href="../issuetype/manage.php">Issue Types</a>
      <a href="../reports/manage.php">Reports</a>
    </div>
  </div>
</div>

<div class="container">

  <?php if($err): ?><div class="alert error"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
  <?php if($success): ?><div class="alert success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

  <div class="card">
    <h3>Create New Request</h3>

    <form method="post" class="form-center">
      <input type="hidden" name="action" value="create_request">

      <p>
        <label>UserID (optional)</label>
        <input name="UserID" type="number" placeholder="Leave empty if none">
      </p>

      <p>
        <label>Issue Type *</label>
        <select name="IssueTypeID" required>
          <option value="">Select Issue Type</option>
          <?php while($it = $issueTypes->fetch_assoc()): ?>
            <option value="<?php echo (int)$it["IssueTypeID"]; ?>">
              <?php echo htmlspecialchars($it["TypeName"]); ?>
            </option>
          <?php endwhile; ?>
        </select>
      </p>

      <p>
        <label>Location *</label>
        <select name="LocationID" required>
          <option value="">Select Location</option>
          <?php while($loc = $locations->fetch_assoc()): ?>
            <option value="<?php echo (int)$loc["LocationID"]; ?>">
              <?php
                $label = $loc["BuildingName"];
                if(!empty($loc["RoomNumber"])) $label .= " - Room ".$loc["RoomNumber"];
                echo htmlspecialchars($label);
              ?>
            </option>
          <?php endwhile; ?>
        </select>
      </p>

      <p>
        <label>Description</label>
        <textarea name="RequestDescription" placeholder="Write the issue details..."></textarea>
      </p>

      <p>
        <label>Request Date *</label>
        <input name="RequestDate" type="date" value="<?php echo date('Y-m-d'); ?>" required>
      </p>

      <div style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
        <button class="btn" type="submit">Create Request</button>
        <a class="btn btn-secondary" href="manage.php">Refresh</a>
      </div>
    </form>
  </div>

  <div class="card">
    <h3>Assign Staff</h3>

    <form method="post" class="form-center">
      <input type="hidden" name="action" value="assign_staff">

      <p>
        <label>Request</label>
        <select name="RequestID" required>
          <option value="">Select Request</option>
          <?php while($r = $reqDD->fetch_assoc()): ?>
            <option value="<?php echo (int)$r["RequestID"]; ?>">
              #<?php echo (int)$r["RequestID"]; ?> (<?php echo htmlspecialchars($r["Status"]); ?>)
            </option>
          <?php endwhile; ?>
        </select>
      </p>

      <p>
        <label>Staff</label>
        <select name="StaffID" required>
          <option value="">Select Staff</option>
          <?php while($s = $staff->fetch_assoc()): ?>
            <option value="<?php echo (int)$s["StaffID"]; ?>">
              <?php echo htmlspecialchars($s["StaffName"]); ?>
            </option>
          <?php endwhile; ?>
        </select>
      </p>

      <div style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
        <button class="btn" type="submit">Assign</button>
      </div>
    </form>
  </div>

  <div class="card">
    <h3>All Requests</h3>

    <?php if(!$requestsView): ?>
      <div class="alert error">View query failed: <?php echo htmlspecialchars($conn->error); ?></div>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Requester</th>
            <th>Issue Type</th>
            <th>Location</th>
            <th>Description</th>
            <th>Date</th>
            <th>Status</th>
            <th>Assigned Staff</th>
            <th style="width:160px;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $requestsView->fetch_assoc()): ?>
            <?php
              $status = $row["Status"] ?? "";
              $badgeClass = "badge";
              if($status === "Pending") $badgeClass .= " pending";
              elseif($status === "In Progress") $badgeClass .= " progress";
              elseif($status === "Completed") $badgeClass .= " done";
            ?>
            <tr>
              <td><?php echo (int)$row["RequestID"]; ?></td>
              <td><?php echo htmlspecialchars($row["Requester"] ?? ""); ?></td>
              <td><?php echo htmlspecialchars($row["IssueType"] ?? ""); ?></td>
              <td><?php echo htmlspecialchars($row["Location"] ?? ""); ?></td>
              <td><?php echo htmlspecialchars($row["RequestDescription"] ?? ""); ?></td>
              <td><?php echo htmlspecialchars($row["RequestDate"] ?? ""); ?></td>
              <td><span class="<?php echo $badgeClass; ?>"><?php echo htmlspecialchars($status); ?></span></td>
              <td><?php echo htmlspecialchars($row["AssignedStaff"] ?? ""); ?></td>
              <td>
                <?php if($status !== "Completed"): ?>
                  <a class="btn btn-secondary"
                     href="?complete=<?php echo (int)$row["RequestID"]; ?>"
                     onclick="return confirm('Mark this request completed?')">Mark Completed</a>
                <?php else: ?>
                  <span class="badge done">Done</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php endif; ?>

  </div>

</div>
</body>
</html>
