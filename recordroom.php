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
    $batchNumber = $_POST['batchNumber']; 
    $year = $_POST['year']; 
    $count = $_POST['count']; 
    $type = $_POST['type']; 
    $status = $_POST['status']; 
    $comment = $_POST['comment']; 

    $sql = "INSERT INTO batch (batchNumber, year, count, type, status, comment)
            VALUES ('$batchNumber', '$year', '$count', '$type', '$status', '$comment')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['msg'] = "New record created successfully"; 
        header("Location: recordroom.php");
    } else {
        $_SESSION['msg'] = "Error: " . $sql . "<br>" . $conn->error; 
    }

   
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Record Room</title>
    <link rel="icon" type="image/x-icon" href="High-Court-of-Kerala-Logo-fotor-bg-remover-20230926133216.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="style2.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
    <header class="flex justify-between items-center mb-8" style="position:absolute; top:0%; width:100%">
        <div class="flex items-center">
            <img src="highcourt.png" alt="Header Image" class="mr-2" width="3500px" height="400px">
            <button style=" padding: 3px; border-radius:0.2rem;margin-left: -150px; color: white; text-decoration: none; background-color: red; border: none; cursor: pointer;">
    <a href="logout.php" style="color: inherit; text-decoration: none;">Logout</a>
</button>

        </div>
    </header>
    
    <div class=" mx-auto bg-white rounded p-8 shadow-md" style="width:100%; margin-left: 0; margin-right: 0; position:absolute; top:18%;" >
        <h2  class="text-2xl mb-4 font-bold text-center">RECORD ROOM</h2>
        <?php
    if (isset($_SESSION['msg'])) {
        ?>

        <div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong> <?php echo $_SESSION['msg']; ?></strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php
        unset($_SESSION['msg']);
    }
    ?>
        <form id="recordForm" class="space-y-4"  method="POST" >
            <div class="flex space-x-4">
                <div class="flex-1">
                    <label for="batchNumber" class="block mb-2 font-bold text-gray-700">Batch Number</label>
                    <input type="text" id="batchNumber" name="batchNumber" placeholder="Enter the Batch Number " class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500" required autocomplete="off">
                </div>
                <div class="flex-1">
                    <label for="year" class="block mb-2 font-bold text-gray-700">Year</label>
                    <input type="text" id="year" name="year" placeholder="Enter the Year" class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500" required autocomplete="off">
                </div>
            </div>
            <div class="flex space-x-4">
                <div class="flex-1">
                    <label for="type" class="block mb-2 font-bold text-gray-700">Type</label>
                    <input type="text" id="type" name="type" placeholder="Enter the Case type" class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500" required autocomplete="off">
                </div>
                <div class="flex-1">
                    <label for="status" class="block mb-2 font-bold text-gray-700">Status</label>
                    <select id="status" name="status" class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500" required autocomplete="off">
                        <option disabled selected value="">Select Status</option>
                        <option value="weedingroom">Sent for Weeding</option>                        
                    </select>
                </div>

    
            </div>
            <div>
                <label for="count" class="block mb-2 font-bold text-gray-700">Count (Number of Files)</label>
                <input type="number" id="count" name="count" placeholder="Select the Number of Files" class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500" required autocomplete="off">                            </div>
            <div>
                <label for="comment" class="block mb-2 font-bold text-gray-700">Comment</label>
                <textarea id="comment" name="comment" rows="4" class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-300"></textarea>
            </div>
            <div class="flex justify-center">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded " style="background-color: #966144;">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>
