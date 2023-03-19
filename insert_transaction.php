<?php
echo "<HTML>\n";
# Connect to database
include("dbconfig.php");

$con = mysqli_connect($hostname,$username,$password,$dbname)
	or die("<br>Cannot connect to $dbname on $hostname, error: " . mysqli_connect_error());

$customer_id = $_GET['customer_id'];

$sql = "SELECT type, amount FROM CPS3740_2023S.Money_ruizbri WHERE cid='$customer_id'";
$result = mysqli_query($con, $sql);
$balance = 0;
while ($row = mysqli_fetch_array($result)) {
	$type = $row['type'];
	$amount = $row['amount'];
	if ($type == "D") {
		$balance += $amount;
	}
	else {
		$balance -= $amount;
	}
}

$code = mysqli_real_escape_string($con, $_GET['code']);
$amount = mysqli_real_escape_string($con,$_GET['amount']);
$source_id = $_GET['source_id'];
$cnote = mysqli_real_escape_string($con, $_GET['cnote']);
$type = $_GET['type'];
$now = date("Y-m-d H:i:s");

if ($amount <= 0) {
	echo "<br>Amount cannot be less than or equal to 0.";
}
else if (!isset($type)) {
	echo "<br>Deposit or Withdraw must be selected.";
}
else if ($source_id == "") {
	echo "<br>Must select a source.";
}
else if ($type == "W" && $balance < $amount) {
	echo "<br>Amount trying to withdraw is less than current balance.";
}
else {
	$sql = "INSERT INTO CPS3740_2023S.Money_ruizbri (code, cid, type, amount, mydatetime, note, sid) 
			VALUES ('$code', $customer_id, '$type', $amount, '$now', '$cnote', $source_id)";

	$result = mysqli_query($con, $sql);

	if ($result) {
		echo "Transaction entered successfully.";
		if ($type == "D") {
			$balance += $amount;
		}
		else {
			$balance -= $amount;
		}
		echo "<br>New balance: $balance";
	}
	else {
		echo mysqli_error($con);
		echo "<br>Failed to enter.";
	}
}
?>