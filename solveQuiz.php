<?php
    session_start();
    include("config.php");
    //Get questions that belong to this quiz
    $thisQuizID = $_GET['quizID'];
    $questionID = $_GET['questionID'];
    echo "current Index: " . $questionID;

    $questionCountQuery = "SELECT question_no FROM quiz WHERE  quiz_id = '$thisQuizID'";
    $questionCountQueryResult = $mysqli->query($questionCountQuery);
    $questionCountQueryRow = $questionCountQueryResult->fetch_assoc();
    $questionCount = $questionCountQueryRow['question_no'];
    echo "question count: " . $questionCount;

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
            //CEVAP KONTROL EDİLECEK
            $questionID++;
            header("location: solveQuiz.php?quizID=" . urlencode($thisQuizID) . "&questionID=" . urlencode($questionID));
        }
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
        echo"<form method='post'><button name='finish'>Finish Attempt</button></form>";
    }
    ?>
</table>
</body>
</html>