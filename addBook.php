 <?php
    session_start();
    include("config.php");
    ?>
<DOCTYPE! html>
<html>
<head>
    <title>
        AddBookPageFromAuthorAccount
    </title>
</head>
<body>
<div>
    <?php
    if (isset($_POST['addBook'])) {
        echo 'addBook isset entered.';
        $bookTitle = $_POST['bookTitle'];
        $year = $_POST['year'];
        $genre = $_POST['genre'];
        $summary = $_POST['summary'];
        $editionNo = $_POST['editionNo'];
        $publisher = $_POST['publisher'];
        $publishYear = $_POST['publishYear'];
        $language = $_POST['language'];
        $translator = $_POST['translator'];
        $format = $_POST['format'];

        if ( !empty($bookTitle) && !empty($year) && !empty($genre) && !empty($summary) &&
            !empty($editionNo) && !empty($publisher) && !empty($publishYear) && !empty($language)) {
            //ADD NEW BOOK TO BOOK TABLE
            $queryBook = "INSERT INTO books( book_id, title, genre, year, summary) VALUES (LAST_INSERT_ID(),'$bookTitle', '$genre',
                                                                        '$year', '$summary')";
            $bookInsert = $mysqli->prepare($queryBook);
            $resultBookInsert = $bookInsert->execute();
            $bookInsert->close();
            if($resultBookInsert) {
                echo 'Successfully inserted new book.';
            }

            //GET LAST INSERTED BOOK ID
            $lastBookRow = $mysqli->query("SELECT book_id FROM books ORDER BY book_id DESC LIMIT 1");
            $row = $lastBookRow->fetch_assoc();
            $lastBookID = $row['book_id'];
            if($lastBookID) {
                echo 'Successfully get last book ID';
            }

            //ADD EDITION OF BOOK TO EDITION TABLE
            $queryEdition = "INSERT INTO edition( book_id, edition_no, publisher, publishing_year, language, translator, like_count, dislike_count, comment_count, format) 
                            VALUES ('$lastBookID', '$editionNo', '$publisher', '$publishYear', '$language', '$translator', 0, 0, 0, '$format')";
            $editionInsert = $mysqli->prepare($queryEdition);
            $resultEditionInsert = $editionInsert->execute();
            $editionInsert->close();
            if ($resultEditionInsert) {
                echo 'Successfully inserted new edition';
            }

            //ADD PUBLISHER-BOOK TUPLE TO PUBLISHES RELATION
            $authorID = $_SESSION['kkk'];
            echo "Author id: " . $authorID . "\r\n";
            echo "Book id:" . $lastBookID . "\r\n";

            $queryPublishes = "INSERT INTO publishes( book_id, author_id) 
                            VALUES ('$lastBookID', '$authorID')";
            $publishesInsert = $mysqli->prepare($queryPublishes);
            $resultPublishesInsert = $publishesInsert->execute();
            $publishesInsert->close();
            if ($resultPublishesInsert) {
                echo 'Successfully inserted new publishes relation';
            }
        }
    }
    ?>
</div>
<form class="button" action="addBook.php" method="post">
    <div>
        <input type="text"
               id="bookTitle"
               name="bookTitle"
               placeholder="Book Title">
        <br></br>
        <input type="date"
               id="year"
               name="year"
               placeholder="Publish year"
               style="margin-top: 5px;">
        <br></br>
        <input type="text"
               id="genre"
               name="genre"
               placeholder="Genre"
               style="margin-top: 5px;">
        <br></br>
        <textarea type="text" id="summary" name="summary" rows="8" cols="50" placeholder="Summary"></textarea>
        <br></br>
        <input type="number"
               id="editionNo"
               name="editionNo"
               style="margin-top: 5px;"
               placeholder="EditionNo">
        <br></br>
        <input type="text"
               id="publisher"
               name="publisher"
               placeholder="Publisher"
               style="margin-top: 5px;">
        <br></br>
        <input type="number"
               id="publishYear"
               name="publishYear"
               placeholder="Edition publish year"
               style="margin-top: 5px;">
        <br></br>
        <input type="text"
               id="language"
               name="language"
               placeholder="Language"
               style="margin-top: 5px;">
        <br></br>
        <input type="text"
               id="translator"
               name="translator"
               placeholder="Translator"
               style="margin-top: 5px;">
        <br></br>
        <input type="text"
               id="format"
               name="format"
               placeholder="Format"
               style="margin-top: 5px;">
        <br></br>
        <input type="submit" name="addBook" onclick="checkForEmptyInputs()">
    </div>
</form>
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