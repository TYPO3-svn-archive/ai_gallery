<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_aigallery_galleries'] = array (
	'ctrl' => $TCA['tx_aigallery_galleries']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'CType,hidden,starttime,endtime,fe_group,title,description,images,alt_attributes,title_attributes,image_descriptions,max_images'
	),
	'feInterface' => $TCA['tx_aigallery_galleries']['feInterface'],
	'columns' => array (
	    'CType' => Array (
            'label' => 'LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.ctype',
            'config' => Array (
                'type' => 'select',
                'items' => Array (
                    array('LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.ctype.I.0', 'manual'),
                    array('LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.ctype.I.1', 'automatic')
                ),
                'default' => 'manual',
                'authMode' => $GLOBALS['TYPO3_CONF_VARS']['BE']['explicitADmode'],
                'authMode_enforce' => 'strict',
            )
        ),
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array (
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'fe_group' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'  => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
					array('LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.any_login', -2),
					array('LLL:EXT:lang/locallang_general.xml:LGL.usergroups', '--div--')
				),
				'foreign_table' => 'fe_groups'
			)
		),
		'title' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.title',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'required',
			)
		),
		'description' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.description',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',
				'rows' => '2',
			)
		),
		'images' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.images',		
			'config' => array (
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => 'gif,png,jpeg,jpg',	
				'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],	
				'uploadfolder' => 'uploads/tx_aigallery',
				'show_thumbs' => 1,	
				'size' => 15,	
				'minitems' => 0,
				'maxitems' => 100,
			)
		),
		'image_folder' => array (       
            'exclude' => 0,     
            'label' => 'LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.image_folder',     
            'config' => array (
                'type' => 'input',
				'eval' => 'trim',
				'wizards' => array(
				    '_PADDING' => 2,
					'link' => array(
					   'type' => 'popup',
					   'title' => 'Link',
					   'icon' => 'link_popup.gif',
					   'script' => 'browse_links.php?mode=wizard&amp;act=folder',
					   'params' => array(
					       'blindLinkOptions' => 'page,file,url,mail,spec',
					   ),
					   'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1',
					)
				)
                
            )
        ),
		'live_update' => array (       
            'exclude' => 1,
            'label'   => 'LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.live_update',
            'config'  => array (
                'type'    => 'check',
                'default' => '0'
            )
        ),
		'alt_attributes' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.alt_attributes',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '15',
			)
		),
		'title_attributes' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.title_attributes',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '15',
			)
		),
		'image_descriptions' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.image_descriptions',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '15',
			)
		),
		'max_images' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.max_images',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.max_images.I.0', '0'),
					array('LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.max_images.I.1', 'all'),
					array('LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.max_images.I.2', '10'),
					array('LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.max_images.I.3', '20'),
					array('LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.max_images.I.4', '30'),
					array('LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.max_images.I.5', '40'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
	),
	'types' => array (
		//'0' => array('showitem' => 'hidden;;1;;1-1-1, title;;;;2-2-2, description;;;richtext[]:rte_transform[mode=ts_css|imgpath=uploads/tx_aigallery/rte/];3-3-3, images, alt_attributes, title_attributes, image_descriptions, max_images')
	   '1' =>  array('showitem' => 'CType'),
	   'manual' => array('showitem' => '--div--;LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.tabs.general,
	                                    CType;;4;;1-1-1, title;;;;2-2-2,
										description;;;richtext[]:rte_transform[mode=ts_css|imgpath=uploads/tx_aigallery/rte/];3-3-3, max_images,
										--div--;LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.tabs.manual,
										images, alt_attributes, title_attributes, image_descriptions,
										--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.access'
	   ),
	   'automatic' => array('showitem' => 'CType;;4;;1-1-1, title;;;;2-2-2
                                        --div--;LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.tabs.general,
                                        description;;;richtext[]:rte_transform[mode=ts_css|imgpath=uploads/tx_aigallery/rte/];3-3-3, max_images,
                                        --div--;LLL:EXT:ai_gallery/resources/backend/locallang/locallang_db.xml:tx_aigallery_galleries.tabs.automatic,
                                        image_folder, live_update, alt_attributes, title_attributes, image_descriptions,
										--div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.access'
       ),
	),
	'palettes' => array (
		'1' => array('showitem' => 'hidden, starttime, endtime, fe_group')
	)
);
?>