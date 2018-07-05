<?php

global $thepostid;

if ( ! isset( $field['value'] ) ) {
	$field['value'] = get_post_meta( $thepostid, $key, true );
}

if ( empty( $field['name'] ) ) {
	$field['name'] = $key;
}

$_REQUEST[ 'job_id' ] = $thepostid;
?>

<div class="form-field">
	<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>: <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
	<?php require trailingslashit( CASE27_INTEGRATIONS_DIR ) . "wp-job-manager/templates/form-fields/location-field.php"; ?>
</div>