<?php

$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "student_registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_answers = [];
    
    // Loop through the submitted answers
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'question_') === 0) {
            $question_id = str_replace('question_', '', $key);
            $student_answers[$question_id] = $value;
        }
    }

    // Save student answers to the database
    foreach ($student_answers as $question_id => $answer) {
        $sql = "INSERT INTO student_answers (question_id, selected_answer) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $question_id, $answer);

        if (!$stmt->execute()) {
            echo "Error saving answer: " . $stmt->error;
        }
    }

    // Calculate the score
    $correct_count = 0;
    $total_questions = count($student_answers);

    foreach ($student_answers as $question_id => $selected_answer) {
        // Fetch the correct answer from the database
        $sql = "SELECT correct_answer FROM questions WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $stmt->bind_result($correct_answer);
        $stmt->fetch();
        $stmt->close();

        // Check if the selected answer is correct
        if ($selected_answer == $correct_answer) {
            $correct_count++;
        }
    }

    // Calculate total marks
    $total_marks = $correct_count; // Assuming 1 mark per correct answer


    echo "You answered <strong>$correct_count</strong> out of <strong>$total_questions</strong> questions correctly.<br>";
    echo "Your total marks: <strong>$total_marks</strong>";


        // it return to the exam's login page
        echo '<br><br><a href="login.html">Back to Exam</a>';
}


$conn->close();
?>
