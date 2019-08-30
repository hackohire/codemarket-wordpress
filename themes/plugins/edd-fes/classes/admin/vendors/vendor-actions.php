<?php
/**
 * Vendor Profile Save Functions
 *
 * This file contains functions
 * used to save the profile and
 * notes on the vendor admin profile
 * pages.
 *
 * @package FES
 * @subpackage Administration
 * @since 2.3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { 
	exit;
}

/**
 * Processes a custom edit.
 *
 * When the edit vendor button is pushed on the 
 * vendor admin profile, this function does the
 * actual saving.
 *
 * @since 2.3.0
 * @access public
 *
 * @param array $args Data to save.
 * @return array Response messages
 */
function fes_edit_vendor( $args ) {
	if ( ! fes_is_admin() || ! EDD_FES()->vendors->user_is_admin() ) {
		wp_die( sprintf( __( 'You do not have permission to edit this %s', 'edd_fes' ), EDD_FES()->helper->get_vendor_constant_name( $plural = false, $uppercase = false ) ) );
	}

	if ( empty( $args ) ) {
		return;
	}

	$vendor_info = $args['vendorinfo'];
	$vendor_id   = (int)$args['vendorinfo']['id'];
	$nonce       = $args['_wpnonce'];

	if ( ! wp_verify_nonce( $nonce, 'edit-vendor' ) ) {
		wp_die( __( 'Cheatin\' eh?!', 'edd_fes' ) );
	}

	$vendor = new FES_Vendor( $vendor_id );

	if ( empty( $vendor->id ) ) {
		return false;
	}

	$defaults = array(
		'name'    => '',
		'email'   => '',
		'user_id' => 0
	);

	$vendor_info = wp_parse_args( $vendor_info, $defaults );

	if ( ! is_email( $vendor_info['email'] ) ) {
		edd_set_error( 'edd-invalid-email', __( 'Please enter a valid email address.', 'edd_fes' ) );
	}

	if ( (int) $vendor_info['user_id'] != (int) $vendor->user_id ) {

		// Make sure we don't already have this user attached to a vendor
		if ( ! empty( $vendor_info['user_id'] ) && false !== EDD()->vendors->get_vendor_by( 'user_id', $vendor_info['user_id'] ) ) {
			edd_set_error( 'edd-invalid-vendor-user_id', sprintf( _x( 'The User ID %d is already associated with a different %s.', 'Vendor ID number', 'edd_fes' ), $vendor_info['user_id'], EDD_FES()->helper->get_vendor_constant_name( $plural = false, $uppercase = false ) ) );
		}

		// Make sure it's actually a user
		$user = get_user_by( 'id', $vendor_info['user_id'] );
		if ( ! empty( $vendor_info['user_id'] ) && false === $user ) {
			edd_set_error( 'edd-invalid-user_id', sprintf( _x( 'The User ID %d does not exist. Please assign an existing %s.', '1: Vendor ID number, 2: FES setting for vendor lowercase singular', 'edd_fes' ), $vendor_info['user_id'], EDD_FES()->helper->get_vendor_constant_name( $plural = false, $uppercase = false ) ) );
		}

	}

	// Record this for later
	$previous_user_id  = $vendor->user_id;

	if ( edd_get_errors() ) {
		return;
	}

	// Setup the vendor address, if present
	$address = array();
	if ( intval( $vendor_info['user_id'] ) > 0 ) {

		$current_address = get_user_meta( $vendor_info['user_id'], '_fes_vendor_address', true );

		if ( empty( $current_address ) ) {
			$address['line1']   = isset( $vendor_info['line1'] )   ? $vendor_info['line1']   : '';
			$address['line2']   = isset( $vendor_info['line2'] )   ? $vendor_info['line2']   : '';
			$address['city']    = isset( $vendor_info['city'] )    ? $vendor_info['city']    : '';
			$address['country'] = isset( $vendor_info['country'] ) ? $vendor_info['country'] : '';
			$address['zip']     = isset( $vendor_info['zip'] )     ? $vendor_info['zip']     : '';
			$address['state']   = isset( $vendor_info['state'] )   ? $vendor_info['state']   : '';
		} else {
			$current_address    = wp_parse_args( $current_address, array( 'line1', 'line2', 'city', 'zip', 'state', 'country' ) );
			$address['line1']   = isset( $vendor_info['line1'] )   ? $vendor_info['line1']   : $current_address['line1']  ;
			$address['line2']   = isset( $vendor_info['line2'] )   ? $vendor_info['line2']   : $current_address['line2']  ;
			$address['city']    = isset( $vendor_info['city'] )    ? $vendor_info['city']    : $current_address['city']   ;
			$address['country'] = isset( $vendor_info['country'] ) ? $vendor_info['country'] : $current_address['country'];
			$address['zip']     = isset( $vendor_info['zip'] )     ? $vendor_info['zip']     : $current_address['zip']    ;
			$address['state']   = isset( $vendor_info['state'] )   ? $vendor_info['state']   : $current_address['state']  ;
		}

	}

	// Sanitize the inputs
	$vendor_data            = array();
	$vendor_data['name']    = $vendor_info['name'];
	$vendor_data['email']   = $vendor_info['email'];
	$vendor_data['user_id'] = $vendor_info['user_id'];

	/**
	 * Vendor profile data.
	 *
	 * Filters the vendor profile data
	 * being saved on the admin vendor
	 * profile.
	 *
	 * @since 2.3.0
	 *
	 * @param  int $vendor_id The vendor ID of the vendor being edited.
	 * @param  array $vendor_data The vendor data being saved.
	 */
	$vendor_data   = apply_filters( 'fes_edit_vendor_info', $vendor_data, $vendor_id );

	/**
	 * Vendor profile address.
	 *
	 * Filters the vendor address being
	 * saved on the admin vendor profile.
	 *
	 * @since 2.3.0
	 *
	 * @param  int $vendor_id The vendor ID of the vendor being edited.
	 * @param  array $address The vendor address being saved.
	 */	
	$address       = apply_filters( 'fes_edit_vendor_address', $address, $vendor_id );

	$vendor_data   = array_map( 'sanitize_text_field', $vendor_data );
	$address       = array_map( 'sanitize_text_field', $address );

	/**
	 * Before vendor profile save action.
	 *
	 * Before vendor profile save action which
	 * runs on the admin vendor profile.
	 *
	 * @since 2.3.0
	 *
	 * @param  int $vendor_id The vendor ID of the vendor being edited.
	 * @param  array $vendor_data The vendor data being saved.
	 * @param  array $address The vendor address being saved.
	 */
	do_action( 'edd_pre_edit_vendor', $vendor_id, $vendor_data, $address );

	$output         = array();
	$previous_email = $vendor->email;

	if ( $vendor->update( $vendor_data ) ) {

		if ( ! empty( $vendor->user_id ) && $vendor->user_id > 0 ) {
			update_user_meta( $vendor->user_id, '_fes_vendor_address', $address );
		}

		update_user_meta( $vendor->user_id, '_fes_automatic_approval', (bool) esc_attr( $vendor_info['automatic_approval'] ) );

		$output['success']     = true;
		$vendor_data           = array_merge( $vendor_data, $address );
		$output['vendor_info'] = $vendor_data;

	} else {
		$output['success'] = false;
	}

	/**
	 * After vendor profile save action.
	 *
	 * After vendor profile save action which
	 * runs on the admin vendor profile.
	 *
	 * @since 2.3.0
	 *
	 * @param  int $vendor_id The vendor ID of the vendor being edited.
	 * @param  array $vendor_data The vendor data saved.
	 */
	do_action( 'edd_post_edit_vendor', $vendor_id, $vendor_data );

	if ( fes_is_ajax_request() ) {
		header( 'Content-Type: application/json' );
		echo json_encode( $output );
		wp_die();
	}

	return $output;

}
add_action( 'edd_edit-vendor', 'fes_edit_vendor', 10, 1 );

/**
 * Save a vendor note being added.
 *
 * This function is used to save
 * notes about vendors made on the 
 * admin vendor profile pages.
 *
 * @since 2.3.0
 * @access public
 *
 * @param array $args The $_POST array being passed.
 * @return int The Note ID that was saved, or 0 if nothing was saved.
 */
function fes_vendor_save_note( $args ) {

	if ( ! fes_is_admin() || ! EDD_FES()->vendors->user_is_admin() ) {
		wp_die( sprintf( _x( 'You do not have permission to edit this %s', 'FES singular lowercase setting for vendor', 'edd_fes' ), EDD_FES()->helper->get_vendor_constant_name( $plural = false, $uppercase = false ) ) );
	}

	if ( empty( $args ) ) {
		return;
	}

	$vendor_note = trim( sanitize_text_field( $args['vendor_note'] ) );
	$vendor_id   = (int)$args['vendor_id'];
	$nonce       = $args['add_vendor_note_nonce'];

	if ( ! wp_verify_nonce( $nonce, 'add-vendor-note' ) ) {
		wp_die( __( 'Cheatin\' eh?!', 'edd_fes' ) );
	}

	if ( empty( $vendor_note ) ) {
		edd_set_error( 'empty-vendor-note', __( 'A note is required', 'edd_fes' ) );
	}

	if ( edd_get_errors() ) {
		return;
	}

	$vendor   = new FES_Vendor( $vendor_id );
	$new_note = $vendor->add_note( $vendor_note );

	/**
	 * Before vendor note save action.
	 *
	 * Before vendor note save action which
	 * runs on the admin vendor profile.
	 *
	 * @since 2.3.0
	 *
	 * @param  int $vendor_id The vendor ID of the vendor being edited.
	 * @param  string $new_note The vendor note being saved.
	 */
	do_action( 'edd_pre_insert_vendor_note', $vendor_id, $new_note );

	if ( ! empty( $new_note ) && ! empty( $vendor->id ) ) {

		ob_start(); ?>

		<div class="vendor-note-wrapper dashboard-comment-wrap comment-item">
			<span class="note-content-wrap">
				<?php echo stripslashes( $new_note ); ?>
			</span>
		</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		if ( fes_is_ajax_request() ) {
			echo $output;
			exit;
		}

		return $new_note;

	}
	return false;
}
add_action( 'edd_add-vendor-note', 'fes_vendor_save_note', 10, 1 );

/**
 * Delete a vendor.
 *
 * @since 2.6
 *
 * @param  array $args The $_POST array being passeed
 *
 * @return int Whether it was a successful deletion
 */
function fes_delete_vendor( $args ) {
	if ( ! EDD_FES()->vendors->user_is_admin() ) {
		wp_die( __( 'You do not have permission to delete this vendor.', 'easy-digital-downloads' ) );
	}

	if ( empty( $args ) ) {
		return;
	}

	$vendor_id = (int) $args['vendor_id'];

	$confirm        = ! empty( $args['fes-vendor-delete-confirm'] ) ? true : false;
	$remove_data    = ! empty( $args['fes-delete-option'] ) && 'delete' === $args['fes-delete-option'] ? true : false;
	$attribute_data = ! empty( $args['fes-delete-option'] ) && 'other' === $args['fes-delete-option'] && ! empty( $args['fes-user-list'] ) ? $args['fes-user-list'] : false;
	$nonce          = $args['_wpnonce'];

	if ( ! wp_verify_nonce( $nonce, 'fes_delete_vendor' ) ) {
		wp_die( __( 'Cheatin\' eh?!', 'edd_fes' ) );
	}

	if ( ! $confirm ) {
		edd_set_error( 'vendor-delete-no-confirm', __( 'Please confirm you want to delete this vendor', 'edd_fes' ) );
	}

	if ( ! $remove_data && ! $attribute_data ) {
		edd_set_error( 'vendor-delete-method', __( 'Please confirm what should be done with the products owned by this vendor', 'edd_fes' ) );
	}

	if ( edd_get_errors() ) {
		wp_redirect( admin_url( 'admin.php?page=fes-vendors&view=overview&id=' . $vendor_id ) );
		exit;
	}

	$vendor = new FES_Vendor( $vendor_id );

	do_action( 'fes_pre_delete_vendor', $vendor_id, $confirm, $remove_data, $attribute_data );

	if ( $vendor->id > 0 ) {
		$db = new FES_DB_Vendors;

		$success = $db->delete( $vendor->id );

		$success = true;

		if ( $success ) {
			if ( $remove_data ) {
				$args  = array(
					'post_type'      => 'download',
					'author'         => $vendor->user_id,
					'posts_per_page' => -1,
					'fields'         => 'ids',
					'post_status'    => 'any',
				);

				$query = new WP_Query( $args );

				foreach ( $query->posts as $id ) {
					wp_delete_post( $id, false );
				}

				$redirect = admin_url( 'admin.php?page=fes-vendors' );
			} elseif ( $attribute_data > 0 ) {
				$args  = array(
					'post_type'      => 'download',
					'author'         => $vendor->user_id,
					'posts_per_page' => -1,
					'fields'         => 'ids',
					'post_status'    => 'any',
				);

				$query = new WP_Query( $args );

				foreach ( $query->posts as $id ) {
					wp_update_post( array(
						'ID'          => $id,
						'post_author' => $attribute_data,
					) );
				}

				$redirect = admin_url( 'admin.php?page=fes-vendors' );
			} else {
				$redirect = admin_url( 'admin.php?page=fes-vendors&view=delete&id=' . $vendor_id );
				edd_set_error( 'fes-vendor-delete-failed', __( 'Error deleting vendor', 'edd_fes' ) );
			}
		} else {
			$redirect = admin_url( 'admin.php?page=fes-vendors&view=delete&id=' . $vendor_id );
			edd_set_error( 'fes-vendor-invalid-id', __( 'Invalid vendor ID', 'edd_fes' ) );
		}
	}

	wp_redirect( $redirect );
	exit;
}
add_action( 'edd_fes_delete_vendor', 'fes_delete_vendor', 10, 1 );