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
     * @var int Current page uid.
     */
    protected $currentPageUid;

    /**
     * @var int Current sys_language_uid.
     */
    protected $currentLanguageUid;

    /**
     * @var array
     */
    protected $targetParameters = [];

    /**
     * Class constructor.
     *
     * Prepares instance properties.
     */
    public function __construct()
    {
        $this->currentPageUid = (int)$GLOBALS['TSFE']->id;
        $this->currentLanguageUid = (int)$GLOBALS['TSFE']->sys_language_uid;
    }

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
            $isTarget = ((int)$parameters['L'] == $this->currentLanguageUid);
        }
        if (isset($parameters['id'])) {
            $isTarget = ((int)$parameters['id'] == $this->currentPageUid);
        }

        return $isTarget;
    }
}
