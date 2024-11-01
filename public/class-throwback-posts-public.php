<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://samuelsilva.pt
 * @since      1.0.0
 *
 * @package    Throwback_Posts
 * @subpackage Throwback_Posts/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Throwback_Posts
 * @subpackage Throwback_Posts/public
 * @author     Samuel Silva <hello@samuelsilva.pt>
 */
class Throwback_Posts_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/throwback-posts-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/throwback-posts-public.js', array( 'jquery' ), $this->version, false );

	}
	public function render_throwback_posts() {

		$throwback_class = new Throwback_Posts();
		$throwback_posts = $throwback_class->get_throwback_posts();
		$options         = $throwback_class->get_options();

		if( ! $options || count( $throwback_posts ) == 0 ){
			return;
		}
		
		$primary_color		  = $options['primary_color'];
		$secondary_color      = $options['secondary_color'];
		//booleans
		$show_date            = $options['show_date'];
		$show_time            = $options['show_time'];
		$show_image           = $options['show_image'];
		$show_excerpt         = $options['show_excerpt'];
		$html_throwback_posts = '<div id="throwback-posts">';
		$throwback_icon       = ( $options['icon']['url'] && '' !== $options['icon']['url'] ? $options['icon']['url'] : plugin_dir_url( __FILE__ )  . 'img/default_icon.svg' );

		if ( is_array( $throwback_posts ) ) {
			// stylesheet
			$html_throwback_posts .= '<style>.throwback-posts__header{background:' . esc_attr( $primary_color ) . '} .throwback-posts__card a{ color:' . esc_attr( $secondary_color ) . ' !important}</style>';
			$html_throwback_posts .= '<div class="throwback-posts__header" id="js-throwback-posts">';
			$html_throwback_posts .= '<div class="throwback-posts__icon"><img src="' . $throwback_icon . '"/></div>';
			$html_throwback_posts .= '<div class="throwback-posts__arrow close"></div>';
			$html_throwback_posts .= '<div class="throwback-posts__texting">';
			$html_throwback_posts .= '<h3 class="throwback-posts__maintitle">' . $options['title'] . '</h3>';
			$html_throwback_posts .= '<p class="throwback-posts__subtitle">' . $options['subtitle'] . '</p>';
			$html_throwback_posts .= '</div>';
			$html_throwback_posts .= '</div>';
			$html_throwback_posts .= '<div class="throwback-posts__container">';

			// run render
			foreach ( $throwback_posts as $t_post ) {
				foreach ( $t_post['posts'] as $the_post ) {
					$bg_image = '';
					$post_id  = $the_post->ID;
					// show image thumbnail
					if ( $show_image && has_post_thumbnail( $post_id ) ) {
						$bg_image = 'style="background-image:url(' . get_the_post_thumbnail_url( $post_id ) . ');"';
					}

					$html_throwback_posts .= '<div class="throwback-posts__card">
					<div class="throwback-posts__bg" ' . $bg_image . '></div>
					<a target="' . ( $options['open_target'] ? $options['open_target'] : '_blank' ) . '" href="' . get_the_permalink( $post_id ) . '">';
	
					$html_throwback_posts .= '<div class="throwback-posts__datetime">';
					if ( $show_date ) {
						$html_throwback_posts .= '<div class="throwback-posts__date">' . date( 'Y/m/d', strtotime( $the_post->post_date ) ) . '</div>';
					}
					if ( $show_time ) {
						$html_throwback_posts .= '<div class="throwback-posts__time">' . $t_post['label'] . '</div>';
					}
					$html_throwback_posts .= '</div>';

					//title
					$html_throwback_posts .= '<h4 class="throwback-posts__title">' . get_the_title( $post_id ) . '</h4>';
					//date
		
					//excerpt
					if ( $show_excerpt && get_the_excerpt( $post_id ) ) {
						$html_throwback_posts .= '<p class="throwback-posts__excerpt">' . get_the_excerpt( $post_id ) . '</p>';
					}
					$html_throwback_posts .= '</a>';

				}
				$html_throwback_posts .= '</div>';

			}
			$html_throwback_posts .= '</div>';

			echo $html_throwback_posts .= '</div>'; //phpcs:ignore
		}

	}


}
