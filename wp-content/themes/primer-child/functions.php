<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles', 11 );
function my_theme_enqueue_styles() {

  $parent_style = 'primer-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.

  wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
  wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style-rtl.css' );
/*  wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ), wp_get_theme()->get('Version'));
*/
  wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'parent-style' ), wp_get_theme()->get('Version'),all);
  
}


add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );


/*add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles', 'enqueue_child_theme_styles', PHP_INT_MAX);*/
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

add_filter('show_admin_bar', '__return_false');

add_filter('wp_nav_menu_items', 'add_login_logout_link', 10, 2);
function add_login_logout_link($items, $args) {
        ob_start();
        wp_loginout('index.php');
        $loginoutlink = ob_get_contents();
        ob_end_clean();
       // $items .= '<li>'. $loginoutlink .'</li>';
    return $items;
}


/**
*Gravity Form Button
*/
/**
 * Helper Functions for Gravity Forms.
 * @package Leaven
 */
add_filter( 'gform_shortcode_button', 'leaven_gravity_button_shortcode', 40, 3 );
/**
 * Add the "button" action to the gravityform shortcode
 * e.g. [gravityform id="1" action="button" text="button text"]
 * @param $shortcode_string
 * @param $attributes
 * @param $content
 *
 * @return string|void
 */
function leaven_gravity_button_shortcode( $shortcode_string, $attributes, $content ) {
	if ( 'button' !== $attributes['action'] ) {
		return $shortcode_string;
	}
	$defaults = array(
		'title'        => true,
		'description'  => false,
		'id'           => 0,
		'name'         => '',
		'field_values' => '',
		'tabindex'     => 1,
		'text'         => __( 'Click to open form', 'leaven' ),
	);
	$attributes = wp_parse_args( $attributes, $defaults );
	if ( $attributes['id'] < 1 ) {
		return __( 'Missing the ID attribute.', 'leaven' );
	}
	return leaven_build_gravity_button( $attributes );
}
/**
 * Build the button/form output.
 * @param $attributes array shortcode arguments
 *
 * @return string
 */
function leaven_build_gravity_button( $attributes ) {
	$form_id = absint( $attributes['id'] );
	$text    = esc_attr( $attributes['text'] );
	$onclick = "jQuery('#gravityform_button_{$form_id}, #gravityform_container_{$form_id}').slideToggle();";
	$html  = sprintf( '<button id="gravityform_button_%1$d" class="gravity_button" onclick="%2$s">%3$s</button>', esc_attr( $form_id ), $onclick, esc_attr( $text ) );
	$html .= sprintf( '<div id="gravityform_container_%1$d" class="gravity_container" style="display:none;">', esc_attr( $form_id ) );
	$html .= gravity_form( $form_id, $attributes['title'], $attributes['description'], false, $attributes['field_values'], true, $attributes['tabindex'], false );
	$html .= '</div>';
	return $html;
}

/*Logged out nonce*/
add_action( 'login_form_logout', function () {
    $user = wp_get_current_user();

    wp_logout();

    if ( ! empty( $_REQUEST['redirect_to'] ) ) {
        $redirect_to = $requested_redirect_to = $_REQUEST['redirect_to'];
    } else {
        $redirect_to = 'wp-login.php?loggedout=true';
        $requested_redirect_to = 'http://wordpressmyreferrals.myreferrals.net/login/';
    }

    /**
     * Filters the log out redirect URL.
     *
     * @since 4.2.0
     *
     * @param string  $redirect_to           The redirect destination URL.
     * @param string  $requested_redirect_to The requested redirect destination URL passed as a parameter.
     * @param WP_User $user                  The WP_User object for the user that's logging out.
     */
    $redirect_to = apply_filters( 'logout_redirect', $redirect_to, $requested_redirect_to, $user );
    wp_safe_redirect( $redirect_to );
    exit;
});
/*Logged out nonce*/

/*JP Edit End*/


?>
