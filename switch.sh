#!/bin/bash
#Version 12/17/2023

#This script pulls SNMP data off Netgear XS7xx series switches like the XS708T, XS716T etc

#############################################
#VERIFICATIONS
#############################################
#1.) data is collected into influx and mariaDB properly............................................................. VERFIED 1/18/2023
#2.) SNMP errors:
	#a.) bad SNMP username causes script to shutdown with email..................................................... VERFIED 1/18/2023
	#b.) bad SNMP authpass causes script to shutdown with email..................................................... VERFIED 1/18/2023
	#c.) bad SNMP privacy pass causes script to shutdown with email................................................. VERFIED 1/18/2023
	#d.) bad SNMP ip address causes script to shutdown with email................................................... VERFIED 1/18/2023
	#e.) bad SNMP port causes script to shutdown with email......................................................... VERFIED 1/18/2023
	#f.) error emails a through e above only are sent within the allowed time interval.............................. VERFIED 1/18/2023
#3.) verify that when "sendmail" is unavailable, emails are not sent, and the appropriate warnings are displayed.... VERFIED 1/18/2023
#4.) verify script behavior when config file is unavailable......................................................... VERFIED 1/18/2023
#5.) verify script behavior when config file has wrong number of arguments.......................................... VERFIED 1/18/2023
#6.) verify script behavior when the target device is not available / responding to pings........................... VERFIED 1/18/2023
#7.) verify email send when MAC temp is too high and only sends emails within allowed time interval................. VERFIED 1/18/2023
#8.) verify email send when PHY temp is too high and only sends emails within allowed time interval................. VERFIED 1/18/2023
#9.) verify email send when fan1 speed is too low and only sends emails within allowed time interval................  VERFIED 1/18/2023
#10.) verify email send when fan2 speed is too low and only sends emails within allowed time interval...............  VERFIED 1/18/2023

###########################################
#USER VARIABLES
###########################################
maria_location="/volume1/@appstore/MariaDB10/usr/local/mariadb10/bin" #location where mariaDB is installed
lock_file="/volume1/web/logging/notifications/1st_floor_bed_switch_testing.lock"
config_file="/volume1/web/config/config_files/config_files_local/FB_switch_config.txt"
last_time_email_sent="/volume1/web/logging/notifications/1st_floor_bedroom_switch_last_email_sent.txt"
email_contents="/volume1/web/logging/notifications/1st_floor_bedroom_switch_email_contents.txt"


#########################################################
#EMAIL SETTINGS USED IF CONFIGURATION FILE IS UNAVAILABLE
#These variables will be overwritten with new corrected data if the configuration file loads properly. 
email_address="email@email.com"
from_email_address="email@email.com"
#########################################################

#track if any of these items have caused the script to send an email. if an email was sent, then at the end of script we will save the time it was sent
mac0=0
phy0=0
fan0=0
fan1=0
snmp_error=0

#########################################################
#this function pings google.com to confirm internet access is working prior to sending email notifications 
#########################################################
check_internet() {
ping -c1 "www.google.com" > /dev/null #ping google.com									
	local status=$?
	if ! (exit $status); then
		false
	else
		true
	fi
}

#########################################################
#this function is used to send notifications
#########################################################
function send_email(){
#email_last_sent_log_file=${1}			this file contains the UNIX time stamp of when the email is sent so we can track how long ago an email was last sent
#message_text=${2}						this string of text contains the body of the email message
#email_subject=${3}						this string of text contains the email subject line
#email_contents_file=${4}				this file is where the contents of the email are saved prior to sending and it contains the log of the email transmission, either will indicated email sent successfully or will include the error details
#error_message=${5}						this string of text is only displayed when the script is executed from the CLI, it will be part of the error message if the email is not sent correctly
#email_interval=${6}					this numerical value will control how many minutes must pass before the next email is allowed to be sent
	local message_tracker=""
	local time_diff=0
	echo "${2}"
	echo ""
	if check_internet; then
		if [ $sendmail_installed -eq 1 ]; then
			local current_time=$( date +%s )
			if [ -r "${1}" ]; then #file is available and readable 
				read message_tracker < "${1}"
				time_diff=$((( $current_time - $message_tracker ) / 60 ))
			else
				echo -n "$current_time" > "${1}"
				time_diff=$(( ${6} + 1 ))
			fi
				
			if [ $time_diff -ge ${6} ]; then
				local now=$(date +"%T")
				echo "the email has not been sent in over ${6} minutes, re-sending email"
				echo "from: $from_email_address " > "${4}"
				echo "to: $email_address " >> "${4}"
				echo "subject: ${3}" >> "${4}"
				echo "" >> "${4}"
				echo "$now - ${2}" >> "${4}" #adding the mailbody text. 
				local email_response=$(sendmail -t < "${4}"  2>&1)
				if [[ "$email_response" == "" ]]; then
					echo "" |& tee -a "${4}"
					echo "Email Sent Successfully" |& tee -a "${4}"
					message_tracker=$current_time
					time_diff=0
					echo -n "$message_tracker" > "${1}"
				else
					echo "Warning, an error occurred while sending the ${5} notification email. the error was: $email_response" |& tee -a "${4}"
				fi
			else
				echo "Only $time_diff minuets have passed since the last notification, email will be sent every ${6} minutes. $(( ${6} - $time_diff )) Minutes Remaining Until Next Email"
			fi
		else
			echo "Unable to send email, \"sendmail\" command is unavailable" |& tee -a "${4}"
		fi
	else
		echo "Internet is not available, skipping sending email" |& tee -a "${4}"
	fi
}


#create a lock file in the ramdisk directory to prevent more than one instance of this script from executing  at once
if ! mkdir "$lock_file"; then
	echo "Failed to acquire lock.\n" >&2
	exit 1
fi
trap 'rm -rf "$lock_file"' EXIT #remove the lockdir on exit

#verify MailPlus Server package is installed and running as the "sendmail" command is not installed in Synology by default. the MailPlus Server package is required
install_check=$(/usr/syno/bin/synopkg list | grep MailPlus-Server)
	if [ "$install_check" = "" ];then
	echo "WARNING!  ----   MailPlus Server NOT is installed, cannot send email notifications"
	sendmail_installed=0
else
	#echo "MailPlus Server is installed, verify it is running and not stopped"
	status=$(/usr/syno/bin/synopkg is_onoff "MailPlus-Server")
	if [ "$status" = "package MailPlus-Server is turned on" ]; then
		sendmail_installed=1
	else
		sendmail_installed=0
		echo "WARNING!  ----   MailPlus Server NOT is running, cannot send email notifications"
	fi
fi

#reading in variables from configuration file
if [ -r "$config_file" ]; then
	#file is available and readable 
	read input_read < "$config_file"
	explode=(`echo $input_read | sed 's/,/\n/g'`)
	
	#verify the correct number of configuration parameters are in the configuration file
	if [[ ! ${#explode[@]} == 27 ]]; then
		send_email "$last_time_email_sent" "WARNING - the configuration file is incorrect or corrupted. It should have 27 parameters, it currently has ${#explode[@]} parameters." "ALERT script \"${0##*/}\" - Configuration file is corrupt or mis-configured" "SNMP Error" $email_interval
		exit 1
	fi
	
	minimum_fan_speed=${explode[0]}
	max_mac_temp_f=${explode[1]}
	max_phy_temp_f=${explode[2]}
	email_address=${explode[3]}
	email_interval=${explode[4]} #time between emails in seconds
	capture_interval=${explode[5]}
	switch_url=${explode[6]}
	switch_name=${explode[7]}
	ups_group="Switches"
	influxdb_host=${explode[8]}
	influxdb_port=${explode[9]}
	influxdb_name=${explode[10]}
	influxdb_user=${explode[11]}
	influxdb_pass=${explode[12]}
	script_enable=${explode[13]}
	max_mac_temp=${explode[14]}
	max_phy_temp=${explode[15]}
	AuthPass1=${explode[16]}
	PrivPass2=${explode[17]}
	snmp_privacy_protocol=${explode[18]}
	snmp_auth_protocol=${explode[19]}
	snmp_user=${explode[20]}
	from_email_address=${explode[21]}
	num_fans=${explode[22]}
	maria_password=${explode[23]}
	maria_user=${explode[24]}
	mariadb_db=${explode[25]}
	mariadb_table=${explode[26]}
	target_available=0
	
		if [ $script_enable -eq 1 ]
	then
	
		#let's make sure the target for SNMP walking is available on the network 
		ping -c1 $switch_url > /dev/null
		if [ $? -eq 0 ]
		then
				target_available=1 #network coms are good
		else
			#ping failed
			#since the ping failed, let's do just one more ping juts in case
			ping -c1 $switch_url > /dev/null
			if [ $? -eq 0 ]
			then
				target_available=1 #network coms are good
			else
				target_available=0 #network coms appear to be down, stop script
			fi
		fi
		
		capture_system="true" #model information, temperature, update status, 

		if [ $target_available -eq 1 ]
		then		

			#loop the script 
			total_executions=$(( 60 / $capture_interval))
			echo "Capturing $total_executions times"
			i=0
			while [ $i -lt $total_executions ]; do
				
				#Create empty URL
				post_url=
				post_url_network_map=

				#GETTING VARIOUS SYSTEM INFORMATION
				if (${capture_system,,} = "true"); then
					
					measurement="Switch_system"
					
					#collect the first SNMP data point and make sure it is valid or exit the script
					if [ "$snmp_user" = "" ];then
						send_email "$last_time_email_sent" "SNMP Username is BLANK, please configure the SNMP settings" "ALERT Switch at IP $switch_url named \"$switch_name\" appears to have an issue with SNMP" "$email_contents" "SNMP Error" $email_interval
						exit 1
					else
						if [ "$AuthPass1" = "" ];then
							send_email "$last_time_email_sent" "SNMP Authentication Password is BLANK, please configure the SNMP settings" "ALERT Switch at IP $switch_url named \"$switch_name\" appears to have an issue with SNMP" "$email_contents" "SNMP Error" $email_interval
							exit 1
						else
							if [ "$PrivPass2" = "" ];then
								send_email "$last_time_email_sent" "SNMP Privacy Password is BLANK, please configure the SNMP settings" "ALERT Switch at IP $switch_url named \"$switch_name\" appears to have an issue with SNMP" "$email_contents" "SNMP Error" $email_interval
								exit 1
							else
								MAC_temp=$(snmpwalk -v3 -r 1 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 .1.3.6.1.4.1.4526.11.43.1.8.1.5.1.0 -Oqv 2>&1)
								
								#since $MAC_temp is the first time we have performed a SNMP request to the switch, let's make sure we did not receive any errors that could be caused by things like bad passwords, bad username, incorrect auth or privacy types etc
								#if we receive an error now, then something is wrong with the SNMP settings and this script will not be able to function so we should exit out of it. 
								#the six main error are
								#1 - too short of a password
									#Error: passphrase chosen is below the length requirements of the USM (min=8).
									#snmpwalk:  (The supplied password length is too short.)
									#Error generating a key (Ku) from the supplied privacy pass phrase.

								#2
									#Timeout: No Response from localhost:161

								#3
									#snmpwalk: Unknown user name

								#4
									#snmpwalk: Authentication failure (incorrect password, community or key)
									
								#5
									#we get nothing, the results are blank
									
								#6	#snmpwalk: Timeout

								
								if [[ "$MAC_temp" == "Error:"* ]]; then #will search for the first error type
									send_email "$last_time_email_sent" "WARNING -- The SNMP Auth password and or the Privacy password supplied is below the minimum 8 characters required." "ALERT Switch at IP $switch_url named \"$switch_name\" appears to have an issue with SNMP" "$email_contents" "SNMP Error" $email_interval
									exit 1
								fi
								
								if [[ "$MAC_temp" == "Timeout:"* ]]; then #will search for the second error type
									send_email "$last_time_email_sent" "WARNING -- The SNMP target did not respond. This could be the result of a bad SNMP privacy password, the wrong IP address, the wrong port, or SNMP services not being enabled on the target device" "ALERT Switch at IP $switch_url named \"$switch_name\" appears to have an issue with SNMP" "$email_contents" "SNMP Error" $email_interval
									exit 1
								fi
								
								if [[ "$MAC_temp" == "snmpwalk: Unknown user name"* ]]; then #will search for the third error type 
									send_email "$last_time_email_sent" "WARNING -- The supplied username is incorrect. Exiting Script" "ALERT Switch at IP $switch_url named \"$switch_name\" appears to have an issue with SNMP" "$email_contents" "SNMP Error" $email_interval
									exit 1
								fi
								
								if [[ "$MAC_temp" == "snmpwalk: Authentication failure (incorrect password, community or key)"* ]]; then #will search for the fourth error type
									send_email "$last_time_email_sent" "WARNING -- The Authentication protocol or password is incorrect." "ALERT Switch at IP $switch_url named \"$switch_name\" appears to have an issue with SNMP" "$email_contents" "SNMP Error" $email_interval
									exit 1
								fi
								
								if [[ "$MAC_temp" == "" ]]; then #will search for the fifth error type
									send_email "$last_time_email_sent" "WARNING -- Something is wrong with the SNMP settings, the results returned a blank/empty value." "ALERT Switch at IP $switch_url named \"$switch_name\" appears to have an issue with SNMP" "$email_contents" "SNMP Error" $email_interval
									exit 1
								fi
								
								if [[ "$MAC_temp" == "snmpwalk: Timeout"* ]]; then #will search for the second error type
									send_email "$last_time_email_sent" "WARNING -- The SNMP target did not respond. This could be the result of a bad SNMP privacy password, the wrong IP address, the wrong port, or SNMP services not being enabled on the target device" "ALERT Switch at IP $switch_url named \"$switch_name\" appears to have an issue with SNMP" "$email_contents" "SNMP Error" $email_interval
									exit 1
								fi
							fi
						fi
					fi

					PHY_temp=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 .1.3.6.1.4.1.4526.11.43.1.8.1.5.1.1 -Oqv`
						
					MAC_temp_STATUS=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 .1.3.6.1.4.1.4526.11.43.1.15.1.1.1 -Oqv`
						
					PHY_temp_STATUS=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 .1.3.6.1.4.1.4526.11.43.1.15.1.2.1 -Oqv`
					
					if [ $num_fans -eq 0 ]; then
						FAN1_SPEED=0
						FAN2_SPEED=0
						FAN1_DUTY=0
						FAN2_DUTY=0
						FAN1_STATUS=0
						FAN2_STATUS=0
					fi
					
					if [ $num_fans -eq 1 ]; then
						FAN1_SPEED=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 .1.3.6.1.4.1.4526.11.43.1.6.1.4.1.0 -Oqv`
						FAN2_SPEED=0
						FAN1_DUTY=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 .1.3.6.1.4.1.4526.11.43.1.6.1.5.1.0 -Oqv`
						FAN2_DUTY=0
						FAN1_STATUS=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 .1.3.6.1.4.1.4526.11.43.1.6.1.6.1.0 -Oqv`
						FAN2_STATUS=0
					fi
					
					if [ $num_fans -eq 2 ]; then
						FAN1_SPEED=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 .1.3.6.1.4.1.4526.11.43.1.6.1.4.1.0 -Oqv`
						FAN2_SPEED=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 .1.3.6.1.4.1.4526.11.43.1.6.1.4.1.1 -Oqv`
						FAN1_DUTY=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 .1.3.6.1.4.1.4526.11.43.1.6.1.5.1.0 -Oqv`
						FAN2_DUTY=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 .1.3.6.1.4.1.4526.11.43.1.6.1.5.1.1 -Oqv`
						FAN1_STATUS=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 .1.3.6.1.4.1.4526.11.43.1.6.1.6.1.0 -Oqv`
						FAN2_STATUS=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 .1.3.6.1.4.1.4526.11.43.1.6.1.6.1.1 -Oqv`
					fi	

					uptime=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 1.3.6.1.2.1.1.3 -Oqv`	

					name=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 1.3.6.1.2.1.1.5 -Oqv`

					serial=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 1.3.6.1.4.1.4526.11.1.1.1.4.0 -Oqv`

					version=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 1.3.6.1.4.1.4526.11.1.1.1.13.0 -Oqv`

					port_vlan_ID_raw=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 1.3.6.1.2.1.17.7.1.4.5.1.1 -Oqv`

					port_vlan_ID=(`echo $port_vlan_ID_raw | sed 's/,/\n/g'`)
					
					port_names_raw=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 1.3.6.1.2.1.47.1.1.1.1.7 -Oqv`

					port_names=(`echo $port_names_raw | sed 's/,/\n/g'`)

					ii=0
					while [ $ii -lt 20 ]; do
						port_names[$ii]="${port_names[$ii]//"\""}"
						let ii=ii+1
					done
					
					port_status_raw=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 1.3.6.1.2.1.2.2.1.8 -Oqv`

					explode=(`echo $port_status_raw | sed 's/,/\n/g'`)

					ii=0
					while [ $ii -lt 16 ]; do
						if [ "${explode[$ii]}" = "up" ]; then
							port_status[$ii]=1
						else
							port_status[$ii]=0
						fi
						let ii=ii+1
					done
					
					#port data received in octet, or one byte
					port_data_received_raw=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 1.3.6.1.2.1.31.1.1.1.6 -Oqv`

					port_data_received=(`echo $port_data_received_raw | sed 's/,/\n/g'`)
					
					#port data sent in octet, or one byte
					port_data_sent_raw=`snmpwalk -v3 -l authPriv -u $snmp_user -a $snmp_auth_protocol -A $AuthPass1 -x $snmp_privacy_protocol -X $PrivPass2 $switch_url:161 1.3.6.1.2.1.31.1.1.1.10 -Oqv`

					port_data_sent=(`echo $port_data_sent_raw | sed 's/,/\n/g'`)
					
					#System details to post
					
					post_url=$post_url"$measurement,switch_name=$switch_name MAC_temp=$MAC_temp,PHY_temp=$PHY_temp,MAC_temp_STATUS=$MAC_temp_STATUS,PHY_temp_STATUS=$PHY_temp_STATUS,FAN1_SPEED=$FAN1_SPEED,FAN2_SPEED=$FAN2_SPEED,FAN1_DUTY=$FAN1_DUTY,FAN2_DUTY=$FAN2_DUTY,FAN1_STATUS=$FAN1_STATUS,FAN2_STATUS=$FAN2_STATUS,port1_status=${port_status[0]},port2_status=${port_status[1]},port3_status=${port_status[2]},port4_status=${port_status[3]},port5_status=${port_status[4]},port6_status=${port_status[5]},port7_status=${port_status[6]},port8_status=${port_status[7]},port9_status=${port_status[8]},port10_status=${port_status[9]},port11_status=${port_status[10]},port12_status=${port_status[11]},port13_status=${port_status[12]},port14_status=${port_status[13]},port15_status=${port_status[14]},port16_status=${port_status[15]},port1_data_received=${port_data_received[0]},port2_data_received=${port_data_received[1]},port3_data_received=${port_data_received[2]},port4_data_received=${port_data_received[3]},port5_data_received=${port_data_received[4]},port6_data_received=${port_data_received[5]},port7_data_received=${port_data_received[6]},port8_data_received=${port_data_received[7]},port9_data_received=${port_data_received[8]},port10_data_received=${port_data_received[9]},port11_data_received=${port_data_received[10]},port12_data_received=${port_data_received[11]},port13_data_received=${port_data_received[12]},port14_data_received=${port_data_received[13]},port15_data_received=${port_data_received[14]},port16_data_received=${port_data_received[15]},port1_data_sent=${port_data_sent[0]},port2_data_sent=${port_data_sent[1]},port3_data_sent=${port_data_sent[2]},port4_data_sent=${port_data_sent[3]},port5_data_sent=${port_data_sent[4]},port6_data_sent=${port_data_sent[5]},port7_data_sent=${port_data_sent[6]},port8_data_sent=${port_data_sent[7]},port9_data_sent=${port_data_sent[8]},port10_data_sent=${port_data_sent[9]},port11_data_sent=${port_data_sent[10]},port12_data_sent=${port_data_sent[11]},port13_data_sent=${port_data_sent[12]},port14_data_sent=${port_data_sent[13]},port15_data_sent=${port_data_sent[14]},port16_data_sent=${port_data_sent[15]},port1_VLAN_ID=${port_vlan_ID[0]},port2_VLAN_ID=${port_vlan_ID[1]},port3_VLAN_ID=${port_vlan_ID[2]},port4_VLAN_ID=${port_vlan_ID[3]},port5_VLAN_ID=${port_vlan_ID[4]},port6_VLAN_ID=${port_vlan_ID[5]},port7_VLAN_ID=${port_vlan_ID[6]},port8_VLAN_ID=${port_vlan_ID[7]},port9_VLAN_ID=${port_vlan_ID[8]},port10_VLAN_ID=${port_vlan_ID[9]},port11_VLAN_ID=${port_vlan_ID[10]},port12_VLAN_ID=${port_vlan_ID[11]},port13_VLAN_ID=${port_vlan_ID[12]},port14_VLAN_ID=${port_vlan_ID[13]},port15_VLAN_ID=${port_vlan_ID[14]},port16_VLAN_ID=${port_vlan_ID[15]}"
					
					secondString=""
					post_url=${post_url//\"/$secondString}
					
					PHY_temp=${PHY_temp//\"/$secondString}
						
					MAC_temp=${MAC_temp//\"/$secondString}
						
					FAN1_SPEED=${FAN1_SPEED//\"/$secondString}
					
					FAN2_SPEED=${FAN2_SPEED//\"/$secondString}
					
					serial=${serial//\"/$secondString}
					
					version=${version//\"/$secondString}
				
					if [ $MAC_temp -ge $max_mac_temp ]
					then
						send_email "$last_time_email_sent" "Warning the temperature of \"$switch_name\" MAC has exceeded $max_mac_temp Degrees C / $max_mac_temp_f Degrees F " "\"$switch_name\" MAC Temperature Warning " "$email_contents" "MAC Temp Alert" $email_interval
					fi

					if [ $PHY_temp -ge $max_phy_temp ]
					then
						send_email "$last_time_email_sent" "Warning the temperature of \"$switch_name\" PHY has exceeded $max_phy_temp Degrees C / $max_phy_temp_f Degrees F " "\"$switch_name\" PHY Temperature Warning " "$email_contents" "PHY Temp Alert" $email_interval
					fi
					
					if [ $num_fans -gt 0 ]; then
						if [ $FAN1_SPEED -le $minimum_fan_speed ]
						then
							send_email "$last_time_email_sent" "Warning \"$switch_name\" Fan#1 Speed has dropped below $minimum_fan_speed RPM." "\"$switch_name\" Fan Speed Warning" "$email_contents" "Fan1 Speed Alert" $email_interval
						fi
					fi
						
					if [ $num_fans -eq 2 ]; then
						if [ $FAN2_SPEED -le $minimum_fan_speed ]
						then
							send_email "$last_time_email_sent" "Warning \"$switch_name\" Fan#2 Speed has dropped below $minimum_fan_speed RPM." "\"$switch_name\" Fan Speed Warning" "$email_contents" "Fan2 Speed Alert" $email_interval
						fi
					fi
				else
					echo "Skipping system capture"
				fi
				
				if [ $mac0 -eq 1 ] || [ $phy0 -eq 1 ] || [ $fan0 -eq 1 ] || [ $fan1 -eq 1 ] || [ $snmp_error -eq 1 ]; then
					current_time=$( date +%s )
					echo "$current_time" > "$last_time_email_sent"
					email_time_diff=0
				fi
				
				
				#Post to regular database in influxdb

				curl -XPOST "http://$influxdb_host:$influxdb_port/api/v2/write?bucket=$influxdb_name&org=home" -H "Authorization: Token $influxdb_pass" --data-raw "$post_url"
				
				cd "$maria_location"

				./mysql -u $maria_user -p$maria_password -D $mariadb_db -e "UPDATE $mariadb_table SET port1_status = ${port_status[0]}, port2_status = ${port_status[1]}, port3_status = ${port_status[2]}, port4_status = ${port_status[3]}, port5_status = ${port_status[4]}, port6_status = ${port_status[5]}, port7_status = ${port_status[6]}, port8_status = ${port_status[7]}, port9_status = ${port_status[8]}, port10_status = ${port_status[9]}, port11_status = ${port_status[10]}, port12_status = ${port_status[11]}, port13_status = ${port_status[12]}, port14_status = ${port_status[13]}, port15_status = ${port_status[14]}, port16_status = ${port_status[15]}, port1_name = '${port_names[4]}', port2_name = '${port_names[5]}', port3_name = '${port_names[6]}', port4_name = '${port_names[7]}', port5_name = '${port_names[8]}', port6_name = '${port_names[9]}', port7_name = '${port_names[10]}', port8_name = '${port_names[11]}', port9_name = '${port_names[12]}', port10_name = '${port_names[13]}', port11_name = '${port_names[14]}', port12_name = '${port_names[15]}', port13_name = '${port_names[16]}', port14_name = '${port_names[17]}', port15_name = '${port_names[18]}', port16_name = '${port_names[19]}',port1_vlan = ${port_vlan_ID[0]},port2_vlan = ${port_vlan_ID[1]},port3_vlan = ${port_vlan_ID[2]},port4_vlan = ${port_vlan_ID[3]},port5_vlan = ${port_vlan_ID[4]},port6_vlan = ${port_vlan_ID[5]},port7_vlan = ${port_vlan_ID[6]},port8_vlan = ${port_vlan_ID[7]},port9_vlan = ${port_vlan_ID[8]},port10_vlan = ${port_vlan_ID[9]},port11_vlan = ${port_vlan_ID[10]},port12_vlan = ${port_vlan_ID[11]},port13_vlan = ${port_vlan_ID[12]},port14_vlan = ${port_vlan_ID[13]},port15_vlan = ${port_vlan_ID[14]},port16_vlan = ${port_vlan_ID[15]},uptime = '$uptime',name = '$name',serial = '$serial',version = '$version',mac_temp = $MAC_temp,phy_temp = $PHY_temp,fan1_speed = $FAN1_SPEED,fan2_speed = $FAN2_SPEED WHERE id=0"
					
				
				let i=i+1
				
				echo "Capture #$i complete"
				
				#Sleeping for capture interval unless its last capture then we don't sleep
				if (( $i < $total_executions)); then
					sleep $(( $capture_interval -1))
				fi
				
			done
		else
			send_email "$last_time_email_sent" "Warning Switch SNMP Monitoring Failed for device IP $switch_url - Target is Unavailable - script \"${0##*/}\"" "Warning Switch SNMP Monitoring Failed for device IP $switch_url - Target is Unavailable" "$email_contents" "Target Unavailable Error" $email_interval
			exit 1
		fi
	else
		echo "Script Disabled"
	fi
else
	send_email "$last_time_email_sent" "Warning Switch SNMP Monitoring Failed for script \"${0##*/}\" - Configuration file is missing" "Warning Switch SNMP Monitoring Failed for script \"${0##*/}\" - Configuration file is missing" "$email_contents" "Config File Missing Alert" 60
	exit 1
fi
