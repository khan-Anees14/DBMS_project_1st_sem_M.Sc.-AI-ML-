<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "student_registration");
$questions = $mysqli->query("SELECT * FROM questions ORDER BY RAND() LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam</title>
    <link rel="stylesheet" href="exam.css"> 
</head>
<body>
    <form action="submit_exam.php" method="POST">
        <h2>Exam Questions</h2>
        <?php while ($question = $questions->fetch_assoc()): ?>
            <div class="question">
                <p><?php echo $question['question']; ?></p>
                <label><input type="radio" name="question_<?php echo $question['id']; ?>" value="1"> <?php echo $question['answer1']; ?></label><br>
                <label><input type="radio" name="question_<?php echo $question['id']; ?>" value="2"> <?php echo $question['answer2']; ?></label><br>
                <label><input type="radio" name="question_<?php echo $question['id']; ?>" value="3"> <?php echo $question['answer3']; ?></label><br>
                <label><input type="radio" name="question_<?php echo $question['id']; ?>" value="4"> <?php echo $question['answer4']; ?></label><br>
            </div>
        <?php endwhile; ?>
        <button type="submit">Submit Exam</button>
        
    </form>
</body>
</html>
