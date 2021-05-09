<?php
	include_once 'config.php'
?>
<!DOCTYPE html>
<html>
<head>
	<title>List of Books</title>
	<h1>List of Books</h1>
</head>
<body align = "CENTER">
	<form action = "" method = "POST">
		<table align = "CENTER">
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
    $listSql = "SELECT * FROM books";

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

        if( $resultCheck > 0)
        {
            while( $row = mysqli_fetch_assoc($result))
            {
                echo "Book ID: ". $row['book_id'] . "|| Book Year: " . $row['year'] . "|| Book Title: " . $row['title'] . "|| Book Genre: ". $row['genre'] . "<br>";
            }
        }
    }
    ?>
</body>
</html>