<?php
add_action('admin_menu', 'register_import_csv_page');
function register_import_csv_page() {
	add_submenu_page( 'edit.php?post_type=lease', 'Import Lease/Client CSV', 'Import CSV', 'edit_posts', 'import-csv', 'import_csv_page_callback' );
}

function import_csv_page_callback() {
	if(isset($_POST['yl_import_csv_submit'])){
		echo "<div class='updated'><p>Done</p></div>";

		$bm_id = $_POST['bm_id'];
		?>
		<div>

			<?php
			//$csv = plugin_dir_url( __FILE__ ).'import/McKinney.csv';
			//$csv = plugin_dir_url( __FILE__ ).'import/FortHarrison.csv';
			//$csv = plugin_dir_url( __FILE__ ).'import/Greenwood.csv';
			//$csv = plugin_dir_url( __FILE__ ).'import/Noblesville.csv';
			//$csv = plugin_dir_url( __FILE__ ).'import/Noblesville2.csv';
			//$csv = plugin_dir_url( __FILE__ ).'import/Plainfield.csv';
			//$csv = plugin_dir_url( __FILE__ ).'import/Carmel.csv';
			$csv = plugin_dir_url( __FILE__ ).'import/Fishers.csv';


			//return;

			$line_n = 0;
			$lines = array();
			$clients_total = array();
			$companies_total = array();
			$suites_total = array();
			$suites_types = array();


			$file = fopen($csv,"r");
			while(! feof($file)) {
				$line = fgetcsv($file);

				if ($line_n >= 6) {

					$lines[] = $line;
					//print_r($line);
					
					$email 				= trim($line[10]); 		// Added the 1 to the email to avoid accidental emailing of the client
					$company_name 		= trim($line[8]);

					// Suite
					if ($line[4] == 1)
						$floor = '1st';
					if ($line[4] == 2)
						$floor = '2nd';
					if ($line[4] == 3)
						$floor = '3rd';

					$suite = array(
						'suite_n' 		=> $line[0],
						'suite_type' 	=> $line[1],
						'rent_rate'		=> $line[2],
						'available'		=> ucwords(strtolower($line[3])),
						'floor'			=> $floor,
						'dimensions'	=> $line[5],
						'location_type'	=> $line[6],
						'surface'		=> $line[7],
						'move_out'		=> trim($line[15]),
						'avail_date_ts'	=> ((trim($line[15])) ? (strtotime(trim($line[15]))+(60*60*24*2)) : ''),
						'avail_date_raw'=> ((trim($line[15])) ? trim($line[15]) : ''),
						'avail_date'	=> ((trim($line[15])) ? date('Y-m-d', (strtotime(trim($line[15]))+(60*60*24*2))) : ''),
					);

					if ((strtolower(trim($line[1])) == 'suite') || (strtolower(trim($line[1])) == 'storage')) {
						$suites_types[$line[6]] = $line[6];
						$suites_total[$line[0]] = $suite;
					}


					// Company
					$company = array(
						'name'			=> $company_name,
						'email'			=> $email
					);
					$companies_total[$company_name] = $company;

					// Client / Lease Holder
					$line[12] = trim(str_replace("  ", " ", $line[12]));
					$name_parts = explode(' ', $line[12]);
					if (count($name_parts) == 3) {
						$name_parts_tmp = array(
							'first_name'	=> $name_parts[0],
							'middle_name'	=> $name_parts[1],
							'last_name'		=> $name_parts[2]
						);
					}
					else {
						$name_parts_tmp = array(
							'first_name'	=> $name_parts[0],
							'last_name'		=> $name_parts[1]
						);
					}

					// Guarantor
					$line[13] = trim(str_replace("  ", " ", $line[13]));
					$name_parts = explode(' ', $line[13]);
					if (count($name_parts) == 3) {
						$guarantor_name_parts_tmp = array(
							'first_name'	=> $name_parts[0],
							'middle_name'	=> $name_parts[1],
							'last_name'		=> $name_parts[2]
						);
					}
					else {
						$guarantor_name_parts_tmp = array(
							'first_name'	=> $name_parts[0],
							'last_name'		=> $name_parts[1]
						);
					}

					// Address
					$addr_parts = explode(',', $line[9]);
					$address = array(
						'street' => trim($addr_parts[0]),
						'street2' => trim($addr_parts[1]),
						'city'	=> trim($addr_parts[2]),
						'state'	=> trim($addr_parts[3]),
						'zip'	=> trim($addr_parts[4])
					);

					$client = array(
						'address'		=> $address,
						'email'			=> $email, 
						'phone'			=> $line[11],
						'name'			=> $name_parts_tmp,
					);

					$clients_total[$email] = $client;
					
					// Lease
					$lease = array(
						'suite'			=> $line[0],
						'client'		=> $name_parts_tmp,
						'guarantor'		=> $guarantor_name_parts_tmp,
						'deposit'		=> str_replace('$', '', trim($line[14])),
						'rent'			=> str_replace('$', '', trim($line[2])),
						'move_out_date'	=> strtotime(trim($line[15])),
						'multi_discount'=> str_replace('%', '', trim($line[16])),
						'phone_fee'		=> str_replace('$', '', trim($line[17])),
						'service_fee'	=> str_replace('$', '', trim($line[18])),
						'cable_fee'		=> str_replace('$', '', trim($line[19])),
						'ip_fee'		=> str_replace('$', '', trim($line[20])),
						'fax_fee'		=> str_replace('$', '', trim($line[21])),
						'postage_fee'	=> str_replace('$', '', trim($line[22])),
					);

				}
				//echo '<pre>'.print_r($lease, true).'</pre>';
				
				$line_n++;
			}

			fclose($file);

			//echo '<pre>'.print_r($clients_total, true).'</pre>';
			//echo '<pre>'.print_r($suites_total, true).'</pre>';
			//echo '<pre>'.print_r($companies_total, true).'</pre>';
			//echo '<pre>'.print_r($companies_total, true).'</pre>';

			
			
			// Import Companies
			
			foreach ($companies_total as $company) {
				$company_args = array(
				  'post_type'      => 'company',
				  'post_title'     => $company['name'],
				  'post_status'    => 'publish',
				  'post_content'   => '',
				);
				$company_id = yl_create_company($company_args);
				$companies_total[$company['name']]['id'] = $company_id;
			}
			

			// Import Suites
			
			foreach ($suites_total as $suite) {
				$suite_title = 'Suite #'.$suite['suite_n'];

				if (strtolower(trim($suite['suite_type'])) == 'storage') {
					$suite_title = 'Storage Unit #'.$suite['suite_n'];
				}

				$suite_args = array(
				  'post_type'      => 'suites',
				  'post_title'     => $suite_title,
				  'post_status'    => 'publish',
				  'post_content'   => ''
				);
				$suite_id = wp_insert_post( $suite_args );

				update_post_meta($suite_id, '_yl_room_number', $suite['suite_n']);
				update_post_meta($suite_id, '_yl_rent_rate', $suite['rent_rate']);
				update_post_meta($suite_id, '_yl_available', $suite['available']);
				update_post_meta($suite_id, '_yl_floor_level', $suite['floor']);
				update_post_meta($suite_id, '_yl_dimensions', $suite['dimensions']);
				update_post_meta($suite_id, '_yl_location_type', $suite['location_type']);
				update_post_meta($suite_id, '_yl_square_feet', $suite['surface']);

				$s_type = get_term_by('name', $suite['suite_type'], 'suitestype');
				$suites_taxonomy_id = wp_set_object_terms( $suite_id, $s_type->slug, 'suitestype' );

				update_post_meta($suite_id, '_yl_available_date', ((trim($suite['avail_date_raw'])) ? date('Y-m-d', (strtotime(trim($suite['avail_date_raw']))+(60*60*24*2))) : ''));
				if (trim($suite['avail_date_raw'])) {
					update_post_meta($suite_id, '_yl_date_vacate_notice_given', ((trim($suite['avail_date_raw'])) ? date('Y-m-d', (strtotime(trim($suite['avail_date_raw']))+(60*60*24*2))) : ''));
				}
			}
			

			// Import Clients
			
			foreach ($clients_total as $cl) {
				if ($cl['email'] != '') {
					$client_arg['user_login'] 	= $cl['email'];
					$client_arg['user_email'] 	= $cl['email'];
					$client_arg['first_name'] 	= $cl['name']['first_name'];
					$client_arg['middle_name'] 	= $cl['name']['middle_name'];
					$client_arg['last_name'] 	= $cl['name']['last_name'];
					$client_arg['phone'] 		= $cl['phone'];
					$client_arg['address_1']	= $cl['address']['street'];
					$client_arg['address_2']	= $cl['address']['street2'];
					$client_arg['city']			= $cl['address']['city'];
					$client_arg['state']		= $cl['address']['state'];
					$client_arg['zip']			= $cl['address']['zip'];
					yl_register_client_user($client_arg, false);
				}
			}
			

			// Leases
			
			foreach ($lines as $l) {
				$is_storage = false;
				if (strtolower(trim($l[1])) == 'storage') {
					$is_storage = true;
				}


				if (!trim($l[1])) {
					continue;
				}

				// Suite
				$args = array(
					'post_type' 	=> 'suites',
					'post_status'   => 'publish',
					'numberposts'   => '-1',
					'meta_query'	=> array(
						array(
							'key'     => '_yl_room_number',
							'value'   => $l['0'],
							'compare' => '='
						)
					)
				);
				$suite_obj = get_posts($args)[0];

				// User
				$email = trim($l[10]); 		// Added the 1 to the email to avoid accidental emailing of the client
				$user_obj = get_user_by( 'email' , $email );

				// Company
				$company_name = trim($l[8]);				
				$args = array(
					'post_type' 	=> 'company',
					'post_status'   => 'publish',
					's'				=> $company_name,
				);
				$company_obj = get_posts($args)[0];

				// Start the Lease Generation
				$product_id = $suite_obj->ID;
				$user_id	= $user_obj->ID;
				$company_id = $company_obj->ID;
				
				if ($product_id) {
					$lease_title = get_the_title( $product_id );
				}
				else {
					$lease_title = trim($l[1]);
				}

				$lease_args = array(
				  'post_type'      => 'lease',
				  'post_title'     => $lease_title.' Lease',
				  'post_status'    => 'publish',
				  'post_content'   => '',
				  'post_author'    => $bm_id
				);

				//echo '<pre>'.print_r($lease_args, true).'</pre>';

				
				if ($lease_id = wp_insert_post( $lease_args )) {
					$deposit = ((trim($l[14])) ? trim($l[14]) : '0');
					$rent = ((trim($l[2])) ? trim($l[2]) : '0');

					update_post_meta($lease_id, '_yl_product_id', $product_id);
					update_post_meta($lease_id, '_yl_author_id', $user_id);

					$user_data = get_userdata( $user_id );
					update_post_meta($lease_id, '_yl_author_email', $user_data->user_email);
					update_post_meta($lease_id, '_yl_author_name', $user_data->first_name);
					update_post_meta($lease_id, '_yl_lessor', get_option('yl_lessor'));
					update_post_meta($lease_id, '_yl_location', get_option('yl_location'));
					update_post_meta($lease_id, '_yl_location_phone_number', get_option('yl_location_phone'));

					if ($product_id) {
						update_post_meta($lease_id, '_yl_suite_number', get_the_title( $product_id ));
						update_post_meta($lease_id, '_yl_security_deposit', str_replace('$', '', trim($l[14])));
						update_post_meta($lease_id, '_yl_monthly_rent', str_replace('$', '', trim($l[2])));
					}
					else {
						update_post_meta($lease_id, '_yl_suite_number', -1);
						update_post_meta($lease_id, '_yl_security_deposit', str_replace('$', '', trim($l[14])));
						update_post_meta($lease_id, '_yl_monthly_rent', str_replace('$', '', trim($l[2])));
					}

					if ($is_storage == true) {
						update_post_meta($lease_id, '_yl_is_storage', '1');
					}

					update_post_meta($lease_id, '_yl_l_first_name', get_user_meta($user_id, '_yl_l_first_name', true));
					update_post_meta($lease_id, '_yl_l_middle_name', get_user_meta($user_id, '_yl_l_middle_name', true));
					update_post_meta($lease_id, '_yl_l_last_name', get_user_meta($user_id, '_yl_l_last_name', true));
					update_post_meta($lease_id, '_yl_l_phone', get_user_meta($user_id, '_yl_l_phone', true));
					update_post_meta($lease_id, '_yl_l_email', $email);
					update_post_meta($lease_id, '_yl_l_street_address', get_user_meta($user_id, '_yl_l_street_address', true));
					update_post_meta($lease_id, '_yl_l_address_line_2', get_user_meta($user_id, '_yl_l_address_line_2', true));
					update_post_meta($lease_id, '_yl_l_city', get_user_meta($user_id, '_yl_l_city', true));
					update_post_meta($lease_id, '_yl_l_state', get_user_meta($user_id, '_yl_l_state', true));
					update_post_meta($lease_id, '_yl_l_zip_code', get_user_meta($user_id, '_yl_l_zip_code', true));

					update_post_meta($lease_id, '_yl_g_first_name', get_user_meta($user_id, '_yl_l_first_name', true));
					update_post_meta($lease_id, '_yl_g_middle_name', get_user_meta($user_id, '_yl_l_middle_name', true));
					update_post_meta($lease_id, '_yl_g_last_name', get_user_meta($user_id, '_yl_l_last_name', true));
					update_post_meta($lease_id, '_yl_g_phone', get_user_meta($user_id, '_yl_l_phone', true));
					update_post_meta($lease_id, '_yl_g_email', $email);
					update_post_meta($lease_id, '_yl_g_street_address', get_user_meta($user_id, '_yl_l_street_address', true));
					update_post_meta($lease_id, '_yl_g_address_line_2', get_user_meta($user_id, '_yl_l_address_line_2', true));
					update_post_meta($lease_id, '_yl_g_city', get_user_meta($user_id, '_yl_l_city', true));
					update_post_meta($lease_id, '_yl_g_state', get_user_meta($user_id, '_yl_l_state', true));
					update_post_meta($lease_id, '_yl_g_zip_code', get_user_meta($user_id, '_yl_l_zip_code', true));

					update_post_meta($lease_id, '_yl_company_id', $company_id);
					update_post_meta($lease_id, '_yl_company_name', $company_id);

					update_post_meta($lease_id, '_yl_multi_suite_discount', str_replace('%', '', trim($l[16])));
					update_post_meta($lease_id, '_yl_service_fees', str_replace('$', '', trim($l[18])));
					update_post_meta($lease_id, '_yl_phone_fee', str_replace('$', '', trim($l[17])));
					update_post_meta($lease_id, '_yl_cable_fee', str_replace('$', '', trim($l[19])));
					update_post_meta($lease_id, '_yl_ipservice_fee', str_replace('$', '', trim($l[20])));
					update_post_meta($lease_id, '_yl_fax_fee', str_replace('$', '', trim($l[21])));
					update_post_meta($lease_id, '_yl_postage_fee', str_replace('$', '', trim($l[22])));
					update_post_meta($lease_id, '_yl_credit_card_line_fee', 0);

					update_post_meta($lease_id, '_yl_special_received', trim($l[23]));

					update_post_meta($lease_id, '_yl_lease_user', $user_id );
					update_post_meta($lease_id, '_yl_author_id', $bm_id);
					update_post_meta($lease_id, '_yl_lease_start_date', date('Y-m-j', time()));
					yl_update_calendar_credit_for_single_blog(0,$lease_id);
				}
				


			}
			

			?>

		</div>
		<?php
	}
	?>
	<div class="wrap">
	<h2><?php echo __('Import CSV'); ?></h2>
	<form name="yl_lease_pdf" method="post" action="edit.php?post_type=lease&page=import-csv">	
		<p>	<label>Building manager: </label>
			<select name="bm_id">
				<?php
				$user_query = new WP_User_Query( array( 'role' => 'building_manager' ) );
				// User Loop
				if ( ! empty( $user_query->results ) ) {
					foreach ( $user_query->results as $user ) {
						echo '<option value="'.$user->ID.'">'.$user->display_name.'</option>';
					}
				}
				?>
			</select>
		</p>		
		<p class="submit">
			<input type="submit" name="yl_import_csv_submit" class="button-primary" value="<?php _e('Import') ?>" />
		</p>
	</form>	
	</div>
	<?php
}