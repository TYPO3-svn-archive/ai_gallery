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
	 * @var array
	 */
	protected $imageFiles = array('01.jpg','02.png', '03.gif', '04.jpeg');
	
	/**
	 * Example Array of files we will create, but should be excluded
	 * 
	 * @var array
	 */
	protected $invalidFiles = array('05.pdf', '06.doc', '07.psd', '08.jp');
	
	/**
	 * Allowed image extensions
	 * 
	 * @var array
	 */
	protected $allowedExtensions = array('gif', 'png', 'jpg', 'jpeg');
	
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
			
			foreach ($this->invalidFiles as $invalidFile) {
				touch($this->tmpDirectory . $invalidFile);
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
	 * Tests if the image files were created
	 * 
	 * @return void 
	 */
	public function testImageFiles() {
		
		foreach ($this->imageFiles as $file) {
			$this->assertTrue(is_file($this->tmpDirectory . $file));
		}
		
	}
	
	/**
     * Tests if the invalid files were created
     * 
     * @return void 
     */
    public function testInvalidFiles() {
        
        foreach ($this->invalidFiles as $invalidFile) {
            $this->assertTrue(is_file($this->tmpDirectory . $invalidFile));
        }
        
    }
	
	/**
     * Tests if the valid image extensions in the TCA are as expected
     * 
     * @return void 
     */
    public function testAllowedImageExtensions() {
        
        t3lib_div::loadTCA(self::TABLE_GALLERIES);
        
        $tcaImageExtensions = explode(',', $GLOBALS['TCA'][self::TABLE_GALLERIES]['columns']['images']['config']['allowed']);
        
        // Make sure we deal with an array
        $this->assertTrue(is_array($tcaImageExtensions));
        $this->assertTrue(count($tcaImageExtensions) == count($this->allowedExtensions));
        
        // Make sure the same extensions are allowed
        $this->assertTrue(0 === count(array_diff($tcaImageExtensions, $this->allowedExtensions)));
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
		
		// Make sure non of the invalid files are present 
		foreach ($this->invalidFiles as $invalidFile) {
            $this->assertFalse(in_array($this->tmpDirectory . $invalidFile, $files));
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
			
			foreach ($this->invalidFiles as $invalidFile) {
                @unlink($this->tmpDirectory . $invalidFile);
            }
		}
		
		// Delete folder
		rmdir($this->tmpDirectory);
	}
}
?>