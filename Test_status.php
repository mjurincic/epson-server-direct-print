<?php

	header('Content-Type: text/xml; charset=UTF-8');

	# open log file
	$fhandle = @fopen("ResultStatus.log", "wt");

	# update status
	if (isset($_POST["Status"])) {
		$status =  $_POST["Status"];
	}
	$xml = simplexml_load_string($status);

	foreach ($xml->printerstatus as $printerstatus) {
		$asb = hexdec($printerstatus['asbstatus']);
		$strmsg = "";
		if (($asb & 0x00000004) == 0x00000004)
		{
			$strmsg .= "  Status of the drawer kick number 3 connector pin = H \n";
		}
		if (($asb & 0x00000008) == 0x00000008)
		{
			$strmsg .= "  Offline status \n";
		}
		if (($asb & 0x00000020) == 0x00000020)
		{
			$strmsg .= "  Cover is open \n";
		}
		if (($asb & 0x00000040) == 0x00000040)
		{
			$strmsg .= "  Paper feed switch is feeding paper \n";
		}
		if (($asb & 0x00000100) == 0x00000100)
		{
			$strmsg .= "  Waiting for online recovery \n";
		}
		if (($asb & 0x00000200) == 0x00000200)
		{
			$strmsg .="  Panel switch is ON \n";
		}
		if (($asb & 0x00000400) == 0x00000400)
		{
			$strmsg .= "  Mechanical error generated \n";
		}
		if (($asb & 0x00000800) == 0x00000800)
		{
			$strmsg .= "  Auto cutter error generated \n";
		}
		if (($asb & 0x00002000) == 0x00002000)
		{
			$strmsg .= "  Unrecoverable error generated  \n";
		}
		if (($asb & 0x00004000) == 0x00004000)
		{
			$strmsg .= "  Auto recovery error generated  \n";
		}
		if (($asb & 0x00020000) == 0x00020000)
		{
			$strmsg .= "  No paper in the roll paper near end detector \n";
		}
		if (($asb & 0x00080000) == 0x00080000)
		{
			$strmsg .= "  No paper in the roll paper end detector  \n";
		}
		if (($asb & 0x01000000) == 0x01000000)
		{
			$strmsg .= "  A buzzer is on (only for applicable devices)  \n";
		}
		if (($asb & 0x01000000) == 0x01000000)
		{
			$strmsg .= "  Waiting for label to be removed (only for applicable devices)  \n";
		}
		if (($asb & 0x04000000) == 0x04000000)
		{
			$strmsg .= "  No paper in label peeling sensor (only for applicable devices)  \n";
		}

		$battery = hexdec($printerstatus['battery']);
		if ($battery == 0x0000)
		{
			$strmsg .= "  This printer does not have battery \n";
		}
		else {
			if (($battery & 0xFF00) == 0x3000)
			{
				$strmsg .= "  AC adaptor is connected \n";
			}
			if (($battery & 0xFF00) == 0x3100)
			{
				$strmsg .= "  AC adaptor is not connected \n";
			}
			if (($battery & 0x00FF) == 0x0036)
			{
				$strmsg .= "  Remaining battery capacity is 6 \n";
			}
			if (($battery & 0x00FF) == 0x0035)
			{
				$strmsg .= "  Remaining battery capacity is 5 \n";
			}
			if (($battery & 0x00FF) == 0x0034)
			{
				$strmsg .= "  Remaining battery capacity is 4 \n";
			}
			if (($battery & 0x00FF) == 0x0033)
			{
				$strmsg .= "  Remaining battery capacity is 3 \n";
			}
			if (($battery & 0x00FF) == 0x0032)
			{
				$strmsg .= "  Remaining battery capacity is 2 \n";
			}
			if (($battery & 0x00FF) == 0x0031)
			{
				$strmsg .= "  Remaining battery capacity is 1 \n";
			}
			if (($battery & 0x00FF) == 0x0030)
			{
				$strmsg .= "  Remaining battery capacity is 0 \n";
			}
		}

		$result = fprintf($fhandle, "Printer name = %s : Status ASB value is %s : Battery status is %s \n",  $printerstatus['devicename'], $asb, $battery);
		$result = fprintf($fhandle,  "%s\n", $strmsg);
		if ($result) {
			# OK
		} else {
			# N.G
		}
	}

	foreach ($xml->servicestatus as $servicestatus) {
		$servicename = $servicestatus['servicename'];
		$code = $servicestatus['code'];
		$severity = $servicestatus['severity'];
		$message = $servicestatus['message'];

		$result = fprintf($fhandle, "Service name = %s : Code is %s : Severity is %s : Message is %s \n\n",  $servicestatus['servicename'], $servicestatus['code'], $servicestatus['severity'], $servicestatus['message']);
		if ($result) {
			# OK
		} else {
			# N.G
		}
	}

	fclose($fhandle);

?>