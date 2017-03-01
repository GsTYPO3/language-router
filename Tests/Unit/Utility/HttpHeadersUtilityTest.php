<?php
namespace NIMIUS\LanguageRouter\Test\Unit\Utility;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Unit test case for HttpheadersUtility class.
 */
class HttpHeadersUtilityTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * Test that getAcceptedLocales() returns an array of 5-char locales with priority.
     *
     * @test
     */
    public function testGetAcceptedLocalesReturnsArrayWith5CharLocalesByPriority()
    {   
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'en-US,en;q=0.8,de;q=0.6,fr;q=0.4';
        $this->assertEquals(
            [
                'en_US' => 1.0,
                'en_EN' => (float)0.8,
                'de_DE' => (float)0.6,
                'fr_FR' => (float)0.4
            ],
            HttpHeadersUtility::getAcceptedLocales()
        );
    }
    
    /**
     * Test that getAcceptedLocales() returns a precise language first before a "general"
     * one. This means that e.g. 'en,en-US,...' should return en_US first despite 'en' being first.
     *
     * @test
     */
    public function testGetAcceptedLocalesReturnsPreciserLanguageOverGeneralLanguage()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'en,en-US,en-AU;q=0.8,fr;q=0.6,en-GB;q=0.4';
        $this->assertEquals(
            [
                'en_US' => 1.0,
                'en_EN' => 1.0,
                'en_AU' => (float)0.8,
                'fr_FR' => (float)0.6,
                'en_GB' => (float)0.4
            ],
            HttpHeadersUtility::getAcceptedLocales()
        );
    }
    
    /**
     * Test tear-down.
     *
     * Flushes the internal method caches, as certain methods
     * implement their own cache.
     *
     * @return void
     */
    public function tearDown()
    {
        GeneralUtility::flushInternalRuntimeCaches();
    }
}
