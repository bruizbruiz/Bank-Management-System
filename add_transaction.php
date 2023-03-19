<?php
echo "<HTML>\n";
echo "<a href='logout.php'>User Logout</a><br><br>";
# Connect to database
include("dbconfig.php");

$con = mysqli_connect($hostname,$username,$password,$dbname)
	or die("<br>Cannot connect to $dbname on $hostname, error: " . mysqli_connect_error());

$customer_id = $_GET["customer_id"];

$sql = "SELECT name FROM CPS3740.Customers WHERE id='$customer_id'";
$result = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($result)) {
	$customer_name = $row['name'];
}

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


# Making form for adding a transaction
echo "<font size='+1'><b>Add Transaction</b></font>";
echo "<br><b>$customer_name</b>'s current balance is: <b>$balance</b>.<br>";

echo "<form action='insert_transaction.php' method='GET' required>";
echo "Transaction code: <input type='text' name='code' required><br>";
echo "<input type='radio' id='Deposit' name='type' value='D'>Deposit";
echo "<input type='radio' id='Withdraw' name='type' value='W'>Withdraw";

echo "<br> Amount: <input type='text' name='amount' required>";
echo "<input type='hidden' name='customer_id' value=$customer_id>";

$sql = "SELECT id, name FROM CPS3740.Sources";
$result = mysqli_query($con, $sql);
echo "<br>Select a source: <select name='source_id'>";
echo "<option value=''></option>";
while ($row = mysqli_fetch_array($result)) {
	$source_id = $row["id"];
	$source = $row["name"];

	echo "<option value=$source_id>$source</option>";
}
echo "</select>";
echo "<br>Note: <input type='text' name='cnote'><br>";
echo "<input type='submit' value='Submit'>";
echo "</form>";



?>