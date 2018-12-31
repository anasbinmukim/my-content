<?php
function yl_available_suites_search_shortcodes($atts, $content = null) {
	extract(shortcode_atts(array(
		'search' => 'yes',
	), $atts));
	
	global $us_states_full;
	
	if(!current_user_can( 'building_manager' )){
		echo "Only Building Manager are able to create lease. Please login to search available suites.";
		return;
	}
	
	ob_start();
?>
<form action="" method="get">
	<div id="lease_step_1">
		<p><label for="MoveinDate">Move in Date</label> <input type="text" name="MoveinDate" id="MoveinDate" style="max-width: 125px;" value="<?php if( isset($_GET['MoveinDate']) ) echo $_GET['MoveinDate']; ?>" /></p>
		<p id="suiteType"><label for="type">Type</label>
			<?php wp_dropdown_categories( 'name=suites_type&taxonomy=product_cat&show_count=0&hierarchical=1' ); ?>
		
		</p>
		<p><button id="searchSuites" name="searchSuites">Go</button></p>
	</div>
</form>

	<?php		
	
	$yl_search_form = ob_get_contents();
	ob_end_clean();	
	return $yl_search_form;	
	
}
add_shortcode('available-suites-search-form','yl_available_suites_search_shortcodes');