<?php

$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "student_registration";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['id']) && filter_var($_POST['id'], FILTER_VALIDATE_INT)) {
        $id = (int)$_POST['id'];

        $sql = "DELETE FROM questions WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {

                    echo "provided question number deleted successfully! <br> 
                        Go Back to Admin Panel to delete more questions";
                
                // Redirect to the admin panel
                //header("Location: admin.html");
               // exit;
            } else {
                
                error_log("Error executing query: " . $stmt->error);
                
            }
            $stmt->close();
        } 
        else {
            error_log("Error preparing statement: " . $conn->error);
        }
    }
     else {
        
        error_log("Invalid ID input.");
    }
}

$conn->close();
