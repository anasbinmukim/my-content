<?php
/*
 * Plugin Name: Yeager Client Portal
 */

// Our custom post type function
/*
 * Creating a function to create our CPT
 */
$dir = plugin_dir_url(__FILE__);
define('MK_MAIN_DIR_CSS', $dir . '/css');
define('MK_MAIN_DIR_JS', $dir . '/js');
include( plugin_dir_path(__FILE__) . 'building-manager-post.php');

function custom_post_type() {

// Set UI labels for Custom Post Type
    $labels = array(
        'name' => _x('News', 'Post Type General Name', 'twentythirteen'),
        'singular_name' => _x('News', 'Post Type Singular Name', 'twentythirteen'),
        'menu_name' => __('News', 'twentythirteen'),
        'parent_item_colon' => __('Parent News', 'twentythirteen'),
        'all_items' => __('All News', 'twentythirteen'),
        'view_item' => __('View News', 'twentythirteen'),
        'add_new_item' => __('Add News', 'twentythirteen'),
        'add_new' => __('Add New', 'twentythirteen'),
        'edit_item' => __('Edit News', 'twentythirteen'),
        'update_item' => __('Update News', 'twentythirteen'),
        'search_items' => __('Search News', 'twentythirteen'),
        'not_found' => __('Not Found', 'twentythirteen'),
        'not_found_in_trash' => __('Not found in Trash', 'twentythirteen'),
    );

// Set other options for Custom Post Type

    $args = array(
        'label' => __('News', 'twentythirteen'),
        // 'description'         => __( 'Movie news and reviews', 'twentythirteen' ),
        'labels' => $labels,
        // Features this CPT supports in Post Editor
        'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields',),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies' => array('genres'),
        /* A hierarchical CPT is like Pages and can have
         * Parent and child items. A non-hierarchical CPT
         * is like Posts.
         */
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
    );

    // Registering your Custom Post Type
    register_post_type('news', $args);
}

/* Hook into the 'init' action so that the function
 * Containing our post type registration is not 
 * unnecessarily executed. 
 */

//add_action('init', 'custom_post_type', 0);
//add_action('init', 'create_news_tax');

function create_news_tax() {
    register_taxonomy(
            'news-category', 'news', array(
        'label' => __('News Categories'),
        'rewrite' => array('slug' => 'news-category'),
        'hierarchical' => true,
            )
    );
}


// adding setting page
class options_page_mk {

    function __construct() {
        include'custom_widget.php';
        add_shortcode('client-portal-event', array($this, 'client_portal_event_function'));
        add_shortcode('client-portal-slider', array($this, 'client_portal_slider_function'));
        add_shortcode('client-portal-building-manager', array($this, 'settings_page_mk'));
        wp_register_style('custom-css', MK_MAIN_DIR_CSS . '/custom.css', array(), '1.0.0', 'all');
        wp_enqueue_style('custom-css');
        add_action('admin_init', array($this, 'register_allow_uploads'));
        add_action('admin_menu', array($this, 'admin_menu'));
        add_filter('widget_text', array($this, 'execute_php'), 100);
        add_action('wp_enqueue_scripts', array($this, 'shortcode_wp_enqueue_scripts'));
    }


function register_allow_uploads() {
    $contributor = get_role('building_manager');
    $contributor->add_cap('upload_files');
}
    function admin_menu() {
        add_options_page(
                'Page Title', 'Client Portal', 'manage_options', 'options_page_slug_mk', array(
            $this,
            'settings_page_mk'
                )
        );
    }

    function shortcode_wp_enqueue_scripts() {
        wp_register_script('bxslider-js', MK_MAIN_DIR_JS . '/jquery_bxslider.js', array(), '1.0.0', 'all');
        wp_register_style('bxslider-css', MK_MAIN_DIR_CSS . '/jquery_bxslider.css', array(), '1.0.0', 'all');
    }

    function execute_php($html) {
        if (strpos($html, "<" . "?php") !== false) {
            ob_start();
            eval("?" . ">" . $html);
            $html = ob_get_contents();
            ob_end_clean();
        }
        return $html;
    }

    function client_portal_slider_function() {

        //  wp_enqueue_style('slick-theme');
        //wp_enqueue_style('slick-css');
        // wp_enqueue_script('slick-js');
        wp_enqueue_style('bxslider-css');
        wp_enqueue_script('bxslider-js');
        ?>
        <div class="cls-event-image">
            <?php /*
              <section dir="rtl" class="regular slider">

              <?php
              $client_image = array();
              $i = 0;
              $client_image = get_option('client_image');
              if (sizeof($client_image) > 1) {
              foreach ($client_image as $image) {

              if ($image && $image != '') {
              ?>
              <div>
              <img src="<?= $image ?>">
              </div>
              <?php
              }
              $i++;
              }
              }
              ?>



              </section>
             */ ?>
            <ul class="bxslider">
                <?php
                $client_image = array();
                $i = 0;
                $client_image = get_option('client_image');
                if (sizeof($client_image) > 1) {
                    foreach ($client_image as $image) {

                        if ($image && $image != '') {
                            ?>
                            <li>
                                <img src="<?= $image ?>">
                            </li>
                            <?php
                        }
                        $i++;
                    }
                }
                ?>
            </ul>
            <script type="text/javascript">
                jQuery(document).on('ready', function ($) {
                    jQuery('.bxslider').bxSlider({
                        mode: 'vertical',
                        pager: false,
                        auto: true,
                        responsive: true,
                        controls: false,
                        slideWidth: 350,
                        minSlides: 3,
                        moveSlides: 1,
                        slideMargin: 5,
                        autoDirection: 'prev'
                    });
                    /*
                     jQuery(".regular").slick({
                     dots: true,
                     infinite: true,
                     slidesToShow: 3,
                     slidesToScroll: 1,
                     autoplay: true,
                     vertical: true,
                     autoplaySpeed: 2000,
                     prevArrow: null,
                     nextArrow: null,
                     rtl: true,
                     });
                     */
                });
            </script>
            <style type="text/css">
                html, body {
                    margin: 0;
                    padding: 0;
                }

                * {
                    box-sizing: border-box;
                }

                .slider {
                    width: 100%;
                    margin: 100px auto;
                }

                .slick-slide {
                    margin: 0px 20px;
                }

                .slick-slide img {
                    width: 100%;
                    /*height: 200px;*/
                }

                .slick-prev:before,
                .slick-next:before {
                    color: black;
                }
            </style>
        </div>
        <?php
    }

    function client_portal_event_function() {
        ?>

        <div class="cls-event-text">
            <?= html_entity_decode(get_option('client_portal_event')) ?>
        </div>

        <?php
    }

    function settings_page_mk() {
        // echo 'This is the page content';
        $settings = array(
            'teeny' => true,
            'textarea_rows' => 15,
            'tabindex' => 1
        );

        if ($_POST[Client_Portal]) {
            if (get_option('client_portal_event') !== false) {
//                    update_option('client_portal_event', $_POST['client_portal_event']);
                //                    update_option('client_portal_event', $_POST['client_portal_event']);
                update_option('client_portal_event', wp_kses_post($_REQUEST['client_portal_event']));
            } else {

                $deprecated = null;
                $autoload = 'no';
                add_option('client_portal_event', $_POST['client_portal_event'], $deprecated, $autoload);
            }
            if (get_option('client_image') !== false) {
                update_option('client_image', $_POST['client_image']);
            } else {

                $deprecated = null;
                $autoload = 'no';
                add_option('client_image', $_POST['client_image'], $deprecated, $autoload);
            }
        }
        echo '<div class="wrap">';
        if (is_user_logged_in()) {
            $client_image = get_option('client_image');
            ?>
            <h2 class="cls-title_header">Client Portal</h2>
            <h3 class="cls-sub_title">Use this shortcode [client-portal-event] for event description and [client-portal-slider] for image slider</h3>
            <form action="" method="post" id="clientPortal">

                <table class="form-table">				
                    <tr valign="top">
                        <th scope="row"><label for="day_4_subject">Today's Events</label></th>
                        <td><?php wp_editor(get_option('client_portal_event'), 'client_portal_event', $settings); ?></td>
                    </tr>						
                </table>

                <hr>	

                <table class="form-table">              
                    <tr valign="top" class="entry">
                        <th scope="row"><label for="day_4_subject">Images</label></th>
                        <td>
                            <button type="button" class="btn add_new_image pull-right btn-success">Add new Image</button>
                            <div class="form-group hide" id="optionTemplate">
                                <img class="custom_media_image" src="" style="max-width:100px; float:left; margin: 0px     10px 0px 0px; display:inline-block;" />

                                <!-- Upload button and text field -->
                                <input class="custom_media_url" id="" type="text" name="client_image[]" value="" style="margin-bottom:10px; clear:right;">
                                <a href="#" class="button custom_media_upload">Upload</a>
                                <button type="button" class="btn removeButton"><span class="plus"></span></button>
                            </div>
                            <?php
                            $client_image = array();
                            $i = 0;
                            $client_image = get_option('client_image');

                            $client_image = array_filter($client_image);
                            if (sizeof($client_image) >= 1) {
                                foreach ($client_image as $image) {

                                    if ($image && $image != '') {
                                        ?>

                                        <div class="form-group">
                                            <img class="custom_media_image" src="<?= $image ?>" style="max-width:100px; float:left; margin: 0px     10px 0px 0px; display:inline-block;" />

                                            <!-- Upload button and text field -->
                                            <input class="custom_media_url" id="" type="text" name="client_image[]" value="<?= $image ?>" style="margin-bottom:10px; clear:right;">
                                            <a href="#" class="button custom_media_upload">Upload</a>
                                            <button class="btn  removeButton" type="button">
                                                <span class="plus"></span>
                                            </button>
                                        </div>
                                        <?php
                                    }
                                    $i++;
                                }
                            }
                            if ($i === 0) {
                                /*
                                  ?>
                                  <div class="form-group">
                                  <img class="custom_media_image" src="" style="max-width:100px; float:left; margin: 0px     10px 0px 0px; display:inline-block;" />

                                  <!-- Upload button and text field -->
                                  <input class="custom_media_url" id="" type="text" name="client_image[]" value="" style="margin-bottom:10px; clear:right;">
                                  <a href="#" class="button custom_media_upload">Upload</a>
                                  <button class="btn btn-success addButton " type="button">
                                  <span class="plus"></span>
                                  </button>
                                  </div>
                                  <?php
                                 * 
                                 */
                            }
                            ?>

                        </td>
                    </tr>

                </table>



                <hr>			

                <!-- <h2>Day 28</h2> -->
                <table class="form-table">				

                    <tr valign="top">
                        <th scope="row"><label for="email_settings_save"></label></th>
                        <td><input type="submit" class="button button-primary btn-danger button-large" value="Settings Save" id="cronemail_settings_save" name="Client_Portal"></td>
                    </tr>	

                </table>

                <hr>		

            </form>
       
                <style>
                   .wp-admin button.btn.add_new_image.pull-right.btn-success {
                        float: right;
                        /*margin-right: 60%;*/
                        display: inline-block;
                        padding: 6px 12px;
                        margin-bottom: 0;
                        font-size: 14px;
                        font-weight: 400;
                        line-height: 1.42857143;
                        text-align: center;
                        white-space: nowrap;
                        vertical-align: middle;
                        touch-action: manipulation;
                        cursor: pointer;
                        -webkit-user-select: none;
                        background-image: none;
                        background: #5cb85c;
                        border: none;
                        color: #fff;
                    }
                   .wp-admin button.btn.add_new_image.pull-right.btn-success:hover {
                        color: #fff;
                        background-color: #449d44;
                        border-color: #398439;
                    }
                </style>
           
            <style>
                .hide{display: none;}

                .page-template-default #clientPortal th {
                    max-width: 100% !important;
                }
                .page-template-default #clientPortal td {
                    max-width: 100% !important;
                }
                .page-template-default .wp-editor-area{border: 1px solid #eee;}

                .page-template-default #clientPortal tr td{border: none !important;}
            </style>
            <script>
                jQuery(document).ready(function ($) {
                    // The maximum number of options
                    var MAX_OPTIONS = 100;

                    $('#clientPortal')
                            .on('click', '.add_new_image', function () {
                                var $template = $('#optionTemplate'),
                                        $clone = $template
                                        .clone()
                                        .removeClass('hide')
                                        .removeAttr('id')
                                        .insertAfter($template),
                                        $option = $clone.find('[name="client_image[]"]');
                            })

                            // Remove button click handler
                            .on('click', '.removeButton', function () {
                                var $row = $(this).parents('.form-group'),
                                        $option = $row.find('[name="client_image[]"]');

                                // Remove element containing the option
                                $row.remove();

                            })

                            // Called after adding new field
                            .on('added.field.fv', function (e, data) {
                                // data.field   --> The field name
                                // data.element --> The new field element
                                // data.options --> The new field options

                                if (data.field === 'option[]') {
                                    if ($('#clientPortal').find(':visible[name="client_image[]"]').length >= MAX_OPTIONS) {
                                        $('#clientPortal').find('.addButton').attr('disabled', 'disabled');
                                    }
                                }
                            })

                            // Called after removing the field
                            .on('removed.field.fv', function (e, data) {
                                if (data.field === 'option[]') {
                                    if ($('#clientPortal').find(':visible[name="client_image[]"]').length < MAX_OPTIONS) {
                                        $('#clientPortal').find('.addButton').removeAttr('disabled');
                                    }
                                }
                            })
                            .on('click', '.custom_media_upload', function () {
                                var send_attachment_bkp = wp.media.editor.send.attachment;
                                var button = $(this);

                                wp.media.editor.send.attachment = function (props, attachment) {

                                    $(button).prev().prev().attr('src', attachment.url);
                                    $(button).prev().val(attachment.url);

                                    wp.media.editor.send.attachment = send_attachment_bkp;
                                }

                                wp.media.editor.open(button);

                                return false;
                            });
                });

            </script>

            <?php
        } else {
            echo "<h3>You must be logged in to access this page.</h3>";
        }
        echo '</div>';
    }

}

new options_page_mk;
