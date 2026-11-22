<?php
$conn = new mysqli("localhost","root","","appointment_system");
date_default_timezone_set("Asia/Kolkata");

$res = $conn->query("SELECT * FROM appointments WHERE date=CURDATE()");
$data=[];

while($row=$res->fetch_assoc()){
$data[]=$row;
}
?>

<audio id="sound" src="beep.mp3"></audio>

<script>
let reminders = <?php echo json_encode($data); ?>;

function check(){
let now = new Date();

reminders.forEach(r=>{
let appt = new Date(r.date+"T"+r.time);
let diff = (now - appt)/1000;

if(diff>=0 && diff<=10){
document.getElementById("sound").play();
alert("Reminder: "+r.name+" - "+r.type);
}
});
}

setInterval(check,5000);
</script>