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
			$zero_space = 6 - strlen($id);
			$doi_number = str_repeat('0',$zero_space) . $id;

			// Attachment array
			$attachments = array();
			$wp_attachments = get_attached_media('image',$id);
			$attach_int = 1; // For adding DOI numbers to attachments
			foreach($wp_attachments as $wp_attachment) {
				// Generate a DOI
				$attach_zero_space = 3 - strlen($attach_int);
				$attach_doi = 'f' . str_repeat('0',$attach_zero_space) . $attach_int;
				$attach_int++;
				// Push the attachment into the attachment array
				$attachments[] = array(
					'description' => $wp_attachment->post_title,
					'doi' => $attach_doi,
					'resource' => $wp_attachment->guid
					);
			}

			// Post data array
			$postData = array(
				'id' => $id,
				'title' => get_the_title($id),
				'date' => array(
						'full' => get_the_date('omdGis', $id ), // YYYYMMDDHHMMSS
						'year' => get_the_date('o',$id),
						'month' => get_the_date('m',$id),
						'day' => get_the_date('d',$id)
					),
				'doi' => $doi_number,
				'permalink' => get_permalink($id),
				'attachments' => $attachments
			);


			// Setup dom
			$dom = new DOMDocument('1.0', 'utf-8');
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;


			// <doi_batch>
			$dom_doi_batch = $dom->createElement('doi_batch');
			$dom_doi_batch->setAttributeNS('', 'version', '4.3.0');
			$dom_doi_batch->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'http://www.crossref.org/schema/4.3.0');
			$dom_doi_batch->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
			$dom_doi_batch->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xlink', 'http://www.w3.org/1999/xlink');
			$dom_doi_batch->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ai', 'http://www.crossref.org/AccessIndicators.xsd');
			$dom_doi_batch->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:fr', 'http://www.crossref.org/fundref.xsd');
			$dom_doi_batch->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'xsi:schemaLocation', 'http://www.crossref.org/schema/4.3.0 http://www.crossref.org/schema/deposit/crossref4.3.0.xsd');
			$dom->appendChild($dom_doi_batch);

			// <head>
			$dom_head = $dom->createElement('head');
			$dom_doi_batch->appendChild($dom_head);

				// <doi_batch_id>
				// Generate DOI, supporting up to 999999 posts (ie. lets add zome zeros to the post ID)
				$dom_doi_batch_id = $dom->createElement('doi_batch_id','10.12952/dta.elementa.' . $postData['doi']); // ** Should be changed to an option value
				$dom_head->appendChild($dom_doi_batch_id);

				// <timestamp>
				$dom_timestamp = $dom->createElement('timestamp', $postData['date']['full']);
				$dom_head->appendChild($dom_timestamp);

				// <depositor>
				$dom_depositor = $dom->createElement('depositor');
				$dom_head->appendChild($dom_depositor);

					// <name>
					$dom_depositor_name = $dom->createElement('name','BioOne'); // ** Should be changed to an option value
					$dom_depositor->appendChild($dom_depositor_name);

					// <email_address>
					$dom_email_address = $dom->createElement('email_address','crossrefadmin@elementascience.org'); // ** Should be changed to an option value
					$dom_depositor->appendChild($dom_email_address);

				// <registrant>
				$dom_registrant = $dom->createElement('registrant', 'BioOne'); // ** Should be changed to an option value
				$dom_head->appendChild($dom_registrant);

			// <body>
			$dom_body = $dom->createElement('body');
			$dom_doi_batch->appendChild($dom_body);

				// <journal>
				$dom_journal = $dom->createElement('journal');
				$dom_doi_batch->appendChild($dom_journal);

					// <journal_metadata>
					$dom_journal_metadata = $dom->createElement('journal_metadata');
					$dom_journal_metadata_language = $dom->createAttribute('language');
					$dom_journal_metadata_language->value = 'en'; // ** Should be changed to an option value
					$dom_journal_metadata->appendChild($dom_journal_metadata_language);
					$dom_journal->appendChild($dom_journal_metadata);

						// <full_title>
						$dom_full_title = $dom->createElement('full_title', get_bloginfo('name'));
						$dom_journal_metadata->appendChild($dom_full_title);

						// <abbrev_title>
						$dom_abbrev_title = $dom->createElement('abbrev_title', 'WP DOI'); // ** Should be changed to an option value
						$dom_journal_metadata->appendChild($dom_abbrev_title);

						// <issn>
						$dom_issn = $dom->createElement('issn', '2325-1026'); // ** Should be changed to an option value
						$dom_issn_media_type = $dom->createAttribute('media_type');
						$dom_issn_media_type->value = 'electronic';
						$dom_issn->appendChild($dom_issn_media_type);
						$dom_journal_metadata->appendChild($dom_issn);

					// <journal_issue>
					$dom_journal_issue = $dom->createElement('journal_issue');
					$dom_journal->appendChild($dom_journal_issue);

						// <publication_date>
						$dom_publication_date = $dom->createElement('publication_date');
						$dom_publication_date_media_type = $dom->createAttribute('media_type');
						$dom_publication_date_media_type->value = 'online';
						$dom_publication_date->appendChild($dom_publication_date_media_type);
						$dom_journal_issue->appendChild($dom_publication_date);

							// <month>
							$dom_publication_date_month = $dom->createElement('month', $postData['date']['month']); // ## Should be adjusted to an actual value (post date is not issue date)
							$dom_publication_date->appendChild($dom_publication_date_month);

							// <day>
							$dom_publication_date_day = $dom->createElement('day', $postData['date']['day']); // ## Should be adjusted to an actual value (post date is not issue date)
							$dom_publication_date->appendChild($dom_publication_date_day);

							// <year>
							$dom_publication_date_year = $dom->createElement('year', $postData['date']['year']); // ## Should be adjusted to an actual value (post date is not issue date)
							$dom_publication_date->appendChild($dom_publication_date_year);

						// <journal_volume>
						$dom_journal_volume = $dom->createElement('journal_volume'); // ## Should be adjusted to an actual value (post date is not issue date)
						$dom_journal_issue->appendChild($dom_journal_volume);
						$dom_journal_volume_volume = $dom->createElement('volume',$postData['id']); // ## Should be adjusted to an actual value (post id is not volume number)
						$dom_journal_volume->appendChild($dom_journal_volume_volume);

					// <journal_article>
					$dom_journal_article = $dom->createElement('journal_article');
					$dom_journal_article_publication_type = $dom->createAttribute('publication_type');
					$dom_journal_article_publication_type->value = 'full_text';
					$dom_journal_article->appendChild($dom_journal_article_publication_type);
					$dom_journal->appendChild($dom_journal_article);

						// <titles>
						$dom_titles = $dom->createElement('titles',$postData['title']);
						$dom_journal_article->appendChild($dom_titles);

						// <contributors>
						$dom_contributors = $dom->createElement('contributors');
						$dom_journal_article->appendChild($dom_contributors);

							// <person_name>
							$dom_person_name = $dom->createElement('person_name'); // ## Should be adjusted to an actual value (contributors in post)
							$dom_person_name_contributor_role = $dom->createAttribute('contributor_role');
							$dom_person_name_contributor_role->value = 'author'; // ## Should be adjusted to an actual value (first contributor in post)
							$dom_person_name->appendChild($dom_person_name_contributor_role);
							$dom_person_name_sequence = $dom->createAttribute('sequence');
							$dom_person_name_sequence->value = 'first'; // ## Should be adjusted to an actual value (first contributor in post)
							$dom_person_name->appendChild($dom_person_name_sequence);
							$dom_contributors->appendChild($dom_person_name);

								// <given_name>
								$dom_given_name = $dom->createElement('given_name','John'); // ## Should be adjusted to an actual value (first name)
								$dom_person_name->appendChild($dom_given_name);

								// <surname>
								$dom_surname = $dom->createElement('surname','Doe'); // ## Should be adjusted to an actual value (first name)
								$dom_person_name->appendChild($dom_surname);

							// <publication_date media_type="online">
							$dom_journal_article_publication_date = $dom->createElement('publication_date');
							$dom_journal_article_publication_date_media_type = $dom->createAttribute('media_type');
							$dom_journal_article_publication_date_media_type->value = 'online';
							$dom_journal_article_publication_date->appendChild($dom_journal_article_publication_date_media_type);
							$dom_journal_article->appendChild($dom_journal_article_publication_date);

								// <month>
								$dom_journal_article_publication_date_month = $dom->createElement('month', $postData['date']['month']); // ## Should be adjusted to an actual value (post date is not issue date)
								$dom_journal_article_publication_date->appendChild($dom_journal_article_publication_date_month);

								// <day>
								$dom_journal_article_publication_date_day = $dom->createElement('day', $postData['date']['day']); // ## Should be adjusted to an actual value (post date is not issue date)
								$dom_journal_article_publication_date->appendChild($dom_journal_article_publication_date_day);

								// <year>
								$dom_journal_article_publication_date_year = $dom->createElement('year', $postData['date']['year']); // ## Should be adjusted to an actual value (post date is not issue date)
								$dom_journal_article_publication_date->appendChild($dom_journal_article_publication_date_year);

						// <pages>
						$dom_pages = $dom->createElement('pages');
						$dom_journal_article->appendChild($dom_pages);

							// <first_page>
							$dom_first_page = $dom->createElement('first_page',$postData['doi']);
							$dom_pages->appendChild($dom_first_page);
						
						// <publisher_item>
						$dom_publisher_item = $dom->createElement('publisher_item');
						$dom_journal_article->appendChild($dom_publisher_item);

							// <item_number>
							$dom_item_number = $dom->createElement('item_number','10.12952/dta.elementa.' . $postData['doi']);
							$dom_publisher_item->appendChild($dom_item_number);

						// <doi_data>
						$dom_doi_data = $dom->createElement('doi_data');
						$dom_journal_article->appendChild($dom_doi_data);

							// <doi>
							$dom_doi = $dom->createElement('doi','10.12952/dta.elementa.' . $postData['doi']);
							$dom_doi_data->appendChild($dom_doi);

							// <timestamp>
							$dom_doi_data_timestamp = $dom->createElement('timestamp', $postData['date']['full']);
							$dom_doi_data->appendChild($dom_doi_data_timestamp);

							// <resource>
							$dom_resource = $dom->createElement('resource', $postData['permalink']);
							$dom_doi_data->appendChild($dom_resource);

						// <component_list>
						if (count($postData['attachments']) > 0) { // Only include component list if attachments exist

							// <component_list>
							$dom_component_list = $dom->createElement('component_list');
							$dom_journal_article->appendChild($dom_component_list);

							// <component parent_relation="isPartOf">
							for ($a=0;$a<count($postData['attachments']);$a++) {
								$dom_component = $dom->createElement('component');
								$dom_component_parent_relation = $dom->createAttribute('parent_relation');
								$dom_component_parent_relation->value = 'isPartOf';
								$dom_component->appendChild($dom_component_parent_relation);
								$dom_component_list->appendChild($dom_component);

								// <description>
								$dom_component_description = $dom->createElement('description',$postData['attachments'][$a]['description']);
								$dom_component->appendChild($dom_component_description);

								// <doi_data>
								$dom_component_doi_data = $dom->createElement('doi_data');
								$dom_component->appendChild($dom_component_doi_data);

									// <doi>
									$dom_component_doi_data_doi = $dom->createElement('doi','10.12952/dta.elementa.' . $postData['doi'] . '.' . $postData['attachments'][$a]['doi']);
									$dom_component_doi_data->appendChild($dom_component_doi_data_doi);

									// <resource>
									$dom_component_doi_data_resource = $dom->createElement('resource',$postData['attachments'][$a]['resource']);
									$dom_component_doi_data->appendChild($dom_component_doi_data_resource);
							}
						}
							

			// Echo XML onto the page
			$xml_string = $dom->saveXML();
			header("Content-Type: application/xml; charset=utf-8");
			echo $xml_string;
			exit;
		}
	}

}
