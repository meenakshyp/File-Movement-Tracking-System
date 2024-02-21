<?php
session_start();
$host = "localhost";
$username = "root";
$password = "";
$dbname = "fmts";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $batchNumber= $_POST['batch_no'];
    $uniqueID = $_POST['uniqueID'];
    $defect=$_POST['defective_file'];
    $reason_for_defect=$_POST['reason_for_defect'];
    $location="scanning room";
    

    $sql = "INSERT INTO batchno_idlink (batchNumber, uniqueID)
            VALUES ('$batchNumber', '$uniqueID')";

  for ($i = 0; $i < count($defect); $i++) {
      $defectiveFile = $defect[$i];
      $reason = $reason_for_defect[$i];

      $sql1 = "INSERT INTO defective_files (file_no, batchNumber, reason_for_defect, location)
              VALUES ('$defectiveFile', '$batchNumber', '$reason', '$location')";

      if ($conn->query($sql1) !== TRUE) {
          // Handle the error if needed
          echo "Error: " . $sql1 . "<br>" . $conn->error;
      }
  }

    $newStatus = "Verification 1";
    $sql2 = "UPDATE batch SET status = '$newStatus' WHERE batchNumber = '$batchNumber'";

    // Initialize a flag variable
$successFlag = true;

if ($conn->query($sql) !== TRUE) {
    $successFlag = false;
    echo "Error: " . $sql . "<br>" . $conn->error;
}


if ($conn->query($sql2) !== TRUE) {
    $successFlag = false;
    echo "Error: " . $sql2 . "<br>" . $conn->error;
}

if ($successFlag) {
    $_SESSION['msg'] = "Scanning successful";
    header("Location: scanning.php");
}
exit(); 
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Scanning Room</title>
  <link rel="icon" type="image/x-icon" href="High-Court-of-Kerala-Logo-fotor-bg-remover-20230926133216.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel="stylesheet" href="scanning.css">
<link rel="stylesheet" href="style2.css">
  <style>
    /* Add this CSS to reduce the space between buttons */
    .defective-file-entry button {
        margin-top: 80px; /* Adjust the margin as needed */
    }

    /* Add this CSS to align the "Add More" button with the "Remove" button */
    #addDefectiveFile {
        margin-top: 5px; /* Adjust the margin as needed */
    }
    
    .btn{
      margin-top:50px;
      border-radius:10px;

    }
    .left-section {
    float: left; /* Float left for the left section */
    width: 50%; /* Set the width as needed */
    position:relative;
    left:0%;
}

.right-section {
    float: right; /* Float left for the right section */
    width: 50%; /* Set the width as needed */
    position:relative;
    right:0%;
}

/* Clear the floats to prevent issues with following elements */
.container::after {
    content: "";
    display: table;
    clear: both;
}

</style>

</head>
<body>
   <header class="flex justify-between items-center">
    <div class="flex items-center">
        <img src="highcourt.png" alt="Header Image" class="mr-2" width="900px" height="100px">
    </div>
    <button style=" padding: 5px; border-radius:0.2rem;margin-left: -150px; color: white; text-decoration: none; background-color: red; border: none; cursor: pointer;">
            <a href="logout.php" style="color: inherit; text-decoration: none;">Logout</a>
   </header>
   
   <form  action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="POST">
   <div class="box" style=" width: 100%;">
      <div>
      <?php
   if (isset($_SESSION['msg'])) {
        ?>

        <div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong> <?php echo $_SESSION['msg']; ?></strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php
        unset($_SESSION['msg']); // Clear the message
    }
    ?>
    <div class="left-section">
        <h1>Enter batch number</h1>
        <input class="name-input" type="text" name="batch_no" placeholder="Batch Number" style="color:black; width:75%" required autocomplete="off">
        <h1>Enter new identifier</h1>
        <input class="name-input ext" type="text" name="uniqueID" placeholder="Unique ID" style="color:black; width:75%" required autocomplete="off">
      </div>
</div>

<div class="right-section">
      <div class="defect-info">
        <h4>Defective Files</h4>
        <p>Enter the file numbers of the defected files and their reasons</p>

        <!-- Dynamic input fields for defective files and reasons -->
        <div id="defective-files-container">
            <div class="defective-file-entry">
                <input class="name-input" type="text" name="defective_file[]" placeholder="Defective File No" style="width:57%" autocomplete="off">
                <input class="name-input" type="text" name="reason_for_defect[]" placeholder="Reason"style="width:57%" autocomplete="off">
                <button type="button" class="remove-field btn" >Remove</button>
            </div>
        </div>
        <button type="button" id="addDefectiveFile" class="btn">Add More</button> 
      </div>
</div>
      <!--<button type="submit" class="btn" style="border-radius:1px; position:relative;top:4px; background-color" >Send for verification</button>-->
      <button type="submit" class="btn" style="background-color: black; border-radius: 1px; width: 600px; height: 45px; position:relative;top:4px; color: white; transition: background-color 0.3s; cursor: pointer;" 
      onmouseover="this.style.backgroundColor='#604536'" onmouseout="this.style.backgroundColor='black'">Send for verification</button>
    </form>
   </div>

    <!-- jQuery library (add it to the head or body as needed) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#addDefectiveFile").click(function () {
                var newField = '<div class="defective-file-entry">' +
                    '<input class="name-input" type="text" name="defective_file[]" placeholder="Defective File No" style="width:50%" autocomplete="off">' +
                    '<br>' +
                    '<input class="name-input" type="text" name="reason_for_defect[]" placeholder="Reason" style="width:50%" autocomplete="off">' +
                    '<button type="button" class="remove-field btn">Remove</button>' 
                    '</div>';
                $("#defective-files-container").append(newField);
            });

            $("#defective-files-container").on("click", ".remove-field", function () {
                $(this).closest(".defective-file-entry").remove();
            });
        });
    </script>
</body>
</html>
