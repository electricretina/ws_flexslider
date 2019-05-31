<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, 'pi1/class.tx_mediaelements_pi1.php', '_pi1', 'list_type', 1);


$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['mediaelements']);

// hooks into tt_news
$TYPO3_CONF_VARS['EXTCONF']['tt_news']['extraItemMarkerHook'][]  = 'EXT:mediaelements/pi1/class.tx_mediaelements_pi1.php:tx_mediaelements_pi1';

if ( $confArr['globalScript'] == 1) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] =  'EXT:mediaelements/hooks/class.tx_mediaelementshook.php:tx_mediaelementshook->includeJSandCSS';
}

?>