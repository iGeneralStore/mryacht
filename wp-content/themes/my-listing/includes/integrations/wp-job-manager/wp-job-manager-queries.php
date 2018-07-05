<?php
/**
 * WP Job Manager Queries.
 */

class CASE27_WP_Job_Manager_Queries {
    use \MyListing\Src\Traits\Instantiatable;

	public function __construct() {
		// Listing quick view.
		add_action( "wp_ajax_get_listing_quick_view", [ $this, 'get_listing_quick_view' ] );
		add_action( "wp_ajax_nopriv_get_listing_quick_view", [ $this, 'get_listing_quick_view' ] );
	}

	public function get_listing_quick_view() {
		if (!isset($_REQUEST['listing_id']) || !$_REQUEST['listing_id']) return;

		$listing = get_post(absint((int) $_REQUEST['listing_id']));

		if (!$listing || $listing->post_type !== 'job_listing') return;

		ob_start();

		c27()->get_partial('listing-quick-view', [
			'listing' => $listing,
			]);

		return wp_send_json( [
			'html' => ob_get_clean(),
		] );
	}

	public function promoted_first_clause() {
		return [
			'relation' => 'OR',
			[
				'key' => '_case27_listing_promotion_end_date',
				'compare' => 'NOT EXISTS',
			],
			[
				'key' => '_case27_listing_promotion_end_date',
				'value' => date('Y-m-d H:i:s'),
				'compare' => '<',
				'type' => 'DATETIME',
			],
			[
				'relation' => 'AND',
				[
					'key' => '_case27_listing_promotion_start_date',
					'value' => date('Y-m-d H:i:s'),
					'compare' => '<=',
					'type' => 'DATETIME',
				],
				'c27_promoted_clause_end_date' => [
					'key' => '_case27_listing_promotion_end_date',
					'value' => date('Y-m-d H:i:s'),
					'compare' => '>=',
					'type' => 'DATETIME',
				],
			],
		];
	}

	public function promoted_only_clause() {
		return [
			'relation' => 'AND',
			[
				'key' => '_case27_listing_promotion_start_date',
				'value' => date('Y-m-d H:i:s'),
				'compare' => '<=',
				'type' => 'DATETIME',
			],
			[
				'key' => '_case27_listing_promotion_end_date',
				'value' => date('Y-m-d H:i:s'),
				'compare' => '>=',
				'type' => 'DATETIME',
			],
		];
	}

	public function hide_promoted_clause() {
		return [
			'relation' => 'OR',
			[
				'key' => '_case27_listing_promotion_start_date',
				'value' => date('Y-m-d H:i:s'),
				'compare' => '>',
				'type' => 'DATETIME',
			],
			[
				'key' => '_case27_listing_promotion_end_date',
				'value' => date('Y-m-d H:i:s'),
				'compare' => '<',
				'type' => 'DATETIME',
			],
			[
				'key' => '_case27_listing_promotion_end_date',
				'compare' => 'NOT EXISTS',
			],
		];
	}
}

new CASE27_WP_Job_Manager_Queries;