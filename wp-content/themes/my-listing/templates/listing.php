<?php

global $post;

if ( ! class_exists( 'WP_Job_Manager' ) ) {
    return;
}

$listing = MyListing\Src\Listing::get( $post );

if ( ! $listing->type ) {
    return;
}

// Get the layout blocks for the single listing page.
$layout = $listing->type->get_layout();
$fields = $listing->type->get_fields();
$tagline = $listing->get_field( 'tagline' );

$listing_logo = $listing->get_logo( 'medium' );
// $listing_logo = job_manager_get_resized_image( $listing->get_field( 'job_logo' ), 'medium' );

$listing_menu_classes = sprintf( 'cts-%s-font cts-font-weight-%s', c27()->get_setting( 'single_listing_menu_font_size', 'default' ), c27()->get_setting( 'single_listing_menu_font_weight', 'normal' ) );
$content_block_classes = sprintf(
    'cts-%s-font cts-font-weight-%s cts-content-%s-font',
    c27()->get_setting('single_listing_content_block_title_size', 'default' ),
    c27()->get_setting( 'single_listing_content_block_title_weight', 'normal' ),
    c27()->get_setting( 'single_listing_content_block_font_size', 'default' )
);
$GLOBALS['case27_custom_styles'] .= sprintf( ' body.single-listing .title-style-1 i { color: %s; } ', c27()->get_setting( 'single_listing_content_block_icon_color', '#c7cdcf' ) );
?>

<!-- SINGLE LISTING PAGE -->
<div class="single-job-listing <?php echo ! $listing_logo ? 'listing-no-logo' : '' ?>" id="c27-single-listing">
    <input type="hidden" id="case27-post-id" value="<?php echo esc_attr( get_the_ID() ) ?>">
    <input type="hidden" id="case27-author-id" value="<?php echo esc_attr( get_the_author_meta('ID') ) ?>">

    <!-- <section> opening tag is omitted -->
    <?php
    /**
     * Cover section.
     */
    $cover_template_path = sprintf( 'partials/single/cover/%s.php', $layout['cover']['type'] );
    if ( $cover_template = locate_template( $cover_template_path ) ) {
        require $cover_template;
    } else {
        require locate_template( 'partials/single/cover/none.php' );
    }

    /**
     * Cover buttons.
     */
    require locate_template( 'partials/single/buttons/buttons.php' );
    ?>
    </section>

    <div class="profile-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div v-pre>
                        <?php if ( $listing_logo ):
                            $listing_logo_large = $listing->get_logo( 'full' ); ?>
                            <a class="profile-avatar open-photo-swipe"
                               href="<?php echo esc_url( $listing_logo_large ) ?>"
                               style="background-image: url('<?php echo esc_url( $listing_logo ) ?>')"
                               >
                            </a>
                        <?php endif ?>
                    </div>
                    <div class="profile-name <?php echo esc_attr( $tagline ? 'has-tagline' : 'no-tagline' ) ?>" v-pre>
                        <h1 class="case27-primary-text"><?php the_title() ?></h1>
                        <?php if ( $tagline ): ?>
                            <h2 class="profile-tagline listing-tagline-field"><?php echo esc_html( $tagline ) ?></h2>
                        <?php endif ?>
                    </div>
                    <div class="cover-details" v-pre>
                        <ul></ul>
                    </div>
                    <div class="profile-menu <?php echo esc_attr( $listing_menu_classes ) ?>">
                        <ul role="tablist">
                            <?php $i = 0;
                            foreach ((array) $layout['menu_items'] as $key => $menu_item): $i++;
                                if (
                                    $menu_item['page'] == 'bookings' &&
                                    $menu_item['provider'] == 'timekit' &&
                                    ! $listing->get_field( $menu_item['field'] )
                                ) { continue; }

                                ?><li class="<?php echo ($i == 1) ? 'active' : '' ?>">
                                    <a href="<?php echo "#_tab_{$i}" ?>" aria-controls="<?php echo esc_attr( "_tab_{$i}" ) ?>" data-section-id="<?php echo esc_attr( "_tab_{$i}" ) ?>"
                                       role="tab" class="tab-reveal-switch <?php echo esc_attr( "toggle-tab-type-{$menu_item['page']}" ) ?>">
                                        <?php echo esc_html( $menu_item['label'] ) ?>

                                        <?php if ($menu_item['page'] == 'comments'): ?>
                                            <span class="items-counter"><?php echo $listing->get_review_count() ?></span>
                                        <?php endif ?>

                                        <?php if (in_array($menu_item['page'], ['related_listings', 'store'])):
                                            $vue_data_keys = ['related_listings' => 'related_listings', 'store' => 'products'];
                                            ?>
                                            <span class="items-counter" v-if="<?php echo esc_attr( $vue_data_keys[$menu_item['page']] ) ?>['_tab_<?php echo esc_attr( $i ) ?>'].loaded" v-cloak>
                                                {{ <?php echo $vue_data_keys[$menu_item['page']] ?>['_tab_<?php echo $i ?>'].count }}
                                            </span>
                                            <span v-else class="c27-tab-spinner">
                                                <i class="fa fa-circle-o-notch fa-spin"></i>
                                            </span>
                                        <?php endif ?>
                                    </a>
                                </li><?php
                            endforeach; ?>
                            <div id="border-bottom"></div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-content">
        <?php $i = 0; ?>
        <?php foreach ((array) $layout['menu_items'] as $key => $menu_item): $i++; ?>
            <section class="tab-pane profile-body <?php echo ($i == 1) ? 'active' : '' ?> <?php echo esc_attr( "tab-type-{$menu_item['page']}" ) ?>" id="<?php echo esc_attr( "_tab_{$i}" ) ?>" role="tabpanel">

                <?php if ($menu_item['page'] == 'main' || $menu_item['page'] == 'custom'):
                    if ( empty( $menu_item['template'] ) ) {
                        $menu_item['template'] = 'masonry';
                    }

                    if ( empty( $menu_item['layout'] ) ) {
                        $menu_item['layout'] = [];
                    }

                    if ( empty( $menu_item['sidebar'] ) ) {
                        $menu_item['sidebar'] = [];
                    }

                    // Column settings for each page template.
                    if ( $menu_item['template'] == 'two-columns' ) {
                        $columns = [
                            'main-col-wrap' => '<div class="col-md-6"><div class="row cts-column-wrapper cts-main-column">',
                            'main-col-end'  => '</div></div>',
                            'side-col-wrap' => '<div class="col-md-6"><div class="row cts-column-wrapper cts-side-column">',
                            'side-col-end'  => '</div></div>',
                            'block-class'   => 'col-md-12',
                        ];
                    } elseif ( $menu_item['template'] == 'full-width' ) {
                        $columns = [
                            'main-col-wrap' => '',
                            'main-col-end'  => '',
                            'side-col-wrap' => '',
                            'side-col-end'  => '',
                            'block-class'   => 'col-md-12',
                        ];
                    } elseif ( in_array( $menu_item['template'], ['content-sidebar', 'sidebar-content'] ) ) {
                        $columns = [
                            'main-col-wrap' => '<div class="col-md-%d"><div class="row cts-column-wrapper cts-left-column">',
                            'main-col-end'  => '</div></div>',
                            'side-col-wrap' => '<div class="col-md-%d"><div class="row cts-column-wrapper cts-right-column">',
                            'side-col-end'  => '</div></div>',
                            'block-class'   => 'col-md-12',
                        ];

                        $columns['main-col-wrap'] = sprintf( $columns['main-col-wrap'], $menu_item['template'] === 'content-sidebar' ? 7 : 5 );
                        $columns['side-col-wrap'] = sprintf( $columns['side-col-wrap'], $menu_item['template'] === 'content-sidebar' ? 5 : 7 );
                    } else {
                        // Masonry.
                        $columns = [
                            'main-col-wrap' => '',
                            'main-col-end'  => '',
                            'side-col-wrap' => '',
                            'side-col-end'  => '',
                            'block-class'   => 'col-md-6 col-sm-12 col-xs-12 grid-item',
                        ];
                    }

                    // For templates with two columns, merge the other column items into the main column.
                    // And divide them with an 'endcolumn' array item, which will later be used to contruct columns.
                    if ( in_array( $menu_item['template'], ['two-columns', 'content-sidebar', 'sidebar-content'] ) ) {
                        $first_col = $menu_item['template'] === 'sidebar-content' ? 'sidebar' : 'layout';
                        $second_col = $first_col === 'layout' ? 'sidebar' : 'layout';

                        $menu_item[ 'layout' ] = array_merge( $menu_item[ $first_col ], ['endcolumn'], $menu_item[ $second_col ] );
                    }
                    ?>

                    <div class="container <?php printf( 'tab-template-%s', $menu_item['template'] ) ?>" v-pre>
                        <div class="row reveal <?php echo $menu_item['template'] == 'masonry' ? 'grid' : '' ?>">

                            <?php echo $columns['main-col-wrap'] ?>

                            <?php foreach ($menu_item['layout'] as $block):
                                if ( $block === 'endcolumn' ) {
                                    echo $columns['main-col-end'];
                                    echo $columns['side-col-wrap'];
                                    $columns['main-col-end'] = $columns['side-col-end'];
                                    continue;
                                }

                                if ( empty( $block['type'] ) ) {
                                    $block['type'] = 'default';
                                }

                                if ( empty( $block['id'] ) ) {
                                    $block['id'] = '';
                                }

                                // Default block icons used on previous versions didn't include the icon pack name.
                                // Since they were all material icons, we just add the "mi" prefix to them.
                                $default_icons = ['view_headline', 'insert_photo', 'view_module', 'map', 'email', 'layers', 'av_timer', 'attach_file', 'alarm', 'videocam', 'account_circle'];
                                if ( ! empty( $block['icon'] ) && in_array( $block['icon'], $default_icons ) ) {
                                    $block['icon'] = sprintf( 'mi %s', $block['icon'] );
                                }

                                $block_wrapper_class = $columns['block-class'];
                                $block_wrapper_class .= ' block-type-' . esc_attr( $block['type'] );
                                $block_wrapper_class .= ' ' . $content_block_classes;

                                if ( ! empty( $block['show_field'] ) ) {
                                    $block_wrapper_class .= ' block-field-' . esc_attr( $block['show_field'] );
                                }

                                if ( ! empty( $block['class'] ) ) {
                                    $block_wrapper_class .= ' ' . esc_attr( $block['class'] );
                                }

                                if (
                                    $listing->type && ! empty( $block['show_field'] ) &&
                                    $listing->get_field( $block['show_field'] ) &&
                                    $listing->type->get_field( $block['show_field'] )
                                ) {
                                    $field = $listing->type->get_field( $block['show_field'] );
                                } else {
                                    $field = null;
                                }

                                // Text Block.
                                if ( $block['type'] == 'text' && isset( $block['show_field'] ) && ( $block_content = $listing->get_field( $block['show_field'] ) ) ) {
                                    $escape_html = true;
                                    $allow_shortcodes = false;
                                    if ( $field ) {
                                        if ( ! empty( $field['type'] ) && in_array( $field['type'], [ 'texteditor', 'wp-editor' ] ) ) {
                                            $escape_html = empty( $field['editor-type'] ) || $field['editor-type'] == 'textarea';

                                            if ( $field['type'] == 'wp-editor' ) {
                                                $escape_html = false;
                                            }

                                            $allow_shortcodes = ! empty( $field['allow-shortcodes'] ) && $field['allow-shortcodes'] && ! $escape_html;
                                        }
                                    }

                                    c27()->get_section('content-block', [
                                        'ref' => 'single-listing',
                                        'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi view_headline',
                                        'title' => $block['title'],
                                        'content' => $block_content,
                                        'wrapper_class' => $block_wrapper_class,
                                        'wrapper_id' => $block['id'],
                                        'escape_html' => $escape_html,
                                        'allow-shortcodes' => $allow_shortcodes,
                                        ]);
                                }


                                // Gallery Block.
                                if ( $block['type'] == 'gallery' && ( $gallery_items = (array) $listing->get_field( $block['show_field'] ) ) ) {
                                    $gallery_type = 'carousel';
                                    foreach ((array) $block['options'] as $option) {
                                        if ($option['name'] == 'gallery_type') $gallery_type = $option['value'];
                                    }

                                    if ( array_filter( $gallery_items ) ) {
                                        c27()->get_section('gallery-block', [
                                            'ref' => 'single-listing',
                                            'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi insert_photo',
                                            'title' => $block['title'],
                                            'gallery_type' => $gallery_type,
                                            'wrapper_class' => $block_wrapper_class,
                                            'wrapper_id' => $block['id'],
                                            'gallery_items' => array_filter( $gallery_items ),
                                            'gallery_item_interface' => 'CASE27_JOB_MANAGER_ARRAY',
                                            ]);
                                    }
                                }

                                // Files Block.
                                if ( $block['type'] == 'file' && ( $files = (array) $listing->get_field( $block['show_field'] ) ) ) {
                                    if ( array_filter( $files ) ) {
                                        c27()->get_section('files-block', [
                                            'ref' => 'single-listing',
                                            'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi attach_file',
                                            'title' => $block['title'],
                                            'wrapper_class' => $block_wrapper_class,
                                            'wrapper_id' => $block['id'],
                                            'items' => array_filter( $files ),
                                            ]);
                                    }
                                }

                                // Categories Block.
                                if ( $block['type'] == 'categories' && ( $terms = $listing->get_field( 'job_category' ) ) ) {
                                    c27()->get_section('listing-categories-block', [
                                        'ref' => 'single-listing',
                                        'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi view_module',
                                        'title' => $block['title'],
                                        'terms' => $terms,
                                        'wrapper_class' => $block_wrapper_class,
                                        'wrapper_id' => $block['id'],
                                        ]);
                                }

                                // Tags Block.
                                if ( $block['type'] == 'tags' && ( $terms = $listing->get_field( 'job_tags' ) ) ) {
                                    c27()->get_section('list-block', [
                                        'ref' => 'single-listing',
                                        'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi view_module',
                                        'title' => $block['title'],
                                        'items' => $terms,
                                        'item_interface' => 'WP_TERM',
                                        'wrapper_class' => $block_wrapper_class,
                                        'wrapper_id' => $block['id'],
                                        ]);
                                }

                                if ( $block['type'] == 'terms' ) {
                                    // Keys = taxonomy name
                                    // Value = taxonomy field name
                                    $taxonomies = [
                                        'job_listing_category' => 'job_category',
                                        'case27_job_listing_tags' => 'job_tags',
                                        'region' => 'region',
                                    ];

                                    $taxonomy = 'job_listing_category';
                                    $template = 'listing-categories-block';

                                    if ( isset( $block['options'] ) ) {
                                        foreach ((array) $block['options'] as $option) {
                                            if ($option['name'] == 'taxonomy') $taxonomy = $option['value'];
                                            if ($option['name'] == 'style') $template = $option['value'];
                                        }
                                    }

                                    if ( ! isset( $taxonomies[ $taxonomy ] ) ) {
                                        continue;
                                    }

                                    if ( $terms = $listing->get_field( $taxonomies[ $taxonomy ] ) ) {
                                        if ( $template == 'list-block' ) {
                                            c27()->get_section('list-block', [
                                                'ref' => 'single-listing',
                                                'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi view_module',
                                                'title' => $block['title'],
                                                'items' => $terms,
                                                'item_interface' => 'WP_TERM',
                                                'wrapper_class' => $block_wrapper_class,
                                                'wrapper_id' => $block['id'],
                                            ]);
                                        } else {
                                            c27()->get_section('listing-categories-block', [
                                                'ref' => 'single-listing',
                                                'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi view_module',
                                                'title' => $block['title'],
                                                'terms' => $terms,
                                                'wrapper_class' => $block_wrapper_class,
                                                'wrapper_id' => $block['id'],
                                            ]);
                                        }
                                    }
                                }


                                // Location Block.
                                if ( $block['type'] == 'location' && isset( $block['show_field'] ) && ( $block_location = $listing->get_field( $block['show_field'] ) ) ) {
                                    if ( ! ( $listing_logo = $listing->get_logo( 'thumbnail' ) ) ) {
                                        $listing_logo = c27()->image( 'marker.jpg' );
                                    }

                                    $location_arr = [
                                        'address' => $block_location,
                                        'marker_image' => ['url' => $listing_logo],
                                    ];

                                    if ( $block['show_field'] == 'job_location' && ( $lat = $listing->get_data('geolocation_lat') ) && ( $lng = $listing->get_data('geolocation_long') ) ) {
                                        $location_arr = [
                                            'marker_lat' => $lat,
                                            'marker_lng' => $lng,
                                            'marker_image' => ['url' => $listing_logo],
                                        ];
                                    }

                                    $map_skin = 'skin1';
                                    if ( ! empty( $block['options'] ) ) {
                                        foreach ((array) $block['options'] as $option) {
                                            if ($option['name'] == 'map_skin') $map_skin = $option['value'];
                                        }
                                    }

                                    c27()->get_section('map', [
                                        'ref' => 'single-listing',
                                        'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi map',
                                        'title' => $block['title'],
                                        'wrapper_class' => $block_wrapper_class,
                                        'wrapper_id' => $block['id'],
                                        'template' => 'block',
                                        'options' => [
                                            'locations' => [ $location_arr ],
                                            'zoom' => 11,
                                            'draggable' => false,
                                            'skin' => $map_skin,
                                        ],
                                    ]);
                                }


                                // Contact Form Block.
                                if ($block['type'] == 'contact_form') {
                                    $contact_form_id = false;
                                    $email_to = ['job_email'];
                                    foreach ((array) $block['options'] as $option) {
                                        if ($option['name'] == 'contact_form_id') $contact_form_id = absint( $option['value'] );
                                        if ($option['name'] == 'email_to') $email_to = $option['value'];
                                    }

                                    $email_to = array_filter( $email_to );
                                    $recipients = [];
                                    foreach ( $email_to as $email_field ) {
                                        if ( ( $email = $listing->get_field( $email_field ) ) && is_email( $email ) ) {
                                            $recipients[] = $email;
                                        }
                                    }

                                    if ( $contact_form_id && count( $email_to ) && count( $recipients ) ) {
                                        c27()->get_section('raw-block', [
                                            'ref' => 'single-listing',
                                            'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi email',
                                            'title' => $block['title'],
                                            'content' => str_replace('%case27_recipients%', join('|', $email_to), do_shortcode( sprintf( '[contact-form-7 id="%d"]', $contact_form_id ) ) ),
                                            'wrapper_class' => $block_wrapper_class,
                                            'wrapper_id' => $block['id'],
                                            'escape_html' => false,
                                        ]);
                                    }
                                }


                                // Host Block.
                                if ($block['type'] == 'related_listing' && ( $related_listing = $listing->get_field( 'related_listing' ) ) ) {
                                    c27()->get_section('related-listing-block', [
                                        'ref' => 'single-listing',
                                        'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi layers',
                                        'title' => $block['title'],
                                        'related_listing' => $related_listing,
                                        'wrapper_class' => $block_wrapper_class,
                                        'wrapper_id' => $block['id'],
                                    ]);
                                }


                                // Countdown Block.
                                if ($block['type'] == 'countdown' && ( $countdown_date = $listing->get_field( $block['show_field'] ) ) ) {
                                    c27()->get_section('countdown-block', [
                                        'ref' => 'single-listing',
                                        'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi av_timer',
                                        'title' => $block['title'],
                                        'countdown_date' => $countdown_date,
                                        'wrapper_class' => $block_wrapper_class,
                                        'wrapper_id' => $block['id'],
                                    ]);
                                }

                                // Video Block.
                                if ($block['type'] == 'video' && ( $video_url = $listing->get_field( $block['show_field'] ) ) ) {
                                    c27()->get_section('video-block', [
                                        'ref' => 'single-listing',
                                        'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi videocam',
                                        'title' => $block['title'],
                                        'video_url' => $video_url,
                                        'wrapper_class' => $block_wrapper_class,
                                        'wrapper_id' => $block['id'],
                                    ]);
                                }

                                if ( in_array( $block['type'], [ 'table', 'accordion', 'tabs', 'details' ] ) ) {
                                    $rows = [];

                                    foreach ((array) $block['options'] as $option) {
                                        if ($option['name'] == 'rows') {
                                            foreach ((array) $option['value'] as $row) {
                                                if ( ! is_array( $row ) || empty( $row['show_field'] ) ) {
                                                    continue;
                                                }

                                                if ( ! ( $row_field = $listing->get_field( $row['show_field'] ) ) ) {
                                                    continue;
                                                }

                                                if ( is_array( $row_field ) ) {
                                                    $row_field = join( ', ', $row_field );
                                                }

                                                $rows[] = [
                                                    'title' => $row['label'],
                                                    'content' => $listing->compile_field_string( $row['content'], $row_field ),
                                                    'icon' => isset( $row['icon'] ) ? $row['icon'] : '',
                                                ];
                                            }
                                        }
                                    }
                                }

                                // Table Block.
                                if ( $block['type'] == 'table' && count( $rows ) ) {
                                    c27()->get_section('table-block', [
                                        'ref' => 'single-listing',
                                        'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi view_module',
                                        'title' => $block['title'],
                                        'rows' => $rows,
                                        'wrapper_class' => $block_wrapper_class,
                                        'wrapper_id' => $block['id'],
                                        ]);
                                }


                                // Details Block.
                                if ( $block['type'] == 'details' && count( $rows ) ) {
                                    c27()->get_section('list-block', [
                                        'ref' => 'single-listing',
                                        'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi view_module',
                                        'title' => $block['title'],
                                        'item_interface' => 'CASE27_DETAILS_ARRAY',
                                        'items' => $rows,
                                        'wrapper_class' => $block_wrapper_class,
                                        'wrapper_id' => $block['id'],
                                        ]);
                                }


                                // Accordion Block.
                                if ( $block['type'] == 'accordion' && count( $rows ) ) {
                                    c27()->get_section('accordion-block', [
                                        'ref' => 'single-listing',
                                        'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi view_module',
                                        'title' => $block['title'],
                                        'rows' => $rows,
                                        'wrapper_class' => $block_wrapper_class,
                                        'wrapper_id' => $block['id'],
                                        ]);
                                }

                                // Tabs Block.
                                if ( $block['type'] == 'tabs' && count( $rows ) ) {
                                    c27()->get_section('tabs-block', [
                                        'ref' => 'single-listing',
                                        'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi view_module',
                                        'title' => $block['title'],
                                        'rows' => $rows,
                                        'wrapper_class' => $block_wrapper_class,
                                        'wrapper_id' => $block['id'],
                                        ]);
                                }

                                // Work Hours Block.
                                if ($block['type'] == 'work_hours' && ( $work_hours = $listing->get_field( 'work_hours' ) ) ) {
                                    c27()->get_section('work-hours-block', [
                                        'wrapper_class' => $block_wrapper_class . ' open-now sl-zindex',
                                        'wrapper_id' => $block['id'],
                                        'ref' => 'single-listing',
                                        'title' => $block['title'],
                                        'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi alarm',
                                        'hours' => (array) $work_hours,
                                    ]);
                                }

                                // Social Networks (Links) Block.
                                if ( $block['type'] == 'social_networks' && ( $networks = $listing->get_social_networks() ) ) {
                                    c27()->get_section('list-block', [
                                        'ref' => 'single-listing',
                                        'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi view_module',
                                        'title' => $block['title'],
                                        'item_interface' => 'CASE27_LINK_ARRAY',
                                        'items' => $networks,
                                        'wrapper_class' => $block_wrapper_class,
                                        'wrapper_id' => $block['id'],
                                    ]);
                                }

                                // Author Block.
                                if ($block['type'] == 'author') {
                                    c27()->get_section('author-block', [
                                        'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi account_circle',
                                        'ref' => 'single-listing',
                                        'author' => $listing->get_author(),
                                        'title' => $block['title'],
                                        'wrapper_class' => $block_wrapper_class,
                                        'wrapper_id' => $block['id'],
                                    ]);
                                }

                                // Code block.
                                if ( $block['type'] == 'code' && ! empty( $block['content'] ) ) {
                                    if ( ( $content = $listing->compile_string( $block['content'] ) ) ) {
                                        c27()->get_section('raw-block', [
                                            'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi view_module',
                                            'ref' => 'single-listing',
                                            'title' => $block['title'],
                                            'wrapper_class' => $block_wrapper_class,
                                            'wrapper_id' => $block['id'],
                                            'content' => $content,
                                            'do_shortcode' => true,
                                        ]);
                                    }
                                }

                                // Raw content block.
                                if ( $block['type'] == 'raw' ) {
                                    $content = '';
                                    foreach ((array) $block['options'] as $option) {
                                        if ($option['name'] == 'content') $content = $option['value'];
                                    }

                                    if ( $content ) {
                                        c27()->get_section('raw-block', [
                                            'icon' => ! empty( $block['icon'] ) ? $block['icon'] : 'mi view_module',
                                            'ref' => 'single-listing',
                                            'title' => $block['title'],
                                            'wrapper_class' => $block_wrapper_class,
                                            'wrapper_id' => $block['id'],
                                            'content' => $content,
                                        ]);
                                    }
                                }

                                do_action( "case27/listing/blocks/{$block['type']}", $block );

                            endforeach ?>

                            <?php echo $columns['main-col-end'] ?>

                        </div>
                    </div>
                <?php endif ?>

                <?php if ($menu_item['page'] == 'comments'): ?>
                    <div v-pre>
                        <?php $GLOBALS['case27_reviews_allow_rating'] = $listing->type->is_rating_enabled() ?>
                        <?php comments_template() ?>
                    </div>
                <?php endif ?>

                <?php if ($menu_item['page'] == 'related_listings'): ?>
                    <input type="hidden" class="case27-related-listing-type" value="<?php echo esc_attr( $menu_item['related_listing_type'] ) ?>">
                    <div class="container c27-related-listings-wrapper reveal">
                        <div class="row listings-loading" v-show="related_listings['<?php echo esc_attr( "_tab_{$i}" ) ?>'].loading">
                            <div class="loader-bg">
                                <?php c27()->get_partial('spinner', [
                                    'color' => '#777',
                                    'classes' => 'center-vh',
                                    'size' => 28,
                                    'width' => 3,
                                    ]); ?>
                            </div>
                        </div>
                        <div class="row section-body i-section" v-show="!related_listings['<?php echo esc_attr( "_tab_{$i}" ) ?>'].loading">
                            <div class="c27-related-listings" v-html="related_listings['<?php echo esc_attr( "_tab_{$i}" ) ?>'].html" :style="!related_listings['<?php echo esc_attr( "_tab_{$i}" ) ?>'].show ? 'opacity: 0;' : ''"></div>
                        </div>
                        <div class="row">
                            <div class="c27-related-listings-pagination" v-html="related_listings['<?php echo esc_attr( "_tab_{$i}" ) ?>'].pagination"></div>
                        </div>
                    </div>
                <?php endif ?>

                <?php if ($menu_item['page'] == 'store'):
                    $selected_ids = isset($menu_item['field']) && $listing->get_field( $menu_item['field'] ) ? (array) $listing->get_field( $menu_item['field'] ) : [];
                    ?>
                    <input type="hidden" class="case27-store-products-ids" value="<?php echo json_encode(array_map('absint', (array) $selected_ids)) ?>">
                    <div class="container c27-products-wrapper woocommerce reveal">
                        <div class="row listings-loading" v-show="products['<?php echo esc_attr( "_tab_{$i}" ) ?>'].loading">
                            <div class="loader-bg">
                                <?php c27()->get_partial('spinner', [
                                    'color' => '#777',
                                    'classes' => 'center-vh',
                                    'size' => 28,
                                    'width' => 3,
                                    ]); ?>
                            </div>
                        </div>
                        <div class="section-body" v-show="!products['<?php echo esc_attr( "_tab_{$i}" ) ?>'].loading">
                            <ul class="c27-products products" v-html="products['<?php echo esc_attr( "_tab_{$i}" ) ?>'].html" :style="!products['<?php echo esc_attr( "_tab_{$i}" ) ?>'].show ? 'opacity: 0;' : ''"></ul>
                        </div>
                        <div class="row">
                            <div class="c27-products-pagination" v-html="products['<?php echo esc_attr( "_tab_{$i}" ) ?>'].pagination"></div>
                        </div>
                    </div>
                <?php endif ?>

                <?php if ($menu_item['page'] == 'bookings'): ?>
                    <div class="container" v-pre>
                        <div class="row">
                            <?php // Contact Form Block.
                            if ($menu_item['provider'] == 'basic-form') {
                                $contact_form_id = absint( $menu_item['contact_form_id'] );
                                $email_to = array_filter( [$menu_item['field']] );
                                $recipients = [];
                                foreach ( $email_to as $email_field ) {
                                    if ( ( $email = $listing->get_field( $email_field ) ) && is_email( $email ) ) {
                                        $recipients[] = $email;
                                    }
                                }

                                if ( $contact_form_id && count( $email_to ) && count( $recipients ) ) {
                                    c27()->get_section('raw-block', [
                                        'ref' => 'single-listing',
                                        'icon' => 'material-icons://email',
                                        'title' => __( 'Book now', 'my-listing' ),
                                        'content' => str_replace('%case27_recipients%', join('|', $email_to), do_shortcode( sprintf( '[contact-form-7 id="%d"]', $contact_form_id ) ) ),
                                        'wrapper_class' => 'col-md-6 col-md-push-3 col-sm-8 col-sm-push-2 col-xs-12 grid-item',
                                        'escape_html' => false,
                                        ]);
                                }
                            }
                            ?>

                            <?php // TimeKit Widget.
                            if ($menu_item['provider'] == 'timekit' && ( $timekitID = $listing->get_field( $menu_item['field'] ) ) ): ?>
                                <div class="col-md-8 col-md-push-2 c27-timekit-wrapper">
                                    <iframe src="https://my.timekit.io/<?php echo esc_attr( $timekitID ) ?>" frameborder="0"></iframe>
                                </div>
                            <?php endif ?>

                        </div>
                    </div>
                <?php endif ?>

            </section>
        <?php endforeach; ?>
    </div>

    <?php c27()->get_partial('report-modal', ['listing' => $post]) ?>
</div>

<?php echo apply_filters( 'mylisting\single\output_schema', $listing->schema->get_markup() ) ?>
