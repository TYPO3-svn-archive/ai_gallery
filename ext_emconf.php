<?php

########################################################################
# Extension Manager/Repository config file for ext "ai_gallery".
#
# Auto generated 03-01-2010 16:54
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'aijko Gallery',
	'description' => 'Enables galleries for TYPO3.',
	'category' => 'fe',
	'author' => 'aijko GmbH',
	'author_email' => 'info@aijko.de',
	'shy' => '',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => 'uploads/tx_aigallery/rte/',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => 'aijko GmbH',
	'version' => '0.0.5',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:31:{s:9:"ChangeLog";s:4:"0552";s:10:"README.txt";s:4:"723f";s:21:"ext_conf_template.txt";s:4:"8810";s:12:"ext_icon.gif";s:4:"d801";s:17:"ext_localconf.php";s:4:"e59e";s:15:"ext_php_api.dat";s:4:"bd5c";s:14:"ext_tables.php";s:4:"5388";s:14:"ext_tables.sql";s:4:"a3a1";s:34:"classes/class.tx_aigallery_lib.php";s:4:"99cf";s:37:"classes/class.tx_aigallery_tsfunc.php";s:4:"e8a3";s:51:"classes/Repository/class.tx_aigallery_galleries.php";s:4:"24e7";s:52:"classes/Repository/class.tx_aigallery_repository.php";s:4:"95f6";s:14:"doc/manual.sxw";s:4:"a6cc";s:19:"doc/wizard_form.dat";s:4:"d4ca";s:20:"doc/wizard_form.html";s:4:"9874";s:39:"hooks/class.tx_aigallery_cachehooks.php";s:4:"d357";s:36:"hooks/class.tx_aigallery_dmhooks.php";s:4:"3331";s:30:"pi1/class.tx_aigallery_pi1.php";s:4:"8c61";s:17:"pi1/locallang.xml";s:4:"c69f";s:38:"resources/backend/flexforms/ds_pi1.xml";s:4:"84d9";s:55:"resources/backend/icons/icon_tx_aigallery_galleries.png";s:4:"a4ad";s:55:"resources/backend/locallang/locallang_csh_galleries.xml";s:4:"1d59";s:44:"resources/backend/locallang/locallang_db.xml";s:4:"4453";s:29:"resources/backend/tca/tca.php";s:4:"228c";s:47:"resources/backend/typoscript/rte/linkhandler.ts";s:4:"1f4a";s:43:"resources/frontend/realurl/realurl_conf.php";s:4:"89ab";s:37:"resources/frontend/templates/pi1.html";s:4:"dd8f";s:57:"resources/frontend/typoscript/aijko_gallery/constants.txt";s:4:"26bd";s:53:"resources/frontend/typoscript/aijko_gallery/setup.txt";s:4:"baa5";s:22:"tests/lib_testcase.php";s:4:"8337";s:29:"tests/repository_testcase.php";s:4:"f1c0";}',
	'suggests' => array(
	),
);

?>