<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Sven Wappler <typo3YYYY@wapplersystems.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Helper Class which makes various tools and helper available
 *
 * @author Sven Wappler <typo3YYYY@wapplersystems.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_WsFlexslider_Utility_Div
{

	/**
	 * Better implementation of php's array_combine().
	 * This wont throw false in case both array haven't an identical size.
	 *
	 * @static
	 * @param array $a Array containing the keys.
	 * @param array $b Array containing the values.
	 * @param bool $pad Switch for allowing padding. Fills the combined array with empty values if any array is larger than the other one.
	 * @return array Combined array.
	 */
	public static function combineArray($a, $b, $pad = true) {
		$acount = count($a);
		$bcount = count($b);
		// more elements in $a than $b but we don't want to pad either
		if (!$pad) {
			$size = ($acount > $bcount) ? $bcount : $acount;
			$a = array_slice($a, 0, $size);
			$b = array_slice($b, 0, $size);
		} else {
			// more headers than row fields
			if ($acount > $bcount) {
				$more = $acount - $bcount;
				// how many fields are we missing at the end of the second array?
				// Add empty strings to ensure arrays $a and $b have same number of elements
				$more = $acount - $bcount;
				for($i = 0; $i < $more; $i++) {
					$b[] = "";
				}
				// more fields than headers
			} else if ($acount < $bcount) {
				$more = $bcount - $acount;
				// fewer elements in the first array, add extra keys
				for($i = 0; $i < $more; $i++) {
					$key = 'extra_field_0' . $i;
					$a[] = $key;
				}

			}
		}

		$z = array_combine($a, $b);
		if (!$z) return array();

		return $z;
	}

	/**
	 * Returns the reference to a 'resource' in TypoScript.
	 *
	 * @param string $file File get a reference from - can contain EXT:ext_name
	 * @return mixed
	 */
	public static function getFileResource($file)
	{
		return $GLOBALS['TSFE']->tmpl->getFileName($file);
	}

	/**
	 * Checks a passed CSS or JS file and adds it to the Frontend.
	 *
	 * @param string $file File reference
	 * @param string $addUnique Unique key to avoid multiple inclusions
	 * @param bool $moveToFooter Flag to include file into footer - doesn't work for CSS files
	 */
	public static function addCssJsFile($file, $addUnique = NULL, $moveToFooter = FALSE)
	{
		// Get file extension (after last occurance of a dot)
		$mediaTypeSplit = strrchr($file, '.');
		// Get file reference
		$resolved = self::getFileResource($file);

		if ($resolved) {
			// JavaScript processing
			if ($mediaTypeSplit == '.js') {
				if ($addUnique) {
					$code = '<script src="' . $resolved . '" type="text/javascript"></script>';
					($moveToFooter)
						? $GLOBALS['TSFE']->additionalFooterData[$addUnique] = $code
						: $GLOBALS['TSFE']->additionalHeaderData[$addUnique] = $code;

				} else {
					($moveToFooter)
						? $GLOBALS['TSFE']->getPageRenderer()->addJsFooterFile($resolved)
						: $GLOBALS['TSFE']->getPageRenderer()->addJsFile($resolved);
				}

			// Stylesheet processing
			} elseif ($mediaTypeSplit == '.css') {
				if ($addUnique) {
					$GLOBALS['TSFE']->additionalHeaderData[$addUnique] =
						'<link href="' . $resolved . '" rel="stylesheet" type="text/css" media="all" />';

				} else {
					$GLOBALS['TSFE']->getPageRenderer()->addCssFile($resolved);
				}
			}
		}
	}

	/**
	 * Adds/renders a Flash message.
	 *
	 * @param string $title The title
	 * @param string $message The message
	 * @param int $type Message level
	 * @return mixed
	 */
	public static function renderFlashMessage($title, $message, $type = t3lib_FlashMessage::WARNING) {
		$code  = ".typo3-message .message-header{padding: 10px 10px 0 30px;font-size:0.9em;}";
		$code .= ".typo3-message .message-body{padding: 10px;font-size:0.9em;}";

		$GLOBALS['TSFE']->getPageRenderer()->addCssFile(t3lib_extMgm::siteRelPath('t3skin') . 'stylesheets/visual/element_message.css');
		$GLOBALS['TSFE']->getPageRenderer()->addCssInlineBlock('flashmessage',$code);

		$flashMessage = $this->objectManager->get('t3lib_FlashMessage', $message, $title, $type);
		return $flashMessage->render();
	}

}
