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
	<?php
		
	?>
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
			<td><input type = "text" name = "listYear" value = "" placeholder = "Year"></td>
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
			if( !empty($listName) || !empty($listAuthor)  || !empty(listYear)  ||  !empty($listGenre))
			{
				$listSql .= " WHERE ";
				$andCount = 0;
				
				if(!empty($listName))
				{
					$andCount++;
					if($andCount == 1)
					{
						$listSql .= "title like '%$listName%'";
					}
					else
					{
						$listSql .= " AND title like '%$listName%'";
					}
				}
				/*
				if(!empty($listAuthor))
				{
					$andCount++;
					if($andCount == 1)
					{
						$listSql .= "author like '%$listAuthor%'";
					}
					else
					{
						$listSql .= " AND title like '%$listAuthor%'";
					}
				}
				if(!empty($listYear))
				{
					$andCount++;
					if($andCount == 1)
					{
						$listSql .= "year like '%$listAuthor%'";
					}
					else
					{
						$listSql .= " AND title like '%$listAuthor%'";
					}
				}
				*/
				
			}
				
		}
		
		$result = mysqli_query($mysqli, $listSql);
		$resultCheck = mysqli_num_rows( $result);
	
		if( $resultCheck > 0)
		{
			while( $row = mysqli_fetch_assoc($result))
			{
				echo $row['book_id'] . " || " . $row['year'] . " || " . $row['title'] . " || ". $row['summary'] . "<br>";
			
			}
		}
	?>
</body>
</html>
