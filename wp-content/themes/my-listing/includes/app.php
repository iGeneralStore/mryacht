<?php

namespace MyListing\Includes;

class App {
	use \MyListing\Src\Traits\Instantiatable;

	private $classes;

	public function register( $name, $instance ) {
		$this->classes[ $name ] = $instance;
	}

	public function __call( $method, $params ) {
		if ( isset( $this->classes[ $method ] ) ) {
			return $this->classes[ $method ];
		}

		return null;
	}
}
