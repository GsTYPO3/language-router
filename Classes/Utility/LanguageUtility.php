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

/**
 * Language utility.
 *
 * Containts helper methods to work with language codes and locales.
 */
class LanguageUtility
{
    /**
     * Converts given input to a correct locale.
     *
     * Input may be "en-EN" or "en", which both results in "en_EN".
     *
     * @param string $input
     * @return string
     */
    public static function convertToLocale($input)
    {
        if (strlen($input) == 5) {
            // Based on the source, the input may be "en-EN" instead of "en_EN".
            preg_match('/([a-z]{2})[-_]([a-z]{2})/i', $input, $parts);
            $locale = strtolower($parts[1]) . '_' . strtoupper($parts[2]);
        } elseif (strlen($input) == 2) {
            $locale = strtolower($input) . '_' . strtoupper($input);
        }

        return $locale;
    }
}
