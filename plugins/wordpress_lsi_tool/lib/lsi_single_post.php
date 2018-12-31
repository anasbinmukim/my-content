<?php

get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

$current_user_id=get_current_user_id();

?>
<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php if (et_get_option('divi_integration_single_top') <> '' && et_get_option('divi_integrate_singletop_enable') == 'on') echo(et_get_option('divi_integration_single_top')); ?>

				<?php
					$et_pb_has_comments_module = has_shortcode( get_the_content(), 'et_pb_comments' );
					$additional_class = $et_pb_has_comments_module ? ' et_pb_no_comments_section' : '';
				?>
                <style>
					.ms-protected-info .ms-alternate-msg {background: #fff !important;padding: 4px;font-size: 12px;color: #666;opacity: 0.25;}
					.ms-protected-info {  border: 0px solid rgba(0, 0, 0, 0.07) !important;}
					.ms-protected-info:hover {  border: 0px solid rgba(0, 0, 0, 0.3) !important;}
					.ms-protected-info .ms-contents { padding: 4px 0 !important;}
					.ms-protected-info:hover { opacity: 1; }
					.ms-protected-info .ms-details, .ms-protected-info .ms-alternate-msg {background: rgba(0, 0, 0, 0) none repeat scroll 0 0;opacity: 1 !important;}
					.ms-protected-info:hover{background: rgba(0, 0, 0, 0) none repeat scroll 0 0;}
				</style>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' . $additional_class ); ?>>
				    <?php
					    $lsirecrd_date=$post->post_date;
						$lsisingledate=date("M j, Y",strtotime($lsirecrd_date));

						$lsitags = get_the_terms($post, 'lsi_tags');
						//echo "<pre>";print_r($lsitags);
						if(!empty($lsitags))
						{
							$lsitagshtml='';
							foreach($lsitags as $lsisingletags)
							{
								//term_id,name,slug
								$lsitagid=$lsisingletags->term_id;
								$lsitagname=$lsisingletags->name;
								$lsitagshtml.='<a href="'.get_tag_link($lsitagid).'">'.$lsitagname.'</a>, ';
							}
							$lsitagshtmlfinal=' | '.rtrim($lsitagshtml,", ");
						}

						$lsi_short_desc=get_post_meta($post->ID,'lsi_short_description',true);
					?>
					<div class="et_post_meta_wrapper">
							<h1 class="entry-title"><?php the_title(); ?></h1>
						    <p class="post-meta">
							    <span class="published"><?php echo $lsisingledate; ?></span><?php echo $lsitagshtmlfinal; ?>
							</p>
					</div> <!-- .et_post_meta_wrapper -->


					<div class="entry-content">
						<?php
							$lsiexpldshrtdes=explode("\n",$lsi_short_desc);

							if(empty($lsiexpldshrtdes))
							{
								$displayedtext=$lsi_short_desc;
							}else{
								$displayedtext='<p>'.implode("</p><p>",$lsiexpldshrtdes).'</p>';
							}
							echo $displayedtext;

							$current_lsi_id=$post->ID;
							$usermetakey='lsi_count_no_downloads_for_'.$current_lsi_id;

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

							$getuseroldstartdate=get_user_meta($current_user_id,"crnt_start_subscription_date", true);
							if($getuseroldstartdate===false)
							{
								add_user_meta( $current_user_id,"crnt_start_subscription_date", $getsubsstartdate);
							}else{
								if($getuseroldstartdate!=$getsubsstartdate && $getsubsstartdate!='')
								{
									update_user_meta( $current_user_id, $usermetakey, 0);
								}
								update_user_meta( $current_user_id,"crnt_start_subscription_date", $getsubsstartdate);
							}

							$getuseroldenddate=get_user_meta($current_user_id,"crnt_end_subscription_date", true);
							if($getuseroldenddate===false)
							{
								add_user_meta( $current_user_id,"crnt_end_subscription_date", $getsubsexpiredate);
							}else{
								if($getuseroldenddate!=$getsubsexpiredate && $getsubsexpiredate!='')
								{
									update_user_meta( $current_user_id, $usermetakey, 0);
								}
								update_user_meta( $current_user_id,"crnt_end_subscription_date", $getsubsexpiredate);
							}

                            $getuserprelsidata=get_user_meta($current_user_id, $usermetakey, true);
							$enableproshortpaid=get_option("enable_protection_shortcode_val_".$membership_id);
							$lsinumberdownloads=get_option("lsi_number_of_audion_downloads_".$membership_id);

							if($lsinumberdownloads=='')
							{
								$lsinumberdownloads=2;
							}

							$todaycurrentdate=date('2017-02-03');
							//$todaycurrentdate=date('Y-m-d');
							$subscrirpDateBegin = date('Y-m-d', strtotime($getsubsstartdate));
							$subscripDateEnd = date('Y-m-d', strtotime($getsubsexpiredate));

							if(is_super_admin() || is_admin())
							{
								$downloadaudiolink=CedarWaterLSITOOL_URL.'lib/download_lsi_audio.php?lsi_id='.$post->ID.'&current_user_id='.$current_user_id;
                                $downloadhtmlarea='<a class="morequotes downloadachorlink" id="lsidwnload" href="'.$downloadaudiolink.'">Download</a>';
							}elseif($getuserprelsidata<$lsinumberdownloads && $subscriptionids!='' && (($todaycurrentdate > $subscrirpDateBegin) && ($todaycurrentdate < $subscripDateEnd)) )
							{
								$downloadaudiolink=CedarWaterLSITOOL_URL.'lib/download_lsi_audio.php?lsi_id='.$post->ID.'&current_user_id='.$current_user_id;
                                $downloadhtmlarea='<a class="morequotes downloadachorlink" id="lsidwnload" href="'.$downloadaudiolink.'">Download</a>';
							}else{
								$downloadhtmlarea='';
							}

							$lsi_filename=get_post_meta($post->ID,'lsi_filename',true);
		                    $strshortcdetext='<div class="medifielsp3lsi_abovecls">[s3mediastream type="streamingaudio" file="'.$lsi_filename.'" expires="1200" /]'.$downloadhtmlarea.'</div>';
						?>
						<div class="listing_ct_protedtedarea singlelsipost" id="singlelsidisplaydata">
						<?php
                            if($enableproshortpaid==0 && $subscriptionids!='')
							{
								echo do_shortcode($strshortcdetext);
							}elseif($enableproshortpaid==1)
							{
							    echo do_shortcode('[ms-protect-content id="44,7964,7977,15476,15477"]'.$strshortcdetext.'[/ms-protect-content]');
							}else{
								echo do_shortcode('[ms-protect-content id="44,7964,7977,15476,15477"]'.$strshortcdetext.'[/ms-protect-content]');
							}
						?>
						</div>
					</div> <!-- .entry-content -->
				</article> <!-- .et_pb_post -->

			<?php endwhile; ?>
			</div> <!-- #left-area -->
			<div id="sidebar" class="sidebar_lsi_archive">
            <?php
			if ( is_active_sidebar( 'et_pb_widget_area_8' ) ) :
			dynamic_sidebar( 'et_pb_widget_area_8' );
             endif;  ?>
			</div> <!-- end #sidebar -->
			<!--<div class="blog_sitebar clearfix">
			<?php //dynamic_sidebar( 'et_pb_widget_area_7' ); ?>
			<!--</div><!--blog_sitebar-->
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
<?php get_footer(); ?>
<?php
$is_admin=0;
if($getuserprelsidata<$lsinumberdownloads && $subscriptionids!='')
{
	if($getuserprelsidata=='' || $getuserprelsidata==0)
	{
		$getuserprelsidata=0;
	}
	if($lsinumberdownloads=='' || $lsinumberdownloads==0)
	{
		$lsinumberdownloads=0;
	}
	$is_admin=1;
}
if(is_super_admin() || is_admin())
{
    $is_admin=2;
}
?>
<script>
jQuery(document).ready(function(){
    var counterstart=<?php echo $getuserprelsidata; ?>;
    var counterendlimit=<?php echo $lsinumberdownloads; ?>;
    var iscurrenteradmin=<?php echo $is_admin; ?>;

	if (jQuery( "#lsidwnload" ).length)
	{
		jQuery("#lsidwnload").click(function(){
			counterstart++;
			if(counterstart>=counterendlimit && iscurrenteradmin!=2)
			{
				jQuery("#lsidwnload").hide();
				setTimeout(function(){ jQuery("#lsidwnload").attr("href","javascript:void(0)"); }, 3000);
			}
		});
	}
});
</script>
