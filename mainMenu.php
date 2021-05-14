<?php
    session_start();
    include("config.php");
    $thisUserID = $_SESSION['userID'];

    $checkReaderAccountType = "SELECT * FROM reader_account WHERE user_id = '$thisUserID'";
    $resultReaderAcc = $mysqli->query($checkReaderAccountType);

    $checkAuthorAccountType = "SELECT * FROM author_account WHERE user_id = '$thisUserID'";
    $resultAuthorAcc = $mysqli->query($checkAuthorAccountType);

    $checkLibrarianAccountType = "SELECT * FROM librarian_account WHERE user_id = '$thisUserID'";
    $resultLibrarianAcc = $mysqli->query($checkLibrarianAccountType);
?>
<body>
<section>
    <div style="align-items: center">
        <h6>BookWorm</h6>
        <a href="#">
            <img  style="position: relative; max-height: 80px; max-width: 80px;" src="img/logo.png">
            <ul>
                <li>
                    <?php
                        if($resultReaderAcc->num_rows==1){
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"searchBooks.php\">Search Book</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"myLibrary.php\">My Library</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"searchUsers.php\">Search Users</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"userDetails.php\">My Profile</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"joinReadingChallenge.php\">Join Reading Challenge</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"createQuiz.php\">Create Quiz</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"quizTable.php\">Solve Quiz</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"createRequest.php\">Create Request</a>";
                        }
                        elseif ($resultAuthorAcc->num_rows==1){
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"searchBooks.php\">Search Book</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"addBook.php\">Add Book</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"myLibrary.php\">My Library</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"searchUsers.php\">Search Users</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"userDetails.php\">My Profile</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"joinReadingChallenge.php\">Join Reading Challenge</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"createQuiz.php\">Create Quiz</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"quizTable.php\">Solve Quiz</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"createRequest.php\">Create Request</a>";
                        }
                        else{
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"searchBooks.php\">Search Book</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"myLibrary.php\">My Library</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"searchUsers.php\">Search Users</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"userDetails.php\">My Profile</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"createReadingChallenge.php\">Create Reading Challenge</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"joinReadingChallenge.php\">Join Reading Challenge</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"createQuiz.php\">Create Quiz</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"quizTable.php\">Solve Quiz</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"createRequest.php\">Create Request</a>";
                            echo "<a style=\"margin-left: 10px; display: inline-block; color: darkgrey;text-decoration: navajowhite;\" href=\"viewRequest.php\">View Request</a>";
                        }
                    ?>
                </li>
            </ul>
        </a>
    </div>
</section>
</body>