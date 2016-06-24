<?php

 require_once('Payeezy.php');

 $payeezy = new Payeezy();
 
 $options = get_option('ninja_forms_settings');

 $payeezy->setApiKey($options['API_key']);
 $payeezy->setApiSecret($options['API_secret']);
 $payeezy->setMerchantToken($options['merchant_token']);
 $payeezy->setTokenUrl("https://api-cert.payeezy.com/v1/transactions/tokens");  
 $payeezy->setUrl("https://api-cert.payeezy.com/v1/transactions");

function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return strval($data);
}

function processData($data){
    $card_holder_name = cleanInput($data['name']);
    $card_number = cleanInput($data['number']);
    $card_type = cleanInput($data['type']);
    $card_cvv = cleanInput($data['cvc']);
    $card_expiry = cleanInput($data['expiry']);
    $amount = cleanInput($data['amount']);
    $currency_code = cleanInput("USD");
    //$merchant_ref = cleanInput(get_setting('merchant_ref'));
    $method = cleanInput("credit_card");


    $primaryTxPayload = array(
        "amount"=> $amount,
        "card_number" => $card_number,
        "card_type" => $card_type,
        "card_holder_name" => $card_holder_name,
        "card_cvv" => $card_cvv,
        "card_expiry" => $card_expiry,
        //"merchant_ref" => $merchant_ref,
        "currency_code" => $currency_code,
        "method"=> $method,
    );

    return $primaryTxPayload;
}

function purchase($data){
    global $payeezy;

    $primaryTxResponse_JSON = json_decode($payeezy->purchase(processData($data)));
    return $primaryTxResponse_JSON->transaction_status;
}

/**
 * This function will be ran during the processing (process) of a form.
 *
 * @param int $field_id - ID number of the field that is currently being displayed.
 * @param array/string $user_value - the value of the field within the user-submitted form.
 */

function ninja_forms_payment_form_process($field_id, $user_value){
	global $ninja_forms_processing;
	
	$ninja_forms_processing->remove_field_value($field_id);

    $data = array();
    parse_str(htmlspecialchars_decode($user_value), $data);
    
    // Remove Spaces in Number
    $data['number'] = str_replace(' ', '', $data['number']);

    // Re-cast amount in cents
    $origAmount = $data['amount'];
    $data['amount'] = number_format($data['amount'], 2, '', '');

    // Get expire date inn MMYY format
    $data['expiry'] = substr($data['expiry'], 0, 2).substr($data['expiry'], -2);

	if(!$ninja_forms_processing->get_all_errors()){
		$status = purchase($data);
		$ninja_forms_processing->update_field_value($field_id, " $".$origAmount." changed to ".$data['name']." [".$status."]");
	}
}
