<?php
require_once("../db/connection.php");

// DELETE
if(isset($_GET["delete"])){
  $id = (int)$_GET["delete"];
  $stmt = $conn->prepare("DELETE FROM issuetype WHERE IssueTypeID=?");
  $stmt->bind_param("i",$id);
  $stmt->execute();
  header("Location: manage.php");
  exit;
}

// ADD
$err = "";
$success = "";

if($_SERVER["REQUEST_METHOD"]==="POST"){
  $t = trim($_POST["TypeName"] ?? "");
  $d = trim($_POST["Description"] ?? "");

  if($t===""){
    $err="Type Name is required.";
  } else {
    $stmt = $conn->prepare("INSERT INTO issuetype (TypeName, Description) VALUES (?,?)");
    $stmt->bind_param("ss",$t,$d);
    $stmt->execute();
    $success="Issue Type added successfully.";
  }
}

$list = $conn->query("SELECT * FROM issuetype ORDER BY IssueTypeID DESC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Issue Types</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="topbar">
  <div class="container">
    <h1 class="main-heading">Maintenance Request System</h1>
    <p class="sub-heading">Issue Type Management</p>

    <div class="nav">
      <a href="../index.php">Dashboard</a>
      <a href="../staff/manage.php">Staff</a>
      <a href="../location/manage.php">Locations</a>
      <a href="../requests/manage.php">Requests</a>
      <a href="../reports/manage.php">Reports</a>
    </div>
  </div>
</div>

<div class="container">

  <?php if($err): ?><div class="alert error"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
  <?php if($success): ?><div class="alert success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

  <div class="card">
    <h3>Add Issue Type</h3>

    <form method="post" class="form-center">
      <p>
        <label>Type Name *</label>
        <input name="TypeName" required placeholder="e.g. Electrical">
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
    <h3>Issue Type List</h3>

    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Type</th>
          <th>Description</th>
          <th style="width:140px;">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $list->fetch_assoc()): ?>
          <tr>
            <td><?php echo (int)$row["IssueTypeID"]; ?></td>
            <td><?php echo htmlspecialchars($row["TypeName"]); ?></td>
            <td><?php echo htmlspecialchars($row["Description"] ?? ""); ?></td>
            <td>
              <a class="btn btn-danger"
                 href="?delete=<?php echo (int)$row["IssueTypeID"]; ?>"
                 onclick="return confirm('Delete this issue type?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</div>
</body>
</html>
