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

use NIMIUS\LanguageRouter\Utility\LanguageUtility;

/**
 * Unit test case for LanguageUtility class.
 */
class LanguageUtilityTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * Test that convertToLocale() correctly handles inputs being 2 characters in length.
     *
     * @test
     */
    public function testConvertToLocaleCorrectlyConverts2CharLocales()
    {
        $this->assertEquals(LanguageUtility::convertToLocale('en'), 'en_EN');
        $this->assertEquals(LanguageUtility::convertToLocale('de'), 'de_DE');
    }

    /**
     * Test that convertToLocale() correctly handles inputs being 5 characters in length.
     *
     * @test
     */
    public function testConvertToLocaleCorrectlyConverts5CharLocales()
    {
        $this->assertEquals(LanguageUtility::convertToLocale('en_en'), 'en_EN');
        $this->assertEquals(LanguageUtility::convertToLocale('de_DE'), 'de_DE');
        $this->assertEquals(LanguageUtility::convertToLocale('fr-fr'), 'fr_FR');
        $this->assertEquals(LanguageUtility::convertToLocale('ch_it'), 'ch_IT');
    }
}
