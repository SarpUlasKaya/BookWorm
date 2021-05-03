<DOCTYPE! html>
<html>
<head>
    <title>
        Application Page
    </title>

</head>
<body>
    <h1>
        Here are the Companies Available for You:
    </h1>

    <table id="company_table">
	<th>Company Id</th>
	<th>Company Name</th>
	<th>Quota</th>
    <?php
	include("config.php");
	session_start();
	$id = $_SESSION['user_id'];
	
	$companies = mysqli_query($mysqli, "SELECT cid, cname, quota FROM company");

	if ($companies->num_rows > 0) {
	    while ($company_row = $companies->fetch_assoc()) {
		$applications = mysqli_query($mysqli, "SELECT sid, cid FROM apply");
		$applied = 0;
		while ($application_row = $applications->fetch_assoc()) {
		    if ( ($company_row["cid"] == $application_row["cid"]) && ($application_row["sid"] == $id) ) {
			$applied = 1;
			break;
		    }
		}
		if (($applied == 0) && ($company_row["quota"] > 0 )) {
		    echo "<tr><td>". $company_row["cid"] ."</td><td>". $company_row["cname"] ."</td><td>". $company_row["quota"] ."</td></tr>";
		}
	    }
	    echo "</table>";
	}
	else {
	    echo "0 results";
	}
    ?>

</body>
</html>