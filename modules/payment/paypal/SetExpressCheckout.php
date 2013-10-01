<?php
/***********************************************************
SetExpressCheckout.php

This is the main web page for the Express Checkout sample.
The page allows the user to enter amount and currency type.
It also accept input variable paymentType which becomes the
value of the PAYMENTACTION parameter.

When the user clicks the Submit button, ReviewOrder.php is
called.

Called by index.html.

Calls ReviewOrder.php.

***********************************************************/
// clearing the session before starting new API Call
session_unset();

	$paymentType = $_GET['paymentType'];
?>


<html>
<head>
    <title>PayPal PHP SDK - ExpressCheckout API</title>
    <link href="sdk.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <center>
	<form action="ReviewOrder.php" method="POST">
	<input type=hidden name=paymentType value='<?=$paymentType?>' >

	<span id=apiheader>SetExpressCheckout</span>

    <table class="api">

        <tr>
           <td colspan="2">
                <center></br>
                You must be logged into <a href="https://developer.paypal.com" id="PayPalDeveloperCentralLink"  target="_blank">Developer
                    Central<br / </a> </br>
                </center>
            </td>
        </tr>
        <tr>
            <td class="field">
                Amount:</td>
            <td>
                <input type="text" name="paymentAmount" size="5" maxlength="7" value="1.00" />
                <select name="currencyCodeType">
                <option value="USD">USD</option>
                <option value="GBP">GBP</option>
                <option value="EUR">EUR</option>
                <option value="JPY">JPY</option>
                <option value="CAD">CAD</option>
                <option value="AUD">AUD</option>
                </select>
                (Required)</td>
        </tr>
        <tr>
            <td> </br></br>
                <input type="image" name="submit" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" />
            </td>
            <td>
                Save time. Pay securely without sharing your financial information.
            </td>
        </tr>
    </table>
    </center>
    <a class="home" id="CallsLink" href="index.html">Home</a>
</body>
</html>
