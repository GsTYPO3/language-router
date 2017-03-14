<?php
namespace NIMIUS\LanguageRouter\Factory;

/**
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
use NIMIUS\LanguageRouter\Utility\ObjectUtility;

/**
 * Detection factory.
 *
 * Factory to instantiate routing detection classes.
 */
class DetectionFactory extends AbstractFactory
{
    /**
     * Method to instantiate a concrete detection.
     *
     * @param string $detection
     * @return object
     */
    public static function get($detection)
    {
        $className = self::getClassNameFor($detection, 'detection');
        return ObjectUtility::getObjectManager()->get($className);
    }
}
