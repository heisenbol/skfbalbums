<?php
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_photo',
        'label' => 'facebook_id',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => [
            'disabled' => 'hidden',
        ],
		'searchFields' => 'facebook_id,images,caption,caption_override,album',
        'iconfile' => 'EXT:skfbalbums/Resources/Public/Icons/tx_skfbalbums_domain_model_photo.svg'
    ],
    'interface' => [
		'showRecordFieldList' => 'hidden, facebook_id, images, caption, caption_override, album',
    ],
    'types' => [
		'1' => ['showitem' => 'hidden, facebook_id, images, caption, caption_override, album'],
    ],
    'columns' => [
		'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_photo.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
            ],
        ],
        'facebook_id' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_photo.facebook_id',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'images' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_photo.images',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			]
	    ],
	    'caption' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_photo.caption',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			]
	    ],
	    'caption_override' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_photo.caption_override',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			]
	    ],
	    'album' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_photo.album',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'foreign_table' => 'tx_skfbalbums_domain_model_album',
			    'minitems' => 0,
			    'maxitems' => 1,
			],
	    ],
    ],
];
