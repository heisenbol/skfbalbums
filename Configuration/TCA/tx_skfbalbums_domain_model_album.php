<?php
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_album',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => [
            'disabled' => 'hidden',
        ],
		'searchFields' => 'name,description,download,name_override,description_override,facebook_id,link,cover_photo_fb_id,last_synced,token',
        'iconfile' => 'EXT:skfbalbums/Resources/Public/Icons/tx_skfbalbums_domain_model_album.svg'
    ],
    'interface' => [
		'showRecordFieldList' => 'hidden, name, description, download, name_override, description_override, facebook_id, link, cover_photo_fb_id, last_synced, token',
    ],
    'types' => [
		'1' => ['showitem' => 'hidden, name, description, download, name_override, description_override, facebook_id, link, cover_photo_fb_id, last_synced, token'],
    ],
    'columns' => [
		'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
            ],
        ],
        'name' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_album.name',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'description' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_album.description',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			],
	    ],
	    'download' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_album.download',
	        'config' => [
			    'type' => 'check',
			],
	    ],
	    'name_override' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_album.name_override',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'description_override' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_album.description_override',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			],
	    ],
	    'facebook_id' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_album.facebook_id',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
	    'link' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_album.link',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'cover_photo_fb_id' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_album.cover_photo_fb_id',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'last_synced' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_album.last_synced',
	        'config' => [
			    'type' => 'input',
			    'size' => 10,
			    'eval' => 'datetime',
			    'renderType' => 'inputDateTime',
			    'default' => time()
			],
	    ],
	    'token' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_album.token',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'foreign_table' => 'tx_skfbalbums_domain_model_token',
			    'minitems' => 0,
			    'maxitems' => 1,
			],
	    ],
    ],
];
