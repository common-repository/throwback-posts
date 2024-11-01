<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://samuelsilva.pt
 * @since      1.0.0
 *
 * @package    Throwback_Posts
 * @subpackage Throwback_Posts/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Throwback_Posts
 * @subpackage Throwback_Posts/admin
 * @author     Samuel Silva <hello@samuelsilva.pt>
 */
class Throwback_Posts_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Throwback_Posts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Throwback_Posts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/throwback-posts-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Throwback_Posts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Throwback_Posts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/throwback-posts-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Create the settings page with Codestar Framework integration
	 *
	 * @since    1.0.0
	 **/
	public function create_settings_page( $dates, $categories_to_filter, $all_posts ) {

		$dates_to_filter = array();

		foreach ( $dates as $date ) {
			$dates_to_filter[ $date['time'] ] = $date['label'];
		}
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/vendor/codestar-framework/codestar-framework.php';

		if ( class_exists( 'CSF' ) ) {

			$prefix = $this->plugin_name;

			CSF::createOptions(
				$prefix,
				array(
					'menu_title' => 'Throwback Posts',
					'menu_slug'  => 'throwback-posts',
					'menu_icon'  => 'dashicons-pressthis',
					'theme'      => 'light',
				)
			);

			CSF::createSection(
				$prefix,
				array(
					'title'  => __( 'General', $prefix ), //phpcs:ignore
					'fields' => array(
						array(
							'id'    => 'activate',
							'type'  => 'checkbox',
							'title' => __( 'Activate Throwback Posts', $prefix ), //phpcs:ignore
						),
						array(
							'id'       => 'dates',
							'type'     => 'select',
							'title'    => __( 'Throwback Dates', $prefix ), //phpcs:ignore
							'options'  => $dates_to_filter,
							'subtitle' => __( 'Which dates do you want to show?', $prefix ), //phpcs:ignore
							'multiple' => true,
							'chosen'   => true,
						),
						array(
							'id'       => 'categories',
							'type'     => 'select',
							'title'    => __( 'Post Categories', $prefix ), //phpcs:ignore
							'options'  => $categories_to_filter,
							'subtitle' => __( 'Select the categories for the posts do you want to include. Leave empty if you want to use all the categories.', $prefix ),
							'multiple' => true,
							'chosen'   => true,
						),
						array(
							'id'       => 'posts_to_exclude',
							'type'     => 'select',
							'title'    => __( 'Posts to Exclude', $prefix ), //phpcs:ignore
							'options'  => $all_posts,
							'subtitle' => __( 'These posts will not appear on the widget.', $prefix ),
							'multiple' => true,
							'chosen'   => true,
						),
					),
				)
			);

			CSF::createSection(
				$prefix,
				array(
					'title'  => 'Design/Content Options',
					'fields' => array(
						array(
							'id'      => 'title',
							'type'    => 'text',
							'title'   => __( 'Widget Title', $prefix ), //phpcs:ignore
							'default' => __( 'Throwback Posts!', $prefix ), //phpcs:ignore
						),
						array(
							'id'      => 'subtitle',
							'type'    => 'text',
							'title'   => __( 'Widget Subtitle', $prefix ), //phpcs:ignore
							'default' => __( 'Time to celebrate.', $prefix ), //phpcs:ignore
						),
						array(
							'id'       => 'max_posts',
							'type'     => 'spinner',
							'title'    => __( 'Maximum number of posts', $prefix ), //phpcs:ignore
							'subtitle' => __( 'Max posts per date', $prefix ), //phpcs:ignore
							'min'      => 1,
							'max'      => 8,
							'step'     => 1,
							'unit'     => 'posts',
							'default'  => 5, //phpcs:ignore
						),
						array(
							'id'       => 'open_target',
							'type'     => 'select',
							'title'    => __( 'Open Posts Method', $prefix ), //phpcs:ignore
							'subtitle' => __( 'Where to open the linked posts', $prefix ),
							'options'  => array(
								'_self'   => 'Self page',
								'_blank' => 'New Tab',
							),
							'default'  => '_self', //phpcs:ignore
						),
						array(
							'id'      => 'primary_color',
							'type'    => 'color',
							'title'   => __( 'Primary Color', $prefix ), //phpcs:ignore
							'default' => '#EE4440', //phpcs:ignore
						),
						array(
							'id'      => 'secondary_color',
							'type'    => 'color',
							'title'   => __( 'Secondary Color', $prefix ), //phpcs:ignore
							'default' => '#4CDBC4', //phpcs:ignore
						),
						array(
							'id'       => 'icon',
							'type'     => 'media',
							'title'    => __( 'Image Icon', $prefix ), //phpcs:ignore
							'subtitle' => __( 'Default Icon: ', $prefix ) . '<img src="' . plugin_dir_url( __FILE__ ) . 'img/default_icon.svg">', //phpcs:ignore
						),
						/*
						* soon 
					 	array(
							'id'      => 'design',
							'type'    => 'select',
							'title'   => __( 'Widget Presentation', $prefix ), //phpcs:ignore
							'options' => array(
								'list'   => 'List',
								'slider' => 'Slider/Carousel',
							),
						),
						*/
						array(
							'id'         => 'slider_autoplay', 
							'type'       => 'checkbox',
							'title'      => __( 'Autoplay', $prefix ), //phpcs:ignore
							'dependency' => array( 'design', 'any', 'slider' ),
						),
						array(
							'id'         => 'slider_dots',
							'type'       => 'checkbox',
							'title'      => __( 'Add Navigation dots', $prefix ), //phpcs:ignore
							'dependency' => array( 'design', 'any', 'slider' ),
						),
						array(
							'id'    => 'show_date',
							'type'  => 'checkbox',
							'title' => __( 'Display Date for the post', $prefix ),  //phpcs:ignore
						),
						array(
							'id'       => 'show_time',
							'type'     => 'checkbox',
							'title' => __( 'Display Throwback Time', $prefix ),  //phpcs:ignore
							'subtitle' => __( 'Display "One month ago", "One year ago"...', $prefix ),  //phpcs:ignore
						),
						array(
							'id'    => 'show_image',
							'type'  => 'checkbox',
							'title' => __( 'Display Featured Image (if exists)', $prefix ),  //phpcs:ignore
						),
						array(
							'id'    => 'show_excerpt',
							'type'  => 'checkbox',
							'title' => __( 'Display Excerpt for the post (if exists)', $prefix ), //phpcs:ignore
						),
					),
				)
			);
			/* 
			* Coming Soon

			CSF::createSection(
				$prefix,
				array(
					'title'  => __( 'Preview Throwback Posts!', $prefix ), //phpcs:ignore
				)
			);
			*/
		}

	}
}
