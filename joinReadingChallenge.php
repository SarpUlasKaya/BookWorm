<?php
session_start();
include("config.php");
$thisUserID = $_SESSION['userID'];

$selectedChallengeID = null;
if(!empty($_GET['selectedChallengeID'])){
    $selectedChallengeID = $_GET['selectedChallengeID'];
}

//Get the selected challenge
$getSelectedChallenge = "SELECT * FROM reading_challenge WHERE challenge_id = '$selectedChallengeID'";
$getSelectedChallengeResult = $mysqli->query($getSelectedChallenge);
$getSelectedChallengeRow = $getSelectedChallengeResult->fetch_assoc();

if(isset($_POST['takeChallenge'])){
    if (!empty($_POST['bookGoal'])) {
        $userGoal = $_POST['bookGoal'];
        //echo"Challenge ID: " . $selectedChallengeID;
        //echo "User GOAL: " . $userGoal;
        //Check if user previously joined
        $searchUserQuery = "SELECT * FROM joins WHERE user_id = '$thisUserID' AND challenge_id = $selectedChallengeID";
        $searchUserQueryResult = $mysqli->query($searchUserQuery);
        if ($searchUserQueryResult->num_rows == 1) {
            echo "<script>alert(\"You have joined this challenge before.\");</script>";
        } else {
            //Add new joins relation tuple
            $insertJoinsQuery = "INSERT INTO joins(user_id, challenge_id, goal) 
                                VALUES ('$thisUserID', '$selectedChallengeID', $userGoal)";
            $insertJoinsQueryPrep = $mysqli->prepare($insertJoinsQuery);
            $insertJoinsQueryResult = $insertJoinsQueryPrep->execute();
            $insertJoinsQueryPrep->close();
            //update the reading challenge's pledged book count
            $updateRCQuery = "UPDATE reading_challenge SET books_pledged = books_pledged + '$userGoal' WHERE reading_challenge.challenge_id = '$selectedChallengeID'";
            $updateRCQueryPrep = $mysqli->prepare($updateRCQuery);
            $updateRCQueryResult = $updateRCQueryPrep->execute();
            $updateRCQueryPrep->close();
            //update participant count
            $updateRCQuery = "UPDATE reading_challenge SET participant_count = participant_count + 1 WHERE reading_challenge.challenge_id = '$selectedChallengeID'";
            $updateRCQueryPrep = $mysqli->prepare($updateRCQuery);
            $updateRCQueryResult = $updateRCQueryPrep->execute();
            $updateRCQueryPrep->close();
        }
    }
    else {
        echo "<script>alert(\"Please pledge a valid number of books to join the challenge.\");</script>";
    }
}
//display current challenges
$getAllChallengesQuery = "SELECT * FROM reading_challenge";
$getAllChallengesQueryResult = $mysqli->query($getAllChallengesQuery);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>
        Reading Challenge
    </title>
    <h1> Join a Reading Challenge </h1>
    <br></br>

</head>
<body>

    <div style="width: 49%; position: relative; top: 0px; left: 50px;">
        <h1>All Current Challenges:</h1>
        <table style="margin-top: 50px;">
            <tr>
                <th>Reading Challenge Name</th>
                <th>Participants</th>
                <th>Books Pledged</th>
                <th>Expire Date</th>
            </tr>
            <?php
            while ($getAllChallengesQueryRow =  $getAllChallengesQueryResult->fetch_assoc()) {
                echo "<tr>
                    <td>" . $getAllChallengesQueryRow['name'] . "</td>
                    <td>" . $getAllChallengesQueryRow['participant_count'] . "</td>
                    <td>" . $getAllChallengesQueryRow['books_pledged'] . "</td>
                    <td>" . $getAllChallengesQueryRow['time_limit'] . "</td>
                    <td>
                        <form style='margin-top: 10px; margin-bottom: 5px' method='post' action='joinReadingChallenge.php?selectedChallengeID=" . urlencode($getAllChallengesQueryRow['challenge_id']) . "'>
                            <button class='btn' name='joinChallenge'>Join</button>
                        </form>
                    </td>
                  </tr>";

            }
            ?>
        </table>
    </div>
    <div style="width: 49%; position: absolute; top: 0px; right: 50px;">
        <?php
        if(!empty($selectedChallengeID)) {
            //User is in his/her profile can create posts
            echo "<h1>".$getSelectedChallengeRow['name']."</h1>
                        <label>Participants: </label><b></b>".$getSelectedChallengeRow['participant_count']."
                        <br>
                        <label>Books Pledged: </label>".$getSelectedChallengeRow['books_pledged']."
                        <br>
                        <label>Expire Date: </label>".$getSelectedChallengeRow['time_limit']."
                        <br>
                        ";
            echo"<div style='border: 1px solid black; border-collapse: collapse; margin-top: 50px; width: 50%; padding: 20px;'>
                     <form method='post'>
                        <h4>Number of books to pledge: <span><input type='number' name='bookGoal' style='width: 40px;'></span></h4>
                        <button class='btn' name='takeChallenge'>Take the Challenge</button>
                     </form>
                     </div>";
        }
        ?>
    </div>

<a style ="position: absolute; bottom: 0px; right: 0px;"href="mainMenu.php">Main Menu</a>
<br></br>
</body>

<style>
    .btn {
        background-color: cadetblue;
        border: none;
        color: white;
        padding: 7px 16px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 8px;
    }
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