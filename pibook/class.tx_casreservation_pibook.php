<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Laurent Winkler <laurent.winkler@bluewin.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');
require_once(PATH_t3lib.'class.t3lib_htmlmail.php');
require_once(t3lib_extMgm::extPath('cas_reservation').'pilib/class.tx_casreservation_pilib.php'); // Extension library

/**
 * Plugin 'Create a new reservation' for the 'cas_reservation' extension.
 *
 * @author	Laurent Winkler <laurent.winkler@bluewin.ch>
 * @package	TYPO3
 * @subpackage	tx_casreservation
 */
class tx_casreservation_pibook extends tslib_pibase {
	var $prefixId      = 'tx_casreservation_pibook';		// Same as class name
	var $scriptRelPath = 'pibook/class.tx_casreservation_pibook.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'cas_reservation';	// The extension key.
	var $pi_checkCHash = true;
	
	var $delaymin = "+7 day"; // Delay min and max to make a reservation (change if admin)
	var $delaymax = "+52 weeks";
	
	// the following values are imported from flexform
	var $admin = ""; // Nb of admin group
	var $rooms= array(); // Room to manage in this plugin
	var $email_admin=''; // Email address where to send notifications
	var $send_mail = 0;

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		//ini_set('display_errors', TRUE);
		//error_reporting(E_ALL);
		if($GLOBALS['TSFE']->fe_user->user['uid']=='') return 'Error : This page can only be accessed by logged in users.';

		/// Get plugin parameters from flexform
		$this->pi_initPIflexForm(); // Init and get the flexform data of the plugin
		$piFlexForm = $this->cObj->data['pi_flexform'];
		$this->admin = $this->pi_getFFvalue($piFlexForm, "admin", "sDEF");
		$this->rooms = explode(',', $this->pi_getFFvalue($piFlexForm, "room", "sDEF"));
		$this->email_admin = $this->pi_getFFvalue($piFlexForm, "email_admin", "sDEF");
		$this->send_email = $this->pi_getFFvalue($piFlexForm, "send_email", "sDEF");

		$dateSelectedMonday = '';
		$dateThisMonday = tx_casreservation_pilib::getMonday();
		if(isset($this->piVars['week']))
			$dateSelectedMonday= htmlspecialchars($this->piVars['week']);

		// Delai min et max pour reservation
		if(tx_casreservation_pilib::isAdmin($this)){
			$this->delaymin="0 day";
			$this->delaymax="+104 weeks";
		}

		if( $dateSelectedMonday == '' || strtotime($dateSelectedMonday) < strtotime($this->delaymin, strtotime($dateThisMonday))
				|| strtotime($dateSelectedMonday) > strtotime($this->delaymax, strtotime($dateThisMonday)))
			$dateSelectedMonday = $dateThisMonday;
			
		$room='';
		if(isset($this->piVars['room'])) $room = htmlspecialchars($this->piVars['room']);
		if($room=='' || count($this->rooms)==1){
			$room= $this->rooms[0];
		}
		$material='';
		if(isset($this->piVars['material'])) $material=intval($this->piVars['material']);
		if($material=='') $material="1";

		/// Initializations

		if(isset($this->piVars['submit_button'])) { 
			$content=$this->book();
			return $this->pi_wrapInBaseClass($content);
		}
		
		//$label=tx_casreservation_pilib::getGroupName();
		
		// Get template
		$this->templateCode = $this->cObj->fileResource($conf['templateFile']);
		
		// Get the parts out of the template
		$template['total'] = $this->cObj->getSubpart($this->templateCode,'###TEMPLATE###');

		// Fill markers
		$markerArray['###SELECT_WEEK###'] = tx_casreservation_pilib::displaySelectMonday($this->delaymin, $this->delaymax, $this, $dateSelectedMonday, $room);
		$markerArray['###WEEK###'] = $dateSelectedMonday;
		$markerArray['###SELECT_ROOM###'] = tx_casreservation_pilib::displaySelectRoom($this->rooms, $this);
		$markerArray['###DATE###'] = tx_casreservation_pilib::getDate();
		$markerArray['###ROOM###'] = $room;
		//$markerArray['###LABEL###'] = $label;
		$markerArray['###FORM_ACTION_SELECT_WEEK###'] = $this->pi_getPageLink($GLOBALS['TSFE']->id, '');
		$markerArray['###FORM_ACTION_BOOKING###'] = $this->pi_getPageLink($GLOBALS['TSFE']->id, '', array("week"=>$dateSelectedMonday));


		$markerArray['###DIV_WEEKVIEW###'] = tx_casreservation_pilib::displayGrid($room, $dateSelectedMonday, true, $this->delaymin, $this->delaymax, $this);
		$markerArray['###DISPLAY_COSTS###'] = tx_casreservation_pilib::displayCosts($room);
		$markerArray['###MATERIAL###'] = $this->generateMaterial($room);

		$markerArray['###FARE###'] = $this->pi_getLL('fare');
		$markerArray['###NOTE###'] = $this->pi_getLL('note');
		$markerArray['###LABEL###'] = $this->pi_getLL('label') . '&nbsp;<img src="typo3conf/ext/cas_reservation/images/helpbubble.gif" title="' . $this->pi_getLL('descr_label') . '" alt=""/>';
		$markerArray['###LABEL_VALUE###'] = $GLOBALS['TSFE']->fe_user->user['company'];
		$markerArray['###MAKE_A_DEMAND###'] = $this->pi_getLL('make_a_demand');

		// Create the content by replacing the content markers in the template
		$content = $this->cObj->substituteMarkerArrayCached($template['total'],$markerArray);


		return $this->pi_wrapInBaseClass($content);

	}
//========================================================================
/** Book a reservation */
//========================================================================

	function book() {
	// Ici, on lance les requetes qui reservent les dates demandees
	if(! isset($this->piVars['week'])) return "ERROR : Week must be set";
	$week= htmlspecialchars($this->piVars['week']);
	$room= htmlspecialchars($this->piVars['room']);
	$arr[0]=$week;
	$arr[1]=date("Y-m-d",strtotime("+1 day",strtotime($week)));
	$arr[2]=date("Y-m-d",strtotime("+2 day",strtotime($week)));
	$arr[3]=date("Y-m-d",strtotime("+3 day",strtotime($week)));
	$arr[4]=date("Y-m-d",strtotime("+4 day",strtotime($week)));
	$arr[5]=date("Y-m-d",strtotime("+5 day",strtotime($week)));
	$arr[6]=date("Y-m-d",strtotime("+6 day",strtotime($week)));
	$note= htmlspecialchars($this->piVars['note']);
	$material= intval($this->piVars['material']);
	$label= htmlspecialchars($this->piVars['label']);
	$content='';
	$cpt=0;

	if($this->piVars['submit_button']){
		if($label == '') {
			$content.="<p>Le champ <b>Groupe ou activité</b> doit être rempli.</p>";
		}
		else
		{
			for ($i=0; $i<7; $i++)
			for ($p=8; $p<=20; $p++)
			if(isset($this->piVars['cb-'.$arr[$i]."-".$p]) && $this->piVars['cb-'.$arr[$i]."-".$p]){
				$insertArray = array(
				"member_id" => $GLOBALS['TSFE']->fe_user->user['uid'],
				"label" => $label,
				"room" => $room,
				"material" => $material,
				"date_demand" => date("Y-m-d"),
				"time_demand" => date("H:i"),
				"date_reserv" => $arr[$i],
				"time_reserv" => $p,
				"status" => "1",
				"note" => $note
				);
				$GLOBALS['TYPO3_DB']->exec_INSERTquery("tx_casreservation_reservation", $insertArray)
					or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
				$cpt++;
			}
			$msg_arr[] = "$cpt demandes traitées.";
			$msgflag = true;
		
			// Envoie le mail
			if($cpt > 0)
			{
				$message = "Nouvelle demande de réservation de la salle no ".$room." pour le groupe '".$label."'";
				$this->sendEmail($message);
			}
		}
	}
	if($msgflag) {
		$_SESSION['MSG_ARR'] = $msg_arr;
	}
	$content.= '<p>Votre demande de réservation pour '.$cpt.' période(s) a été traitée. </p>';
	$content.= '<p>'.$this->pi_linkToPage('Retour', $GLOBALS['TSFE']->id, '', array($this->prefixId.'[week]' => $week, 'tx_casreservation_pibook[room]' => $room, 'tx_casreservation_pibook[material]' => $material)).'</p>';

	return $this->pi_wrapInBaseClass($content);
	}

//========================================================================
/// Send an e-mail
//========================================================================

	function sendEmail($message){
		$content='';
		$dest=$this->email_admin;
		if($message!=""){
			if( $this->send_email && $this->cObj->sendNotifyEmail('CAS : nouvelle réservations'.chr(10).$message, $dest, '', 'no_reply@cas-moleson.ch', 'CAS réservations', '') ) //sendHTMLMail
				$content.= "Un e-mail envoyé à ".$dest."<br/>";
			else
				$content.= "Email non envoyé (erreur) : $dest <br />,";
			$content.= "\"*CAS réservations : nouvelle réservation*\" <br />#<br /> ".$message."<br />#<br /> \"From: no_reply@cas-moleson.ch\" <br />";
		}
		return $content;
	}


//========================================================================
/// Radio button to select material or not
//========================================================================

	function generateMaterial($room){
		$content='';
	if($room=='')return 'Error : generateMaterial, room empty';
	$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('material', 'tx_casreservation_costs', 'room='.$room, 'material')
		or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());

	if($GLOBALS['TYPO3_DB']->sql_num_rows($result) > 1)
		$content.='<td>Matériel</td>
<td><input type="radio" name="tx_casreservation_pibook[material]" value="1" checked /> Oui <input type="radio" name="tx_casreservation_pibook[material]" value="0" /> Non </td>
';
	$GLOBALS['TYPO3_DB']->sql_free_result($result);

	return $content == '' ? '&nbsp;': $content;

	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cas_reservation/pibook/class.tx_casreservation_pibook.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cas_reservation/pibook/class.tx_casreservation_pibook.php']);
}

?>



