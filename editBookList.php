<?php
session_start();
include("config.php");

$bookListID = $_GET['bookListId'];
$userID = $_SESSION['userID'];

if (!empty($_GET['bookToAddId']) && !empty($_GET['bookToAddEditionNo']) && !empty($_GET['bookToAddPublisher'])) {
    $bookToAddID = $_GET['bookToAddId'];
    $bookToAddEditionNo = $_GET['bookToAddEditionNo'];
    $bookToAddPublisher = $_GET['bookToAddPublisher'];
    //echo "ID of book to add: " . $bookToAddID . ", edition of book to add: " . $bookToAddEditionNo .
    //    ", publisher of book to add: " . $bookToAddPublisher;

    //Search if book exists in member_of
    $searchMemberOfQuery = "SELECT * FROM member_of WHERE member_of.book_id = '$bookToAddID' AND member_of.edition_no = '$bookToAddEditionNo'
                          AND member_of.publisher = '$bookToAddPublisher' AND member_of.book_list_id = '$bookListID'";
    $searchMemberOfQueryResult = $mysqli->query($searchMemberOfQuery);
    if ( $searchMemberOfQueryResult->num_rows == 1) {
        echo "This book is already added.";
    }
    else {
        //Add member_of relation
        $insertMemberOfQuery = "INSERT INTO member_of (book_list_id, book_id, edition_no, publisher)
                            VALUES ( '$bookListID', '$bookToAddID', '$bookToAddEditionNo', '$bookToAddPublisher')";
        $insertMemberOfQueryPrep = $mysqli->prepare($insertMemberOfQuery);
        $insertMemberOfQueryResult = $insertMemberOfQueryPrep->execute();
        $insertMemberOfQueryPrep->close();
        //Increase book list's book count
        $increaseBookCountQuery = "UPDATE book_list SET book_count = book_count + 1 WHERE book_list.book_list_id = '$bookListID'";
        $increaseBookCountQueryPrep = $mysqli->prepare($increaseBookCountQuery);
        $increaseBookCountQueryResult = $increaseBookCountQueryPrep->execute();
        $increaseBookCountQueryPrep->close();
        echo "This book is successfully added.";
    }
}

//Get selected book list's name
$getBookListNameQuery = "SELECT name FROM book_list WHERE book_list_id = '$bookListID'";
$getBookListNameQueryResult = $mysqli->query($getBookListNameQuery);
$getBookListNameQueryRow = $getBookListNameQueryResult->fetch_assoc();
$bookListName = $getBookListNameQueryRow['name'];

//Get book infos inside book_list
$getBookInfosInList = "SELECT * FROM member_of INNER JOIN books ON member_of.book_id = books.book_id WHERE  member_of.book_list_id = '$bookListID'";
$getBookInfosInListResult = mysqli_query($mysqli, $getBookInfosInList);
$getBookInfosInListRowCount = mysqli_num_rows( $getBookInfosInListResult);
?>
<!DOCTYPE html>
<html>
<head>
    <title>List of Books</title>
</head>
<body >
<div style="width: 49%; position: absolute; top: 0px; left: 0px;">
    <h1><?php echo"$bookListName";?></h1>
    <label>Books In This List:</label>
    <table>
        <tr>
            <th>Book Name</th>
            <th>Year</th>
            <th>Genre</th>
            <th>ID</th>
            <th>Edition No</th>
            <th>Publisher</th>
        </tr>
        <?php
        if( $getBookInfosInListRowCount > 0)
        {
            while( $getBookInfosInListRow = mysqli_fetch_assoc($getBookInfosInListResult))
            {
                echo "<tr>
                          <td>".$getBookInfosInListRow['title']."</td>
                          <td>".$getBookInfosInListRow['year']."</td>
                          <td>".$getBookInfosInListRow['genre']."</td>
                          <td>".$getBookInfosInListRow['book_id']."</td>
                          <td>".$getBookInfosInListRow['edition_no']."</td>
                          <td>".$getBookInfosInListRow['publisher']."</td>
                      </tr>";
            }
            echo "</table>";
        }
        ?>
</div>
<div style="width: 49%; position: absolute; top: 0px;right: 75px;">
    <form action = "" method = "POST">
        <table align = "CENTER">
            <tr>
                <td>     </td>
                <td>Book Name:</td>
                <td>Genre:</td>
                <td>Author:</td>
                <td colspan="2">Year:</td>
            </tr>
            <tr>
                <td>Search by:</td>
                <td><input type = "text" name = "listName" value = "" placeholder = "Search by name"></td>
                <td><input type = "text" name = "listGenre" value = "" placeholder = "Genre"></td>
                <td><input type = "text" name = "listAuthor" value = "" placeholder = "Author"></td>
                <td><input type = "date" name = "listYear"></td>
                <td><input type = "date" name = "listYear2"></td>

            </tr>
            <tr>
                <td><input type = "submit" name = "Search" value = "Search"</td>
            </tr>
        </table>
    </form>

    <?php
    $listSql = "SELECT * FROM books NATURAL JOIN edition";

    if( isset($_POST['Search']))
    {
        // for checking if the fields are empty
        $listName = $_POST['listName'];
        $listAuthor = $_POST['listAuthor'];
        $listYear = $_POST['listYear'];
        $listYear2 = $_POST['listYear2'];
        $listGenre = $_POST['listGenre'];

        // if not all the fields are empty, continue constructing query
        if( !empty($listName) || !empty($listAuthor)  || !empty($listYear)|| !empty($listYear2)   ||  !empty($listGenre))
        {
            $listSql .= " WHERE ";
            $andCount = 0;

            if(!empty($listName)) {
                $andCount++;
                if($andCount == 1) {
                    $listSql .= "title like '%$listName%'";
                } else {
                    $listSql .= " AND title like '%$listName%'";
                }
            }
            if(!empty($listYear)) {
                $andCount++;
                if ($andCount == 1) {
                    $listSql .= "year BETWEEN '$listYear' AND '$listYear2'";
                } else {
                    $listSql .= " AND year BETWEEN '$listYear' AND '$listYear2'";
                }
            }
            if(!empty($listGenre)) {
                $andCount++;
                if($andCount == 1) {
                    $listSql .= "genre like '%$listGenre%'";
                } else {
                    $listSql .= " AND genre like '%$listGenre%'";
                }
            }
            if(!empty($listAuthor)) {
                $andCount++;
                if($andCount == 1) {
                    $listSql .= "edition.book_id IN (SELECT book_id FROM users INNER JOIN publishes ON users.user_id = publishes.author_id WHERE name LIKE '%$listAuthor%')";
                } else {
                    $listSql .= " AND edition.book_id IN (SELECT book_id FROM users INNER JOIN publishes ON users.user_id = publishes.author_id WHERE name LIKE '%$listAuthor%')";
                }
            }
        }
        $result = mysqli_query($mysqli, $listSql);
        $resultCheck = mysqli_num_rows( $result);
        echo"<h2>Results</h2>
            <p>To add a caption to a table, use the caption tag.</p>
            <table style=\"width:75%\">
            <tr>
                <th>Book Name</th>
                <th>Year</th>          
                <th>Genre</th>
                <th>ID</th> 
                <th>Edition No</th>
                <th>Publisher</th>   
                <th>Page Count</th>   
            </tr>";
        if( $resultCheck > 0)
        {
            while( $row = mysqli_fetch_assoc($result))
            {
                echo "<tr>
                            <td><a href=\"editBookList.php?bookListId=" . urlencode($bookListID) . "&bookToAddId=" . urlencode($row['book_id']) .
                            "&bookToAddEditionNo=" . urlencode($row['edition_no']) . "&bookToAddPublisher=" . urlencode($row['publisher']) . "\">".$row['title']."</a></td>
                            <td>".$row['year']."</td>
                            <td>".$row['genre']."</td>
                            <td>".$row['book_id']."</td>
                            <td>".$row['edition_no']."</td>
                            <td>".$row['publisher']."</td>
                            <td>".$row['page_count']."</td>
                        </tr>";
            }
            echo "</table>";
        }
    }
    ?>
</div>
<a style ="position: absolute; bottom: 0px; right: 0px;"href="mainMenu.php">Main Menu</a>
<br></br>
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
