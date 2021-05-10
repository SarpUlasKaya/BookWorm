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
$getMPTable = "SELECT * FROM mark_progress INNER JOIN edition ON mark_progress.book_id = edition.book_id 
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

$totalPageNum = $getPageInfoQueryRow['page_count'];
//echo "Current Page = " . $currentPageNum;
//echo "Total page = " . $totalPageNum;

?>

<!DOCTYPE html>
<html>
<head>
    <title>MarkProgressPage</title>
</head>
<body align = \"CENTER\">
<div style="width: 49%; position: absolute; top: 0px; right: 0px;">
    <h1>
        YOUR PROGRESS
    </h1>
    <p> You are currently at page <?php echo "$currentPageNum ";
        if($totalPageNum) {
            echo "of $totalPageNum ";
        }
        ?>
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
<div style="width: 49%; position: absolute; top: 0px; left: 200px;">
    <td>Author : author alıcaz</td>
    <br></br>
    <td>Genre : <?php echo $detailResult["genre"]?></td>
    <br></br>
    <td>Year : <?php echo $detailResult["year"]?></td>
    <br></br>
    <td>Edition : edition alıcaz</td>
    <br></br>
    <td>Publisher : publisher alıcaz</td>
    <br></br>
    <td>Publication Year : publisher alıcaz</td>
    <br></br>
    <td>Language : publisher alıcaz</td>
    <br></br>
    <td>Translator : publisher alıcaz</td>
    <br></br>
    <td>Summary : <?php echo $detailResult["summary"]?></td>
    <br></br>
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

