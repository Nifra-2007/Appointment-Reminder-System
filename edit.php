<?php
$conn = new mysqli("localhost","root","","appointment_system");

$id = $_GET['id'] ?? 0;

$result = $conn->query("SELECT * FROM appointments WHERE id='$id'");

if($result->num_rows == 0){
    echo "<h2 style='text-align:center;color:red;'>No Data Found</h2>";
    exit;
}

$row = $result->fetch_assoc();

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $type = $_POST['type'];
    $purpose = $_POST['purpose'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $conn->query("UPDATE appointments SET
        name='$name',
        phone='$phone',
        type='$type',
        purpose='$purpose',
        date='$date',
        time='$time'
        WHERE id='$id'
    ");

    header("Location:view.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<title>Edit</title>
</head>

<body>

<div class="form-box">
<h2>✏ Edit Appointment</h2>

<form method="POST">

<input type="text" name="name" value="<?php echo $row['name']; ?>" required>
<input type="text" name="phone" value="<?php echo $row['phone']; ?>" required>

<select name="type">
<option <?php if($row['type']=="Meeting") echo "selected"; ?>>Meeting</option>
<option <?php if($row['type']=="Doctor") echo "selected"; ?>>Doctor</option>
<option <?php if($row['type']=="Personal") echo "selected"; ?>>Personal</option>
</select>

<input type="text" name="purpose" value="<?php echo $row['purpose']; ?>">
<input type="date" name="date" value="<?php echo $row['date']; ?>" required>
<input type="time" name="time" value="<?php echo $row['time']; ?>" required>

<button class="btn" name="update">Update</button>

</form>
</div>

</body>
</html>