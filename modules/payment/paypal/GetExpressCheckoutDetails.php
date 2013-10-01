<?php
/********************************************************
GetExpressCheckoutDetails.php

This functionality is called after the buyer returns from
PayPal and has authorized the payment.

Displays the payer details returned by the
GetExpressCheckoutDetails response and calls
DoExpressCheckoutPayment.php to complete the payment
authorization.

Called by ReviewOrder.php.

Calls DoExpressCheckoutPayment.php and APIError.php.

********************************************************/


session_start();

/* Collect the necessary information to complete the
   authorization for the PayPal payment
   */

$_SESSION['token']=$_REQUEST['token'];
$_SESSION['payer_id'] = $_REQUEST['PayerID'];

$_SESSION['paymentAmount']=$_REQUEST['paymentAmount'];
$_SESSION['currCodeType']=$_REQUEST['currencyCodeType'];
$_SESSION['paymentType']=$_REQUEST['paymentType'];

$resArray=$_SESSION['reshash'];

/* Display the  API response back to the browser .
   If the response from PayPal was a success, display the response parameters
   */

?>



<html>
<head>
    <title>PayPal PHP SDK - ExpressCheckout API</title>
    <link href="sdk.css" rel="stylesheet" type="text/css" />
</head>
<body>
   
	<form action="DoExpressCheckoutPayment.php">
	 <center>
           <table width =400>
            <tr>
                <td><b>Order Total:</b></td>
                <td>
                  <?=$_REQUEST['currencyCodeType'] ?> <?=$_REQUEST['paymentAmount']?></td>
            </tr>
			<tr>
			    <td ><b>Shipping Address: </b></td>
			</tr>
            <tr>
                <td >
                    Street 1:</td>
                <td>
                   <?=$resArray['SHIPTOSTREET'] ?></td>

            </tr>
            <tr>
                <td >
                    Street 2:</td>
                <td><?=$resArray['SHIPTOSTREET2'] ?>
                </td>
            </tr>
            <tr>
                <td >
                    City:</td>

                <td>
                    <?=$resArray['SHIPTOCITY'] ?></td>
            </tr>
            <tr>
                <td >
                    State:</td>
                <td>
                    <?=$resArray['SHIPTOSTATE'] ?></td>
            </tr>
            <tr>
                <td >
                    Postal code:</td>

                <td>
                    <?=$resArray['SHIPTOZIP'] ?></td>
            </tr>
            <tr>
                <td >
                    Country:</td>
                <td>
                     <?=$resArray['SHIPTOCOUNTRYNAME'] ?></td>
            </tr>
            <tr>
                <td class="thinfield">
                     <input type="submit" value="Pay" />
                </td>
            </tr>
        </table>
    </center>
    </form>

</body>
</html>
