<?php

namespace MyListing\Includes;

class Ajax {
    use \MyListing\Src\Traits\Instantiatable;

	protected $actions = [
		'get_icon_packs',
	];

	public function __construct() {
		$this->register_actions();
	}

	public function register_action( $action ) {
		if (!in_array($action, $this->actions)) {
			$this->actions[] = $action;
		}
	}

	public function register_actions() {
		foreach ($this->actions as $action) {
			add_action( "wp_ajax_$action", array($this, $action) );
			add_action( "wp_ajax_nopriv_$action", array($this, $action) );
		}
	}

	public function get_icon_packs() {
		if ( ! is_user_logged_in() ) {
			return;
		}

		$font_awesome_icons = require CASE27_INTEGRATIONS_DIR . '/27collective/icons/font-awesome.php';
		$material_icons = require CASE27_INTEGRATIONS_DIR . '/27collective/icons/material-icons.php';

		echo json_encode( [
			'font-awesome'   => array_map(function($icon) { return "fa {$icon}"; }, array_values($font_awesome_icons)),
			'material-icons' => array_map(function($icon) { return "mi {$icon}"; }, array_values($material_icons)),
			'theme-icons'    => array_values(require CASE27_INTEGRATIONS_DIR . '/27collective/icons/theme-icons.php'),
		] );
		die;
	}
}
