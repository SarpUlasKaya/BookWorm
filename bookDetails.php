<?php
session_start();
include("config.php");

$bookID = $_GET["bookId"];
$editionNO = $_GET["editionNo"];
$bookEditionPublisher = $_GET["publisher"];
$userID = $_SESSION['userID'];
//echo "BOOK ID: " . $bookID . " Edition NO: " . $editionNO . "Publisher: " . $bookEditionPublisher . " USER ID: " . $userID . "\r\n";
if( isset($_POST['newPageCountSubmit'])) {
    $newCurrentPageNum = $_POST['newCurrentPageNum'];
    //Mark new Progress
    $markProgressQuery = "INSERT INTO mark_progress(current_page, progress_date, user_id, book_id) 
                            VALUES ('$newCurrentPageNum', NOW(),'$userID', '$bookID')";
    $markProgressQueryPrep = $mysqli->prepare($markProgressQuery);
    $markProgressQueryResult = $markProgressQueryPrep->execute();
    $markProgressQueryPrep->close();
    if ($markProgressQueryResult) {
        //echo 'Successfully inserted new mark_progress.';
    }
}
//get mark_progress table
$getMPTable = "SELECT progress_date, current_page FROM mark_progress INNER JOIN edition ON mark_progress.book_id = edition.book_id 
                            WHERE mark_progress.book_id = '$bookID' AND edition.edition_no = '$editionNO' AND edition.publisher = '$bookEditionPublisher' AND mark_progress.user_id = '$userID' ORDER BY progress_date DESC";
$getMPTableResult = mysqli_query($mysqli, $getMPTable);
$getMPTableRowNum = mysqli_num_rows( $getMPTableResult);

//get Last mark_progress tuple
$getPageInfoQuery = "$getMPTable" ." LIMIT 1";
$getPageInfoQueryResult = $mysqli->query($getPageInfoQuery);
$getPageInfoQueryRow = $getPageInfoQueryResult->fetch_assoc();

if($getPageInfoQueryRow['current_page']){
    $currentPageNum = $getPageInfoQueryRow['current_page'];
} else {
    $currentPageNum = 0;
}
//get book info
$getBookInfoQuery = "SELECT * FROM books INNER JOIN edition ON edition.book_id = books.book_id 
                        WHERE books.book_id = '$bookID' AND edition.edition_no='$editionNO' AND edition.publisher = '$bookEditionPublisher'";
$getBookInfoQueryResult = $mysqli->query($getBookInfoQuery);
$getBookInfoQueryRow = $getBookInfoQueryResult->fetch_assoc();
$bookTitle = $getBookInfoQueryRow['title'];
$bookGenre = $getBookInfoQueryRow['genre'];
$bookYear = $getBookInfoQueryRow['year'];
$bookEdition = $getBookInfoQueryRow['edition_no'];
$bookPublisher = $getBookInfoQueryRow['publisher'];
$bookPublishYear = $getBookInfoQueryRow['publishing_year'];
$bookLanguage = $getBookInfoQueryRow['language'];
$bookTranslator = $getBookInfoQueryRow['translator'];
$bookFormat = $getBookInfoQueryRow['format'];
$bookPageCount = $getBookInfoQueryRow['page_count'];
$bookLikeCount = $getBookInfoQueryRow['like_count'];
$bookDislikeCount = $getBookInfoQueryRow['dislike_count'];
$bookCommentCount = $getBookInfoQueryRow['comment_count'];

//get author info
$getAuthorInfoQuery = "SELECT author_id FROM publishes INNER JOIN books ON books.book_id = publishes.book_id WHERE books.book_id = '$bookID'";
$getAuthorInfoQueryResult = $mysqli->query($getAuthorInfoQuery);
$getAuthorInfoQueryRow = $getAuthorInfoQueryResult->fetch_assoc();
$authorID = $getAuthorInfoQueryRow['author_id'];
$authorName = ($mysqli->query("SELECT name FROM users WHERE user_id = '$authorID'"))->fetch_assoc()['name'];

if( isset($_POST['like'])) {

}
if( isset($_POST['dislike'])) {
    $bookDislikeCount = $bookDislikeCount + 1;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>MarkProgressPage</title>
</head>
<body align = \"CENTER\">

<div style="width: 49%; position: absolute; top: 0px; left: 150px;">
    <h1><?php echo"$bookTitle"; ?></h1>
    <td>Author : <?php echo"$authorName"; ?></td>
    <br></br>
    <td>Genre : <?php echo"$bookGenre"; ?></td>
    <br></br>
    <td>Year : <?php echo"$bookYear"; ?></td>
    <br></br>
    <td>Edition : <?php echo"$bookEdition"; ?></td>
    <br></br>
    <td>Publisher : <?php echo"$bookEditionPublisher"; ?></td>
    <br></br>
    <td>Publication Year : <?php echo"$bookPublishYear"; ?></td>
    <br></br>
    <td>Language : <?php echo"$bookLanguage"; ?></td>
    <br></br>
    <td>Translator : <?php echo"$bookTranslator"; ?></td>
    <br></br>
    <td>Format :<?php echo"$bookFormat"; ?></td>
    <br></br>
    <td>Page Count :<?php echo"$bookPageCount"; ?></td>
    <br></br>
    <td>Like Count :<?php echo"$bookLikeCount"; ?></td>
    <br></br>
    <td>Dislike Count :<?php echo"$bookDislikeCount"; ?></td>
    <br></br>
    <td>Comment Count :<?php echo"$bookCommentCount"; ?></td>
    <br></br>

    <form method="post">
        <button name="like"><img src="img/like.png" alt="Like" style="position: relative; height: 40px; width: 40px;"></button>
        <button name="dislike"><img src="img/dislike.png" alt="Dislike" style="position: relative; height: 40px; width: 40px;"></button>
    </form>
</div>
<div style="width: 49%; position: absolute; top: 0px; right: 0px;">
    <h1>
        YOUR PROGRESS
    </h1>
    <p> You are currently at page <?php echo "$currentPageNum of $bookPageCount";?>
    </p>
    <table>
        <tr>
            <th>Progress Mark Date</th>
            <th>Page No</th>
        </tr>
        <?php
        if( $getMPTableRowNum > 0) {
            while ($getMPTableRow = mysqli_fetch_assoc($getMPTableResult)) {
                echo "<tr>
                    <td>" . $getMPTableRow['progress_date'] . "</td>
                    <td>" . $getMPTableRow['current_page'] . "</td>
                  </tr>";
            }
        }
        ?>
    </table>
    <br></br>
    <label>Enter Current Page Number: </label>
    <form method="post">
        <input name="newCurrentPageNum" type="number"/>
        <input type="submit" name="newPageCountSubmit" value="Submit"/>
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

