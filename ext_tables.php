<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// Build extension name vars - used for plugin registration, flexforms and similar
$extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY);
$pluginSignature = strtolower($extensionName) . '_pi1';

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'WapplerSystems.'.$_EXTKEY,
	'Pi1',
	'WS Flexslider'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Flexslider');



\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_wsflexslider_domain_model_image', 'EXT:ws_flexslider/Resources/Private/Language/locallang_csh_tx_wsflexslider_domain_model_image.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_wsflexslider_domain_model_image');
$TCA['tx_wsflexslider_domain_model_image'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:ws_flexslider/Resources/Private/Language/locallang_db.xml:tx_wsflexslider_domain_model_image',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,description,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Image.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/picture.png'
	),
);


$tempColumns = array(
	'tx_wsflexslider_images' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:ws_flexslider/Resources/Private/Language/locallang.xml:tx_wsflexslider_domain_model_flexslider.images',
		'config' => array(
			'type' => 'inline',
			'foreign_table' => 'tx_wsflexslider_domain_model_image',
			'foreign_field' => 'content_uid',
			'foreign_label' => 'title',
			'foreign_sortby' => 'sorting',
			'maxitems' => '100',
			'appearance' => array(
				#'collapseAll' => 0, // Auskommentieren, da es sonst immer als true interpretiert wird
				'expandSingle' => true,
				'newRecordLinkAddTitle' => 1,
				'newRecordLinkPosition' => "both",
				'showAllLocalizationLink' => TRUE,
				'showPossibleLocalizationRecords' => TRUE,
			),
			'behaviour' => array(
				'localizationMode' => 'select',
				'localizeChildrenAtParentLocalization' => TRUE,
			),
		)
	),
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns, 1);

$temp2Columns = array(
		"tx_wsflexslider_activate" => array(
				"exclude" => 1,
				"label" => "LLL:EXT:ws_flexslider/Resources/Private/Language/locallang_db.xml:tt_content.tx_wsflexslider_activate",
				"config" => array(
						"type" => "check",
				)
		),
		"tx_wsflexslider_duration" => array(
				"exclude" => 1,
				"label" => "LLL:EXT:ws_flexslider/Resources/Private/Language/locallang_db.xml:tt_content.tx_wsflexslider_duration",
				"config" => array(
						"type" => "input",
						"size" => "5",
						"trim" => "int",
						"default" => "6000"
				)
		),
);

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('tt_news')) {
	//\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'static/tt_news/', 'Image-Cycle for tt_news - Cycle');
	//\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'static/tt_news/nivoslider/', 'Image-Cycle for tt_news - Nivo');
	//\TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA('tt_news');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_news', $temp2Columns, 1);
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_news', 'tx_wsflexslider', '', 'after:image');
}

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'tx_wsflexslider_images,pi_flexform';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForm/Flexslider.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_wsflexslider_domain_model_image');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin($_EXTKEY, 'Pi1', 'Flexslider');




if (TYPO3_MODE == 'BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['WapplerSystems\WsFlexslider\Wizicon'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Wizicon.php';
}

?>