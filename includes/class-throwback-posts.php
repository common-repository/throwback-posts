<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://samuelsilva.pt
 * @since      1.0.0
 *
 * @package    Throwback_Posts
 * @subpackage Throwback_Posts/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Throwback_Posts
 * @subpackage Throwback_Posts/includes
 * @author     Samuel Silva <hello@samuelsilva.pt>
 */
class Throwback_Posts {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Throwback_Posts_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'THROWBACK_POSTS_VERSION' ) ) {
			$this->version = THROWBACK_POSTS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'throwback-posts';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

		if ( get_option( 'throwback-posts' )['activate'] ) {
			$this->define_public_hooks();
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Throwback_Posts_Loader. Orchestrates the hooks of the plugin.
	 * - Throwback_Posts_i18n. Defines internationalization functionality.
	 * - Throwback_Posts_Admin. Defines all hooks for the admin area.
	 * - Throwback_Posts_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-throwback-posts-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-throwback-posts-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-throwback-posts-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-throwback-posts-public.php';

		$this->loader = new Throwback_Posts_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Throwback_Posts_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Throwback_Posts_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Throwback_Posts_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$plugin_admin->create_settings_page( $this->get_dates_to_filter(), $this->get_categories_to_filter(), $this->get_posts_to_exclude() );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Throwback_Posts_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'render_throwback_posts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Throwback_Posts_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	public function get_posts_to_exclude() {
		$posts  = get_posts(  
			array (  
				'posts_per_page'   => -1,
			)  
		);  
		$all_posts = array();

		foreach( $posts as $post ){
			$all_posts[$post->ID] = $post->post_title;
		}
		return $all_posts;
	}

	public function get_dates_to_filter() {

		$today = date( 'Y-m-d', time() );

		$dates = array(
			array(
				'time'  => '7 years',
				'label' => 'Seven years ago',
				'date'  => date( 'Y-m-d', strtotime( $today . ' - 7 years' ) ),
			),
			array(
				'time'  => '6 years',
				'label' => 'Six years ago',
				'date'  => date( 'Y-m-d', strtotime( $today . ' - 6 years' ) ),
			),
			array(
				'time'  => '5 years',
				'label' => 'Five years ago',
				'date'  => date( 'Y-m-d', strtotime( $today . ' - 5 years' ) ),
			),
			array(
				'time'  => '4 years',
				'label' => 'Four years ago',
				'date'  => date( 'Y-m-d', strtotime( $today . ' - 4 years' ) ),
			),
			array(
				'time'  => '3 years',
				'label' => 'Three years ago',
				'date'  => date( 'Y-m-d', strtotime( $today . ' - 3 years' ) ),
			),
			array(
				'time'  => '2 years',
				'label' => 'Two years ago',
				'date'  => date( 'Y-m-d', strtotime( $today . ' - 2 years' ) ),
			),
			array(
				'time'  => '1 year',
				'label' => 'One year ago',
				'date'  => date( 'Y-m-d', strtotime( $today . ' - 1 year' ) ),
			),
			array(
				'time'  => '6 months',
				'label' => 'Six Months ago',
				'date'  => date( 'Y-m-d', strtotime( $today . ' - 6 months' ) ),

			),
			array(
				'time'  => '3 months',
				'label' => 'Three Months ago',
				'date'  => date( 'Y-m-d', strtotime( $today . ' - 3 months' ) ),

			),
			array(
				'time'  => '1 month',
				'label' => 'One Month ago',
				'date'  => date( 'Y-m-d', strtotime( $today . ' - 1 month' ) ),
			),
			array(
				'time'  => '1 week',
				'label' => 'One Week ago',
				'date'  => date( 'Y-m-d', strtotime( $today . ' - 1 week' ) ),

			),
		);
		return $dates;
	}

	public function get_options() {
		return get_option( 'throwback-posts' );
	}

	public function get_label_date( $date ) {

		$dates_to_filter = $this->get_dates_to_filter();

		foreach ( $dates_to_filter as $filter ) {
			if ( $filter['time'] === $date ) {
				return $filter['label'];
			}
		}

	}

	public function get_date_date( $date ) {

		$dates_to_filter = $this->get_dates_to_filter();
		foreach ( $dates_to_filter as $filter ) {
			if ( $filter['time'] === $date ) {
				return $filter['date'];
			}
		}

	}

	public function get_categories_to_filter() {
		$categories           = get_categories();
		$categories_to_filter = array();

		if ( $categories && count( $categories ) > 0 ) {
			foreach ( $categories as $category ) {
				$categories_to_filter[ $category->term_id ] = $category->name;
			}
			return $categories_to_filter;
		}
		return false;
	}

	public function get_throwback_posts() {

		$options = $this->get_options();

		$year             = '';
		$month            = '';
		$day              = '';
		$throwback_posts  = array();
		$posts_to_exclude = ( isset( $options['posts_to_exclude'] ) ? array_values( $options['posts_to_exclude'] ) : false );
		
		$args             = array(
			'numberposts'      => ( isset( $options['max_posts'] ) ? (int) $options['max_posts'] : 0 ),
			'category__in'     => ( isset( $options['categories'] ) && is_array( $options['categories'] ) ? $options['categories'] : '' ),
			'post__not_in'	   => $posts_to_exclude,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'post',
			'suppress_filters' => true,

		);

		foreach ( $options['dates']  as $date ) {
			$date_final     = $this->get_date_date( $date );
			$str_date = strtotime( $date_final );
			$year     = date( 'Y', $str_date );
			$month    = date( 'm', $str_date );
			$day      = date( 'd', $str_date );

			$args['date_query'] = array(
				array(
					'year'  => $year,
					'month' => $month,
					'day'   => $day,
				),
			);

			$posts = get_posts( $args );

			$temp = array(
				'posts' => $posts,
				'label' => $this->get_label_date( $date ),
			);

			if ( count( $posts ) > 0 ) {
				array_push( $throwback_posts, $temp );
			}
		}
		return $throwback_posts;
	}



}
