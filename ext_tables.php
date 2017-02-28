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

// TODO l10n
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'NIMIUS.' . $_EXTKEY,
    'LanguageRouting',
    '((Label: Language routing plugin))'
);
