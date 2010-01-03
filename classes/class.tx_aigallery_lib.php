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
 *   44: class tx_aigallery_lib
 *   54:     public static function getImagesInDir($folder)
 *
 * TOTAL FUNCTIONS: 1
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

	const TABLE_GALLERIES = 'tx_aigallery_galleries';

	/**
	 * Returns a comma separated list of all images in a folder
	 *
	 * @param string $folder absolute Path to the dir
	 * @return string
	 */
	public static function getImagesInDir($folder) {

		$images = '';

		t3lib_div::loadTCA(self::TABLE_GALLERIES);
        $allowedExt = $GLOBALS['TCA'][self::TABLE_GALLERIES]['columns']['images']['config']['allowed'];

        if (t3lib_div::isAbsPath($folder)) {

            $files = t3lib_div::getFilesInDir($folder, $allowedExt);

            // Add files to be imported
            foreach ($files as $file) {
                $images .= $folder . $file . ',';
            }

            $images = substr($images, 0, -1);
        }

		return $images;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ai_gallery/classes/class.tx_aigallery_lib.php'])   {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ai_gallery/classes/class.tx_aigallery_lib.php']);
}

?>