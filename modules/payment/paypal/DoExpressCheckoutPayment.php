<?php
/**********************************************************
DoExpressCheckoutPayment.php

This functionality is called to complete the payment with
PayPal and display the result to the buyer.

The code constructs and sends the DoExpressCheckoutPayment
request string to the PayPal server.

Called by GetExpressCheckoutDetails.php.

Calls CallerService.php and APIError.php.

**********************************************************/

require_once 'CallerService.php';
session_start();

/* Gather the information to make the final call to
   finalize the PayPal payment.  The variable nvpstr
   holds the name value pairs
   */
$token =urlencode( $_SESSION['token']);
$paymentAmount =urlencode ($_SESSION['paymentAmount']);
$paymentType = urlencode($_SESSION['paymentType']);
$currCodeType = urlencode($_SESSION['currCodeType']);
$payerID = urlencode($_SESSION['payer_id']);
$serverName = urlencode($_SERVER['SERVER_NAME']);

$nvpstr='&TOKEN='.$token.'&PAYERID='.$payerID.'&PAYMENTACTION='.$paymentType.'&AMT='.$paymentAmount.'&CURRENCYCODE='.$currCodeType.'&IPADDRESS='.$serverName ;

 /* Make the call to PayPal to finalize payment
    If an error occured, show the resulting errors
    */
$resArray=hash_call("DoExpressCheckoutPayment",$nvpstr);

/* Display the API response back to the browser.
   If the response from PayPal was a success, display the response parameters'
   If the response was an error, display the errors received using APIError.php.
   */
$ack = strtoupper($resArray["ACK"]);


if($ack!="SUCCESS"){
	$_SESSION['reshash']=$resArray;
	$location = "APIError.php";
		 header("Location: $location");
               }

?>


<html>
<head>
    <title>PayPal PHP SDK - DoExpressCheckoutPayment API</title>
    <link href="sdk.css" rel="stylesheet" type="text/css" />
</head>
<body>
		<br>
		<center>
		<font size=2 color=black face=Verdana><b>DoExpressCheckoutPayment</b></font>
		<br><br>

		<b>Thank you for your payment!</b><br><br>

    
    <table width =400>
        
        <tr>
            <td >
                Transaction ID:</td>
            <td><?=$resArray['TRANSACTIONID'] ?></td>
        </tr>
        <tr>
            <td >
                Amount:</td>
            <td><?=$currCodeType?> <?=$resArray['AMT'] ?></td>
        </tr>
    </table>
    </center>
    <a class="home" id="CallsLink" href="index.html">Home</a>
</body>
</html>

