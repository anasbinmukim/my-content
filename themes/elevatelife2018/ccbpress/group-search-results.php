<div class="ccbpress-group-search-table">
	<table>
		<tr>
			<th><?php esc_html_e( 'Name', 'ccbpress-groups' ); ?></th>
			<th><?php esc_html_e( 'Day', 'ccbpress-groups' ); ?></th>
			<th><?php esc_html_e( 'Time', 'ccbpress-groups' ); ?></th>
			<th><?php esc_html_e( 'Type', 'ccbpress-groups' ); ?></th>
			<th><?php esc_html_e( 'Area', 'ccbpress-groups' ); ?></th>
			<!-- <th><?php esc_html_e( 'Campus', 'ccbpress-groups' ); ?></th> -->
		</tr>
		<?php
		/**
		 * Loop through each result
		 */
		foreach ( $data['search_results']->response->items->item as $item ) : ?>
			<?php $data['is_valid_args']['item'] = $item; ?>
			<?php if ( $group_profile = $template::is_valid( $data['is_valid_args'] ) ) : ?>
				<tr class="ccbpress-group-search-list" data-ccb-group-id="<?php echo esc_attr( $item->id ); ?>">
					<td>
					<?php
						/**
						 * Check if this group has an image
						 */
						if ( $template::has_group_image( $group_profile ) ) : ?>
							<div class="ccbpress_column_feature_image"><img src="<?php echo esc_attr( $group_profile->image ); ?>" /></div>
						<?php endif; ?>
						<span class="dashicons dashicons-arrow-right"></span><span class="dashicons dashicons-arrow-down"></span> <?php echo esc_html( $item->name ); ?>
					</td>
					<td>
						<?php if ( 0 !== strlen( $item->meet_day_name ) ) : ?>
							<?php echo esc_html( $item->meet_day_name ); ?>
						<?php endif; ?>
					</td>
					<td>
						<?php if ( 0 !== strlen( $item->meet_time_name ) ) : ?>
							<?php echo esc_html( $item->meet_time_name ); ?>
						<?php endif; ?>
					</td>
					<td>
						<?php if ( 0 !== strlen( $item->group_type_name ) ) : ?>
							<?php echo esc_html( $item->group_type_name ); ?>
						<?php endif; ?>
					</td>
					<td>
						<?php if ( 0 !== strlen( $item->area_name ) ) : ?>
							<?php echo esc_html( $item->area_name ); ?>
						<?php endif; ?>
					</td>
					<!-- <td>
						<?php //echo esc_html( $template::display_campus( $group_profile, '', '' ) ); ?>
					</td> -->
				</tr>
				<tr class="ccbpress-group-search-details ccbpress-group-<?php echo esc_attr( $item->id ); ?>">
					<td colspan="7">
						<?php if ( $mailto = $template::get_mailto( $group_profile ) ) : ?>
							<div class="ccbpress-group-leader-contact">
								<a href="<?php echo esc_attr( $mailto ); ?>" target="_blank"><?php esc_html_e( 'Contact Leader', 'ccbpress-groups' ); ?></a>
							</div>
						<?php endif; ?>
						<div class="ccbpress-group-name">
								<?php echo esc_html( $item->name ); ?>
							<?php if ( true === ( $spots_available = $template::is_full( $group_profile ) ) ) : ?>
								&nbsp;<span class="ccbpress-group-full"><?php esc_html_e( '(Full Group)', 'ccbpress-groups' ); ?></span>
							<?php elseif ( 'unlimited' !== $spots_available ) : ?>
								&nbsp;<span class="ccbpress-group-spots-available"><?php _n( '%s spot available', '%s spots available', $spots_available, 'ccbpress-groups' ); ?></span>
							<?php endif; ?>
						</div>
						<div class="ccbpress-group-leader">
							<?php esc_html_e( 'Leader:', 'ccbpress-groups' ); ?></php> <?php echo esc_html( $item->owner_name ); ?>
						</div>
						<div class="ccbpress-group-description"><?php echo wpautop( $item->description, true ); ?></div>
						<div class="ccbpress-group-meta">
							<?php if ( 0 !== strlen( $item->area_name ) ) : ?>
								<div class="ccbpress-group-meta-area" title="<?php esc_attr_e( 'Area', 'ccbpress-groups' ); ?>">
									<span class="dashicons dashicons-location"></span> <?php echo esc_html( $item->area_name ); ?> <?php echo esc_html( $template::display_campus( $group_profile ) ); ?>
								</div>
							<?php endif; ?>

							<?php if ( 0 !== strlen( $item->group_type_name ) ) : ?>
								<div class="ccbpress-group-meta-type" title="<?php esc_attr_e( 'Group Type', 'ccbpress-groups' ); ?>">
									<span class="dashicons dashicons-tag"></span> <?php echo esc_html( $item->group_type_name ); ?>
								</div>
							<?php endif; ?>

							<?php if ( 0 !== strlen( $item->meet_day_name ) ) : ?>
								<div class="ccbpress-group-meta-day" title="<?php esc_attr_e( 'Meeting Day', 'ccbpress-groups' ); ?>">
									<span class="dashicons dashicons-calendar-alt"></span> <?php echo esc_html( $item->meet_day_name ); ?>
								</div>
							<?php endif; ?>

							<?php if ( 0 !== strlen( $item->meet_time_name ) ) : ?>
								<div class="ccbpress-group-meta-time" title="<?php esc_attr_e( 'Meeting Time', 'ccbpress-groups' ); ?>">
									<span class="dashicons dashicons-clock"></span> <?php echo esc_html( $item->meet_time_name ); ?>
								</div>
							<?php endif; ?>

							<?php if ( $template::childcare_provided( $group_profile ) ) : ?>
								<div class="ccbpress-group-meta-childcare" title="<?php esc_attr_e( 'Childcare', 'ccbpress-groups' ); ?>">
									<span class="dashicons dashicons-universal-access"></span> <?php esc_html_e( 'Childcare Available', 'ccbpress-groups' ); ?>
								</div>
							<?php endif; ?>
						</div>
					</td>
				</tr>
			<?php endif; ?>
		<?php endforeach; ?>
	</table>
</div>
