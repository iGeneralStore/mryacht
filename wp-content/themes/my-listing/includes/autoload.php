<?php
/*
 * Autoload classes.
 *
 * MyListing\Includes -> my-listing/includes
 * MyListing\Src      -> my-listing/includes/classes
 * @todo MyListing\Ext -> my-listing/includes/integrations
 */
spl_autoload_register( function( $classname ) {
	$parts = explode( '\\', $classname );

	if ( $parts[0] !== 'MyListing' ) {
		return false;
	}

	if ( $parts[1] === 'Src' ) {
		$parts[1] = 'Includes' . DIRECTORY_SEPARATOR . 'Classes';
	}

	if ( $parts[1] === 'Ext' ) {
		$parts[1] = 'Includes' . DIRECTORY_SEPARATOR . 'Extensions';
	}

	$path_parts = array_map( function( $part) {
		return strtolower( str_replace( '_', '-', $part ) );
	}, $parts );

	// unset my-listing path part since that's already known.
	unset( $path_parts[0] );

	$path = join( DIRECTORY_SEPARATOR, $path_parts ) . '.php';

	if ( locate_template( $path ) ) {
		require_once locate_template( $path );
	}
} );

// Manual load.
$classes = [
	'integrations/advanced-custom-fields/plugin/acf.php',
	'helpers.php',
	'init.php',
	'plugins/activator.php',

	// Integrations.
	// @todo: Convert integrations to autoloadable namespace format.
	'integrations/advanced-custom-fields/acf.php',
	'integrations/wp-job-manager-wc-paid-listings/wp-job-manager-wc-paid-listings.php',
	'integrations/27collective/listing-types/designer.php',
	'integrations/wp-job-manager/wp-job-manager.php',
	'integrations/woocommerce/woocommerce.php',
	'integrations/buddypress/buddypress.php',
	'integrations/elementor/elementor.php',
	'integrations/contact-form-7/contact-form-7.php',
	'integrations/27collective/share/share.php',
	'integrations/27collective/bookmarks/bookmark.php',
	'integrations/27collective/reviews/review.php',
	'integrations/27collective/breadcrumbs/breadcrumbs.php',
	'integrations/27collective/search/search.php',
	'integrations/27collective/promotions/promotions.php',
	'integrations/27collective/dashboard-pages/pages.php',
	'integrations/27collective/listing-types/listing-type.php',
	'integrations/27collective/reports/reports.php',
	'integrations/27collective/product-vendors/vendors.php',
	'integrations/27collective/claims/claims.php',
	'integrations/27collective/paid-listings/paid-listings.php',
];

foreach ( $classes as $classPath ) {
	require_once locate_template( "includes/{$classPath}" );
}