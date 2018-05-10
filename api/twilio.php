<?php

	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);

	require "vendor/twilio/sdk/Services/Twilio.php";
	$configs = include('../config.php');

	session_start();

	$SamPin = $configs['PIN'];
	$SendTo = $configs['SendTo'];
	$OutboundNumber = $configs['OutboundNumber'];
	$AccountSid = $configs['AccountSid'];
	$AuthToken = $configs['AuthToken'];

	if (isset($_GET["textSam"]) && $_GET["textSam"] === "1") {
		//Only allow this to run every 5 minutes
		if (isset($_SESSION["lastSent"]) == false || minutesBetween($_SESSION["lastSent"]) >= 5) {
			//Check PIN
			if (isset($_GET["pin"]) && $_GET["pin"] === $SamPin) {



				$client = new Services_Twilio($AccountSid, $AuthToken);

				$message = $client->account->messages->create(array(
					"From" => $OutboundNumber,
					"To" => $SendTo,
					"Body" => "[The Sam Signal] ".$_GET['message']
				));

				echo "true";
				$_SESSION["lastSent"] = new DateTime();
				return;

			else if (isset($_GET["pin"]) && $_GET["pin"] === "test") {
				echo "true";
				return;
			} else { //PIN check
				echo "false";
				return;
			} 

		} else {
			echo "false";
			return;
		}

	} 

	function minutesBetween($date1) {
		$since_date = $date1->diff(new DateTime());
		return $since_date->i;
	}
?>
