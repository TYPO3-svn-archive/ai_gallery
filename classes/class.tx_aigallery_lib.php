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
 *   47: class tx_aigallery_lib
 *   73:     function tsCountImages($content, $conf)
 *  104:     function tsGalleryNavigation($content, $conf)
 *  219:     function tsPaginator($content, $conf)
 *  304:     protected function initializeRepository()
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * Library functions for 'ai_gallery'
 *
 * @author aijko GmbH <info@aijko.de>
 * @package TYPO3
 * @subpackage ai_gallery
 */
class tx_aigallery_lib {
	// Internal.
	const DEFAULT_IMAGE_FIELD = 'images';
	const DEFAULT_PAGING_LENGTH = 30;

	/**
	 * cObj - set from outside
	 *
	 * @var tslib_cObj
	 */
	public $cObj;

	/**
     * Gallery Repository
     *
     * @var tx_aigallery_galleries
     */
    protected $galleryRepository = null;

	/**
	 * Used to count the images via Typoscript
	 *
	 * @param string $content The current value - empty most of the time, and irrelevant in this case
	 * @param array $conf TS Configuration for this field
	 * @return string
	 */
	function tsCountImages($content, $conf)
	{

		// Get image field.
		$imageField = self::DEFAULT_IMAGE_FIELD;

		// Overwrite the image field from TS.
		if (isset($conf['imageField'])) {
			$imageField = $conf['imageField'];
		}

		// Count images.
		$images = explode(',', $this->cObj->data[$imageField]);
		$imageCount = count($images);

		// check if we need to apply the standard wrap
		// TODO: What if locallang markers appear?
		if (isset($conf['stdWrap.'])) {
			$imageCount = $this->cObj->stdWrap($imageCount, $conf['stdWrap.']);
		}

		return $imageCount;
	}

	/**
	 * Used to print out the gallery navigation
	 *
	 * @param string $content The current value
	 * @param array $conf TS Configuration for this field
	 * @return string
	 */
	function tsGalleryNavigation($content, $conf)
	{
		// read all possible galleries
		$this->initializeRepository();
		$this->galleryRepository->setFields($conf['repositoryFields']);

        // TODO: add orderBy field (is in flexforms).
        $this->galleryRepository->load();

		if ($this->galleryRepository->isValidResource()) {

			// Load rows
            $rows = $this->galleryRepository->getRows();

			// Prepare.
            $currentUid = $this->cObj->data['uid'];
			$tempPrevious = null;
			$tempNext = null;
			$currentReached = false;

			// Walk the galleries to find previous and next.
			foreach ($rows as $row) {

				if ($row['uid'] == $currentUid) {

					// The current record is this.
					$currentReached = true;
				} else if (!$currentReached) {

					// Store
					$tempPrevious = $row;

				} else {

					// We reached the next gallery-
					$tempNext = $row;
					break;
				}
			}

			// Get previous and next.
			$previous = $tempPrevious;
			$next = $tempNext;

			// Render elements
			if (($previous || $next) && (isset($conf['render.']))) {

	            // get indexes of next and previous.
	            $indexPrev = $conf['render.']['prev'];
				$indexNext = $conf['render.']['next'];

				// Unset those entries so we can render the list.
				unset($conf['render.']['prev'], $conf['render.']['next']);

				$listConf = $conf['render.'];

				// Begin to render every element
				foreach ($listConf as $index => $cType) {

					if ($index == $indexNext) {

						// Render with the next record as basis.
						if ($next) {

							// Make localTs.
							$localTs = $listConf[$index . '.'];

							// Initialize cObj.
							$cObj = t3lib_div::makeInstance('tslib_cObj');
                            $cObj->start($next, $this->galleryRepository->getTable());

							$content .= $cObj->cObjGetSingle($cType, $localTs);
						}

					} else if ($index == $indexPrev) {

						// Render with the previous record as basis.
                        if ($previous) {

                            // Make localTs.
                            $localTs = $listConf[$index . '.'];

                            // Initialize cObj.
                            $cObj = t3lib_div::makeInstance('tslib_cObj');
                            $cObj->start($previous, $this->galleryRepository->getTable());

                            $content .= $cObj->cObjGetSingle($cType, $localTs);
                        }

					} else {

						// Render with current gallery row as basis.
						$localTs = $listConf[$index . '.'];

						$content .= $this->cObj->cObjGetSingle($cType, $localTs);
					}
				}

				// Wrap the whole thing with the stdWrap.
				if (isset($conf['stdWrap.'])) {
				    $content = $this->cObj->stdWrap($content, $conf['stdWrap.']);
				}
			}
		}

		return $content;
	}

	/**
	 * Used to print out the paginator
	 *
	 * @param string $content The current value
	 * @param array $conf TS Configuration for this field
	 * @return string
	 */
	function tsPaginator($content, $conf)
	{
		// Determine paging length.
		$pagingLength = self::DEFAULT_PAGING_LENGTH;

		// Overwrite by TS.
		if (t3lib_div::testInt($conf['pagingLength'])) {
			$pagingLength = (int) $conf['pagingLength'];
		}

		// Overwrite by DB is wanted.
		// display all images = no paging.
		if ('all' == $this->cObj->data['max_images']) {
			return '';
		}

		// 0 = default, and default is determined by TS.
		if (0 != $this->cObj->data['max_images']) {
			$pagingLength = (int) $this->cObj->data['max_images'];
		}

		// Get image field.
        $imageField = self::DEFAULT_IMAGE_FIELD;

        // Overwrite the image field from TS.
        if (isset($conf['imageField'])) {
            $imageField = $conf['imageField'];
        }

		// Determine number of pages
		$imgCount = count(explode(',', $this->cObj->data[$imageField]));

		$pages = ceil($imgCount / $pagingLength);

		// Get the current page.
		// TODO: make with piVars.
		$piVars = t3lib_div::_GP('tx_aigallery_pi1');
		$currentPage = (isset($piVars['page'])) ? (int) $piVars['page'] : 0;

		// Start output.
		for ($i = 0; $i < $pages; $i ++) {

			// Set register to current page
			$GLOBALS['TSFE']->register['TX_AIGALLERY_PAGE'] = $i;

			$stdWrap = array();

			if ($i == $currentPage) {

				// stdWrap for CUR.
				if (isset($conf['pages.']) && isset($conf['pages.']['CUR_stdWrap.'])) {
					$stdWrap =  $conf['pages.']['CUR_stdWrap.'];
				}

			} else {

				// stdWrap for NO.
                if (isset($conf['pages.']) && isset($conf['pages.']['NO_stdWrap.'])) {
                    $stdWrap = $conf['pages.']['NO_stdWrap.'];
                }

			}

			// Add one, since we start at 0.
            $page = ($i + 1);

            // Apply stdWrap.
            $page = $this->cObj->stdWrap($page, $stdWrap);

            $content .= $page;
		}

		// Wrap the whole thing with the stdWrap.
        if (isset($conf['pages.']) && isset($conf['pages.']['stdWrap.'])) {
            $content = $this->cObj->stdWrap($content, $conf['pages.']['stdWrap.']);
        }

		return $content;
	}

	/**
	 * Initializes the repository for galleries
	 *
	 * @return void
	 */
	protected function initializeRepository() {

		// Initializes the repository for galleries
        $galleryRepository = t3lib_div::makeInstance('tx_aigallery_galleries');
        $galleryRepository->initialize($this->cObj);

        $this->galleryRepository = $galleryRepository;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ai_gallery/classes/class.tx_aigallery_lib.php'])   {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ai_gallery/classes/class.tx_aigallery_lib.php']);
}

?>