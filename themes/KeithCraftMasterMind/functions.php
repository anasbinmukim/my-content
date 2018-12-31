<?php
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles');
function enqueue_child_theme_styles() {
  wp_enqueue_style( 'Parents_theme_style', get_template_directory_uri().'/style.css' );
}

function mastermind_get_current_user_role() {
    if( is_user_logged_in() ) {
      $user = wp_get_current_user();
      $role = ( array ) $user->roles;
      return $role[0];
    } else {
      return false;
    }
 }


register_nav_menus( array(
	'non_logged_menu' => 'Non Logged Menu',
	'participant_menu' => 'Participant Menu',
  'admin_menu' => 'Admin Menu',
) );



function mastermind_dynamic_style() {
    ?>
    <style type="text/css">
    <?php if(bp_current_component()){ ?>
    /*** Take out the divider line between content and sidebar from all place except single lesson page ***/
    #main-content .container:before {background: none;}
    /*** Hide Sidebar ***/
    #sidebar {display:none;}

    /*** Expand the content area to fullwidth ***/
    @media (min-width: 981px){
      #left-area {
          width: 100%;
          padding: 23px 0px 0px !important;
          float: none !important;
      }
    }
    <?php } ?>
    <?php if(!is_user_logged_in()){ ?>
      #et_top_search{ display: none; }
    <?php } ?>
    <?php if(is_cart()){ ?>
        .woocommerce-message,
        th.product-remove,
        th.product-thumbnail,
        th.product-quantity,
        td.product-remove,
        td.product-thumbnail,
        td.product-quantity,
        tr.cart-subtotal,
        tr.recurring-totals,
        tr.cart-subtotal.recurring-total{ display: none; }
    <?php } ?>
    .single-item.groups #item-header-avatar{ display: none; }
    .et-cart-info { display:none; }
    </style>
    <?php
}
add_action('wp_head', 'mastermind_dynamic_style');


add_action("template_redirect", 'mastermind_group_redirect_for_user_role');
function mastermind_group_redirect_for_user_role(){
    //Redirect to class page if student have only one class
    if((is_page('groups') || is_page('members')) && (mastermind_get_current_user_role() == 'bbp_participant')){
        $user_id = get_current_user_id();
        $group_ids = groups_get_user_groups($user_id);
      	$visible_class_ids = array();
      	foreach($group_ids["groups"] as $group_id) {
          if($group_id > 0){
      		    $visible_group_ids[] = $group_id;
          }
      	}
        if(count($visible_group_ids) != 1){
          $my_account_url = home_url() . '/my-account/?msg=nogroup';
          wp_safe_redirect( $my_account_url );
          exit;
        }
        //print_r($visible_class_ids);
        if( (isset($visible_group_ids)) && (count($visible_group_ids) == 1) && is_page('groups') ){
          $group_id = $visible_group_ids[0];
          $group_url = home_url() . '/groups/' . groups_get_group(array( 'group_id' => $group_id )) -> slug;
          wp_safe_redirect( $group_url );
          exit;
        }

        if( (isset($visible_group_ids)) && (count($visible_group_ids) == 1) && is_page('members') ){
          $group_id = $visible_group_ids[0];
          $group_url = home_url() . '/groups/' . groups_get_group(array( 'group_id' => $group_id )) -> slug . '/members/';
          wp_safe_redirect( $group_url );
          exit;
        }
    }
}

//add_filter( 'bp_is_profile_cover_image_active', '__return_false' );
add_filter( 'bp_is_groups_cover_image_active', '__return_false' );


function mastermind_login_redirect( $redirect_to, $request, $user ) {
  //is there a user to check?
  if ( isset( $user->roles ) && is_array( $user->roles ) ) {
    //check for admins
    if ( in_array( 'administrator', $user->roles ) ) {
      $redirect_to = '/groups/';
      return $redirect_to;
    } elseif ( in_array( 'bbp_participant', $user->roles ) ) {
      $redirect_to = '/groups/';
      return $redirect_to;
    }
  } else {
    return $redirect_to;
  }
}
add_filter( 'login_redirect', 'mastermind_login_redirect', 10, 3 );


function mastermind_sense_add_group_section_bulk_update()
{
    $selected_group = 0;
    // Dropdown options
    $options = '';

    $bp_groups = BP_Groups_Group::get(array(
    							'type'=>'alphabetical',
                  'show_hidden' => true,
    							'per_page'=>999
    							));

    foreach ($bp_groups['groups'] as $bp_group) {
        $group_id = $bp_group->id;
        $group_name = $bp_group->name;
        $options .= sprintf(
            '<option value="%1$d" %2$s>%3$s</option>',
            $group_id, selected( $group_id, $selected_group, 0 ), $group_name
        );
    }

    // Display dropdown with a different name for each instance
    printf(
        '<select id="%s" name="%s" style="float:none;"><option value="0">%s</option>%s</select>',
        'add_user_to_group_bulk', 'group_bulk_select',
        __( 'Select Group' ),
        $options
    );

    // Button
    printf (
        '<input id="add-to-group-submit" type="submit" class="button" value="%s" name="add_to_group">',
        __( 'Add to Group' )
    );
}
add_action( 'restrict_manage_users', 'mastermind_sense_add_group_section_bulk_update' );


add_action('load-users.php',function() {
if(isset($_GET['action']) && isset($_GET['bp_gid']) && isset($_GET['users'])) {
    $group_id = $_GET['bp_gid'];
    $users = $_GET['users'];
    foreach ($users as $user_id) {
        groups_join_group( $group_id, $user_id );
    }
}
    //Add some Javascript to handle the form submission
    add_action('admin_footer',function(){ ?>
    <script>
        jQuery("#add-to-group-submit").click(function(e){
            if(jQuery("select[name='group_bulk_select'] :selected").val() != 0) { e.preventDefault();
                var selected_grup_id = jQuery("#add_user_to_group_bulk option:selected").val();
                jQuery(".wrap form").append('<input type="hidden" name="bp_gid" value="'+selected_grup_id+'" />').submit();
            }
        });
    </script>
    <?php
    });
});



function mastermind_new_modify_user_admin_table( $column ) {
    $column['groups'] = 'Groups';
    return $column;
}
add_filter( 'manage_users_columns', 'mastermind_new_modify_user_admin_table' );

function mastermind_new_modify_user_admin_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'groups' :
            return mastermind_user_group_memberships( $user_id, true );
            break;
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'mastermind_new_modify_user_admin_table_row', 10, 3 );


function mastermind_user_group_memberships( $user_id = null, $show_hidden = false ) {
  $group_output = '';
  $class_name = array();
	//$group_ids = groups_get_user_groups($user_id, false, false);
  //$group_ids = BP_Groups_Member::get_membership_ids_for_user($user_id);
  $group_ids = mastermind_all_get_group_ids($user_id);
  // $classes = print_r($group_ids, true);
  // return $classes;

	$visible_group_ids = array();

	foreach($group_ids["groups"] as $group_id) {
		if (!$show_hidden) {
			if(groups_get_group(array( 'group_id' => $group_id )) -> status !== 'hidden') {
			$visible_group_ids[] = $group_id;
			}
		} else {
		$visible_group_ids[] = $group_id;
		}
	}

	if (empty($visible_group_ids)) {
		$group_output = 'N/A';
	} else {
		foreach($visible_group_ids as $visible_group_id) {
      if(groups_get_group(array( 'group_id' => $visible_group_id )) -> name != ''){
  			$class_name[] = (
  				'<a title="View group page" href="' . home_url() . '/groups/' . groups_get_group(array( 'group_id' => $visible_group_id )) -> slug . '">' .
  				groups_get_group(array( 'group_id' => $visible_group_id )) -> name . '</a>' .
  				(end($visible_group_ids) == $visible_group_id ? '' : '' )
  			);
      }
		}
	}

  if((isset($class_name)) && (count($class_name) > 0)){
    $group_output = implode(", ", $class_name);
  }

  return $group_output;
}


function mastermind_all_get_group_ids( $user_id, $limit = false, $page = false ) {
  global $wpdb;

  $pag_sql = '';
  if ( !empty( $limit ) && !empty( $page ) )
    $pag_sql = $wpdb->prepare( " LIMIT %d, %d", intval( ( $page - 1 ) * $limit), intval( $limit ) );

  $bp = buddypress();

  // If the user is logged in and viewing their own groups, we can show hidden and private groups.
  if ( $user_id != bp_loggedin_user_id() ) {
    $group_sql = $wpdb->prepare( "SELECT DISTINCT m.group_id FROM {$bp->groups->table_name_members} m, {$bp->groups->table_name} g WHERE m.user_id = %d AND m.is_confirmed = 1 AND m.is_banned = 0{$pag_sql}", $user_id );
    $total_groups = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(DISTINCT m.group_id) FROM {$bp->groups->table_name_members} m, {$bp->groups->table_name} g WHERE g.status != 'hidden' AND m.user_id = %d AND m.is_confirmed = 1 AND m.is_banned = 0", $user_id ) );
  } else {
    $group_sql = $wpdb->prepare( "SELECT DISTINCT group_id FROM {$bp->groups->table_name_members} WHERE user_id = %d AND is_confirmed = 1 AND is_banned = 0{$pag_sql}", $user_id );
    $total_groups = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(DISTINCT group_id) FROM {$bp->groups->table_name_members} WHERE user_id = %d AND is_confirmed = 1 AND is_banned = 0", $user_id ) );
  }

  $groups = $wpdb->get_col( $group_sql );

  return array( 'groups' => $groups, 'total' => (int) $total_groups );
}

function mastermind_new_customer_data($new_customer_data){
 $new_customer_data['role'] = 'bbp_participant';
 return $new_customer_data;
}
//add_filter( 'woocommerce_new_customer_data', 'mastermind_new_customer_data');


function mastermind_change_user_role_to_participant( $order_id ) {
  $order = new WC_Order( $order_id );
  $user_id = $order->user_id;
  wp_update_user( array( 'ID' => $user_id, 'role' => 'bbp_participant') );
}
add_action( 'woocommerce_thankyou', 'mastermind_change_user_role_to_participant' );


function mastermind_woocommerce_account_content_message( $order_id ) {
  if(isset($_GET['msg']) && ($_GET['msg'] == 'nogroup')){
    wc_print_notice( __( 'You have no assigned group.', 'woocommerce' ), 'error' );
  }
}
add_action( 'woocommerce_account_content', 'mastermind_woocommerce_account_content_message' );
