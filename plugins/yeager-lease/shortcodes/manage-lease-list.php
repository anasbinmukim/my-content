<?php
// Specific user data list
add_shortcode('manage-lease-list', 'yl_manage_lease_list');
function yl_manage_lease_list($content = null) {

    ob_start();

    if ( is_user_logged_in() ):

        global $current_user;
        get_currentuserinfo();

        $args = array(
            'post_type'  => 'lease',
        	'posts_per_page' => -1,
        	'post_status' => 'any',
        	'author' => $current_user->ID
        );

        $query = new WP_Query($args);
        $count = 0;
        while($query->have_posts()): $query->the_post();
            $count++;
            ?>

            <h2><a href="<?php echo get_permalink(get_option('yl_bm_summary_sign_page')); ?>?lid=<?php echo get_the_ID(); ?>"><?php echo esc_html(get_the_title()); ?></a></h2>

            <?php
			
    	endwhile; 

    else:
    	echo "User Not Logged In";
		wp_login_form();

    endif;

    $content = ob_get_clean();
    return $content;
}
