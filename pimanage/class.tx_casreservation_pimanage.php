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
/*t3lib_div::debug($this->conf, 'conf');
		t3lib_div::debug($GLOBALS['TSFE']->fe_user->user, 'tsfe');
		ini_set('display_errors', TRUE);
		error_reporting(E_ALL);/**/

require_once(PATH_tslib.'class.tslib_pibase.php');
require_once(PATH_t3lib.'class.t3lib_htmlmail.php');
require_once(t3lib_extMgm::extPath('cas_reservation').'pilib/class.tx_casreservation_pilib.php'); // Extension library

/**
 * Plugin 'Administration of reservations' for the 'cas_reservation' extension.
 *
 * @author	Laurent Winkler <laurent.winkler@bluewin.ch>
 * @package	TYPO3
 * @subpackage	tx_casreservation
 * @version     $Id: $
 */
class tx_casreservation_pimanage extends tslib_pibase {
	var $prefixId      = 'tx_casreservation_pimanage';		// Same as class name
	var $scriptRelPath = 'pimanage/class.tx_casreservation_pimanage.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'cas_reservation';	// The extension key.
	var $pi_checkCHash = true;

	// how many records to show per page
	var $rowsPerPage = 20;
	var $allids = '';
	var $isAdmin;
	
	// the following values are imported from flexform
	var $admin = "";
	var $rooms = array();
	var $room_names = array();
	var $room;
	var $send_email = 0;
	var $jpgraph_pathlib = "";
	var $jpgraph_pathsampler = "";

	// members
	var $nb_records = 0;

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
		// ini_set('display_errors', TRUE);
		// error_reporting(E_ALL);
		$this->templateCode = $this->cObj->fileResource($conf['templateFile']);

		// Initialization
		$GLOBALS['TSFE']->additionalHeaderData[] = '<script type="text/javascript" src="typo3conf/ext/cas_reservation/cas_reservation.js"></script>';
		if($GLOBALS['TSFE']->fe_user->user['uid']=='') return 'Error : This page can only be accessed by logged in users.';

		/// Get plugin parameters from flexform
		$this->pi_initPIflexForm(); // Init and get the flexform data of the plugin
		$piFlexForm = $this->cObj->data['pi_flexform'];
		$this->admin = $this->pi_getFFvalue($piFlexForm, "admin", "sDEF");
		$this->rooms = explode(',', $this->pi_getFFvalue($piFlexForm, "room", "sDEF"));
		$this->send_email = $this->pi_getFFvalue($piFlexForm, "send_email", "sDEF");
		$this->isAdmin = tx_casreservation_pilib::isAdmin($this);

		// Get room names
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('id, room_name',
			'tx_casreservation_room',
			'id IN('.implode(',',$this->rooms).')','', 'id')
			or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_row($result))
		{
			list($id, $room_name) = $row;
			$this->room_names[$id] = $room_name;
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($result);

		// Get values
		if(isset($this->piVars['status']))
			$condStatus = intval($this->piVars['status']);
		else $condStatus="1";

		if(isset($this->piVars['past']))
			$condPast=($this->piVars['past']=="on");
		else $condPast=false;
		
		$this->room='';
		if(isset($this->piVars['room'])) $this->room= intval($this->piVars['room']);
		if($this->room=='' && count($this->rooms)==1){
			$this->room = $this->rooms[0];
		}
		$condRoom = $this->room;

		$page = intval($this->piVars['page']);
		if($page < 1)$page = 1;

		if($this->piVars['stats'] == "on") return $this->showStatistiques();


		// Set conditions for filter
		if($this->isAdmin){
			if(isset($this->piVars['user']))
				$condUser=intval($this->piVars['user']);
			else $condUser="";

			// On compte les emails et factures a envoyer
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('count(*)', 
				'tx_casreservation_email JOIN tx_casreservation_reservation ON tx_casreservation_reservation.id=reservation_id',
				'room IN('.implode(',',$this->rooms).')') 
				or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error()); 
			list($nb_chg) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result);
			$GLOBALS['TYPO3_DB']->sql_free_result($result);
		}else{
			$condUser=$GLOBALS['TSFE']->fe_user->user['uid'];
			$nb_chg=0;
		}
		
		// Set filter conditions
		$conditions=" true ";
		if($condUser!="")
			$conditions.=' and tx_casreservation_reservation.member_id='.$GLOBALS['TYPO3_DB']->fullQuoteStr($condUser, 'tx_casreservation_reservation'); 
		if(!$condPast)
			$conditions.=" and tx_casreservation_reservation.date_reserv>=current_date ";	

		//if(!tx_casreservation_pilib::isAdmin($this)) $cpt[2]+= $cpt[3] + $cpt[4];

		/*if($condStatus!="0" && $condStatus!="1" && !tx_casreservation_pilib::isAdmin($this))
			$conditions.=" and status>=2 ";	
		else*/
		if($condStatus!="5")
			$conditions.=" and tx_casreservation_reservation.status=".intval($condStatus);	

		if($this->room!=0) $conditions.=" and tx_casreservation_reservation.room=".intval($this->room); 
		$conditions.=' and room IN('.implode(',',$this->rooms).')';
		$this->allids="";

		//print_msg();
		if(isset($this->piVars['submit_change'])) { 
			$content = $this->change();
			return $this->pi_wrapInBaseClass($content);
		}
		if($this->piVars['action'] == "email") { 
			return $this->email($piFlexForm);
			//return $this->pi_wrapInBaseClass($content);
		}
		
		// Get the parts out of the template
		$template['total'] = $this->cObj->getSubpart($this->templateCode,'###TEMPLATE###');
		$template['manage'] = $this->cObj->getSubpart($template['total'], '###MANAGE###');
		
		//TODO : Add messages and print message array // $content.= tx_casreservation_pilib::print_msg();
		
		// Fill markers
		if($nb_chg > 0) 
			$markerArray['###MESSAGE###'] = "<p class=\"texte\">Il y a $nb_chg changements effectués : "
				.$this->pi_linkToPage($this->pi_getLL('send_admin_mail'), $GLOBALS['TSFE']->id, '', array($this->prefixId.'[action]' => "email"))
				.'</p>';
		else $markerArray['###MESSAGE###'] = "";
		if($this->isAdmin) $markerArray['###MESSAGE###'] .= "<p class=\"texte\">".$this->pi_linkToPage($this->pi_getLL('display_stats'), $GLOBALS['TSFE']->id, '', array($this->prefixId.'[stats]' => "on"))."</p>";

		$markerArray['###FORM_FILTER_ACTION###'] = $this->pi_getPageLink($GLOBALS['TSFE']->id, '');
		$markerArray['###FILTER_STATUS###'] = $this->generateFilter($condUser, $condStatus, $condPast, $condRoom);
		$markerArray['###DISPLAY_USER###'] = tx_casreservation_pilib::displayUser($condUser);
		$markerArray['###FORM_CHANGE_ACTION###'] = $this->pi_getPageLink($GLOBALS['TSFE']->id, '');
		$markerArray['###RECORD_TABLE###'] = $this->generateRecordTable($conditions, $this->isAdmin, $page);
		$markerArray['###COMMANDS###'] = $this->generateCommands();
		$markerArray['###SELECT_PAGE###'] = $this->generateSelectPage($page, $this->nb_records);

		
		if($condRoom != '')
			$markerArray['###DISPLAY_COSTS###'] = tx_casreservation_pilib::displayCosts($this->room);
		else
			$markerArray['###DISPLAY_COSTS###'] = $this->pi_getLL('display_fare_error');

		$markerArray['###FILTER_PAST###'] = '<input type="checkbox" name="tx_casreservation_pimanage[past]" onclick="this.form.submit();" ' . ($condPast ? 'checked="checked"' : '') . ' />' . $this->pi_getLL('filter_past_label');
		$markerArray['###FILTER_ROOM###'] = tx_casreservation_pilib::displaySelectRoom($this->rooms, $this, true);
		$markerArray['###TITLE_FILTER###'] = $this->pi_getLL('title_filter');
		$markerArray['###TITLE_FARE###']   = $this->pi_getLL('title_fare');
		
		// Create the content by replacing the content markers in the template

		$content = $this->cObj->substituteMarkerArrayCached($template['manage'],$markerArray);

		return $this->pi_wrapInBaseClass($content);
	}


//========================================================================
/// Generate the filter to filter demands
//========================================================================

	function generateFilter($condUser, $condStatus, $condPast, $condRoom){
		$conditions=" true ";
		if($condUser!="")
			$conditions.=' and member_id='.$GLOBALS['TYPO3_DB']->fullQuoteStr($condUser, 'tx_casreservation_reservation'); // FIXME use id ?
		if(!$condPast)
			$conditions.=" and date_reserv>=current_date ";
		//if($condRoom!=0)
		//	$conditions.=' and room='.$GLOBALS['TYPO3_DB']->fullQuoteStr($condRoom, 'tx_casreservation_reservation');

		// On compte les records de chaque status
		$cpt=array();
		for($i=0;$i<=4;$i++){
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('count(*)', 
				'tx_casreservation_reservation', // JOIN fe_users ON uid=member_id',
				$conditions.' and tx_casreservation_reservation.status='.intval($i).' and room IN('.implode(',',$this->rooms).')')
				or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
			list($cpt[$i]) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result);
			$GLOBALS['TYPO3_DB']->sql_free_result($result);
			if ($cpt[$i]=="")$cpt[$i]=0;
		}

		if($this->isAdmin){
			// Affiche le selectionneur d'utilisateur
			$content= '<b>'. $this->pi_getLL('user').'</b> <select name="'.$this->prefixId.'[user]" onchange="this.form.submit();">';
	
			// Fill option box with possible users
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid, username', 'tx_casreservation_reservation JOIN fe_users ON uid=member_id', 'room IN('.implode(',',$this->rooms).')', 'member_id')
				or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
	
			$content.= '<option value="" ';
			if($condUser=='')$content.= "selected=\"selected\"";
			$content.= ">" . $this->pi_getLL('all_users') ."</option>\n"; 
	
			while($row = $GLOBALS['TYPO3_DB']->sql_fetch_row($result))
			{
				list($id,$login) = $row;
				$content.= "<option value=\"$id\" ";
				if($id==$condUser) $content.=  "selected=\"selected\"";
				$content.= ">$login</option>\n"; 
			}
			$content.= '</select>';
			$GLOBALS['TYPO3_DB']->sql_free_result($result);
		}
		$content.= '<br/><br/>

<input type="radio" name="'.$this->prefixId.'[status]" onclick="this.form.submit();" value="5" '.($condStatus=="5"?'checked="checked"':'').' /><font>' . $this->pi_getLL('all_demands') .'</font><br/>
<input type="radio" name="'.$this->prefixId.'[status]" onclick="this.form.submit();" value="0" '.($condStatus=="0"?'checked="checked"':'').' /><font class="'.($cpt[0]==0 ? "grayed" : "stat0").'">' . $this->pi_getLL('demands0') .' ('.$cpt[0].')</font><br/>
<input type="radio" name="'.$this->prefixId.'[status]" onclick="this.form.submit();" value="1" '.($condStatus=="1"?'checked="checked"':'').' /><font class="'.($cpt[1]==0 ? "grayed" : "stat1").'">' . $this->pi_getLL('demands1') .' ('.$cpt[1].')</font><br/>
<input type="radio" name="'.$this->prefixId.'[status]" onclick="this.form.submit();" value="2" '.($condStatus=="2"?'checked="checked"':'').' /><font class="'.($cpt[2]==0 ? "grayed" : "stat2").'">' . $this->pi_getLL('demands2') .' ('.$cpt[2].')</font><br/>
<input type="radio" name="'.$this->prefixId.'[status]" onclick="this.form.submit();" value="3" '.($condStatus=="3"?'checked="checked"':'').' /><font class="'.($cpt[3]==0 ? "grayed" : "stat3").'">' . $this->pi_getLL('demands3') .' ('.$cpt[3].')</font><br/>
<input type="radio" name="'.$this->prefixId.'[status]" onclick="this.form.submit();" value="4" '.($condStatus=="4"?'checked="checked"':'').' /><font class="'.($cpt[4]==0 ? "grayed" : "stat4").'">' . $this->pi_getLL('demands4') .' ('.$cpt[4].')</font><br/>';

		return $content;
	}

//========================================================================
/// Generate the records table
//========================================================================

	function generateRecordTable($conditions, $editable, $page){
		$offset = ($page - 1) * $this->rowsPerPage;
		$content='';
		// first count rows
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery("count(*)",
			'tx_casreservation_reservation JOIN fe_users ON uid=member_id',
			$conditions.' and room IN('.implode(',',$this->rooms).')'
			)
			or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
		list($this->nb_records) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result);

		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery("id, room, member_id, username, DATE_FORMAT(date_demand, '%d.%m.%Y'), time_demand, date_reserv, DATE_FORMAT(date_reserv, '%d.%m.%Y'),".
			"time_reserv, tx_casreservation_reservation.status, note, material, DATE_FORMAT(date_bill, '%d.%m.%Y'),DATE_FORMAT(date_pay, '%d.%m.%Y'), paid, label",
			'tx_casreservation_reservation JOIN fe_users ON uid=member_id',
			$conditions.' and room IN('.implode(',',$this->rooms).')',
			'','date_reserv, time_reserv',  // using ORDER BY to show the most current entry first
			intval($offset).', '.intval($this->rowsPerPage)
			) // LIMIT
			or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());

		if($GLOBALS['TYPO3_DB']->sql_num_rows($result) == 0)
		{
			$content.= '<p>' . $this->pi_getLL('no_reservation_found') . '</p>';
		}
		else
		{
			if($this->isAdmin) $label_user = '<th rowspan="2">' . $this->pi_getLL('user') . '</th>';
			else $label_user='';
			$content.= '
<table border="1" cellpadding="2" cellspacing="0">
 <tr class="header"> 
  <th rowspan="2">&nbsp;<br/>&nbsp;<br/>&nbsp;</th>
  <th rowspan="2">' . $this->pi_getLL('room') . '</th>
  '.$label_user.'
  <th rowspan="2">' . $this->pi_getLL('label') . '</th>
  <th colspan="3">' . $this->pi_getLL('reservation') . '</th>
  <th colspan="2">' . $this->pi_getLL('demand') . '</th>
  <th>' . $this->pi_getLL('bill') . '</th>
  <th colspan="2">' . $this->pi_getLL('payment') . '</th>
  <th rowspan="2">' . $this->pi_getLL('note') . '</th>
 </tr>
 <tr class="header"> 
  <th>' . $this->pi_getLL('date') . '</th>
  <th>' . $this->pi_getLL('period') . '</th>
  <th>' . $this->pi_getLL('material') . '</th>
  <th>' . $this->pi_getLL('date') . '</th>
  <th>' . $this->pi_getLL('time') . '</th>
  <th>' . $this->pi_getLL('date') . '</th>
  <th>' . $this->pi_getLL('date') . '</th>
  <th>' . $this->pi_getLL('paid') . '</th>
 </tr>';

			$cc=0;
			// get all records
			while($row = $GLOBALS['TYPO3_DB']->sql_fetch_row($result))
			{
				// list() is a convenient way of assign a list of variables
				// from an array values 
				list($id, $room, $member_id, $login, /*$lastname, $firstname, $address, $postcode, $location, $tel, 
					$email,*/ $date_demand, $time_demand, $date_reserv, $date_reserv2, $time_reserv, $status, 
					$note, $material, $date_bill, $date_pay, $paid, /*$nb_periods, $price,*/ 
					$label /*, $conflict*/) = $row;

				$result2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery('count(*)',
					'tx_casreservation_reservation',
					'room='.intval($room).' and date_reserv='.intval($date_reserv).' and time_reserv='.intval($time_reserv).' and status>0 and room IN('.implode(',',$this->rooms).')'
					) 
					or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
				list($conflict)=$GLOBALS['TYPO3_DB']->sql_fetch_row($result2);
				$GLOBALS['TYPO3_DB']->sql_free_result($result2);

				if($status>=2)
				{
					// Get number of periods rented on the same day
					$result2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery('count(*)',
						'tx_casreservation_reservation ',
						'room='.intval($room).' and date_reserv='.$GLOBALS['TYPO3_DB']->fullQuoteStr($date_reserv, 'tx_casreservation_reservation').
						' AND material='.intval($material).' AND member_id='.intval($member_id).' AND status>=2 and room IN('.implode(',',$this->rooms).')',
						'room,date_reserv,member_id,material')
						or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
					list($nb_periods) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result2);
					$GLOBALS['TYPO3_DB']->sql_free_result($result2);
					// Get price given the nb of periods
					$result2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery('price/'.intval($nb_periods),
						'tx_casreservation_costs',
						'room='.intval($room).' and nb_periods='.intval($nb_periods).' AND material='.intval($material),
						'')
						or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
					list($price) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result2);
					$GLOBALS['TYPO3_DB']->sql_free_result($result2);
				}
				// Get room name
				$room_label = $this->room_names[$room];

				// change all HTML special characters,
				// to prevent some nasty code injection
				$room= tx_casreservation_pilib::explainRoom(intval($room), $room_label);
				$login= htmlspecialchars($login);
				$note= htmlspecialchars($note);
				$label= htmlspecialchars($label);

				$this->allids.= $id."-";
				if($this->isAdmin) $login2= '<td>'.$login.'</td>';
				else $login2='';
				$content.='
 <tr class="'.($conflict>1 ? "conflict" :"line".($cc%2)).'"> 
  <td class="statb'.$status.'">'.'<input type="checkbox" name="'.$this->prefixId.'[cb-'.$id.']"/>'.' </td>
  <td>'.$room.'</td>
  '.$login2.'
  <td>'.$label.'</td>
  <td>'.$date_reserv2.'</td>
  <td>'.tx_casreservation_pilib::explainTime($time_reserv).'</td>
  <td>'.tx_casreservation_pilib::explainBoolean($material).'</td>
  <td>'.$date_demand.'</td>
  <td>'.$time_demand.'</td>
  <td>'.tx_casreservation_pilib::explainDate($date_bill,'bill',$id,$status, $editable, $this).'</td>
  <td>'.tx_casreservation_pilib::explainDate($date_pay,'pay',$id,$status, $editable, $this).'</td>
  <td>'.tx_casreservation_pilib::explainPaid($paid, $price, $id, $status, $editable, $this).'</td>
  <td>'.tx_casreservation_pilib::explainNote($note).'</td>
  &nbsp;<input type="hidden" name="'.$this->prefixId.'[price-'.$id.']" value="'.$price.'"/>
 </tr>';
				$cc++;
			} // end while
			$content.='
</table>
<br/>';
			$GLOBALS['TYPO3_DB']->sql_free_result($result);
		}
		return $content;
	}

//========================================================================
/// Generate commands
//========================================================================

	function generateCommands(){

		$content = '<table>';

		if($this->isAdmin){
			$content.= '<tr><td> <input type="radio" name="'.$this->prefixId.'[change]" value="confirm" checked="checked" />' . $this->pi_getLL('action_accept') . '</td></tr>'."\n";
			$content.= '<tr><td> <input type="radio" name="'.$this->prefixId.'[change]" value="cancel"/>' . $this->pi_getLL('action_cancel') . '</td></tr>'."\n";
			$content.= '<tr><td> <input type="radio" name="'.$this->prefixId.'[change]" value="bill"/>'   . $this->pi_getLL('action_bill') . '</td></tr>'."\n";
			$content.= '<tr><td> <input type="radio" name="'.$this->prefixId.'[change]" value="pay"/>'    . $this->pi_getLL('action_pay') . '</td></tr>'."\n";
		}else{
			$content.= '<input type="hidden" name="'.$this->prefixId.'[change]" value="cancel" checked="checked" />'."\n";
		}

		$content.='<tr><td>&nbsp;</td></tr><tr><td>';

		if($this->isAdmin) 
			$content.= '<input name="'.$this->prefixId.'[submit_change]" type="submit" value="' . $this->pi_getLL('submit_change_admin') . '" onclick="return checkDates();"/>';
		else 
			$content.= '<input name="'.$this->prefixId.'[submit_change]" type="submit" value="' . $this->pi_getLL('submit_change_user') . '" onclick="return checkDates();"/>';
		
		$content.= '</td></tr><tr><td>&nbsp;</td></tr>
</table>
<input type="hidden" name="'.$this->prefixId.'[ids]" value="'.$this->allids.'"/>'."\n";
		// TODO : Maybe use a cleaner ways than hidden ids
		return $content;

	}
//========================================================================
/// Apply changes
//========================================================================

	function change(){
		$content = '';
		$change = htmlentities($this->piVars['change']);
		$ids = htmlentities($this->piVars['ids']);
		if(isset($change)){
			$arr= explode ("-", $ids);

			//Demande Acceptee
			if($this->isAdmin &&  $change=="confirm"){
				$cpt=0;

				for ($i=0; $i<count($arr); $i++)
				if($this->piVars["cb-".$arr[$i]]){
					// On verifie les eventuels conflits
					//$query= "SELECT status, (SELECT count(*) FROM tx_casreservation_reservation as t2 WHERE (t1.date_reserv=t2.date_reserv and t1.time_reserv=t2.time_reserv and status>0)) as conflict from tx_casreservation_reservation as t1".
					//	" WHERE id='$arr[$i]' ";
					$result  = $GLOBALS['TYPO3_DB']->exec_SELECTquery('status, (SELECT count(*) FROM tx_casreservation_reservation as t2 WHERE t1.date_reserv=t2.date_reserv and t1.time_reserv=t2.time_reserv and t1.room=t2.room and status>0) as conflict',
						'tx_casreservation_reservation as t1',
						'id='.$arr[$i], '')
						or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
					list($status,$confl) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result);
					$GLOBALS['TYPO3_DB']->sql_free_result($result);

					if(($status==0 && $confl==0) || ($status==1 && $confl==1)){
					
						// Get member id and status of changed reservation
						$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('member_id, status','tx_casreservation_reservation', "id='".$arr[$i]."'")
							or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
						list($member_id, $status1) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result);
						$GLOBALS['TYPO3_DB']->sql_free_result($result);

						// Ajout d'un email a envoyer
						$insertArray = array(
							"reservation_id" => $arr[$i],
							"status1" => $status1,
							"status2" => '2'
							);
						$GLOBALS['TYPO3_DB']->exec_INSERTquery("tx_casreservation_email", $insertArray) 
							or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
						
						$updateArray = array('status' => '2');
						$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_casreservation_reservation', 'id='.$arr[$i], $updateArray);

						$cpt++;
					}else{
						$msg_arr[] = $this->pi_getLL('error_conflict');
						$msgflag = true;
					}
				}
				$msg_arr[] = $cpt . $this->pi_getLL('accepted_demands');
				$msgflag = true;
			}
			else if($change=="cancel"){
				//Demande Annullee
				$cpt=0;
				for ($i=0;$i<count($arr);$i++)
				if($this->piVars["cb-".$arr[$i]]){
					$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('status',
						'tx_casreservation_reservation', 
						'id='.$arr[$i],'')
						or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
					list($status) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result);
					$GLOBALS['TYPO3_DB']->sql_free_result($result);

					if($this->isAdmin){
						// Get member id and status of changed reservation
						$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('member_id, status','tx_casreservation_reservation', "id='".$arr[$i]."'")
							or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
						list($member_id, $status1) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result);
						$GLOBALS['TYPO3_DB']->sql_free_result($result);
						// Add email to send
						$insertArray = array(
							"reservation_id" => $arr[$i],
							"status1" => $status1,
							"status2" => '0'
							);
						$GLOBALS['TYPO3_DB']->exec_INSERTquery("tx_casreservation_email", $insertArray) 
							or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
					}
					if($this->isAdmin||$status==1){
			
						// marquer comme annule
						$updateArray = array('status' => '0');
						$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_casreservation_reservation', 'id='.$arr[$i], $updateArray);
						$cpt++;
					}
					else{
						$msg_arr[] = $this->pi_getLL('error_status');
						$msgflag = true;
					}
				}
				$msg_arr[] = $cpt . $this->pi_getLL('cancelled_demands');
				$msgflag = true;
			}
			else if($this->isAdmin && $change=="bill"){
				//Demande facturee
				$cpt=0;
				for ($i=0; $i<count($arr); $i++)
				if($this->piVars["cb-".$arr[$i]]){
					// On recupere les valeurs
					$date_bill = $this->piVars['yy-bill-'.$arr[$i]].'-'.$this->piVars['mm-bill-'.$arr[$i]].'-'.$this->piVars['dd-bill-'.$arr[$i]];
					$price = $this->piVars['price-'.$arr[$i]];
					$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('status',
						'tx_casreservation_reservation as t1',
						'id='.$arr[$i],'')
						or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
					list($status) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result);
					$GLOBALS['TYPO3_DB']->sql_free_result($result);

					if($status==2||$status==3){
						// Get member id and status of changed reservation
						$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('member_id, status','tx_casreservation_reservation', "id='".$arr[$i]."'")
							or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
						list($member_id, $status1) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result);
						$GLOBALS['TYPO3_DB']->sql_free_result($result);

						// Ajout d'un email a envoyer
						$insertArray = array(
							"reservation_id" => $arr[$i],
							"status1" => $status1,
							"status2" => '3',
							"price" => $price
						);
						$GLOBALS['TYPO3_DB']->exec_INSERTquery("tx_casreservation_email", $insertArray) 
							or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());

						if($status==2){
							$updateArray = array('status' => '3', 'date_bill' => $date_bill);
							$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_casreservation_reservation', 'id='.$arr[$i], $updateArray);
						}
						$cpt++;
					}
					else{
						$msg_arr[] = $this->pi_getLL('error_conflict');
						$msgflag = true;
					}
				}
				$msg_arr[] = $cpt . $this->pi_getLL('billed_demands');
				$msgflag = true;
			}
			else if($this->isAdmin && $change=="pay"){
				//Demande payee
				$cpt=0;
				for ($i=0;$i<count($arr);$i++)
				if($this->piVars["cb-".$arr[$i]]){
					// On recupere la date
					$date_pay = htmlspecialchars($this->piVars['yy-pay-'.$arr[$i]].'-'.$this->piVars['mm-pay-'.$arr[$i]].'-'.$this->piVars['dd-pay-'.$arr[$i]]);
					$paid = $this->piVars['paid-'.$arr[$i]];
					// On verifie les eventuels conflits
					//$query = "SELECT status, (SELECT count(*) FROM tx_casreservation_reservation as t2 WHERE (t1.date_reserv=t2.date_reserv and t1.time_reserv=t2.time_reserv and status>0)) as conflict from tx_casreservation_reservation as t1".
					//	" WHERE id='$arr[$i]' ";

					$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('status',
						'tx_casreservation_reservation',
						'id='.$arr[$i],'') 
						or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
					list($status) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result);
					$GLOBALS['TYPO3_DB']->sql_free_result($result);

					if($status==3){
						// Get member id and status of changed reservation
						$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('member_id, status','tx_casreservation_reservation', "id='".$arr[$i]."'")
							or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
						list($member_id, $status1) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result);
						$GLOBALS['TYPO3_DB']->sql_free_result($result);

						// Ajout d'un email a envoyer
						$insertArray = array(
							"reservation_id" => $arr[$i],
							"status1" => $status1,
							"status2" => '4'
						);
						$GLOBALS['TYPO3_DB']->exec_INSERTquery("tx_casreservation_email", $insertArray) 
							or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());

						// marquer comme paye
						$updateArray = array('status' => '4', 
								'date_pay' => $date_pay,
								'paid' => $paid);
						$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_casreservation_reservation', 'id='.$arr[$i], $updateArray);
						$cpt++;
					}else{
						$msg_arr[] = $this->pi_getLL('error_paid');
						$msgflag = true;
					}
				}
				$msg_arr[] = $cpt . $this->pi_getLL('paid_demands');
				$msgflag = true;
			}
		}

		//If there are input validations, redirect back to the registration form
		if($msgflag) {
			$_SESSION['MSG_ARR'] = $msg_arr;
		}
		$content.= tx_casreservation_pilib::print_msg();
		// Retour a la derniere page
		//$content.= '<script type="text/javascript">document.location(history.go(-1))</script>';
		$content.= '<p>'.$this->pi_linkToPage('Retour', $GLOBALS['TSFE']->id, '', array($this->prefixId.'[week]' => $week, $this->prefixId.'[room]' => $room)).'</p>';
		
		return $content;
	}

//========================================================================
// entete de la facture
//========================================================================
	function head_bill($text,$name,$address,$npa,$location,$tel,$email)
	{
		//$myFile = t3lib_extMgm::extPath('cas_reservation').'pimanage/head_bill_room'.$this->rooms[0].'.txt';
		//$fh = fopen($myFile, 'r');
		//$text =  fread($fh, 1000);
		//fclose($fh);
		if($text == '') return "Error : cannot read bill header";

		$text = str_replace("###NAME###", $name, $text);
		$text = str_replace("###ADDRESS###", $address, $text);
		$text = str_replace("###CODE###", $npa, $text);
		$text = str_replace("###LOCATION###", $location, $text);

		return '<p style="page-break-before: always;">'."\n".$text; // Add page break
	}
//========================================================================
// Ligne de la facture
//========================================================================
	function line_bill($text,$room_label, $date_reserv,$time_reserv,$label,$material,$price)
	{
		//$myFile = t3lib_extMgm::extPath('cas_reservation').'pimanage/line_bill_room'.$this->rooms[0].'.txt';
		//$fh = fopen($myFile, 'r');
		//$text = fread($fh, 1000);
		//fclose($fh);
		if($text == '') return "Error : cannot read file bill line";

		if($material)$strmaterial='avec'; else $strmaterial='sans';


		$text = str_replace("###LABEL###", $label, $text);
		$text = str_replace("###DATE###", $date_reserv, $text);
		$text = str_replace("###TIME###", tx_casreservation_pilib::explaintime($time_reserv), $text);
		$text = str_replace("###ROOM_LABEL###", $room_label, $text);
		$text = str_replace("###MATERIAL###", $strmaterail, $text);

		return $text;
	}
//========================================================================
// Pied de la facture
//========================================================================
	function foot_bill($text,$total,$date_reserv)
	{
		//$myFile = t3lib_extMgm::extPath('cas_reservation').'pimanage/foot_bill_room'.$this->rooms[0].'.txt';
		//$fh = fopen($myFile, 'r');
		//$text = fread($fh, 1000);
		//fclose($fh);
		if($text == '') return "Error : cannot read file bill footer";

		$text = str_replace("###TOTAL###", $total, $text);
		$text = str_replace("###DATE###", $date_reserv, $text);

		return $text;
	}
//========================================================================
// entete du mail
//========================================================================
	function head_mail($text)
	{
		// Read data from text file
		//$myFile = t3lib_extMgm::extPath('cas_reservation').'pimanage/head_mail_room'.$this->rooms[0].'.txt';
		//$fh = fopen($myFile, 'r');
		//$text = fread($fh, 1000);
		//fclose($fh);
		if($text == '') return "Error : cannot read e-mail header";
		return '<html>'.$text;
	}
//========================================================================
// Ligne du mail
//========================================================================
	function line_mail($text, $room, $room_label, $date_reserv, $time_reserv, $label, $material, $status)
	{
		// Read data from text file
		//$myFile = t3lib_extMgm::extPath('cas_reservation').'pimanage/line_mail_room'.$this->rooms[0].'.txt';
		//$fh = fopen($myFile, 'r');
		//$text = fread($fh, 1000);
		//fclose($fh);
		
		if($status==2){
			//Recuperation du code de la semaine
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('code',
				'tx_casreservation_codes',
				'no_week='.$GLOBALS['TYPO3_DB']->fullQuoteStr(tx_casreservation_pilib::getWeekNo($date_reserv),'tx_casreservation_codes')." and ".
				'room='.$GLOBALS['TYPO3_DB']->fullQuoteStr($room,'tx_casreservation_codes'), '') 
				or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_row($result);
			list($code) = $row;
			$GLOBALS['TYPO3_DB']->sql_free_result($result);

			if($code=='')$code='';//"<b>(Code non disponible, veuillez contacter le responsable.)</b><br/>\n";
			else $code= '<p>' . $this->pi_getLL('code_is') . $code . '</p>'. "\n";
		}
		else $code='';
		
		if($status==3)$strstatus=tx_casreservation_pilib::explainStatus($status)."*";
		else $strstatus=tx_casreservation_pilib::explainStatus($status);

		if($material) $strmaterial='avec matériel'; else $strmaterial='';

		$text = str_replace("###DATE###", $date_reserv, $text);
		$text = str_replace("###TIME###", tx_casreservation_pilib::explaintime($time_reserv), $text);
		$text = str_replace("###ROOM_LABEL###", $room_label, $text);
		$text = str_replace("###MATERIAL###", $strmaterail, $text);
		$text = str_replace("###STATUS###", $strstatus, $text);
		$text = str_replace("###CODE###", $code, $text);

		if($text == '') return "Error : cannot read file email line";
		
		return $text; 
	}
//========================================================================
// Pied du mail


//========================================================================
	function foot_mail($text)
	{
		// Read data from text file
		//$myFile = t3lib_extMgm::extPath('cas_reservation').'pimanage/foot_mail_room'.$this->rooms[0].'.txt';
		//$fh = fopen($myFile, 'r');
		//$text = fread($fh, 1000);
		//fclose($fh);
		if($text == '') return "Error : cannot read mail footer";
		return $text.'</html>';
	}
//========================================================================
/// Send an e-mail
//========================================================================

	function sendEmail($dest, $message){
		$content='';
		if($message!=""){
			//if( !file_exists ( t3lib_extMgm::extPath('cas_reservation').'send_no_email' ) && 
			//		$this->cObj->sendNotifyEmail('CAS Réservations'.chr(10).$this->head_mail().$message.$this->foot_mail(), $dest, '', 'no_reply@cas-moleson.ch', 'CAS réservations', '') )
			if( $this->send_email)
			{
				$mailBody = $message;
				$mailer = t3lib_div::makeInstance('t3lib_htmlmail');
				$mailer->start();
				$mailer->from_email = $this->pi_getLL('mail_sender_email');
				$mailer->from_name  = $this->pi_getLL('mail_sender_name');
				//$mailer->replyto_email = 'no_reply@cas-moleson.ch';
				//$mailer->replyto_name = ;
				$mailer->subject    = $this->pi_getLL('mail_subject');
				$mailer->setPlain($mailer->encodeMsg(strip_tags($mailBody)));
				$mailer->setHtml($mailer->encodeMsg($mailBody));
				$mailer->setRecipient($dest);
				$mailer->setHeaders();
				$mailer->setContent();
				$success = $mailer->SendTheMail();

				$content.= $this->pi_getLL('mail_sent_to') . $dest . "<br/>";
			}
			else
				$content.= $this->pi_getLL('mail_not_sent') . $dest . "<br/>";
			$content.= "\"*" . $this->pi_getLL('mail_subject') . "*\" <br/>#<br/> ".$message."<br/>#<br/> \"From: no_reply@cas-moleson.ch\" <br/>";
		}
		return $content;
	}

//========================================================================
/// Send all mails related to changed reservations (admin only)
//========================================================================

// Ici, on envoie les e-mails en attente

	function email($piFlexForm){
		if(!$this->isAdmin) return "";
		$content = '';
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery("member_id, name, label, address, zip, city, telephone, email, reservation_id, room, DATE_FORMAT(date_reserv, '%d.%m.%Y'), time_reserv, room_name, material, status1, status2, price",
			'tx_casreservation_email '.
			' JOIN tx_casreservation_reservation ON tx_casreservation_reservation.id=reservation_id '.
			' JOIN fe_users ON member_id=fe_users.uid'.
			' JOIN tx_casreservation_room ON tx_casreservation_room.id=room',
			' room IN('.implode(',',$this->rooms).')','')
			or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
		$bill='';
		$bill_line='';
		$message='';
		$total=0;
		$cpt_bill=0;
		$oldstatus= '';

		$head_bill = $this->pi_getFFvalue($piFlexForm, "head_bill", "bill");
		$line_bill = $this->pi_getFFvalue($piFlexForm, "line_bill", "bill");
		$foot_bill = $this->pi_getFFvalue($piFlexForm, "foot_bill", "bill");
		$head_email = $this->pi_getFFvalue($piFlexForm, "head_email", "email");
		$line_email = $this->pi_getFFvalue($piFlexForm, "line_email", "email");
		$foot_email = $this->pi_getFFvalue($piFlexForm, "foot_email", "email");

		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_row($result)){
			list($id, $name, $label, $address, $npa, $location, $tel ,$email, $id_reserv, $room, $date_reserv, $time_reserv, $room_name, $material, $statusA, $statusB,$price) = $row;
			
			if($oldstatus=="")$oldstatus=$statusA;

			// Une seule ligne par reservation
			if($id_reserv2!=""){
				if($id_reserv2!=$id_reserv){
					if($oldstatus!=$statusB2)
						$message.= $this->line_mail($line_email,$room2, $room_name2, $date_reserv2, $time_reserv2, $label2, $material2, $statusB2);
					$oldstatus=$statusA;
				}
				// Creation de facture
				if($statusB2==3){
					$bill_line.=$this->line_bill($line_bill,$room_name2, $date_reserv2, $time_reserv2, $label2, $material2, $price2);
					$total+=$price2;
				}
				// Envoyer un seul email par utilisateur
					if($id!=$id2){
						$content .= $this->sendEmail($email2,$this->head_mail($head_email).$message.$this->foot_mail($foot_email));
					$message='';
				// On ajoute une facture
					if($bill_line!=''){
						$bill.=$this->head_bill($head_bill,$name2,$address2,$npa2,$location2,$tel2,$email2).$bill_line.$this->foot_bill($foot_bill,$total, $date_reserv2);
						$cpt_bill++;
						$bill_line='';
						$total=0;
					}
				}
			}
			// Save old values
			$id2=$id;
			$name2=$name;
			$address2=$address;
			$npa2=$npa;
			$location2=$location;
			$email2=$email;
			$tel2=$tel;
			$label2=$label;
			$email2=$email;
			$id_reserv2=$id_reserv;
			$room2=$room;
			$date_reserv2=$date_reserv;
			$time_reserv2=$time_reserv;
			$room_name2=$room_name;
			$material2=$material;
			$statusA2=$statusA;
			$statusB2=$statusB;
			$price2=$price;
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($result);

		// On envoie le dernier mail !!!!
		if($id_reserv2!=""){
			if($oldstatus!=$statusB2)
				$message.= $this->line_mail($line_email,$room2, $room_name2, $date_reserv2,$time_reserv2,$label2,$material2,$statusB2);
				$oldstatus=$statusA;
			$content .= $this->sendEmail($email2, $this->head_mail($head_email).$message.$this->foot_mail($foot_email));

			// Creation de facture
			if($statusB2==3){
				$bill_line.=$this->line_bill($line_bill,$room_name2, $date_reserv2,$time_reserv2,$label2,$material2,$price2);
				$total+=$price2;
			}

			// On envoie une facture
			if($bill_line!=''){
				$bill.= $this->head_bill($head_bill,$name2,$address2,$npa2,$location2,$tel2,$email2).$bill_line.$this->foot_bill($foot_bill,$total,$date_reserv2);
				$cpt_bill++;
			}
		}
		// On vide la table
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('tx_casreservation_email.id',
			'tx_casreservation_email JOIN tx_casreservation_reservation ON tx_casreservation_reservation.id=reservation_id',
			'room IN('.implode(',',$this->rooms).')')
			or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_row($result)){
			list($id)=$row;
			$GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_casreservation_email', 
				'id='.$id)
				or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($result);
		$content.= "Il y a $cpt_bill factures &agrave; imprimer.";
		$content.= '<p>'.$this->pi_linkToPage('Retour', $GLOBALS['TSFE']->id, '').'</p>';		

		$content.= $bill.'<p style="page-break-before: always;">';

		return $content;
	}
//========================================================================
/// Show statistics (admin only)
//========================================================================

	function showStatistiques()
	{
		if($GLOBALS['TYPO3_LOADED_EXT']['rt_jpgraphlib'] == '')return 'Error: Please install the jpgraph extension';

		//$GLOBALS['TSFE']->additionalHeaderData[] = '<script type="text/javascript" src="typo3conf/ext/cas_reservation/cas_reservation.js"></script>';
		if(!$this->isAdmin) return "Error : This page is for admins only !!";
		
		$plot_data="";

		// Valeurs de filtre
		if(isset($this->piVars['status']))
			$condStatus = intval($this->piVars['status']);
		else $condStatus="5";

		$detail = $this->piVars['detail']=="on";
		
		if(isset($this->piVars['start']))
			$start = intval($this->piVars['start']);
		else $start=(date("Y")-1);//."-08";
		$end=$start+1;


		// Set filter conditions
		$conditions=" true ";

		// On compte les records de chaque status
		$cpt=array();
		$conditions.=" and status>0";

		$allids="";
		
		// Get the parts out of the template
		$template['total'] = $this->cObj->getSubpart($this->templateCode,'###TEMPLATE###');
		$template['stats'] = $this->cObj->getSubpart($template['total'], '###STATS###');
		
		$markerArray['###FORM_ACTION###'] = $this->pi_getPageLink($GLOBALS['TSFE']->id, '');
		$markerArray['###START###'] = $start;
		$markerArray['###END###'] = $end;
		$markerArray['###DISPLAY_USER###'] = ""; // tx_casreservation_pilib::displayUser($condUser);
		$markerArray['###DETAIL_CHECKED###'] = $detail ? 'checked="checked"' : '';

		//$markerArray['###FORM_CHANGE_ACTION###'] = $this->pi_getPageLink($GLOBALS['TSFE']->id, '');
		$markerArray['###SHOW_STATS###'] = $this->generateStats($start, $conditions, $detail);
		//$markerArray['###PLOT_PHP###'] = /*t3lib_extMgm::extPath('cas_reservation').'pimanage/*/'plot.php';
		$markerArray['###PLOTS###'] = $this->generatePlots($start);

		// Create the content by replacing the content markers in the template
		$content = $this->cObj->substituteMarkerArrayCached($template['stats'],$markerArray);
		return $this->pi_wrapInBaseClass($content);
	}

//========================================================================
/// Generate statistics (admin only)
//========================================================================

	function generateStats($start, $conditions, $detail)
	{
		$this->jpgraph_pathlib = $GLOBALS['TYPO3_LOADED_EXT']['rt_jpgraphlib']['siteRelPath'].'jpgraph/';
		$this->jpgraph_pathsampler = $GLOBALS['TYPO3_LOADED_EXT']['cas_reservation']['siteRelPath'].'pimanage/';

		include_once ($this->jpgraph_pathlib . "jpgraph.php");
		include_once ($this->jpgraph_pathlib . "jpgraph_bar.php");

		$month="8";//substr($start,6,2);
		$monthend="8";//(substr($end,6,2)%12)+1;
		$year=substr($start,0,4);
		$yearend=$year+1;//substr($end,0,4);
		$iii = "";
		$plot_data = "";
		$content = "";

		while($month!=$monthend||$year!=$yearend)
		{
			if($month<10)$month="0$month";
			$content.= "<p style=\"page-break-before: always;\"/>";
			$content.= "<h2>$year ".tx_casreservation_pilib::explainMonth($month)."</h2>\n";

			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery("count(*)as nb, sum(paid)as sum_paid ",
				'tx_casreservation_reservation',"date_reserv like '$year-$month-%' and ".$conditions, '','') 
			or die('Error, query failed. line '.__LINE__ ." ".$GLOBALS['TYPO3_DB']->sql_error());

			if($row = $GLOBALS['TYPO3_DB']->sql_fetch_row($result))
				list($nb,$sum_paid) = $row;
			$content.= '<p><b>Nombre de réservations :</b> '.$nb."</p>\n";
			$content.= "<p><b>Total encaissé :</b> ".tx_casreservation_pilib::formatFranc($sum_paid)."<br/></p>\n";

			//Add to plot string
			if ($iii=="")$iii="0";
			$plot_data.="$iii ".tx_casreservation_pilib::explainMonth($month)." $nb\n";
			$iii+=1;

			if($detail)
			{
				// Show detail
				$conditions2= $conditions." and date_reserv like '$year-$month-%'";
				$content .= $this->generateRecordTable($conditions2, false);
			}
			$month=$month+1;
			if($month==13){$month=1;$year=$year+1;}
		}
		return $content;
	}

	/**
	 * Function that run into a folder to retrieve files with $filter named extension
	 *
	 * @param	string $filter: The extension file to retrieve
 	 * @param	string $folder: The folder to run into
	 * @return	The content that is displayed on the website
	 */
	function getFiles($filter, $folder) {
		$counter=0;
		$Fichiers=array();
		$handle=opendir($folder);
		while(false!==($file = readdir($handle))) {
			if($file!= "." && $file != "..") {
				if(is_file($folder.$file) && preg_match("/\.(".$filter.")$/", strtolower($file), $ext)) {
					$Fichiers[$counter]['file'] = $file;
					$Fichiers[$counter]['name'] = substr($file, 0, strlen($file)-4);
					$Fichiers[$counter]['extension'] = $ext[1];
					$counter++;
					clearstatcache();
				}
			}
		}
		closedir($handle);
		sort($Fichiers);
		return $Fichiers;
	}


//========================================================================
/// Generate plots (admin only)
//========================================================================
	function generatePlots($start)
	{
		if(!is_dir(CACHE_DIR . 'cas_reservation/')) mkdir(CACHE_DIR . 'cas_reservation/');
		$content = "";
		$time_now = time();
		//Search plot...php files
		$samples = $this->getFiles("php", $this->jpgraph_pathsampler."plots/");
		$nbSamples = count($samples);
		
		// Empty the plot dir once in a while
		if($time_now % 10 == 0)
		{
			$mask = CACHE_DIR . 'cas_reservation/*.png';
			array_map( "unlink", glob( $mask ) );
		}

		foreach($samples as $sample)
		{
			$file = CACHE_DIR . 'cas_reservation/' . $sample['name'] . "_" . $time_now . '.png';
			include_once ($this->jpgraph_pathsampler . "plots/" . $sample['file']);
			$content .= '<h3>'.$sample['name'].'</h3>';
			$content .= '<p><img border=0 src="'. $file . '" alt="Error while plotting with jpgraph."/></p>'."\n"; // TODO : store image in cache !
		}
		return $content;
	}

//========================================================================
/// Generate the page selection
//========================================================================

	function generateSelectPage($page, $nbRecords)
	{
		$pageMax = ceil($nbRecords / $this->rowsPerPage);
		if($nbRecords < $this->rowsPerPage) return "";

		$content .= $this->pi_getLL('page').'&nbsp;';
		$content.='<select name="'.$this->prefixId.'[page]" onchange="this.form.submit();">';

		for($i = 1 ; $i <= $pageMax ; $i++)
		{
			$content.="<option value=\"$i\"";
			if($i==$page) $content.="selected=\"selected\"";
			$content.=">".$i."</option>\n"; 
		} // end while
		$content.='</select>';
		return $content;
	}



} // end of class

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cas_reservation/pimanage/class.tx_casreservation_pimanage.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cas_reservation/pimanage/class.tx_casreservation_pimanage.php']);
}

?>
