<?php
    session_start();
    include("config.php");
    $reqID = $_GET['requestID'];

    //FIND BOOK USING CONCERNS RELATION
    $findBookStatQuery = "SELECT book_id, edition_no, publisher FROM concerns WHERE request_id = '$reqID'";
    $findBookStatQueryResult = $mysqli->query($findBookStatQuery);
    $findBookStatQueryRow = $findBookStatQueryResult->fetch_assoc();
    $thisBookID = $findBookStatQueryRow['book_id'];
    $thisBookEditionNo = $findBookStatQueryRow['edition_no'];
    $thisBookPublisher = $findBookStatQueryRow['publisher'];

    if (isset($_POST['editBook'])) {
        $bookTitle = $_POST['bookTitle'];
        $year = $_POST['year'];
        $genre = $_POST['genre'];
        $summary = $_POST['summary'];
        $publishYear = $_POST['publishYear'];
        $language = $_POST['language'];
        $translator = $_POST['translator'];
        $totalPageCount = $_POST['totalPageCount'];
        $format = $_POST['format'];

        if ( !empty($bookTitle) && !empty($year) && !empty($genre) && !empty($summary) && !empty($publishYear) && !empty($language) && !empty($totalPageCount) && !empty($translator) && !empty($format)) {
            //UPDATE BOOK and EDITION tables
            $updateBookQuery = "UPDATE books SET genre = '$genre', year = '$year', title = '$bookTitle', summary = '$summary' WHERE books.book_id = '$thisBookID'";
            $updateBookQueryPrep = $mysqli->prepare($updateBookQuery);
            $updateBookQueryResult = $updateBookQueryPrep->execute();
            $updateBookQueryPrep->close();

            $updateEditionQuery = "UPDATE edition SET publishing_year = '$publishYear', language = '$language', translator = '$translator', page_count = '$totalPageCount', format = '$format' WHERE edition.book_id = '$thisBookID' AND edition.edition_no = '$thisBookEditionNo' AND edition.publisher = '$thisBookPublisher'";
            $updateEditionQueryPrep = $mysqli->prepare($updateEditionQuery);
            $updateEditionQueryResult = $updateEditionQueryPrep->execute();
            $updateEditionQueryPrep->close();
        }
    }
    //Get the book
    $getTheBookQuery = "SELECT * FROM books INNER JOIN edition ON edition.book_id = books.book_id 
                            WHERE books.book_id = '$thisBookID' AND edition.edition_no='$thisBookEditionNo' AND edition.publisher = '$thisBookPublisher'";
    $getTheBookQueryResult = $mysqli->query($getTheBookQuery);
    $getTheBookQueryRow = $getTheBookQueryResult->fetch_assoc();
    ?>
<DOCTYPE! html>
<html>
<head>
    <title>
        EditBookFromLibrarianAccount
    </title>
</head>
<body>
    <?php
        echo "
            <form method=\"post\">
                <div>
                    <label>Title:</label><br>
                    <input type=\"text\"
                           id=\"bookTitle\"
                           name=\"bookTitle\"
                           placeholder=\"Book Title\"
                           value='".$getTheBookQueryRow['title']."'>
                    <br></br>
                    <label>Original Publish Year:</label><br>
                    <input type=\"date\"
                           id=\"year\"
                           name=\"year\"
                           placeholder=\"Publish year\"
                           style=\"margin-top: 5px;\"
                           value='".$getTheBookQueryRow['year']."'>
                    <br></br>
                    <label>Genre:</label><br>
                    <input type=\"text\"
                           id=\"genre\"
                           name=\"genre\"
                           placeholder=\"Genre\"
                           style=\"margin-top: 5px;\"
                           value='".$getTheBookQueryRow['genre']."'>
                    <br></br>
                    <label>Summary:</label><br>
                    <textarea type=\"text\" id=\"summary\" name=\"summary\" rows=\"8\" cols=\"50\">".$getTheBookQueryRow['summary']."</textarea>
                    <br></br>
                    <label>Edition Publish Year:</label><br>
                    <input type=\"number\"
                           id=\"publishYear\"
                           name=\"publishYear\"
                           placeholder=\"Edition publish year\"
                           style=\"margin-top: 5px;\"
                           value='".$getTheBookQueryRow['publishing_year']."'>
                    <br></br>
                    <label>Language:</label><br>
                    <input type=\"text\"
                           id=\"language\"
                           name=\"language\"
                           placeholder=\"Language\"
                           style=\"margin-top: 5px;\"
                           value='".$getTheBookQueryRow['language']."'>
                    <br></br>
                    <label>Translator:</label><br>
                    <input type=\"text\"
                           id=\"translator\"
                           name=\"translator\"
                           placeholder=\"Translator\"
                           style=\"margin-top: 5px;\"
                           value='".$getTheBookQueryRow['translator']."'>
                    <br></br>
                    <label>Page Count:</label><br>
                    <input type=\"number\"
                           id=\"totalPageCount\"
                           name=\"totalPageCount\"
                           placeholder=\"Total Page Count\"
                           style=\"margin-top: 5px;\"
                           value='".$getTheBookQueryRow['page_count']."'>
                    <br></br>
                    <label>Format:</label><br>
                    <input type=\"text\"
                           id=\"format\"
                           name=\"format\"
                           placeholder=\"Format\"
                           style=\"margin-top: 5px;\"
                           value='".$getTheBookQueryRow['format']."'>
                    <br></br>
                    <button name=\"editBook\" onclick=\"checkForEmptyInputs()\">Edit</button>
                </div>
            </form>";
    ?>
</body>
</html>
<script>
    function checkForEmptyInputs() {
        var errorMsg = "";
        if ( document.getElementById('bookTitle').value == "" ) {
            errorMsg += "Please fill out the bookTitle field. \n";
        }
        if ( document.getElementById('year').value == "" ) {
            errorMsg += "Please fill out the year field. \n";
        }
        if ( document.getElementById('genre').value == "" ) {
            errorMsg += "Please fill out the genre field. \n";
        }
        if ( document.getElementById('editionNo').value == "" ) {
            errorMsg += "Please fill out the edition field. \n";
        }
        if ( document.getElementById('publisher').value == "" ) {
            errorMsg += "Please fill out the confirm publisher field. \n";
        }
        if ( document.getElementById('publishYear').value == "" ) {
            errorMsg += "Please fill out the publishYear field. \n";
        }
        if ( document.getElementById('language').value == "" ) {
            errorMsg += "Please fill out the language field. \n";
        }
        if ( document.getElementById('summary').value == "" ) {
            errorMsg += "Please fill out the summary type field. \n";
        }
        if ( errorMsg != "" ) {
            alert(errorMsg);
            return false;
        }
        return true;
    }
</script>