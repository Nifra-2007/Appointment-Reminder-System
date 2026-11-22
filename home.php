<?php
$conn = new mysqli("localhost","root","","appointment_system");

date_default_timezone_set("Asia/Kolkata");
$now = date("Y-m-d H:i:s");

// counts
$total = $conn->query("SELECT COUNT(*) c FROM appointments")->fetch_assoc()['c'];
$upcoming = $conn->query("SELECT COUNT(*) c FROM appointments WHERE CONCAT(date,' ',time)>'$now'")->fetch_assoc()['c'];
$completed = $conn->query("SELECT COUNT(*) c FROM appointments WHERE CONCAT(date,' ',time)<='$now'")->fetch_assoc()['c'];

// next appointment
$next = $conn->query("SELECT * FROM appointments WHERE CONCAT(date,' ',time)>'$now' ORDER BY date,time LIMIT 1")->fetch_assoc();

// reminders (ONLY FOR POPUP, not UI)
$res = $conn->query("SELECT * FROM appointments WHERE date=CURDATE()");
$reminders=[];
while($r=$res->fetch_assoc()) $reminders[]=$r;
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

<!-- MAIN -->
<div class="main">

<!-- 🔥 TOPBAR WITH TITLE + SEARCH -->
<!-- TOPBAR -->
<div class="topbar">

    <div class="clock" id="clock"></div>

    <h2>Appointment Reminder System</h2>

    <form action="view.php" method="GET" class="search-box">
        <input type="text" name="search" placeholder="Search by name...">
        <button class="btn">Search</button>
    </form>

</div>

<!-- CLOCK SCRIPT -->
<script>
function updateClock(){
    let now = new Date();

    document.getElementById("clock").innerHTML =
    now.toLocaleDateString() + " | " +
    now.toLocaleTimeString();
}

setInterval(updateClock,1000);
updateClock();
</script>

<!-- DASHBOARD -->
<div class="dashboard">

<div class="card">
<p>Total</p>
<h1><?php echo $total ?></h1>
</div>

<div class="highlight-card">
<p>Upcoming</p>
<h1><?php echo $upcoming ?></h1>
</div>

<div class="card">
<p>Completed</p>
<h1><?php echo $completed ?></h1>
</div>

</div>

<!-- NEXT APPOINTMENT -->
<div style="text-align:center; margin-top:40px;">

<h2>Next Appointment</h2>

<?php if($next){ ?>

<div class="card next-card">
    <b><?php echo $next['name'] ?></b><br><br>

    📅 <?php echo $next['date'] ?><br><br>

    ⏰ <?php echo $next['time'] ?>
</div>

<?php } else { ?>

<p>No upcoming appointments</p>

<?php } ?>

</div>

<!-- 🔥 PREMIUM POPUP (SIDE FLOATING) -->
<div id="popup" style="
display:none;
position:fixed;
top:20px;
right:20px;
background:#1e293b;
color:white;
padding:20px;
border-radius:10px;
box-shadow:0 5px 20px rgba(0,0,0,0.3);
width:250px;
z-index:999;
">
<h3>Reminder</h3>
<p id="msg"></p>
<button onclick="closePop()" style="
margin-top:10px;
padding:8px 15px;
background:#4a6cf7;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
">OK</button>
</div>

<audio id="sound" src="beep.mp3"></audio>

<script>
let reminders = <?php echo json_encode($reminders); ?>;
let audio = document.getElementById("sound");

// unlock sound
document.body.addEventListener("click", ()=>{
    audio.play().then(()=>{
        audio.pause();
        audio.currentTime=0;
    });
},{once:true});

let triggered = {};

function showPop(r){
    document.getElementById("msg").innerHTML =
    "Name: "+r.name+"<br>Type: "+r.type+"<br>Time: "+r.time;

    document.getElementById("popup").style.display = "block";

    // 🔥 sound 3 times
    let count = 0;
    let interval = setInterval(()=>{
        audio.currentTime = 0;
        audio.play();
        count++;
        if(count >= 3) clearInterval(interval);
    },1000);
}

function closePop(){
    document.getElementById("popup").style.display = "none";
}

function check(){
    let now = new Date();

    reminders.forEach(r=>{
        let appt = new Date(r.date + "T" + r.time);
        let diff = (now - appt) / 1000;

        // ✅ EXACT TIME (no early trigger)
        if(diff >= 0 && diff <= 5 && !triggered[r.id]){
            triggered[r.id] = true;
            showPop(r);
        }
    });
}

setInterval(check,1000); // check every second (accurate)
</script>