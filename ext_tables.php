<?php

/*
 * This file is part of the package netzweber/nw_citavi
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if (TYPO3_MODE === 'BE') {
    ExtensionUtility::registerModule(
        'Netzweber.NwCitavi',
        'tools',
        'Citavi',
        '',
        [
            'Log' => 'list,scheduler',
        ],
        [
            'access'    => 'user,group',
            'icon'      => 'EXT:nw_citavi/ext_icon.gif',
            'labels'    => 'LLL:EXT:nw_citavi/Resources/Private/Language/locallang_mod.xlf',
        ]
    );
}
