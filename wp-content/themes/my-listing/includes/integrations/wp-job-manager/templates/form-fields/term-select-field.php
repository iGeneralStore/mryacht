<?php
/**
 * Term select field frontend template. If 'terms-template' option is provided,
 * display terms in the requested template. Default to 'multiselect' template.
 *
 * @since 1.5.1
 */

$listing_id = ! empty( $_REQUEST[ 'job_id' ] ) ? absint( $_REQUEST[ 'job_id' ] ) : 0;
$type_slug  = ! empty( $_GET['listing_type'] ) ? sanitize_text_field( $_GET['listing_type'] ) : false;
$type_id    = 0;

// In submit listing form, get the active listing type from the url.
if ( $type_slug && ( $type = get_page_by_path( $type_slug, OBJECT, 'case27_listing_type' ) ) ) {
	$type_id = $type->ID;
}

// In edit listing form, get the active listing type from the post meta.
if ( $listing_id && ( $type = get_page_by_path( get_post_meta( $listing_id, '_case27_listing_type', true ), OBJECT, 'case27_listing_type' ) ) ) {
	$type_id = $type->ID;
}

// Get the list of all terms that belong to this taxonomy and listing type.
$args = [
	'taxonomy' => $field['taxonomy'],
	'orderby'    => 'name',
	'order'      => 'ASC',
	'hide_empty' => false,
	'meta_query' => [
		'relation' => 'OR',
		[
			'key' => 'listing_type',
			'value' => '"' . $type_id . '"',
			'compare' => 'LIKE',
		],
		[
			'key' => 'listing_type',
			'value' => '',
		],
		[
			'key' => 'listing_type',
			'compare' => 'NOT EXISTS',
		]
	],
];

// If available, fetch terms from cache.
$cache_version = get_option( sprintf( 'listings_tax_%s_version', $field['taxonomy'] ), 100 );
$terms_hash = sprintf( 'c27_cats_%s_v%s', md5( json_encode( $args ) ), $cache_version );
$raw_terms = get_transient( $terms_hash );

if ( empty( $raw_terms ) ) {
    $raw_terms = get_terms( $args );
    set_transient( $terms_hash, $raw_terms, HOUR_IN_SECONDS * 6 );
    // dump( 'Loaded via db query' );
} else {
    // dump( 'Loaded from cache' );
}

if ( is_wp_error( $raw_terms ) ) {
	return false;
}

// Sort terms in alphabetical order.
$terms = [];
MyListing\Src\Term::iterate_recursively(
    function( $term, $depth ) use ( &$terms ) {
        $term->depth = $depth;
        $terms[] = $term;
    },
    MyListing\Src\Term::get_term_tree( $raw_terms )
);

// dd($terms);

// Get selected value
if ( isset( $field['value'] ) ) {
	$selected = (array) $field['value'];
} elseif (  ! empty( $field['default'] ) && is_int( $field['default'] ) ) {
	$selected = (array) $field['default'];
} elseif ( ! empty( $field['default'] ) && ( $term = get_term_by( 'slug', $field['default'], $field['taxonomy'] ) ) ) {
	$selected = (array) $term->term_id;
} else {
	$selected = [];
}

if ( ! empty( $field['terms-template'] ) && ( $template = locate_job_manager_template( "form-fields/term-{$field['terms-template']}-field.php" ) ) ) {
	require $template;
} else {
	require locate_job_manager_template( 'form-fields/term-multiselect-field.php' );
}
