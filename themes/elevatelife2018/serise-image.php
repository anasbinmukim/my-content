<?php
// Add term page
function message_series_taxonomy_add_new_meta_field() {
	// this will add the custom meta field to the add new term page
	?>
	<div class="form-field">
		<label for="term_meta[series_video_url]"><?php _e( 'Video URL', 'Divi' ); ?></label>
		<input type="text" class="elevate-video" name="term_meta[series_video_url]" id="term_meta[series_video_url]" value="">
		<p class="description"><?php _e( 'Enter Video URL','Divi' ); ?></p>
	</div>
	<div class="form-field">
		<label for="term_meta[series_date]"><?php _e( 'Date', 'Divi' ); ?></label>
		<input type="text" class="elevate-datepicker" name="term_meta[series_date]" id="term_meta[series_date]" value="">
		<p class="description"><?php _e( 'Enter Date YYYY-MM-DD','Divi' ); ?></p>
	</div>
  <div class="form-field">
    <label for="term_meta[series_display]"><?php _e( 'Display', 'Divi' ); ?></label>
    <select name="term_meta[series_display]" id="term_meta[series_display]">
        <option value="Yes">Yes</option>
        <option value="No">No</option>
    </select>
    <p class="description"><?php _e( 'Show or hide in front end','Divi' ); ?></p>
  </div>
<?php
}
add_action( 'message_series_add_form_fields', 'message_series_taxonomy_add_new_meta_field', 10, 2 );


// Edit term page
function message_series_taxonomy_edit_meta_field($term) {

	// put the term ID into a variable
	$t_id = $term->term_id;

	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "taxonomy_$t_id" ); ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta[series_video_url]"><?php _e( 'Video URL', 'Divi' ); ?></label></th>
		<td>
			<input class="elevate-video-url" type="text" name="term_meta[series_video_url]" id="term_meta[series_video_url]" value="<?php echo esc_url( isset($term_meta['series_video_url']) ) ? esc_url( $term_meta['series_video_url'] ) : ''; ?>">
			<p class="description"><?php _e( 'Enter Video URL', 'Divi' ); ?></p>
		</td>
	</tr>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta[series_date]"><?php _e( 'Date', 'Divi' ); ?></label></th>
		<td>
			<input class="elevate-datepicker" type="text" name="term_meta[series_date]" id="term_meta[series_date]" value="<?php echo esc_attr( $term_meta['series_date'] ) ? esc_attr( $term_meta['series_date'] ) : ''; ?>">
			<p class="description"><?php _e( 'Enter Date YYYY-MM-DD', 'Divi' ); ?></p>
		</td>
	</tr>
  <tr class="form-field">
    <th scope="row" valign="top"><label for="term_meta[series_display]"><?php _e( 'Display', 'Divi' ); ?></label></th>
      <td>
        <?php $display = esc_attr( $term_meta['series_display'] ) ? esc_attr( $term_meta['series_display'] ) : ''; ?>
        <select name="term_meta[series_display]" id="term_meta[series_display]">
            <option value="Yes" <?php if($display == 'Yes'){ ?> selected="selected" <?php } ?>>Yes</option>
            <option value="No" <?php if($display == 'No'){ ?> selected="selected" <?php } ?>>No</option>
        </select>
        <p class="description"><?php _e( 'Show or hide in front end', 'Divi' ); ?></p>
      </td>
  </tr>
<?php
}
add_action( 'message_series_edit_form_fields', 'message_series_taxonomy_edit_meta_field', 10, 2 );

// Save extra taxonomy fields callback function.
function save_taxonomy_message_series_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}
add_action( 'edited_message_series', 'save_taxonomy_message_series_custom_meta', 10, 2 );
add_action( 'create_message_series', 'save_taxonomy_message_series_custom_meta', 10, 2 );


function add_message_series_columns($columns){
    $columns['photo'] = 'Photo';
    $columns['series_date'] = 'Date';
    return $columns;
}
add_filter('manage_edit-message_series_columns', 'add_message_series_columns');

function add_message_series_column_content($content, $column_name, $term_id){
    $term= get_term($term_id, 'message_series');
    switch ($column_name) {
        case 'photo':
            $content = '';
            $meta_image = get_wp_term_image($term_id);
            if($meta_image != ''){
              $content = '<img width="50" src="'.$meta_image.'" />';
            }
            break;
        case 'series_date':
            $content = '';
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id" );
            $term_data = esc_attr( $term_meta['series_date'] ) ? esc_attr( $term_meta['series_date'] ) : '';
            if($term_data != ''){
              $content = $term_data;
            }
            break;
        default:
            break;
    }
    return $content;
}
add_filter('manage_message_series_custom_column', 'add_message_series_column_content',10,3);
