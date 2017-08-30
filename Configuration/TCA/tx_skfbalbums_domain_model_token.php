<?php
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_token',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
		'delete' => 'deleted',
		'enablecolumns' => [
        ],
		'searchFields' => 'name,app_id,app_secret,page_id,exclude_album_ids,include_album_ids',
        'iconfile' => 'EXT:skfbalbums/Resources/Public/Icons/tx_skfbalbums_domain_model_token.gif'
    ],
    'interface' => [
		'showRecordFieldList' => 'name, app_id, app_secret, page_id, exclude_album_ids, include_album_ids',
    ],
    'types' => [
		'1' => ['showitem' => 'name, app_id, app_secret, page_id, exclude_album_ids, include_album_ids'],
    ],
    'columns' => [
        'name' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_token.name',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
	    'app_id' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_token.app_id',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
	    'app_secret' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_token.app_secret',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
	    'page_id' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_token.page_id',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
	    'exclude_album_ids' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_token.exclude_album_ids',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			]
	    ],
	    'include_album_ids' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_token.include_album_ids',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			]
	    ],
    ],
];
