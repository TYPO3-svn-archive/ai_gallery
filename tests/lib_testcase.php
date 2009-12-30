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

require_once (t3lib_extMgm::extPath('ai_gallery') .'classes/class.tx_aigallery_lib.php');

/**
 * TestCase for library functions 
 *
 * @author  aijko GmbH <info@aijko.de>
 * @package TYPO3
 * @subpackage tx_aigallery
 */
class lib_testcase extends tx_phpunit_testcase {

	const TABLE_GALLERIES = 'tx_aigallery_galleries';
	
	/**
	 * Temporary directory
	 * 
	 * @var string
	 */
	protected $tmpDirectory = '';
	
	/**
	 * Image Files we create
	 * 
	 * @var
	 */
	protected $imageFiles = array('01.jpg','02.png', '03.gif', '04.jpeg');
	
	/**
	 * Create a temporary directory to test image capabilities
	 * 
	 * @return void 
	 */
	public function setUp() {  
	    
		// Create directory
		$tmpFolder = 'ai_gallery_test_' . time();
		$path = PATH_site . 'typo3temp/temp/' . $tmpFolder;
		
		mkdir($path);
		
		$this->tmpDirectory = $path . '/';
		
		// Create files.
		if(is_dir($this->tmpDirectory)) {
			
			foreach ($this->imageFiles as $file) {
				touch($this->tmpDirectory . $file);
			}
		}
		
	}
	
	/**
	 * Tests if the directory was created
	 * 
	 * @return void
	 */
	public function testTemporaryDirectory() {
        $this->assertTrue(is_dir($this->tmpDirectory));
	}
	
	/**
	 * Tests if the files were created
	 * 
	 * @return void 
	 */
	public function testImageFiles() {
		
		foreach ($this->imageFiles as $file) {
			$this->assertTrue(is_file($this->tmpDirectory . $file));
		}
		
	}
	
	/**
	 * Tests if the getImagesInDir function in lib runs
	 * 
	 * @return void
	 */
	public function testGetImagesInDir() {
		
		// Read.
		$files = explode(',', tx_aigallery_lib::getImagesInDir($this->tmpDirectory));
		
		// Make sure directory was read.
		$this->assertFalse(empty($files));
		$this->assertTrue(count($this->imageFiles) === count($files));
	
	    // Make sure all files are present
		foreach ($this->imageFiles as $file) {
			$this->assertTrue(in_array($this->tmpDirectory . $file, $files));
		}
	}
	
	/**
	 * Delete the temp folder
	 * 
	 * @return void
	 */
	public function tearDown() {
		
		// Delete all files
		if (is_dir($this->tmpDirectory)) {
			foreach ($this->imageFiles as $file) {
				@unlink($this->tmpDirectory . $file);
			}
		}
		
		// Delete folder
		rmdir($this->tmpDirectory);
	}
}
?>