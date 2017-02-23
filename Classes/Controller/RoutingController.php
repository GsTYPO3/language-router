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
use NIMIUS\LanguageRouter\Utility\HttpHeadersUtility;
use NIMIUS\LanguageRouter\Utility\LanguageUtility;
use NIMIUS\LanguageRouter\Utility\ObjectUtility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
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
     * @var int Current sys_language_uid.
     */
    protected $currentLanguageUid;
    
    /**
     * @var int Current page uid.
     */
    protected $currentPageUid;
    
    /**
     * @var string|null Current country, set via config.country.
     */
    protected $currentCountry;
    
    /**
     * @var array Accepted languages from client browser.
     */
    protected $acceptedLocales = [];
    
    /**
     * @var string|null Country from php-geoip, if available.
     */
    protected $acceptedCountry;
    
    
    /**
     * Class constructor.
     *
     * Initializes instance properties for processing actions.
     */
    public function __construct()
    {   
        $this->currentPageUid = (int)$GLOBALS['TSFE']->id;
        $this->currentLanguageUid = (int)$GLOBALS['TSFE']->sys_language_uid;
        $this->acceptedLocales = HttpHeadersUtility::getAcceptedLocales();
        
        if (function_exists('geoip_country_code_by_name')) {
            $address = GeneralUtility::getIndpEnv('HTTP_X_FORWARDED_FOR');
            if (!$address) {
                $address = GeneralUtility::getIndpEnv('REMOTE_ADDR');
            }
            $this->acceptedCountry = strtoupper(geoip_country_code_by_name($address));
            $this->currentCountry = ConfigurationUtility::getFullTypoScript()['config.']['country'];
            
        }
        
        // TODO testing
        $this->acceptedCountry = 'CH';
        $this->currentCountry = 'CH';
    }
    
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
        if (empty($configuration) || empty($configuration['routes'])) {
            return '';
        }
        
        foreach ($configuration['routes'] as $route) {
            if ($route['detection'] == 'acceptedLanguages') {
                foreach ($route['targets'] as $language => $targetParameters) {
                    $locale = LanguageUtility::convertToLocale($language);
                    if (!isset($targetParameters['id'])) {
                        $targetParameters['id'] = $this->currentPageUid;
                    }
                    
                    if (array_key_exists($locale, $this->acceptedLocales)) {
                        if (!$this->currentPageMatchesTarget($targetParameters)) {
                            $this->redirectToTarget($targetParameters);
                        }
                        return '';
                    }
                }
            } elseif ($route['detection'] == 'country') {
                if (!$this->currentCountry) {
                    continue;
                }
                
                foreach ($route['targets'] as $country => $targetParameters) {
                    if (strtoupper($country) == $this->acceptedCountry) {
                        if (
                            !$this->currentPageMatchesTarget($targetParameters)
                            && $this->currentCountry != $country
                        ) {
                            $this->redirectToTarget($targetParameters);
                        }
                        return '';
                    }
                }
            }
        }
        return '';
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
            $uriBuilder->setTargetPageUid($this->currentPageUid);
        }
        
        $uriBuilder->setArguments($parameters);
        $this->redirectToUri($uriBuilder->buildFrontendUri());
    }
}
