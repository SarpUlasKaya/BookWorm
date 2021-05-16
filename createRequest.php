<?php
    session_start();
    include("config.php");
    $thisUserID = $_SESSION['userID'];
    if(isset($_POST['submitReq'])){
        if (!empty($_POST['reqTitle']) && !empty($_POST['reqBody']) && !empty($_POST['selected'])) {
            $reqTitle = $_POST['reqTitle'];
            $reqBody = $_POST['reqBody'];
            //Insert into request table new tuple
            $insertRequestQuery = "INSERT INTO request(text, title) 
                                        VALUES ('$reqBody', '$reqTitle')";
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
            //Select the book that req is concerned about
            $bookInfo = $_POST['selected'];
            $bookInfo = explode("#", $bookInfo);
            $bookID = $bookInfo[0];
            $bookEditionNo = $bookInfo[1];
            $bookPublisher = $bookInfo[2];
            //Insert to concerns relation
            $insertConcernsQuery = "INSERT INTO concerns(request_id, book_id, edition_no, publisher) 
                                    VALUES ('$lastAddedReqID','$bookID', '$bookEditionNo', '$bookPublisher')";
            $insertConcernsQueryPrep = $mysqli->prepare($insertConcernsQuery);
            $insertConcernsQueryResult = $insertConcernsQueryPrep->execute();
            $insertConcernsQueryPrep->close();

            echo "<script>
                alert('Successfully Sent Request');
                window.location.href='mainMenu.php';
            </script>";
        }
        else {
            echo "<script>alert(\"Please fill out the fields and indicate which book your request concerns before submitting it.\");</script>";
        }
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
<form method="post">
<div style="width: 49%; position: absolute; top: 0px; left: 100px;">

    <h1>Create a Request</h1>
        <label>Title:</label><br>
        <input type="text" name="reqTitle" placeholder="Enter title"><br>
        <div style="margin-top: 50px;">
            <label>Request:</label><br>
            <textarea type="text" name="reqBody" rows="15" cols="60" placeholder="Enter request body"></textarea><br>
        </div>
        <button style="margin-top: 25px;" name='submitReq' class="btn">Submit</button>
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
                        <td><input type = \"radio\" name = \"selected\" value=\"".$getAllBooksQueryRow["book_id"]."#". $getAllBooksQueryRow["edition_no"]."#". $getAllBooksQueryRow["publisher"]."\"></td>
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
</form>
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
