<?php
session_start(); // Start a PHP session

$host = "localhost";
$username = "root";
$password = "";
$dbname = "fmts";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uniqueID = $_POST['uniqueID']; // Corrected to match your HTML input name
    $defect=$_POST['defective_file'];
    $reason_for_defect=$_POST['reason_for_defect'];


   
        // batchNumber corresponding to the uniqueID
        $sql_select = "SELECT batchNumber FROM batchno_idlink WHERE uniqueID = '$uniqueID'";
        $result_select = $conn->query($sql_select);
    
        if ($result_select && $result_select->num_rows > 0) {
            $row = $result_select->fetch_assoc();
            $batchNumber = $row["batchNumber"];
        } else {
            echo "Error: Unable to retrieve batchNumber for uniqueID '$uniqueID'";
    
        }
    
        // retrieved batchNumber to update information in another table
        if (isset($batchNumber)) {
            $sql_update = "UPDATE batch SET status = 'Verification 2' WHERE batchNumber = '$batchNumber'";
            $location="vERIFICATION 1";
            for ($i = 0; $i < count($defect); $i++) {
                $defectiveFile = $defect[$i];
                $reason = $reason_for_defect[$i];
            
                $sql1 = "INSERT INTO defective_files (file_no, batchNumber, reason_for_defect, location)
                        VALUES ('$defectiveFile', '$batchNumber', '$reason', '$location')";
                
                if ($conn->query($sql1) === TRUE) {
                    // Successfully inserted a defective file
                } else {
                    $_SESSION['msg'] = "Error: " . $sql1 . "<br>" . $conn->error; // Set error message
                }
            if ($conn->query($sql_update) === TRUE) {
                    $_SESSION['msg'] = "L1 verification successful";
            } else {
                $_SESSION['msg'] = "Error updating batch status: " . $conn->error;
            }
        }
            
            
            header("Location: verification.php");
            } else {
                $_SESSION['msg'] = "Error: " . $sql . "<br>" . $conn->error; // Set error message
            }


    
    
    exit(); // Stop further execution of the current script
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="scanning.css">
<link rel="stylesheet" href="style2.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>L1 Verification</title>
    <link rel="icon" type="image/x-icon" href="High-Court-of-Kerala-Logo-fotor-bg-remover-20230926133216.png">
    <link rel="stylesheet" href="verification.css" type="text/css">
    <style>
    /* Add this CSS to reduce the space between buttons */
    .defective-file-entry button {
        margin-top: 100px; /* Adjust the margin as needed */
    }

    /* Add this CSS to align the "Add More" button with the "Remove" button */
    #addDefectiveFile {
        margin-top: 5px; /* Adjust the margin as needed */
    }
    
    .btn{
      margin-top:50px;
    }

    h4{
        font-weight:bold;
    }
</style>
</head>

<body>
    <header class="flex justify-between items-center">

        <div class="flex items-center">
            <img src="highcourt.png" alt="Header Image" class="mr-2" width="980px" height="100px">
        </div>
        <button style=" padding: 5px; border-radius:0.2rem;margin-left: -150px; color: white; text-decoration: none; background-color: red; border: none; cursor: pointer;">
            <a href="logout.php" style="color: inherit; text-decoration: none;">Logout</a>
    </header>

    <div class="container" style="position: relative; bottom:405px;">
        <h1>L-1 Verification</h1>
 <form id="batchForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <div style="text-align: left; position:absolute; top:250px;">
        <label for="uniqueID" style="font-size: 25px; font-family: monospace; display: inline-block; width: 2 00px; text-align: right;">Batch ID:</label>
        <input type="text" style="border-color: black; width: 550px; height: 50px; display: inline-block;" id="uniqueID" name="uniqueID" required>
    </div>
    <div style="text-align: center; margin-top: 10px;">
        <div id="error" class="error-message"></div>
    </div>
    

    <div class="defect-info" style="position:absolute; top:320px; text-align:left;">
        <div class="defect-info">
        <?php
        // Display success or error message if they are set
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
        <h4>
    <i class="fas fa-exclamation-triangle mr-2"></i> Defective Files
</h4>
        <p>Enter the file numbers of the defected files and their reasons</p>

        <!-- Dynamic input fields for defective files and reasons -->
        <div id="defective-files-container">
            <div class="defective-file-entry">
                <input class="name-input" type="text" name="defective_file[]" placeholder="Defective File No" autocomplete="off">
                <input class="name-input" type="text" name="reason_for_defect[]" placeholder="Reason" autocomplete="off">
                <button type="button" class="remove-field btn" style="width: 150px; height:45px">Remove</button>
            </div>
        </div>
        <button type="button" id="addDefectiveFile" class="btn" style="width: 150px; height:45px">Add More</button>
      </div>
      <div class="center-button">
      <button type="submit" class="btn" style="background-color: black; border-radius: 1px; width: 700px; height: 45px; position: relative; left: 160px; color: white; transition: background-color 0.3s; cursor: pointer;" 
      onmouseover="this.style.backgroundColor='#604536'" onmouseout="this.style.backgroundColor='black'">Send for verification</button>
</div>
   </div>

    <!-- jQuery library (add it to the head or body as needed) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#addDefectiveFile").click(function () {
                var newField = '<div class="defective-file-entry">' +
                    '<input class="name-input" type="text" name="defective_file[]" placeholder="Defective File No" autocomplete="off">' +
                    '<input class="name-input" type="text" name="reason_for_defect[]" placeholder="Reason" autocomplete="off">' +
                    '<button type="button" class="remove-field btn" style="width: 150px; height:45px">Remove</button>' +
                    '</div>';
                $("#defective-files-container").append(newField);
            });

            $("#defective-files-container").on("click", ".remove-field", function () {
                $(this).closest(".defective-file-entry").remove();
            });
        });
    </script>
    
    </div>
</form>



</body>
</html>
