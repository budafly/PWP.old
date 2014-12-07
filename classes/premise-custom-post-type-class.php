<?php
/**
 * Premise Custom Post Type Class
 * @package Premise
 * @subpackage Custom Post Type
 */

/**
* Premise CPT Class
*/
class PremiseCPT {

	public $post_type;
	public $menu_name;
	public $singular;
	public $plural;
	public $slug;
	public $cpt_args;
	public $text_domain;

	public $cpt_defaults = array(
		'labels'               => array(),
		'description'          => '',
		'public'               => true,
		'hierarchical'         => false,
		'exclude_from_search'  => null,
		'publicly_queryable'   => true,
		'show_ui'              => true,
		'show_in_menu'         => true,
		'show_in_nav_menus'    => true,
		'show_in_admin_bar'    => true,
		'menu_position'        => 5,
		'menu_icon'            => null,
		'capability_type'      => 'post',
		'capabilities'         => array(),
		'map_meta_cap'         => null,
		'supports'             => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
		'register_meta_box_cb' => null,
		'taxonomies'           => array(),
		'has_archive'          => true,
		'rewrite'              => true,
		'query_var'            => true,
		'can_export'           => true,
		'delete_with_user'     => null,
		'_builtin'             => false,
	);

	public $tax_defaults = array(
		'hierarchical'      => '', 
		'labels'            => '', 
		'show_ui'           => '', 
		'show_admin_column' => '', 
		'query_var'         => '', 
		'rewrite'           => '', 
	);

	function __construct( $post_type, $args = '', $text_domain = null ) {

		if ( is_array( $post_type ) ) {
			$this->post_type = $post_type['post_type'];
			$this->singular  = $post_type['singular'] 	? $post_type['singular'] 	: ucwords( str_replace( '_', ' ', $this->post_type ) );
			$this->plural    = $post_type['plural'] 	? $post_type['plural'] 		: ucwords( str_replace( '_', ' ', $this->post_type ) );
			$this->menu_name = $post_type['menu_name'] 	? $post_type['menu_name'] 	: ucwords( str_replace( '_', ' ', $this->plural ) );
			$this->slug      = $post_type['slug'];
		}
		else {
			$this->post_type = $post_type;
			$this->singular  = ucwords( str_replace( '_', ' ', $this->post_type ) );
			$this->plural    = ucwords( str_replace( '_', ' ', $this->post_type ) );
			$this->slug      = $post_type;
		}
		
		$this->cpt_args = wp_parse_args( $args, $this->cpt_defaults );

		if( null !== $text_domain )
			$this->text_domain = $text_domain;

		add_action( 'init', array( $this, 'register_post_type' ) );
	}


	public function register_post_type() {
		if( is_array( $this->cpt_args ) && empty( $this->cpt_args['labels'] ) ) {
			$menu_name = $this->menu_name;
			$singular = $this->singular;
			$plural = $this->plural;
			$domain = $this->text_domain;

			$labels = array(
				'name'               => __( $plural, $domain ),
				'singular_name'      => __( $singular, $domain ),
				'menu_name'          => __( $menu_name, $domain ),
				'all_items'          => __( $plural, $domain ),
				'add_new'            => __( 'Add New ' . $singular, $domain ),
				'add_new_item'       => __( 'Add New ' . $singular, $domain ),
				'edit_item'          => __( 'Edit ' . $singular, $domain ),
				'new_item'           => __( 'New ' . $singular, $domain ),
				'view_item'          => __( 'View ' . $singular, $domain ),
				'search_items'       => __( 'Search ' . $plural, $domain ),
				'not_found'          => __( 'No ' . $plural . ' found', $domain ),
				'not_found_in_trash' => __( 'No ' . $plural . ' found in Trash', $domain ),
				'parent_item_colon'  => __( 'Parent ' . $singular . ':', $domain ),
		    );

			$this->cpt_args['labels'] = $labels;
		}
		else{
			$this->cpt_args = $this->plural;
		}

		if( !post_type_exists( $this->post_type ) )
			register_post_type( $this->post_type,  $this->cpt_args );
	}

	public function register_taxonomy( $taxonomy, $post_types = array(), $args ='' ) {

	}
}
?>