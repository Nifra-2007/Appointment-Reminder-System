<?php
$conn = new mysqli("localhost","root","","appointment_system");

$id = $_GET['id'] ?? "";
$row = [];

// FETCH DATA FOR EDIT
if(!empty($id)){
    $res = $conn->query("SELECT * FROM appointments WHERE id='$id'");
    if($res && $res->num_rows > 0){
        $row = $res->fetch_assoc();
    }
}

// SAVE / UPDATE
if(isset($_POST['save'])){

    $id_post = $_POST['id'];

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $type = $_POST['type'];
    $purpose = $_POST['purpose'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    if(empty($id_post)){
        $conn->query("INSERT INTO appointments(name,phone,type,purpose,date,time)
        VALUES('$name','$phone','$type','$purpose','$date','$time')");
    } else {
        $conn->query("UPDATE appointments SET
        name='$name',
        phone='$phone',
        type='$type',
        purpose='$purpose',
        date='$date',
        time='$time'
        WHERE id='$id_post'");
    }

    header("Location:view.php");
}
?>

<link rel="stylesheet" href="style.css">

<div class="form-box">

<h2><?php echo $row ? "✏️ Edit Appointment" : "➕ Add Appointment"; ?></h2>

<form method="POST" class="form-grid">

<input type="hidden" name="id" value="<?php echo $id; ?>">

<label>👤 Name</label>
<input name="name" value="<?php echo $row['name'] ?? '' ?>" required>

<label>📞 Phone</label>
<input name="phone" value="<?php echo $row['phone'] ?? '' ?>">

<label>📌 Type</label>
<select name="type">
<option <?php if(($row['type'] ?? '')=='Meeting') echo 'selected'; ?>>Meeting</option>
<option <?php if(($row['type'] ?? '')=='Doctor') echo 'selected'; ?>>Doctor</option>
<option <?php if(($row['type'] ?? '')=='Personal') echo 'selected'; ?>>Personal</option>
</select>

<label>📝 Purpose</label>
<input name="purpose" value="<?php echo $row['purpose'] ?? '' ?>">

<label>📅 Date</label>
<input type="date" name="date" value="<?php echo $row['date'] ?? '' ?>" required>

<label>⏰ Time</label>
<input type="time" name="time" value="<?php echo $row['time'] ?? '' ?>" required>

<button class="btn" name="save">💾 Save Appointment</button>

</form>
</div>