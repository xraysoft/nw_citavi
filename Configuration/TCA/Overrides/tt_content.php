<?php
if (!defined('TYPO3_MODE')) {die('Access denied.');}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
  'Netzweber.NwCitavi', 
  'Citaviimporter', 
  'Importer'
);