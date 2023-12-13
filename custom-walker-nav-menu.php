<?php

/*
 * Plugin Name: HTDEV test task custom walker_nav_menu class
 * Description: Расширяет класс Walker_Nav_Menu для создания своей разметки меню: вместо ul>li>a будет div>a. Название класса 'HTDEV_Custom_Walker_Nav_Menu'.
 * Author:      Maxivillus
 * Version:     0.1
 *
 * Text Domain: htdev-custom-walker-nav-menu
 * Domain Path: /languages
 *
 * License:     GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 */


if( !function_exists( 'htdev_cwnm_activate' ) && !function_exists( 'htdev_cwnm_deactivate' ) && !function_exists( 'htdev_cwnm_uninstall' ) ) {
    function htdev_cwnm_activate() {
        htdev_cwnm_init();
    }

    function htdev_cwnm_deactivate() {
    }

    function htdev_cwnm_uninstall() {
    }

    register_activation_hook( __FILE__, 'htdev_cwnm_activate' );
    register_deactivation_hook( __FILE__, 'htdev_cwnm_deactivate' );
    register_uninstall_hook( __FILE__, 'htdev_cwnm_uninstall' );
} else {
    echo "Plugin 'HTDEV custom walker nav menu' init functions conflict with other plugins!";
    die;
}


if( !function_exists( 'htdev_cwnm_init' ) ) {

    add_action( 'init', 'htdev_cwnm_init' );

    function htdev_cwnm_init() {

        class HTDEV_Custom_Walker_Nav_Menu extends Walker_Nav_Menu {

            function start_el( &$output, $item, $depth = 0, $args = NULL, $id = 0 ) {
                $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

                $class_names = $value = '';
                $classes = empty( $item->classes ) ? array() : (array) $item->classes;
                $classes[] = 'menu-item-' . $item->ID;

                $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
                $class_names = ' class="' . esc_attr( $class_names ) . '"';

                $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		        $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

                $output .= $indent;

                $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
                $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
                $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
                $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

                $attributes .= 'id="nav-menu-item-'. $item->ID . '" ' . $class_names . '"';

                $item_output = $args->before;
                $item_output .= '<a'. $attributes .'>';
                $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
                $item_output .= '</a>';

                $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
                $args->items_wrap = '%3$s';
            }

        }

    }

}