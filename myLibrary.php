<?php
session_start();
include("config.php");

$userID = $_SESSION['userID'];

if(isset($_POST['addNewBookList'])){
    $newBookListName = $_POST['newBookListName'];
    //Create new Booklist
    $createBookListQuery = "INSERT INTO book_list(name, created_at, book_count) 
                                VALUES ('$newBookListName', NOW(), 0)";
    $createBookListQueryPrep = $mysqli->prepare($createBookListQuery);
    $createBookListQueryResult = $createBookListQueryPrep->execute();
    $createBookListQueryPrep->close();

    //Get Lastly Created bookList
    $getLastRowBookListIDQuery = "SELECT book_list_id FROM book_list ORDER BY book_list_id DESC LIMIT 1";
    $getLastRowBookListIDQueryResult = $mysqli->query($getLastRowBookListIDQuery);
    $getLastRowBookListIDQueryRow = $getLastRowBookListIDQueryResult->fetch_assoc();
    $mostRecentBookListID = $getLastRowBookListIDQueryRow['book_list_id'];

    //Create new prepares relation
    $createPreparesQuery = "INSERT INTO prepares(user_id, book_list_id) 
                                VALUES ('$userID',$mostRecentBookListID)";
    $createPreparesQueryPrep = $mysqli->prepare($createPreparesQuery);
    $createPreparesQueryResult = $createPreparesQueryPrep->execute();
    $createPreparesQueryPrep->close();

    header("location: myLibrary.php");
}

//Get All BookList Table and Display
$getBookListsQuery = "SELECT * FROM book_list INNER JOIN prepares ON prepares.book_list_id = book_list.book_list_id WHERE prepares.user_id = '$userID'";
$getBookListsQueryResult = mysqli_query($mysqli, $getBookListsQuery);
$getBookListsQueryRowCount = mysqli_num_rows( $getBookListsQueryResult);
?>

<!DOCTYPE html>
<html>
<head>
    <title>MyLibrary</title>
</head>
<body align = \"CENTER\">
    <div style="width: 49%; position: absolute; top: 0px; left: 150px;">
        <h1>My Libraries</h1>
        <table style=\"width:75%\">
            <tr>
                <th>Book List Name</th>
                <th>Created At</th>
                <th>Book Count</th>
                <th>Book List ID</th>
            </tr>
            <?php
            if( $getBookListsQueryRowCount > 0)
            {
                while( $getBookListsQueryRow = mysqli_fetch_assoc($getBookListsQueryResult))
                {
                    echo "<tr>
                                <td><a href=\"editBookList.php?bookListId=" . urlencode($getBookListsQueryRow['book_list_id']) . "\">" . $getBookListsQueryRow['name'] . "</a></td>
                                <td>".$getBookListsQueryRow['created_at']."</td>
                                <td>".$getBookListsQueryRow['book_count']."</td>
                                <td>".$getBookListsQueryRow['book_list_id']."</td>
                        </tr>";
                }
                echo "</table>";
            }
            ?>
    </div>
    <div style="width: 49%; position: absolute; top: 0px; left: 750px;">
        <h1>Create New Library</h1>
        <form method="post">
            <label>ADD New Book List<input type="text" name="newBookListName"/></label> <br><br>
            <input type="submit" name="addNewBookList" value="Submit" />
        </form>

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
