<?php

// Specific user data list
add_shortcode('my-lease-list', 'yl_lease_list');
function yl_lease_list($content = null) {

ob_start();

if ( is_user_logged_in() ):

    global $current_user;
    get_currentuserinfo();

    $args = array(
    'post_type'  => 'lease',
    'meta_query' => array(
        array(
            'key'     => '_yl_lease_user',
            'value'   => $current_user->ID,
            'compare' => 'LIKE',
        ),

    ),
);
$query = new WP_Query( $args );


    $query = new WP_Query($args);
    $count = 0;
    while($query->have_posts()): $query->the_post();
    $count++;
    ?>

    <h2><a href="<?php echo get_permalink(get_option('yl_client_sign_page')); ?>?lid=<?php echo get_the_ID(); ?>"><?php echo esc_html(get_the_title()); ?></a></h2>


    <?php
		endwhile; 
		else:
			echo "User Not Logged In";
			wp_login_form();
endif;


$content = ob_get_clean();
return $content;

}

?>