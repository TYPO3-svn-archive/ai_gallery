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

/**
 * Hooks for datamap processing
 *
 * @author aijko GmbH <info@aijko.de>
 * @package TYPO3
 * @subpackage tx_aigallery
 */
class tx_aigallery_dmhooks	{
    
	const TABLE_GALLERIES = 'tx_aigallery_galleries';
	
	/**
	 * Hook to process field array before any values are stripped
	 * 
	 * @param array $incomingFieldArray
	 * @param string $table
	 * @param int $id
	 * @param object $pObj
	 * @return void
	 */
	public function processDatamap_preProcessFieldArray(&$incomingFieldArray, $table, $id, &$pObj)    {
		
		switch (strtolower((string)$table))	{
			case self::TABLE_GALLERIES:

				// Check if live update is disabled - if so, import all images at once
				if (isset($incomingFieldArray['live_update']) && 
				    0 == $incomingFieldArray['live_update'] &&
					isset($incomingFieldArray['CType']) &&
					'automatic' == $incomingFieldArray['CType']) {
					
					// Read all files in the folder the user specified
					$folder = PATH_site . $incomingFieldArray['image_folder'];
						
					$incomingFieldArray['images'] = tx_aigallery_lib::getImagesInDir($folder);
				}
			break;
		}
	}
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']["ext/ai_gallery/hooks/class.tx_aigallery_dmhooks.php"])	{
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']["ext/ai_gallery/hooks/class.tx_aigallery_dmhooks.php"]);
}
?>
