<DOCTYPE! html>
<html>
<head>
    <title>
        Welcome Page
    </title>

    <script>
    function checkForMaxApplications() {
	var table = document.getElementById("application_table");	

	if ( table.rows.length >= 4 ) {
	    alert("You cannot apply for any more internships!");
	    return false;
	}
	return true;
    }
    </script>
</head>
<body>
    <h1>
        Welcome! Here are your Internship Applications:
    </h1>

    <table id="application_table">
	<th>Company Id</th>
	<th>Company Name</th>
	<th>Quota</th>
    <?php
	include("config.php");
	session_start();
	$id = $_SESSION['user_id'];
	
	$applications = mysqli_query($mysqli, "SELECT sid, cid FROM apply");

	if ($applications->num_rows > 0) {
	    while ($application_row = $applications->fetch_assoc()) {
		if ( $application_row["sid"] == $id ) {
		    $companies = mysqli_query($mysqli, "SELECT cid, cname, quota FROM company");
		    while ($company_row = $companies->fetch_assoc()) {
			if ( $company_row["cid"] == $application_row["cid"] ) {
			    echo "<tr><td>". $company_row["cid"] ."</td><td>". $company_row["cname"] ."</td><td>". $company_row["quota"] ."</td></tr>";
			}
		    }
		}
	    }
	    echo "</table>";
	}
	else {
	    echo "0 results";
	}
    ?>

    <form action="selectcomp.php" method="post" onsubmit="return checkForMaxApplications()">
        <input type="submit" name="apply" value="Apply"/>
    </form>
</body>
</html>