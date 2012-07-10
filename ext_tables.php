<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
$tempColumns = array (
	
);


t3lib_div::loadTCA('be_groups');
t3lib_extMgm::addTCAcolumns('be_groups',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('be_groups','');

// --------------------------------------------

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pidisplay']='layout,select_key';


// Line added to use flexform 
$TCA["tt_content"]["types"]["list"]["subtypes_addlist"][$_EXTKEY."_pidisplay"]="pi_flexform"; 

t3lib_extMgm::addPlugin(array(
	'LLL:EXT:cas_reservation/locallang_db.xml:tt_content.list_type_pidisplay',
	$_EXTKEY . '_pidisplay',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');

// Line added to use flexform 
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pidisplay', 'FILE:EXT:'.$_EXTKEY.'/flexform_ds.xml');

// --------------------------------------------

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pibook']='layout,select_key';

// Line added to use flexform 
$TCA["tt_content"]["types"]["list"]["subtypes_addlist"][$_EXTKEY."_pibook"]="pi_flexform"; 

t3lib_extMgm::addPlugin(array(
	'LLL:EXT:cas_reservation/locallang_db.xml:tt_content.list_type_pibook',
	$_EXTKEY . '_pibook',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');

// Line added to use flexform 
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pibook', 'FILE:EXT:'.$_EXTKEY.'/flexform_ds.xml');

// --------------------------------------------

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pimanage']='layout,select_key';

// Line added to use flexform 
$TCA["tt_content"]["types"]["list"]["subtypes_addlist"][$_EXTKEY."_pimanage"]="pi_flexform"; 

t3lib_extMgm::addPlugin(array(
	'LLL:EXT:cas_reservation/locallang_db.xml:tt_content.list_type_pimanage',
	$_EXTKEY . '_pimanage',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');

// Line added to use flexform 
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pimanage', 'FILE:EXT:'.$_EXTKEY.'/flexform_ds.xml');

t3lib_extMgm::addStaticFile($_EXTKEY, 'static/', 'CAS Reservation');

?>
