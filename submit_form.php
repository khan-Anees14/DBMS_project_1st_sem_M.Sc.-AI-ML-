<?php

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "student_registration";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $stmt = $conn->prepare("INSERT INTO registrations (username, course, name, father, mother, dob, email, phone, gender, qualification, percentage, maths) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssss", $_POST['username'], $_POST['course'], $_POST['name'], $_POST['father'], $_POST['mother'], $_POST['DOB'], $_POST['email'], $_POST['phone'], $_POST['gender'], $_POST['qualification'], $_POST['percentage'], $_POST['maths']);

    
    if ($stmt->execute()) {
        echo "CONGRATULATIONS.....YOU HAVE COMPLETED YOUR REGISTRATION FORM SUCCESFULLY ....";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}


$result = $conn->query("SELECT COUNT(*) AS count FROM registrations");
$row = $result->fetch_assoc();
echo "<br>Number of students registered: " . $row['count'];

$conn->close();
?>
