<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wp_doi
 * @subpackage wp_doi/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    wp_doi
 * @subpackage wp_doi/public
 * @author     Your Name <email@example.com>
 */
class wp_doi_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $wp_doi    The ID of this plugin.
	 */
	private $wp_doi;

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
	 * @param      string    $wp_doi       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $wp_doi, $version ) {

		$this->wp_doi = $wp_doi;
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
		 * defined in wp_doi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wp_doi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->wp_doi, plugin_dir_url( __FILE__ ) . 'css/wp-doi-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in wp_doi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wp_doi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->wp_doi, plugin_dir_url( __FILE__ ) . 'js/wp-doi-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Show XML for a given post ID
	 *
	 * @since     1.0.0
	 * @return    xml    XML for a given post.
	 */
	public function get_post_xml() {
		if (isset($_GET['xml'])) { // Only execute if ?xml=[post id] is provided in the URL
			
			// Variable setup
			$id = $_GET['xml'];
			$postData = array(
				'id' => $id,
				'title' => get_the_title($id)
			);

			// print_r($postData);

			$xml = new SimpleXMLElement('<xml/>');
			$xml->addAttribute('version', '1.0'); 
		   	
			// <doi_batch>
			$doi_batch = $xml->addChild('doi_batch');
			$doi_batch->addAttribute('version', '4.3.4');
			$doi_batch->addAttribute('xmlns', 'http://www.crossref.org/schema/4.3.4');
			$doi_batch->addAttribute('xsi', 'xmlns', 'http://www.w3.org/2001/XMLSchema-instance');
			// $doi_batch->addAttribute('xmlns:ai', 'http://www.crossref.org/AccessIndicators.xsd');
			// $doi_batch->addAttribute('xmlns:fr', 'http://www.crossref.org/fundref.xsd');
			// $doi_batch->addAttribute('xsi:schemaLocation', 'http://www.crossref.org/schema/4.3.4 http://www.crossref.org/schema/deposit/crossref4.3.4.xsd');

			$head = $doi_batch->addChild('head');

			// <body>
			$body = $xml->addChild('body');
			
			$journal_article = $body->addChild('journal_article');
			$journal_article->addAttribute('publication_type', 'full_text');

			$titles = $journal_article->addChild('titles');
			$titles->addChild('titles', $postData['title']);


			Header('Content-type: text/xml');
			print($xml->asXML());
			exit;
		}
	}

}
