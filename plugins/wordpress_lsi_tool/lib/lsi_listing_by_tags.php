<?php

get_header();

$archivevals = get_queried_object();
//echo "<pre>";print_r($archivevals);
//term_id,name,slug
$archiveslug=$archivevals->slug;
$archivetermid=$archivevals->term_id;

$lsitaglink=get_tag_link($archivetermid);
?>
<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				    <h1 class="entry-title main_title">LSI under "<?php echo $archivevals->name; ?>"</h1>
					<style>
					    .ms-protected-info .ms-alternate-msg {background: #fff !important;padding: 4px;font-size: 12px;color: #666;opacity: 0.25;}
						.ms-protected-info {  border: 0px solid rgba(0, 0, 0, 0.07) !important;}
						.ms-protected-info:hover {  border: 0px solid rgba(0, 0, 0, 0.3) !important;}
						.ms-protected-info .ms-contents { padding: 4px 0 !important;}
						.ms-protected-info:hover { opacity: 1; }
						.ms-protected-info .ms-details, .ms-protected-info .ms-alternate-msg {background: rgba(0, 0, 0, 0) none repeat scroll 0 0;opacity: 1 !important;}
                        .ms-protected-info:hover{background: rgba(0, 0, 0, 0) none repeat scroll 0 0;}
					</style>
                    <div class="lsi_all_recording_listing_container">
					    <?php

							$taxsqlqry=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts  LEFT JOIN ".$wpdb->prefix."term_relationships ON (".$wpdb->prefix."posts.ID = ".$wpdb->prefix."term_relationships.object_id) WHERE 1=1  AND ( ".$wpdb->prefix."term_relationships.term_taxonomy_id IN (".$archivetermid.") ) AND ".$wpdb->prefix."posts.post_type = 'lsi_posts' AND ((".$wpdb->prefix."posts.post_status = 'publish')) GROUP BY ".$wpdb->prefix."posts.ID ORDER BY ".$wpdb->prefix."posts.post_date DESC LIMIT 0, 10");

							$total_pages = $wpdb->get_row("SELECT COUNT(*) as num FROM ".$wpdb->prefix."posts  LEFT JOIN ".$wpdb->prefix."term_relationships ON (".$wpdb->prefix."posts.ID = ".$wpdb->prefix."term_relationships.object_id) WHERE 1=1  AND ( ".$wpdb->prefix."term_relationships.term_taxonomy_id IN (".$archivetermid.") ) AND ".$wpdb->prefix."posts.post_type = 'lsi_posts' AND ((".$wpdb->prefix."posts.post_status = 'publish')) GROUP BY ".$wpdb->prefix."posts.ID ORDER BY ".$wpdb->prefix."posts.post_date DESC");

							$total_pages = $total_pages->num;

							$lsitoolscustompg=get_option("wp_lsi_tools_custom_listing_page");

							$targetpage = $lsitaglink; 	//your file name  (the name of this file)
							$limit = 30; 								//how many items to show per page
							$page = $_GET['lsi_paged'];
							if($page)
								$start = ($page - 1) * $limit; 			//first item to display on this page
							else
								$start = 0;								//if no page var is given, set start to 0

							$getlsiresults = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts  LEFT JOIN ".$wpdb->prefix."term_relationships ON (".$wpdb->prefix."posts.ID = ".$wpdb->prefix."term_relationships.object_id) WHERE 1=1  AND ( ".$wpdb->prefix."term_relationships.term_taxonomy_id IN (".$archivetermid.") ) AND ".$wpdb->prefix."posts.post_type = 'lsi_posts' AND ((".$wpdb->prefix."posts.post_status = 'publish')) GROUP BY ".$wpdb->prefix."posts.ID ORDER BY ".$wpdb->prefix."posts.post_date DESC LIMIT $start, $limit");

							/* Setup page vars for display. */
							if ($page == 0) $page = 1;					//if no page var is given, default to 1.
							$prev = $page - 1;							//previous page is page - 1
							$next = $page + 1;							//next page is page + 1
							$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
							$lpm1 = $lastpage - 1;						//last page minus 1

							$pagination = "";
							if($lastpage > 1)
							{
								$pagination .= "<div class=\"pagination\">";
								//previous button
								if ($page > 1)
									$pagination.= "<a href=\"$targetpage?lsi_paged=$prev\"> < previous</a>";
								else
									$pagination.= "<span class=\"disabled\"> < previous</span>";

								//pages
								if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
								{
									for ($counter = 1; $counter <= $lastpage; $counter++)
									{
										if ($counter == $page)
											$pagination.= "<span class=\"current\">$counter</span>";
										else
											$pagination.= "<a href=\"$targetpage?lsi_paged=$counter\">$counter</a>";
									}
								}
								elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
								{
									//close to beginning; only hide later pages
									if($page < 1 + ($adjacents * 2))
									{
										for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
										{
											if ($counter == $page)
												$pagination.= "<span class=\"current\">$counter</span>";
											else
												$pagination.= "<a href=\"$targetpage?lsi_paged=$counter\">$counter</a>";
										}
										$pagination.= "...";
										$pagination.= "<a href=\"$targetpage?lsi_paged=$lpm1\">$lpm1</a>";
										$pagination.= "<a href=\"$targetpage?lsi_paged=$lastpage\">$lastpage</a>";
									}
									//in middle; hide some front and some back
									elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
									{
										$pagination.= "<a href=\"$targetpage?lsi_paged=1\">1</a>";
										$pagination.= "<a href=\"$targetpage?lsi_paged=2\">2</a>";
										$pagination.= "...";
										for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
										{
											if ($counter == $page)
												$pagination.= "<span class=\"current\">$counter</span>";
											else
												$pagination.= "<a href=\"$targetpage?lsi_paged=$counter\">$counter</a>";
										}
										$pagination.= "...";
										$pagination.= "<a href=\"$targetpage?lsi_paged=$lpm1\">$lpm1</a>";
										$pagination.= "<a href=\"$targetpage?lsi_paged=$lastpage\">$lastpage</a>";
									}
									//close to end; only hide early pages
									else
									{
										$pagination.= "<a href=\"$targetpage?lsi_paged=1\">1</a>";
										$pagination.= "<a href=\"$targetpage?lsi_paged=2\">2</a>";
										$pagination.= "...";
										for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
										{
											if ($counter == $page)
												$pagination.= "<span class=\"current\">$counter</span>";
											else
												$pagination.= "<a href=\"$targetpage?lsi_paged=$counter\">$counter</a>";
										}
									}
								}

								//next button
								if ($page < $counter - 1)
									$pagination.= "<a href=\"$targetpage?lsi_paged=$next\">next > </a>";
								else
									$pagination.= "<span class=\"disabled\">next > </span>";
								$pagination.= "</div>\n";
							}
						?>
					    <?php
						$lsiarchivelistinghtml='';
					    $lsiarchivelistinghtml='<div class="recoding_listing_tbl">';
					    if(!empty($getlsiresults))
						{
							$k=1+$start;
							foreach($getlsiresults as $getrecording)
							{
								$lsi_short_desc='';
								$lsi_short_desc=get_post_meta($getrecording->ID,'lsi_short_description',true);
								$lsi_filename=get_post_meta($getrecording->ID,'lsi_filename',true);
		                        $strshortcdetext='[s3mediastream type="streamingaudio" file="'.$lsi_filename.'" expires="1200" /]';
								$lsirecrd_date=$getrecording->post_date;
							    $lsisingledate=date("M d, Y",strtotime($lsirecrd_date));
								
								$lsi_term_tag = '';
								$lsi_term_tag = get_the_term_list( $getrecording->ID, 'lsi_tags', '', '' );	
								
								$lsiarchivelistinghtml .= '<div class="recording_list_inner">								    
									    <h4><a href="'.get_permalink($getrecording->ID).'">'.$getrecording->post_title.'</a></h4><p>'.$lsisingledate;
										if($lsi_term_tag){ 
											$lsiarchivelistinghtml .= ' | '.$lsi_term_tag; 
										}											
										$lsiarchivelistinghtml .= '</p>';										
									   $lsiarchivelistinghtml .= '<div class="table_recoding_audlisting">'.do_shortcode($strshortcdetext).'</div>
									
								</div>';
								$k++;
							}
						}
						$lsiarchivelistinghtml.='</div>';
						if($lastpage>1)
						{
							$lsiarchivelistinghtml.='<div class="pagination_container_part">'.$pagination.'</div>';
						}
						
						$getcurrentmember = MS_Model_Member::get_current_member();
						if(!empty($getcurrentmember))
						{
							foreach ( $getcurrentmember->subscriptions as $subscription ) 
							{
								$membership = $subscription->get_membership();
								$membership_id = $membership->id;
								$subscriptionids = $subscription->id;
							}
							$getsubsexpiredate=get_post_meta($subscriptionids,'expire_date',true);
							$getsubsstartdate=get_post_meta($subscriptionids,'start_date',true);
							$getsubsstatus=get_post_meta($subscriptionids,'status',true);
						}
						
						$enableproshortpaid=get_option("enable_protection_shortcode_val_".$membership_id);
					    $lsinumberdownloads=get_option("lsi_number_of_audion_downloads_".$membership_id);
						?>
						<div class="listing_ct_protedtedarea">
						    <?php 
							    if($enableproshortpaid==0 && $subscriptionids!='') 
								{
									echo do_shortcode($lsiarchivelistinghtml);
								}elseif($enableproshortpaid==1)
								{
									echo do_shortcode('[ms-protect-content id="44,7964,7977"]'.$lsiarchivelistinghtml.'[/ms-protect-content]'); 
								}else{
									echo do_shortcode('[ms-protect-content id="44,7964,7977"]'.$lsiarchivelistinghtml.'[/ms-protect-content]'); 
								}	
							?>
						</div>
					</div>
				</article> <!-- .et_pb_post -->

			</div> <!-- #left-area -->

			<div id="sidebar" class="sidebar_lsi_archive">
            <?php
			if ( is_active_sidebar( 'et_pb_widget_area_8' ) ) : 
			dynamic_sidebar( 'et_pb_widget_area_8' ); 
             endif;  ?>           
		</div> <!-- end #sidebar -->
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>
