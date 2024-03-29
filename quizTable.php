<?php
    session_start();
    include("config.php");

    //Get all quizes from db
    $getAllQuizQuery = "SELECT * FROM quiz";
    $getAllQuizQueryResult = $mysqli->query($getAllQuizQuery);
    $questionID = 1;
    $quizScore = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Solve a Quiz</title>
    <a style ="position: absolute; bottom: 0px; right: 0px;"href="mainMenu.php">Main Menu</a>
    <br></br>
    <h1>Solve a Quiz</h1>
</head>

<body>
    <?php
    echo"   <table style=\"width:100%\">
            <tr>
                <th>Quiz Name</th>
                <th>Quiz ID</th>          
                <th>Average Score</th>
                <th>Question No</th> 
                <th>Attempt No</th> 
            </tr>";
    while($getAllQuizQueryRow = $getAllQuizQueryResult->fetch_assoc())
    {
        echo "<tr>
                <td><a href=\"solveQuiz.php?quizID=" . urlencode($getAllQuizQueryRow['quiz_id']). "&questionID=" . urlencode($questionID).  "&quizScore=" . urlencode($quizScore). "\">" . $getAllQuizQueryRow['name'] . "</a></td>
                <td>".$getAllQuizQueryRow['quiz_id']."</td>
                <td>".$getAllQuizQueryRow['average_score'] ."</td>
                <td>".$getAllQuizQueryRow['question_no']."</td>
                <td>".$getAllQuizQueryRow['attempt_no']."</td>
              </tr>";
    }
    echo "</table>";
    ?>
</body>
</html>

<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    th, td {
        padding: 5px;
        text-align: left;
    }
    a:link, a:visited {
      background-color: #f44336;
      color: white;
      padding: 14px 25px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
    }

    a:hover, a:active {
      background-color: red;
    }
</style>