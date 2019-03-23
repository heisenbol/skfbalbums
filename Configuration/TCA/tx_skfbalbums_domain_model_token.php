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
		'searchFields' => 'name,access_token,page_id,exclude_album_ids,include_album_ids',
        'iconfile' => 'EXT:skfbalbums/Resources/Public/Icons/tx_skfbalbums_domain_model_token.svg'
    ],
    'interface' => [
		'showRecordFieldList' => 'name, access_token, page_id, defaultdownload, exclude_album_ids, include_album_ids',
    ],
    'types' => [
		'1' => ['showitem' => 'name, access_token, page_id, defaultdownload, exclude_album_ids, include_album_ids'],
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
	    'access_token' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_token.access_token',
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
	    'defaultdownload' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:skfbalbums/Resources/Private/Language/locallang_db.xlf:tx_skfbalbums_domain_model_token.defaultdownload',
	        'config' => [
			    'type' => 'check',
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
