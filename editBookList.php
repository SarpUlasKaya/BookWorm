<?php
session_start();
include("config.php");

$bookListID = $_GET['bookListId'];
$userID = $_SESSION['userID'];

//Get book list name
$getBookListNameQuery = "SELECT name FROM book_list WHERE book_list_id = '$bookListID'";
$getBookListNameQueryResult = $mysqli->query($getBookListNameQuery);
$getBookListNameQueryRow = $getBookListNameQueryResult->fetch_assoc();
$bookListName = $getBookListNameQueryRow['name'];

//Add Books to BookList

?>
<!DOCTYPE html>
<html>
<head>
    <title>List of Books</title>
</head>
<body align = "LEFT">
<div style="width: 49%; position: absolute; top: 0px; left: 150px;">
    <p> BOOK LIST NAME: <?php echo"$bookListName";?> </p>
</div>
<div style="width: 49%; position: absolute; top: 0px; left: 750px;">
    <form action = "" method = "POST">
        <table>
            <tr>
                <td>     </td>
                <td>Genre:</td>
                <td>Author:</td>
                <td>Year:</td>
            </tr>
            <tr>
                <td>Sort by:</td>
                <td><input type = "text" name = "listGenre" value = "" placeholder = "Genre"></td>
                <td><input type = "text" name = "listAuthor" value = "" placeholder = "Author"></td>
                <td><input type = "date" name = "listYear" value = "" placeholder = "Year"></td>
            </tr>
            <tr>
                <td>Search:</td>
                <td><input type = "text" name = "listName" value = "" placeholder = "Search by name"></td>
                <td><input type = "submit" name = "Search" value = "Search"</td>
            </tr>
        </table>
    </form>

    <?php
    // if search is done using only search by name
    $listSql = "SELECT * FROM books INNER JOIN edition ON books.book_id = edition.book_id";

    if( isset($_POST['Search']))
    {
        // for checking if the fields are empty
        $listName = $_POST['listName'];
        $listAuthor = $_POST['listAuthor'];
        $listYear = $_POST['listYear'];
        $listGenre = $_POST['listGenre'];



        // if not all the fields are empty, continue constructing query
        if( !empty($listName) || !empty($listAuthor)  || !empty($listYear)  ||  !empty($listGenre))
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
            if(!empty($listAuthor)) {
                $andCount++;
                if($andCount == 1) {
                    $listSql .= "book_id IN (SELECT book_id FROM users INNER JOIN publishes ON users.user_id = publishes.author_id WHERE name LIKE '%$listAuthor%')";
                } else {
                    $listSql .= " AND book_id IN (SELECT book_id FROM users INNER JOIN publishes ON users.user_id = publishes.author_id WHERE name LIKE '%$listAuthor%')";
                }
            }
            if(!empty($listYear)) {
                $andCount++;
                if ($andCount == 1) {
                    $listSql .= "year like '%$listYear%'";
                } else {
                    $listSql .= " AND year like '%$listYear%'";
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
        }
        $result = mysqli_query($mysqli, $listSql);
        $resultCheck = mysqli_num_rows( $result);
        echo"<h2>Results</h2>
                <p>To add a caption to a table, use the caption tag.</p>
                <table>
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
                            <td>".$row['title']."</td>
                            <td>".$row['year']."</td>
                            <td>".$row['genre']."</td>
                            <td>".$row['book_id']."</td>
                            <td>".$row['edition_no']."</td>
                            <td>".$row['publisher']."</td>
                            <td>".$row['page_count']."</td>
                            <td><button>Add</button></td>
                        </tr>
                  ";
            }
            echo "</table>";
        }
    }
    ?>
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
