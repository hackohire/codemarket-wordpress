<?php
/**
 * Commissions Filters
 *
 * @package     EDD_Commissions
 * @subpackage  Admin
 * @copyright   Copyright (c) 2017, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Renders the main commissions admin page
 *
 * @since       3.3
 * @return      void
*/
function eddc_commissions_page() {
	$default_views  = eddc_commission_views();
	$requested_view = isset( $_GET['view'] ) ? sanitize_text_field( $_GET['view'] ) : 'commissions';

	if ( $requested_view == 'add' ) {
		eddc_render_add_commission_view();
	} elseif ( array_key_exists( $requested_view, $default_views ) && function_exists( $default_views[$requested_view] ) ) {
		eddc_render_commission_view( $requested_view, $default_views );
	} else {
		eddc_commissions_list();
	}
}


/**
 * Register the views for commission management
 *
 * @since       3.3
 * @return      array Array of views and their callbacks
 */
function eddc_commission_views() {
	$views = array();
	return apply_filters( 'eddc_commission_views', $views );
}


/**
 * Register the tabs for commission management
 *
 * @since       3.3
 * @return      array Array of tabs for the customer
 */
function eddc_commission_tabs() {
	$tabs = array();
	return apply_filters( 'eddc_commission_tabs', $tabs );
}


/**
 * List table of commissions
 *
 * @since  3.3
 * @return void
 */
function eddc_commissions_list() {
	?>
	<div class="wrap">

		<div id="icon-edit" class="icon32"><br/></div>
		<h2>
			<?php _e( 'Easy Digital Download Commissions', 'eddc' ); ?>
			<a href="<?php echo esc_url( add_query_arg( array( 'view' => 'add' ) ) ); ?>" class="add-new-h2"><?php _e( 'Add New', 'eddc' ); ?></a>
		</h2>

		<?php if ( defined( 'EDD_VERSION' ) && version_compare( '2.4.2', EDD_VERSION, '<=' ) ) : ?>
			<div id="edd-commissions-export-wrap">
				<button class="button-primary eddc-commissions-export-toggle"><?php _e( 'Generate Payout File', 'eddc' ); ?></button>
				<button class="button-primary eddc-commissions-export-toggle" style="display:none"><?php _e( 'Close', 'eddc' ); ?></button>

				<?php do_action( 'eddc_commissions_page_buttons' ); ?>

				<form id="eddc-export-commissions" class="eddc-export-form edd-export-form" method="post" style="display:none;">
					<?php echo EDD()->html->date_field( array( 'id' => 'edd-payment-export-start', 'name' => 'start', 'placeholder' => __( 'Choose start date', 'eddc' ) ) ); ?>
					<?php echo EDD()->html->date_field( array( 'id' => 'edd-payment-export-end','name' => 'end', 'placeholder' => __( 'Choose end date', 'eddc' ) ) ); ?>
					<input type="number" increment="0.01" class="eddc-medium-text" id="minimum" name="minimum" placeholder=" <?php _e( 'Minimum', 'eddc' ); ?>" />
					<?php wp_nonce_field( 'edd_ajax_export', 'edd_ajax_export' ); ?>
					<input type="hidden" name="edd-export-class" value="EDD_Batch_Commissions_Payout"/>
					<span>
						<input type="submit" value="<?php _e( 'Generate File', 'eddc' ); ?>" class="button-secondary"/>
						<span class="spinner"></span>
					</span>
					<p><?php _e( 'This will generate a payout file for review.', 'eddc' ); ?></p>
				</form>

				<form id="eddc-export-commissions-mark-as-paid" class="eddc-export-form edd-export-form" method="post" style="display: none;">
					<?php wp_nonce_field( 'edd_ajax_export', 'edd_ajax_export' ); ?>
					<input type="hidden" name="edd-export-class" value="EDD_Batch_Commissions_Mark_Paid"/>
					<span>
						<input type="submit" value="<?php _e( 'Mark as Paid', 'eddc' ); ?>" class="button-primary"/>&nbsp;
						<a href="<?php echo admin_url( 'edit.php?post_type=download&page=edd-commissions' ); ?>" class="button-secondary"><?php _e( 'Cancel', 'eddc' ); ?></a>
						<span class="spinner"></span>
					</span>
					<p><?php _e( 'This will mark all unpaid commissions in the generated file as paid', 'eddc' ); ?></p>
				</form>
			</div>
		<?php else: ?>
			<p>
				<form id="commission-payouts" method="get" style="float:right;margin:0;">
					<input type="text" name="from" class="edd_datepicker" placeholder="<?php _e( 'From - mm/dd/yyyy', 'eddc' ); ?>"/>
					<input type="text" name="to" class="edd_datepicker" placeholder="<?php _e( 'To - mm/dd/yyyy', 'eddc' ); ?>"/>
					<input type="hidden" name="post_type" value="download" />
					<input type="hidden" name="page" value="edd-commissions" />
					<input type="hidden" name="edd_action" value="generate_payouts" />
					<?php echo wp_nonce_field( 'eddc-payout-nonce', 'eddc-payout-nonce' ); ?>
					<?php echo submit_button( __('Generate Mass Payment File', 'eddc'), 'secondary', '', false ); ?>
				</form>
			</p>
		<?php endif; ?>

		<style>
			.column-status, .column-count { width: 100px; }
			.column-limit { width: 150px; }
		</style>
		<form id="commissions-filter" method="get">
			<input type="hidden" name="post_type" value="download" />
			<input type="hidden" name="page" value="edd-commissions" />
			<?php
			$commissions_table = new edd_C_List_Table();
			$commissions_table->prepare_items();
			$commissions_table->views();
			
			$user_id      = $commissions_table->get_filtered_user();
			$total_unpaid = edd_currency_filter( edd_format_amount( eddc_get_unpaid_totals( $user_id ) ) );
			?>
			<div class="eddc-user-search-wrapper">
				<?php if ( ! empty( $user_id ) ) : ?>
					<?php $user = get_userdata( $user_id ); ?>
					<?php printf( __( 'Showing commissions for: %s', 'eddc' ), $user->user_nicename ); ?> <a class="eddc-clear-search" href="<?php echo admin_url( 'edit.php?post_type=download&page=edd-commissions' ); ?>">&times;</a>
				<?php else: ?>
					<?php echo EDD()->html->ajax_user_search( array( 'name' => 'user', 'placeholder' => __( 'Search Users', 'eddc' ) ) ); ?>
					<input type="submit" class="button-secondary" value="Filter" />
				<?php endif; ?>
			</div>
			<?php
			$commissions_table->display();
			?>
		</form>
		<div class="commission-totals">
			<?php _e( 'Total Unpaid:', 'eddc' ); ?>&nbsp;<strong><?php echo $total_unpaid; ?></strong>
		</div>
	</div>
	<?php

	$redirect = get_transient( '_eddc_bulk_actions_redirect' );

	if ( false !== $redirect ) : delete_transient( '_eddc_bulk_actions_redirect' );
	$redirect = admin_url( 'edit.php?post_type=download&page=edd-commissions' );

	if ( isset( $_GET['s'] ) ) {
		$redirect = add_query_arg( 's', $_GET['s'], $redirect );
	}
	?>
	<script type="text/javascript">
	window.location = "<?php echo $redirect; ?>";
	</script>
	<?php endif;
}


/**
 * Renders the add commission view
 *
 * @since       3.3
 * @return      void
 */
function eddc_render_add_commission_view() {
	$render = true;

	if ( ! current_user_can( 'edit_shop_payments' ) ) {
		edd_set_error( 'edd-no-access', __( 'You are not permitted to add commissions.', 'eddc' ) );
		$render = false;
	}
	?>
	<div class="wrap">
		<h2><?php _e( 'Add New Commission', 'eddc' ); ?></h2>
		<?php if ( edd_get_errors() ) : ?>
			<div class="error settings-error">
				<?php edd_print_errors(); ?>
			</div>
		<?php endif; ?>

		<?php if ( $render ) : ?>
			<div id="edd-item-card-wrapper" class="eddc-commission-card eddc-add-commission" style="float: left">
				<div class="info-wrapper item-section">
					<form id="add-item-info" method="post" action="<?php echo admin_url( 'edit.php?post_type=download&page=edd-commissions' ); ?>">
						<div class="item-info">
							<table class="widefat striped">
								<?php do_action( 'eddc_commission_edit_fields_top', $commission_id ); ?>
								<tr id="eddc-add-user-id-row">
									<td class="row-title">
										<label for="user_id"><?php _e('User ID', 'eddc'); ?></label>
									</td>
									<td style="word-wrap: break-word">
										<?php echo EDD()->html->user_dropdown( array( 'id' => 'user_id', 'name' => 'user_id' ) ); ?>
										<p class="description"><?php _e('The ID of the user that received this commission.', 'eddc'); ?></p>
									</td>
								</tr>
								<tr id="eddc-add-download-id-row">
									<td class="row-title">
										<label for="download_id"><?php _e('Download ID', 'eddc'); ?></label>
									</td>
									<td style="word-wrap: break-word">
										<?php echo EDD()->html->product_dropdown( array( 'id' => 'download_id', 'name' => 'download_id', 'chosen' => true, 'variations' => true ) ); ?>
										<p class="description"><?php _e('The ID of the product this commission was for.', 'eddc'); ?></p>
									</td>
								</tr>
								<tr id="eddc-add-payment-id-row">
									<td class="row-title">
										<label for="payment_id_id"><?php _e('Payment ID', 'eddc'); ?></label>
									</td>
									<td style="word-wrap: break-word">
										<input type="text" id="payment_id_id" name="payment_id" value=""/>
										<p class="description"><?php _e('The payment ID this commission is related to (optional).', 'eddc'); ?></p>
									</td>
								</tr>
								<tr id="eddc-add-type-row">
									<td class="row-title">
										<label for="type"><?php _e('Type', 'eddc'); ?></label>
									</td>
									<td style="word-wrap: break-word">
										<input type="radio" id="type-percentage" name="type" value="percentage" checked="checked" /> <label for="type-percentage"><?php _e( 'Percentage', 'eddc' ); ?></label>
										<br />
										<input type="radio" id="type-flat" name="type" value="flat"/> <label for="type-flat"><?php _e( 'Flat', 'eddc' ); ?></label>
										<p class="description"><?php _e('The type of commission to be recorded.', 'eddc'); ?></p>
									</td>
								</tr>
								<tr id="eddc-add-rate-row">
									<td class="row-title">
										<label for="rate"><?php _e('Rate', 'eddc'); ?></label>
									</td>
									<td style="word-wrap: break-word">
										<input type="text" id="rate" name="rate" value=""/>
										<p class="description"><?php _e('The percentage rate of this commission.', 'eddc'); ?></p>
									</td>
								</tr>
								<tr id="eddc-add-amount-row">
									<td class="row-title">
										<label for="amount"><?php _e('Amount', 'eddc'); ?></label>
									</td>
									<td style="word-wrap: break-word">
										<input type="text" id="amount" name="amount" value=""/>
										<p class="description"><?php _e('The total amount of this commission.', 'eddc'); ?></p>
									</td>
								</tr>
								<?php do_action( 'eddc_commission_edit_fields_bottom', $commission_id ); ?>
							</table>
						</div>
						<div id="item-edit-actions" class="edit-item" style="float: right; margin: 10px 0 0; display: block;">
							<?php wp_nonce_field( 'eddc_add_commission', 'eddc_add_commission_nonce' ); ?>
							<input type="submit" name="eddc_add_commission" id="eddc_add_commission" class="button button-primary" value="<?php _e( 'Add Commission', 'eddc' ); ?>" />
						</div>
						<div class="clear"></div>
					</form>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<?php
}


/**
 * Renders the commission view wrapper
 *
 * @since       3.3
 * @param       string $view The View being requested
 * @param       array $callbacks The Registered views and their callback functions
 * @return      void
 */
function eddc_render_commission_view( $view, $callbacks ) {
	$render = true;

	if ( ! current_user_can( 'edit_shop_payments' ) ) {
		edd_set_error( 'edd-no-access', __( 'You are not permitted to view this data.', 'eddc' ) );
		$render = false;
	}

	if ( ! isset( $_GET['commission'] ) || ! is_numeric( $_GET['commission'] ) ) {
		edd_set_error( 'edd-invalid-commission', __( 'Invalid commission ID provided.', 'eddc' ) );
		$render = false;
	}

	$commission_id   = (int) $_GET['commission'];
	$commission      = get_post( $commission_id );
	$commission_tabs = eddc_commission_tabs();
	?>
	<div class="wrap">
		<h2><?php _e( 'Commission Details', 'eddc' ); ?></h2>
		<?php if ( edd_get_errors() ) : ?>
			<div class="error settings-error">
				<?php edd_print_errors(); ?>
			</div>
		<?php endif; ?>

		<?php if ( $render ) : ?>
			<div id="edd-item-wrapper" class="edd-item-has-tabs edd-clearfix">
				<div id="edd-item-tab-wrapper" class="commission-tab-wrapper">
					<ul id="edd-item-tab-wrapper-list" class="commission-tab-wrapper-list">
						<?php foreach ( $commission_tabs as $key => $tab ) : ?>
							<?php $active = $key === $view ? true : false; ?>
							<?php $class  = $active ? 'active' : 'inactive'; ?>

							<li class="<?php echo sanitize_html_class( $class ); ?>">
								<?php
								$tab_title  = sprintf( _x( 'Commission %s', 'Commission Details page tab title', 'eddc' ), esc_attr( $tab['title'] ) );
								$aria_label = ' aria-label="' . $tab_title . '"';
								?>

								<?php if ( ! $active ) : ?>
									<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=download&page=edd-commissions&view=' . $key . '&commission=' . $commission_id . '#wpbody-content' ) ); ?>"<?php echo $aria_label; ?>>
								<?php endif; ?>
								<span class="edd-item-tab-label-wrap"<?php echo $active ? $aria_label : ''; ?>>
									<span class="dashicons <?php echo sanitize_html_class( $tab['dashicon'] ); ?>" aria-hidden="true"></span>
									<?php
									if ( version_compare( EDD_VERSION, 2.7, '>=' ) ) {
										echo '<span class="edd-item-tab-label">' . esc_attr( $tab['title'] ) . '</span>';
									}
									?>
								</span>
								<?php if ( ! $active ) : ?>
									</a>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>

				<div id="edd-item-card-wrapper" class="eddc-commission-card" style="float: left">
					<?php
					if ( function_exists( $callbacks[ $view ] ) ) {
						$callbacks[ $view ]( $commission );
					}
					?>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<?php
}


/**
 * View a commission
 *
 * @since       3.3
 * @param       object $commission The commission object being displayed
 * @return      void
 */
function eddc_commissions_view( $commission ) {
	if ( ! $commission ) {
		echo '<div class="info-wrapper item-section">' . __( 'Invalid commission specified.', 'eddc' ) . '</div>';
		return;
	}

	$base            = admin_url( 'edit.php?post_type=download&page=edd-commissions&view=overview&commission=' . $commission->ID );
	$base            = wp_nonce_url( $base, 'eddc_commission_nonce' );
	$commission_id   = $commission->ID;
	$commission_info = get_post_meta( $commission_id, '_edd_commission_info', true );
	$user_data       = get_userdata( $commission_info['user_id'] );
	$payment         = get_post_meta( $commission_id, '_edd_commission_payment_id', true );
	$download        = get_post_meta( $commission_id, '_download_id', true );
	$type            = ( array_key_exists( 'type', $commission_info ) ? $commission_info['type'] : eddc_get_commission_type( $download ) );
	$status          = eddc_get_commission_status( $commission_id );

	$child_args      = array(
		'post_type'      => 'edd_commission',
		'post_status'    => array( 'publish', 'future' ),
		'posts_per_page' => -1,
		'post_parent'    => $commission_id
	);

	$has_variable_prices = edd_has_variable_prices( $download );
	$variation           = false;
	if ( $has_variable_prices ) {
		$variation = get_post_meta( $commission_id, '_edd_commission_download_variation', true );
	}

	$rate = eddc_format_rate( $commission_info['rate'], $type );

	do_action( 'eddc_commission_card_top', $commission_id );
	?>
	<div class="info-wrapper item-section">
		<form id="edit-item-info" method="post" action="<?php echo admin_url( 'edit.php?post_type=download&page=edd-commissions&view=overview&commission=' . $commission_id ); ?>">
			<div class="item-info">
				<table class="widefat striped">
					<tbody>
						<tr>
							<td class="row-title">
								<label for="tablecell"><?php _e( 'Commission ID', 'eddc' ); ?></label>
							</td>
							<td style="word-wrap: break-word">
								<?php echo $commission_id; ?>
							</td>
						</tr>
						<tr>
							<td class="row-title">
								<label for="tablecell"><?php _e( 'Payment', 'eddc' ); ?></label>
							</td>
							<td style="word-wrap: break-word">
								<?php echo $payment ? '<a href="' . esc_url( admin_url( 'edit.php?post_type=download&page=edd-payment-history&view=view-order-details&id=' . $payment ) ) . '" title="' . __( 'View payment details', 'eddc' ) . '">#' . $payment . '</a> - ' . edd_get_payment_status( get_post( $payment ), true  ) : ''; ?>
							</td>
						</tr>
						<tr>
							<td class="row-title">
								<label for="tablecell"><?php _e( 'Status', 'eddc' ); ?></label>
							</td>
							<td style="word-wrap: break-word">
								<?php echo ucfirst( $status ); ?>
							</td>
						</tr>
						<tr>
							<td class="row-title">
								<label for="tablecell"><?php _e( 'Create Date', 'eddc' ); ?></label>
							</td>
							<td style="word-wrap: break-word">
								<?php echo date_i18n( get_option( 'date_format' ), strtotime( get_post_field( 'post_date', $commission_id ) ) ); ?>
							</td>
						</tr>
						<tr>
							<td class="row-title">
								<label for="tablecell"><?php _e( 'User', 'eddc' ); ?></label>
							</td>
							<td style="word-wrap: break-word">
								<?php
								if ( false !== $user_data ) {
									echo '<a href="' . esc_url( add_query_arg( 'user', $user_data->ID ) ) . '" title="' . __( 'View all commissions for this user', 'eddc' ) . '"">' . $user_data->display_name . '</a>&nbsp;(' . __( 'ID:', 'eddc' ) . ' ' . $commission_info['user_id'] . ')';
								} else {
									echo '<em>' . __( 'Invalid User', 'eddc' ) . '</em>';
								}
								?>
								<?php echo EDD()->html->user_dropdown( array( 'class' => 'eddc-commission-user', 'id' => 'eddc_user', 'name' => 'eddc_user', 'selected' => esc_attr( $commission_info['user_id'] ) ) ); ?>
							</td>
						</tr>
						<tr>
							<td class="row-title">
								<label for="tablecell"><?php _e( 'Download', 'eddc' ); ?></label>
							</td>
							<td style="word-wrap: break-word">
								<?php
								$selected = ! empty( $download ) ? $download . ( ! empty( $variation ) ? '_' . $variation : '' ) : '';
								echo ! empty( $download ) ? '<a href="' . esc_url( add_query_arg( 'download', $download ) ) . '" title="' . __( 'View all commissions for this item', 'eddc' ) . '">' . get_the_title( $download ) . '</a>' . ( ! empty( $variation ) ? ' - ' . $variation : '') : '';
								echo EDD()->html->product_dropdown( array( 'class' => 'eddc-commission-download', 'id' => 'eddc_download', 'name' => 'eddc_download', 'chosen' => true, 'variations' => true, 'selected' => $selected ) );
								?>
							</td>
						</tr>
						<tr>
							<td class="row-title">
								<label for="tablecell"><?php _e( 'Rate', 'eddc' ); ?></label>
							</td>
							<td style="word-wrap: break-word">
								<?php echo $rate; ?>
								<input type="text" name="eddc_rate" class="hidden eddc-commission-rate" value="<?php echo esc_attr( $commission_info['rate'] ); ?>" />
							</td>
						</tr>
						<tr>
							<td class="row-title">
								<label for="tablecell"><?php _e( 'Amount', 'eddc' ); ?></label>
							</td>
							<td style="word-wrap: break-word">
								<?php echo edd_currency_filter( edd_format_amount( $commission_info['amount'] ) ); ?>
								<input type="text" name="eddc_amount" class="hidden eddc-commission-amount" value="<?php echo edd_format_amount( $commission_info['amount'] ); ?>" />
							</td>
						</tr>
						<tr>
							<td class="row-title">
								<label for="tablecell"><?php _e( 'Currency', 'eddc' ); ?></label>
							</td>
							<td style="word-wrap: break-word">
								<?php echo $commission_info['currency']; ?>
							</td>
						<tr>
							<td class="row-title">
								<label for="tablecell"><?php _e( 'Actions:', 'eddc' ); ?></label>
							</td>
							<td class="eddc-commission-card-actions">
								<?php
								$actions = array(
									'edit' => '<a href="#" class="eddc-edit-commission">' . __( 'Edit Commission', 'eddc' ) . '</a>'
								);
								$base    = admin_url( 'edit.php?post_type=download&page=edd-commissions&view=overview&commission=' . $commission_id );
								$base    = wp_nonce_url( $base, 'eddc_commission_nonce' );

								if ( $status == 'revoked' ) {
									$actions['mark_as_accepted'] = sprintf( '<a href="%s&action=%s">' . __( 'Accept', 'eddc' ) . '</a>', $base, 'mark_as_accepted' );
								} elseif ( $status == 'paid' ) {
									$actions['mark_as_unpaid'] = sprintf( '<a href="%s&action=%s">' . __( 'Mark as Unpaid', 'eddc' ) . '</a>', $base, 'mark_as_unpaid' );
								} else {
									$actions['mark_as_paid'] = sprintf( '<a href="%s&action=%s">' . __( 'Mark as Paid', 'eddc' ) . '</a>', $base, 'mark_as_paid' );
									$actions['mark_as_revoked'] = sprintf( '<a href="%s&action=%s">' . __( 'Revoke', 'eddc' ) . '</a>', $base, 'mark_as_revoked' );
								}

								$actions = apply_filters( 'eddc_commission_details_actions', $actions, $commission_id );

								if ( ! empty( $actions ) ) {
									$count = count( $actions );
									$i     = 1;

									foreach ( $actions as $action ) {
										echo $action;

										if ( $i < $count ) {
											echo '&nbsp;|&nbsp;';
											$i++;
										}
									}
								} else {
									_e( 'No actions available for this commission', 'eddc' );
								}
								?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div id="item-edit-actions" class="edit-item" style="float: right; margin: 10px 0 0; display: block;">
				<?php wp_nonce_field( 'eddc_update_commission', 'eddc_update_commission_nonce' ); ?>
				<input type="submit" name="eddc_update_commission" id="eddc_update_commission" class="button button-primary" value="<?php _e( 'Update Commission', 'eddc' ); ?>" />
				<input type="hidden" name="commission_id" value="<?php echo absint( $commission_id ); ?>" />
			</div>
			<div class="clear"></div>
		</form>
	</div>

	<?php
	do_action( 'eddc_commission_card_bottom', $commission_id );
}


/**
 * Delete a commission
 *
 * @since       3.3
 * @param       object $commission The commission being deleted
 * @return      void
 */
function eddc_commissions_delete_view( $commission ) {
	if ( ! $commission ) {
		echo '<div class="info-wrapper item-section">' . __( 'Invalid commission specified.', 'eddc' ) . '</div>';
		return;
	}

	$commission_id = $commission->ID;
	?>

	<div class="eddc-commission-delete-header">
		<span><?php printf( __( 'Commission ID: %s', 'eddc' ), $commission_id ); ?></span>
	</div>

	<?php do_action( 'eddc_commissions_before_commission_delete', $commission_id ); ?>

	<form id="delete-commission" method="post" action="<?php echo admin_url( 'edit.php?post_type=download&page=edd-commissions&view=delete&commission=' . $commission_id ); ?>">
		<div class="edd-item-info delete-commission">
			<span class="delete-commission-options">
				<p>
					<?php echo EDD()->html->checkbox( array( 'name' => 'eddc-commission-delete-comfirm' ) ); ?>
					<label for="eddc-commission-delete-comfirm"><?php _e( 'Are you sure you want to delete this commission?', 'eddc' ); ?></label>
				</p>

				<?php do_action( 'eddc_commissions_delete_inputs', $commission_id ); ?>
			</span>

			<span id="commission-edit-actions">
				<input type="hidden" name="commission_id" value="<?php echo $commission_id; ?>" />
				<?php wp_nonce_field( 'delete-commission', '_wpnonce', false, true ); ?>
				<input type="hidden" name="edd_action" value="delete_commission" />
				<input type="submit" disabled="disabled" id="eddc-delete-commission" class="button-primary" value="<?php _e( 'Delete Commission', 'eddc' ); ?>" />
				<a id="eddc-delete-commission-cancel" href="<?php echo admin_url( 'edit.php?post_type=download&page=edd-commissions&view=overview&commission=' . $commission_id ); ?>" class="delete"><?php _e( 'Cancel', 'eddc' ); ?></a>
			</span>
		</div>
	</form>

	<?php do_action( 'eddc_commissions_after_commission_delete', $commission_id );
}
