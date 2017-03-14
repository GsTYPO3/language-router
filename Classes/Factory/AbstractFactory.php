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

/**
 * Abstract factory.
 *
 * Factory for getting data mappers suiting to a gateway.
 */
abstract class AbstractFactory
{
    /**
     * Get class name for given identifier and type.
     *
     * @param string|object $identifier Used to identify the class name
     * @param string $type The type of class the name is being fetched for
     * @throws \InvalidArgumentException if $identifier is not a valid string
     * @return string
     */
    protected static function getClassNameFor($identifier, $type)
    {
        if (is_object($identifier)) {
            $fullClassName = get_class($identifier);
            $classNameParts = explode('\\', $fullClassName);
            $identifier = array_pop($classNameParts);
        } elseif ($identifier && (!is_string($identifier) || !preg_match('/[a-z0-9]/i', $identifier))) {
            throw new \InvalidArgumentException('The provided identifier is invalid');
        }

        $className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['language_router']['classes']['routing'][$type][$identifier];
        if (class_exists($className)) {
            return $className;
        } else {
            throw new \InvalidArgumentException('Could not find a valid class for identifier "' . $identifier . '"!');
        }
    }
}
