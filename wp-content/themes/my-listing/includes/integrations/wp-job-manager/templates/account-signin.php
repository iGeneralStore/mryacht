<?php
/**
 * In listing creation flow, this template shows above the creation form.
 *
 * @since 1.6.3
 */
?>
<?php if ( is_user_logged_in() ) : ?>

	<fieldset class="fieldset-logged_in">
		<label><?php _e( 'Your account', 'my-listing' ); ?></label>
		<div class="field account-sign-in">
			<?php
				$user = wp_get_current_user();
				printf( __( 'You are currently signed in as <strong>%s</strong>.', 'my-listing' ), $user->user_login );
			?>

			<a class="button" href="<?php echo apply_filters( 'submit_job_form_logout_url', wp_logout_url( get_permalink() ) ); ?>"><?php _e( 'Sign out', 'my-listing' ); ?></a>
		</div>
	</fieldset>

<?php else: ?>
	<?php
	$account_required = job_manager_user_requires_account();
	if ( $account_required ) {
		$message = __( 'You must be logged in to post new listings.', 'my-listing' );
	} elseif ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) {
		$message = __( 'You can login to your existing account or create a new one using the buttons below.', 'my-listing' );
	} else {
		$message = __( 'If you already have an account, you can login using the button below.', 'my-listing' );
	}
	?>

	<fieldset class="fieldset-login_required">
		<p><?php echo $message ?></p>
		<p>
			<a href="#" data-toggle="modal" data-target="#sign-in-modal" class="buttons button-5">
				<i class="mi perm_identity"></i>
				<?php _e( 'Sign in', 'my-listing' ) ?>
			</a>
			<?php if (get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes'): ?>
				<span><?php _e( 'or', 'my-listing' ) ?></span>
				<a href="#" data-toggle="modal" data-target="#sign-up-modal" class="buttons button-5">
					<i class="mi perm_identity"></i>
					<?php _e( 'Register', 'my-listing' ) ?>
				</a>
			<?php endif ?>
		</p>
	</fieldset>

<?php endif ?>
