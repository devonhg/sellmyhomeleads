<?php
/*
	Generate the options
*/
	function smh_cmp($a, $b){
    	return strcmp($a->name, $b->name);
	}
	function smh_generate_list( $slug, $title, $id ) {
			$args = array(
				'taxonomy' => $slug,
				'hide_empty' => false,
			);
			
			
			//Get our terms
			$terms = get_categories( $args );
			//var_dump( $terms );
			$terms_sorted = usort($terms, "smh_cmp");
			//var_dump( $terms );
			ob_start();
			// arguments for function wp_list_categories
			
			//Add in Title
			echo "<h2 class='fs-title'>" . $title . "</h2>";
			
			echo "<div id='" . $id . "' class='fa-checkboxes'>";
			$counter = 1; 	
			foreach( $terms as $term ){
				if (strpos($term->name, ')') !== false){ $name = explode( ")", $term->name ); }
				else{ $name = array( "", $term->name ); }
				$name[1] = filter_var($name[1], FILTER_SANITIZE_STRING);
				echo "<input data-for='" . $slug . "'class='next-nested form_" . $slug . "' onclick='smi_selectOnlyThisProperty(this.id, this.parentNode.id);' id='". $id ."-box" . $counter . "' type='checkbox' name='" . $id . "' value='" . $term->slug . "' />";
				echo "<label name='" . $id . "' value='" . $term->slug . "' for='". $id ."-box" . $counter . "'>" . $name[1] . "</label>";
				$counter += 1; 
			}
			echo "</div>";
			return ob_get_clean();
		}
		function get_city(){
			$o = "";
			//$o .= '<h2 class="fs-title">'. $title . '</h2>';
			$o .= '<div class="city-wrapper">';
			//$o .= '<label for="zip">Zip:</label>';
			$o .= '<input data-for="zip" class="required form_zip" placeholder="Zip" type="text" id="zip" name="zip"/>';
			//$o .= '<label for="city">City:</label>';
			$o .= '<div id="city_wrap"><input data-for="city" class="required form_city" placeholder="City" type="text" id="city" name="city"/></div>';
			//$o .= '<label for="state">State:</label>';
			$o .= '<input class="required form_state" placeholder="State" type="text" id="state" name="state"/>';
			$o .= "</div>";
			$o .= "<p></p>";
			return $o;
		}
function sellmyhome_form( $emails, $social, $cust_email_body, $submit_message ){
	//Validation
	$postTitleError = '';
	if ( isset( $_POST['submitted'] ) ) {
		if( !isset( $posted ) ){
			//Process, clean, and create a post entry
				$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				//var_dump( $_POST );
			//Prepare useful information for sending and saving the lead
				if( $_POST['buysell'] == "buy" ){
					$email_subject = $_POST['full_name'] . 
						" #". $_POST['buysell'] . 
						" #" . substr( str_replace( " ", "_", $_POST['frm-propertyvalue'] ) , 2) . 
						" #" . substr( str_replace( " ", "_", $_POST['frm-propertytype'] ) , 2) .
						" #" . substr( str_replace( " ", "_", $_POST['frm-whenbuy'] ) , 2) ;
					$email_body = "---" .
						"\n\n###Contact" .
						"\n- " . $_POST["full_name"] .
						"\n- " . $_POST["email"] .
						"\n- " . $_POST["phone"] .
						"\n- " . $_POST["city"] . ", " . $_POST["state"] . " " .  $_POST["zip"] .
						"\n\n---";
				}else{
					$email_subject = $_POST['full_name'] . 
						" #". $_POST['buysell'] . 
						" #" . substr( str_replace( " ", "_", $_POST['frm-propertyvalue'] ) , 2) . 
						" #" . substr( str_replace( " ", "_", $_POST['frm-propertytype'] ) , 2) ;
					$email_body = "---" .
						"\n\n###Contact" .
						"\n- " . $_POST["full_name"] .
						"\n- " . $_POST["email"] .
						"\n- " . $_POST["phone"] .
						"\n- " . $_POST['address'] . " " . $_POST["city"] . ", " . $_POST["state"] . " " .  $_POST["zip"] .
						"\n\n---";		
				}
				//If email enabled, send emails
				if ( $emails !== '' ){ 
					$emails_boom = explode(",", $emails);
					wp_mail( $emails_boom, $email_subject, $email_body  );

				}
			//Send customer an email
				 
				$headers = apache_request_headers();
				$url = $headers["Host"];
				ob_start();
				?>
					<strong>Thank you for choosing <a href="<?php echo get_site_url() ?>"><?php echo get_bloginfo("name"); ?></a></strong>
					<p><?php echo $cust_email_body; ?></p>
					
				<?
				if( $social != "" ){ ?>	
					<p>In the meantime feel free to <a href="<?php echo $social ?>">follow us on social media!</a></p>
				<?php }

				$email_body = ob_get_clean();
				wp_mail( $_POST["email"], "Thank You!", $email_body  );
				
			//Create the lead
				$post_information = array(
					'post_title' => str_replace("_", " ", $email_subject),
					'post_content' => $email_body,
					'post_type' => 'pt_leads',
					'post_status' => 'publish'
				);
				$lead_post = wp_insert_post( $post_information );
			
			//Assign the taxonomies and data
				if( $_POST['buysell'] == "buy" ){
					//var_dump( $_POST['frm-propertyvalue'] );
					//Categories
					wp_set_object_terms( $lead_post, $_POST['buysell'], 'tax_lead_type_leads' );
					wp_set_object_terms( $lead_post, $_POST['frm-propertyvalue'], 'tax_property_value_leads' );
					wp_set_object_terms( $lead_post, $_POST['frm-propertytype'], 'tax_mortgage_option_leads' );
					wp_set_object_terms( $lead_post, $_POST['frm-whenbuy'], 'tax_purchase_time_leads' );
					wp_set_object_terms( $lead_post, $_POST['city'], 'tax_city_leads' );
					//Meta	
					add_post_meta( $lead_post, "meta_leads_phone_vkey", $_POST['phone'] );
					add_post_meta( $lead_post, "meta_leads_email_vkey", $_POST['email'] );
				}else{
					//var_dump( $_POST['frm-propertyvalue'] );
					//Categories
					wp_set_object_terms( $lead_post, $_POST['buysell'], 'tax_lead_type_leads' );
					wp_set_object_terms( $lead_post, $_POST['frm-propertyvalue'], 'tax_property_value_leads' );
					wp_set_object_terms( $lead_post, $_POST['frm-propertytype'], 'tax_property_type_leads' );
					wp_set_object_terms( $lead_post, $_POST['city'], 'tax_city_leads' );
					//Meta	
					add_post_meta( $lead_post, "meta_leads_address_vkey", $_POST['address'] );
					add_post_meta( $lead_post, "meta_leads_phone_vkey", $_POST['phone'] );
					add_post_meta( $lead_post, "meta_leads_email_vkey", $_POST['email'] );
				}
			//Show notification message.
				?>
					<div id="msform">
						<fieldset>
							<p><?php echo $submit_message; ?></p>
						</fieldset>
					</div>
				<?php
				$posted = true; 
		}
	}else{
		global $glb_smh_pluginuri;
		ob_start();
		$prop_types = smh_generate_list( "tax_property_type_leads", "Type Of Property", "frm-propertytype" );
		$purch_times = smh_generate_list( "tax_purchase_time_leads", "When do you plan on buying?", "frm-whenbuy" );
		$mortga_options = smh_generate_list( "tax_mortgage_option_leads", "What is your Mortgage Status?", "frm-propertytype" );
		?>

		<script>
			//Declare content
				var selling_content = "<?php echo $prop_types; ?>";
				var buying_content = "";
				buying_content += "<?php echo $purch_times; ?>";
				var mortage_content = "<?php echo $mortga_options; ?>";
				var get_city = '<?php echo get_city(); ?>';
				var prev_button = '<!--<input type="button" name="previous" class="previous action-button" value="Previous" />-->';
				var next_button = '<input type="button" name="next" class="next action-button" value="Next" />';
				var buying = false; 
		</script>

	    <!-- multistep form -->
	        <form action="" method="POST" id="msform" >
	            <!-- fieldsets -->
	            <fieldset id="fs-buysell">
	                <h2 class="fs-title">Are you buying or selling?</h2>
	                <div class="image-checkboxes">
		                <input class="next-nested" data-for="buysell" value="buy" name="buysell" onclick="smi_selectOnlyThis(this.id);smi_isbuying();" type="checkbox" id="cb1" /><label for="cb1"><img src="<?php echo $glb_smh_pluginuri.'/images/buy_home.png' ?>" /><br>Buying</label>
		                <input class="next-nested" data-for="buysell" value="sell" name="buysell" onclick="smi_selectOnlyThis(this.id);smi_isselling();" type="checkbox" id="cb2" /><label for="cb2"><img src="<?php echo $glb_smh_pluginuri.'/images/sell_home.png' ?>" /><br>Selling</label>
		                <input class="next-nested" data-for="buysell" value="both" name="buysell" onclick="smi_selectOnlyThis(this.id);smi_isselling();" type="checkbox" id="cb3" /><label for="cb3"><img src="<?php echo $glb_smh_pluginuri.'/images/both_home.png' ?>" /><br>Both</label>		            
		            </div>
	                <hr>
	            </fieldset>
	            <fieldset id="fs-property-value">

					<?php echo smh_generate_list( "tax_property_value_leads", "Property Value", "frm-propertyvalue" ); ?>
					<hr>

	            </fieldset>
	            <fieldset id="fs-property-location">
	            	<h2 id="location-title" class="fs-title">Where are you looking to buy?</h2>
					<?php echo get_city(); ?>
					<div id="fs-location-dyn">

	            	</div>
					<hr>
	                <input data-for="where_to" id="dhg-validate" type="button" name="next" class="next action-button" value="Next" disabled/>
	            </fieldset>
				<fieldset id="fs-addressmort">

	                <hr>
	            </fieldset>            
	            <fieldset id="fs-property">
	            	<div id="fs-property-dyn">

	            	</div>
	                <hr>
	            </fieldset>
	            <fieldset>
	                <h2 class="fs-title">Contact Information</h2>
	                <input class="required form_full_name" type="text" name="full_name" placeholder="Full Name" />
	                <input id="useremail" class="required email form_email" type="text" name="email" placeholder="Email" />
	                <input class="form_phone" type="text" name="phone" placeholder="Phone ( Optional )" />
	                <sub style="color: green;">We do not sell your information to third parties.</sub>
	                <hr>
	                <input type="hidden" name="submitted" id="submitted" value="true" />
	                <input id="dhg-validate-submit" data-for="submit" type="submit" name="submit" class="submit action-button" value="Submit" />

	            </fieldset>
	        </form>
	    <?php
	    echo ob_get_clean();
	}
}
?>