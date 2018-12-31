<?php 
echo	$building=$_POST['_accounting_building_id'];
echo "<br>";
// var_dump($building);
// exit();
		 	$start_account=$_POST['_accounting_start_date'];
			$end_account=$_POST['_accounting_end_date'];
	$start_Account2=strtotime($start_account);
							$end_Account2=strtotime($end_account);
			switch_to_blog($building);
					$Missing = array(
										        'post_type'   => 'sa_invoice',
										        'post_status'   => 'any',
										        'posts_per_page'  => -1,
										        'order' => 'ASC',
										      );	

								$invoice_results = get_posts($Missing);
								 $home_url=get_home_url();
								// echo "<pre>";
								// print_r($invoice_results);
								// echo "<pre>";
								// print_r($invoice_results );
								foreach ($invoice_results as  $invoice_result) {
									$due_date=get_post_meta( $invoice_result->ID, '_due_date', true );
									if($due_date=="" || $due_date==false)
									{
								$edit_url= $home_url."/wp-admin/post.php?post=".$invoice_result->ID."&action=edit";

									// if($due_date>=$start_Account2 && $due_date<=$end_Account2 )
									// {

													$invoice_id=$invoice_result->ID;

													echo "Lease id is missing in invoice id <a href='".$edit_url."' >".$invoice_id."</a>"; 
													echo "<br>";

													// echo "Edit link of post".get_edit_post_link($invoice_id);
											?>
						<!-- 					<form class="mk_lease_fixed">
												<input type="hidden" class="mk_building" name="mk_building" value="<?php echo $building; ?>" />
												<input type="hidden" class="mk_invoice_id" name="mk_invoice_id" value="<?php echo $invoice_id; ?>" />
												<input type="text" class="mk_date_fix" name="mk_date_fix" value="" placeholder="Put date" />
												<input type="submit" name="fix_lease" value="Fix Invoice <?php echo $invoice_id; ?>" class="fix_lease">
											</form> -->
											<?php		

									// }
									}


}
 ?>
