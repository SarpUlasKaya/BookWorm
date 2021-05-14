<?php
    session_start();
    include("config.php");
    $thisUserID = $_SESSION['userID'];
    $lastAddedQuizID = -1;
    if(isset($_POST['continue'])){
        $quizName = $_POST['quizName'];
        $questionCount = $_POST['questionCount'];
        //Insert into quiz table new quiz tuple
        $insertQuizQuery = "INSERT INTO quiz(name, average_score, question_no, attempt_no) 
                                    VALUES ('$quizName', 0, '$questionCount', 0)";
        $insertQuizQueryPrep = $mysqli->prepare($insertQuizQuery);
        $insertQuizQueryResult = $insertQuizQueryPrep->execute();
        $insertQuizQueryPrep->close();
        //Get last added quiz id
        $getLastQuizQuery = "SELECT quiz_id FROM quiz ORDER BY quiz_id DESC LIMIT 1";
        $getLastQuizQueryResult = $mysqli->query($getLastQuizQuery);
        $getLastQuizQueryRow = $getLastQuizQueryResult->fetch_assoc();
        $lastAddedQuizID = $getLastQuizQueryRow['quiz_id'];
        //Insert to creates relation
        $insertCreatesQuery = "INSERT INTO creates(user_id, quiz_id) 
                                    VALUES ('$thisUserID','$lastAddedQuizID')";
        $insertCreatesQueryPrep = $mysqli->prepare($insertCreatesQuery);
        $insertCreatesQueryResult = $insertCreatesQueryPrep->execute();
        $insertCreatesQueryPrep->close();

        header("location: addQuestion.php?quizID=".urlencode($lastAddedQuizID));
    }


?>
<!DOCTYPE html>
<html>
    <head>
        <title>Create a Quiz</title>
        <h1>Create a Quiz</h1>
    </head>

    <body>
    <?php
        echo "<form method=\"post\">
            <label> Enter quiz name: <span><input name =\"quizName\" type=\"text\"></span></label><br>
            <label> Enter question count: <span><input name =\"questionCount\" type=\"number\"></span></label><br>
            <button style='margin-top: 50px' name=\"continue\" class=\"btn\">Continue</button>
        </form>";
    ?>
    <a style ="position: absolute; bottom: 0px; right: 0px;"href="mainMenu.php">Main Menu</a>
    <br></br>
    </body>

</html>
<style>
    .btn {
        background-color: cadetblue;
        border: none;
        color: white;
        padding: 10px 25px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 13px;
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

