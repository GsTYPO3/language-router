<?php
namespace NIMIUS\LanguageRouter\Report\Status;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Reports\Status;

/**
 * GeoIP Status report.
 *
 * Returns information about whether php-geoip methods
 * are available or not.
 */
class GeoIpStatus implements \TYPO3\CMS\Reports\StatusProviderInterface
{
    /**
     * Main method.
     *
     * Executes status checks against configurations.
     *
     * @return array
     */
    public function getStatus()
    {
        return [
            'GeoIpExtensionLoaded' => $this->getGeoIpExtensionLoaded(),
            'GeoIpFunctionsExist' => $this->getGeoIpFunctionsExist()
        ];
    }

    /**
     * Checks if php-geoip is loaded.
     *
     * @return \TYPO3\CMS\Reports\Status
     */
    protected function getGeoIpExtensionLoaded()
    {
        if (extension_loaded('geoip')) {
            return GeneralUtility::makeInstance(
                Status::class,
                'PHP GeoIP extension loaded',
                'OK',
                null,
                Status::OK
            );
        } else {
            return GeneralUtility::makeInstance(
                Status::class,
                'PHP GeoIP extension loaded',
                'Not available',
                'It seems that the GeoIP module for the current php version is not loaded.',
                Status::ERROR
            );
        }
    }
    
    public function getGeoIpFunctionsExist()
    {
        if (function_exists('geoip_country_code_by_name')) {
            return GeneralUtility::makeInstance(
                Status::class,
                'PHP functions exist',
                'OK',
                null,
                Status::OK
            );
        } else {
            return GeneralUtility::makeInstance(
                Status::class,
                'PHP functions exist',
                'Function does not exist',
                'The method <code>geoip_country_code_by_name()</code> does not exist, which is required for ip-based routing.',
                Status::ERROR
            );
        }
    }
}
