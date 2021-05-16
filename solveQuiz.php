<?php
    session_start();
    include("config.php");

    $thisUserID = $_SESSION['userID'];

    //Get questions that belong to this quiz
    $thisQuizID = $_GET['quizID'];
    $questionID = $_GET['questionID'];
    $quizScore = $_GET['quizScore'];
    //echo "current Index: " . $questionID;

    $questionCountQuery = "SELECT question_no FROM quiz WHERE  quiz_id = '$thisQuizID'";
    $questionCountQueryResult = $mysqli->query($questionCountQuery);
    $questionCountQueryRow = $questionCountQueryResult->fetch_assoc();
    $questionCount = $questionCountQueryRow['question_no'];
    //echo "question count: " . $questionCount;

    $getQuestionsQuery = "SELECT * FROM question WHERE quiz_id = '$thisQuizID' AND question_id = '$questionID'";
    $getQuestionsQueryResult = $mysqli->query($getQuestionsQuery);
    if ( $getQuestionsQueryRow = $getQuestionsQueryResult->fetch_assoc() ) {
        $questionID = $getQuestionsQueryRow['question_id'];
        $currentQuestionText = $getQuestionsQueryRow['question_text'];
        $currentQOptA = $getQuestionsQueryRow['option_A_text'];
        $currentQOptB = $getQuestionsQueryRow['option_B_text'];
        $currentQOptC = $getQuestionsQueryRow['option_C_text'];
        $currentQOptD = $getQuestionsQueryRow['option_D_text'];
        if (isset($_POST['next'])) {
            if ($_POST['answer'] == $getQuestionsQueryRow['correct_answer_index']) {
                $quizScore++;
            }
            $questionID++;
            header("location: solveQuiz.php?quizID=" . urlencode($thisQuizID) . "&questionID=" . urlencode($questionID) . "&quizScore=" . urlencode($quizScore));
        }
    }
    if (isset($_POST['finish'])) {
        //INSERT INTO SOLVES RELATION
        $insertSolvesQuery = "INSERT INTO solves(user_id, quiz_id, score) 
                                    VALUES ('$thisUserID', '$thisQuizID', '$quizScore')";
        $insertSolvesQueryPrep = $mysqli->prepare($insertSolvesQuery);
        $insertSolvesQueryResult = $insertSolvesQueryPrep->execute();
        $insertSolvesQueryPrep->close();

        //UPDATE QUIZ ATTEMPT NO
        $updateAttemptNoQuery = "UPDATE quiz SET attempt_no = attempt_no + 1 WHERE quiz.quiz_id = '$thisQuizID'";
        $updateAttemptNoQueryPrep = $mysqli->prepare($updateAttemptNoQuery);
        $updateAttemptNoQueryResult = $updateAttemptNoQueryPrep->execute();
        $updateAttemptNoQueryPrep->close();

        //UPDATE QUIZ AVERAGE SCORE
        $updateAvgQuery = "UPDATE quiz SET average_score = (average_score * (attempt_no-1) + '$quizScore')/attempt_no WHERE quiz.quiz_id = '$thisQuizID'";
        $updateAvgQueryPrep = $mysqli->prepare($updateAvgQuery);
        $updateAvgQueryResult = $updateAvgQueryPrep->execute();
        $updateAvgQueryPrep->close();

        header("location: mainMenu.php");
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Solve Quiz</title>
    <h1>Solve Quiz</h1>
</head>
<body>
<table>
    <?php
    if($questionID <= $questionCount){
        echo"<form method='post'>
                <tr>
                    <td>Question:</td>
                    <td>". $currentQuestionText . "</td>
                </tr>
                <tr>
                     <td>Option A</td>
                     <td>".$currentQOptA."</td>
                </tr>
                <tr>
                     <td>Option B</td>
                     <td>".$currentQOptB."</td>
                </tr>
                <tr>
                     <td>Option C</td>
                     <td>".$currentQOptC."</td>
                </tr>
                <tr>
                     <td>Option D</td>
                     <td>".$currentQOptD."</td>
                </tr>
                <tr>
                    <td>Please Select the Correct Answer</td><br>
                    <td><input type = \"radio\" name = \"answer\" value = \"1\">
                    <label for = \"correctA\">Option A</label><br>
                    <input type = \"radio\" name = \"answer\" value = \"2\">
                    <label for = \"correctB\">Option B</label><br>
                    <input type = \"radio\" name = \"answer\" value = \"3\">
                    <label for = \"correctC\">Option C</label><br>
                    <input type = \"radio\" name = \"answer\" value =\"4\">
                    <label for = \"correctD\">Option D</label><br>
                    </td>
                </tr>
                <td><button name='next''>Next Question</button></td>              
             </form>";
    }
    else{
        echo "You have answered all of the questions, your score is " . $quizScore . " out of " . $questionCount . ".<br><br>";
        echo "<form method='post'><button name='finish'>Finish Attempt</button></form>";
    }
    ?>
</table>
</body>
</html>