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
 * Fallback detection.
 *
 * As its name says, it's not a real detection, but a fallback.
 */
class FallbackDetection extends AbstractDetection implements DetectionInterface
{
    /**
     * @var array
     */
    protected $configuration;

    /**
     * The fallback detector obviously must match.
     *
     * @return bool
     */
    public function matches()
    {
        return true;
    }

    /**
     * Get target parameters for redirection.
     *
     * Overrides abstract method due to the configuration
     * being in another namespace / scope.
     *
     * @param array
     */
    public function getTargetParameters()
    {
        return $this->configuration['target'];
    }
}
