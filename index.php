<DOCTYPE! html>
<html>
<head>
    <title>
        User Login
    </title>
    <script>
    function checkForEmptyField() {
	var errorMsg = "";
	if ( document.getElementById('username').value == "" ) {
	    errorMsg += "Please fill out the Username field. \n";
	}
	if ( document.getElementById('passwd').value == "" ) {
	    errorMsg += "Please fill out the Password field. \n";
	}
	if ( errorMsg != "" ) {
	    alert(errorMsg);
	    return false;
	}
	return true;
    }
    </script>
</head>
<body>
    <h1>
        Please Log in Using your Name and Password
    </h1>
    <form action="login.php" method="post" onsubmit="return checkForEmptyField()">
        <label>Username: <input type="text" id="username" name="name" /></label> <br><br>
        <label>Password: <input type="password" id="passwd" name="password" /></label><br><br>
        <input type="submit" name="submit" value="Login"/>
    </form>
</body>
</html>