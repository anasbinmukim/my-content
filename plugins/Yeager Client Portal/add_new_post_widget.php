<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$post_id = @$_GET['edit_id'];
$cur_page=get_the_id();
?>
<div class="cls-form" style="padding-top: 20px">
    <a href="<?=get_permalink($page_id)?>" class="btn btn-success <?= $post_id ? '' : 'hidden' ?>">Add new Post</a>
    <div class="alert alert-success" style="display: none">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong><p id='alert_msg'></p></strong></div>
    <form data-toggle="validator" role="form" id="add_new_post" action="/wp-admin/admin-ajax.php">
        <input type="hidden" class="form-control" value="add_new_post" name="action" id='action' required>
        <input type="hidden" class="form-control" value="post" name="post_type" id="post_type" required>
        <input type="hidden" class="form-control" value="<?=$cur_page?>" name="page_id_mk" id="page_id_mk" required>
        <input type="hidden" class="form-control" value="<?= ($post_id) ? $post_id : '' ?>" name="post_id" id="post_id" >
        <div class="form-group">
            <label for="exampleInputEmail1">Title</label>
            <input type="text" class="form-control" value="<?= $post_id ? get_the_title($post_id) : '' ?>" name="post_title" id="post_title" aria-describedby="emailHelp" placeholder="Enter post title" required>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Content</label>
            <?php
            if ($post_id) {
                $url = wp_get_attachment_url(get_post_thumbnail_id($post_id));
                $post_thumbnail_id = get_post_thumbnail_id($post_id);
                $content = apply_filters('the_content', get_post_field('post_content', $post_id));
            } else {
                $content = ' ';
            }
            $settings = array('editor_class' => 'form-control');
            wp_editor($content, 'post_content', $settings);
            ?>
        </div>

        <div class="form-group">
            <img id="frontend-image" src="<?= $url ? $url : '' ?>" />
              <button type="button" id="remove_image" class="btn btn-danger pull-right btn-xs <?= $post_thumbnail_id ? '' : 'hidden' ?>" >Remove Image</button>
            <button type="button" id="frontend-button" class="btn add_new_image  btn-success <?= $post_thumbnail_id ? 'hidden' : '' ?>">Add new Image</button>
            <input type="hidden" class="form-control" id="feature_image" value="<?= $post_thumbnail_id ? $post_thumbnail_id : '' ?>" name="feature_image">
        </div>
      
        <button type="submit" class="btn btn-primary"><?= $post_id ? 'Update post' : 'Add post' ?></button>
    </form>
</div>

<script>
    (function ($) {

        $(document).ready(function () {
            jQuery('#add_new_post').validator().on('submit', function (e) {
                if (e.isDefaultPrevented()) {
                    // handle the invalid form...
                } else {
                    e.preventDefault();
                     tinyMCE.triggerSave();
                    var wp_editor_iframe = jQuery('#post_content_ifr');
                    var post_contents = jQuery('#post_content').val();
                    var data = jQuery(this).serialize();
                    var url = $(this).attr('action');
                    jQuery.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            'post_title': $('#post_title').val(),
                            'action': $("#action").val(),
                            'post_data': post_contents,
                            'feature_image': $("#feature_image").val(),
                            'post_id': $('#post_id').val(),
                               'page_id': $('#page_id_mk').val(),
                            'post_type': $('#post_type').val()
                        },
                        success: function (data) {
                            var obj = jQuery.parseJSON(data);
                            $('#alert_msg').html(obj.msg);
                            $('.alert-success').css('display','block');
                            setTimeout(function () {
                                window.location.href = obj.url
                            }, 3000);
                        }
                    });

                }
            });
            $('#remove_image').on('click', function (event) {
                    $('#frontend-image').attr('src','');
                    $('#feature_image').val(''); 
                    $('.add_new_image').removeClass('hidden');
                      $('#remove_image').addClass('hidden');
            });
            var file_frame;
            $('#frontend-button').on('click', function (event) {
                event.preventDefault();
                if (file_frame) {
                    file_frame.open();
                    return;
                }

                file_frame = wp.media.frames.file_frame = wp.media({
                    title: $(this).data('uploader_title'),
                    button: {
                        text: $(this).data('uploader_button_text'),
                    },
                    multiple: false // set this to true for multiple file selection
                });

                file_frame.on('select', function () {
                    attachment = file_frame.state().get('selection').first().toJSON();
                    console.log(attachment);
                    // do something with the file here
                    $('#frontend-image').attr('src', attachment.url);
                    $('#feature_image').val(attachment.id);
                    $('#remove_image').removeClass('hidden');
                    $('.add_new_image').addClass('hidden');
                });

                file_frame.open();
            });
        });

    })(jQuery);
</script>
