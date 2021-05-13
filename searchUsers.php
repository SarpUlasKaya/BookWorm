<?php
    include_once 'config.php';
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>List of Users</title>
    <h1>List of Users</h1>
</head>
<body align = "CENTER">
<form action = "" method = "POST">
    <label>Search:</label>
    <input type = "text" name = "userName" placeholder = "Search by Username">
    <input type = "submit" name = "searchUser">
</form>

<?php
if( isset($_POST['searchUser'])) {
    $userID = $_SESSION['userID'];
//get all users with similar username
    $listSql = "SELECT * FROM users WHERE user_id !='$userID'";

    if (isset($_POST['searchUser'])) {
        $listName = $_POST['userName'];

        if (!empty($listName)) {
            $listSql .= " WHERE name like '%$listName%'";
        }
    }
    $result = mysqli_query($mysqli, $listSql);
    $resultCheck = mysqli_num_rows($result);
    echo "<h2>Results</h2>
        <table style=\"width:100%\">
            <tr>
                <th>User Name</th>
                <th>Gender</th>          
                <th>Joined At</th>
            </tr>";
    if ($resultCheck > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td><a href=\"userDetails.php?searchedUserID=" . urlencode($row['user_id']) . "\">" . $row['name'] . "</td>
                    <td>" . $row['gender'] . "</td>
                    <td>" . $row['created_at'] . "</td>
                  </tr>";
        }
        echo "</table>";
    }
}
?>
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