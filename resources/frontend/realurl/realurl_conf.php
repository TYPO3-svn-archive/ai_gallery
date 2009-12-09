<?php
if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'])) {
	
	if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']) &&
	    is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['postVarSets']) &&
		is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['postVarSets']['_DEFAULT'])) {
	
	    // Gallery Keyword.
	    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['postVarSets']['_DEFAULT']['gallery'] = array (
            '0' => array (
                'GETvar' => 'tx_aigallery_pi1[gallery]',
                'lookUpTable' => array (
                    'table' => 'tx_aigallery_galleries',
                    'id_field' => 'uid',
                    'alias_field' => 'title',
                    'addWhereClause' => ' AND NOT deleted',
                    'useUniqueCache' => '1',
                    'useUniqueCache_conf' => array (
                        'strtolower' => '1',
                        'spaceCharacter' => '-',
                    ),
                ),
            ),
        );
		
		// Gallery Keyword.
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['postVarSets']['_DEFAULT']['gallery-page'] = array (
            '0' => array (
                'GETvar' => 'tx_aigallery_pi1[page]',
            ),
        );
	    	
	}
	
}
?>