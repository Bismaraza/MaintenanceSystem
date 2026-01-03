<?php
require_once("../db/connection.php");

// DELETE
if(isset($_GET["delete"])){
  $id = (int)$_GET["delete"];
  $stmt = $conn->prepare("DELETE FROM maintenancestaff WHERE StaffID=?");
  $stmt->bind_param("i",$id);
  $stmt->execute();
  header("Location: manage.php");
  exit;
}

// ADD
$err = "";
$success = "";

if($_SERVER["REQUEST_METHOD"]==="POST"){
  $name = trim($_POST["StaffName"] ?? "");
  $contact = trim($_POST["ContactNumber"] ?? "");
  $email = trim($_POST["Email"] ?? "");
  $role = trim($_POST["Role"] ?? "");

  if($name===""){
    $err="Staff Name is required.";
  } else {
    $stmt = $conn->prepare("INSERT INTO maintenancestaff (StaffName, ContactNumber, Email, Role) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss",$name,$contact,$email,$role);
    $stmt->execute();
    $success = "Staff added successfully.";
  }
}

$list = $conn->query("SELECT * FROM maintenancestaff ORDER BY StaffID DESC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Staff</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<!-- Top Bar -->
<div class="topbar">
  <div class="container">
    <h1 class="main-heading">Maintenance Request System</h1>
    <p class="sub-heading">Staff Management</p>

    <div class="nav">
      <a href="../index.php">Dashboard</a>
      <a href="../location/manage.php">Locations</a>
      <a href="../issuetype/manage.php">Issue Types</a>
      <a href="../requests/manage.php">Requests</a>
      <a href="../reports/manage.php">Reports</a>
    </div>
  </div>
</div>

<div class="container">

  <?php if($err): ?>
    <div class="alert error"><?php echo htmlspecialchars($err); ?></div>
  <?php endif; ?>

  <?php if($success): ?>
    <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>

  <!-- Add Staff -->
  <div class="card">
    <h3>Add Staff</h3>

    <form method="post" class="form-center">
      <p>
        <label>Staff Name *</label>
        <input name="StaffName" required placeholder="e.g. Ali Khan">
      </p>

      <p>
        <label>Contact Number</label>
        <input name="ContactNumber" placeholder="e.g. 03001234567">
      </p>

      <p>
        <label>Email</label>
        <input name="Email" type="email" placeholder="e.g. ali@uni.edu">
      </p>

      <p>
        <label>Role</label>
        <input name="Role" placeholder="Technician / Electrician / Plumber etc">
      </p>

      <div style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
        <button class="btn" type="submit">Save</button>
        <a class="btn btn-secondary" href="manage.php">Refresh</a>
      </div>
    </form>
  </div>

  <!-- Staff List -->
  <div class="card">
    <h3>Staff List</h3>

    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Contact</th>
          <th>Email</th>
          <th>Role</th>
          <th style="width:140px;">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $list->fetch_assoc()): ?>
          <tr>
            <td><?php echo (int)$row["StaffID"]; ?></td>
            <td><?php echo htmlspecialchars($row["StaffName"]); ?></td>
            <td><?php echo htmlspecialchars($row["ContactNumber"] ?? ""); ?></td>
            <td><?php echo htmlspecialchars($row["Email"] ?? ""); ?></td>
            <td><?php echo htmlspecialchars($row["Role"] ?? ""); ?></td>
            <td>
              <a class="btn btn-danger" href="?delete=<?php echo (int)$row["StaffID"]; ?>"
                 onclick="return confirm('Delete this staff?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

  </div>

</div>
</body>
</html>
