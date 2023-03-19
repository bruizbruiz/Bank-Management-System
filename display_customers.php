<?php
# Connect to database
include("dbconfig.php");

$con = mysqli_connect($hostname,$username,$password,$dbname)
	or die("<br>Cannot connect to $dbname on $hostname, error: " . mysqli_connect_error());
$sql = "SELECT * FROM CPS3740.Customers";
$result = mysqli_query($con, $sql);

echo "The following are in the banking system:";

echo "<TABLE border=1>\n";
echo "<TR><TH>ID<TH>Name<TH>Login<TH>Password<TH>DOB<TH>Gender<TH>Street<TH>City<TH>State<TH>Zipcode\n";
	while($row = mysqli_fetch_array($result))
	{
		$customer_id = $row["id"];
		$name = $row["name"];
		$login = $row["login"];
		$pass = $row["password"];
		$dob = $row["DOB"];
		$gender = $row["gender"];
		$street = $row["street"];
		$city = $row["city"];
		$state = $row["state"];
		$zip = $row["zipcode"];

		echo "<TR><TD>$customer_id<TD>$name<TD>$login<TD>$pass<TD>$dob<TD>$gender<TD>$street<TD>$city<TD>$state
			<TD>$zip";
	}
echo "</TABLE>";
?>