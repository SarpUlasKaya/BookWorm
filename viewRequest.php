<?php
    session_start();
    include("config.php");
    $thisUserID = $_SESSION['userID'];

    $selectedRequestID = null;
    if(!empty($_GET['selectedRequestID'])){
        $selectedRequestID = $_GET['selectedRequestID'];
    }
    //Delete selected request when delete selected
    if( isset($_POST['deleteReq'])) {
        //delete from concerns
        $deleteRequestQuery = "DELETE FROM request WHERE request_id = $selectedRequestID";
        $deleteRequestQueryPrep = $mysqli->prepare($deleteRequestQuery);
        $deleteRequestQueryResult = $deleteRequestQueryPrep->execute();
        $deleteRequestQueryPrep->close();
        $selectedRequestID = null;
    }
    //Add accepts relation tuple if request accepted
    if( isset($_POST['acceptReq'])) {
        $insertAcceptsQuery = "INSERT INTO accepts(librarian_id, request_id) 
                                VALUES ('$thisUserID', '$selectedRequestID')";
        $insertAcceptsQueryPrep = $mysqli->prepare($insertAcceptsQuery);
        $insertAcceptsQueryResult = $insertAcceptsQueryPrep->execute();
        $insertAcceptsQueryPrep->close();
        header("location:editBook.php?requestID=".urlencode($selectedRequestID));
    }

    //Get the selected request
    $getSelectedRequest = "SELECT * FROM request WHERE request_id = '$selectedRequestID'";
    $getSelectedRequestResult = $mysqli->query($getSelectedRequest);
    $getSelectedRequestRow = $getSelectedRequestResult->fetch_assoc();

    //Get all request that are not accepted previously
    $getAllReqQuery = "SELECT * FROM unanswered_requests";
    $getAllReqQueryResult = $mysqli->query($getAllReqQuery);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Requests</title>
</head>
<body>
<div style="width: 49%; position: absolute; top: 0px; left: 50px;">

<?php
    echo"<h1>All Waiting Requests</h1>
                <table style=\"width:75%\">
                <tr>
                    <th>Request ID</th>
                    <th>Title</th>          
                </tr>";
    while($getAllReqQueryRow = $getAllReqQueryResult->fetch_assoc())
    {
        echo "<tr>
                <td>".$getAllReqQueryRow['request_id']."</td>
                <td>".$getAllReqQueryRow['title']."</td>
                <td>
                    <form style='margin-top: 10px; margin-bottom: 5px' method='post' action='viewRequest.php?selectedRequestID=" . urlencode($getAllReqQueryRow['request_id']) . "'>
                        <button class='btn' style='font-size: 8px; padding: 7px 16px;' name='joinChallenge'>Details</button>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
?>
</div>
<div style="width: 49%; position: absolute; top: 0px; right: 50px;">
    <?php
        if(!empty($selectedRequestID)) {
            echo "<h1>Request Details</h1>
                    <h3>Request Title:<span><h4>".$getSelectedRequestRow['title']."</h4></span></h3>
                    <h3>Request Body:<span><h4>".$getSelectedRequestRow['text']."</h4></span></h3>
                    <form method='post'>
                        <button class='btn' name='acceptReq'>Accept</button>
                    </form>
                    <form method='post'>
                        <button class='btn' style='background-color: red' name='deleteReq'>Delete</button>
                    </form>
            ";
        }
    ?>
</div>

<a style ="position: absolute; bottom: 0px; right: 0px;"href="mainMenu.php">Main Menu</a>
<br></br>
</body>
</html>

<style>
    .btn {
        background-color: cadetblue;
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
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