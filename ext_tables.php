<?php
defined('TYPO3_MODE') || die ('Access denied.');
$tempColumns = array();


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('be_groups', $tempColumns, 1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('be_groups', '');

// --------------------------------------------

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_pidisplay'] = 'layout,select_key';

// Line added to use flexform
$GLOBALS['TCA']["tt_content"]["types"]["list"]["subtypes_addlist"][$_EXTKEY . "_pidisplay"] = "pi_flexform";

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(array(
    'LLL:EXT:cas_reservation/locallang_db.xml:tt_content.list_type_pidisplay',
    $_EXTKEY . '_pidisplay',
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'ext_icon.gif',
), 'list_type');

// Line added to use flexform
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($_EXTKEY . '_pidisplay', 'FILE:EXT:' . $_EXTKEY . '/flexform_ds.xml');

// --------------------------------------------

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_pibook'] = 'layout,select_key';

// Line added to use flexform
$GLOBALS['TCA']["tt_content"]["types"]["list"]["subtypes_addlist"][$_EXTKEY . "_pibook"] = "pi_flexform";

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(array(
    'LLL:EXT:cas_reservation/locallang_db.xml:tt_content.list_type_pibook',
    $_EXTKEY . '_pibook',
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'ext_icon.gif',
), 'list_type');

// Line added to use flexform
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($_EXTKEY . '_pibook', 'FILE:EXT:' . $_EXTKEY . '/flexform_ds.xml');

// --------------------------------------------

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_pimanage'] = 'layout,select_key';

// Line added to use flexform
$GLOBALS['TCA']["tt_content"]["types"]["list"]["subtypes_addlist"][$_EXTKEY . "_pimanage"] = "pi_flexform";

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(array(
    'LLL:EXT:cas_reservation/locallang_db.xml:tt_content.list_type_pimanage',
    $_EXTKEY . '_pimanage',
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'ext_icon.gif',
), 'list_type');

// Line added to use flexform
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($_EXTKEY . '_pimanage', 'FILE:EXT:' . $_EXTKEY . '/flexform_ds.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'static/', 'CAS Réservation');
