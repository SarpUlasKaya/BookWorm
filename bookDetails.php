<?php
$bookID = $_GET["bookId"];
$editionNO = $_GET["editionNo"];
$getBookQuery = "SELECT * FROM books INNER JOIN edition ON books.book_id = edition.book_id WHERE book_id = '$bookID'";

echo "<h1>
        YOUR PROGRESS
      </h1>
      <p> You are currently at page"." </p>";
?>

//Mark Progress
$markProgressQuery = "INSERT INTO mark_progress( user_id ) SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1";
$stmtinsert = $mysqli->prepare($libQuery);
$result = $stmtinsert->execute();
$stmtinsert->close();
if ($result) {
    echo 'Successfully inserted new librarian.';
}
?>