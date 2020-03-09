<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
          'Netzweber.NwCitavi',
          'Citaviimporter',
          [
              'Reference' => 'import, parser'
          ],
          // non-cacheable actions
          [
              'Reference' => 'import, parser'
          ]
        );

        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            'mod {
        			wizards.newContentElement.wizardItems.plugins {
        				elements {
        					citaviimporter {
        						iconIdentifier = nw_citavi-plugin-citaviimporter
        						title = LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nw_citavi_domain_model_citaviimporter
        						description = LLL:EXT:nw_citavi/Resources/Private/Language/locallang_db.xlf:tx_nw_citavi_domain_model_citaviimporter.description
        						tt_content_defValues {
        							CType = list
        							list_type = nwcitavi_citaviimporter
        						}
        					}
        				}
        				show = *
        			}
        	   }'
        );

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Netzweber\NwCitavi\Task\ParseCategories::class] = [
            'extension' => $extKey,
            'title' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseCategories.name',
            'description' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseCategories.description',
            'additionalFields' => '',
        ];
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Netzweber\NwCitavi\Task\ParseKeywords::class] = [
            'extension' => $extKey,
            'title' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseKeywords.name',
            'description' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseKeywords.description',
            'additionalFields' => '',
        ];
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Netzweber\NwCitavi\Task\ParseLibraries::class] = [
            'extension'        => $extKey,
            'title' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseLibraries.name',
            'description' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseLibraries.description',
            'additionalFields' => '',
        ];
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Netzweber\NwCitavi\Task\ParsePeriodicals::class] = [
            'extension'        => $extKey,
            'title' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parsePeriodicals.name',
            'description' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parsePeriodicals.description',
            'additionalFields' => '',
        ];
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Netzweber\NwCitavi\Task\ParsePersons::class] = [
            'extension'        => $extKey,
            'title' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parsePersons.name',
            'description' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parsePersons.description',
            'additionalFields' => '',
        ];
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Netzweber\NwCitavi\Task\ParsePublishers::class] = [
            'extension'        => $extKey,
            'title' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parsePublishers.name',
            'description' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parsePublishers.description',
            'additionalFields' => '',
        ];
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Netzweber\NwCitavi\Task\ParseSeriestitles::class] = [
            'extension'        => $extKey,
            'title' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseSeriestitles.name',
            'description' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseSeriestitles.description',
            'additionalFields' => '',
        ];
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Netzweber\NwCitavi\Task\ParseKnowledgeitems::class] = [
            'extension'        => $extKey,
            'title' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseKnowledgeitems.name',
            'description' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseKnowledgeitems.description',
            'additionalFields' => '',
        ];
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Netzweber\NwCitavi\Task\ParseReferences::class] = [
            'extension'        => $extKey,
            'title' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseReferences.name',
            'description' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseReferences.description',
            'additionalFields' => '',
        ];
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Netzweber\NwCitavi\Task\ParseLocations::class] = [
            'extension'        => $extKey,
            'title' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseLocations.name',
            'description' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseLocations.description',
            'additionalFields' => '',
        ];
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Netzweber\NwCitavi\Task\ParseFiles::class] = [
            'extension'        => $extKey,
            'title' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseFiles.name',
            'description' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseFiles.description',
            'additionalFields' => '',
        ];
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Netzweber\NwCitavi\Task\ParseCleaner::class] = [
            'extension'        => $extKey,
            'title' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseCleaner.name',
            'description' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseCleaner.description',
            'additionalFields' => '',
        ];
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Netzweber\NwCitavi\Task\ParseSorting::class] = [
            'extension'        => $extKey,
            'title' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseSorting.name',
            'description' => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang.xlf:task.parseSorting.description',
            'additionalFields' => '',
        ];
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconRegistry->registerIcon('nw_citavi-plugin-citaviimporter', \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class, ['source' => 'EXT:nw_citavi/Resources/Public/Icons/user_plugin_citaviimporter.svg']);
    },
    $_EXTKEY
);
