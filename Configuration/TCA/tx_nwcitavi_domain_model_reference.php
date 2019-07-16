<?php
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_nwcitavi_domain_model_reference', 'EXT:nw_citavi/Resources/Private/Language/locallang_csh_tx_nwcitavi_domain_model_reference.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_nwcitavi_domain_model_reference');
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
		'searchFields' => 'citavi_hash,citavi_id,created_by,created_by_sid,created_on,modified_by,modified_by_sid,modified_on,reference_type,parent_reference_type,i_s_b_n,sequence_number,abstract,abstract_r_t_f,access_date,additions,ref_authors,ref_categories,citation_key_update_type,ref_collaborators,custom_field1,custom_field2,custom_field3,custom_field4,custom_field5,custom_field6,custom_field7,custom_field8,custom_field9,book_date,default_location_i_d,edition,ref_editors,evaluation,evaluation_r_t_f,ref_keywords,book_language,book_note,number,number_of_volumes,online_address,ref_organizations,original_checked_by,original_publication,ref_others_involved,page_count,page_range,parallel_title,ref_periodical,place_of_publication,price,ref_publishers,rating,ref_series_title,short_title,source_of_bibliographic_information,specific_field1,specific_field2,specific_field4,specific_field7,storage_medium,subtitle,table_of_contents,table_of_contents_r_t_f,title,title_in_other_languages,title_supplement,translated_title,uniform_title,book_volume,book_year,page_range_start,page_range_end,doi,sort_date,literaturlist_id,attachment,cover,categories,keywords,editors,authors,others_involved,collaborators,organizations,publishers,periodicals,seriestitles,knowledge_items,locations,parent_references,child_references',
        'iconfile' => 'EXT:nw_citavi/Resources/Public/Icons/tx_nwcitavi_domain_model_reference.png'
    ],
    'interface' => [
		'showRecordFieldList' => 'hidden, citavi_hash, citavi_id, created_by, created_by_sid, created_on, modified_by, modified_by_sid, modified_on, reference_type, parent_reference_type, i_s_b_n, sequence_number, abstract, abstract_r_t_f, access_date, additions, ref_authors, ref_categories, citation_key_update_type, ref_collaborators, custom_field1, custom_field2, custom_field3, custom_field4, custom_field5, custom_field6, custom_field7, custom_field8, custom_field9, book_date, default_location_i_d, edition, ref_editors, evaluation, evaluation_r_t_f, ref_keywords, book_language, book_note, number, number_of_volumes, online_address, ref_organizations, original_checked_by, original_publication, ref_others_involved, page_count, page_range, parallel_title, ref_periodical, place_of_publication, price, ref_publishers, rating, ref_series_title, short_title, source_of_bibliographic_information, specific_field1, specific_field2, specific_field4, specific_field7, storage_medium, subtitle, table_of_contents, table_of_contents_r_t_f, title, title_in_other_languages, title_supplement, translated_title, uniform_title, book_volume, book_year, page_range_start, page_range_end, doi, sort_date, literaturlist_id, attachment, cover, categories, keywords, editors, authors, others_involved, collaborators, organizations, publishers, periodicals, seriestitles, knowledge_items, locations, parent_references, child_references',
    ],
    'types' => [
		'1' => ['showitem' => 'hidden, citavi_hash, citavi_id, created_by, created_by_sid, created_on, modified_by, modified_by_sid, modified_on, reference_type, parent_reference_type, i_s_b_n, sequence_number, abstract, abstract_r_t_f, access_date, additions, ref_authors, ref_categories, citation_key_update_type, ref_collaborators, custom_field1, custom_field2, custom_field3, custom_field4, custom_field5, custom_field6, custom_field7, custom_field8, custom_field9, book_date, default_location_i_d, edition, ref_editors, evaluation, evaluation_r_t_f, ref_keywords, book_language, book_note, number, number_of_volumes, online_address, ref_organizations, original_checked_by, original_publication, ref_others_involved, page_count, page_range, parallel_title, ref_periodical, place_of_publication, price, ref_publishers, rating, ref_series_title, short_title, source_of_bibliographic_information, specific_field1, specific_field2, specific_field4, specific_field7, storage_medium, subtitle, table_of_contents, table_of_contents_r_t_f, title, title_in_other_languages, title_supplement, translated_title, uniform_title, book_volume, book_year, page_range_start, page_range_end, doi, sort_date, literaturlist_id, attachment, cover, categories, keywords, editors, authors, others_involved, collaborators, organizations, publishers, periodicals, seriestitles, knowledge_items, locations, parent_references, child_references, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
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
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.citavi_hash',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'citavi_id' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.citavi_id',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'created_by' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.created_by',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'created_by_sid' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.created_by_sid',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'created_on' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.created_on',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	    ],
	    'modified_by' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.modified_by',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'modified_by_sid' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.modified_by_sid',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'modified_on' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.modified_on',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	    ],
	    'reference_type' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.reference_type',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'parent_reference_type' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.parent_reference_type',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'i_s_b_n' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.i_s_b_n',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'sequence_number' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.sequence_number',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'abstract' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.abstract',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			]
	    ],
	    'abstract_r_t_f' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.abstract_r_t_f',
	        'config' => [
			    'type' => 'text',
          'enableRichtext' => true,
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim',
			],
	    ],
	    'access_date' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.access_date',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'additions' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.additions',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'ref_authors' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.ref_authors',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'ref_categories' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.ref_categories',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'citation_key_update_type' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.citation_key_update_type',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'ref_collaborators' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.ref_collaborators',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'custom_field1' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.custom_field1',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'custom_field2' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.custom_field2',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'custom_field3' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.custom_field3',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'custom_field4' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.custom_field4',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'custom_field5' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.custom_field5',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'custom_field6' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.custom_field6',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'custom_field7' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.custom_field7',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'custom_field8' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.custom_field8',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'custom_field9' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.custom_field9',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'book_date' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.book_date',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'default_location_i_d' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.default_location_i_d',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'edition' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.edition',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'ref_editors' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.ref_editors',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'evaluation' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.evaluation',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			]
	    ],
	    'evaluation_r_t_f' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.evaluation_r_t_f',
	        'config' => [
			    'type' => 'text',
          'enableRichtext' => true,
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim',
			],
	    ],
	    'ref_keywords' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.ref_keywords',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'book_language' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.book_language',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'book_note' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.book_note',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'number' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.number',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'number_of_volumes' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.number_of_volumes',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'online_address' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.online_address',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'ref_organizations' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.ref_organizations',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'original_checked_by' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.original_checked_by',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'original_publication' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.original_publication',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'ref_others_involved' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.ref_others_involved',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'page_count' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.page_count',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'page_range' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.page_range',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'parallel_title' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.parallel_title',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'ref_periodical' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.ref_periodical',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'place_of_publication' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.place_of_publication',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'price' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.price',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'double2'
			]
	    ],
	    'ref_publishers' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.ref_publishers',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'rating' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.rating',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	    ],
	    'ref_series_title' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.ref_series_title',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'short_title' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.short_title',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'source_of_bibliographic_information' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.source_of_bibliographic_information',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'specific_field1' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.specific_field1',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'specific_field2' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.specific_field2',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'specific_field4' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.specific_field4',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'specific_field7' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.specific_field7',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'storage_medium' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.storage_medium',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'subtitle' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.subtitle',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'table_of_contents' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.table_of_contents',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			]
	    ],
	    'table_of_contents_r_t_f' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.table_of_contents_r_t_f',
	        'config' => [
			    'type' => 'text',
          'enableRichtext' => true,
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim',
			],
	    ],
	    'title' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.title',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'title_in_other_languages' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.title_in_other_languages',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'title_supplement' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.title_supplement',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'translated_title' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.translated_title',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'uniform_title' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.uniform_title',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'book_volume' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.book_volume',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'book_year' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.book_year',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'page_range_start' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.page_range_start',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'page_range_end' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.page_range_end',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'doi' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.doi',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'sort_date' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.sort_date',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'literaturlist_id' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.literaturlist_id',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'attachment' => [
        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.attachment',
        'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
          'attachment',
          [
            'appearance' => [
              'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference'
            ],
            'overrideChildTca' => [
              'types' => [
                '0' => [
                  'showitem' => '
                  --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                  --palette--;;filePalette'
                ],
                \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                  'showitem' => '
                  --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                  --palette--;;filePalette'
                ],
              ],
            ],
            'maxitems' => 1,
            'foreign_match_fields' => [
              'tablenames' => 'tx_nwcitavi_domain_model_reference',
              'table_local' => 'sys_file'
            ]
          ]
        ),
	    ],
	    'cover' => [
	      'exclude' => true,
	      'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.cover',
	      'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
          'cover',
          [
  					'appearance' => [
  						'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference'
  					],
  					'overrideChildTca' => [
              'types' => [
                '0' => [
                  'showitem' => '
                  --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                  --palette--;;filePalette'
                ],
                \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                  'showitem' => '
                  --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                  --palette--;;filePalette'
                ],
              ],
            ],
  					'maxitems' => 1,
            'foreign_match_fields' => [
              'tablenames' => 'tx_nwcitavi_domain_model_reference',
              'table_local' => 'sys_file'
            ]
				  ]
        ),
	    ],
	    'categories' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.categories',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectMultipleSideBySide',
			    'foreign_table' => 'tx_nwcitavi_domain_model_category',
			    'MM' => 'tx_nwcitavi_reference_category_mm',
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
	    'keywords' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.keywords',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectMultipleSideBySide',
			    'foreign_table' => 'tx_nwcitavi_domain_model_keyword',
			    'MM' => 'tx_nwcitavi_reference_keyword_mm',
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
	    'editors' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.editors',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectMultipleSideBySide',
			    'foreign_table' => 'tx_nwcitavi_domain_model_person',
			    'MM' => 'tx_nwcitavi_reference_editors_person_mm',
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
	    'authors' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.authors',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectMultipleSideBySide',
			    'foreign_table' => 'tx_nwcitavi_domain_model_person',
			    'MM' => 'tx_nwcitavi_reference_authors_person_mm',
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
	    'others_involved' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.others_involved',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectMultipleSideBySide',
			    'foreign_table' => 'tx_nwcitavi_domain_model_person',
			    'MM' => 'tx_nwcitavi_reference_othersinvolved_person_mm',
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
	    'collaborators' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.collaborators',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectMultipleSideBySide',
			    'foreign_table' => 'tx_nwcitavi_domain_model_person',
			    'MM' => 'tx_nwcitavi_reference_collaborators_person_mm',
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
	    'organizations' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.organizations',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectMultipleSideBySide',
			    'foreign_table' => 'tx_nwcitavi_domain_model_person',
			    'MM' => 'tx_nwcitavi_reference_organizations_person_mm',
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
	    'publishers' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.publishers',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectMultipleSideBySide',
			    'foreign_table' => 'tx_nwcitavi_domain_model_publisher',
			    'MM' => 'tx_nwcitavi_reference_publisher_mm',
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
	    'periodicals' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.periodicals',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectMultipleSideBySide',
			    'foreign_table' => 'tx_nwcitavi_domain_model_periodical',
			    'MM' => 'tx_nwcitavi_reference_periodical_mm',
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
	    'seriestitles' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.seriestitles',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectMultipleSideBySide',
			    'foreign_table' => 'tx_nwcitavi_domain_model_seriestitle',
			    'MM' => 'tx_nwcitavi_reference_seriestitle_mm',
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
	    'knowledge_items' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.knowledge_items',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectMultipleSideBySide',
			    'foreign_table' => 'tx_nwcitavi_domain_model_knowledgeitem',
			    'MM' => 'tx_nwcitavi_reference_knowledgeitem_mm',
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
	    'locations' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.locations',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectMultipleSideBySide',
			    'foreign_table' => 'tx_nwcitavi_domain_model_location',
			    'MM' => 'tx_nwcitavi_reference_location_mm',
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
	    'parent_references' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.parent_references',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectMultipleSideBySide',
			    'foreign_table' => 'tx_nwcitavi_domain_model_reference',
			    'MM' => 'tx_nwcitavi_reference_reference_mm',
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
	    'child_references' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nwcitavi_domain_model_reference.child_references',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectMultipleSideBySide',
			    'foreign_table' => 'tx_nwcitavi_domain_model_reference',
			    'MM' => 'tx_nwcitavi_reference_childreferences_reference_mm',
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
