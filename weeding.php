<?php
session_start();
$host = "localhost";
$username = "root";
$password = "";
$dbname = "fmts";


$conn = new mysqli($host, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $batchNumber = $_POST['batchNumber'];
    
    $newStatus = "Scanning";
    $sql2 = "UPDATE batch SET status = '$newStatus' WHERE batchNumber = '$batchNumber'";

  if ($conn->query($sql) === TRUE) {
    $_SESSION['msg'] = "New record created successfully"; 
    header("Location: recordroom.php");
} else {
    $_SESSION['msg'] = "Error: " . $sql . "<br>" . $conn->error; 
}
    mysqli_close($conn);
}
?>
