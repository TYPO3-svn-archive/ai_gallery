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

require_once (t3lib_extMgm::extPath('ai_gallery') .'classes/class.tx_aigallery_lib.php');
require_once (t3lib_extMgm::extPath('ai_gallery') .'classes/Repository/class.tx_aigallery_galleries.php');

/**
 * Hooks for datamap processing
 *
 * @author aijko GmbH <info@aijko.de>
 * @package TYPO3
 * @subpackage tx_aigallery
 */
class tx_aigallery_cachehooks	{
    
	const TABLE_GALLERIES = 'tx_aigallery_galleries';
	
	/**
	 * Do live update of galleries after the cache is cleared
	 * 
	 * @return void 
	 */
	public function liveUpdate($params) {
        
		switch ($params['cacheCmd']) {
			
			// If "all" or only "pages" cache is flushed
			case 'all':
			case 'pages':
						
				// Get all galleries that have "live update" enabled
				$galleryRepository = t3lib_div::makeInstance('tx_aigallery_galleries');
				$galleryRepository->setFields('uid,image_folder');
                $galleryRepository->setWhere(' AND deleted = 0 AND hidden = 0 AND live_update = 1');
                
				$galleryRepository->load();
				
				$rows = $galleryRepository->getRows();
				
				if (0 < count($rows)) {
					
					$datamap = array();
				
					// Walk each gallery
					foreach ($rows as $row) {
						
						// Get all images.
						$folder = PATH_site . $row['image_folder'];
						$images = tx_aigallery_lib::getImagesInDir($folder);
						
						// Prepare datamap
						$datamap[self::TABLE_GALLERIES][$row['uid']]['images'] = $images;
					}
					
					// Store using datamap
	                $tce = t3lib_div::makeInstance('t3lib_TCEmain');
					
					$tce->start($datamap, array());
	                $tce->process_datamap();
				}
				
			    break;
		}
	}
	
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']["ext/ai_gallery/hooks/class.tx_aigallery_cachehooks.php"])	{
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']["ext/ai_gallery/hooks/class.tx_aigallery_cachehooks.php"]);
}
?>
