<?php
// Include required library files.
require_once('../includes/config.php');
require_once('../includes/paypal.class.php');

// Create PayPal object.
$PayPalConfig = array(
					  'Sandbox' => $sandbox,
					  'DeveloperAccountEmail' => $developer_account_email,
					  'ApplicationID' => $application_id,
					  'DeviceID' => $device_id,
					  'IPAddress' => $_SERVER['REMOTE_ADDR'],
					  'APIUsername' => $api_username,
					  'APIPassword' => $api_password,
					  'APISignature' => $api_signature,
					  'APISubject' => $api_subject
					);

$PayPal = new PayPal_Adaptive($PayPalConfig);

// Prepare request arrays
$SPOFields = array(
				'PayKey' => 'AP-6K251159421974004', 							// Required.  The pay key that identifies the payment for which you want to set payment options.  
				'ShippingAddressID' => '' 					// Sender's shipping address ID.
				);
				
$DisplayOptions = array(
				'EmailHeaderImageURL' => 'http://www.angelleye.com/images/email_header_image.jpg', 			// The URL of the image that displays in the header of customer emails.  1,024 char max.  Image dimensions:  43 x 240
				'EmailMarketingImageURL' => 'http://www.angelleye.com/images/email_marketing_image.jpg', 		// The URL of the image that displays in the customer emails.  1,024 char max.  Image dimensions:  80 x 530
				'HeaderImageURL' => 'http://www.angelleye.com/header_image.jpg', 				// The URL of the image that displays in the header of a payment page.  1,024 char max.  Image dimensions:  750 x 90
				'BusinessName' => 'Angell EYE'					// The business name to display.  128 char max.
				);
						
$InstitutionCustomer = array(
				'CountryCode' => 'US', 				// Required.  2 char code of the home country of the end user.
				'DisplayName' => 'Tester Testerson', 				// Required.  The full name of the consumer as known by the institution.  200 char max.
				'InstitutionCustomerEmail' => 'test@testerson.com', 	// The email address of the consumer.  127 char max.
				'FirstName' => 'Tester', 					// Required.  The first name of the consumer.  64 char max.
				'LastName' => 'Testerson', 					// Required.  The last name of the consumer.  64 char max.
				'InstitutionCustomerID' => '12345', 		// Required.  The unique ID assigned to the consumer by the institution.  64 char max.
				'InstitutionID' => '1234'				// Required.  The unique ID assigned to the institution.  64 char max.
				);
						
$SenderOptions = array(
				'RequireShippingAddressSelection' => 'true' // Boolean.  If true, require the sender to select a shipping address during the embedded payment flow.  Default is false.
				);
					
// Begin loop to populate receiver options.
$ReceiverOptions = array();
$ReceiverOption = array(
		'Description' => 'Test Order: sandbo_1215254764_biz@angelleye.com', 					// A description you want to associate with the payment.  1000 char max.
		'CustomID' => '' 						// An external reference number you want to associate with the payment.  1000 char max.
);
	
$InvoiceData = array(
		'TotalTax' => '', 							// Total tax associated with the payment.
		'TotalShipping' => '' 						// Total shipping associated with the payment.
);

$InvoiceItems = array();
$InvoiceItem = array(
		'Name' => 'Widget ABC', 								// Name of item.
		'Identifier' => '', 						// External reference to item or item ID.
		'Price' => '25.00', 								// Total of line item.
		'ItemPrice' => '25.00',							// Price of an individual item.
		'ItemCount' => '1'							// Item QTY
);
array_push($InvoiceItems,$InvoiceItem);

$ReceiverIdentifier = array(
		'Email' => 'sandbo_1215254764_biz@angelleye.com', 	// Receiver's email address.  127 char max.
		'PhoneCountryCode' => '', 			// Receiver's telephone number country code.
		'PhoneNumber' => '', 				// Receiver's telephone number.
		'PhoneExtension' => ''				// Receiver's telephone extension.
);

$ReceiverOption['InvoiceData'] = $InvoiceData;
$ReceiverOption['InvoiceItems'] = $InvoiceItems;
$ReceiverOption['ReceiverIdentifier'] = $ReceiverIdentifier;
array_push($ReceiverOptions,$ReceiverOption);

$ReceiverOption = array(
		'Description' => 'Test Order: usb_1329725429_biz@angelleye.com', 					// A description you want to associate with the payment.  1000 char max.
		'CustomID' => '' 						// An external reference number you want to associate with the payment.  1000 char max.
);
	
$InvoiceData = array(
		'TotalTax' => '', 							// Total tax associated with the payment.
		'TotalShipping' => '' 						// Total shipping associated with the payment.
);

$InvoiceItems = array();
$InvoiceItem = array(
		'Name' => 'Widget ZDF', 								// Name of item.
		'Identifier' => '', 						// External reference to item or item ID.
		'Price' => '5.00', 								// Total of line item.
		'ItemPrice' => '5.00',							// Price of an individual item.
		'ItemCount' => '1'							// Item QTY
);
array_push($InvoiceItems,$InvoiceItem);

$ReceiverIdentifier = array(
		'Email' => 'usb_1329725429_biz@angelleye.com', 	// Receiver's email address.  127 char max.
		'PhoneCountryCode' => '', 			// Receiver's telephone number country code.
		'PhoneNumber' => '', 				// Receiver's telephone number.
		'PhoneExtension' => ''				// Receiver's telephone extension.
);

$ReceiverOption['InvoiceData'] = $InvoiceData;
$ReceiverOption['InvoiceItems'] = $InvoiceItems;
$ReceiverOption['ReceiverIdentifier'] = $ReceiverIdentifier;
array_push($ReceiverOptions,$ReceiverOption);
// End receiver options loop

$PayPalRequestData = array(
				'SPOFields' => $SPOFields, 
				'DisplayOptions' => $DisplayOptions, 
				//'InstitutionCustomer' => $InstitutionCustomer, 
				'SenderOptions' => $SenderOptions, 
				'ReceiverOptions' => $ReceiverOptions
				);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->SetPaymentOptions($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>