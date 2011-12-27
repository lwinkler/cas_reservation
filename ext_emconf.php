<?php

########################################################################
# Extension Manager/Repository config file for ext "cas_reservation".
#
<<<<<<< HEAD
# Auto generated 04-09-2011 22:42
=======
# Auto generated 25-08-2011 23:26
>>>>>>> 710f1db5513aa688f90b42236da603b77154068b
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Management of reservations',
	'description' => 'Management of reservations for CAS.',
	'category' => 'plugin',
	'author' => 'Laurent Winkler',
	'author_email' => 'laurent.winkler@bluewin.ch',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
<<<<<<< HEAD
	'version' => '1.0.4',
=======
	'version' => '1.0.1',
>>>>>>> 710f1db5513aa688f90b42236da603b77154068b
	'constraints' => array(
		'depends' => array(
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
<<<<<<< HEAD
	'_md5_values_when_last_written' => 'a:64:{s:9:"ChangeLog";s:4:"dcea";s:10:"README.txt";s:4:"f012";s:18:"cas_reservation.js";s:4:"a02d";s:21:"cas_reservation.kdev4";s:4:"d41d";s:9:"diff_fix1";s:4:"5eca";s:12:"ext_icon.gif";s:4:"d79c";s:17:"ext_localconf.php";s:4:"481f";s:14:"ext_tables.php";s:4:"4ba8";s:14:"ext_tables.sql";s:4:"6959";s:25:"ext_tables_static+adt.sql";s:4:"ebea";s:19:"fill_dates_group.py";s:4:"aeb2";s:15:"flexform_ds.xml";s:4:"c7f7";s:18:"locallang.xml.orig";s:4:"d41d";s:17:"locallang.xml.rej";s:4:"8a33";s:16:"locallang_db.xml";s:4:"8f65";s:23:"sql_transfert_codes.sql";s:4:"56f9";s:22:"sql_transfert_cost.sql";s:4:"c5d7";s:22:"sql_transfert_grid.sql";s:4:"b034";s:23:"template_book.html.orig";s:4:"d41d";s:22:"template_book.html.rej";s:4:"1dba";s:26:"template_display.html.orig";s:4:"d41d";s:25:"template_display.html.rej";s:4:"75ed";s:19:"doc/wizard_form.dat";s:4:"f2ef";s:20:"doc/wizard_form.html";s:4:"5d68";s:25:"images/entete-facture.png";s:4:"a297";s:21:"images/helpbubble.gif";s:4:"7877";s:16:"images/room1.png";s:4:"91d9";s:20:"images/room1_big.png";s:4:"ac76";s:21:"images/room2 _big.png";s:4:"958b";s:16:"images/room2.png";s:4:"bcda";s:16:"images/room3.png";s:4:"570a";s:20:"images/room3_big.png";s:4:"26ed";s:41:"pibook/class.tx_casreservation_pibook.php";s:4:"9ef0";s:20:"pibook/locallang.xml";s:4:"adba";s:25:"pibook/template_book.html";s:4:"5c10";s:47:"pidisplay/class.tx_casreservation_pidisplay.php";s:4:"eb8a";s:23:"pidisplay/locallang.xml";s:4:"d37c";s:31:"pidisplay/template_display.html";s:4:"8328";s:39:"pilib/class.tx_casreservation_pilib.php";s:4:"1d57";s:19:"pilib/locallang.xml";s:4:"de41";s:45:"pimanage/class.tx_casreservation_pimanage.php";s:4:"b62c";s:50:"pimanage/class.tx_casreservation_pimanage.php.orig";s:4:"2c95";s:49:"pimanage/class.tx_casreservation_pimanage.php.rej";s:4:"5b97";s:28:"pimanage/foot_bill_room1.txt";s:4:"387f";s:28:"pimanage/foot_bill_room2.txt";s:4:"d235";s:28:"pimanage/foot_mail_room1.txt";s:4:"baec";s:28:"pimanage/foot_mail_room2.txt";s:4:"6593";s:28:"pimanage/head_bill_room1.txt";s:4:"1f19";s:28:"pimanage/head_bill_room2.txt";s:4:"0ba2";s:28:"pimanage/head_mail_room1.txt";s:4:"588d";s:28:"pimanage/head_mail_room2.txt";s:4:"99ce";s:28:"pimanage/line_bill_room1.txt";s:4:"6a13";s:28:"pimanage/line_bill_room2.txt";s:4:"ae03";s:28:"pimanage/line_mail_room1.txt";s:4:"2170";s:28:"pimanage/line_mail_room2.txt";s:4:"2170";s:22:"pimanage/locallang.xml";s:4:"68de";s:29:"pimanage/template_manage.html";s:4:"fa7d";s:34:"pimanage/plots/plot_week_view1.php";s:4:"36d5";s:34:"pimanage/plots/plot_week_view2.php";s:4:"63e5";s:34:"pimanage/plots/plot_year_view1.php";s:4:"4e96";s:34:"pimanage/plots/plot_year_view2.php";s:4:"29b7";s:20:"static/constants.txt";s:4:"80ae";s:16:"static/setup.txt";s:4:"c1b9";s:30:"static/css/cas_reservation.css";s:4:"fba9";}',
=======
	'_md5_values_when_last_written' => 'a:61:{s:9:"ChangeLog";s:4:"dcea";s:10:"README.txt";s:4:"f012";s:18:"cas_reservation.js";s:4:"0691";s:21:"cas_reservation.kdev4";s:4:"d41d";s:12:"ext_icon.gif";s:4:"d79c";s:17:"ext_localconf.php";s:4:"481f";s:14:"ext_tables.php";s:4:"4ba8";s:14:"ext_tables.sql";s:4:"6959";s:25:"ext_tables_static+adt.sql";s:4:"ebea";s:19:"fill_dates_group.py";s:4:"aeb2";s:15:"flexform_ds.xml";s:4:"c7f7";s:18:"locallang.xml.orig";s:4:"d41d";s:17:"locallang.xml.rej";s:4:"8a33";s:16:"locallang_db.xml";s:4:"8f65";s:23:"sql_transfert_codes.sql";s:4:"56f9";s:22:"sql_transfert_cost.sql";s:4:"c5d7";s:22:"sql_transfert_grid.sql";s:4:"b034";s:23:"template_book.html.orig";s:4:"d41d";s:22:"template_book.html.rej";s:4:"1dba";s:26:"template_display.html.orig";s:4:"d41d";s:25:"template_display.html.rej";s:4:"75ed";s:19:"doc/wizard_form.dat";s:4:"f2ef";s:20:"doc/wizard_form.html";s:4:"5d68";s:25:"images/entete-facture.png";s:4:"a297";s:21:"images/helpbubble.gif";s:4:"7877";s:16:"images/room1.png";s:4:"91d9";s:20:"images/room1_big.png";s:4:"ac76";s:21:"images/room2 _big.png";s:4:"958b";s:16:"images/room2.png";s:4:"bcda";s:16:"images/room3.png";s:4:"570a";s:20:"images/room3_big.png";s:4:"26ed";s:41:"pibook/class.tx_casreservation_pibook.php";s:4:"249d";s:20:"pibook/locallang.xml";s:4:"adba";s:25:"pibook/template_book.html";s:4:"b50c";s:47:"pidisplay/class.tx_casreservation_pidisplay.php";s:4:"eb8a";s:23:"pidisplay/locallang.xml";s:4:"d37c";s:31:"pidisplay/template_display.html";s:4:"8328";s:39:"pilib/class.tx_casreservation_pilib.php";s:4:"1b16";s:19:"pilib/locallang.xml";s:4:"838f";s:45:"pimanage/class.tx_casreservation_pimanage.php";s:4:"e8ec";s:28:"pimanage/foot_bill_room1.txt";s:4:"387f";s:28:"pimanage/foot_bill_room2.txt";s:4:"d235";s:28:"pimanage/foot_mail_room1.txt";s:4:"baec";s:28:"pimanage/foot_mail_room2.txt";s:4:"6593";s:28:"pimanage/head_bill_room1.txt";s:4:"1f19";s:28:"pimanage/head_bill_room2.txt";s:4:"0ba2";s:28:"pimanage/head_mail_room1.txt";s:4:"588d";s:28:"pimanage/head_mail_room2.txt";s:4:"99ce";s:28:"pimanage/line_bill_room1.txt";s:4:"6a13";s:28:"pimanage/line_bill_room2.txt";s:4:"ae03";s:28:"pimanage/line_mail_room1.txt";s:4:"2170";s:28:"pimanage/line_mail_room2.txt";s:4:"2170";s:22:"pimanage/locallang.xml";s:4:"9b37";s:29:"pimanage/template_manage.html";s:4:"5bcc";s:34:"pimanage/plots/plot_week_view1.php";s:4:"36d5";s:34:"pimanage/plots/plot_week_view2.php";s:4:"63e5";s:34:"pimanage/plots/plot_year_view1.php";s:4:"4e96";s:34:"pimanage/plots/plot_year_view2.php";s:4:"29b7";s:20:"static/constants.txt";s:4:"80ae";s:16:"static/setup.txt";s:4:"c1b9";s:30:"static/css/cas_reservation.css";s:4:"fba9";}',
>>>>>>> 710f1db5513aa688f90b42236da603b77154068b
);

?>
