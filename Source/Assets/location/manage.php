<?php
require_once("../db/connection.php");

// DELETE
if(isset($_GET["delete"])){
  $id = (int)$_GET["delete"];
  $stmt = $conn->prepare("DELETE FROM location WHERE LocationID=?");
  $stmt->bind_param("i",$id);
  $stmt->execute();
  header("Location: manage.php");
  exit;
}

// ADD
$err = "";
$success = "";

if($_SERVER["REQUEST_METHOD"]==="POST"){
  $b = trim($_POST["BuildingName"] ?? "");
  $r = trim($_POST["RoomNumber"] ?? "");
  $f = ($_POST["FloorNumber"] === "" ? null : (int)$_POST["FloorNumber"]);
  $d = trim($_POST["Description"] ?? "");

  if($b===""){
    $err = "Building Name is required.";
  } else {
    // Floor can be null - easiest: store null as 0? better: insert null by using IF in query
    $stmt = $conn->prepare("INSERT INTO location (BuildingName, RoomNumber, FloorNumber, Description) VALUES (?,?,?,?)");
    $floor = $f;
    $stmt->bind_param("ssis",$b,$r,$floor,$d);
    $stmt->execute();
    $success = "Location added successfully.";
  }
}

$list = $conn->query("SELECT * FROM location ORDER BY LocationID DESC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Locations</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="topbar">
  <div class="container">
    <h1 class="main-heading">Maintenance Request System</h1>
    <p class="sub-heading">Location Management</p>

    <div class="nav">
      <a href="../index.php">Dashboard</a>
      <a href="../staff/manage.php">Staff</a>
      <a href="../issuetype/manage.php">Issue Types</a>
      <a href="../requests/manage.php">Requests</a>
      <a href="../reports/manage.php">Reports</a>
    </div>
  </div>
</div>

<div class="container">

  <?php if($err): ?><div class="alert error"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
  <?php if($success): ?><div class="alert success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

  <div class="card">
    <h3>Add Location</h3>

    <form method="post" class="form-center">
      <p>
        <label>Building Name *</label>
        <input name="BuildingName" required placeholder="e.g. Block A">
      </p>

      <p>
        <label>Room Number</label>
        <input name="RoomNumber" placeholder="e.g. 101">
      </p>

      <p>
        <label>Floor Number</label>
        <input name="FloorNumber" type="number" placeholder="e.g. 1">
      </p>

      <p>
        <label>Description</label>
        <input name="Description" placeholder="Optional details...">
      </p>

      <div style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
        <button class="btn" type="submit">Save</button>
        <a class="btn btn-secondary" href="manage.php">Refresh</a>
      </div>
    </form>
  </div>

  <div class="card">
    <h3>Location List</h3>

    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Building</th>
          <th>Room</th>
          <th>Floor</th>
          <th>Description</th>
          <th style="width:140px;">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $list->fetch_assoc()): ?>
          <tr>
            <td><?php echo (int)$row["LocationID"]; ?></td>
            <td><?php echo htmlspecialchars($row["BuildingName"]); ?></td>
            <td><?php echo htmlspecialchars($row["RoomNumber"] ?? ""); ?></td>
            <td><?php echo htmlspecialchars($row["FloorNumber"] ?? ""); ?></td>
            <td><?php echo htmlspecialchars($row["Description"] ?? ""); ?></td>
            <td>
              <a class="btn btn-danger"
                 href="?delete=<?php echo (int)$row["LocationID"]; ?>"
                 onclick="return confirm('Delete this location?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</div>
</body>
</html>
