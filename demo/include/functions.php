<?php
	# const
	define("RESULT_PRINT_PATH", '../../ResultPrint.log');
	define("RESULT_STATUS_PATH", '../../ResultStatus.log');

	if (isset($_POST["functionName"])) {
		$functionName = $_POST["functionName"];
	}

	if ($functionName == 'updateResultPrint') {
		# update print result
		if (file_exists(RESULT_PRINT_PATH)) {
			$contents = @file(RESULT_PRINT_PATH);
			foreach($contents as $line){
			  echo $line."<br />";
			}
		}
	} else if ($functionName == 'updateResultStatus') {
		# update status
		if (file_exists(RESULT_STATUS_PATH)) {
			$contents = @file(RESULT_STATUS_PATH);
			foreach($contents as $line){
			  echo $line."<br />";
			}
		}
	} else if ($functionName == 'copyFile') {
		# copy file
		copy("../sample.xml", "../request/sample.xml");
	} else if ($functionName == 'copyFileV2') {
		# copy file (Version 2)
		copy("../sampleV2.xml", "../request/sample.xml");
	} else if ($functionName == 'copyFileV3') {
		# copy file (Version 3)
		copy("../sampleV3.xml", "../request/sample.xml");
	}
?>