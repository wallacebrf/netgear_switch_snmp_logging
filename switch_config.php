<?php
///////////////////////////////////////////////////
//User Defined Variables
///////////////////////////////////////////////////

$config_file="/volume1/web/config/config_files/config_files_local/FB_switch_config.txt";
$use_login_sessions=true; //set to false if not using user login sessions
$form_submittal_destination="index.php?page=6&config_page=bedroom_switch"; //set to the destination the HTML form submit should be directed to
$page_title="First Floor Bedroom Switch Logging Configuration Settings";

///////////////////////////////////////////////////
//Beginning of configuration page
///////////////////////////////////////////////////
if($use_login_sessions){
	if($_SERVER['HTTPS']!="on") {

	$redirect= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	header("Location:$redirect"); } 

	// Initialize the session
	if(session_status() !== PHP_SESSION_ACTIVE) session_start();
	 
	// Check if the user is logged in, if not then redirect him to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location: login.php");
		exit;
	}
}
error_reporting(E_NOTICE);
include $_SERVER['DOCUMENT_ROOT']."/functions.php";
$fan1_error="";
$max_MAC_error="";
$max_PHY_error="";
$email_error="";
$email_interval_error="";
$switch_url_error="";
$switch_name_error="";
$ups_group_error="";
$influxdb_host_error="";
$influxdb_port_error="";
$influxdb_name_error="";
$influxdb_user_error="";
$influxdb_pass_error="";
$from_email_error="";
$auth_pass_error="";
$priv_pass_error="";
$snmp_user_error="";
$generic_error="";
$num_fans_error="";
$mariadb_user_error="";
$mariadb_password_error="";
$mariadb_db_error="";
$mariadb_table_error="";
		

if(isset($_POST['submit_server_switch'])){
	if (file_exists("$config_file")) {
		$data = file_get_contents("$config_file");
		$pieces = explode(",", $data);
	}
	
	[$script_enable, $generic_error] = test_input_processing($_POST['script_enable'], "", "checkbox", 0, 0);	
	
	[$fan1, $fan1_error] = test_input_processing($_POST['fan1'], $pieces[0], "numeric", 500, 10000);		  
	
	[$MAC, $max_MAC_error] = test_input_processing($_POST['MAC'], $pieces[1], "numeric", 65, 200);	
		  
	[$PHY, $max_PHY_error] = test_input_processing($_POST['PHY'], $pieces[2], "numeric", 65, 200);		  
		 
	[$email, $email_error] = test_input_processing($_POST['email'], $pieces[3], "email", 0, 0);	    
		
	[$email_interval, $generic_error] = test_input_processing($_POST['email_interval'], $pieces[4], "numeric", 60, 360);

	[$capture_interval, $generic_error] = test_input_processing($_POST['capture_interval'], $pieces[5], "numeric", 10, 60);
		 
	[$switch_url, $switch_url_error] = test_input_processing($_POST['switch_url'], $pieces[6], "ip", 0, 0);	  
	
	[$switch_name, $switch_name_error] = test_input_processing($_POST['switch_name'], $pieces[7], "name", 0, 0);	  
		  
	[$ups_group, $ups_group_error] = test_input_processing($_POST['ups_group'], $pieces[8], "name", 0, 0);	  
		  
	[$influxdb_host, $influxdb_host_error] = test_input_processing($_POST['influxdb_host'], $pieces[9], "ip", 0, 0);		 
		  
	[$influxdb_port, $influxdb_port_error] = test_input_processing($_POST['influxdb_port'], $pieces[10], "numeric", 0, 65000);	  
		  
	[$influxdb_name, $influxdb_name_error] = test_input_processing($_POST['influxdb_name'], $pieces[11], "name", 0, 0);	  
		 
	[$influxdb_user, $influxdb_user_error] = test_input_processing($_POST['influxdb_user'], $pieces[12], "name", 0, 0);	  
		 
	[$influxdb_pass, $influxdb_pass_error] = test_input_processing($_POST['influxdb_pass'], $pieces[13], "password", 0, 0);	  

	[$auth_pass, $auth_pass_error] = test_input_processing($_POST['auth_pass'], $pieces[17], "password", 0, 0);
	
	[$priv_pass, $priv_pass_error] = test_input_processing($_POST['priv_pass'], $pieces[18], "password", 0, 0);
	
	if ($_POST['snmp_privacy_protocol']=="AES" || $_POST['snmp_privacy_protocol']=="DES"){
		[$snmp_privacy_protocol, $generic_error] = test_input_processing($_POST['snmp_privacy_protocol'], $pieces[19], "name", 0, 0);
	}else{
		$snmp_privacy_protocol=$pieces[19];
	}
		   

	if ($_POST['snmp_auth_protocol']=="MD5" || $_POST['snmp_auth_protocol']=="SHA"){
		[$snmp_auth_protocol, $generic_error] = test_input_processing($_POST['snmp_auth_protocol'], $pieces[20], "name", 0, 0);
	}else{
		$snmp_auth_protocol=$pieces[20];
	}
	
	[$snmp_user, $snmp_user_error] = test_input_processing($_POST['snmp_user'], $pieces[21], "name", 0, 0);
	
	[$from_email, $from_email_error] = test_input_processing($_POST['from_email'], $pieces[22], "email", 0, 0);	
	
	[$num_fans, $num_fans_error] = test_input_processing($_POST['num_fans'], $pieces[23], "numeric", 0, 2);	
	
	[$mariadb_password, $mariadb_password_error] = test_input_processing($_POST['mariadb_password'], $pieces[24], "password", 0, 0);
	
	[$mariadb_user, $mariadb_user_error] = test_input_processing($_POST['mariadb_user'], $pieces[25], "name", 0, 0);
	
	[$mariadb_db, $mariadb_db_error] = test_input_processing($_POST['mariadb_db'], $pieces[26], "name", 0, 0);
	
	[$mariadb_table, $mariadb_table_error] = test_input_processing($_POST['mariadb_table'], $pieces[27], "name", 0, 0);
		 
	$put_contents_string="".$fan1.",".$MAC.",".$PHY.",".$email.",".$email_interval.",".$capture_interval.",".$switch_url.",".$switch_name.",".$ups_group.",".$influxdb_host.",".$influxdb_port.",".$influxdb_name.",".$influxdb_user.",".$influxdb_pass.",".$script_enable.",".round((($MAC-32)*(5/9)),0).",".round((($PHY-32)*(5/9)),0).",".$auth_pass.",".$priv_pass.",".$snmp_privacy_protocol.",".$snmp_auth_protocol.",".$snmp_user.",".$from_email.",".$num_fans.",".$mariadb_password.",".$mariadb_user.",".$mariadb_db.",".$mariadb_table."";
		  
	if (file_put_contents("$config_file",$put_contents_string )==FALSE){
		print "<font color=\"red\">Error - Could not save configuration</font>";
	}
		  
}else{
	if (file_exists("$config_file")) {
		$data = file_get_contents("$config_file");
		$pieces = explode(",", $data);
		$fan1=$pieces[0];
		$MAC=$pieces[1];
		$PHY=$pieces[2];
		$email=$pieces[3];
		$email_interval=$pieces[4];
		$capture_interval=$pieces[5];
		$switch_url=$pieces[6];
		$switch_name=$pieces[7];
		$ups_group=$pieces[8];
		$influxdb_host=$pieces[9];
		$influxdb_port=$pieces[10];
		$influxdb_name=$pieces[11];
		$influxdb_user=$pieces[12];
		$influxdb_pass=$pieces[13];
		$script_enable=$pieces[14];
		$auth_pass=$pieces[17];
		$priv_pass=$pieces[18];
		$snmp_privacy_protocol=$pieces[19];
		$snmp_auth_protocol=$pieces[20];
		$snmp_user=$pieces[21];
		$from_email=$pieces[22];
		$num_fans=$pieces[23];
		$mariadb_password=$pieces[24];
		$mariadb_user=$pieces[25];
		$mariadb_db=$pieces[26];
		$mariadb_table=$pieces[27];
	}else{
		$fan1=500;
		$MAC=32;
		$PHY=32;
		$email="admin@admin.com";
		$email_interval=60;
		$capture_interval=60;
		$switch_url="localhost";
		$switch_name="";
		$ups_group="NAS";
		$influxdb_host=0;
		$influxdb_port=8086;
		$influxdb_name="db";
		$influxdb_user="admin";
		$influxdb_pass="password";
		$script_enable=0;
		$MAC_c=0;
		$PHY_c=0;
		$auth_pass="password1";
		$priv_pass="password2";
		$snmp_privacy_protocol="DES";
		$snmp_auth_protocol="MD5";
		$snmp_user="admin";
		$from_email="admin@admin.com";
		$num_fans=0;
		$mariadb_password="password3";
		$mariadb_user="user";
		$mariadb_db="database";
		$mariadb_table="table";
			
		$put_contents_string="".$fan1.",".$MAC.",".$PHY.",".$email.",".$email_interval.",".$capture_interval.",".$switch_url.",".$switch_name.",".$ups_group.",".$influxdb_host.",".$influxdb_port.",".$influxdb_name.",".$influxdb_user.",".$influxdb_pass.",".$script_enable.",".$MAC_c.",".$PHY_c.",".$auth_pass.",".$priv_pass.",".$snmp_privacy_protocol.",".$snmp_auth_protocol.",".$snmp_user.",".$from_email.",".$num_fans.",".$mariadb_password.",".$mariadb_user.",".$mariadb_db.",".$mariadb_table."";
		  
		if (file_put_contents("$config_file",$put_contents_string )==FALSE){
			print "<font color=\"red\">Error - Could not save configuration</font>";
		}
	}
}
	   
print "
<br>
<fieldset>
	<legend>
		<h3>$page_title</h3>
	</legend>	
	<table border=\"0\">
		<tr>
			<td>";
				if ($script_enable==1){
					print "<font color=\"green\"><h3>Script Status: Active</h3></font>";
				}else{
					print "<font color=\"red\"><h3>Script Status: Inactive</h3></font>";
				}
print "		</td>
		</tr>
		<tr>
			<td align=\"left\">
				<form action=\"$form_submittal_destination\" method=\"post\">
					<p><input type=\"checkbox\" name=\"script_enable\" value=\"1\" ";
					   if ($script_enable==1){
							print "checked";
					   }
					   print ">Enable Entire Script?
					 </p>
					 <br>
					<b>FAN SETTINGS</b>
					<p>->Minimum Fan Speed: <input type=\"text\" name=\"fan1\" value=".$fan1."> ".$fan1_error."</p>
					<p>->Number of Cooling Fans: <select name=\"num_fans\">";
						if ($num_fans=="0"){
							print "<option value=\"0\" selected>0</option>
							<option value=\"1\">1</option>
							<option value=\"2\">2</option>";
						}else if ($num_fans=="1"){
							print "<option value=\"0\">0</option>
							<option value=\"1\" selected>1</option>
							<option value=\"2\">2</option>";
						}else if ($num_fans=="2"){
							print "<option value=\"0\">0</option>
							<option value=\"1\">1</option>
							<option value=\"2\" selected>2</option>";
						}
print "					</select>
					</p>
					<br>
					<b>TEMPERATURE LIMITS</b>
					<p>->Max MAC Temperature [F]: <input type=\"text\" name=\"MAC\" value=".$MAC."> [C]: ".round((($MAC-32)*(5/9)),2)." ".$max_MAC_error."</p>
					<p>->Max PHY Temperature [F]: <input type=\"text\" name=\"PHY\" value=".$PHY."> [C]: ".round((($PHY-32)*(5/9)),2)." ".$max_PHY_error."</p>
					<br>
					<b>EMAIL SETTINGS</b>
					<p>->Alert Email Recipient: <input type=\"text\" name=\"email\" value=".$email."> ".$email_error."</p>
					<p>->From Email Address: <input type=\"text\" name=\"from_email\" value=".$from_email."> ".$from_email_error."</p>
					<p>->Email Delay Period [Hours]: <select name=\"email_interval\">";
					for ($x=1;$x<=6;$x++){
						$minuets=$x*60;
						print "<option value=\"".$minuets."\" ";
						if ($email_interval==$minuets){
							print "selected";
						}
						print ">".$x."</option>";
					}
print "					</select>
					</p>
					<br>
					<b>INFLUXDB SETTINGS</b>
					<p>->URL of switch to gather SNMP Information from: <input type=\"text\" name=\"switch_url\" value=".$switch_url."> ".$switch_url_error."</p>
					<p>->Name of switch: <input type=\"text\" name=\"switch_name\" value=".$switch_name."> ".$switch_name_error."</p>
					<p>->IP of Influx DB: <input type=\"text\" name=\"influxdb_host\" value=".$influxdb_host."> ".$influxdb_host_error."</p>
					<p>->PORT of Influx DB: <input type=\"text\" name=\"influxdb_port\" value=".$influxdb_port."> ".$influxdb_port_error."</p>
					<p>->Database to use within Influx DB: <input type=\"text\" name=\"influxdb_name\" value=".$influxdb_name."> ".$influxdb_name_error."</p>
					<p>->User Name of Influx DB: <input type=\"text\" name=\"influxdb_user\" value=".$influxdb_user."> ".$influxdb_user_error."</p>
					<p>->Password of Influx DB: <input type=\"text\" name=\"influxdb_pass\" value=".$influxdb_pass."> ".$influxdb_pass_error."</p>
					<br>
					<b>SNMP SETTINGS</b>
					<p>->SNMP user: <input type=\"text\" name=\"snmp_user\" value=".$snmp_user."> ".$snmp_user_error."</p>
					<p>->SNMP Authorization Password: <input type=\"text\" name=\"auth_pass\" value=".$auth_pass."> ".$auth_pass_error."</p>
					<p>->SNMP Privacy Password: <input type=\"text\" name=\"priv_pass\" value=".$priv_pass."> ".$priv_pass_error."</p>
					<p>->Authorization Protocol: <select name=\"snmp_auth_protocol\">";
					if ($snmp_auth_protocol=="MD5"){
						print "<option value=\"MD5\" selected>MD5</option>
						<option value=\"SHA\">SHA</option>";
					}else if ($snmp_auth_protocol=="SHA"){
						print "<option value=\"MD5\">MD5</option>
						<option value=\"SHA\" selected>SHA</option>";
					}
print "				</select></p>
					<p>->Privacy Protocol: <select name=\"snmp_privacy_protocol\">";
					if ($snmp_privacy_protocol=="AES"){
						print "<option value=\"AES\" selected>AES</option>
						<option value=\"DES\">DES</option>";
					}else if ($snmp_privacy_protocol=="DES"){
						print "<option value=\"AES\">AES</option>
						<option value=\"DES\" selected>DES</option>";
					}
print "				</select></p>
					<b>MARIADB SETTINGS</b>
					<p>->DB Username: <input type=\"text\" name=\"mariadb_user\" value=".$mariadb_user."> ".$mariadb_user_error."</p>
					<p>->DB Password: <input type=\"text\" name=\"mariadb_password\" value=".$mariadb_password."> ".$mariadb_password_error."</p>
					<p>->DB Name: <input type=\"text\" name=\"mariadb_db\" value=".$mariadb_db."> ".$mariadb_db_error."</p>
					<p>->DB Table: <input type=\"text\" name=\"mariadb_table\" value=".$mariadb_table."> ".$mariadb_table_error."</p>
					<center><input type=\"submit\" name=\"submit_server_switch\" value=\"Submit\" /></center>
				</form>
			</td>
		</tr>
	</table>
</fieldset>";
?>