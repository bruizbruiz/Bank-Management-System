<?php
# Connect to database
include("dbconfig.php");

$con = mysqli_connect($hostname,$username,$password,$dbname)
	or die("<br>Cannot connect to $dbname on $hostname, error: " . mysqli_connect_error());

$customer_id = $_GET['customer_id'];

$sql = "SELECT s.id as sid, mid, code, type, amount, mydatetime, note, s.name as source FROM CPS3740_2023S.Money_ruizbri m, CPS3740.Customers c, CPS3740.Sources s WHERE m.cid = c.id AND c.id = '$customer_id' AND s.id = m.sid";
$result = mysqli_query($con, $sql);

echo "<a href='logout.php'>User Logout</a><br><br>";
echo "You can only update the <b>Note</b> column.";

echo "<form action='update_transaction.php' method='GET'>";
$balance = 0;
$i = 0;
echo "<TABLE border=1>\n";
echo "<TR><TH>ID<TH>Code<TH>Type<TH>Amount<TH>Source<TH>Date Time<TH>Note<TH>Delete\n";
while($row = mysqli_fetch_array($result))
{
	$mid = $row["mid"];
	$code = $row["code"];
	$type = $row["type"];
	$source_id = $row["sid"];
	$amount = $row["amount"];
	$source = $row["source"];
	$date = $row["mydatetime"];
	$cnote = $row["note"];
	if ($type == "D") {
		$type = "Deposit";
		$color = "blue";
		$balance += $amount;
	}
	else {
		$type = "Withdraw";
		$color = "red";
		$balance -= $amount;
		$amount = "-$amount";
	}
	echo "<TR><TD><input type='hidden' value=$customer_id name='customer_id[$i]'><input type='hidden' value=$mid name='mid[$i]'>$mid<TD>$code<TD>$type<TD><font color='$color'>$amount</font><TD><input type='hidden' value=$source_id name='source_id[$i]'>$source<TD>$date<TD bgcolor='yellow'><input type='text' style='background-color:yellow' value='$cnote' name='cnote[$i]'><TD><input type='checkbox' name='cdelete[$i]' value='$mid'>\n";

	$i++;

}

echo "</TABLE>\n";
echo "Total balance: <font color='blue'>$balance</font><br>";
echo "<br><input type='submit' value='Update transaction'>";
echo "</form>";
?>