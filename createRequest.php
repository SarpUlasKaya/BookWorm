<?php
    session_start();
    include("config.php");
    $thisUserID = $_SESSION['userID'];
    if(isset($_POST['submitReq'])){
        $reqTitle = $_POST['reqTitle'];
        $reqBody = $_POST['reqBody'];
        //Insert into request table new tuple
        $insertRequestQuery = "INSERT INTO request(text) 
                                        VALUES ('$reqBody')";
        $insertRequestQueryPrep = $mysqli->prepare($insertRequestQuery);
        $insertRequestQueryResult = $insertRequestQueryPrep->execute();
        $insertRequestQueryPrep->close();
        //Get last added request id
        $getLastReqQuery = "SELECT request_id FROM request ORDER BY request_id DESC LIMIT 1";
        $getLastReqQueryResult = $mysqli->query($getLastReqQuery);
        $getLastReqQueryRow = $getLastReqQueryResult->fetch_assoc();
        $lastAddedReqID = $getLastReqQueryRow['request_id'];
        //Insert to sends relation
        $insertSendsQuery = "INSERT INTO sends(user_id, request_id) 
                                    VALUES ('$thisUserID','$lastAddedReqID')";
        $insertSendsQueryPrep = $mysqli->prepare($insertSendsQuery);
        $insertSendsQueryResult = $insertSendsQueryPrep->execute();
        $insertSendsQueryPrep->close();

        echo "<script>
            alert('Successfully Sent Request');
            window.location.href='mainMenu.php';
        </script>";
    }
    //List all books
    $getAllBooksQuery = "SELECT * FROM books NATURAL JOIN edition";
    $getAllBooksQueryResult = $mysqli->query($getAllBooksQuery);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create a Request</title>
</head>
<body>
<div style="width: 49%; position: absolute; top: 0px; left: 100px;">
    <h1>Create a Request</h1>
    <form method="post">
        <label>Title:</label><br>
        <input type="text" name="reqTitle" placeholder="Enter title"><br>
        <div style="margin-top: 50px;">
            <label>Request:</label><br>
            <textarea type="text" name="reqBody" rows="15" cols="60" placeholder="Enter request body"></textarea><br>
        </div>
        <button style="margin-top: 25px;" name='submitReq' class="btn">Submit</button>
    </form>
</div>
<div style="width: 49%; position: absolute; top: 0px; right: 100px;">
    <?php
    echo"<h2>Concerns</h2>
            <table style=\"width:100%\">
            <tr>
                <td> </td>
                <th>Book Name</th>
                <th>Year</th>          
                <th>Genre</th>
                <th>ID</th> 
                <th>Edition No</th>
                <th>Publisher</th>   
                <th>Page Count</th>   
            </tr>";
        while($getAllBooksQueryRow = $getAllBooksQueryResult->fetch_assoc())
        {
            echo "<tr>
                        <td><input type = \"radio\" name = \"selected\" value=\"".$getAllBooksQueryRow["book_id"]."\"></td>
                        <td>".$getAllBooksQueryRow['title']."</td>
                        <td>".$getAllBooksQueryRow['year']."</td>
                        <td>".$getAllBooksQueryRow['genre']."</td>
                        <td>".$getAllBooksQueryRow['book_id']."</td>
                        <td>".$getAllBooksQueryRow['edition_no']."</td>
                        <td>".$getAllBooksQueryRow['publisher']."</td>
                        <td>".$getAllBooksQueryRow['page_count']."</td>
                    </tr>";
        }
        echo "</table>";
    ?>
</div>
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
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    th, td {
        padding: 5px;
        text-align: left;
    }
</style>
