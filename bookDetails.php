<?php
session_start();
include("config.php");

$bookID = $_GET["bookId"];
$editionNO = $_GET["editionNo"];
$bookEditionPublisher = $_GET["publisher"];
$userID = $_SESSION['userID'];
echo "BOOK ID: " . $bookID . " Edition NO: " . $editionNO . "Publisher: " . $bookEditionPublisher . " USER ID: " . $userID . "\r\n";
if( isset($_POST['newPageCountSubmit'])) {
    $newCurrentPageNum = $_POST['newCurrentPageNum'];
    //Mark new Progress
    $markProgressQuery = "INSERT INTO mark_progress(current_page, progress_date, user_id, book_id) 
                            VALUES ('$newCurrentPageNum', NOW(),'$userID', '$bookID')";
    $markProgressQueryPrep = $mysqli->prepare($markProgressQuery);
    $markProgressQueryResult = $markProgressQueryPrep->execute();
    $markProgressQueryPrep->close();
    if ($markProgressQueryResult) {
        echo 'Successfully inserted new mark_progress.';
    }
}
//get mark_progress table
$getMPTable = "SELECT * FROM mark_progress INNER JOIN edition ON mark_progress.book_id = edition.book_id 
                            WHERE mark_progress.book_id = '$bookID' AND edition.edition_no = '$editionNO' AND edition.publisher = '$bookEditionPublisher' AND mark_progress.user_id = '$userID' ORDER BY progress_date DESC";
$getMPTableResult = mysqli_query($mysqli, $getMPTable);
$getMPTableRowNum = mysqli_num_rows( $getMPTableResult);
//get Last mark_progress tuple
$getPageInfoQuery = "$getMPTable" ." LIMIT 1";
$getPageInfoQueryResult = $mysqli->query($getPageInfoQuery);
$getPageInfoQueryRow = $getPageInfoQueryResult->fetch_assoc();
$currentPageNum = $getPageInfoQueryRow['current_page'];
$totalPageNum = $getPageInfoQueryRow['page_count'];
echo "Current Page = " . $currentPageNum;
echo "Total page = " . $totalPageNum;

?>

<!DOCTYPE html>
<html>
<head>
    <title>MarkProgressPage</title>
</head>
<body align = \"CENTER\">
<h1>
    YOUR PROGRESS
</h1>
<p> You are currently at page <?php echo "$currentPageNum "; ?> of <?php echo "$totalPageNum "; ?> </p>
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

