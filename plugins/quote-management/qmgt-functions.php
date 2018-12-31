<?php
add_filter( 'manage_edit-quote_columns', 'custom_quote_columns' ) ;
function custom_quote_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'quote_author' => __( 'Author' ),
		'quote_tag' => __( 'Tag' ),
		'book_credit' => __( 'Book Credit' ),
		'full_quote' => __( 'Full Quote' ),
		'thought_behind_quote' => __( 'Thought Behind Quote' ),
		'last_ran_date' => __( 'Last Ran Date' ),
		'available_for_pick' => __( 'Email Status' ),
		'date' => __( 'Date' )
	);

	return $columns;
}

add_action( 'manage_quote_posts_custom_column' , 'quote_custom_columns_content', 10, 2 );
function quote_custom_columns_content( $column, $post_id ) {
    switch ( $column ) {
		case 'quote_author' :
			$terms = get_the_term_list( $post_id , 'quoteauthor' , '' , ',' , '' );
			if ( is_string( $terms ) )
				echo $terms;
			else
				_e( '__', 'QMgt' );
			break;

		case 'quote_tag' :
			$terms = get_the_term_list( $post_id , 'quotetag' , '' , ',' , '' );
			if ( is_string( $terms ) )
				echo $terms;
			else
				_e( '__', 'QMgt' );
			break;

		case 'book_credit' :
			$terms = get_the_term_list( $post_id , 'quotebookcredits' , '' , ',' , '' );
			if ( is_string( $terms ) )
				echo $terms;
			else
				_e( '__', 'QMgt' );
			break;

		case 'full_quote' :
			echo apply_filters('the_content', get_post_field('post_content', $post_id));
			break;

		case 'thought_behind_quote' :
			echo get_post_meta( $post_id , '_cmb_thought_behind_quote' , true );
			break;

		case 'last_ran_date' :
			if(get_post_meta($post_id, '_cmb_last_ran_date', true)){
				$last_ran_date = get_post_meta($post_id, '_cmb_last_ran_date', true);
				echo $last_ran_date;
			}
			break;

		case 'available_for_pick' :
			if( get_post_meta($post_id, '_rmt_quote_email', true) == 'yes' ) {
				$send_status = 'Already Sent';
			}else{
				$send_status = 'Ready to email generate.';
			}
			echo $send_status;
			break;

    }
}

add_filter( 'manage_edit-quote_sortable_columns', 'quote_last_ran_sortable_columns' );
function quote_last_ran_sortable_columns( $columns ) {
	$columns['last_ran_date'] = 'last_ran_date';
	return $columns;
}

/* Only run our customization on the 'edit.php' page in the admin. */
add_action( 'load-edit.php', 'qmgt_edit_quote_load' );

function qmgt_edit_quote_load() {
	add_filter( 'request', 'qmgt_sort_quotes' );
}

/* Sorts the quotes. */
function qmgt_sort_quotes( $vars ) {

	/* Check if we're viewing the 'quote' post type. */
	if ( isset( $vars['post_type'] ) && 'quote' == $vars['post_type'] ) {

		/* Check if 'orderby' is set to 'duration'. */
		if ( isset( $vars['orderby'] ) && 'last_ran_date' == $vars['orderby'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => '_cmb_last_ran_date',
					'meta_type' => 'DATETIME',
					'orderby' => 'meta_value'
				)
			);
		}
	}

	return $vars;
}


add_filter( 'manage_edit-emails_columns', 'custom_quote_email_columns' ) ;
function custom_quote_email_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'quote_title' => __( 'Quote Title' ),
		'mc_status' => __( 'MC Status' ),
		'date' => __( 'Date' )
	);

	return $columns;
}


add_action( 'manage_emails_posts_custom_column' , 'quote_email_custom_columns_content', 10, 2 );
function quote_email_custom_columns_content( $column, $post_id ) {
    switch ( $column ) {

		case 'quote_title' :
			if( get_post_meta($post_id, '_rmt_quote_title', true)) {
				$rmt_quote_title = get_post_meta($post_id, '_rmt_quote_title', true);
				echo $rmt_quote_title;
			}
			break;
		case 'mc_status' :
			if( get_post_meta($post_id, '_rmt_quote_id', true)) {
				$generated_quote_id = get_post_meta($post_id, '_rmt_quote_id', true);
				$campaign_id = get_post_meta($generated_quote_id, '_rmt_campaign_id', true);
				if($campaign_id != ''){
					echo check_mc_campaign_status($campaign_id);
				}
			}
			break;

    }
}



//No need anymore, already created..
function add_user_role_leadership(){
	if(isset($_GET['addrole']) && ($_GET['addrole'] == 'do')){
		add_role( 'quote_manager', 'Quote Manager', array( 'read' => true, 'level_2' => true ) );

//		remove_role( 'tow_company' );
	}
}
//add_action( 'init', 'add_user_role_leadership' );



function get_random_quote($content = null) {
	//remove_all_filters('posts_orderby');
	$args = array( 'post_type' => 'quote', 'posts_per_page' => 1, 'post_status' => 'publish', 'orderby' => 'rand' );

	$quote_posts = new WP_Query( $args );
	ob_start();
	if($quote_posts->have_posts()) {
		global $post;
		//$posts_list = array();
		while($quote_posts->have_posts()): $quote_posts->the_post();
			//$posts_list[] = $post->ID;
			?>
			<div class="quote_of_the_day">
				<p><?php echo get_the_excerpt(); ?></p>
				<a href="<?php echo get_permalink(); ?>" class="more_link">Read More</a>
			</div>
			<?php

		endwhile;
		wp_reset_postdata();
	}


	$content = ob_get_clean();
	return $content;
}
add_shortcode('quote_of_the_day', 'get_random_quote');




add_action('admin_menu' , 'quote_settings_page');
function quote_settings_page() {
	add_submenu_page( 'edit.php?post_type=quote', 'Quote Settings', 'Settings', 'manage_options', 'quote-settings', 'quote_settings');
}

function quote_settings() {
	if(isset($_POST['options_submit'])){

		update_option( 'topics_page_id', $_POST['topics_page_id'] );
		update_option( 'quotes_page_id', $_POST['quotes_page_id'] );
		update_option( 'quote_email_from_name', $_POST['quote_email_from_name'] );
		update_option( 'quote_email_from_email', $_POST['quote_email_from_email'] );
		update_option( 'quote_photo_url', $_POST['quote_photo_url'] );

		echo "<div class='updated'><p>Successfully Updated</p></div>";
	}
?>
<div class="wrap">

	<?php
	// if(isset($_GET['quote_test'])){
	// 		$args_reset = array(
	// 			'date_query' => array(
	// 				array(
	// 					'after'     => 'January 1st, 2016',
	// 					'before'    => array(
	// 						'year'  => 2016,
	// 						'month' => 12,
	// 						'day'   => 31,
	// 					),
	// 					'inclusive' => true,
	// 				),
	// 			),
	// 			'post_type' => 'quote',
	// 			'posts_per_page' => -1,
	// 		);
	//
	//
	// 		$the_query_reset = new WP_Query( $args_reset );
	//
	//
	// 		if ( $the_query_reset->have_posts() ) {
	// 			$count = 1;
	// 			echo '<ul>';
	// 			while ( $the_query_reset->have_posts() ) {
	// 				$the_query_reset->the_post();
	// 				echo '<li>';
	// 				$quote_id = get_the_ID();
	//
	// 				echo '==' . $count . ': ';
	// 				echo get_the_title();
	//
	// 				echo ' Date: '. get_the_date('Y-m-d');
	// 				//delete_post_meta( $quote_id, '_rmt_quote_email', 'yes' );
	// 				echo '</li>';
	// 				$count++;
	// 			}
	// 			echo '</ul>';
	// 			wp_reset_postdata();
	// 		} else {
	// 		}
	// 	}
	?>

	<h2 style="padding-bottom: 25px;"><?php echo __('Quote Settings'); ?></h2>
	<form name="quote_settings" method="post" action="">
		<?php wp_nonce_field('update-options'); ?>

		<table class="form-table" style="margin-top:0;">

			<tr valign="top">
				<th scope="row" style="padding-top:0;"><?php echo __('Email From Name'); ?></th>
				<td style="padding-top:0;">
					<input type="text" name="quote_email_from_name" value="<?php echo esc_attr(get_option('quote_email_from_name')); ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" style="padding-top:0;"><?php echo __('Email From Email'); ?></th>
				<td style="padding-top:0;">
					<input type="text" name="quote_email_from_email" value="<?php echo esc_attr(get_option('quote_email_from_email')); ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" style="padding-top:0;"><?php echo __('Topics page (id)'); ?></th>
				<td style="padding-top:0;">
					<input type="text" name="topics_page_id" value="<?php echo get_option('topics_page_id'); ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php echo __('Quotes page (id)'); ?></th>
				<td>
					<input type="text" name="quotes_page_id" value="<?php echo get_option('quotes_page_id'); ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php echo __('Quote photo URL'); ?></th>
				<td>
						<?php if(get_option('quote_photo_url') != ''){ ?>
							<?php echo '<img src="'.get_option('quote_photo_url').'" alt="" />'; ?>
						<?php }else{ ?>
							<?php echo '<img src="'.EMAIL_FOLDER_URL.'images/200x160_PK.jpg" alt="" />'; ?>
						<?php } ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"></th>
				<td>
					<input type="text" class="large-text" name="quote_photo_url" value="<?php echo get_option('quote_photo_url'); ?>" />
					<br /><small>Please keep photo size 200X160 for better email rendering.
					<br />Note: Updated photo will only apply to newly generated email. If you want to apply it all schedueld campaign, you need to update all scheduled emails.</small>
				</td>
			</tr>
		</table>

		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="topics_page_id,quotes_page_id" />

		<p class="submit">
			<input type="submit" name="options_submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>


	<?php include('qmgt-quote-preview.php'); ?>

</div>

<?php
}

add_action( 'after_setup_theme', 'register_topic_menu' );
function register_topic_menu() {
	register_nav_menu( 'topic_menu', 'Popular Topics' );
}

function get_favorite_quotes($atts, $content = null) {
	global $post;
	extract(shortcode_atts(array(
		'quote_ids' => ''
	), $atts));

	$quote_id_list = explode(",", $quote_ids);
	$args = array(
				'post_type' => 'quote',
				'post_status' => 'publish',
				'post__in' => $quote_id_list,
				'order' => 'ASC',
				'orderby' => 'menu_order',
				'posts_per_page' => 3
			);
	$quoteArgs = new WP_Query( $args );

	ob_start();
	if($quoteArgs->have_posts()) {
		echo '<ul class="favorite_quotes_list">';
		while ( $quoteArgs->have_posts() ) : $quoteArgs->the_post();
	?>
			<li>
				<p><?php echo get_the_excerpt(); ?></p>
				<div class="quote_author"><?php echo get_the_term_list( $post->ID, 'quoteauthor', '', '' ); ?></div>
				<div class="quote_tags">
					<?php echo get_the_term_list( $post->ID, 'quotetag', 'Tags: ', ' ' ); ?>
				</div>
			</li>
	<?php
		endwhile;
		wp_reset_postdata();
		echo '</ul>';
	}
	$content = ob_get_clean();
	return $content;
}
add_shortcode('favorite_quotes', 'get_favorite_quotes');


function get_all_topics( $content = null ) {
	$terms = get_terms( 'quotetag' );
	ob_start();
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		echo '<ul class="popular_topic_list clearfix">';
		foreach ( $terms as $term ) {
			echo '<li><a href="' . get_term_link( $term ) . '">' . $term->name . '</a></li>';
		}
		echo '</ul>';
	}
	$content = ob_get_clean();
	return $content;
}
add_shortcode('topics_list', 'get_all_topics');

function get_quote_search_form() {
	ob_start();
	?>
	<form class="sidebar_quote-search-form" id="sidebar_search_quotes" action="<?php echo get_permalink( get_option('quotes_page_id') ); ?>" method="get">
		<div class="form-group">
			<input type="text" name="quote_title" id="quote_title" value="<?php echo $quote_title; ?>" class="form-control" placeholder="Keyword... " />
		</div>
		<input type="submit" name="quote_search_submit" id="quote_search_submit" class="btn btn-default" value="Search Quotes">
	</form>
	<?php
	$content = ob_get_clean();
	return $content;
}
add_shortcode('quote_search_form', 'get_quote_search_form');

add_filter('wp_mail_from', 'quote_email_mail_from');
function quote_email_mail_from($original_email_address) {
    return get_option('quote_email_from_email');
}

add_filter('wp_mail_from_name', 'quote_email_mail_from_name');
function quote_email_mail_from_name($original_email_from) {
    return get_option('quote_email_from_name');
}

if(!function_exists('qmgt_pagination')){
	function qmgt_pagination($pages = '', $range = 2) {
		$output = '';
		 $showitems = ($range * 2)+1;
		 global $paged;
		 if(empty($paged)) $paged = 1;
		 if($pages == '') {
			 global $wp_query;
			 $pages = $wp_query->max_num_pages;
			 if(!$pages) {
				 $pages = 1;
			 }
		 }
		 if(1 != $pages) {
			 $output .= "<div class='pagination loop-pagination clearfix'>";
			 if($paged > 1) $output .= "<a class='prev page-numbers' href='".get_pagenum_link($paged - 1)."'><span class='page-prev'></span>".__('Previous', 'RMTheme')."</a>";

			 for ($i=1; $i <= $pages; $i++) {
				 if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
				 {
					 $output .= ($paged == $i)? "<span class='page-numbers current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
				 }
			 }
			 if ($paged < $pages) $output .= "<a class='next page-numbers' href='".get_pagenum_link($paged + 1)."'>".__('Next', 'RMTheme')."<span class='page-next'></span></a>";
			 $output .= "</div>\n";
		 }

		 return $output;
	}
}

function qmgt_is_taxonomy_template( $template_path ) {

    //Get template name
    $template = basename($template_path);

    //Check if template is taxonomy-quoteauthor.php
    //Check if template is taxonomy-quoteauthor-{term-slug}.php
    //Check if template is taxonomy-quotetag.php
    //Check if template is taxonomy-quotetag-{term-slug}.php
    if( (1 == preg_match('/^taxonomy-quoteauthor((-(\S*))?).php/',$template)) || (1 == preg_match('/^taxonomy-quotetag((-(\S*))?).php/',$template)) ) {
         return true;
	}

    return false;
}
