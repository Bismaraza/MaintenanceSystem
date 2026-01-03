<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Maintenance Request System</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Top Bar -->
<div class="topbar">
  <div class="container">
    <h1 class="main-heading">Maintenance Request System</h1>
    <p class="sub-heading">Dashboard</p>
  </div>
</div>

<!-- Dashboard Cards -->
<div class="container">

  <div class="cards dashboard-grid">

    <!-- Row 1 -->
    <div class="stat">
      <div class="title">Manage Staff</div>
      <div class="value">👨‍🔧</div>
      <p>Add & manage maintenance staff</p>
      <a class="btn" href="staff/manage.php">Open</a>
    </div>

    <div class="stat">
      <div class="title">Manage Locations</div>
      <div class="value">🏢</div>
      <p>Buildings, rooms & floors</p>
      <a class="btn" href="location/manage.php">Open</a>
    </div>

    <!-- Row 2 -->
    <div class="stat">
      <div class="title">Issue Types</div>
      <div class="value">🛠️</div>
      <p>Plumbing, Electrical, Network</p>
      <a class="btn" href="issuetype/manage.php">Open</a>
    </div>

    <div class="stat">
      <div class="title">Requests</div>
      <div class="value">📋</div>
      <p>Create, assign & track requests</p>
      <a class="btn" href="requests/manage.php">Open</a>
    </div>

    <!-- Full Width -->
    <div class="stat full">
      <div class="title">Reports</div>
      <div class="value">📊</div>
      <p>View system statistics</p>
      <a class="btn" href="reports/manage.php">Open</a>
    </div>

  </div>

</div>

</body>
</html>
