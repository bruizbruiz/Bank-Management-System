<?php
# Connect to database
include("dbconfig.php");

$con = mysqli_connect($hostname,$username,$password,$dbname)
	or die("<br>Cannot connect to $dbname on $hostname, error: " . mysqli_connect_error());

$customer_id = $_GET['customer_id'];
$keyword = mysqli_real_escape_string($con, $_GET['keyword']);

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

echo "The transactions in customer <b>$customer_name</b> records matched keyword <b>$keyword</b> are: ";

# if keyword == "*" then SELECT ALL
if ($keyword == "*") {
	$sql = "SELECT mid, code, type, amount, mydatetime, note, s.name as source FROM CPS3740_2023S.Money_ruizbri m, CPS3740.Customers c, CPS3740.Sources s WHERE m.cid = c.id AND c.id = '$customer_id' AND s.id = m.sid";
	$result = mysqli_query($con, $sql);
}
# esle do this ->
else {
	$sql = "SELECT mid, code, type, amount, mydatetime, note, s.name as source FROM CPS3740_2023S.Money_ruizbri m, CPS3740.Customers c, CPS3740.Sources s WHERE m.cid = c.id AND c.id = '$customer_id' AND s.id = m.sid AND m.note LIKE '%$keyword%'";
	$result = mysqli_query($con, $sql);
}

echo "<TABLE border=1>\n";
echo "<TR><TH>ID<TH>Code<TH>Type<TH>Amount<TH>Source<TH>Date Time<TH>Note\n";
while($row = mysqli_fetch_array($result))
{
	$mid = $row["mid"];
	$code = $row["code"];
	$type = $row["type"];
	$amount = $row["amount"];
	$source = $row["source"];
	$date = $row["mydatetime"];
	$cnote = $row["note"];
	if ($type == "D") {
		$type = "Deposit";
		$color = "blue";
	}
	else {
		$type = "Withdraw";
		$color = "red";
		$amount = "-$amount";
	}

	echo "<TR><TD>$mid<TD>$code<TD>$type<TD><font color='$color'>$amount</font><TD>$source<TD>$date<TD>$cnote\n";

}
echo "</TABLE>\n";
echo "Total balance: <font color='blue'>$balance</font><br>";
?>