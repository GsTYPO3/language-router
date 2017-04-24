<?php
namespace NIMIUS\LanguageRouter\Controller;

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
use NIMIUS\LanguageRouter\Utility\ObjectUtility;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

/**
 * RoutingController class.
 *
 * Responsible for redirecting the visitor based on
 * configured detections and routes.
 */
class RoutingController extends ActionController
{
    /**
     * @var \NIMIUS\LanguageRouter\Routing\RoutesProcessor
     * @inject
     */
    protected $routesProcessor;

    /**
     * @var \NIMIUS\LanguageRouter\Persistence\Cookie
     * @inject
     */
    protected $cookie;

    /**
     * Process action.
     *
     * Parses TypoScript configuration to check if a redirect needs to happen.
     *
     * @return string An empty string is returned to prevent template rendering.
     */
    public function processAction()
    {
        $configuration = ConfigurationUtility::getTyposcriptConfiguration();
        $this->routesProcessor->setConfiguration($configuration);
        if ($this->routesProcessor->process()) {
            $expirationInSeconds = (int)$configuration['redirectCookie']['expirationInSeconds'];
            if ($expirationInSeconds) {
                $expiration = time() + $expirationInSeconds;
            } else {
                $expiration = 0;
            }
            $this->cookie->set('redirected', 1, $expiration);
            $this->redirectToTarget($this->routesProcessor->getTargetParameters());
        }
        return '';
    }

    /**
     * Builds an URI from given parameters and redirects to
     * the built URI.
     *
     * @param array $parameters
     * @return void
     */
    protected function redirectToTarget(array $parameters)
    {
        $uriBuilder = ObjectUtility::getObjectManager()->get(UriBuilder::class);
        $uriBuilder
            ->setRequest($this->request)
            ->setCreateAbsoluteUri(false)
            ->setArgumentPrefix(null);

        if (isset($parameters['id'])) {
            $uriBuilder->setTargetPageUid($parameters['id']);
            unset($parameters['id']);
        } else {
            $uriBuilder->setTargetPageUid($GLOBALS['TSFE']->id);
        }

        $uriBuilder->setArguments($parameters);
        $this->redirectToUri($uriBuilder->buildFrontendUri());
    }
}
