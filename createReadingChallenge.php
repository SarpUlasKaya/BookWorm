<?php
    session_start();
    include("config.php");
    $librarianID = $_SESSION['userID'];
    if(isset($_POST['submit'])){
        $rcName = $_POST['RCName'];
        $expireDate = $_POST['expireDate'];

        //Add RC to RC table
        $insertRCQuery = "INSERT INTO reading_challenge(name, time_limit, participant_count, books_pledged) 
                                VALUES ('$rcName', '$expireDate', 0, 0)";
        $insertRCQueryPrep = $mysqli->prepare($insertRCQuery);
        $insertRCQueryResult = $insertRCQueryPrep->execute();
        $insertRCQueryPrep->close();
        //Insert relation
        $getLastRowQuery = "SELECT * FROM reading_challenge ORDER BY challenge_id DESC LIMIT 1";
        $getLastRowQueryResult = $mysqli->query($getLastRowQuery);
        $getLastRowQueryRow = $getLastRowQueryResult->fetch_assoc();
        $lastAddedChallengeID = $getLastRowQueryRow['challenge_id'];
        $insertHostRelationQuery = "INSERT INTO hosts(librarian_id, challenge_id) 
                                VALUES ('$librarianID', '$lastAddedChallengeID')";
        $insertHostRelationQueryPrep = $mysqli->prepare($insertHostRelationQuery);
        $insertHostRelationQueryResult = $insertHostRelationQueryPrep->execute();
        $insertHostRelationQueryPrep->close();
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
</head>
<body>
    <div style="position:relative; align-content: center">
        <form method="post">
            <label>Reading Challenge Name: </label>
            <input type="text" name="RCName" placeholder="Name">
            <label>Expire Date: </label>
            <input type="date" name="expireDate" placeholder="Last Day">
            <input type="submit" name="submit" placeholder="Submit">
        </form>
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
                  </tr>";
        }
        ?>
        </table>
    </div>

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
</style>