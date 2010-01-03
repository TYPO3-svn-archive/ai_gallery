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
 *   56: class tx_aigallery_repository
 *  128:     public function initialize($cObj)
 *  140:     public function load()
 *  157:     public function getRows()
 *  178:     public function isValidResource()
 *  188:     public function setWhere($where)
 *  198:     public function setOrderBy($order)
 *  208:     public function setOrderDirection($direction)
 *  218:     public function setLimit($limit)
 *  228:     public function setFields($fields)
 *  237:     public function getTable()
 *  247:     public function verifyFields($fields)
 *  266:     protected function hook_beforeLoad()
 *  276:     protected function hook_afterGetRows(&$rows)
 *
 * TOTAL FUNCTIONS: 13
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * Repository blueprint
 *
 * @author aijko GmbH <info@aijko.de>
 * @package TYPO3
 * @subpackage tx_aigallery
 */
class tx_aigallery_repository {

	/**
	 * Where-Clause
	 *
	 * @var string
	 */
	protected $where = '';

	/**
	 * Order-By
	 *
	 * @var string
	 */
	protected $orderBy = '';

	/**
	 * Order-By Direction
	 *
	 * @var string
	 */
	protected $orderByDirection = '';

	/**
	 * LIMIT clause
	 *
	 * @var string
	 */
	protected $limit = '';

	/**
	 * Enable fields
	 *
	 * @var string
	 */
	protected $enableFields = '';

	/**
	 * Fields that should be read
	 * comma-separated
	 *
	 * @var string
	 */
	protected $fields = '';

	/**
	 * Table
	 *
	 * @var string
	 */
	protected $table = '';

	/**
	 * DB Resource after the load method was called
	 *
	 * @var resource
	 */
	protected $resource = null;

	/**
	 * Flag if the loaded resource is valid
	 *
	 * @var boolean
	 */
	protected $validResource = false;

	/**
	 * Initializes the repository
	 *
	 * @param object $cObj cObject of plugin
	 * @return void
	 */
	public function initialize($cObj) {

		// Get enable fields
		$this->enableFields = $cObj->enableFields($this->table);

	}

	/**
	 * Loads all records from the galleries table and stores them
	 *
	 * @return void
	 */
	public function load() {

        // Hook: before load
        $this->hook_beforeLoad();

		// Load records
		$this->res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($this->fields, $this->table, '1=1' . $this->enableFields . $this->where, '', trim($this->orderBy . ' ' . $this->orderByDirection), $this->limit);

		// Check if resource is valid
		$this->validResource = (is_resource($this->res) && $GLOBALS['TYPO3_DB']->sql_num_rows($this->res) > 0);
	}

	/**
	 * Returns all records that were loaded as rows
	 *
	 * @return array
	 */
	public function getRows() {

		$rows = array();

		if ($this->validResource) {
		    while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->res)) {
			    $rows[] = $row;
		    }
		}

		// Hook: after getRows
        $this->hook_afterGetRows($rows);

		return $rows;
	}

	/**
	 * Returns if the resource is valid
	 *
	 * @return boolean
	 */
	public function isValidResource() {
		return $this->validResource;
	}

	/**
	 * Sets the where clause
	 *
	 * @param string $where WHERE-Clause
	 * @return void
	 */
	public function setWhere($where) {
		$this->where = $where;
	}

	/**
	 * Sets the order-by clause
	 *
	 * @param string $order Order-By-Clause
	 * @return void
	 */
    public function setOrderBy($order) {
        $this->orderBy = $order;
    }

	/**
	 * Sets the order-by direction
	 *
	 * @param string $direction Order-By Direction
	 * @return void
	 */
	public function setOrderDirection($direction) {
		$this->orderByDirection = $direction;
	}

	/**
	 * Sets the limit clause
	 *
	 * @param string $limit LIMIT-Clause
	 * @return void
	 */
    public function setLimit($limit) {
        $this->limit = $limit;
    }

	/**
	 * Sets the fields to read
	 *
	 * @param string $fields comma-separated list of fields
	 * @return void
	 */
	public function setFields($fields) {
		$this->fields = $fields;
	}

	/**
	 * Returns the table of this repository
	 *
	 * @return string
	 */
	public function getTable() {
		return $this->table;
	}

	/**
	 * Verifies if a given set of fields actually exist in a given table
	 *
	 * @param string	$fields comma-separated fields to test against
	 * @return boolean
	 */
	public function verifyFields($fields) {

		// Table fields
		$tableFields = array_keys($GLOBALS['TYPO3_DB']->admin_get_fields($this->table));

		// Testfields
		$testFields = t3lib_div::trimExplode(',', $fields);

		// Verify that all testFields are in tableFields
		$diff = array_diff($testFields, $tableFields);

		return (0 == count($diff));
	}

	/**
	 * Method stub - can be used by extending repositories
	 *
	 * @return	void
	 */
	protected function hook_beforeLoad() {

	}

	/**
	 * Method stub - can be used by extending repositories
	 *
	 * @param array	$rows Rows that were loaded
	 * @return void
	 */
	protected function hook_afterGetRows(&$rows) {

	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ai_gallery/classes/Repository/class.tx_aigallery_repository.php'])   {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ai_gallery/classes/class.tx_aigallery_repository.php']);
}

?>