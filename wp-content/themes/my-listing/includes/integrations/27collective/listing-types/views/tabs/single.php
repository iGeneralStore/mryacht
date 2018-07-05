<?php

$cover_buttons = apply_filters( 'case27\types\cover_buttons', [
	'custom-field' => [
		'label' => 'Show a field\'s value',
		'action' => 'custom-field',
	],

	'share' => [
		'label' => 'Share Listing',
		'action' => 'share',
	],

	'bookmark' => [
		'label' => 'Bookmark Listing',
		'action' => 'bookmark',
	],

	'add-review' => [
		'label' => 'Add Review',
		'action' => 'add-review',
	],

	'book' => [
		'label' => 'Book',
		'action' => 'book',
	],

	'display-rating' => [
		'label' => 'Display Rating',
		'action' => 'display-rating',
	],
] );
?>

<div class="sub-tabs">
	<ul>
		<li :class="currentTab == 'single-page' && currentSubTab == 'style' ? 'active' : ''" class="single-page-tab-style">
			<a @click.prevent="setTab('single-page', 'style')">Cover style</a>
		</li>
		<li :class="currentTab == 'single-page' && currentSubTab == 'buttons' ? 'active' : ''" class="single-page-tab-buttons">
			<a @click.prevent="setTab('single-page', 'buttons')">Cover buttons</a>
		</li>
		<li :class="currentTab == 'single-page' && currentSubTab == 'pages' ? 'active' : ''" class="single-page-tab-pages">
			<a @click.prevent="setTab('single-page', 'pages')">Content &amp; Tabs</a>
		</li>
	</ul>
</div>

<div class="tab-content">
	<input type="hidden" v-model="single_page_options_json_string" name="case27_listing_type_single_page_options">

	<div class="single-page-tab-style-content" v-show="currentSubTab == 'style'">
		<?php require_once CASE27_INTEGRATIONS_DIR . '/27collective/listing-types/views/tabs/single-style.php' ?>
	</div>

	<div class="single-page-tab-buttons-content" v-show="currentSubTab == 'buttons'">
		<?php require_once CASE27_INTEGRATIONS_DIR . '/27collective/listing-types/views/tabs/single-buttons.php' ?>
	</div>

	<div class="single-page-tab-pages-content" v-show="currentSubTab == 'pages'">
		<?php require_once CASE27_INTEGRATIONS_DIR . '/27collective/listing-types/views/tabs/single-pages.php' ?>
	</div>
</div>

<!-- <pre>{{ single.menu_items[0] }}</pre> -->
