<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'WapplerSystems.' . $_EXTKEY,
	'Pi1',
	array(
		'Flexslider' => 'list',
		
	),
	// non-cacheable actions
	array(
		
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT:source="FILE:EXT:ws_flexslider/Configuration/Typoscript/ContentElementWizard.txt">');

?>