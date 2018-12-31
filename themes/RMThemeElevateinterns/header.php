<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/fancybox/source/jquery.fancybox.pack.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/fancybox/source/jquery.fancybox.css"/>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/fancybox/source/jquery.fancybox.js"></script>

<script>
$(document).on("click",".year1",function(){
$.fancybox.open("Our goal at Elevate Leadership Institute is not just to see interns graduate with a qualification for ministry, </br>but to see lives transformed by once in a lifetime experiences that will prepare them for leadership in all </br>walks of life. Below is listed the requirements and opportunities thin an interns time at ELI. </br></br>Total Credit hours – 68 </br></br><h1>DURATION</h1> One Year (Three full-time trimesters) </br></br><h1>MINISTRY TRAINING</h1>Elevate Leadership Institute helps to build a basic foundation of how to lead while serving a ministry. </br>After choosing which Intern Area to concentrate in, students will be given opportunities to learn </br>leadership skills and learn the heart of ELI. These areas include the below: </br>Worship </br>Media / Production </br>Administration </br>Student Ministry </br>Kids Ministry </br>Young Adult Ministry </br>Outreach Ministry </br></br><h1>END OF FIRST YEAR</h1> At the end of the one-year mark at ELI, interns will receive an Associate degree in Leadership as well </br>as be apart of a mission trip. Interns will attend a local trip to GUTS Church for a weekend and have a </br>realistic and applicable foundation on Leadership and The Word of God. </br></br><h1>MISSIONS TRIP</h1> First year interns will be able to go on one mission's trip. Mission's trips alternate from out of country </br>and with the USA each year. Interns will also have the opportunity to travel to Tulsa, OK to serve GUTS </br>church for a weekend with the team during each year. </br></br><h1>TUITION</h1> One year at ELI consists of 3 full-time trimesters (12 week segments) </br>Trimester 1 - $2,000.00 </br>Trimester 2 - $2,000.00 </br>Trimester 3 - $2,000.00 </br></br>Cost of one full year: $6,000.00 </br></br><h1>GRADUATION REQUIREMENTS</h1> Complete all 68 credit hours </br>Pay off tuition costs </br>Finish classes with grades above a &quot;C&quot;. </br>Apply for Associates Degree");
});

$(document).on("click",".year2",function(){
$.fancybox.open("<h1>COURSE STRUCTURE</h1> Total Completed Credit hours – 126 </br></br><h1>DURATION</h1> Two Years (Six full trimesters) </br></br><h1>MINISTRY TRAINING</h1> The second year oat Elevate Leadership Institute teaches individuals to put into practice what they have </br>learned about leadership in all areas of life. It is a time to build upon the foundation of what you learned </br>during your first year at ELI. After students discover their passion they are able to deepen their abilities </br>to lead practically in the areas of: </br>Worship </br>Media / Production </br>Administration </br>Student Ministry </br>Kids Ministry </br>Young Adult Ministry </br>Outreach Ministry </br></br><h1>END OF SECOND YEAR</h1> At the end of the second year at ELI, interns will receive a Bachelors degree in Leadership and Biblical </br>Studies as well as complete two missions trips, and attend a two local trips to GUTS Church for a weekend. </br>Students have the privilege of a Graduation ceremony in the summer for friends and family to attend. </br>After two years students gain confidence in what their 1% is, and they are able to leave an imprint on </br>the world that is different than anyone before them or after them. </br></br><h1>MISSIONS TRIP</h1> Second -year interns receive the opportunity to go on a two, 2-week missions trip. These mission's trips </br>alternate from being located within the USA and outside of the USA. </br></br>Interns also have the opportunity to travel to Tulsa, OK to serve &quot;GUTS&quot; church for a weekend with </br>the Intern team in October each year. </br></br><h1>TUITION</h1> One year at ELI consists of 6 trimesters (12 week sections) </br>Trimester 1 - $2,000.00 </br>Trimester 2 - $2,000.00 </br>Trimester 3 - $2,000.00 </br>Trimester 4 - $2,000.00 </br>Trimester 5 - $2,000.00 </br>Trimester 6 - $2,000.00 </br></br>Cost of the two full years at ELI: $12,000.00 </br></br><h1>GRADUATION REQUIREMENTS</h1> Complete all 126 credit hours </br>Pay off all tuition on time </br>Graduate with letter grades above &quot;C&quot; </br>Apply for Bachelors Degree");
});

$(document).on("click",".apply_step_2",function(){
$.fancybox.open("<h1>Request Official Transcripts:</h1>Official transcripts from your high school must be requested. </br>If you attended another college, those transcripts should be requested as well.");
});

$(document).on("click",".apply_step_3",function(){
$.fancybox.open("<h1>Send Transcripts to ELI:</h1>Official transcripts must be sealed in an envelope</br> by the requested school and mailed directly to the ELI offices:</br>Elevate Leadership Institute</br>8500 Teel Pkwy</br>Frisco, TX 75034");
});


$(document).on("click",".apply_step_4",function(){
$.fancybox.open("<h1>Interview Scheduled by ELI:</h1>Once ELI has reviewed all of the necessary items, you will receive an email from intern@elevatelife.com to set up a meeting for an interview.");
});

$(document).on("click",".apply_step_5",function(){
$.fancybox.open("<h1>Attend Orientation:</h1>An email will be sent about further steps and orientation.");
});

</script>

<?php

/******

 * @package WordPress

 * @subpackage RMTheme

 * @since version 1.9.6 

 * @author RM Web Lab

 *****/  

global $rmtheme_options, $rmtopt, $style, $current_slider, $rm_responsive, $google_webfonts, $woocommerce, $post;

$c_pageID = '';

if(isset($post)) {

	$c_pageID = $post->ID;

} 

$rm_responsive = $rmtopt['rmtheme_responsive']; 

?>

<!DOCTYPE html>

<html xmlns="http<?php echo (is_ssl())? 's' : ''; ?>://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head>

<?php

if( isset( $_SERVER['HTTP_USER_AGENT'] ) &&	( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE' ) !== false )) {

echo '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />';

}

?>

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<?php // add 'viewport' meta

if ( $rm_responsive ){

 echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />'; 

}

?>

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!-- For use in JS files -->

<script type="text/javascript">

	var template_dir = "<?php echo get_template_directory_uri(); ?>";

</script>

	<?php $gfont = ''; ?>

	<?php if($rmtopt['g_body_font'] && $rmtopt['g_body_font'] != '0'){ ?>

	<?php $gfont[urlencode($rmtopt['g_body_font'])] = urlencode($rmtopt['g_body_font']) . ':400,400italic,500,500italic,600,600italic,700,700italic:latin,greek-ext,cyrillic,latin-ext,greek,cyrillic-ext,vietnamese'; ?>

	<?php } ?>

	<?php if($rmtopt['g_main_menu_font'] && $rmtopt['g_main_menu_font'] != '0' && $rmtopt['g_main_menu_font'] != $rmtopt['g_body_font']){ ?>

	<?php $gfont[urlencode($rmtopt['g_main_menu_font'])] = urlencode($rmtopt['g_main_menu_font']) . ':400,400italic,500,500italic,600,600italic,700,700italic:latin,greek-ext,cyrillic,latin-ext,greek,cyrillic-ext,vietnamese'; ?>

	<?php } ?> 

	<?php if($rmtopt['g_heading_font'] && $rmtopt['g_heading_font'] != '0' && $rmtopt['g_heading_font'] != $rmtopt['g_main_menu_font'] && $rmtopt['g_heading_font'] != $rmtopt['g_body_font']){ ?>

	<?php $gfont[urlencode($rmtopt['g_heading_font'])] = urlencode($rmtopt['g_heading_font']) . ':400,400italic,500,500italic,600,600italic,700,700italic:latin,greek-ext,cyrillic,latin-ext,greek,cyrillic-ext,vietnamese'; ?>

	<?php } ?>

	<?php if($rmtopt['g_footer_heading_font'] && $rmtopt['g_footer_heading_font'] != '0' && $rmtopt['g_footer_heading_font'] != $rmtopt['g_heading_font'] && $rmtopt['g_footer_heading_font'] != $rmtopt['g_main_menu_font'] && $rmtopt['g_footer_heading_font'] != $rmtopt['g_body_font']){ ?>

	<?php $gfont[urlencode($rmtopt['g_footer_heading_font'])] = urlencode($rmtopt['g_footer_heading_font']) . ':400,400italic,500,500italic,600,600italic,700,700italic:latin,greek-ext,cyrillic,latin-ext,greek,cyrillic-ext,vietnamese'; ?>

	<?php } ?>

<?php 

if($gfont){if(is_array($gfont) && !empty($gfont)) {	

foreach($gfont as $google_font){

echo "<link href='http://fonts.googleapis.com/css?family={$google_font}' rel='stylesheet' type='text/css' media='all' />\n";

}}} 

?>  

<?php 

if(isset($rmtopt['fav_icon']))

$fav_icon = $rmtopt['fav_icon'];

if(isset($fav_icon['url']) && $fav_icon['url']){ ?>

<link rel="shortcut icon" href="<?php echo $fav_icon['url']; ?>" type="image/x-icon" />

<?php } ?>

<?php 

if(isset($rmtopt['fav_icon_apple_iphone']))

$fav_icon_apple_iphone = $rmtopt['fav_icon_apple_iphone'];

if(isset($fav_icon_apple_iphone['url']) && $fav_icon_apple_iphone['url']){ ?>

<!-- For iPhone -->

<link rel="apple-touch-icon-precomposed" href="<?php echo $fav_icon_apple_iphone['url']; ?>">

<?php } ?>

<?php 

if(isset($rmtopt['fav_icon_apple_ipad']))

$fav_icon_apple_ipad = $rmtopt['fav_icon_apple_ipad'];

if(isset($fav_icon_apple_ipad['url']) && $fav_icon_apple_ipad['url']){ ?>

<!-- For iPad -->

<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $fav_icon_apple_ipad['url']; ?>">

<?php } ?>

<?php do_action('rm_seo_settings'); ?>

<?php rm_setup_theme_options_for_scripts(); ?>

<?php if($rmtopt['tracking_code']){ ?>

<!--RMTheme custom head code-->

<script type="text/javascript">

	<?php echo $rmtopt['tracking_code']; ?>

</script>	

<!--RMTheme custom  head code-->

<?php } ?>

<?php if($rmtopt['code_space_head']){ ?>

<!--RMTheme custom head code-->

<script type="text/javascript">

	<?php echo $rmtopt['code_space_head']; ?>

</script>		

<!--RMTheme custom  head code-->

<?php } ?>

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php if($rmtopt['body_background_slider']){ ?>

<div id="body_slider_area" class="body_slider">

<div id="rm_body_background_slider">

    <div class="slides-container">

		<?php echo $rmtopt['body_background_slider_content']; ?>

    </div>

</div>

</div><!--body_slider-->  

<?php } ?>

<?php if($rmtopt['view_full_background']){ ?>

<?php	

    $site_content_switcher = '<a href="javascript:void(0)" id="view_full_background" class="view_background_switcher">View Background</a><a href="javascript:void(0)" id="restore_full_site" class="view_background_switcher" style="display:none;">Restore</a>';

	echo apply_filters( 'rmtheme_view_background', $site_content_switcher );

?>    

    <div id="full_background_content">

<?php } ?>

<?php if(($rmtopt['site_float_menu'] == 'left') || ($rmtopt['site_float_menu'] == 'right') || ($rmtopt['site_float_menu'] == 'left_fixed') || ($rmtopt['site_float_menu'] == 'right_fixed')){ ?>

<div id="float_menu_runner" class="float_menu_single_page_site">

	<div id="float_menu_bar" class="<?php echo $rmtopt['site_float_menu']; ?>">

    	<ul class="float_nav_bar go_to_section">

        	<?php rm_build_nav_menu_one_page_site('float'); ?>

        </ul>    

    </div>

</div>

<?php } ?>

<?php if(($rmtopt['float_social_icons'] == 'left') || ($rmtopt['float_social_icons'] == 'right')){ ?>

<div class="float_social_icons_wrap">

	<div class="float_social_icons <?php echo $rmtopt['float_social_icons']; ?>">

        	<?php 

				if($rmtopt['float_social_icons'] == 'left'){

					display_rmthemes_float_social_icon('right');

				}

				if($rmtopt['float_social_icons'] == 'right'){

					display_rmthemes_float_social_icon('left');

				}			

			?> 

    </div>

</div>

<?php } ?>

<?php do_action('rm_below_body_tag'); ?>

<div id="first_wrapper"><div id="second_wrapper"><div id="third_wrapper">

<?php if(!is_page_template('blank.php')){ ?>

<?php if($rmtopt['top_sliding_bar']){ ?>

<div class="top_sliding_bar_area">

    <div id="top_sliding_bar">

		<div class="wrapper container">		

			<div class="row">    

        		<?php get_template_part('top-sliding-bar'); ?>

			</div><!--row-->

		</div><!--.wrapper container-->        

    </div><!--top_sliding_bar-->    

    <a href="javascript:void(0)" class="top_sliding_bar_toggle "></a>

</div><!--top_sliding_bar_area-->

<?php } ?>

<?php }//eof if blank page ?>

<?php if($rmtopt['site_layout'] == 'Boxed'){ ?>

<div id="box_layout">

<?php }else{ ?>

<div id="wide_layout">

<?php } ?>

<?php do_action('rm_before_header'); ?>

<?php if(!is_page_template('blank.php')){ ?>

<?php if(($rmtopt['site_layout'] == 'header_left_fixed') || ($rmtopt['site_layout'] == 'header_left_scroll')){ ?>

	<div class="leftside_layout_wrapper">

	<div id="layout_header_left_side" class="threecol">

<?php } ?>  

<header id="section_header">

<?php

$header_parallax_markup = '';

if($rmtopt['header_parallax']){

	$header_background_speed = '0.4';

	$header_parallax_markup = ' data-stellar-background-ratio="' . $header_background_speed . '"';	

}

?>	

<div id="header" <?php echo $header_parallax_markup; ?> >



<?php if($rmtopt['rm_welcome_box']){ ?>

<?php if(!is_home() && is_front_page()){ ?>

<div id="rm_welcome_area">

<?php if($rmtopt['rm_background_slider']){ ?>

<div id="rm_background_slider">

    <div class="slides-container">

		<?php echo $rmtopt['rm_background_slider_content']; ?>

    </div>

</div>  

<?php } ?>

<?php if($rmtopt['rm_background_video']){ ?>

	<?php echo $rmtopt['rm_background_video_content']; ?>

<?php } ?>

<div class="welcome_box_bg_image" <?php echo $header_parallax_markup; ?>><div class="welcome_box_bg_color">

<?php 

	if ( is_active_sidebar('rm-welcome-box-widget') ) {

		dynamic_sidebar('rm-welcome-box-widget');

	}			

 ?>

</div></div><!--welcome_box_bg_image-->         

</div><!--rm_welcome_area-->

<?php } ?>

<?php } ?> 

	<?php if($rmtopt['rm_top_section']){ ?>

	<div id="section_top">

		<div class="wrapper container">

			<div class="row">

			<?php display_rmthemes_header_top_section(); ?>	

			</div><!--row-->

		</div><!--.wrapper container-->

	</div><!--#section_top-->

	<?php } ?>	

    <?php do_action('before_header_top_section'); ?>

	<?php if($rmtopt['header_menu_position'] == 4){ ?>

		<!--Necessary to width menu background for design purpose-->

		<div id="section_header_menu_fullwidth" class="main_menu_show_hide">

			<div class="wrapper container">	

				<div class="row">

				<div class="header_main_menu col-xs-12 col-sm-12 col-md-12 <?php if($rmtopt['header_search_box'] == 'menu_right'){ ?> with_search_box <?php } ?>">

                		<?php do_action('rm_before_nav'); ?>																

						<nav class="navbar navbar-default" role="navigation"><ul id="header_main_menu_ul" class="sf-menu nav navbar-nav">	

                        <?php do_action('rm_before_nav_item'); ?>

							<?php 

							if(is_rm_enabled_post_type('page_section') && $rmtopt['single_page_menu_enable']){ 

								rm_build_nav_menu_one_page_site('primary');

							}elseif(has_nav_menu('primary')) {

								if(!$rmtopt['custom_mega_menu_pos']){

									rm_custom_mega_menu(); 

								}

								wp_nav_menu( array('walker' => new RMThemes_Arrow_Walker_Nav_Menu, 'theme_location' => 'primary', 'container' => '', 'items_wrap' => '%3$s' ) );

								if($rmtopt['custom_mega_menu_pos']){

									rm_custom_mega_menu(); 

								}

							}

							else {

								echo '<li><a href="">No menu assigned!</a></li>';

							}

							?>

                            <?php woocommerce_cart_for_main_menu(); ?>

                            <?php if(isset($rmtopt['cart66_cart_icon_primary_menu'])){ get_rmtheme_cart66_cart_icon(); }	?>

                            

						</ul>

						<?php if($rmtopt['header_search_box'] == 'menu_right'){ ?>

							<?php display_rmtheme_search_form_in_main_menu(); ?>			

						<?php	} ?>	

                        	<?php do_action('rm_after_nav_item'); ?>			

						</nav>

                        <?php do_action('rm_after_nav'); ?>			

				</div><!--header_main_menu-->

				</div><!--row-->

			</div><!--.wrapper container-->

		</div><!--#section_main_menu-->

	<?php } ?>	

	<div id="header_top_section">

		<div class="wrapper container">		

			<div class="row">

				<?php

					/*@can be override from child theme.*/ 

					display_rmthemes_header_section(); 

				?>

			</div><!--row-->	

			<?php if($rmtopt['header_menu_position'] == 2){ ?>

			<!--Necessary to width menu background for design purpose-->

			<div class="row">

			<div id="header_inner_main_menu" class="col-xs-12 col-sm-12 col-md-12 main_menu_show_hide">

					<div class="header_main_menu">

                    <?php do_action('rm_before_nav'); ?>

						<nav class="navbar navbar-default" role="navigation"><ul id="header_inner_main_menu_ul" class="sf-menu nav navbar-nav">

							<?php 

							if(is_rm_enabled_post_type('page_section') && $rmtopt['single_page_menu_enable']){ 

								rm_build_nav_menu_one_page_site('primary');

							}elseif(has_nav_menu('primary')) {

								if(!$rmtopt['custom_mega_menu_pos']){

									rm_custom_mega_menu(); 

								}

								wp_nav_menu( array('walker' => new RMThemes_Arrow_Walker_Nav_Menu, 'theme_location' => 'primary', 'container' => '', 'items_wrap' => '%3$s' ) );

								if($rmtopt['custom_mega_menu_pos']){

									rm_custom_mega_menu(); 

								}

							}

							else {

								echo '<li><a href="">No menu assigned!</a></li>';

							}

							?>                            

                            <?php woocommerce_cart_for_main_menu(); ?>

                            

                            <?php if(isset($rmtopt['cart66_cart_icon_primary_menu'])){ get_rmtheme_cart66_cart_icon(); }	?>

                            

						</ul>

							<?php if($rmtopt['header_search_box'] == 'menu_right'){ ?>

								<?php display_rmtheme_search_form_in_main_menu(); ?>			

							<?php	} ?>										

						</nav>

                        <?php do_action('rm_after_nav'); ?>				

					</div><!--header_main_menu-->			

			</div><!--#header_inner_main_menu-->

			</div><!--row-->

			<?php } ?>

		</div><!--.wrapper container-->

		</div><!--#header_top_section-->

        <?php do_action('after_header_top_section'); ?>		

		<?php if($rmtopt['header_menu_position'] == 1){ ?>

		<!--Necessary to width menu background for design purpose-->

		<div id="section_header_menu_fullwidth" class="main_menu_show_hide">

			<div class="wrapper container">	

				<div class="row">

				<div class="header_main_menu col-xs-12 col-sm-12 col-md-12 <?php if($rmtopt['header_search_box'] == 'menu_right'){ ?> with_search_box <?php } ?>">

                		<?php do_action('rm_before_nav'); ?>																

						<nav class="navbar navbar-default" role="navigation"><ul id="header_main_menu_ul" class="sf-menu nav navbar-nav">	

                        <?php do_action('rm_before_nav_item'); ?>

							<?php 

							if(is_rm_enabled_post_type('page_section') && $rmtopt['single_page_menu_enable']){ 

								rm_build_nav_menu_one_page_site('primary');

							}elseif(has_nav_menu('primary')) {

								if(!$rmtopt['custom_mega_menu_pos']){

									rm_custom_mega_menu(); 

								}

								wp_nav_menu( array('walker' => new RMThemes_Arrow_Walker_Nav_Menu, 'theme_location' => 'primary', 'container' => '', 'items_wrap' => '%3$s' ) );

								if($rmtopt['custom_mega_menu_pos']){

									rm_custom_mega_menu(); 

								}

							}

							else {

								echo '<li><a href="">No menu assigned!</a></li>';

							}

							?>

                            <?php woocommerce_cart_for_main_menu(); ?>

                            <?php if(isset($rmtopt['cart66_cart_icon_primary_menu'])){ get_rmtheme_cart66_cart_icon(); }	?>

                            

						</ul>

						<?php if($rmtopt['header_search_box'] == 'menu_right'){ ?>

							<?php display_rmtheme_search_form_in_main_menu(); ?>			

						<?php } ?>	

                        	<?php do_action('rm_after_nav_item'); ?>			

						</nav>

                        <?php do_action('rm_after_nav'); ?>			

				</div><!--header_main_menu-->

				</div><!--row-->

			</div><!--.wrapper container-->

		</div><!--#section_main_menu-->

		<?php } ?>

		<?php if($rmtopt['site_sticky_menu']){ ?>

 		<!--Necessary to width menu background for design purpose-->

		<div id="section_header_sticky_fullwidth"  class="sticky_menu">

			<div class="wrapper container">	

				<div class="row">

				<div class="header_main_menu col-xs-12 col-sm-12 col-md-12 <?php if($rmtopt['header_search_box'] == 'menu_right'){ ?> with_search_box <?php } ?>">

                <?php do_action('rm_before_sticky_nav'); ?>

				<?php if($rmtopt['sticky_menu_logo']['url']){ ?>

                <a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img class="site_logo" src="<?php echo $rmtopt['sticky_menu_logo']['url']; ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" /></a><?php } ?>					

						<nav class="navbar navbar-default" role="navigation"><ul id="header_sticky_main_menu_ul" class="sf-menu nav navbar-nav">	

							<?php 

							if(is_rm_enabled_post_type('page_section') && $rmtopt['single_page_menu_enable']){ 

								rm_build_nav_menu_one_page_site('sticky_menu');

							}elseif(has_nav_menu('sticky_menu')) {

								if(!$rmtopt['custom_mega_menu_pos']){

									rm_custom_mega_menu(); 

								} 

								wp_nav_menu( array('walker' => new RMThemes_Arrow_Walker_Nav_Menu, 'theme_location' => 'sticky_menu', 'container' => '', 'items_wrap' => '%3$s' ) );

								if($rmtopt['custom_mega_menu_pos']){

									rm_custom_mega_menu(); 

								} 

							}

							else {

								echo '<li><a href="">No menu assigned!</a></li>';

							}

							?>

                            <?php woocommerce_cart_for_main_menu(); ?>                            

						</ul>			

						</nav>

                    <?php do_action('rm_after_sticky_nav'); ?>    			

				</div><!--header_main_menu-->				

				</div><!--row-->

			</div><!--.wrapper container-->

		</div><!--#section_header_sticky_fullwidth-->

		<?php } ?>

        <div id="mobile_menu_wrapper">	

            <div class="container">

                <a id="mobile-menu-toggle" href="#"><span>Navigation</span><i class="fa fa-list"></i>&nbsp;</a>

                <div id="mobile-menu">

                    <ul class="mobile_nav_bar">

                        <?php 

                        if(is_rm_enabled_post_type('page_section') && $rmtopt['single_page_menu_enable']){ 

                            rm_build_nav_menu_one_page_site('mobile');

                        }elseif(has_nav_menu('primary')) {

                            if(!$rmtopt['custom_mega_menu_pos']){

                                rm_custom_mega_menu(); 

                            }					 

                            wp_nav_menu( array('walker' => new RMThemes_Arrow_Walker_Mobile_Nav_Menu, 'theme_location' => 'primary', 'container' => '', 'items_wrap' => '%3$s' ) );

                            if($rmtopt['custom_mega_menu_pos']){

                                rm_custom_mega_menu(); 

                            }					 

                        }

                        else {

                            echo '<li><a href="">No menu assigned!</a></li>';

                        }

                        ?>                                        							

                    </ul>						

                    <?php if($rmtopt['header_search_box'] == 'menu_right'){ ?>

                        <?php display_rmthemes_header_others_section_search_box(); ?>			

                    <?php	} ?>						

                </div><!--#mobile-menu-->

            </div>	

        </div><!--#mobile_menu_wrapper-->        

	</div><!--#header-->    

	<?php if($rmtopt['header_bottom_section_text']){ ?>

	<div id="section_header_bottom_section_text">

		<div class="wrapper container">

			<div class="row"><div class="col-md-12 col-xs-12 col-sm-12">

			<?php echo do_shortcode($rmtopt['header_bottom_section_text']); ?>	

			</div></div><!--row-->

		</div><!--.wrapper container-->

	</div><!--#section_top-->

	<?php } ?>	

</header><!--#section_header-->

<?php if($rmtopt['header_sticky']){ ?><div id="header_sticky_space">&nbsp;</div><!--header_sticky_space--><?php } ?>

<?php if(($rmtopt['site_layout'] == 'header_left_fixed') || ($rmtopt['site_layout'] == 'header_left_scroll')){ ?>

	</div><!--layout_header_left_side-->

	<div id="layout_content_right_side" class="ninecol last">

<?php } ?> 

<?php do_action('rm_after_header'); ?>

<?php if($rmtopt['header_shadow'] != 'no'){ ?>    

<div class="header_shadow"><span class="shadow1"></span></div>    

<?php } ?>

<?php do_action('rm_before_title_bar'); ?>

<?php

if((get_post_meta($c_pageID, '_rmt_page_title', true) == 'no') || !$rmtopt['default_page_title_bar']) {

	//override from individual pages

	if((get_post_meta($c_pageID, '_rmt_page_title', true) == 'yes') && is_singular() && !$rmtopt['default_page_title_bar'] && (!is_home() && !is_front_page())) {

		get_template_part( 'section', 'title' );

	}

	//No need title bar

}elseif($rmtopt['default_page_title_bar']){

	get_template_part( 'section', 'title' );

} 

?>

<?php do_action('rm_after_title_bar'); ?>

<?php if($rmtopt['breadcrumb_position'] == 4){ ?>





















<div class="wrapper container">

    <div class="row">

        <div id="breadcrumb_wrapper" class="breadcrumb_below_title_bar col-xs-12 col-md-12 col-sm-12">

            <?php

			if(class_exists('Woocommerce')){

				 if($woocommerce->version && is_woocommerce() && ((is_product() && get_post_meta($c_pageID, '_rmt_page_title', true) != 'no') || (is_shop() && get_post_meta($c_pageID, '_rmt_page_title', true) != 'no')) && !is_search()) {

					 woocommerce_breadcrumb(array(

                        'wrap_before' => '<ul class="breadcrumbs">',

                        'wrap_after' => '</ul>',

                        'before' => '<li>',

                        'after' => '</li>',

                        'delimiter' => ''

                    )); 

				 }else{

					 rmtheme_breadcrumb();

				}

			}else{

				rmtheme_breadcrumb();

			} 

			?>

        </div><!--#breadcrumb_wrapper-->

    </div><!--row-->

</div><!--.wrapper container-->

<?php } ?>

<?php

//Individual page slider

if(is_search()) {

	$slider_page_id = '';

}

if(!is_search()){	

	$slider_page_id = '';

	if(!is_home() && !is_front_page() && !is_archive() && isset($post)) {

		$slider_page_id = $c_pageID;

	}

	if(!is_home() && is_front_page() && isset($post)) {

		$slider_page_id = $c_pageID;

	}

	if(is_home() && !is_front_page()){

		$slider_page_id = get_option('page_for_posts');

	}

	if(class_exists('Woocommerce')) {

		if(is_shop()) {

			$slider_page_id = get_option('woocommerce_shop_page_id');

		}

	}

	?>

		<?php

		if(get_post_meta($slider_page_id, '_rmt_slider_type', true) == 'rev' && get_post_meta($slider_page_id, '_rmt_revslider', true) && function_exists('putRevSlider')) {

			echo '<div id="section_page_slider">';

			putRevSlider(get_post_meta($slider_page_id, '_rmt_revslider', true));

			echo '</div>';

		}

		?>

<?php } ?>

<?php }//eof if blank page ?>

<?php do_action('rm_before_main'); ?>

<div id="section_main">

<div id="page">

<?php 

$class_fullwidth_content_wrap = '';

$parallax_markup = '';

$page_template = '';

if(get_post_meta($c_pageID, '_rmt_page_bg_parallax', true) == 'yes'){

	$background_speed = '0.4';

	$parallax_markup = ' data-stellar-background-ratio="' . $background_speed . '"';

}

$parallax_markup_page_title_bar = '';

if(is_404()){

	$page_template = '';

}elseif($c_pageID){

	$custom_fields = get_post_custom_values('_wp_page_template', $c_pageID);

	if(is_array($custom_fields) && !empty($custom_fields)) {

		$page_template = $custom_fields[0];

	} else {

		$page_template = '';

	}

}

?>

<div id="fullwidth_content_wrap" <?php echo $parallax_markup; ?> class="<?php echo $class_fullwidth_content_wrap; ?>">

<?php if( $page_template == 'page-single-page-site.php' || $page_template == 'page-advanced-home.php') { ?>

<?php }else{ ?>

    <div class="default_page_space">    

    <?php if(get_post_meta($c_pageID, '_rmt_page_content_full_width', true) == 'no' || !get_post_meta($c_pageID, '_rmt_page_content_full_width', true)){ ?>

            <div class="wrapper container">

                <div class="row">

    <?php } ?>

<?php } ?>

<?php

wp_reset_query();

if(class_exists('Woocommerce') && is_singular('product')){

	echo slider_with_thumb_dynamic_script($carousel_id = 'carousel', $slider_id = 'slider', $thumb_item_width = '110', $thumb_item_margin = '10', $easing = '');

}

if(if_cart66() && is_singular('rm_cart66')){

	echo slider_with_thumb_dynamic_script($carousel_id = 'carousel', $slider_id = 'slider', $thumb_item_width = '110', $thumb_item_margin = '10', $easing = '');

}

?>









<style>

.year1{

    padding: 12px 12px;

    font-size: 14px;

    background-color: #3c4b59;

    border-color: #3c4b59;

	color: white;

}



.year2{

    padding: 12px 12px;

    font-size: 14px;

    background-color: #3c4b59;

    border-color: #3c4b59;

	color: white;

}

</style>