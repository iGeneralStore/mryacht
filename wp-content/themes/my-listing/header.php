<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php esc_attr( bloginfo( 'charset' ) ) ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="pingback" href="<?php esc_attr( bloginfo( 'pingback_url' ) ) ?>">

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php

/**
 * Action hook immediately after the opening <body> tag.
 *
 * @since 1.6.6
 */
do_action( 'mylisting/body/start' );

$pageTop = apply_filters('case27_pagetop_args', [
	'header' => [
		'show' => true,
		'args' => [],
	],

	'title-bar' => [
		'show' => c27()->get_setting('header_show_title_bar', false),
		'args' => [
			'title' => get_the_archive_title(),
			'ref' => 'default-title-bar',
		],
	]
]);

if ($pageTop['header']['show']) {
	c27()->get_section('header', $pageTop['header']['args']);

	if ($pageTop['title-bar']['show']) {
		c27()->get_section('title-bar', $pageTop['title-bar']['args']);
	}
}
