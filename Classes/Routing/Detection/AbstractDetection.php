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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Abstract detection class.
 */
abstract class AbstractDetection
{
    /**
     * @var array
     */
    protected $configuration = [];

    /**
     * @var array
     */
    protected $targetParameters = [];

    /**
     * Set configuration for detection.
     *
     * @param array $configuration
     * @return void
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Getter for the current page uid.
     *
     * Returns the parameter from either TSFE or the GET parameter,
     * based on configuration. TSFE will always contain a value even
     * if no GET param is given, which can be a behavior that does
     * not meet implementation requirements.
     *
     * @return int|null
     */
    public function getCurrentPageUid()
    {
        if ($this->configuration['compareParametersFrom'] == 'GET') {
            $id = GeneralUtility::_GET('id');
            if (isset($id)) {
                return (int)$id;
            }
        } else {
            return (int)$GLOBALS['TSFE']->id;
        }
    }

    /**
     * Getter for the current language uid.
     *
     * Returns the parameter from either TSFE or the GET parameter,
     * based on configuration. TSFE will always contain a value even
     * if no GET param is given, which can be a behavior that does
     * not meet implementation requirements.
     *
     * @return int|null
     */
    public function getCurrentLanguageUid()
    {
        if ($this->configuration['compareParametersFrom'] == 'GET') {
            $l = GeneralUtility::_GET('L');
            if (isset($l)) {
                return (int)$l;
            }
        } else {
            return (int)$GLOBALS['TSFE']->sys_language_uid;
        }
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

    /**
     * Checks if the current page matches the given target parameters
     * to prevent a redirect loop.
     *
     * This only checks for L and id GET parameters!
     *
     * @param array $parameters
     * @return bool
     */
    protected function currentPageMatchesTarget(array $parameters)
    {
        $isTarget = false;

        if (isset($parameters['L'])) {
            $isTarget = ((int)$parameters['L'] === $this->getCurrentLanguageUid());
        }
        if (isset($parameters['id'])) {
            $isTarget = ((int)$parameters['id'] === $this->getCurrentPageUid());
        }

        return $isTarget;
    }
}
