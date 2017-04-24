<?php
namespace NIMIUS\LanguageRouter\Routing\Detection;

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

use NIMIUS\LanguageRouter\Utility\ConfigurationUtility;
use NIMIUS\LanguageRouter\Utility\HttpHeadersUtility;

/**
 * Detection by visitor country.
 *
 * The country detection relies on php-geoip being available. If it is not available,
 * nothing is being executed.
 */
class CountryDetection extends AbstractDetection implements DetectionInterface
{
    /**
     * @var array
     */
    protected $configuration = [];

    /**
     * @var string|null
     */
    protected $acceptedCountry;

    /**
     * @var string|null Current country, set via config.country in TypoScript.
     */
    protected $currentCountry;

    /**
     * Class constructor.
     *
     * Prepares instance properties.
     */
    public function __construct()
    {
        if (function_exists('geoip_country_code_by_name')) {
            $address = HttpHeadersUtility::getRemoteAddress();
            if ($address) {
                $this->acceptedCountry = strtoupper(geoip_country_code_by_name($address));
            }
            $this->currentCountry = ConfigurationUtility::getFullTypoScript()['config.']['country'];
        }
    }

    /**
     * The fallback detector obviously must match.
     *
     * @return bool
     */
    public function matches()
    {
        if (!$this->currentCountry) {
            return false;
        }

        foreach ($this->configuration['targets'] as $country => $targetParameters) {
            if (strtoupper($country) == $this->acceptedCountry) {
                if (!$this->currentPageMatchesTarget($targetParameters)) {
                    if ($route['excludeFromMatchComparison'] == 'currentCountry') {
                        $this->targetParameters = $targetParameters;
                        return true;
                    } elseif ($this->currentCountry != $country) {
                        $this->targetParameters = $targetParameters;
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
