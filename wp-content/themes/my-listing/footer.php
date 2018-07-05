<?php if ( isset( $GLOBALS['c27_elementor_page'] ) && $page = $GLOBALS['c27_elementor_page'] ) {
	if ( ! $page->get_settings('c27_hide_footer') ) {
		$args = [
			'show_widgets'      => $page->get_settings('c27_footer_show_widgets'),
			'show_footer_menu'  => $page->get_settings('c27_footer_show_footer_menu'),
		];

		c27()->get_section('footer', ($page->get_settings('c27_customize_footer') == 'yes' ? $args : []));
	}
} else {
	c27()->get_section('footer');
}
?>

<?php wp_footer() ?>

</body>
</html>