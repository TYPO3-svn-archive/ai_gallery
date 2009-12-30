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

require_once (t3lib_extMgm::extPath('ai_gallery') .'classes/Repository/class.tx_aigallery_repository.php');

/**
 * Repository for all galleries handled by this extension
 *
 * @author aijko GmbH <info@aijko.de>
 * @package TYPO3
 * @subpackage tx_aigallery
 */
class tx_aigallery_galleries extends tx_aigallery_repository {
	
	/**
     * Table
     * 
     * @var string
     */
    protected $table = 'tx_aigallery_galleries';
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ai_gallery/classes/Repository/class.tx_aigallery_galleries.php'])   {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ai_gallery/classes/class.tx_aigallery_galleries.php']);
}

?>