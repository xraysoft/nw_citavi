<?php
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_nwcitavi_domain_model_knowledgeitem', 'EXT:nw_citavi/Resources/Private/Language/locallang_csh_tx_nwcitavi_domain_model_knowledgeitem.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_nwcitavi_domain_model_knowledgeitem');
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_knowledgeitem',
        'label' => 'citavi_hash',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
		'delete' => 'deleted',
		'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
		'searchFields' => 'citavi_hash,citavi_id,created_by,created_by_sid,created_on,modified_by,modified_by_sid,modified_on,knowledge_item_type,quotation_type,core_statement,core_statement_update_type,text,text_r_t_f,literaturlist_id',
        'iconfile' => 'EXT:nw_citavi/Resources/Public/Icons/tx_nwcitavi_domain_model_knowledgeitem.png'
    ],
    'interface' => [
		'showRecordFieldList' => 'hidden, citavi_hash, citavi_id, created_by, created_by_sid, created_on, modified_by, modified_by_sid, modified_on, knowledge_item_type, quotation_type, core_statement, core_statement_update_type, text, text_r_t_f, literaturlist_id',
    ],
    'types' => [
		'1' => ['showitem' => 'hidden, citavi_hash, citavi_id, created_by, created_by_sid, created_on, modified_by, modified_by_sid, modified_on, knowledge_item_type, quotation_type, core_statement, core_statement_update_type, text, text_r_t_f, literaturlist_id, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
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
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_knowledgeitem.citavi_hash',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'citavi_id' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_knowledgeitem.citavi_id',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'created_by' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_knowledgeitem.created_by',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'created_by_sid' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_knowledgeitem.created_by_sid',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'created_on' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_knowledgeitem.created_on',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	    ],
	    'modified_by' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_knowledgeitem.modified_by',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'modified_by_sid' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_knowledgeitem.modified_by_sid',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'modified_on' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_knowledgeitem.modified_on',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	    ],
	    'knowledge_item_type' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_knowledgeitem.knowledge_item_type',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'quotation_type' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_knowledgeitem.quotation_type',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'core_statement' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_knowledgeitem.core_statement',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'core_statement_update_type' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_knowledgeitem.core_statement_update_type',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'text' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_knowledgeitem.text',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			]
	    ],
	    'text_r_t_f' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_knowledgeitem.text_r_t_f',
	        'config' => [
			    'type' => 'text',
          'enableRichtext' => true,
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim',
			],
	    ],
	    'literaturlist_id' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_knowledgeitem.literaturlist_id',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
    ],
];
