<?php
$cur_page=get_the_id();
$current_user = get_current_user_id();
$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
$args = array(
    'paged' => $paged,
    'author' => $current_user,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_type' => 'post',
    'post_per_page'=>1,
    'post_status' => array('pending','publish'),
    'suppress_filters' => true
);
$the_query = new WP_Query($args);
 $total = $the_query->max_num_pages;
echo '<pre>';
//var_dump($the_query);
echo '</pre>';
?>

<?php if ($the_query->have_posts()) : ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>SNO</th>
                <th>Title</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $ppp = get_option( 'posts_per_page' );
            $sno = (($paged-1)*$ppp)+1;
            while ($the_query->have_posts()) : $the_query->the_post();
                ?>

                <tr id="post_id_<?= get_the_id() ?>">
                    <td><?= $sno ?></td>
                    <td><?= get_the_title() ?></td>
                    <td></td>
                    <td>
                        <a href="<?= get_permalink() ?>" target="_blank"> <i class="fa fa-eye" aria-hidden="true"></i></a>
                        <a href="<?=get_permalink($cur_page)?>/?edit_id=<?= get_the_id() ?>" id="edit_post" data-post_id="<?= get_the_id() ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <a href="#" class="delete_post" data-url="<?=get_permalink($cur_page)?>" data-post_id="<?= get_the_id() ?>"> X</a>
                    </td>
                </tr>


                <?php
                $sno++;
            endwhile;
            ?>

            <?php wp_reset_postdata(); ?>
        </tbody>
    </table>
<div class="cls-pagination">
<?php 
$cl=new BuildingManager;
$cl->wpex_pagination($total); ?>
    </div>
<?php else : ?>
    <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>


<script>
    (function ($) {

        $(document).ready(function () {
            jQuery('.delete_post').on('click', function (e) {
                var post_id = $(this).data('post_id');
                var $tr = $('#post_id_' + post_id);
                $tr.fadeOut('slow', function () {
                    $tr.slideUp('slow', function () {
                        $tr.remove();
                    });
                });
                var re_url= $(this).data('url');
                var url = '/wp-admin/admin-ajax.php';
                jQuery.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        'action': 'delete_single_post',
                        'post_id': post_id,
                    },
                    success: function (data) {
                        var obj = jQuery.parseJSON(data);
                        $('#alert_msg').html(obj.msg);
                        $('.alert-success').css('display','block');
                         setTimeout(function () {
                                window.location.href = re_url
                            }, 3000);
                    }
                });
            });
        });

    })(jQuery);
</script>