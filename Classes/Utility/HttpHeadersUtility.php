<?php
namespace NIMIUS\LanguageRouter\Utility;

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

/**
 * HttpHeaders utility.
 *
 * Provides functionality to work with HTTP headers.
 */
class HttpHeadersUtility
{
    /**
     * Extracts accepted languages from HTTP_ACCEPT_LANGUAGE
     * and builds a sorted array based of locales on priority.
     *
     * Example output:
     *
     *   array(4) {
     *     ["en_US"]=> float(1)
     *     ["en_EN"]=> float(0.8)
     *     ["de_DE"]=> float(0.6)
     *     ["fr_FR"]=> float(0.4)
     *   }
     *
     * @param float $qualityTreshold
     * @return array
     */
    public static function getAcceptedLocales($qualityTreshold = null)
    {
        $languageString = GeneralUtility::trimExplode(',', GeneralUtility::getIndpEnv('HTTP_ACCEPT_LANGUAGE'));
        $acceptedLocales = array_reduce(
            $languageString,
            function ($collection, $part) use ($qualityTreshold) {
                list($language, $q) = array_merge(
                    GeneralUtility::trimExplode(';q=', $part),
                    [1]
                );

                $quality = (float)$q;
                if ($qualityTreshold && $quality < (float)$qualityTreshold) {
                    return $collection;
                }

                $locale = LanguageUtility::convertToLocale($language);
                $collection[$locale] = $quality;
                return $collection;
            },
            []
        );
        arsort($acceptedLocales);
        return $acceptedLocales;
    }

    /**
     * Returns the remote address of the current client,
     * respecting proxied requests.
     *
     * @return string|null
     */
    public static function getRemoteAddress()
    {
        $address = GeneralUtility::getIndpEnv('HTTP_X_FORWARDED_FOR');

        // In certain situations, getIndpEnv may not return a correct header.
        if (!$address) {
            $address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        if (!$address) {
            $address = GeneralUtility::getIndpEnv('REMOTE_ADDR');
        }

        return $address;
    }
}
