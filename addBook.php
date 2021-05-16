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
<h1>
    Add a New Book
</h1>
<div>
    <?php
    if (isset($_POST['addBook'])) {
        $bookTitle = $_POST['bookTitle'];
        $year = $_POST['year'];
        $genre = $_POST['genre'];
        $summary = $_POST['summary'];
        $editionNo = $_POST['editionNo'];
        $publisher = $_POST['publisher'];
        $publishYear = $_POST['publishYear'];
        $language = $_POST['language'];
        $translator = $_POST['translator'];
        $totalPageCount = $_POST['totalPageCount'];
        $format = $_POST['format'];

        if ( !empty($bookTitle) && !empty($year) && !empty($genre) && !empty($summary) &&
            !empty($editionNo) && !empty($publisher) && !empty($publishYear) && !empty($language) && !empty($totalPageCount)) {
            //SEARCH IF BOOK ALREADY EXISTS
            $queryIsBookExists = " select book_id from books where title = '$bookTitle' and year = '$year' and genre = '$genre' and summary = '$summary'";
            if($isBookExist = $mysqli->query($queryIsBookExists)) {
                $authorRow = null;
                if($isBookExist->num_rows==1) {
                    echo "This book already exists. ";
                    $bookRow = $isBookExist->fetch_assoc();
                    $_SESSION['bookID'] = $bookRow['book_id'];
                    // echo $_SESSION['bookID'];
                    $bookId = $_SESSION['bookID'];
                    $queryAuthor = $mysqli->query("SELECT author_id FROM publishes INNER JOIN books ON books.book_id = publishes.book_id WHERE books.book_id = '$bookId'");
                    $authorRow = $queryAuthor->fetch_assoc();
                }
                else {
                    //ADD NEW BOOK TO BOOK TABLE
                    $queryBook = "INSERT INTO books(title, genre, year, summary)
                                    VALUES ('$bookTitle', '$genre', '$year', '$summary')";
                    $bookInsert = $mysqli->prepare($queryBook);
                    $resultBookInsert = $bookInsert->execute();
                    $bookInsert->close();
                    if($resultBookInsert) {
                        echo 'Successfully added a new book. ';
                    }
                    //GET LAST INSERTED BOOK ID
                    $lastBookRow = $mysqli->query("SELECT book_id FROM books ORDER BY book_id DESC LIMIT 1");
                    $row = $lastBookRow->fetch_assoc();
                    $lastBookID = $row['book_id'];
                    if($lastBookID) {
                        $_SESSION['bookID'] = $lastBookID;
                        //echo 'Successfully get last book ID';
                    }
                    //ADD PUBLISHER-BOOK TUPLE TO PUBLISHES RELATION
                    $authorID = $_SESSION['userID'];
                    $authorRow['author_id'] = $authorID;
                    //echo "Author id: " . $authorID . "\r\n";
                    //echo "Book id:" . $lastBookID . "\r\n";

                    $queryPublishes = "INSERT INTO publishes( book_id, author_id) 
                            VALUES ('$lastBookID', '$authorID')";
                    $publishesInsert = $mysqli->prepare($queryPublishes);
                    $resultPublishesInsert = $publishesInsert->execute();
                    $publishesInsert->close();
                    if ($resultPublishesInsert) {
                        //echo 'Successfully inserted new publishes relation';
                    }
                }
                $bookID = $_SESSION['bookID'];
                //Check if the author trying to add a new edition of an existing is same with the original book publisher
                if ($authorRow && $authorRow['author_id'] == $_SESSION['userID']) {
                    //SEARCH IF EDITION ALREADY EXISTS
                    $queryIsEditionExists = " select book_id, edition_no, publisher from edition where book_id = '$bookID' and edition_no = '$editionNo' and publisher = '$publisher'";
                    if($isEditionExist = $mysqli->query($queryIsEditionExists)) {
                        if($isEditionExist->num_rows==1) {
                            echo "This edition of the book already exists. ";
                        }
                        else {
                            //ADD EDITION OF BOOK TO EDITION TABLE
                            $lastBookID = $_SESSION['bookID'];
                            $queryEdition = "INSERT INTO edition( book_id, edition_no, publisher, publishing_year, language, translator, like_count, dislike_count, comment_count, page_count, format) 
                            VALUES ('$lastBookID', '$editionNo', '$publisher', '$publishYear', '$language', '$translator', 0, 0, 0, '$totalPageCount', '$format')";
                            $editionInsert = $mysqli->prepare($queryEdition);
                            $resultEditionInsert = $editionInsert->execute();
                            $editionInsert->close();
                            if ($resultEditionInsert) {
                                echo 'Successfully added a new edition. ';
                            }
                        }
                    }
                } else {
                    echo "Users other than the original author of a book cannot add new editions of that book.";
                }
            }
        }
        //header("location: addBook.php");
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
        <input type="number"
               id="totalPageCount"
               name="totalPageCount"
               placeholder="Total Page Count"
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
    <div>
        <a style ="position: absolute; bottom: 0px; right: 0px;"href="mainMenu.php">Main Menu</a>
        <br></br>
    </div>
</form>
</body>
</html>

<style>
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