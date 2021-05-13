<?php
    session_start();
    include("config.php");
    //Get questions that belong to this quiz
    $thisQuizID = $_GET['quizID'];

    $questionCountQuery = "SELECT question_no FROM quiz WHERE  quiz_id = '$thisQuizID'";
    $questionCountQueryResult = $mysqli->query($questionCountQuery);
    $questionCountQueryRow = $questionCountQueryResult->fetch_assoc();
    $questionCount = $questionCountQueryRow['question_no'];
    echo"asd"."\r\n";
    $getQuestionsQuery = "SELECT * FROM question WHERE quiz_id = '$thisQuizID'";
    $getQuestionsQueryResult = $mysqli->query($getQuestionsQuery);
    $getQuestionsQueryRow = $getQuestionsQueryResult->fetch_assoc();
    $currentIndex = $getQuestionsQueryRow['question_id'];
    $currentQuestionText = $getQuestionsQueryRow['question_text'];
    $currentQOptA = $getQuestionsQueryRow['option_A_text'];
    $currentQOptB = $getQuestionsQueryRow['option_B_text'];
    $currentQOptC = $getQuestionsQueryRow['option_C_text'];
    $currentQOptD = $getQuestionsQueryRow['option_D_text'];
    echo"current INDEX: " .$currentIndex;

    if(isset($_POST['next'])){
        echo "entered if \r\n";
        $getQuestionsQueryRow = $getQuestionsQueryResult->fetch_assoc();
        $currentIndex = $getQuestionsQueryRow['question_id'];
        $currentQuestionText = $getQuestionsQueryRow['question_text'];
        $currentQOptA = $getQuestionsQueryRow['option_A_text'];
        $currentQOptB = $getQuestionsQueryRow['option_B_text'];
        $currentQOptC = $getQuestionsQueryRow['option_C_text'];
        $currentQOptD = $getQuestionsQueryRow['option_D_text'];
        echo"current Index: " .$currentIndex;
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
    if($currentIndex <= $questionCount){
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