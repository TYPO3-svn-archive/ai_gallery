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

require_once (t3lib_extMgm::extPath('ai_gallery') .'classes/Repository/class.tx_aigallery_galleries.php');

/**
 * TestCase for repository 
 *
 * @author  aijko GmbH <info@aijko.de>
 * @package TYPO3
 * @subpackage tx_aigallery
 */
class repository_testcase extends tx_phpunit_testcase {
	
	const VALID_FIELDS = 'uid,pid,title,description, images, alt_attributes, title_attributes,image_descriptions';
	const INVALID_FIELDS = 'uuid,pid,title,description, images, alt_attributes, title_attributes,image_descriptions';
	const TABLE_GALLERIES = 'tx_aigallery_galleries';
	
	/**
	 * Gallery Repository
	 * 
	 * @var tx_aigallery_galleries
	 */
	protected $repository = null;
	
	/**
	 * Build the inventory
	 * 
	 * @return void 
	 */
	public function setUp() {  
	    $this->repository = t3lib_div::makeInstance('tx_aigallery_galleries');
	}
	
	/**
	 * Tests the table name
	 * 
	 * @return void
	 */
	public function testTableName() {
        $this->assertEquals(self::TABLE_GALLERIES, $this->repository->getTable());
	}
	
	/**
	 * Tests if the verification of fields works
	 * 
	 * @return void
	 */
	public function testFieldVerification() {
		
		// Set valid fields and try for correct check
		$this->assertEquals(true, $this->repository->verifyFields(self::VALID_FIELDS));
		
		// Set invalid fields and try for incorrect check
		$this->assertEquals(false, $this->repository->verifyFields(self::INVALID_FIELDS));
	}
	
	/**
	 * Tests if the repository correctly identifies invalid and empty resources
	 * 
	 * @return void
	 */
	public function testValidResource() {
		
		// Invalid SQL
		$this->repository->setFields('IDontExistAsAField');
		$this->repository->load();
		
		$this->assertEquals(false, $this->repository->isValidResource());
		
		// Empty Set
		$this->repository->setFields(self::VALID_FIELDS);
		$this->repository->setLimit('0');
        $this->repository->load();
        
        $this->assertEquals(false, $this->repository->isValidResource());
		$this->repository->setLimit('');
	}
	
	
}
?>