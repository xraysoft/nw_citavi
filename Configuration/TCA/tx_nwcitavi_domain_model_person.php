<?php
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_nwcitavi_domain_model_person', 'EXT:nw_citavi/Resources/Private/Language/locallang_csh_tx_nwcitavi_domain_model_person.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_nwcitavi_domain_model_person');
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person',
        'label' => 'full_name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
		'delete' => 'deleted',
		'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
		'searchFields' => 'citavi_hash,citavi_id,created_by,created_by_sid,created_on,modified_by,modified_by_sid,modified_on,full_name,first_name,last_name,middle_name,abbreviation,pref,suff,sex,person_type,literaturlist_id',
        'iconfile' => 'EXT:nw_citavi/Resources/Public/Icons/tx_nwcitavi_domain_model_person.png'
    ],
    'interface' => [
		'showRecordFieldList' => 'hidden, citavi_hash, citavi_id, created_by, created_by_sid, created_on, modified_by, modified_by_sid, modified_on, full_name, first_name, last_name, middle_name, abbreviation, pref, suff, sex, person_type, literaturlist_id',
    ],
    'types' => [
		'1' => ['showitem' => 'hidden, citavi_hash, citavi_id, created_by, created_by_sid, created_on, modified_by, modified_by_sid, modified_on, full_name, first_name, last_name, middle_name, abbreviation, pref, suff, sex, person_type, literaturlist_id, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
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
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.citavi_hash',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'citavi_id' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.citavi_id',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'created_by' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.created_by',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'created_by_sid' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.created_by_sid',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'created_on' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.created_on',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	    ],
	    'modified_by' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.modified_by',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'modified_by_sid' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.modified_by_sid',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'modified_on' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.modified_on',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	    ],
      'full_name' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.full_name',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'first_name' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.first_name',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'last_name' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.last_name',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'middle_name' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.middle_name',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'abbreviation' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.abbreviation',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'pref' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.pref',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'suff' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.suff',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'sex' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.sex',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'person_type' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.person_type',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'literaturlist_id' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_person.literaturlist_id',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
    ],
];
