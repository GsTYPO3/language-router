<?php
/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$l10n = 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xlf:plugin.';

// Register 'Language Routing' plugin.
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'NIMIUS.' . $_EXTKEY,
    'LanguageRouting',
    $l10n . 'languageRouting.title'
);

// Register status provider for reports module.
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['Language Router'][] = \NIMIUS\LanguageRouter\Report\Status\GeoIpStatus::class;
