<?php

namespace MyListing\Ext\Admin_Tips;

class Admin_Tips {
    use \MyListing\Src\Traits\Instantiatable;

    protected $tips = [
    	'bracket-syntax',
    ];

	public function __construct() {
		if ( ! is_user_logged_in() || ! is_admin() ) {
			return false;
		}

		// Register ajax route.
		add_action( 'wp_ajax_cts_get_tip', [ $this, 'get_tip' ] );

		// Output tip html wrapper.
        add_action( 'admin_footer', [ $this, 'output_template' ] );
	}

	/**
	 * Handle cts_get_tip Ajax request.
	 *
	 * @since  1.6.6
	 */
	public function get_tip() {
		// Validate request.
		if ( ! is_user_logged_in() || empty( $_GET['tip'] ) ) {
			return false;
		}

		$tip = sanitize_text_field( $_GET['tip'] );
		if ( ! in_array( $tip, $this->tips ) ) {
			return false;
		}

		// Valid, send html.
		include sprintf( '%s/includes/extensions/admin-tips/tips/%s.php', CASE27_THEME_DIR, $tip );
		exit;
	}

	/**
	 * Display tip wrapper markup in wp-admin footer.
	 *
	 * @since  1.6.6
	 */
	public function output_template() { ?>
		<div class="cts-tip-wrapper">
			<div class="cts-tip-container">
				<div class="tip-content"></div>
				<div class="tip-footer">
					<div class="button button-primary close-dialog">Got it!</div>
				</div>
			</div>

			<?php c27()->get_partial( 'spinner', [
				'color' => '#fff',
				'size' => 24,
				'width' => 2.5,
			] ) ?>
		</div>
	<?php }
}