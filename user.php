<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="coun.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="main-block">
      <h1>Registration</h1>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
        <hr>
        <div class="agegap">
          <input type="radio" value="0-15" id="radioOne" name="age" checked/> 
          <label for="radioOne" class="radio">0-15</label>
          <input type="radio" value="15-25" id="radioTwo" name="age" /> 
          <label for="radioTwo" class="radio">15-25</label>
          <input type="radio" value="25+" id="radioThree" name="age" />
          <label for="radioThree" class="radio">25+</label>
        </div>
        <hr>
        <label for="tel"><i class="fas fa-envelope"></i></label> 
        <input type="tel" name="tel" id="tel" placeholder="phone" required/>
        <label for="name"><i class="fas fa-user"></i></label>
        <input type="text" name="name" id="name" placeholder="Name" required/>
        <label for="problem"><i class="fas fa-unlock-alt"></i></label> 
        <input type="text" name="problem" id="problem" placeholder="Problem(just a brief)" required/>
        <hr>
        <div class="gender">
          <input type="radio" value="Male" id="male" name="gender" checked/> 
          <label for="male" class="radio">Male</label>
          <input type="radio" value="Female" id="female" name="gender" />
          <label for="female" class="radio">Female</label>
        </div>
        <hr>
        <div class="btn-block">
          <button type="submit">Submit</button> 
        </div>
      </form>
    </div>
    <?php
session_start();
if (!isset($_SESSION['page_loaded'])) {
    $_SESSION['page_loaded'] = true;
} else {
    session_regenerate_id(true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $phone = $_POST["tel"];
    $problem = $_POST["problem"];
    $gender = $_POST["gender"];
    $age = $_POST["age"];
    
    // Create a database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "psychology";
    $conn = mysqli_connect($servername, $username, $password, $database);
    
    // Check if the connection was successful
    if (!$conn) {
        echo '<div class="alert alert-danger" role="alert">Sorry, unable to connect</div>';
    } else {
        // Determine the table name based on the selected age group
        if ($age == "0-15") {
            $tableName = "age0-15"; // Use underscores instead of hyphens
        } elseif ($age == "15-24") {
            $tableName = "age15-24"; // Use underscores instead of hyphens
        } else {
            $tableName = "age24+"; // Use underscores instead of hyphens
        }
        
        // Prepare and execute the SQL query using prepared statements to prevent SQL injection
        $sql = "INSERT INTO `$tableName`(`name`, `phone`, `problem`, `gender`) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ssss", $name, $phone, $problem, $gender);
        
        // Execute the statement
        $result = mysqli_stmt_execute($stmt);
        
        if ($result) {
            // Display the success message and unset the session variable
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert" id="alert">
                <strong>Submitted successfully</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            unset($_SESSION['success_message']); // Unset the success message session variable
        } else {
            echo '<div class="alert alert-danger" role="alert">Error: ' . mysqli_error($conn) . '</div>';
        }
        
        // Close the statement and database connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}
?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js">
            setTimeout(function() {
        document.getElementById('alert').style.display = 'none';
    }, 5000); // 5000 milliseconds (5 seconds)
    </script>
</body>
</html>
