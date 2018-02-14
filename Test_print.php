<?php

    header('Content-Type: text/xml; charset=UTF-8');

    define("REQUEST_XML_PATH", "demo/request/sample.xml");
    define("RESPONSE_XML_PATH", "demo/response/sample.xml");

    if (isset($_POST["ConnectionType"])) {
        $http_request = $_POST["ConnectionType"];
    }

    if ($http_request == 'GetRequest') {
        # send print data

        # ID
        #
        #
        $shop_id = $_POST["ID"];

        # create print data
        if (file_exists(REQUEST_XML_PATH)) {
            # return print data
            $handle = fopen(REQUEST_XML_PATH, "r");
            fpassthru($handle);
            fclose($handle);

            # move file
            rename(REQUEST_XML_PATH, RESPONSE_XML_PATH);
        }
    } else if ($http_request == 'SetResponse') {
        # get print result

        $xml = simplexml_load_string($_POST["ResponseFile"]);
        $version = $xml['Version'];

        if ($version == '1.00') {

            # save log
            $fhandle = @fopen("ResultPrint.log", "wt");
            fprintf($fhandle, "PrintResponseInfo Version %s\n", $version);
            foreach ($xml->response as $response) {
                fprintf($fhandle, "----------\nsuccess : %s\ncode : %s\n", $response['success'], $response['code']);
            }
            fclose($fhandle);

        } else if ($version == '2.00') {

            # save log
            $fhandle = @fopen("ResultPrint.log", "wt");
            fprintf($fhandle, "PrintResponseInfo Version %s\n", $version);
            foreach ($xml->ePOSPrint as $eposprint) {
                $devid = $eposprint->Parameter->devid;
                $printjobid = $eposprint->Parameter->printjobid;
                $response = $eposprint->PrintResponse->response;
                fprintf($fhandle, "----------\ndevid : %s\nprintjobid : %s\nsuccess : %s\ncode : %s\n", $devid, $printjobid, $response['success'], $response['code']);
            }
        } else if ($version == '3.00') {
            # ePOSDisplay tag is only supported by Ver.3.00 or later.

            # save log
            $fhandle = @fopen("ResultPrint.log", "wt");
            fprintf($fhandle, "PrintResponseInfo Version %s\n", $version);

            $success = $xml->ServerDirectPrint->Response['Success'];

            if($success == 'true') {
                # display ePOSDisplay result
                foreach ($xml->ePOSDisplay as $eposdisplay) {
                    $devid = $eposdisplay->Parameter->devid;
                    $printjobid = $eposdisplay->Parameter->printjobid;
                    $response = $eposdisplay->DisplayResponse->response;
                    fprintf($fhandle, "----------\ndevid : %s\nprintjobid : %s\nsuccess : %s\ncode : %s\n", $devid, $printjobid, $response['success'], $response['code']);
                }

            } else {
                    # display error summary and detail
                    $summary = $xml->ServerDirectPrint->Response->ErrorSummary;
                    $detail = $xml->ServerDirectPrint->Response->ErrorDetail;
                    fprintf($fhandle, "----------\nServer Direct Print Success : false.\nErrorSummary : %s\nErrorDetail : %s\n", $summary, $detail);
            }
            fclose($fhandle);

        } else {
            # Ignore other version
        }

    } else {
        # Ignore other connectionType than GetRequest and SetResponse.
    }
?>
