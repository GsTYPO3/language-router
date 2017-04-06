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
 * Interface for detection classes.
 */
interface DetectionInterface
{
    /**
     * Executes detection.
     *
     * This method runs its detection code and, if
     * it matches, stores redirect parameters.
     *
     * @return bool true if a configuration matches, false otherwise
     */
    public function matches();
}
