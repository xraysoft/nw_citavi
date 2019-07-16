<?php
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_nwcitavi_domain_model_periodical', 'EXT:nw_citavi/Resources/Private/Language/locallang_csh_tx_nwcitavi_domain_model_periodical.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_nwcitavi_domain_model_periodical');
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_periodical',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
		'delete' => 'deleted',
		'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
		'searchFields' => 'citavi_hash,citavi_id,created_by,created_by_sid,created_on,modified_by,modified_by_sid,modified_on,i_s_s_n,name,literaturlist_id',
        'iconfile' => 'EXT:nw_citavi/Resources/Public/Icons/tx_nwcitavi_domain_model_periodical.png'
    ],
    'interface' => [
		'showRecordFieldList' => 'hidden, citavi_hash, citavi_id, created_by, created_by_sid, created_on, modified_by, modified_by_sid, modified_on, i_s_s_n, name, literaturlist_id',
    ],
    'types' => [
		'1' => ['showitem' => 'hidden, citavi_hash, citavi_id, created_by, created_by_sid, created_on, modified_by, modified_by_sid, modified_on, i_s_s_n, name, literaturlist_id, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
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
		'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
              'behaviour' => [
          'allowLanguageSynchronization' => true,
        ],
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
            ]
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
              'behaviour' => [
          'allowLanguageSynchronization' => true,
        ],
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ]
            ],
        ],
        'citavi_hash' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_periodical.citavi_hash',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'citavi_id' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_periodical.citavi_id',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'created_by' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_periodical.created_by',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'created_by_sid' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_periodical.created_by_sid',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'created_on' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_periodical.created_on',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	    ],
	    'modified_by' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_periodical.modified_by',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'modified_by_sid' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_periodical.modified_by_sid',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'modified_on' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_periodical.modified_on',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	    ],
	    'i_s_s_n' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_periodical.i_s_s_n',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'name' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_periodical.name',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'literaturlist_id' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_periodical.literaturlist_id',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
    ],
];
