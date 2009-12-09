<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 aijko GmbH <info@aijko.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   56: class tx_aigallery_pi1 extends tslib_pibase
 *  129:     function main($content, $conf)
 *  183:     protected function initialize($conf)
 *  253:     protected function displayList()
 *  335:     protected function displayLatest()
 *  416:     protected function displaySingle()
 *  523:     protected function displayMenu()
 *  606:     protected function getItemMarkerArray($row, $section = 'singleView')
 *  629:     protected function makeMarkerArray($row, $tsFields)
 *  662:     protected function makeLanguageMarkers()
 *  688:     protected function parseConfiguration()
 *
 * TOTAL FUNCTIONS: 10
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once (PATH_tslib.'class.tslib_pibase.php');
require_once (t3lib_extMgm::extPath('ai_gallery') .'classes/Repository/class.tx_aigallery_galleries.php');

/**
 * Plugin 'Galleries' for the 'ai_gallery' extension.
 *
 * @author aijko GmbH <info@aijko.de>
 * @package	TYPO3
 * @subpackage tx_aigallery
 */
class tx_aigallery_pi1 extends tslib_pibase {
	public $prefixId      = 'tx_aigallery_pi1';		// Same as class name
	public $scriptRelPath = 'pi1/class.tx_aigallery_pi1.php';	// Path to this script relative to the extension dir.
	public $extKey        = 'ai_gallery';	// The extension key.
	public $pi_checkCHash = true;

	// Possible codes.
	const CODE_LIST = 'list';
	const CODE_LATEST = 'latest';
	const CODE_MENU = 'menu';
	const CODE_SINGLE = 'single';

	const SECTION_LIST = 'listView';
	const SECTION_LATEST = 'latestView';
	const SECTION_SINGLE = 'singleView';
	const SECTION_MENU = 'menuView';

	/**
	 * Is the configuration array valid?
	 *
	 * @var boolean
	 */
	protected $validConfiguration = true;

	/**
	 * Which CODE values are valid
	 * Treat like a constant.
	 *
	 * @var array
	 */
	protected $validCodes = array('', 'list', 'latest', 'single', 'menu');

	/**
     * Which "orderby" values are valid
     * Treat like a constant.
     *
     * @var array
     */
    protected $validOrderBy = array('', 'crdate', 'title');

	/**
     * Which "sort" values are valid
     * Treat like a constant.
     *
     * @var array
     */
    protected $validSort = array('', 'ASC', 'DESC');
	
	/**
	 * Gallery Repository
	 * 
	 * @var tx_aigallery_galleries
	 */
	protected $galleryRepository = null;


	/**
	 * The main method of the PlugIn
	 *
	 * @param string $content: The PlugIn content
	 * @param array	$conf: The PlugIn configuration
	 * @return The content that is displayed on the website
	 */
	function main($content, $conf) {

	    // Initialize.
	    $this->initialize($conf);

        // Erroneous configuration handling.
		if (false == $this->validConfiguration) {

			// TODO: Handle with error template.
			return '';
		}

		$content = '';

        switch ($this->conf['code']) {

			// List View.
			case self::CODE_LIST:
				$content .= $this->displayList();
				break;

			// Show latest gallery
			case self::CODE_LATEST:
				$content .= $this->displayLatest();
				break;

			// Show single gallery.
			case self::CODE_SINGLE:
				$content .= $this->displaySingle();
				break;

			// Show menu
		    case self::CODE_MENU:
                $content .= $this->displayMenu();
                break;

			default:
                // Add hook.
			    break;
        }

	    return $this->pi_wrapInBaseClass($content);
	}

	/**
	 * Initializes the Plugin
	 *
	 * @param array	$conf Typoscript configuration
	 * @return void
	 */
	protected function initialize($conf) {

		// Basic plugin init.
		$this->conf = $conf;
        $this->pi_setPiVarDefaults();
        $this->pi_loadLL();

		// Init and get the flexform data of the plugin
		$this->pi_initPIflexForm();
	    $this->lConf = array();

	    // Assign the flexform data to a local variable for easier access
	    $piFlexForm = $this->cObj->data['pi_flexform'];

	    // Traverse the entire array based on the language...
	    // and assign each configuration option to $this->lConf array...
	    if (count($piFlexForm['data']) > 0) {
	        foreach ($piFlexForm['data'] as $sheet => $data) {
	            foreach ($data as $lang => $value) {
	                foreach ($value as $key => $val) {
	                    $this->lConf[$key] = $this->pi_getFFvalue($piFlexForm, $key, $sheet);
	                }
	            }
	        }
	    }

		// Merge into the configuration.
	    $this->conf = array_merge($this->lConf, $this->conf);
        
		// Initializes the repository for galleries
        $galleryRepository = t3lib_div::makeInstance('tx_aigallery_galleries');
        $galleryRepository->initialize($this->cObj);
		
		$this->galleryRepository = $galleryRepository;
		
		// Parse Configuration.
		$this->parseConfiguration();

		// Modify paging if set in piVars.
		if (isset($this->piVars['page']) && t3lib_div::testInt($this->piVars['page'])) {

			$page = $this->piVars['page'];

			$start = ($page * $this->conf['defaults.']['pagingLength']) - 1;
			$length = $start + ($this->conf['defaults.']['pagingLength'] + 1);

			// Default offset for paging.
            $GLOBALS['TSFE']->register['TX_START_EXCLUSIVE'] = $start;
            $GLOBALS['TSFE']->register['TX_END_EXCLUSIVE'] = $length;
		}

		// Set CODE to 'single' if the user clicked a gallery in the list.
		if ($this->conf['code'] == self::CODE_LIST &&
		    isset($this->piVars['gallery']) &&
			t3lib_div::testInt($this->piVars['gallery'])) {

			$this->conf['code'] = self::CODE_SINGLE;
		}
		
		// Set remaining fields on repository
		$this->galleryRepository->setFields($this->conf['repositoryFields']);
		$this->galleryRepository->setOrderBy($this->conf['orderby']);
		$this->galleryRepository->setOrderDirection($this->conf['sort']);
	}

	/**
	 * Displays a list of all galleries
	 *
	 * @return string HTML Code
	 */
	protected function displayList() {
		$content = '';

		// Select all possible galleries
        $this->galleryRepository->load();
		
		// Read subparts
		$template = array();
		$template['list'] = $this->cObj->getSubpart($this->templateCode, '###TEMPLATE_LIST###');
		$template['content'] = $this->cObj->getSubpart($template['list'], '###CONTENT###');
		$template['item'] = $this->cObj->getSubpart($template['content'], '###GALLERY###');

		// Prepare output subparts.
		$subparts = array();
		$subparts['###CONTENT###'] = '';

		// Make sure there is no SQL Error.
		if ($this->galleryRepository->isValidResource()) {

            // Load rows
            $rows = $this->galleryRepository->getRows();

			// Walk galleries.
			$subpartItem = '';

			foreach ($rows as $row) {

				// Generate marker array.
                $markerArray = $this->getItemMarkerArray($row, self::SECTION_LIST);
                $gallery = $this->cObj->substituteMarkerArray($template['item'], $markerArray, '');

				// Check if we need to apply stdWrap per gallery.
                if (isset($this->conf[self::SECTION_LIST . '.']['rowWrap.'])) {
                    $gallery = $this->cObj->stdWrap($gallery, $this->conf[self::SECTION_LIST . '.']['rowWrap.']);
                }

				// Append to current subpart.
				$subpartItem .= $gallery;
			}

			// Check if we need to apply stdWrap for the whole gallery list.
            if (isset($this->conf[self::SECTION_LIST . '.']['galleryWrap.'])) {
                $subpartItem = $this->cObj->stdWrap($subpartItem, $this->conf[self::SECTION_LIST . '.']['galleryWrap.']);
            }

			// Get markers for the content field.
			$markerArray = array();

			if (isset($this->conf[self::SECTION_LIST . '.']) &&
			    isset($this->conf[self::SECTION_LIST . '.']['content.'])) {

			    // Make markers.
				$markerArray = $this->makeMarkerArray($row, $this->conf[self::SECTION_LIST . '.']['content.']);
			}

			// Substitute subpart
			$subparts['###CONTENT###'] = $this->cObj->substituteMarkerArrayCached($template['content'], $markerArray, array('###GALLERY###' => $subpartItem));

			$content .= $this->cObj->substituteMarkerArrayCached($template['list'], array(), $subparts);

		}

		// Generate language markers.
        $languageMarkers = $this->makeLanguageMarkers();

		// Final substitution.
        $content = $this->cObj->substituteMarkerArray($content, $languageMarkers);

		return $content;
	}

	/**
	 * Displays the latest gallery in a form of overview
	 *
	 * @return string
	 */
	protected function displayLatest() {
		$content = '';

        // Select all possible galleries
		$this->galleryRepository->setLimit('0,1');
        $this->galleryRepository->load();

        // Read subparts
        $template = array();
        $template['latest'] = $this->cObj->getSubpart($this->templateCode, '###TEMPLATE_LATEST###');
        $template['content'] = $this->cObj->getSubpart($template['latest'], '###CONTENT###');
        $template['item'] = $this->cObj->getSubpart($template['content'], '###GALLERY###');

        // Prepare output subparts.
        $subparts = array();
        $subparts['###CONTENT###'] = '';

        // Make sure there is no SQL Error.
        if ($this->galleryRepository->isValidResource()) {
            
			// Load rows
            $rows = $this->galleryRepository->getRows();
			
            // Walk galleries.
            $subpartItem = '';

            $row = $rows[0];

            // Generate marker array.
            $markerArray = $this->getItemMarkerArray($row, self::SECTION_LATEST);
            $gallery = $this->cObj->substituteMarkerArray($template['item'], $markerArray, '');

            // Check if we need to apply stdWrap per gallery.
            if (isset($this->conf[self::SECTION_LATEST . '.']['rowWrap.'])) {
                $gallery = $this->cObj->stdWrap($gallery, $this->conf[self::SECTION_LATEST . '.']['rowWrap.']);
            }

            // Append to current subpart.
            $subpartItem .= $gallery;


            // Check if we need to apply stdWrap for the whole gallery list.
            if (isset($this->conf[self::SECTION_LATEST . '.']['galleryWrap.'])) {
                $subpartItem = $this->cObj->stdWrap($subpartItem, $this->conf[self::SECTION_LATEST . '.']['galleryWrap.']);
            }

            // Get markers for the content field.
            $markerArray = array();

            if (isset($this->conf[self::SECTION_LATEST . '.']) &&
                isset($this->conf[self::SECTION_LATEST . '.']['content.'])) {

                // Make markers.
                $markerArray = $this->makeMarkerArray($row, $this->conf[self::SECTION_LATEST . '.']['content.']);
            }

            // Substitute subpart
            $subparts['###CONTENT###'] = $this->cObj->substituteMarkerArrayCached($template['content'], $markerArray, array('###GALLERY###' =>$subpartItem));

            $content .= $this->cObj->substituteMarkerArrayCached($template['latest'], array(), $subparts);

        }

        // Generate language markers.
        $languageMarkers = $this->makeLanguageMarkers();

        // Final substitution.
        $content = $this->cObj->substituteMarkerArray($content, $languageMarkers);

        return $content;
	}

	/**
	 * Displays a single gallery
	 *
	 * @return string HTML Code
	 */
	protected function displaySingle() {
		// Check which gallery we want to display.
		$gallery = 0;

		if (isset($this->piVars['gallery']) && t3lib_div::testInt($this->piVars['gallery'])) {

			// Get gallery from piVars.
			$gallery = (int) $this->piVars['gallery'];

		} else if (t3lib_div::testInt($this->conf['gallery'])) {

			// Get gallery from configuration.
            $gallery = (int) $this->conf['gallery'];

		}

		// Make sure we have a valid gallery.
		if (0 === $gallery) {
            return '';
		}

		$content = '';

        // Select all possible galleries
        $this->galleryRepository->setWhere(' AND uid = ' . $gallery);
        $this->galleryRepository->load();


        // Read subparts
        $template = array();
        $template['single'] = $this->cObj->getSubpart($this->templateCode, '###TEMPLATE_SINGLE###');
        $template['content'] = $this->cObj->getSubpart($template['single'], '###CONTENT###');
        $template['item'] = $this->cObj->getSubpart($template['content'], '###GALLERY###');

        // Prepare output subparts.
        $subparts = array();
        $subparts['###CONTENT###'] = '';

        // Make sure there is no SQL Error.
        if ($this->galleryRepository->isValidResource()) {

            // Load rows
            $rows = $this->galleryRepository->getRows();

            // Walk galleries.
            $subpartItem = '';

            $row = $rows[0];

            // Generate marker array.
            $markerArray = $this->getItemMarkerArray($row, self::SECTION_SINGLE);

            $gallery = $this->cObj->substituteMarkerArray($template['item'], $markerArray, '');

            // Check if we need to apply stdWrap per gallery.
            if (isset($this->conf[self::SECTION_SINGLE . '.']['rowWrap.'])) {
                $gallery = $this->cObj->stdWrap($gallery, $this->conf[self::SECTION_SINGLE . '.']['rowWrap.']);
            }

            // Append to current subpart.
            $subpartItem .= $gallery;

            // Check if we need to apply stdWrap for the whole gallery list.
            if (isset($this->conf[self::SECTION_SINGLE . '.']['galleryWrap.'])) {
                $subpartItem = $this->cObj->stdWrap($subpartItem, $this->conf[self::SECTION_SINGLE . '.']['galleryWrap.']);
            }

            // Get markers for the content field.
            $markerArray = array();

            if (isset($this->conf[self::SECTION_SINGLE . '.']) &&
                isset($this->conf[self::SECTION_SINGLE . '.']['content.'])) {

                // Make markers.
                $markerArray = $this->makeMarkerArray($row, $this->conf[self::SECTION_SINGLE . '.']['content.']);
            }

            // Substitute subpart
            $subparts['###CONTENT###'] = $this->cObj->substituteMarkerArrayCached($template['content'], $markerArray, array('###GALLERY###' =>$subpartItem));

            $content .= $this->cObj->substituteMarkerArrayCached($template['single'], array(), $subparts);

			// Change the page title (real and indexed search).
		    $GLOBALS['TSFE']->page['title'] = $row['title'];
		    $GLOBALS['TSFE']->indexedDocTitle = $row['title'];

        }

        // Generate language markers.
        $languageMarkers = $this->makeLanguageMarkers();

        // Final substitution.
        $content = $this->cObj->substituteMarkerArray($content, $languageMarkers);

        return $content;
	}

	/**
	 * Displays the gallery menu
	 *
	 * @return string
	 */
	protected function displayMenu() {
		$content = '';

        // Select all possible galleries
        $this->galleryRepository->load();

        // Read subparts
        $template = array();
        $template['menu'] = $this->cObj->getSubpart($this->templateCode, '###TEMPLATE_MENU###');
        $template['content'] = $this->cObj->getSubpart($template['menu'], '###CONTENT###');
        $template['item'] = $this->cObj->getSubpart($template['content'], '###GALLERY###');

        // Prepare output subparts.
        $subparts = array();
        $subparts['###CONTENT###'] = '';

        // Make sure there is no SQL Error.
        if ($this->galleryRepository->isValidResource()) {

            // Load rows
            $rows = $this->galleryRepository->getRows();

            // Walk galleries.
            $subpartItem = '';

            foreach ($rows as $row) {

                // Generate marker array.
                $markerArray = $this->getItemMarkerArray($row, self::SECTION_MENU);
                $gallery = $this->cObj->substituteMarkerArray($template['item'], $markerArray, '');

                // Check if we need to apply stdWrap per gallery.
                if (isset($this->conf[self::SECTION_MENU . '.']['rowWrap.'])) {
                    $gallery = $this->cObj->stdWrap($gallery, $this->conf[self::SECTION_MENU . '.']['rowWrap.']);
                }

                // Append to current subpart.
                $subpartItem .= $gallery;
            }

            // Check if we need to apply stdWrap for the whole gallery list.
            if (isset($this->conf[self::SECTION_MENU . '.']['galleryWrap.'])) {
                $subpartItem = $this->cObj->stdWrap($subpartItem, $this->conf[self::SECTION_MENU . '.']['galleryWrap.']);
            }

            // Get markers for the content field.
            $markerArray = array();

            if (isset($this->conf[self::SECTION_MENU . '.']) &&
                isset($this->conf[self::SECTION_MENU . '.']['content.'])) {

                // Make markers.
                $markerArray = $this->makeMarkerArray($row, $this->conf[self::SECTION_MENU . '.']['content.']);
            }

            // Substitute subpart
            $subparts['###CONTENT###'] = $this->cObj->substituteMarkerArrayCached($template['content'], $markerArray, array('###GALLERY###' =>$subpartItem));

            $content .= $this->cObj->substituteMarkerArrayCached($template['menu'], array(), $subparts);

        }

        // Generate language markers.
        $languageMarkers = $this->makeLanguageMarkers();

        // Final substitution.
        $content = $this->cObj->substituteMarkerArray($content, $languageMarkers);

        return $content;
	}

	/**
	 * Generates the marker array of a rowset
	 *
	 * @param array	$row Row from DB
	 * @param string $section Which section in the "fields." array we want to use
	 * @return array Marker Array
	 */
	protected function getItemMarkerArray($row, $section = 'singleView') {
		// Init marker array.
        $markerArray = array();

        // Read all fields.
        if (isset($this->conf[$section . '.']) &&
            isset($this->conf[$section . '.']['fields.'])) {

			// Make marker array.
            $markerArray = $this->makeMarkerArray($row, $this->conf[$section . '.']['fields.']);
        }

		return $markerArray;
	}

	/**
	 * Makes a marker array from local ts and row
	 *
	 * @param array	$row DB Row
	 * @param array	$tsFields Typoscript field definitions
	 * @return array
	 */
	protected function makeMarkerArray($row, $tsFields) {
		// Init marker array.
        $markerArray = array();

		// Start local cObj
        $cObj = t3lib_div::makeInstance('tslib_cObj');
        $cObj->start($row, $this->galleryRepository->getTable());

		foreach ($tsFields as $field => $cType) {

            // Skip if no config was found for the field.
            if (!is_array($tsFields[$field . '.'])) {
                continue;
            }

            // Get local TS for the field.
            $localTs = $tsFields[$field . '.'];

            // Fill marker.
            $marker = '###' . strtoupper($field) . '###';
            $markerArray[$marker] = $cObj->cObjGetSingle($cType, $localTs);
        }

		return $markerArray;
	}

	/**
	 * Makes a marker array from all fields in the locallang
	 *
	 * @return array
	 */
	protected function makeLanguageMarkers() {

	   // Initialize marker array.
	   $markerArray = array();

	   // Get prefix.
	   $prefix = isset($this->conf['llPrefix']) ? $this->conf['llPrefix'] : '';

	   // Pass all language values into the marker
	   $language = array_merge($this->LOCAL_LANG['default'], $this->LOCAL_LANG[$this->LLkey]);

	   foreach ($language as $llKey => $llValue) {
	   	  $marker = '###' . $prefix . strtoupper($llKey) . '###';

	   	  $markerArray[$marker] = $llValue;
	   }

	   return $markerArray;
	}

	/**
	 * Parses the merged configuration of flexforms and TS Config
	 * Validates the data.
	 *
	 * @return void
	 */
	protected function parseConfiguration() {
		// 1. Startingpoint:
		if (isset($this->conf['startingpoint'])) {

			// Needs to be numeric.
			if ('' == trim($this->conf['startingpoint']) ||
			    !t3lib_div::testInt($this->conf['startingpoint'])) {
				$this->validConfiguration = false;
			}

		} else {
			$this->validConfiguration = false;
		}

		// 2. Code
		if (isset($this->conf['code'])) {

			// Whitelist.
			if (!in_array($this->conf['code'], $this->validCodes)) {
				$this->validConfiguration = false;
			}

		} else {
			$this->validConfiguration = false;
		}

		// 3. Order-By
		if (isset($this->conf['orderby'])) {

			// Whitelist.
            if (!in_array($this->conf['orderby'], $this->validOrderBy)) {
                $this->validConfiguration = false;
            }

		} else {
			$this->validConfiguration = false;
		}

		// 4. Sort
        if (isset($this->conf['sort'])) {

			// Whitelist.
            if (!in_array($this->conf['sort'], $this->validSort)) {
                $this->validConfiguration = false;
            }

        } else {
            $this->validConfiguration = false;
        }
		
		// 5. Repository Fields
		if (isset($this->conf['repositoryFields'])) {
			
			// Verify fields if so wanted
            if ($this->conf['verifyRepositoryFields'] && !$this->galleryRepository->verifyFields($this->conf['repositoryFields'])) {
            	$this->validConfiguration = false;
            }

		} else {
			$this->validConfiguration = false;
		}

		// 6. Gallery
        if (isset($this->conf['gallery'])) {

			// Needs to be numeric.
            if ('' != trim($this->conf['gallery']) &&
                !t3lib_div::testInt($this->conf['gallery'])) {
                $this->validConfiguration = false;
            }

        } else {
            $this->validConfiguration = false;
        }

		// 7. Template File - template_file = Flexforms, templateFile = TS
        if (isset($this->conf['template_file']) && isset($this->conf['templateFile'])) {

		    // Either one has to be filled.
            if ('' != trim($this->conf['templateFile']) ||
			    '' != trim($this->conf['template_file'])) {

				// Flexform overrides TS!
				$templateFile = '';

				if ('' != trim($this->conf['template_file'])) {
					$templateFile = $this->conf['template_file'];
				} else {
					$templateFile = $this->conf['templateFile'];
				}

				// Read template code.
				$this->templateCode = $this->cObj->fileResource($templateFile);

            } else {
            	$this->validConfiguration = false;
            }

        } else {
            $this->validConfiguration = false;
        }

		// TYPOSCRIPT defaults.
		if (isset($this->conf['defaults.'])) {

			// Paging.
			if (!t3lib_div::testInt($this->conf['defaults.']['pagingStart'])) {
				$this->validConfiguration = false;
			}
			if (!t3lib_div::testInt($this->conf['defaults.']['pagingLength'])) {
                $this->validConfiguration = false;
            }

		} else {
			$this->validConfiguration = false;
		}

		// Apply default offset for paging.
        $GLOBALS['TSFE']->register['TX_START_EXCLUSIVE'] = intval($this->conf['defaults.']['pagingStart']) - 1;
        $GLOBALS['TSFE']->register['TX_END_EXCLUSIVE'] = intval($this->conf['defaults.']['pagingLength']);
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ai_gallery/pi1/class.tx_aigallery_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ai_gallery/pi1/class.tx_aigallery_pi1.php']);
}

?>