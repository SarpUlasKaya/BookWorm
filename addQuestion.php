<?php
    session_start();
    include("config.php");

    $myQuizID = $_GET['quizID'];
    //get quiz from quiz table
    $getQuizQuery = "SELECT * FROM quiz WHERE quiz_id = $myQuizID";
    $getQuizQueryResult = $mysqli->query($getQuizQuery);
    $getQuizQueryRow = $getQuizQueryResult->fetch_assoc();
    $maxQuestionCount = $getQuizQueryRow['question_no'];

    //get number of added questions
    $getQuestionCountQuery = "SELECT COUNT(*) AS question_count FROM question WHERE quiz_id = '$myQuizID'";
    $getQuestionCountQueryResult = $mysqli->query($getQuestionCountQuery);
    $getQuestionCountQueryRow = $getQuestionCountQueryResult->fetch_assoc();
    $currentQuestionCount = $getQuestionCountQueryRow['question_count'];

    if(isset($_POST['next'])){
        //insert quiz to question table
        $question = $_POST['question'];
        $optionA = $_POST['optionA'];
        $optionB = $_POST['optionB'];
        $optionC = $_POST['optionC'];
        $optionD = $_POST['optionD'];
        $correctAnsIndex = $_POST['answer'];

        //Insert to question table new tuple
        $insertQuestionQuery = "INSERT INTO question(question_id, quiz_id, question_text, option_A_text, option_B_text, option_C_text, option_D_text, correct_answer_index)
                                    VALUES ($currentQuestionCount+1, $myQuizID,'$question', '$optionA', '$optionB', '$optionC', '$optionD', '$correctAnsIndex')";
        $insertQuestionQueryPrep = $mysqli->prepare($insertQuestionQuery);
        $insertQuestionQueryResult = $insertQuestionQueryPrep->execute();
        $insertQuestionQueryPrep->close();
    }
    //get number of added questions
    $getQuestionCountQuery = "SELECT COUNT(*) AS question_count FROM question WHERE quiz_id = '$myQuizID'";
    $getQuestionCountQueryResult = $mysqli->query($getQuestionCountQuery);
    $getQuestionCountQueryRow = $getQuestionCountQueryResult->fetch_assoc();
    $currentQuestionCount = $getQuestionCountQueryRow['question_count'];
    if(isset($_POST['create'])){
        echo "<script>
            alert('Succesfully created quiz');
            window.location.href='mainMenu.php';
        </script>";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Add a Question</title>
        <h1>Add a Question</h1>
    </head>

    <body>
        <table>
            <?php
            if($currentQuestionCount < $maxQuestionCount){
                echo"<form method = \"post\">
                <tr>
                    <td>Enter the Question</td>
                    <td><input type =\"textarea\" name = \"question\" placeholder =\"Enter your question here\" rows = \"50\"></textarea></td>
                </tr>
                <tr>
                     <td>Option A</td>
                     <td><textarea name = \"optionA\" placeholder =\"Option A\"></textarea></td>
                </tr>
                <tr>
                     <td>Option B</td>
                     <td><textarea name = \"optionB\" placeholder =\"Option B\"></textarea></td>
                </tr>
                <tr>
                     <td>Option C</td>
                     <td><textarea name = \"optionC\" placeholder =\"Option C\"></textarea></td>
                </tr>
                <tr>
                     <td>Option D</td>
                     <td><textarea name = \"optionD\" placeholder =\"Option D\"></textarea></td>
                </tr>
                <tr>
                    <td>Please Select the Correct Answer</td>
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
                echo"<form method='post'><button name='create'>Create Quiz</button></form>";
            }
            ?>
        </table>
    </body>
</html>
