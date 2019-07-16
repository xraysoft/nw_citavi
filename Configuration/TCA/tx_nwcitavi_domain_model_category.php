<?php
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_nwcitavi_domain_model_category', 'EXT:nw_citavi/Resources/Private/Language/locallang_csh_tx_nwcitavi_domain_model_category.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_nwcitavi_domain_model_category');
return [
  'ctrl' => [
    'title'	=> 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_category',
    'label' => 'title',
    'tstamp' => 'tstamp',
    'crdate' => 'crdate',
    'cruser_id' => 'cruser_id',
  	'delete' => 'deleted',
  	'enablecolumns' => [
      'disabled' => 'hidden',
      'starttime' => 'starttime',
      'endtime' => 'endtime',
    ],
  	'searchFields' => 'citavi_hash,citavi_id,created_by,created_by_sid,created_on,modified_by,modified_by_sid,modified_on,title,description,literaturlist_id,parent',
    'iconfile' => 'EXT:nw_citavi/Resources/Public/Icons/tx_nwcitavi_domain_model_category.png'
  ],
  'interface' => [
	  'showRecordFieldList' => 'hidden, citavi_hash, citavi_id, created_by, created_by_sid, created_on, modified_by, modified_by_sid, modified_on, title, description, literaturlist_id, parent',
  ],
  'types' => [
	  '1' => ['showitem' => 'hidden, citavi_hash, citavi_id, created_by, created_by_sid, created_on, modified_by, modified_by_sid, modified_on, title, description, literaturlist_id, parent, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
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
      'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_category.citavi_hash',
      'config' => [
  	    'type' => 'input',
  	    'size' => 30,
  	    'eval' => 'trim'
			],
	  ],
    'citavi_id' => [
      'exclude' => true,
      'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_category.citavi_id',
      'config' => [
		    'type' => 'input',
		    'size' => 30,
		    'eval' => 'trim'
		  ],
    ],
    'created_by' => [
      'exclude' => true,
      'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_category.created_by',
      'config' => [
  	    'type' => 'input',
  	    'size' => 30,
  	    'eval' => 'trim'
		  ],
    ],
    'created_by_sid' => [
      'exclude' => true,
      'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_category.created_by_sid',
      'config' => [
		    'type' => 'input',
		    'size' => 30,
		    'eval' => 'trim'
		  ],
    ],
    'created_on' => [
      'exclude' => true,
      'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_category.created_on',
      'config' => [
		    'type' => 'input',
		    'size' => 4,
		    'eval' => 'int'
		  ]
    ],
    'modified_by' => [
      'exclude' => true,
      'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_category.modified_by',
      'config' => [
		    'type' => 'input',
		    'size' => 30,
		    'eval' => 'trim'
		  ],
    ],
    'modified_by_sid' => [
      'exclude' => true,
      'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_category.modified_by_sid',
      'config' => [
		    'type' => 'input',
		    'size' => 30,
		    'eval' => 'trim'
		  ],
    ],
    'modified_on' => [
      'exclude' => true,
      'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_category.modified_on',
      'config' => [
		    'type' => 'input',
		    'size' => 4,
		    'eval' => 'int'
		  ]
    ],
    'title' => [
      'exclude' => true,
      'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_category.title',
      'config' => [
		    'type' => 'input',
		    'size' => 30,
		    'eval' => 'trim'
		  ],
    ],
    'description' => [
      'exclude' => true,
      'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_category.description',
      'config' => [
		    'type' => 'text',
		    'cols' => 40,
		    'rows' => 15,
		    'eval' => 'trim'
		  ]
    ],
    'literaturlist_id' => [
      'exclude' => true,
      'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_category.literaturlist_id',
      'config' => [
		    'type' => 'input',
		    'size' => 30,
		    'eval' => 'trim'
		  ],
    ],
    'parent' => [
      'exclude' => true,
      'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_category.parent',
      'config' => [
  	    'type' => 'select',
  	    'renderType' => 'selectMultipleSideBySide',
  	    'foreign_table' => 'tx_nwcitavi_domain_model_category',
  	    'MM' => 'tx_nwcitavi_category_category_mm',
  	    'size' => 10,
  	    'autoSizeMax' => 30,
  	    'maxitems' => 9999,
  	    'multiple' => 0,
        'fieldControl' => [
            'editPopup' => [
                'disabled' => false,
                'options' => [
                    'title' => 'Edit a selected record!',
                ],
            ],
            'addRecord' => [
                'disabled' => false,
                'options' => [
                    'title' => 'Add new record!',
                ],
            ],
        ],  	    
		  ],
    ],
  ],
];
