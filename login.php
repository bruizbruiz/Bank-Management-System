<?php
echo "<HTML>\n";
# Connect to database
include("dbconfig.php");

$con = mysqli_connect($hostname,$username,$password,$dbname)
	or die("<br>Cannot connect to $dbname on $hostname, error: " . mysqli_connect_error());
# Post user input
$login = mysqli_real_escape_string($con, $_POST["login"]);
$password = mysqli_real_escape_string($con, $_POST["password"]);

# SQL query for user login
$sql = "SELECT login, password FROM CPS3740.Customers WHERE login='$login'";
$result = mysqli_query($con, $sql);
$num = mysqli_num_rows($result);

if ($result) {
	# If there is a username that exists then continue    
    if ($num > 0) {
    	# make query into an array to fetch password and compare with user input
    	$arr = mysqli_fetch_array($result);
    	$customer_pass = $arr['password'];
    	if ($password == $customer_pass) {
    		# after login show name, browser/OS info, age, (street, city, zipcode), IP
			$sql = "SELECT id, img, name, CONCAT(street, ', ', city, ', ', state, ', ', zipcode) AS address, DATE_FORMAT(FROM_DAYS(DATEDIFF(now(), DOB)), '%Y') + 0 as age FROM CPS3740.Customers WHERE login='$login'";
			$result = mysqli_query($con, $sql);
			$arr = mysqli_fetch_array($result);
    		
    		# Finding IP and browser info
			$ip = $_SERVER['REMOTE_ADDR'];
			$browser = $_SERVER['HTTP_USER_AGENT'];

			$customer_id = $arr['id'];
			$customer_address = $arr['address'];
			$customer_name = $arr['name'];
			$customer_age = $arr['age'];
			$customer_img = $arr['img'];

			echo "<a href='logout.php'>User Logout</a>";
			echo "<br>Your IP is: $ip<br>";

			# Check ip address
			if ($ip == "10.136.33.242") {
				echo "You are from Kean University.";
			}
			else {
				echo "You are not from Kean University";
			}

			echo "<br>Your browser and OS are: $browser";

    		echo "<br>Welcome Customer: <b>$customer_name</b>";
    		echo "<br>age: $customer_age";
    					
    		echo "<br>Address: $customer_address<br>";
    		echo "<img src='data:image/jpeg;base64,".base64_encode($customer_img) ."' />";
    		echo "<br><hr>";

    		# Display transaction table
    		$sql = "SELECT count(mid) as count FROM CPS3740_2023S.Money_ruizbri m, CPS3740.Customers c WHERE m.cid = c.id AND c.login = '$login'";
    		$result = mysqli_query($con, $sql);
			$arr = mysqli_fetch_array($result);
			$count = $arr["count"];


    		$sql = "SELECT mid, code, cid, type, amount, mydatetime, note, sid, s.name as source FROM CPS3740_2023S.Money_ruizbri m, CPS3740.Customers c, CPS3740.Sources s WHERE m.cid = c.id AND c.login = '$login' AND s.id = sid";
    		$result = mysqli_query($con, $sql);

    		if ($count > 1) {
    			echo "There are <b>$count</b> transactions for customer <b>$customer_name</b>:";
    		}
    		else {
    			echo "There is <b>$count</b> transaction for customer <b>$customer_name</b>:";
			}

			# To display account balance
			$balance = 0;
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
		        	$balance += $amount;
		        }
		        else {
		        	$type = "Withdraw";
		        	$color = "red";
		        	$balance -= $amount;
		        	$amount = "-$amount";
		        }

		        echo "<TR><TD>$mid<TD>$code<TD>$type<TD><font color='$color'>$amount</font><TD>$source<TD>$date<TD>$cnote\n";
		    }
		    echo "</TABLE>\n";
		    echo "Total balance: <font color='blue'>$balance</font><br>";

		    # making transaction buttons
		    echo "<form action='add_transaction.php' method='GET'>";
		    echo "<input type='hidden' name='customer_id' value=$customer_id>";
		    echo "<br><input type='submit' value='Add transaction'>";
		    echo "</form>";

		    echo"<a href='display_transaction.php?customer_id=$customer_id'>Display and update transaction</a>";

		    echo "<form action='search_transaction.php' method='GET'>";
		    echo "<br>Keyword: <input type='text' name='keyword' required>";
			echo "<input type='hidden' name='customer_id' value=$customer_id>";
		    echo "<input type='submit' value='Search transaction'>";
		    echo "</form>";

    	}
    	else {
    		echo "<br>Username $login exists, wrong password.";
    	}
    }
    else {
		echo "<br>Username does not exist in the database.";
	}
}


?>