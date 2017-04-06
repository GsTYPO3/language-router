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

$EM_CONF[$_EXTKEY] = [
    'title' => 'Language router',
    'description' => 'Routes website visitors based on GeoIP or browser language to configured pages.',
    'category' => 'fe',
    'version' => '0.5.0-dev',
    'state' => 'beta',
    'author' => 'NIMIUS',
    'author_email' => 'info@nimius.net',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-7.6.99',
        ],
    ],
];
