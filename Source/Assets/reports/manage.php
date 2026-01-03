<?php
require_once("../db/connection.php");

// Status counts
$pending   = (int)$conn->query("SELECT COUNT(*) c FROM maintenancerequest WHERE Status='Pending'")->fetch_assoc()["c"];
$progress  = (int)$conn->query("SELECT COUNT(*) c FROM maintenancerequest WHERE Status='In Progress'")->fetch_assoc()["c"];
$completed = (int)$conn->query("SELECT COUNT(*) c FROM maintenancerequest WHERE Status='Completed'")->fetch_assoc()["c"];

// Staff performance (completed)
$staffPerf = $conn->query("
  SELECT ms.StaffID, ms.StaffName, COUNT(mr.RequestID) AS CompletedCount
  FROM maintenancestaff ms
  LEFT JOIN maintenancerequest mr
    ON mr.AssignedStaffID = ms.StaffID AND mr.Status='Completed'
  GROUP BY ms.StaffID, ms.StaffName
  ORDER BY CompletedCount DESC, ms.StaffName
");

// Latest from view
$latest = $conn->query("SELECT * FROM vw_requestdetails ORDER BY RequestID DESC LIMIT 25");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Reports</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="topbar">
  <div class="container">
    <h1 class="main-heading">Maintenance Request System</h1>
    <p class="sub-heading">Reports</p>

    <div class="nav">
      <a href="../index.php">Dashboard</a>
      <a href="../staff/manage.php">Staff</a>
      <a href="../location/manage.php">Locations</a>
      <a href="../issuetype/manage.php">Issue Types</a>
      <a href="../requests/manage.php">Requests</a>
    </div>
  </div>
</div>

<div class="container">

  <div class="card">
    <h3>System Summary</h3>
    <div class="cards">
      <div class="stat">
        <div class="title">Pending</div>
        <div class="value"><?php echo $pending; ?></div>
      </div>
      <div class="stat">
        <div class="title">In Progress</div>
        <div class="value"><?php echo $progress; ?></div>
      </div>
      <div class="stat">
        <div class="title">Completed</div>
        <div class="value"><?php echo $completed; ?></div>
      </div>
    </div>
  </div>

  <div class="card">
    <h3>Staff Performance (Completed Requests)</h3>
    <table class="table">
      <thead>
        <tr><th>StaffID</th><th>Staff Name</th><th>Completed</th></tr>
      </thead>
      <tbody>
        <?php while($r = $staffPerf->fetch_assoc()): ?>
          <tr>
            <td><?php echo (int)$r["StaffID"]; ?></td>
            <td><?php echo htmlspecialchars($r["StaffName"]); ?></td>
            <td><?php echo (int)$r["CompletedCount"]; ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div class="card">
    <h3>Latest Requests</h3>
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
        </tr>
      </thead>
      <tbody>
        <?php while($row = $latest->fetch_assoc()): ?>
          <tr>
            <td><?php echo (int)$row["RequestID"]; ?></td>
            <td><?php echo htmlspecialchars($row["Requester"] ?? ""); ?></td>
            <td><?php echo htmlspecialchars($row["IssueType"] ?? ""); ?></td>
            <td><?php echo htmlspecialchars($row["Location"] ?? ""); ?></td>
            <td><?php echo htmlspecialchars($row["RequestDescription"] ?? ""); ?></td>
            <td><?php echo htmlspecialchars($row["RequestDate"] ?? ""); ?></td>
            <td><?php echo htmlspecialchars($row["Status"] ?? ""); ?></td>
            <td><?php echo htmlspecialchars($row["AssignedStaff"] ?? ""); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</div>
</body>
</html>
