<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1'] = 'layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY . '_pi1'] = 'pi_flexform'; 

// Plugins
t3lib_extMgm::addPlugin(array(
	'LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
), 'list_type');
t3lib_extMgm::addPiFlexFormValue($_EXTKEY . '_pi1', 'FILE:EXT:' . $_EXTKEY . '/resources/backend/flexforms/ds_pi1.xml');


// TCA
$TCA['tx_aigallery_galleries'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries',		
		'label'     => 'title',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',	
			'starttime' => 'starttime',	
			'endtime' => 'endtime',	
			'fe_group' => 'fe_group',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'resources/backend/tca/tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'resources/backend/icons/icon_tx_aigallery_galleries.png',
	),
);

// Add static typoscript.
t3lib_extMgm::addStaticFile($_EXTKEY, 'resources/frontend/typoscript/aijko_gallery/', 'aijko Gallery');
?>