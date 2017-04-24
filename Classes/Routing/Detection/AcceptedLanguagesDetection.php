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

use NIMIUS\LanguageRouter\Utility\HttpHeadersUtility;
use NIMIUS\LanguageRouter\Utility\LanguageUtility;

/**
 * Detection by browser language.
 *
 * The acceptedLanguage detection mode reads the HTTP_ACCEPT_LANGUAGE parameter and
 * parses all accepted languages. This parameter usually contains one locale (like de_CH)
 * and a few language codes that are also supported (like de, en, fr).
 *
 * Language codes are also converted to locales (as in de_DE for de, en_EN for en and fr_FR for fr)
 * to have a consistent configuration.
 */
class AcceptedLanguagesDetection extends AbstractDetection implements DetectionInterface
{
    /**
     * @var array
     */
    protected $acceptedLocales = [];

    /**
     * Class constructor.
     *
     * Prepares instance properties.
     */
    public function __construct()
    {
        $this->acceptedLocales = HttpHeadersUtility::getAcceptedLocales();
    }

    /**
     * Check if the detection matches.
     *
     * @return bool
     */
    public function matches()
    {
        if (empty($this->acceptedLocales)) {
            return false;
        }

        foreach ($this->configuration['targets'] as $language => $targetParameters) {
            $locale = LanguageUtility::convertToLocale($language);
            if (!isset($targetParameters['id'])) {
                $targetParameters['id'] = $this->currentPageUid;
            }

            if (array_key_exists($locale, $this->acceptedLocales)) {
                if (!$this->currentPageMatchesTarget($targetParameters)) {
                    $this->targetParameters = $targetParameters;
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Get target parameters for redirection.
     *
     * @param array
     */
    public function getTargetParameters()
    {
        return $this->targetParameters;
    }
}
