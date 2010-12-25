<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

t3lib_extMgm::addPItoST43($_EXTKEY, 'pidisplay/class.tx_casreservation_pidisplay.php', '_pidisplay', 'list_type', 1);


t3lib_extMgm::addPItoST43($_EXTKEY, 'pibook/class.tx_casreservation_pibook.php', '_pibook', 'list_type', 1);


t3lib_extMgm::addPItoST43($_EXTKEY, 'pimanage/class.tx_casreservation_pimanage.php', '_pimanage', 'list_type', 1);


t3lib_extMgm::addPItoST43($_EXTKEY, 'pilib/class.tx_casreservation_pilib.php', '_pilib', '', 1);
?>