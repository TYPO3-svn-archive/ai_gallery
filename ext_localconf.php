<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

// Plugins.
t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_aigallery_pi1.php', '_pi1', 'list_type', 1);

// Page TS.
t3lib_extMgm::addPageTSConfig('

	# ***************************************************************************************
	# CONFIGURATION of RTE in table "tx_aigallery_galleries", field "description"
	# ***************************************************************************************
	RTE.config.tx_aigallery_galleries.description {
	  hidePStyleItems = H1, H4, H5, H6
	  proc.exitHTMLparser_db=1
	  proc.exitHTMLparser_db {
	    keepNonMatchedTags=1
	    tags.font.allowedAttribs= color
	    tags.font.rmTagIfNoAttrib = 1
	    tags.font.nesting = global
	  }
	}
');

// Add TinyMCE linkhandler
t3lib_extMgm::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/resources/backend/typoscript/rte/linkhandler.ts">');

// Get extension configuration
$extConf = unserialize($_EXTCONF);

// Add realUrl configuration.
if (isset($extConf['realUrl']) && 1 == $extConf['realUrl']) {
	
	// Add realURL configuration file.
    require_once(t3lib_extMgm::extPath($_EXTKEY) . '/resources/frontend/realurl/realurl_conf.php');
}

?>