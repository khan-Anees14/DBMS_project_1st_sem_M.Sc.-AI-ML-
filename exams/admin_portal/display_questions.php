<?php

$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "student_registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM questions";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='question-item'>";
        echo "<p><strong>Q:</strong> " . htmlspecialchars($row['question']) . "</p>";
        echo "<p>1. " . htmlspecialchars($row['answer1']) . "</p>";
        echo "<p>2. " . htmlspecialchars($row['answer2']) . "</p>";
        echo "<p>3. " . htmlspecialchars($row['answer3']) . "</p>";
        echo "<p>4. " . htmlspecialchars($row['answer4']) . "</p>";
        echo "<p><strong>Correct Answer:</strong> " . $row['correct_answer'] . "</p>";
        echo "<form method='POST' action='delete_question.php'>";
        echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
        echo "<button type='submit'>Delete Question</button>";
        echo "</form>";
        echo "</div>";
    }
} else {
    echo "No questions found.";
}

$conn->close();
?>
