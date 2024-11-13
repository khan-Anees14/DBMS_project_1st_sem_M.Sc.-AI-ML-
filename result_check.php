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
    $username = $_POST['username'];
    $password = $_POST['password'];


    $stmt = $conn->prepare("SELECT id, password FROM users_register WHERE username = ?");
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();


        if (password_verify($password, $hashed_password)) {

            $student_answers = [];
            $sql = "SELECT question_id, selected_answer FROM student_answers WHERE id = ?";
            $stmt_answers = $conn->prepare($sql);
            if ($stmt_answers === false) {
                die("Prepare failed: " . htmlspecialchars($conn->error));
            }

            $stmt_answers->bind_param("i", $user_id);
            $stmt_answers->execute();
            $result = $stmt_answers->get_result();

            while ($row = $result->fetch_assoc()) {
                $student_answers[$row['question_id']] = $row['selected_answer'];
            }
            $stmt_answers->close();


            $correct_count = 0;
            $total_questions = count($student_answers);

            foreach ($student_answers as $question_id => $selected_answer) {

                $sql = "SELECT correct_answer FROM questions WHERE id = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    die("Prepare failed: " . htmlspecialchars($conn->error));
                }

                $stmt->bind_param("i", $question_id);
                $stmt->execute();
                $stmt->bind_result($correct_answer);
                if ($stmt->fetch()) {

                    if ($selected_answer == $correct_answer) {
                        $correct_count++;
                    }
                }
                $stmt->close();
            }

            // Calculate total marks
            $total_marks = $correct_count; // Assuming 1 mark per correct answer

            // Output results
            echo "You answered <strong>$correct_count</strong> out of <strong>$total_questions</strong> questions correctly.<br>";
            echo "Your total marks: <strong>$total_marks</strong>";
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }
    $stmt->close();
}

$conn->close();
?>

<!--************************************** HTML Form *******************************************-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Check Result</title>
    <link rel="stylesheet" href="styles.css"> 
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0ece2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px; 
            margin: 100px auto; 
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 95%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>

<div class="container">
    <form method="POST" action="">
        <h1>Login to Check Result</h1>
        <div class="input-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="input-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
