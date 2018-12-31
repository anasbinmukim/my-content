<?php
// global $wpdb;
// echo $wpdb->prefix;

function is_yl_current_latest_lease($checked_lease_id){
	$result = FALSE;
	//get suite id from lease
	$suite_id = yl_get_suite_id_by_lease_id($checked_lease_id);
	echo "<br />";
	//get lease id from suite
	echo $lease_id = get_post_meta($suite_id, '_yl_lease_id', true);
	
	if($checked_lease_id == $lease_id)
		$result = TRUE;
		
	//return 	$result;
}

function yl_get_bm_lease_list_sc() {
	$today_date=date("Y-m-d");
	$args = array(
		'post_type' 		=> 'lease',
		'posts_per_page'	=> -1,
		'orderby'   		=> 'post_date',
		'order'     		=> 'DESC',
		'post_status'		=> 'any'
	);
	ob_start();

	$redirect = get_permalink();

	$query = new WP_Query( $args );
	//echo $query->request;
	$output = '';
	if( is_user_logged_in() ) {
		if( current_user_can('building_manager') ) {
			if($query->have_posts()) {
				
				//echo "<pre>";print_r($_yl_ninty_day_vacate_date);echo "</pre>";
				?>
				<style>
					.icon_has_autopay{ background:#000000; color:#FFFFFF; border-radius:50%; padding:2px 5px; font-size:12px; }
				</style>
				<div class="row">
					<div class="col-md-12 form-group bm_lease_list_filter_container">
						<input type="text" class="form-control bm_lease_list_filter" placeholder="Filter suites, companies and clients">
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<table class="lease_list lease_list_table table table-striped" data-page-length="50" data-order="[[ 0, &quot;asc&quot; ]]">
							<thead>
								<tr>
									<th>Suite</th>
									<th>Company</th>
									<th></th>
									<th>Profile</th>
									<th>Client</th>
									<th class="text-center">BM LS Sign</th>
									<th class="text-center">Client LS Sign</th>
									<th class="text-center">Client L Sign</th>
									<th class="text-center">BM L Sign</th>
									<th class="text-center">Vacate</th>
									<th class="text-center">Lease PDF</th>
								</tr>
							</thead>
							<tbody>

								<?php
								global $post, $yl_latest_active_leases;
								while ( $query->have_posts() ) {
									
									//echo count($_yl_ninty_day_vacate_date);
									$query->the_post();
									$company_id = get_post_meta(get_the_ID(), '_yl_company_name', true);
									$post_meta = get_post_meta(get_the_ID());
									$step_open = 0;
									
									$profile_user_id = 0;									
									$profile_user_id = get_post_meta(get_the_ID(), '_yl_lease_user', true);
									
									
									$lease_suite_id = yl_get_suite_id_by_lease_id(get_the_ID());
									$latest_active_lease_id = 0;
									if (in_array(get_the_ID(), $yl_latest_active_leases)) {
										$latest_active_lease_id = get_the_ID();
									}
									
									$lease_sa_client_id = 0;
									$lease_sa_client_id = yl_get_client_id_by_user_id($profile_user_id);

									//$post_id_k=get_the_ID();
									$_yl_ninty_day_vacate_date=get_post_meta(get_the_ID(),'_yl_ninty_day_vacate_date');

									if(count($_yl_ninty_day_vacate_date) == "0")
									{ ?>
										<tr class="count_0">
											<td data-suite-name>
												<?php  ?>
												<span class="list-filter-hidden">
												<?php 
												echo strtolower(((get_post_meta(get_the_ID(), '_yl_suite_number', true) == -1) ? 'Y-membership' : get_post_meta(get_the_ID(), '_yl_suite_number', true))); 
												?>
												</span>
												
												<?php
												if((get_post_meta(get_the_ID(), '_yl_suite_number', true) == -1) || (get_post_meta(get_the_ID(), '_yl_product_id', true) == -1)){
													echo "Y-membership";
												}else{
												?>
												<a href="<?php echo get_permalink(get_the_ID()); ?>">
												<?php 
												echo get_post_meta(get_the_ID(), '_yl_suite_number', true); ?>
												</a>
												<?php } ?>
											</td>
											<td>
												<span class="list-filter-hidden">
												<?php 
												echo strtolower(get_the_title($company_id)); 
												?>
												</span>

												<a href="<?php echo get_permalink($company_id); ?>"><?php echo get_the_title($company_id); ?></a>
											</td>
											<td><?php echo yl_is_autopay_setup($lease_sa_client_id); ?></td>
											<td><a href="/my-account/?tab=lease&request_profile=<?php echo $profile_user_id; ?>"><i class="fa fa-user" aria-hidden="true"></i></a></td>
											<td>
												<?php
												$client_id = yl_get_client_id_by_user_id(get_post_meta(get_the_ID(), '_yl_lease_user', true));
												$client_meta = get_post($client_id);
												?>

												<span class="list-filter-hidden">
												<?php 
												echo strtolower($client_meta->post_title); 
												?>
												</span>

												<?php
												echo esc_html($client_meta->post_title); 
												?>
											</td>
											<td class="text-center">
												<?php
												if ($post_meta['_yl_bm_ls_signature'][0]) {
													$step_open = 2;
													?>
													<i class="fa fa-check" aria-hidden="true"></i>
													<?php
												} else {
													$page = get_permalink(get_option('yl_lease_summary_page'));
													?>
													<a class="btn btn-primary btn-xs" href="<?php echo $page; ?>?lid=<?php echo get_the_ID(); ?>&amp;redirect=<?php echo $redirect; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sign</a>
													<?php
												}
												?>
											</td>
											<td class="text-center">
												<?php
												if ($post_meta['_yl_client_ls_signature'][0]) {
													$step_open = 3;
													?>
													<i class="fa fa-check" aria-hidden="true"></i>
													<?php
												} else {
													if ($step_open >= 2) {
														?>
														<span class="btn btn-primary btn-xs btn-resend-client-ls-sign-email" data-lease-id="<?php echo get_the_ID(); ?>"><i class="fa fa-reply" aria-hidden="true"></i> Re-send</span>
														<?php
															$page_step_two = get_permalink(get_option('yl_summary_sign_page'));
														?>
														<br /><br /><a class="btn btn-primary btn-xs" href="<?php echo $page_step_two; ?>?lid=<?php echo get_the_ID(); ?>&amp;bmprocess=1"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Continue</a>
														<?php
													}
													else {
														?>
														<i class="fa fa-times yl-icon-grey" aria-hidden="true"></i>
														<?php
													}
												}
												?>
											</td>
											<td class="text-center">
												<?php
												if ($post_meta['_yl_client_signature'][0]) {
													$step_open = 4;
													?>
													<i class="fa fa-check" aria-hidden="true"></i>
													<?php
												} else {
													if ($step_open >= 3) {
														?>
														<span class="btn btn-primary btn-xs btn-resend-client-l-sign-email" data-lease-id="<?php echo get_the_ID(); ?>"><i class="fa fa-reply" aria-hidden="true"></i> Re-send</span>
														<?php
																$page_step_three = get_permalink(get_option('yl_client_sign_page'));
																?>
																<br /><br /><a class="btn btn-primary btn-xs" href="<?php echo $page_step_three; ?>?lid=<?php echo get_the_ID(); ?>&amp;bmprocess=1"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Continue</a>
																
														<?php
													}
													else {
														?>
														<i class="fa fa-times yl-icon-grey" aria-hidden="true"></i>
														<?php
													}
												}
												?>
											</td>
											<td class="text-center">
												<?php
												if ($post_meta['_yl_bm_signature'][0]) {
													?>
													<i class="fa fa-check" aria-hidden="true"></i>
													<?php
												} else {
													if ($step_open >= 4) {
														$page =  get_permalink(get_option('yl_bm_sign_page'));
														?>
														<a class="btn btn-primary btn-xs" href="<?php echo $page; ?>?lid=<?php echo get_the_ID(); ?>&amp;redirect=<?php echo $redirect; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sign</a>
														<?php
													}
													else {
														?>
														<i class="fa fa-times yl-icon-grey" aria-hidden="true"></i>
														<?php
													}
												}
												?>
											</td>
											<td class="text-center">
												<?php
									            //$avail_date = get_post_meta(get_the_ID(), '_yl_date_vacate_notice_given', true);
												$avail_date = get_post_meta(get_the_ID(), '_yl_date_vacate_notice_given', true);
												if($avail_date)
												{
													$is_early_vacate = get_post_meta(get_the_ID(), '_yl_early_vacate_addendum', true);
													if($is_early_vacate=='yes')
													{
														$vacate_datetime = get_post_meta(get_the_ID(), '_yl_date_vacate_notice_given', true);
														$avail_date = $vacate_datetime;
													}else{
														$vacate_datetime = get_post_meta(yl_get_suite_id_by_lease_id(get_the_ID()), '_yl_available_date', true);
														$avail_date = $vacate_datetime;
														//newcode
													}
												}
												if( ($latest_active_lease_id != get_the_ID()) && get_post_meta(get_the_ID(), '_yl_ninty_day_vacate_date', true)){
													$vacate_date = get_post_meta(get_the_ID(), '_yl_ninty_day_vacate_date', true);
									              ?>
									              <span class="available-at-date">Vacate Date: <strong><?php echo $vacate_date; ?></strong></span>
									              <?php													
												}elseif ($avail_date) {
									              ?>
									              <span class="available-at-date">This suite will be available on <strong><?php echo $avail_date; ?></strong></span>
									              <?php
									            }elseif( $latest_active_lease_id == get_the_ID() ) {
									            	?>
										            <a href="<?php echo get_permalink(get_option('yl_vacate_notice_page')); ?>?lid=<?php echo get_the_ID(); ?>" class="btn btn-danger btn-xs">Give Notice</a>																	
													
										            <?php
									            }
									            ?>
											</td>
											<td class="text-center">
												<?php
													if( get_post_meta(get_the_ID(), '_yl_full_lease_pdf', true) ) {
															echo '<a title="Download Lease PDF" href="'.get_post_meta(get_the_ID(), '_yl_full_lease_pdf', true).'" target="_blank"> <i class="fa fa-download" aria-hidden="true"></i></a>';
													}else{
														echo '<a title="Download Lease PDF" href="?download_lease='.get_the_ID().'"> <i class="fa fa-download" aria-hidden="true"></i></a>';
													}
												?>											
											</td>
										</tr>
									<?php }
									else
									{
										if($_yl_ninty_day_vacate_date[0] != "")
										{
											//echo $_yl_ninty_day_vacate_date[0].">".$today_date.":::".$post_id_k."</br>";
											//echo strtotime($_yl_ninty_day_vacate_date[0]).">".strtotime($today_date)."</br>";
											$today = date("Y-m-d");
											$expire = $_yl_ninty_day_vacate_date[0]; //from db

											$today_time = strtotime($today);
											$expire_time = strtotime($expire);

											if ($expire_time > $today_time)
											{ ?>
												<tr class="count_1">
													<td data-suite-name>
													
														<span class="list-filter-hidden">
														<?php 

														echo strtolower(((get_post_meta(get_the_ID(), '_yl_suite_number', true) == -1) ? 'Y-membership' : get_post_meta(get_the_ID(), '_yl_suite_number', true))); 
														?>
														</span>

														<?php
														if((get_post_meta(get_the_ID(), '_yl_suite_number', true) == -1) || (get_post_meta(get_the_ID(), '_yl_product_id', true) == -1)){
															echo "Y-membership";
														}else{
														?>
														<a href="<?php echo get_permalink(get_the_ID()); ?>">
														<?php 
														echo get_post_meta(get_the_ID(), '_yl_suite_number', true); ?>
														</a>
														<?php } ?>
													</td>
													<td>
														<span class="list-filter-hidden">
														<?php 
														echo strtolower(get_the_title($company_id)); 
														?>
														</span>

														<a href="<?php echo get_permalink($company_id); ?>"><?php echo esc_html(get_the_title($company_id)); ?></a>
													</td>
													<td><?php echo yl_is_autopay_setup($lease_sa_client_id); ?></td>
													<td><a href="/my-account/?tab=lease&request_profile=<?php echo $profile_user_id; ?>"><i class="fa fa-user" aria-hidden="true"></i></a></td>
													<td>
														<?php
														$client_id = yl_get_client_id_by_user_id(get_post_meta(get_the_ID(), '_yl_lease_user', true));
														$client_meta = get_post($client_id);
														?>

														<span class="list-filter-hidden">
														<?php 
														echo strtolower($client_meta->post_title); 
														?>
														</span>

														<?php
														echo esc_html($client_meta->post_title); 
														?>
													</td>
													<td class="text-center">
														<?php
														if ($post_meta['_yl_bm_ls_signature'][0]) {
															$step_open = 2;
															?>
															<i class="fa fa-check" aria-hidden="true"></i>
															<?php
														} else {
															$page = get_permalink(get_option('yl_lease_summary_page'));
															?>
															<a class="btn btn-primary btn-xs" href="<?php echo $page; ?>?lid=<?php echo get_the_ID(); ?>&amp;redirect=<?php echo $redirect; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sign</a>
															<?php
														}
														?>
													</td>
													<td class="text-center">
														<?php
														if ($post_meta['_yl_client_ls_signature'][0]) {
															$step_open = 3;
															?>
															<i class="fa fa-check" aria-hidden="true"></i>
															<?php
														} else {
															if ($step_open >= 2) {
																?>
																<span class="btn btn-primary btn-xs btn-resend-client-ls-sign-email" data-lease-id="<?php echo get_the_ID(); ?>"><i class="fa fa-reply" aria-hidden="true"></i> Re-send</span>
																<?php
																$page_step_two = get_permalink(get_option('yl_summary_sign_page'));
																?>
																<br /><br /><a class="btn btn-primary btn-xs" href="<?php echo $page_step_two; ?>?lid=<?php echo get_the_ID(); ?>&amp;bmprocess=1"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Continue</a>
																<?php
															}
															else {
																?>
																<i class="fa fa-times yl-icon-grey" aria-hidden="true"></i>
																<?php
															}
														}
														?>
													</td>
													<td class="text-center">
														<?php
														if ($post_meta['_yl_client_signature'][0]) {
															$step_open = 4;
															?>
															<i class="fa fa-check" aria-hidden="true"></i>
															<?php
														} else {
															if ($step_open >= 3) {
																?>
																<span class="btn btn-primary btn-xs btn-resend-client-l-sign-email" data-lease-id="<?php echo get_the_ID(); ?>"><i class="fa fa-reply" aria-hidden="true"></i> Re-send</span>
																<?php
																$page_step_three = get_permalink(get_option('yl_client_sign_page'));
																?>
																<br /><br /><a class="btn btn-primary btn-xs" href="<?php echo $page_step_three; ?>?lid=<?php echo get_the_ID(); ?>&amp;bmprocess=1"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Continue</a>
																<?php
															}
															else {
																?>
																<i class="fa fa-times yl-icon-grey" aria-hidden="true"></i>
																<?php
															}
														}
														?>
													</td>
													<td class="text-center">
														<?php
														if ($post_meta['_yl_bm_signature'][0]) {
															?>
															<i class="fa fa-check" aria-hidden="true"></i>
															<?php
														} else {
															if ($step_open >= 4) {
																$page =  get_permalink(get_option('yl_bm_sign_page'));
																?>
																<a class="btn btn-primary btn-xs" href="<?php echo $page; ?>?lid=<?php echo get_the_ID(); ?>&amp;redirect=<?php echo $redirect; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sign</a>
																<?php
															}
															else {
																?>
																<i class="fa fa-times yl-icon-grey" aria-hidden="true"></i>
																<?php
															}
														}
														?>
													</td>
													<td class="text-center">
														<?php
														$avail_date = get_post_meta(get_the_ID(), '_yl_date_vacate_notice_given', true);
														if($avail_date)
							                            {
															$is_early_vacate = get_post_meta(get_the_ID(), '_yl_early_vacate_addendum', true);
															if($is_early_vacate=='yes')
															{
																$vacate_datetime = get_post_meta(yl_get_suite_id_by_lease_id(get_the_ID()), '_yl_available_date', true);
																$avail_date = $vacate_datetime;
															}else{
																$vacate_datetime = get_post_meta(yl_get_suite_id_by_lease_id(get_the_ID()), '_yl_available_date', true);
																$avail_date = $vacate_datetime;
															}
														}
														
											            if( ($latest_active_lease_id != get_the_ID()) && get_post_meta(get_the_ID(), '_yl_ninty_day_vacate_date', true)){
															$vacate_date = get_post_meta(get_the_ID(), '_yl_ninty_day_vacate_date', true);
															  ?>
															  <span class="available-at-date">Vacate Date: <strong><?php echo $vacate_date; ?></strong></span>
															  <?php													
														}elseif ($avail_date) {
											              ?>
											              <span class="available-at-date">This suite will be available on <strong><?php echo $avail_date; ?></strong></span>
											              <?php
											            }elseif( $latest_active_lease_id == get_the_ID() ) {
											            	?>
												            <a href="<?php echo get_permalink(get_option('yl_vacate_notice_page')); ?>?lid=<?php echo get_the_ID(); ?>" class="btn btn-danger btn-xs">Give Notice</a>
														
												            <?php
											            }
											            ?>
													</td>
													<td class="text-center">
														<?php
															if( get_post_meta(get_the_ID(), '_yl_full_lease_pdf', true) ) {
															echo '<a title="Download Lease PDF" href="'.get_post_meta(get_the_ID(), '_yl_full_lease_pdf', true).'" target="_blank"> <i class="fa fa-download" aria-hidden="true"></i></a>';
															}else{
																echo '<a title="Download Lease PDF" href="?download_lease='.get_the_ID().'"> <i class="fa fa-download" aria-hidden="true"></i></a>';
															}
														?>											
													</td>													
												</tr>
											<?php }
										}
										else
										{ ?>
											<tr class="count_0">
												<td data-suite-name>
													
													<span class="list-filter-hidden">
													<?php 
													echo strtolower(((get_post_meta(get_the_ID(), '_yl_suite_number', true) == -1) ? 'Y-membership' : get_post_meta(get_the_ID(), '_yl_suite_number', true))); 
													?>
													</span>

													<a href="<?php echo get_permalink(get_the_ID()); ?>">
													<?php 
													echo ((get_post_meta(get_the_ID(), '_yl_suite_number', true) == -1) ? 'Y-membership' : get_post_meta(get_the_ID(), '_yl_suite_number', true)); 
													?>
													</a>
												</td>
												<td>
													<span class="list-filter-hidden">
													<?php 
													echo strtolower(get_the_title($company_id)); 
													?>
													</span>

													<a href="<?php echo get_permalink($company_id); ?>"><?php echo esc_html(get_the_title($company_id)); ?></a>
												</td>
												<td><?php echo yl_is_autopay_setup($lease_sa_client_id); ?></td>
												<td><a href="/my-account/?tab=lease&request_profile=<?php echo $profile_user_id; ?>"><i class="fa fa-user" aria-hidden="true"></i></a></td>
												<td>
													<?php
													$client_id = yl_get_client_id_by_user_id(get_post_meta(get_the_ID(), '_yl_lease_user', true));
													$client_meta = get_post($client_id);
													?>

													<span class="list-filter-hidden">
													<?php 
													echo strtolower($client_meta->post_title); 
													?>
													</span>

													<?php
													echo esc_html($client_meta->post_title); 
													?>
												</td>
												<td class="text-center">
													<?php
													if ($post_meta['_yl_bm_ls_signature'][0]) {
														$step_open = 2;
														?>
														<i class="fa fa-check" aria-hidden="true"></i>
														<?php
													} else {
														$page = get_permalink(get_option('yl_lease_summary_page'));
														?>
														<a class="btn btn-primary btn-xs" href="<?php echo $page; ?>?lid=<?php echo get_the_ID(); ?>&amp;redirect=<?php echo $redirect; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sign</a>
														<?php
													}
													?>
												</td>
												<td class="text-center">
													<?php
													if ($post_meta['_yl_client_ls_signature'][0]) {
														$step_open = 3;
														?>
														<i class="fa fa-check" aria-hidden="true"></i>
														<?php
													} else {
														if ($step_open >= 2) {
															?>
															<span class="btn btn-primary btn-xs btn-resend-client-ls-sign-email" data-lease-id="<?php echo get_the_ID(); ?>"><i class="fa fa-reply" aria-hidden="true"></i> Re-send</span>
																<?php
																$page_step_two = get_permalink(get_option('yl_summary_sign_page'));
																?>
																<br /><br /><a class="btn btn-primary btn-xs" href="<?php echo $page_step_two; ?>?lid=<?php echo get_the_ID(); ?>&amp;bmprocess=1"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Continue</a>
															
															<?php
														}
														else {
															?>
															<i class="fa fa-times yl-icon-grey" aria-hidden="true"></i>
															<?php
														}
													}
													?>
												</td>
												<td class="text-center">
													<?php
													if ($post_meta['_yl_client_signature'][0]) {
														$step_open = 4;
														?>
														<i class="fa fa-check" aria-hidden="true"></i>
														<?php
													} else {
														if ($step_open >= 3) {
															?>
															<span class="btn btn-primary btn-xs btn-resend-client-l-sign-email" data-lease-id="<?php echo get_the_ID(); ?>"><i class="fa fa-reply" aria-hidden="true"></i> Re-send</span>
															<?php
																$page_step_three = get_permalink(get_option('yl_client_sign_page'));
																?>
																<br /><br /><a class="btn btn-primary btn-xs" href="<?php echo $page_step_three; ?>?lid=<?php echo get_the_ID(); ?>&amp;bmprocess=1"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Continue</a>
															<?php
														}
														else {
															?>
															<i class="fa fa-times yl-icon-grey" aria-hidden="true"></i>
															<?php
														}
													}
													?>
												</td>
												<td class="text-center">
													<?php
													if ($post_meta['_yl_bm_signature'][0]) {
														?>
														<i class="fa fa-check" aria-hidden="true"></i>
														<?php
													} else {
														if ($step_open >= 4) {
															$page =  get_permalink(get_option('yl_bm_sign_page'));
															?>
															<a class="btn btn-primary btn-xs" href="<?php echo $page; ?>?lid=<?php echo get_the_ID(); ?>&amp;redirect=<?php echo $redirect; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sign</a>
															<?php
														}
														else {
															?>
															<i class="fa fa-times yl-icon-grey" aria-hidden="true"></i>
															<?php
														}
													}
													?>
												</td>
												<td class="text-center">
													<?php
										            //$avail_date = get_post_meta(yl_get_suite_id_by_lease_id(get_the_ID()), '_yl_date_vacate_notice_given', true);
													$avail_date = get_post_meta(get_the_ID(), '_yl_date_vacate_notice_given', true);
										            if( ($latest_active_lease_id != get_the_ID()) && get_post_meta(get_the_ID(), '_yl_ninty_day_vacate_date', true)){
														$vacate_date = get_post_meta(get_the_ID(), '_yl_ninty_day_vacate_date', true);
														  ?>
														  <span class="available-at-date">Vacate Date: <strong><?php echo $vacate_date; ?></strong></span>
														  <?php													
													}elseif($avail_date) {
										              ?>
										              <span class="available-at-date">This suite will be available on <strong><?php echo $avail_date; ?></strong></span>
										              <?php
										           }elseif( $latest_active_lease_id == get_the_ID() ) {
										            	?>
											            <a href="<?php echo get_permalink(get_option('yl_vacate_notice_page')); ?>?lid=<?php echo get_the_ID(); ?>" class="btn btn-danger btn-xs">Give Notice</a>
														<?php
										            }
										            ?>
												</td>
												<td class="text-center">
													<?php
														if( get_post_meta(get_the_ID(), '_yl_full_lease_pdf', true) ) {
															echo '<a title="Download Lease PDF" href="'.get_post_meta(get_the_ID(), '_yl_full_lease_pdf', true).'" target="_blank"> <i class="fa fa-download" aria-hidden="true"></i></a>';
														}else{
															echo '<a title="Download Lease PDF" href="?download_lease='.get_the_ID().'"> <i class="fa fa-download" aria-hidden="true"></i></a>';
														}
													?>											
												</td>
											</tr>
										<?php }
									}
									
									?>
								    <?php
								}
								wp_reset_postdata();
								?>

							</tbody>
						</table>
					</div>
				</div>

				<?php
			} else {
				?>

				<p>Leases not found.</p>
				
				<?php
			}
		} else {
			?>

			<p>Only building manager can view this content.</p>
			
			<?php
		}
	} else {
		?>

		<p>Please login to view this page.</p>
		
		<?php
	}

	return ob_get_clean();
}
add_shortcode('bm_lease_list', 'yl_get_bm_lease_list_sc');


add_action( 'wp_ajax_yl_lease_list_resend_ls_email', 'yl_lease_list_resend_ls_email_callback' );
add_action( 'wp_ajax_nopriv_yl_lease_list_resend_ls_email', 'yl_lease_list_resend_ls_email_callback' );
function yl_lease_list_resend_ls_email_callback() {
	global $wpdb; // this is how you get access to the database

	$lease_id = $_POST['lid'];

	$lease = get_post($lease_id);
	$lease_meta = get_post_meta($lease_id);
	$lease_user = $lease_meta['_yl_lease_user'][0];

	$first_name = $lease_meta['_yl_l_first_name'][0];
	$last_name = $lease_meta['_yl_l_last_name'][0];
	$user_email = $lease_meta['_yl_l_email'][0];
	yl_lease_list_resend_lease_summary_mail($first_name, $last_name, $user_email, $lease_id);

	wp_die(); // this is required to terminate immediately and return a proper response
}


function yl_lease_list_resend_lease_summary_mail($first_name, $last_name, $user_email, $lease_id) {
 	$email_subject = get_option('clients_lease_summary_resend_email_subject');
	$email_message = get_option('clients_lease_summary_resend_email_message');			
	$search = array();
	$replace = array();	
	
	$search[] = '%%client-name%%';
	$replace[] = $first_name;
	
	$search[] = '%%user-name%%';
	$replace[] = $user_email;
	
	$search[] = '%%lease-sign-url%%';
	$replace[] = ( get_permalink(get_option('yl_summary_sign_page')).'?lid='.$lease_id );
	//$replace[] = wp_login_url();
	
	$get_message = str_replace($search, $replace, $email_message);	
	$get_message = stripslashes($get_message);
	$get_message = nl2br($get_message);  
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";

	add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));			
	if (@wp_mail( $user_email, $email_subject, $get_message, $headers )) {
		wp_send_json_success();
	}
	else {
		wp_send_json_error();
	}
}

add_action( 'wp_ajax_yl_lease_list_resend_l_email', 'yl_lease_list_resend_l_email_callback' );
add_action( 'wp_ajax_nopriv_yl_lease_list_resend_l_email', 'yl_lease_list_resend_l_email_callback' );
function yl_lease_list_resend_l_email_callback() {
	global $wpdb; // this is how you get access to the database

	$lease_id = $_POST['lid'];

	$lease = get_post($lease_id);
	$lease_meta = get_post_meta($lease_id);
	$lease_user = $lease_meta['_yl_lease_user'][0];

	$first_name = $lease_meta['_yl_l_first_name'][0];
	$last_name = $lease_meta['_yl_l_last_name'][0];
	$user_email = $lease_meta['_yl_l_email'][0];
	yl_lease_list_resend_lease_mail($first_name, $last_name, $user_email, $lease_id);

	wp_die(); // this is required to terminate immediately and return a proper response
}
function yl_lease_list_resend_lease_mail($first_name, $last_name, $user_email, $lease_id) {
 	$email_subject = get_option('clients_lease_resend_email_subject');
	$email_message = get_option('clients_lease_resend_email_message');			
	$search = array();
	$replace = array();	
	
	$search[] = '%%client-name%%';
	$replace[] = $first_name;
	
	$search[] = '%%user-name%%';
	$replace[] = $user_email;
	
	$search[] = '%%lease-sign-url%%';
	$replace[] = ( get_permalink(get_option('yl_client_sign_page')).'?lid='.$lease_id );
	//$replace[] = wp_login_url();
	
	$get_message = str_replace($search, $replace, $email_message);	
	$get_message = stripslashes($get_message);
	$get_message = nl2br($get_message);  
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";

	add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));			
	if (@wp_mail( $user_email, $email_subject, $get_message, $headers )) {
		wp_send_json_success();
	}
	else {
		wp_send_json_error();
	}
}
