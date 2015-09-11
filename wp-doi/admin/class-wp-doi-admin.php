<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wp_doi
 * @subpackage wp_doi/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    wp_doi
 * @subpackage wp_doi/admin
 * @author     Your Name <email@example.com>
 */
class wp_doi_Admin {

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
	 * @param      string    $wp_doi       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $wp_doi, $version ) {

		$this->wp_doi = $wp_doi;
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
		 * defined in wp_doi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wp_doi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->wp_doi, plugin_dir_url( __FILE__ ) . 'css/wp-doi-admin.css', array(), $this->version, 'all' );

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
		 * defined in wp_doi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The wp_doi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->wp_doi, plugin_dir_url( __FILE__ ) . 'js/wp-doi-admin.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 * Create an XML button on post admin screens
	 *
	 * @since    1.0.0
	 */	
	public function create_xml_button() {
	  $postid = get_the_ID();
	  $url = get_site_url() . '/?xml=' . $postid;
	  $html = '<div class="misc-pub-section" style="text-align: right;">';
	  $html .= '<a class="button" href="' . $url . '" target="_blank">View Crossref XML</a>';
	  $html .= '</div>';
	  echo $html;
	}

	/**
	 * Create options page using Advanced Custom Fields
	 *
	 * @since    1.0.0
	*/
	public function create_options_page() {
		if( function_exists('acf_add_options_page') ) {
			acf_add_options_page(array(
				'page_title' 	=> 'WP DOI Settings',
				'menu_title'	=> 'WP DOI Settings',
				'menu_slug' 	=> 'wp-doi-settings',
				'redirect'		=> false
			));

			// Add things to the options page
			if( function_exists('acf_add_local_field_group') ):
				
				// Welcome message
				acf_add_local_field_group(array (
					'key' => 'welcome',
					'title' => 'Getting Started',
					'fields' => array (
						array (
							'key' => 'intro',
							'label' => '',
							'name' => 'sub_title',
							'type' => 'message',
							'prefix' => '',
							'instructions' => '<hr /><h1>Getting Started</h1><p style="font-size: 14px; line-height: 1.5; color: black;">WP DOI needs some information in order for DOIs to be properly registered with CrossRef. <strong>This information should be the same as the information you&rsquo;ve previously supplied to or received from CrossRef.</strong></p><p style="font-size: 14px; line-height: 1.5; color: red;"><strong>NOTE: Once you start publishing and registering DOIs with CrossRef, the information on this page should NEVER change!</strong></p>',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'readonly' => 0,
							'disabled' => 0,
						)
					),
					'location' => array (
						array (
							array (
								'param' => 'options_page',
								'operator' => '==',
								'value' => 'wp-doi-settings',
							),
						),
					),
					'menu_order' => 0,
					'position' => 'normal',
					'style' => 'seamless',
					'label_placement' => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen' => '',
				));

				// DOI information
				acf_add_local_field_group(array (
					'key' => 'doi_info',
					'title' => 'DOI Information',
					'fields' => array (
						array (
							'key' => 'doi_prefix',
							'label' => 'DOI prefix',
							'name' => 'doi_prefix',
							'type' => 'text',
							'prefix' => '',
							'instructions' => 'Your DOI prefix is issued to you by CrossRef. It is the number 10 followed by a period and your registrant code. See the <a href="http://www.doi.org/doi_handbook/2_Numbering.html#2.2.2" target="_blank">DOI Handbook</a> for more information.<br /> eg. <em><strong><u>10.12345</u></strong>/journal.example.000012</em>',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '50%',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => 'eg. 10.######',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'readonly' => 0,
							'disabled' => 0,
						),
						array (
							'key' => 'doi_suffix',
							'label' => 'DOI suffix',
							'name' => 'doi_suffix',
							'type' => 'text',
							'prefix' => '',
							'instructions' => 'Your DOI suffix a string of your choosing to represent this publication. See the <a href="http://www.doi.org/doi_handbook/2_Numbering.html#2.2.3" target="_blank">DOI Handbook</a> for more information.<br /> eg. <em>10.12345/<strong><u>journal.example</u></strong>.000012</em>',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '50%',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => 'eg. journal.example',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'readonly' => 0,
							'disabled' => 0,
						)
					),
					'location' => array (
						array (
							array (
								'param' => 'options_page',
								'operator' => '==',
								'value' => 'wp-doi-settings',
							),
						),
					),
					'menu_order' => 1,
					'position' => 'normal',
					'style' => 'default',
					'label_placement' => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen' => '',
				));

				// Depositor information
				acf_add_local_field_group(array (
					'key' => 'depositor_info',
					'title' => 'Depositor Information',
					'fields' => array (
						array (
							'key' => 'depositor_name',
							'label' => 'Depositor name',
							'name' => 'depositor_name',
							'type' => 'text',
							'prefix' => '',
							'instructions' => 'The depositor name registered with CrossRef. This is commonly your organization or journal&rsquo;s name. A complete list of depositors may be found on the <a href="http://www.crossref.org/06members/51depositor.html" target="_blank">CrossRef website</a>.',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => 'Depositor name',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'readonly' => 0,
							'disabled' => 0,
						),
						array (
							'key' => 'depositor_email',
							'label' => 'Depositor email address',
							'name' => 'depositor_email',
							'type' => 'text',
							'prefix' => '',
							'instructions' => 'The depositor email address registered with CrossRef.',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => 'example@example.com',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'readonly' => 0,
							'disabled' => 0,
						),
						array (
							'key' => 'registrant_name',
							'label' => 'Registrant name',
							'name' => 'registrant_name',
							'type' => 'text',
							'prefix' => '',
							'instructions' => 'The registrant name registered with CrossRef. This is commonly your organization or journal&rsquo;s name.',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => 'Registrant name',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'readonly' => 0,
							'disabled' => 0,
						)
					),
					'location' => array (
						array (
							array (
								'param' => 'options_page',
								'operator' => '==',
								'value' => 'wp-doi-settings',
							),
						),
					),
					'menu_order' => 2,
					'position' => 'normal',
					'style' => 'default',
					'label_placement' => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen' => '',
				));

				// Journal information 
				acf_add_local_field_group(array (
					'key' => 'journal_info',
					'title' => 'Journal Information',
					'fields' => array (
						array (
							'key' => 'full_title',
							'label' => 'Full title',
							'name' => 'full_title',
							'type' => 'text',
							'prefix' => '',
							'instructions' => 'The full title of your journal.',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => 'eg. The American Journal of Science Cats',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'readonly' => 0,
							'disabled' => 0,
						),
						array (
							'key' => 'abbrev_title',
							'label' => 'Abbreviated title',
							'name' => 'abbrev_title',
							'type' => 'text',
							'prefix' => '',
							'instructions' => 'The abbreviated title of your journal.',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => 'eg. Amer. Jour. Sci. Cat.',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'readonly' => 0,
							'disabled' => 0,
						),
						array (
							'key' => 'language',
							'label' => 'Language',
							'name' => 'language',
							'type' => 'select',
							'choices' => array(
								'en'	=> 'English (en)'
							),
							'prefix' => '',
							'instructions' => 'The primary language of your journal.',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => 'eg. Amer. Jour. Sci. Cat.',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'readonly' => 0,
							'disabled' => 0,
						),
						array (
							'key' => 'issn',
							'label' => 'ISSN',
							'name' => 'issn',
							'type' => 'text',
							'prefix' => '',
							'instructions' => 'Your journal&rsquo;s ISSN',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => 'eg. ####-####',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'readonly' => 0,
							'disabled' => 0,
						),
						array (
							'key' => 'first_date_pub',
							'label' => 'First date of publication',
							'name' => 'first_date_pub',
							'type' => 'text',
							'prefix' => '',
							'instructions' => 'This date is used to create issue and volume numbers for your journal. WP DOI creates 1 issue and 1 volume per year.<br /><br />For example, if your first date of publication is January 1, 2015, an article published on October 3, 2015 will be in issue 1, volume 1. An article published on February 3, 2015 will be in issue 1, volume 2.<br /><br /><strong>Format: YYYYMMDD</strong>. Note that this date should precede your first article&rsquo;s publish date by at least one day.',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => 'eg. 20150101',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'readonly' => 0,
							'disabled' => 0,
						)
					),
					'location' => array (
						array (
							array (
								'param' => 'options_page',
								'operator' => '==',
								'value' => 'wp-doi-settings',
							),
						),
					),
					'menu_order' => 2,
					'position' => 'normal',
					'style' => 'default',
					'label_placement' => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen' => '',
				));

				endif;
		}
	}

}
