<?php
$conn = new mysqli("localhost","root","","appointment_system");

// DELETE
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM appointments WHERE id=$id");
    header("Location: view.php");
}

// SEARCH
$search = $_GET['search'] ?? '';
$date = $_GET['date'] ?? '';

$q = "SELECT * FROM appointments WHERE 1";
// FILTERS
if(isset($_GET['today'])){
    $today = date("Y-m-d");
    $q .= " AND date='$today'";
}

if(isset($_GET['upcoming'])){
    $now = date("Y-m-d H:i:s");
    $q .= " AND CONCAT(date,' ',time) > '$now'";
}

if(isset($_GET['completed'])){
    $now = date("Y-m-d H:i:s");
    $q .= " AND CONCAT(date,' ',time) <= '$now'";
}

if($search != ""){
    $q .= " AND name LIKE '%$search%'";
}

if($date != ""){
    $q .= " AND date='$date'";
}

$q .= " ORDER BY date,time";

$res = $conn->query($q);
?>

<link rel="stylesheet" href="style.css">

<div class="sidebar">
    <h2>Dashboard</h2>

    <a href="home.php">Home</a>
    <a href="add.php">Add Appointment</a>
    <a href="view.php">All Appointments</a>

    <hr>

    <a href="view.php?today=1">Today</a>
    <a href="view.php?upcoming=1">Upcoming</a>
    <a href="view.php?completed=1">Completed</a>
</div>

<!-- MAIN CONTENT -->
<div class="main">

<div class="topbar">
    <h3>Appointments</h3>
</div>

<!-- SEARCH -->
<form method="GET" style="text-align:center;margin:20px;">
    <input name="search" placeholder="Search name" value="<?php echo $search; ?>">
    <input type="date" name="date" value="<?php echo $date; ?>">
    <button class="btn">Search</button>
</form>

<!-- TABLE -->
<table>
<tr>
    <th>Name</th>
    <th>Phone</th>
    <th>Type</th>
    <th>Date</th>
    <th>Time</th>
    <th>Action</th>
</tr>

<?php while($r = $res->fetch_assoc()){ ?>
<tr>
    <td><?php echo $r['name']; ?></td>
    <td><?php echo $r['phone']; ?></td>
    <td><?php echo $r['type']; ?></td>
    <td><?php echo $r['date']; ?></td>
    <td><?php echo $r['time']; ?></td>
    <td>
        <a class="btn" href="add.php?id=<?php echo $r['id']; ?>">Edit</a>
        <a class="btn" href="?delete=<?php echo $r['id']; ?>" onclick="return confirm('Delete this appointment?')">Delete</a>
    </td>
</tr>
<?php } ?>

</table>

</div>