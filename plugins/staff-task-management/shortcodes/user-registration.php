<?php

function stm_member_registration_form($atts, $content = null) {
	extract(shortcode_atts(array(
		'first_name' => 'yes',
		'last_name' => 'yes',
		'password' => 'yes'
	), $atts));	
	ob_start();	
	
	
	if ( isset( $_POST['stm_register_submit'] ) && wp_verify_nonce( $_POST['stm_register_submit'], 'stm_register_action' ) && isset( $_POST['user_email'] ) ) {	
	   // process form data
	   	$check = true;
		if ( !is_email($_POST['user_email']) ){
			echo '<p class="warning">You must enter a valid email address.</p>';
			$check = false;
		}			
		if ( email_exists($_POST['user_email']) ){
			echo '<p class="warning">Sorry, that email address is already used!</p>';
			$check = false;
		}
		
		if ($_POST['user_password'] != $_POST['user_password2']){
			echo '<p class="warning">Oops! Password did not match! Try again</p>';
			$check = false;		 
		}else{
			$user_pass = $_POST['user_password'];
		}	
		
		$skills_arr = array();
		
		if (isset($_POST['skills'])){
			$skills_arr = $_POST['skills'];

		}
		

		
		//print_r($skills_arr);
		
		if($check){
			$userdata = array(
				'user_pass' => $user_pass,
				'user_login' => esc_attr( $_POST['user_email'] ),
				'first_name' => esc_attr( $_POST['first_name'] ),
				'user_email' => esc_attr( $_POST['user_email'] ),
				'role' => $_POST['user_title'],
			);	// 'role' => 'employee'
			$new_user_id = wp_insert_user( $userdata );
			if($new_user_id){
				//echo '<p class="success">Created done!</p>';
				wp_new_user_notification($new_user_id, $user_pass);
				
//				$max_hours = $_POST['maximum_hours'];
//				$available_hours = $_POST['available_hours'];
				$team = $_POST['team'];
				
//				update_user_meta( $new_user_id, '_stm_max_hours', $max_hours );
//				update_user_meta( $new_user_id, '_stm_available_hours', $available_hours );

				$available_hours_monday = $_POST['available_hours_monday'];
				$available_hours_tuesday = $_POST['available_hours_tuesday'];
				$available_hours_wednesday = $_POST['available_hours_wednesday'];
				$available_hours_thursday = $_POST['available_hours_thursday'];
				$available_hours_friday = $_POST['available_hours_friday'];
				
				update_user_meta( $new_user_id, '_stm_available_hours_monday', $available_hours_monday );
				update_user_meta( $new_user_id, '_stm_available_hours_tuesday', $available_hours_tuesday );
				update_user_meta( $new_user_id, '_stm_available_hours_wednesday', $available_hours_wednesday );
				update_user_meta( $new_user_id, '_stm_available_hours_thursday', $available_hours_thursday );
				update_user_meta( $new_user_id, '_stm_available_hours_friday', $available_hours_friday );
				
				
				
				
				update_user_meta( $new_user_id, '_stm_team', $team );
				//update_user_meta( $new_user_id, '_stm_skills', $skills_arr );
				add_user_meta( $new_user_id, '_stm_skills', $skills_arr);
				
				
				$user_first_name = $_POST['first_name'];
				
				$post_title = $user_first_name.'\'s Work Schedule';
				
				$defaults_timesheet = array(
							  'post_type'      => 'timesheet',
							  'post_title'     => $post_title,
							  'post_author'    => $new_user_id,
							  'post_status'      => 'publish'
							);							
											
				if($timesheet_id = wp_insert_post( $defaults_timesheet )) {
					// add post meta data
					add_post_meta($timesheet_id, '_stm_employee_id', $new_user_id);
					
					//add user profile to post
					update_user_meta( $new_user_id, '_stm_timesheet_post_id', $timesheet_id );		
					
					update_user_meta( $new_user_id, 'show_admin_bar_front', 'false' );	
					
					stm_add_timesheet_member_data($new_user_id, $timesheet_id, $team, $available_hours_monday, $available_hours_tuesday, $available_hours_wednesday, $available_hours_thursday, $available_hours_friday, $skills_arr);					

				}				
				
//				$profile_edit_url = get_permalink(get_option('stm_profile_edit_page'));
//				$arr_params = array( 'registration' => 'success', 'user' => $new_user_id );
//				$profile_edit_success_url = add_query_arg( $arr_params,  $profile_edit_url);	
				
				//echo $profile_edit_success_url;	
				
				$login_url = admin_url();
				
				echo '<script type="text/javascript">window.location = "'.$login_url.'"</script>';		

			}
			
		}	
		
		
				
	}
	
	if ( is_user_logged_in() ) {
		//$profile_edit_url = get_permalink(get_option('stm_profile_edit_page'));
		echo 'Welcome, registered user! ';
		//echo '<a href="'.$profile_edit_url.'">Click here to edit your profile</a>';
	} else {
	
	?>
		
	<form class="stm_register" action="" method="post">
		<p><label for="first_name">Name:</label> <input type="text" name="first_name" value="" id="first_name" class="input"  /></p>
		
<!--		<p><label for="maximum_hours">Maximum Hours:</label> <input type="number" name="maximum_hours" value="" id="maximum_hours" class="input"  /></p>
		<p><label for="available_hours">Available Hours:</label> <input type="number" name="available_hours" value="" id="available_hours" class="input"  /></p>
-->		
		<p><label for="available_hours_monday">Available Hours Monday:</label> <input type="number" name="available_hours_monday" value="" id="available_hours_monday" class="input"  /></p>
		<p><label for="available_hours_tuesday">Available Hours Tuesday:</label> <input type="number" name="available_hours_tuesday" value="" id="available_hours_tuesday" class="input"  /></p>
		<p><label for="available_hours_wednesday">Available Hours Wednesday:</label> <input type="number" name="available_hours_wednesday" value="" id="available_hours_wednesday" class="input"  /></p>
		<p><label for="available_hours_thursday">Available Hours Thursday:</label> <input type="number" name="available_hours_thursday" value="" id="available_hours_thursday" class="input"  /></p>
		<p><label for="available_hours_friday">Available Hours Friday:</label> <input type="number" name="available_hours_friday" value="" id="available_hours_friday" class="input"  /></p>
		
		<p><label for="user_email">Email:</label> <input type="text" name="user_email" value="" id="user_email" class="input"  /></p>

		<p>
			<label for="team">Team:</label>
			<select name="team" id="team">
				<option value="">Select</option>
				<option value="Landers">Landers</option>
				<option value="Winkler">Winkler</option>
				<option value="Costigan">Costigan</option>
				<option value="Maners">Maners</option>
				<option value="Barnes">Barnes</option>
				<option value="Chrietzberg">Chrietzberg</option>
				<option value="Outsource">Outsource</option>
                <option value="Front Office">Front Office</option>
			</select>
		</p>
		
		<p class="setof_skills">
			<span>Select Skills:</span>	<br />
				<input type="checkbox" name="skills[]" id="skills1" value="S.F. calcs/exhibits" /> <label for="skills1">S.F. calcs/exhibits</label><br />
				<input type="checkbox" name="skills[]" id="skills2" value="BOMA charts" /> <label for="skills2">BOMA charts</label><br />
				<input type="checkbox" name="skills[]" id="skills3" value="LSBs" /> <label for="skills3">LSBs</label> <br />
				<input type="checkbox" name="skills[]" id="skills4" value="Mktg plans" /> <label for="skills4">Mktg plans</label> <br />
				<input type="checkbox" name="skills[]" id="skills5" value="Stacking plans" /> <label for="skills5">Stacking plans</label><br />
				<input type="checkbox" name="skills[]" id="skills6" value="Surveys" /> <label for="skills6">Surveys</label><br />
				<input type="checkbox" name="skills[]" id="skills7" value="Space plans" /> <label for="skills7">Space plans</label><br />
				<input type="checkbox" name="skills[]" id="skills8" value="Pricing plans" /> <label for="skills8">Pricing plans</label><br />
				<input type="checkbox" name="skills[]" id="skills9" value="Int. Design - high" /> <label for="skills9">Int. Design - high</label><br />
				<input type="checkbox" name="skills[]" id="skills10" value="Int. Design - moderate" /> <label for="skills10">Int. Design - moderate</label><br />
				<input type="checkbox" name="skills[]" id="skills11" value="Int. CDs" /> <label for="skills11">Int. CDs</label><br />
				<input type="checkbox" name="skills[]" id="skills12" value="Int. Detailing" /> <label for="skills12">Int. Detailing</label><br />
				<input type="checkbox" name="skills[]" id="skills13" value="CA/submittals" /> <label for="skills13">CA/submittals</label><br />
				<input type="checkbox" name="skills[]" id="skills14" value="Arch. Design" /> <label for="skills14">Arch. Design</label><br />
				<input type="checkbox" name="skills[]" id="skills15" value="Arch. CDs" /> <label for="skills15">Arch. CDs</label><br />
				<input type="checkbox" name="skills[]" id="skills16" value="Arch. Detailing" /> <label for="skills16">Arch. Detailing</label><br />
				<input type="checkbox" name="skills[]" id="skills17" value="Finish boards" /> <label for="skills17">Finish boards</label><br />
				<input type="checkbox" name="skills[]" id="skills18" value="Illustrator - high" /> <label for="skills18">Illustrator - high</label><br />
				<input type="checkbox" name="skills[]" id="skills19" value="Illustrator - moderate" /> <label for="skills19">Illustrator - moderate</label><br />
				<input type="checkbox" name="skills[]" id="skills20" value="Photoshop - high" /> <label for="skills20">Photoshop - high</label><br />
				<input type="checkbox" name="skills[]" id="skills21" value="Photoshop - moderate" /> <label for="skills21">Photoshop - moderate</label><br />
				<input type="checkbox" name="skills[]" id="skills22" value="Sketchup - high" /> <label for="skills22">Sketchup - high</label><br />
				<input type="checkbox" name="skills[]" id="skills23" value="Sketchup - moderate" /> <label for="skills23">Sketchup - moderate</label><br />
				<input type="checkbox" name="skills[]" id="skills24" value="TDLR" /> <label for="skills24">TDLR</label><br />
				<input type="checkbox" name="skills[]" id="skills25" value="IECC" /> <label for="skills25">IECC</label><br />			
		</p>				
		
		<p>
        	<label for="user_title">Title:</label>
            <select name="user_title" id="user_title">
            	<option value="employee">Employee</option>
            	<option value="contractor">Contractor</option>
            </select>
        </p>
		<p><label for="user_password">Password:</label> <input type="password" name="user_password" value="" id="user_password" class="input" /></p>
		<p><label for="user_password2">Retype Password:</label> <input type="password" name="user_password2" value="" id="user_password2" class="input" /></p>
		<?php do_action('register_form'); ?>
		<?php wp_nonce_field( 'stm_register_action', 'stm_register_submit' ); ?>
		<input type="submit" value="Register" id="register" />
	</form>		
	
	<?php	
	}
	
	$registration_form = ob_get_contents();
	ob_end_clean();	
	return $registration_form;	
	
}
add_shortcode('stm_register','stm_member_registration_form');

