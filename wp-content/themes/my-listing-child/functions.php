<?php

// Enqueue child theme style.css
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'child-style', get_stylesheet_uri() );

    if ( is_rtl() ) {
    	wp_enqueue_style( 'mylisting-rtl', get_template_directory_uri() . '/rtl.css', [], wp_get_theme()->get('Version') );
    }
}, 500 );


// added by KH
add_filter ( 'woocommerce_account_menu_items', 'ss_one_more_link' );
function ss_one_more_link( $menu_links ){

    // we will hook "addendpoints" later
    $new = array( 'addendpoints' => '상품 페이지 추가하기' );

    // array_slice() is good when you want to add an element between the other ones
    $menu_links = array_slice( $menu_links, 0, 1, true )
    + $new
    + array_slice( $menu_links, 1, NULL, true );


    return $menu_links;

}

add_filter( 'woocommerce_get_endpoint_url', 'ss_hook_endpoint', 10, 4 );
function ss_hook_endpoint( $url, $endpoint, $value, $permalink ){

    if( $endpoint === 'addendpoints' ) {
        $url = get_site_url() . '/add-listing';
    }

    return $url;
}

/**
* Add new register fields for WooCommerce registration.
* 우커머스 등록 폼에 새로운 필드 추가
*
* @return string Register fields HTML.
*/
function wooc_extra_register_fields() {
?>

<p class="form-row form-row-wide">
<label for="reg_billing_address_1"><?php _e( 'Address', 'woocommerce' ); ?> </label>
<input type="text" class="input-text" name="billing_address_1" id="reg_billing_address_1" value="<?php if ( ! empty( $_POST['billing_address_1'] ) ) esc_attr_e( $_POST['billing_address_1'] ); ?>" />
</p>

<?php
}

add_action( 'woocommerce_register_form', 'wooc_extra_register_fields' );

/**
* Validate the extra register fields.
* 추가 등록 필드 유효성 검사
*
* @param  string $username          Current username 현재 사용자명.
* @param  string $email             Current email 현재 이메일.
* @param  object $validation_errors WP_Error object.
*
* @return void
*/
function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {

/*
if ( isset( $_POST['billing_address_1'] ) && empty( $_POST['billing_address_1'] ) ) {
$validation_errors->add( 'billing_address_1_error', __( '<strong>Error</strong>: Address is required!.', 'woocommerce' ) );
}
*/

}

add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );

/**
* Save the extra register fields.
* 추가 등록 필드 저장하기
*
* @param  int  $customer_id Current customer ID 현재 고객 ID.
*
* @return void
*/
function wooc_save_extra_register_fields( $customer_id ) {

if ( isset( $_POST['billing_address_1'] ) ) {
// WooCommerce billing phone 우커머스 청구지 전화번호
update_user_meta( $customer_id, 'billing_address_1', sanitize_text_field( $_POST['billing_address_1'] ) );
}

}

add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );




