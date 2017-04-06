<?php
namespace NIMIUS\LanguageRouter\Routing;

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

use NIMIUS\LanguageRouter\Factory\DetectionFactory;

/**
 * Routes processor.
 *
 * Executes routing configuration in given order to find a matching redirection.
 */
class RoutesProcessor
{
    /**
     * @var \NIMIUS\LanguageRouter\Persistence\Cookie
     * @inject
     */
    protected $cookie;

    /**
     * @var array Configuration array of plugin.tx_languagerouter.settings
     */
    protected $routingConfiguration = [];

    /**
     * @var array Parameters for redirection.
     */
    protected $targetParameters = [];

    /**
     * Setter for configuration.
     *
     * @param array $routingConfiguration
     * @return void
     */
    public function setConfiguration($routingConfiguration)
    {
        $this->routingConfiguration = $routingConfiguration;
    }

    /**
     * Processing action.
     *
     * Starts processing given routing configuration and
     * returns configured parameters to be used for redirecting.
     *
     * @return bool true if processing yields a result, false otherwise
     */
    public function process()
    {
        if (empty($this->routingConfiguration) || empty($this->routingConfiguration['routes'])) {
            return false;
        }

        if (!(int)$this->routingConfiguration['redirectCookie']['disregard'] && $this->cookie->get('redirected')) {
            return false;
        }

        foreach ($this->routingConfiguration['routes'] as $routeConfiguration) {
            $detection = DetectionFactory::get($routeConfiguration['detection']);
            $detection->setConfiguration($routeConfiguration);
            if ($detection->matches()) {
                $this->targetParameters = $detection->getTargetParameters();
                return true;
            }
        }

        return false;
    }

    /**
     * Getter for target parameters resulting from processing detections.
     *
     * @return array
     */
    public function getTargetParameters()
    {
        return $this->targetParameters;
    }
}
