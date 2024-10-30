<?php

defined( 'ABSPATH' ) || exit();

class KeenSalon_Companion_CPT {

	/**
	 * Post_Types constructor.
	 */
	function __construct() {
		add_action( 'init', [ $this, 'register_post_types' ] );
		add_action( 'init', [ $this, 'register_taxonomies' ] );
		add_action( 'init', [ $this, 'flush_rewrite_rules' ], 99 );
	}

	/**
	 * register custom post types
	 *
	 * @since 1.0.0
	 */
	function register_post_types() {

		register_post_type( 'package', array(
			'labels'              => $this->get_posts_labels( __( 'Packages', 'keensalon-companion' ), __( 'Package', 'keensalon-companion' ), __( 'Packages', 'keensalon-companion' ) ),
			'hierarchical'        => false, //Hierarchical causes memory issues - WP Loads all records
			'supports'            => array( 'title'),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-editor-table',
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'has_archive'         => false,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => array( 'slug' => 'package' ),
			'capability_type'     => 'post',
		) );

	}

	/**
	 * Register custom taxonomies
	 *
	 * @since 1.0.0
	 */
	public function register_taxonomies() {
		register_taxonomy( 'package_category', [ 'package' ], array(
			'hierarchical'      => true,
			'labels'            => $this::get_taxonomy_label( __( 'Package Categories', 'keensalon-companion' ), __( 'Category', 'keensalon-companion' ), __( 'Categories', 'keensalon-companion' ) ),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
		) );

	}

	/**
	 * Get all labels from post types
	 *
	 * @param $menu_name
	 * @param $singular
	 * @param $plural
	 *
	 * @return array
	 * @since 1.0.0
	 */
	protected static function get_posts_labels( $menu_name, $singular, $plural, $type = 'plural' ) {
		$labels = array(
			'name'               => 'plural' == $type ? $plural : $singular,
			'all_items'          => sprintf( __( "All %s", 'keensalon-companion' ), $plural ),
			'singular_name'      => $singular,
			'add_new'            => sprintf( __( 'Add New %s', 'keensalon-companion' ), $singular ),
			'add_new_item'       => sprintf( __( 'Add New %s', 'keensalon-companion' ), $singular ),
			'edit_item'          => sprintf( __( 'Edit %s', 'keensalon-companion' ), $singular ),
			'new_item'           => sprintf( __( 'New %s', 'keensalon-companion' ), $singular ),
			'view_item'          => sprintf( __( 'View %s', 'keensalon-companion' ), $singular ),
			'search_items'       => sprintf( __( 'Search %s', 'keensalon-companion' ), $plural ),
			'not_found'          => sprintf( __( 'No %s found', 'keensalon-companion' ), $plural ),
			'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'keensalon-companion' ), $plural ),
			'parent_item_colon'  => sprintf( __( 'Parent %s:', 'keensalon-companion' ), $singular ),
			'menu_name'          => $menu_name,
		);

		return $labels;
	}

	/**
	 * Get all labels from taxonomies
	 *
	 * @param $menu_name
	 * @param $singular
	 * @param $plural
	 *
	 * @return array
	 * @since 1.0.0
	 */
	protected static function get_taxonomy_label( $menu_name, $singular, $plural ) {
		$labels = array(
			'name'              => sprintf( _x( '%s', 'taxonomy general name', 'keensalon-companion' ), $plural ),
			'singular_name'     => sprintf( _x( '%s', 'taxonomy singular name', 'keensalon-companion' ), $singular ),
			'search_items'      => sprintf( __( 'Search %', 'keensalon-companion' ), $plural ),
			'all_items'         => sprintf( __( 'All %s', 'keensalon-companion' ), $plural ),
			'parent_item'       => sprintf( __( 'Parent %s', 'keensalon-companion' ), $singular ),
			'parent_item_colon' => sprintf( __( 'Parent %s:', 'keensalon-companion' ), $singular ),
			'edit_item'         => sprintf( __( 'Edit %s', 'keensalon-companion' ), $singular ),
			'update_item'       => sprintf( __( 'Update %s', 'keensalon-companion' ), $singular ),
			'add_new_item'      => sprintf( __( 'Add New %s', 'keensalon-companion' ), $singular ),
			'new_item_name'     => sprintf( __( 'New % Name', 'keensalon-companion' ), $singular ),
			'menu_name'         => __( $menu_name, 'keensalon-companion' ),
		);

		return $labels;
	}

	/**
	 * Flash The Rewrite Rules
	 *
	 * @since 2.0.2
	 */
	function flush_rewrite_rules() {
		if ( get_option( 'keensalon_companion_flush_rewrite_rules' ) ) {
			flush_rewrite_rules();
			delete_option( 'keensalon_companion_flush_rewrite_rules' );
		}
	}
}

new KeenSalon_Companion_CPT();