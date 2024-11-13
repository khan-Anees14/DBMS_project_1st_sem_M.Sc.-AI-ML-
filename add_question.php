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
    $question = $_POST['question-text'];
    $answer1 = $_POST['answer1'];
    $answer2 = $_POST['answer2'];
    $answer3 = $_POST['answer3'];
    $answer4 = $_POST['answer4'];
    $correct_answer = $_POST['correct-answer'];

    $sql = "INSERT INTO questions (question, answer1, answer2, answer3, answer4, correct_answer) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $question, $answer1, $answer2, $answer3, $answer4, $correct_answer);

    if ($stmt->execute()) {
        echo "New question added successfully! <br> Go Back to Admin Panel to add more questions";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
//    header("Location: admin.html");  Redirect back to the admin panel
exit;
