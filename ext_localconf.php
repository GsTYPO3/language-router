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

// Configure 'Language routing' plugin.
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'NIMIUS.' . $_EXTKEY,
    'LanguageRouting',
    [
        'Routing' => 'process',
    ],
    [
        'Routing' => 'process',
    ]
);

// Register 'acceptedLanguages' detection.
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['language_router']['classes']['routing']['detection']['acceptedLanguages'] = \NIMIUS\LanguageRouter\Routing\Detection\AcceptedLanguagesDetection::class;

// Register 'country' detection.
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['language_router']['classes']['routing']['detection']['country'] = \NIMIUS\LanguageRouter\Routing\Detection\CountryDetection::class;

// Register 'fallback' detection.
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['language_router']['classes']['routing']['detection']['fallback'] = \NIMIUS\LanguageRouter\Routing\Detection\FallbackDetection::class;
