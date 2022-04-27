<?php
define( 'DOCENT_CSS', get_template_directory_uri().'/css/' );
define( 'DOCENT_JS', get_template_directory_uri().'/js/' );
define( 'DOCENT_DIR', get_template_directory() );
define( 'DOCENT_URI', trailingslashit(get_template_directory_uri()) );

/* -------------------------------------------- *
 * Guttenberg for Themeum Themes
* -------------------------------------------- */
add_theme_support( 'align-wide' );
add_theme_support( 'wp-block-styles' );

/* -------------------------------------------- *
 * Include TGM Plugins
* -------------------------------------------- */
get_template_part('lib/class-tgm-plugin-activation');

/* -------------------------------------------- *
 * Register Navigation
* -------------------------------------------- */
register_nav_menus( array(
	'primary' 	=> esc_html__( 'Primary Menu', 'docent' )
	) 
);

/* -------------------------------------------- *
* Navwalker
* -------------------------------------------- */
get_template_part('lib/mobile-navwalker');

/* -------------------------------------------- *
 * Themeum Core
 * -------------------------------------------- */
get_template_part('lib/main-function/docent-core');
get_template_part('lib/main-function/docent-register');

/* -------------------------------------------- *
 * Customizer
 * -------------------------------------------- */
get_template_part('lib/customizer/libs/googlefonts');
get_template_part('lib/customizer/customizer');

/* -------------------------------------------- *
 * Custom Excerpt Length
 * -------------------------------------------- */
if(!function_exists('docent_excerpt_max_charlength')):
	function docent_excerpt_max_charlength($charlength) {
		$excerpt = get_the_excerpt();
		$charlength++;

		if ( mb_strlen( $excerpt ) > $charlength ) {
			$subex = mb_substr( $excerpt, 0, $charlength - 5 );
			$exwords = explode( ' ', $subex );
			$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
			if ( $excut < 0 ) {
				return mb_substr( $subex, 0, $excut );
			} else {
				return $subex;
			}
		} else {
			return $excerpt;
		}
	}
endif;

/* -------------------------------------------- *
* Custom body class
* -------------------------------------------- */
add_filter( 'body_class', 'docent_body_class' );
function docent_body_class( $classes ) {
    $docent_pro_layout = get_theme_mod( 'boxfull_en', 'fullwidth' );
    $classes[] = $docent_pro_layout.'-bg'.' body-content';
	return $classes;
}

/* ------------------------------------------- *
* Logout Redirect Home
* ------------------------------------------- */
add_action( 'wp_logout', 'docent_auto_redirect_external_after_logout');
function docent_auto_redirect_external_after_logout(){
  wp_redirect( home_url('/') );
  exit();
}

/* ------------------------------------------- *
* wp_body_open
* ------------------------------------------- */
function docent_skip_link() {
	echo '<a class="skip-link screen-reader-text" href="#content">' . esc_html__( 'Skip to the content', 'docent' ) . '</a>';
}
add_action( 'wp_body_open', 'docent_skip_link', 5 );

/* ------------------------------------------- *
* Add a pingback url auto-discovery header for 
* single posts, pages, or attachments
* ------------------------------------------- */
function docent_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'docent_pingback_header' );


/**
 * Convert Hex to RGB
 * 
 * @return string
 * 
 * @since 1.2.0
 */
if ( ! function_exists('docent_hex2rgb')) {
    function docent_hex2rgb( string $color ) {

        $default = '0, 0, 0';

        if ( $color === '' ) {
            return '';
        }

        if ( strpos( $color, 'var(--' ) === 0 ) {
            return preg_replace( '/[^A-Za-z0-9_)(\-,.]/', '', $color );
        }

        // convert hex to rgb
        if ($color[0] == '#' ) {
            $color = substr( $color, 1 );
        } else {
            return $default;
        }

        //Check if color has 6 or 3 characters and get values
        if ( strlen( $color ) == 6 ) {
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
            return $default;
        }

        $rgb =  array_map('hexdec', $hex);

        return implode(", ", $rgb);
    }
}