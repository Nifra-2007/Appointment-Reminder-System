<?php
$conn = new mysqli("localhost","root","","appointment_system");

// check connection
if($conn->connect_error){
    die("Connection Failed: " . $conn->connect_error);
}

$id = $_POST['id'] ?? "";

// sanitize inputs (important)
$name = $_POST['name'];
$phone = $_POST['phone'];
$type = $_POST['type'];
$purpose = $_POST['purpose'];
$date = $_POST['date'];
$time = $_POST['time'];

if($id){
    // UPDATE
    $sql = "UPDATE appointments SET 
    name='$name',
    phone='$phone',
    type='$type',
    purpose='$purpose',
    date='$date',
    time='$time'
    WHERE id=$id";
}else{
    // INSERT
    $sql = "INSERT INTO appointments(name,phone,type,purpose,date,time)
    VALUES('$name','$phone','$type','$purpose','$date','$time')";
}

if($conn->query($sql)){
    echo "<script>alert('Appointment Saved Successfully');window.location='view.php';</script>";
}else{
    echo "Error: " . $conn->error;
}
?>