<?php

//Register the Upload field
add_action('init', 'ninja_forms_register_payment_form');

function ninja_forms_register_payment_form(){
	$args = array(
		'name' => 'Payeezy Form', //Required - This is the name that will appear on the add field button.
		'edit_options' => array( //Optional - An array of options to show within the field edit <li>. Should be an array of arrays.
			array(
				'type' => 'text', //What type of input should this be?
				'name' => 'amount', //What should it be named. This should always be a programmatic name, not a label.
				'label' => 'Option 1: Amount To Be Charged', //Label to be shown before the option.
				'class' => 'widefat', //Additional classes to be added to the input element.
			),
			array(
				'type' => 'text', //What type of input should this be?
				'name' => 'total-field-id', //What should it be named. This should always be a programmatic name, not a label.
				'label' => 'Option 2: Field ID of Total', //Label to be shown before the option.
				'class' => 'widefat', //Additional classes to be added to the input element.
			)
		),
		'sidebar' => 'template_fields',
		'display_function' => 'ninja_forms_payment_form_display', //Required - This function will be called to create output when a user accesses a form containing this element.
		'edit_label' => true, //True or False
		'edit_label_pos' => true,
		'edit_req' => true,
		'edit_meta' => true,
		'edit_conditional' => true,
		'pre_process' => 'ninja_forms_payment_form_process', // Run a pre-process to keep data safe!
	);

	if( function_exists( 'ninja_forms_register_field' ) ){
		ninja_forms_register_field('_payeezy', $args);
	}
}


/**
 * This is the main display function that will be called on the front-end when a user is filling out a form.
 *
 * @param int $field_id - ID number of the field that is currently being displayed.
 * @param array $data - array of field data as it has been processed to this point.
 */

function ninja_forms_payment_form_display( $field_id, $data ){
	$plugin_settings = get_option('ninja_forms_settings');
	$id = rand();
?>
    <style>
	    .tcc.card-wrapper{
		    float:left;
		    margin: 0 2%;
	    }
	    
        .tcc.form-container {
            margin: 0 2%;
            float:left;
        }
        
        .tcc input {
            margin: 10px auto;
            display: inline-block;
        }
        
        .tcc hr{
	        margin: 12px auto;
	        padding:0;
        }
        
        .tcc #number, .tcc #name{
	        display: block;
	        width:100%;
        }
        
        .tcc #expiry{
	        width:32%;
	        margin-right: 5%;
        }
        
        .tcc #cvc{
	        width:22%;
        }

        .tcc #tc_pay_<?PHP echo $id; ?> input.jp-card-invalid{
        	border-color:#AD4040;
        }
	        
    </style>

	<div class="tcc card-wrapper card-wrapper<?PHP echo $id; ?>"></div>

    <div class="tcc form-container active">
        <div id="tc_pay_<?PHP echo $id; ?>">
            <input required placeholder="Name on Card" type="text" name="name" id="name">
            <hr />
            <input required placeholder="Card Number" type="text" name="number" id="number">
            <input required placeholder="MM/YYYY" type="text" name="expiry" id="expiry">
            <input required placeholder="CVC" type="text" name="cvc" id="cvc">
            <?php 	if(isset($data['amount']))
            			$amount = $data['amount'];
            		else
            			$amount = 0;
            ?>
            <input type="hidden" name="amount" id="tempAmount" value="<?=$amount?>">
            <input type="hidden" name="type" id="type">
        </div>
        <input type="hidden" name="ninja_forms_field_<?PHP echo $field_id;?>" value="kevin" />
    </div>

	<script src="<?PHP echo NINJA_FORMS_UPLOADS_URL;?>/js/card.js"></script>
	<script>
        new Card({
            form: document.querySelector('#tc_pay_<?PHP echo $id; ?>'),
            container: '.card-wrapper<?PHP echo $id; ?>'
        });

        jQuery('#tc_pay_<?PHP echo $id; ?> input').keyup(function(){
			var card = false;

			switch(jQuery('.card-wrapper<?PHP echo $id; ?> .jp-card').attr('data-type')){
				case 'amex':
					card = "American Express";
					break;
				case 'visaelectron':
				case 'visa':
					card = "Visa";
					break;
				case 'mastercard':
					card = "Mastercard";
					break;
				case 'jcb':
					card = "JCB";
					break;
				case 'dinersclub':
					card = "Diners Club";
					break;
				case 'discover':
					card = "Discover";
					break;
			}

			jQuery("#tc_pay_<?PHP echo $id; ?> input#type").val(card);

        	jQuery('input[name=ninja_forms_field_<?PHP echo $field_id;?>]').val(jQuery("#tc_pay_<?PHP echo $id; ?> input").serialize());
        });

        jQuery('input[name=ninja_forms_field_<?PHP echo $data['total-field-id'];?>]').last().change(function(){
			jQuery('#tc_pay_<?PHP echo $id; ?> #tempAmount').val(jQuery(this).val());
        });
    </script>
<?PHP
}