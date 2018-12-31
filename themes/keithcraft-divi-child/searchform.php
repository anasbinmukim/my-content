<?php
/******
 * The template for displaying search form
 * @package WordPress
 * @subpackage RMTheme
 * @since version 1.0
 * @author RM Web Lab
 *****/
 ?>
<form role="search" class="searchform" id="searchform" action="<?php echo home_url(); ?>/" method="get">
	<div>
    <?php
      $search_for = '';
      $search_type = '';

      if(isset($_GET['s'])){
          $search_for = $_GET['s'];
      }
      if(isset($_GET['post_type'])){
          $search_type = $_GET['post_type'];
      }
    ?>
		<label class="screen-reader-text" for="s">Search for:</label>
    <p><select name="post_type" id="post_type">
      <option value="" disabled="disabled" <?php if($search_type == ''){ ?> selected="selected" <?php } ?> >Search type:</option>
      <option value="post" <?php if($search_type == 'post'){ ?> selected="selected" <?php } ?> >Blogs</option>
      <option value="quote"  <?php if($search_type == 'quote'){ ?> selected="selected" <?php } ?> >Quotes</option>
      <option value="lsi_posts"  <?php if($search_type == 'lsi_posts'){ ?> selected="selected" <?php } ?> >LSI</option>
      <option value="any"  <?php if($search_type == 'any'){ ?> selected="selected" <?php } ?> >All</option>
    </select></p>
    <p><input name="s" id="s" type="text" value="<?php echo $search_for; ?>" placeholder="<?php echo __('Type here...', 'Divi'); ?>" /></p>
		<p><input type="submit" id="searchsubmit" value="Search"></p>
	</div>
</form>
