<?php
# Connect to database
include("dbconfig.php");

$con = mysqli_connect($hostname,$username,$password,$dbname)
	or die("<br>Cannot connect to $dbname on $hostname, error: " . mysqli_connect_error());

$update = 0;
$remove = 0;

# getting error because when deleting and submitting checks deleted value as well.
# when submitted update $note[$i] value in database
$cnote = $_GET['cnote'];
$mid = $_GET['mid'];
$extract_mids = implode(",", $mid);
$cnt = count(explode(",", $extract_mids));

for ($i = 0; $i < $cnt; $i++) {
	$query = "SELECT note FROM CPS3740_2023S.Money_ruizbri WHERE mid=$mid[$i]";
	$result = mysqli_query($con, $query);
	while ($row = mysqli_fetch_array($result)) {
		$stored_note = $row['note'];
	}

	if ($cnote[$i] == $stored_note) {
		continue;
	}
	else {
		$sql = "UPDATE CPS3740_2023S.Money_ruizbri set note='$cnote[$i]', mydatetime=now() WHERE mid=$mid[$i] AND note != '$cnote[$i]'";
		$result = mysqli_query($con, $sql);
		if ($result) {
			$update++;
			echo "<br>Successfully updated transaction code: $sql";
		}
		else {
			echo mysqli_error($con);
		}
	}
}

if (isset($_GET["cdelete"])) {
	$delete = $_GET["cdelete"];
	$extract_del = implode(',', $delete);
	$cnt = count(explode(",", $extract_del));

	for ($i = 0; $i < $cnt; $i++) {
		$sql_del = "DELETE FROM CPS3740_2023S.Money_ruizbri WHERE mid = $mid[$i]";
		$result = mysqli_query($con, $sql_del);

		if ($result) {
			$remove++;
			echo "<br>Successfully deleted transaction code: $sql_del";
		}
		else {
			echo mysqli_error($con);
		}
	}
}

echo "<br>Finished updating $update transactions and removing $remove transactions.";
?>