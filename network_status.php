<?php
// Initialize the session
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$servername = "127.0.0.1:3307";
$username = "root";
$password = "password";
$dbname = "network";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "SELECT * FROM `server_switch`";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
}else{
	print "Error, Database Returned ZERO rows of data. Database may be corrupted";
}
$conn->close();
$row = $result->fetch_assoc();


////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////

//SERVER SWITCH CODE

////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////

print "
<h3>Server Switch Live Status</h3>
<table border=1>
<tr>
<td>
<style type=\"text/css\">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  overflow:hidden;padding:10px 5px;word-break:normal;}
.tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
.tg .tg-umv7{background-color:#34ff34;border-color:#ffffff;text-align:center;vertical-align:middle}
.tg .tg-zv4m{border-color:#ffffff;text-align:left;vertical-align:top}
.tg .tg-jc02{background-color:#efefef;border-color:#ffffff;text-align:center;vertical-align:middle}
.tg .tg-j1ly{background-color:#fe0000;border-color:#ffffff;text-align:center;vertical-align:middle}
.tg .tg-v0mg{border-color:#ffffff;text-align:center;vertical-align:middle}
</style>
<table class=\"tg\">
<thead>
  <tr>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Uptime:</b><br>".$row["uptime"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Serial #:</b><br>".$row["serial"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Switch Name:</b><br>".$row["name"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Firmware:</b><br>".$row["version"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
  </tr>
</thead>
<tbody>
  <tr>
    <td class=\"tg-zv4m\" rowspan=\"3\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\" rowspan=\"3\"></td>
  </tr>
  <tr>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port1_status"]==0){
		print "bad_port";
	}else if ($row["port1_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port1_name"]."<br>".$row["port1_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port2_status"]==0){
		print "bad_port";
	}else if ($row["port2_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port2_name"]."<br>".$row["port2_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port3_status"]==0){
		print "bad_port";
	}else if ($row["port3_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port3_name"]."<br>".$row["port3_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port4_status"]==0){
		print "bad_port";
	}else if ($row["port4_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port4_name"]."<br>".$row["port4_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port5_status"]==0){
		print "bad_port";
	}else if ($row["port5_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port5_name"]."<br>".$row["port5_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port6_status"]==0){
		print "bad_port";
	}else if ($row["port6_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port6_name"]."<br>".$row["port6_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port7_status"]==0){
		print "bad_port";
	}else if ($row["port7_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port7_name"]."<br>".$row["port7_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port8_status"]==0){
		print "bad_port";
	}else if ($row["port8_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port8_name"]."<br>".$row["port8_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port9_status"]==0){
		print "bad_port";
	}else if ($row["port9_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port9_name"]."<br>".$row["port9_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port10_status"]==0){
		print "bad_port";
	}else if ($row["port10_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port10_name"]."<br>".$row["port10_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port11_status"]==0){
		print "bad_port";
	}else if ($row["port11_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port11_name"]."<br>".$row["port11_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port12_status"]==0){
		print "bad_port";
	}else if ($row["port12_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port12_name"]."<br>".$row["port12_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port13_status"]==0){
		print "bad_port";
	}else if ($row["port13_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port13_name"]."<br>".$row["port13_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port14_status"]==0){
		print "bad_port";
	}else if ($row["port14_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port14_name"]."<br>".$row["port14_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port15_status"]==0){
		print "bad_port";
	}else if ($row["port15_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port15_name"]."<br>".$row["port15_vlan"]."</td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"";
	if ($row["port16_status"]==0){
		print "bad_port";
	}else if ($row["port16_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port16_name"]."<br>".$row["port16_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
  </tr>
  <tr>
    <td class=\"tg-v0mg\" colspan=\"33\"></td>
  </tr>
  <tr>
    <td class=\"tg-v0mg\" colspan=\"6\"></td>
    <td class=\"tg-v0mg\" colspan=\"5\"><center><b>Fan1 Speed:</b><br>".$row["fan1_speed"]." RPM</center></td>
    <td class=\"tg-jc02\"></td>
    <td class=\"tg-v0mg\" colspan=\"5\"><center><b>Fan2 Speed:</b><br>".$row["fan2_speed"]." RPM</center></td>
    <td class=\"tg-jc02\"></td>
    <td class=\"tg-v0mg\" colspan=\"5\"><center><b>MAC Temp:</b><br>".(($row["mac_temp"])*(9/5)+32)." F</center></td>
    <td class=\"tg-jc02\"></td>
    <td class=\"tg-v0mg\" colspan=\"5\"><b>PHY Temp:</b><br>".(($row["phy_temp"])*(9/5)+32)." F</center></td>
    <td class=\"tg-v0mg\" colspan=\"6\"></td>
  </tr>
</tbody>
</table>
</td>
</tr>
</table>";

////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////

//UTILITY SWITCH CODE

////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "SELECT * FROM `utility_switch`";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
}else{
	print "Error, Database Returned ZERO rows of data. Database may be corrupted";
}
$conn->close();
$row = $result->fetch_assoc();


print "
<table border=1>
<tr>
	<td><center><h3>Utility Switch Live Status</h3></center></td>
	<td><center><h3>First Floor Bedroom Switch Live Status</center></h3></td>
</tr>
</tr>

<tr>
<td>
<table class=\"tg\">
<thead>
  <tr>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Uptime:</b><br>".$row["uptime"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Serial #:</b><br>".$row["serial"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Switch Name:</b><br>".$row["name"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Firmware:</b><br>".$row["version"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
  </tr>
</thead>
<tbody>
  <tr>
    <td class=\"tg-zv4m\" rowspan=\"3\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\" rowspan=\"3\"></td>
  </tr>
  <tr>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port1_status"]==0){
		print "bad_port";
	}else if ($row["port1_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port1_name"]."<br>".$row["port1_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port2_status"]==0){
		print "bad_port";
	}else if ($row["port2_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port2_name"]."<br>".$row["port2_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port3_status"]==0){
		print "bad_port";
	}else if ($row["port3_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port3_name"]."<br>".$row["port3_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port4_status"]==0){
		print "bad_port";
	}else if ($row["port4_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port4_name"]."<br>".$row["port4_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port5_status"]==0){
		print "bad_port";
	}else if ($row["port5_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port5_name"]."<br>".$row["port5_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port6_status"]==0){
		print "bad_port";
	}else if ($row["port6_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port6_name"]."<br>".$row["port6_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port7_status"]==0){
		print "bad_port";
	}else if ($row["port7_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port7_name"]."<br>".$row["port7_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port8_status"]==0){
		print "bad_port";
	}else if ($row["port8_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port8_name"]."<br>".$row["port8_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-v0mg\"></td>
  </tr>
  <tr>
    <td class=\"tg-v0mg\" colspan=\"33\"></td>
  </tr>
  <tr>
    <td class=\"tg-v0mg\" colspan=\"6\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"5\"><center><b>Fan1 Speed:</b><br>".$row["fan1_speed"]." RPM</center></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"5\"><center><b>MAC Temp:</b><br>".(($row["mac_temp"])*(9/5)+32)." F</center></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"5\"><b>PHY Temp:</b><br>".(($row["phy_temp"])*(9/5)+32)." F</center></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"6\"></td>
  </tr>
</tbody>
</table>
</td>
<td>";



////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////

//FIRST FLOOR BEDROOM SWITCH CODE

////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "SELECT * FROM `first_floor_bedroom_switch_switch`";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
}else{
	print "Error, Database Returned ZERO rows of data. Database may be corrupted";
}
$conn->close();
$row = $result->fetch_assoc();


print "
<table class=\"tg\">
<thead>
  <tr>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Uptime:</b><br>".$row["uptime"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Serial #:</b><br>".$row["serial"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Switch Name:</b><br>".$row["name"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Firmware:</b><br>".$row["version"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
  </tr>
</thead>
<tbody>
  <tr>
    <td class=\"tg-zv4m\" rowspan=\"3\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\" rowspan=\"3\"></td>
  </tr>
  <tr>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port1_status"]==0){
		print "bad_port";
	}else if ($row["port1_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port1_name"]."<br>".$row["port1_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port2_status"]==0){
		print "bad_port";
	}else if ($row["port2_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port2_name"]."<br>".$row["port2_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port3_status"]==0){
		print "bad_port";
	}else if ($row["port3_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port3_name"]."<br>".$row["port3_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port4_status"]==0){
		print "bad_port";
	}else if ($row["port4_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port4_name"]."<br>".$row["port4_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port5_status"]==0){
		print "bad_port";
	}else if ($row["port5_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port5_name"]."<br>".$row["port5_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port6_status"]==0){
		print "bad_port";
	}else if ($row["port6_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port6_name"]."<br>".$row["port6_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port7_status"]==0){
		print "bad_port";
	}else if ($row["port7_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port7_name"]."<br>".$row["port7_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port8_status"]==0){
		print "bad_port";
	}else if ($row["port8_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port8_name"]."<br>".$row["port8_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-v0mg\"></td>
  </tr>
  <tr>
    <td class=\"tg-v0mg\" colspan=\"33\"></td>
  </tr>
  <tr>
    <td class=\"tg-v0mg\" colspan=\"6\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"5\"><center><b>Fan1 Speed:</b><br>".$row["fan1_speed"]." RPM</center></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"5\"><center><b>MAC Temp:</b><br>".(($row["mac_temp"])*(9/5)+32)." F</center></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"5\"><b>PHY Temp:</b><br>".(($row["phy_temp"])*(9/5)+32)." F</center></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"6\"></td>
  </tr>
</tbody>
</table>
</td>
</tr>
</table>";


////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////

//SECOND FLOOR BEDROOM SWITCH CODE

////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "SELECT * FROM `second_floor_bedroom_switch_switch`";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
}else{
	print "Error, Database Returned ZERO rows of data. Database may be corrupted";
}
$conn->close();
$row = $result->fetch_assoc();


print "
<table border=1>
<tr>
	<td><center><h3>Second Floor Bedroom Switch Live Status</h3></center></td>
	<td><center><h3>Server POE Switch Live Status</h3></center></td>
</tr>


<tr>
<td>
<table class=\"tg\">
<thead>
  <tr>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Uptime:</b><br>".$row["uptime"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Serial #:</b><br>".$row["serial"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Switch Name:</b><br>".$row["name"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Firmware:</b><br>".$row["version"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
  </tr>
</thead>
<tbody>
  <tr>
    <td class=\"tg-zv4m\" rowspan=\"3\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\" rowspan=\"3\"></td>
  </tr>
  <tr>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port1_status"]==0){
		print "bad_port";
	}else if ($row["port1_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port1_name"]."<br>".$row["port1_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port2_status"]==0){
		print "bad_port";
	}else if ($row["port2_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port2_name"]."<br>".$row["port2_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port3_status"]==0){
		print "bad_port";
	}else if ($row["port3_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port3_name"]."<br>".$row["port3_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port4_status"]==0){
		print "bad_port";
	}else if ($row["port4_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port4_name"]."<br>".$row["port4_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port5_status"]==0){
		print "bad_port";
	}else if ($row["port5_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port5_name"]."<br>".$row["port5_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port6_status"]==0){
		print "bad_port";
	}else if ($row["port6_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port6_name"]."<br>".$row["port6_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port7_status"]==0){
		print "bad_port";
	}else if ($row["port7_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port7_name"]."<br>".$row["port7_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port8_status"]==0){
		print "bad_port";
	}else if ($row["port8_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port8_name"]."<br>".$row["port8_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-v0mg\"></td>
  </tr>
  <tr>
    <td class=\"tg-v0mg\" colspan=\"33\"></td>
  </tr>
  <tr>
    <td class=\"tg-v0mg\" colspan=\"6\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"5\"><center><b>Fan1 Speed:</b><br>".$row["fan1_speed"]." RPM</center></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"5\"><center><b>MAC Temp:</b><br>".(($row["mac_temp"])*(9/5)+32)." F</center></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"5\"><b>PHY Temp:</b><br>".(($row["phy_temp"])*(9/5)+32)." F</center></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"6\"></td>
  </tr>
</tbody>
</table>
</td>
<td>";


////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////

//Server POE SWITCH CODE

////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "SELECT * FROM `server_POE_switch`";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
}else{
	print "Error, Database Returned ZERO rows of data. Database may be corrupted";
}
$conn->close();
$row = $result->fetch_assoc();


print "

<style type=\"text/css\">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  overflow:hidden;padding:10px 5px;word-break:normal;}
.tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
.tg .tg-9wq8{border-color:inherit;text-align:center;vertical-align:middle}
.tg .tg-zv4m{border-color:#ffffff;text-align:left;vertical-align:top}
.tg .tg-9m7n{background-color:#000000;border-color:#ffffff;color:#333333;text-align:left;vertical-align:top}
.tg .tg-v0mg{border-color:#ffffff;text-align:center;vertical-align:middle}
</style>
<table class=\"tg\">
<thead>
  <tr>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Uptime:</b><br>".$row["uptime"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Serial #:</b><br>".$row["serial"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Switch Name:</b><br>".$row["name"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\" colspan=\"5\"><center><b>Firmware:</b><br>".$row["version"]."</center></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
    <th class=\"tg-zv4m\"></th>
  </tr>
</thead>
<tbody>
  <tr>
    <td class=\"tg-zv4m\" rowspan=\"3\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\" rowspan=\"3\"></td>
  </tr>
  <tr>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port1_status"]==0){
		print "bad_port";
	}else if ($row["port1_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port1_name"]."<br>".$row["port1_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port2_status"]==0){
		print "bad_port";
	}else if ($row["port2_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port2_name"]."<br>".$row["port2_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port3_status"]==0){
		print "bad_port";
	}else if ($row["port3_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port3_name"]."<br>".$row["port3_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port4_status"]==0){
		print "bad_port";
	}else if ($row["port4_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port4_name"]."<br>".$row["port4_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port5_status"]==0){
		print "bad_port";
	}else if ($row["port5_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port5_name"]."<br>".$row["port5_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port6_status"]==0){
		print "bad_port";
	}else if ($row["port6_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port6_name"]."<br>".$row["port6_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port7_status"]==0){
		print "bad_port";
	}else if ($row["port7_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port7_name"]."<br>".$row["port7_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port8_status"]==0){
		print "bad_port";
	}else if ($row["port8_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port8_name"]."<br>".$row["port8_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port9_status"]==0){
		print "bad_port";
	}else if ($row["port9_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port9_name"]."<br>".$row["port9_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"";
	if ($row["port10_status"]==0){
		print "bad_port";
	}else if ($row["port10_status"]==1){
		print "tg-umv7";
	}
	print"\">".$row["port10_name"]."<br>".$row["port10_vlan"]."</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-zv4m\"></td>
    <td class=\"tg-v0mg\"></td>
  </tr>
  <tr>
    <td class=\"tg-v0mg\" colspan=\"33\"></td>
  </tr>
  <tr>
    <td class=\"tg-v0mg\" colspan=\"1\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"1\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"1\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"1\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"1\"></td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"10\"><b>POE Measured Power</b><br>".$row["port_POE_measured_power"]." watts</td>
    <td class=\"tg-v0mg\"></td>
    <td class=\"tg-v0mg\" colspan=\"10\"><b>POE Rated Power</b><br>".$row["port_POE_rated_power"]." watts</td>
    <td class=\"tg-v0mg\"></td>
  </tr>
</tbody>
</table>
</td>
</tr>
</table>";
?>