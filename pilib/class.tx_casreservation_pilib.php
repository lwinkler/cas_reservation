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


/**
 * Plugin 'Static library Class' for the 'cas_reservation' extension.
 *
 * @author	Laurent Winkler <laurent.winkler@bluewin.ch>
 * @package	TYPO3
 * @subpackage	tx_casreservation
 */
class tx_casreservation_pilib extends tslib_pibase {
	var $prefixId      = 'tx_casreservation_pilib';		// Same as class name
	var $scriptRelPath = 'pilib/class.tx_casreservation_pilib.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'cas_reservation';	// The extension key.
	var $pi_checkCHash = true;
	
//========================================================================
// Imprime l'aide
//========================================================================
/*function print_help($help_file_admin,$help_file)
{
	if(isset($_GET['help'])){
		if(isAdmin($this)) include('help/'.$help_file_admin);
		include('help/'.$help_file);
	}else{echo "<p><a class=\"link\" href=\"".add_url($_SERVER['REQUEST_URI'],'help=on')."\">Aide</a></p>\n ";}
}*/
//========================================================================
// Imprime les messages et messages d'erreur
//========================================================================
function print_msg()
{
	$content='';
	if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
		$content.= '<ul class="err">';
		foreach($_SESSION['ERRMSG_ARR'] as $msg) {
			$content.= '<p>'.$msg.'</p>';//'<li>'.$msg.'</li>'; 
		}
		$content.= '</ul>';
		unset($_SESSION['ERRMSG_ARR']);
	}

	if( isset($_SESSION['MSG_ARR']) && is_array($_SESSION['MSG_ARR']) && count($_SESSION['MSG_ARR']) >0 ) {
		$content.= '<ul class="msg">';
		foreach($_SESSION['MSG_ARR'] as $msg) {
			$content.= '<p>'.$msg.'</p>';//'<li>'.$msg.'</li>'; 
		}
		$content.= '</ul>';
		unset($_SESSION['MSG_ARR']);
	}
	return $content;
}
//========================================================================
// Verif si on est admin
//========================================================================
static function isAdmin($plugin)
{
	if($plugin->admin == '') return false;
	foreach(explode(',', $GLOBALS['TSFE']->fe_user->user['usergroup']) as $group)
		if($group==$plugin->admin) return true;
	return false;
}

/*static function unsec($str){
	return str_replace(' ','&nbsp;',$str);
}*/

/*static function explainConflict($str)
{
	if($str<=1)
		return "-";
	else
		return $str . "&nbsp; réservations pour cette date";
}*/

static function explainRoom($no, $str)
{
	if($no>0)
		return '<img src="typo3conf/ext/cas_reservation/images/room'.$no.'.png" width="20" title="'.$str.'"/>';
}

static function explainDate($str,$label,$no,$status,$editable,$plugin)
{
	if($editable&&($label=="bill"&&$status==2||$label=="pay"&&$status==3)){
		return "<script language=\"javascript\" type=\"text/javascript\" >creerselect('$label','$no','".$plugin->prefixId."');</script>";
	}else{
		if($str=='' || $str=='00.00.0000')return "-";
		else return $str;
	}
}

static function explainPaid($str, $default, $no, $status, $editable, $plugin)
{
	if($editable&&$status==3)
		return '
<input name="'.$plugin->prefixId.'[paid-'.$no.']" type="text" size="4" maxlength="8" value="'.sprintf("%0.2f",$default).'"/>
';
	else return tx_casreservation_pilib::formatFranc($str);
}

static function explainBoolean($str)
{
	if($str=="1")
		return $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:yes');
	else if($str=="0")
		return $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:no');
	else
		return "(Valeur inconnue '$str')";
}

static function explainStatus($str)
{
<<<<<<< HEAD
	return $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:status'.intval($str));
=======
	return $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:status'.floor($str));
>>>>>>> 710f1db5513aa688f90b42236da603b77154068b
}
/*
static function explainOccupation($str)
{
	if($str=="0")
		return "Libre";
	else if($str=="1")
		return "Pré-réservé";
	else if($str>"1")
		return "Occupé";
	else if($str=="")
		return "(erreur, date inexistante)";
	else
		return "(erreur, valeur inconnue)";
}*/

static function explainWeekday($str)
{
	return $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:weekday_short'.$str);
}

static function explainTime($t1)
{
	$t2 = $t1 + 2; // TODO : this should be customizable
	$str = '';
	$str = sprintf($GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:format_times'), $t1, $t2);
	return $str;
}

static function explainMonth($str)
{
	return $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:month_short'.$str);
}

static function formatFranc($str){
return $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:currency') . '&nbsp;'.str_replace('.00','.-',sprintf("%0.2f",$str));
}

static function formatEmail($email){
	$arr= explode ("@", $email);
	return "<script type=\"text/javascript\">safemail(\"$arr[0]\", \"$arr[1]\")</script>";
}

static function explainNote($note){
if($note=='') return '&nbsp;';
return '<img src="typo3conf/ext/cas_reservation/images/helpbubble.gif" title="'.$note.'"/>';
}

static function getDate()
{
	$month = date("n");
	if($month < 10) $month = "0".$month;
	return sprintf($GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:format_date'),
		date("Y"),
		$GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:month' . $month), 
		date("d"), 
		$GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:weekday' . date("w")));
}
//========================================================================
// Affiche les details de l'utilisateur
//========================================================================
static function displayUser($uid){
$content='';

if($uid!=''){
	$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('username, name, address, zip, city, telephone, email',
		'fe_users',
		'uid='.$GLOBALS['TYPO3_DB']->fullQuoteStr($uid, 'fe_users'), '') 
		or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
	$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
	// if the guestbook is empty show a message
	if($GLOBALS['TYPO3_DB']->sql_num_rows($result) == 0)
	{
		$content.= "<p><br /><br />Liste vide </p>";
	}
	else{
		$content.=
'<table> 
<tr>
 <td><b>' . $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:login').'</b></td><td>'.$row['username'].'</td>
</tr>
<tr>
 <td><b>' . $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:name').'</b></td><td>'.$row['name'].'</td>
</tr>
<tr>
 <td><b>' . $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:address').'</b></td><td>'.$row['address'].'</td>
</tr>
<tr>
 <td><b>&nbsp;</b></td><td>'.$row['zip'].' '.$row['city'].'</td>
</tr>
<tr>
 <td><b>' . $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:phone').'</b></td><td>'.$row['telephone'].'</td>
</tr>
<tr>
 <td><b>' . $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:e-mail').'</b></td><td>'.tx_casreservation_pilib::formatEmail($row['email']).'</td>
</tr>
</table>';
	}
	$GLOBALS['TYPO3_DB']->sql_free_result($result);
}
return $content;
}

//========================================================================
//	Lundi de cette semaine
//========================================================================
static function getMonday()
{
if(date("l") == "Monday")
return date("Y-m-d"); // if today if monday, just give today's date
else
return date("Y-m-d", strtotime("last monday")); 
}
//========================================================================
//	No de la semaine dans l'annee
//========================================================================
static function getWeekNo($date1)
{
	return date("W", strtotime($date1));
}
//========================================================================
//	Afficher la grille d'occupation de la semaine
//========================================================================
static function displayGrid($room, $dateLundi, $booking, $delaymin, $delaymax, $plugin)
{
	$content="";
	// prepare the query string
	$date_end = date("Y-m-d", strtotime("+7 day",strtotime($dateLundi)));
	$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('room_grid, weekday_grid, time_grid, type_grid, label_grid', 
		' tx_casreservation_grid ',
		' room_grid='.$GLOBALS['TYPO3_DB']->fullQuoteStr($room, 'tx_casreservation_grid'), // WHERE
		'', // GROUP BY
		'time_grid,weekday_grid', // ORDER BY
		"")or die('Error, query failed. line '.__LINE__ ." ".$GLOBALS['TYPO3_DB']->sql_error()); // LIMIT 
	if($GLOBALS['TYPO3_DB']->sql_num_rows($result) == 0||date("D", strtotime($dateLundi) )!="Mon")
	{
		$content.='<p><br />
		<br />' . $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:no_reservation_found') . '</p>';
	}
	else
	{
		$format_date_php = $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:format_date_php');
		$content.='<div id="week_view">
	<table cellpadding="0" cellspacing="0" align="center">
	<tr>
	<td>&nbsp;</td>
	<td>'.$GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:weekday1').'<br />'.date($format_date_php,strtotime($dateLundi)).'</td>
	<td>'.$GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:weekday2').'<br />'.date($format_date_php,strtotime("+1 day",strtotime($dateLundi))).'</td>
	<td>'.$GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:weekday3').'<br />'.date($format_date_php,strtotime("+2 day",strtotime($dateLundi))).'</td>
	<td>'.$GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:weekday4').'<br />'.date($format_date_php,strtotime("+3 day",strtotime($dateLundi))).'</td>
	<td>'.$GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:weekday5').'<br />'.date($format_date_php,strtotime("+4 day",strtotime($dateLundi))).'</td>
	<td>'.$GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:weekday6').'<br />'.date($format_date_php,strtotime("+5 day",strtotime($dateLundi))).'</td>
	<td>'.$GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:weekday7').'<br />'.date($format_date_php,strtotime("+6 day",strtotime($dateLundi))).'</td>
	</tr>
	<tr>' ;

		$p=8;
		$i=0;
		$content.= '<td>'. tx_casreservation_pilib::explainTime($p) . "</td>\n";

		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_row($result))
		{
			list($room,$weekday,$timegrid,$type,$label_grid/*,$occupation,$label*/) = $row;
			$dategrid=date("Y-m-d", strtotime("+".($weekday-1)." day",strtotime($dateLundi)));

			// Get occupation and label
			$result2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery('status, label', 'tx_casreservation_reservation', 
				'room='.$GLOBALS['TYPO3_DB']->fullQuoteStr($room,'tx_casreservation_reservation').
				' and date_reserv='.$GLOBALS['TYPO3_DB']->fullQuoteStr($dategrid,'tx_casreservation_reservation').
				' and time_reserv='.$GLOBALS['TYPO3_DB']->fullQuoteStr($timegrid,'tx_casreservation_reservation'), 
				'', "status DESC", "1")
				or die('Error, query failed. line '.__LINE__ ." ".$GLOBALS['TYPO3_DB']->sql_error());
			list($occupation, $label) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result2);
			$GLOBALS['TYPO3_DB']->sql_free_result($result2);

			// Get result from special dates if any
			$result2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery('type_special', 'tx_casreservation_dates_special', 
				'room_special='.$GLOBALS['TYPO3_DB']->fullQuoteStr($room,'tx_casreservation_dates_special').
				' and date_special='.$GLOBALS['TYPO3_DB']->fullQuoteStr($dategrid,'tx_casreservation_dates_special').
				' and time_special='.$GLOBALS['TYPO3_DB']->fullQuoteStr($timegrid,'tx_casreservation_dates_special'),
				'')
				or die('Error, query failed. line '.__LINE__ ." ".$GLOBALS['TYPO3_DB']->sql_error());
			list($type_special) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result2);
			$GLOBALS['TYPO3_DB']->sql_free_result($result2);
			if($type_special!="") $type=$type_special;
			if($type=="0"){
				/*if($dategroup=="")
					$content.= "  <td class=\"abo\">Abonnement</td>\n";
				else*/ 
				
				if($occupation==""||$occupation=="0"){
					if($booking && strtotime($dategrid)>=strtotime($delaymin,time())&&strtotime($dategrid)<strtotime($delaymax,time()))
						$content.= "  <td class=\"free\">".
						'<input type="checkbox" name="'.$plugin->prefixId.'[cb-'.$dategrid.'-'.$timegrid."]\"/>&nbsp;Libre&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
					else
						$content.= "  <td class=\"free\">Libre</td>\n";
				}
				else if($occupation=="1")
					$content.= "  <td class=\"pre-reserv\">Pr&eacute;-r&eacute;serv&eacute;<br />$label</td>\n";
				else if($occupation>1){
					if (substr_count($label,"UniSport"))$style="uni";
					else $style="occupied";
					$content.= "  <td class=\"$style\">$label</td>\n";
				}else 
					$content.= "  <td>Erreur:$occupation</td>\n";
			}
			else{
				switch ($type){
				case "1":$content.= "  <td class=\"abo\">Abonnement</td>\n";
				break;
				//case "2":echo "  <td class=\"uni\">Universit&eacute;</td>\n";
				//break;
				case "3":$content.= "  <td class=\"special_gr\">$label_grid</td>\n";
				break;
				default:
				;
				break;}
			}
			$i=$i+1;
			if($i==7){
				$i=0;
				$p=$p+2;
				$content.= "</tr>\n";
				if($p<=20){
					$content.= "<tr>\n";
					$content.= "<td>". tx_casreservation_pilib::explainTime($p) ."</td>\n";
				}
			}
		}
	$content.= "</table></div>";
	$GLOBALS['TYPO3_DB']->sql_free_result($result);
	}

return $content;
}
//========================================================================
//	Selection de la semaine a afficher
//========================================================================
static function displaySelectMonday($delaymin, $delaymax, $plugin, $date1, $room)
{
	$content='';
	$datePrev=date("Y-m-d",strtotime("-7days", strtotime($date1)));
	$dateNext=date("Y-m-d",strtotime("+7days", strtotime($date1)));

	$content.=$plugin->pi_linkToPage('&lt;&lt; ', $GLOBALS['TSFE']->id, '', array($plugin->prefixId."[week]"=>$datePrev, $plugin->prefixId."[room]"=>$room, '#'.$plugin->prefixId => ''));

	$content.= $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:week_of') . '<select name="'.$plugin->prefixId.'[week]" onchange="this.form.submit();">';
	$dateLundi='';
	if(isset($plugin->piVars['week']))
		$dateLundi = strtotime($plugin->piVars['week']);


	$dateLu=strtotime(tx_casreservation_pilib::getMonday());

	while($dateLu < strtotime($delaymax))
	{
		$content.= '<option value="' . date("Y-m-d",$dateLu) . '"';
		if($dateLu == $dateLundi) $content.="selected=\"selected\"";
		$content.='>' . $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:weekday1') . ' '.date($GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:format_date_php'), $dateLu)."</option>\n"; 
		$dateLu=strtotime("+7 day",$dateLu);
	} // end while
	$content.='</select>';

	$content.=$plugin->pi_linkToPage(' &gt;&gt;', $GLOBALS['TSFE']->id, '', array($plugin->prefixId."[week]"=>$dateNext, $plugin->prefixId."[room]"=>$room));
	
	return $content;
}
//========================================================================
//	Selection de la salle
//========================================================================
static function displaySelectRoom($rooms, $plugin, $allrooms=false)
{
	$content='';
	$selected='';
	
	$room_name = array();
	$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery("id, room_name", "tx_casreservation_room", '') 
		or die('Error, query failed. line '.__LINE__ ." ".$GLOBALS['TYPO3_DB']->sql_error());
	while($row = $GLOBALS['TYPO3_DB']->sql_fetch_row($result)){
		list($id, $name) = $row;
		$room_name["'".$id."'"]= $name; 
	}
	$GLOBALS['TYPO3_DB']->sql_free_result($result);

	if(count($rooms)==1){
		return $room_name["'".$rooms[0]."'"];
	}
	if(count($rooms) < 1) return 'Error: room number must be specified in plugin.';

	$content.='<select name="'.$plugin->prefixId.'[room]" onchange="this.form.submit();">';
	if(isset($plugin->piVars['room']))
		$selected = htmlspecialchars($plugin->piVars['room']);

	if($allrooms){
		$content.="<option value=\"0\"";
		if($selected=='0') $content.="selected=\"selected\"";
		$content.=">Toutes les salles</option>\n"; 
	}
	foreach ($rooms as $room) {
		$content.="<option value=\"$room\"";
		if($room==$selected) $content.="selected=\"selected\"";
		$content.=">".$room_name["'".$room."'"]."</option>\n"; 
	} // end while
	$content.='</select>';
	
	return $content;
}
//========================================================================
//	Nom du groupe
//========================================================================
//static function getGroupName()
//{
	// Trouver le nom du groupe
	/*$sessionid=1; // HACK : $_SESSION['SESS_MEMBER_ID']
	$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery("label", "tx_casreservation_members","member_id='".$sessionid."' ","","","1") 
		or die('Error, query failed. line '.__LINE__ ." ".$GLOBALS['TYPO3_DB']->sql_error());
	if($row = $GLOBALS['TYPO3_DB']->sql_fetch_row($result))
		list($label) = $row;
*/
//	return "";//$label; FIXME 
//}
//========================================================================
//	Affiche le tableau des couts
//========================================================================
static function displayCosts($room)
{
	if($room == '') return 'Error, room not set.';
	$content='';
	$costs=array();
	$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery("price, material","tx_casreservation_costs","nb_periods<=3 and room=".$room, '',"nb_periods, material") 
		or die('Error, query failed. line '.__LINE__ ." ".$GLOBALS['TYPO3_DB']->sql_error());


	$i=0;
	$mat=0;
	while($row = $GLOBALS['TYPO3_DB']->sql_fetch_row($result))
	{
		list($costs[$i],$mat) = $row;
		$i++;
	}
	$material=$mat;

	$content.='<br />
<table class="costs" cellpadding="2" cellspacing="0">
<tr class="header">
<th> &nbsp; </th>
';
	if($material > 0) 
		$content.= '<th>' . $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:material0') . '</th><th>' . $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:material1') . '</th>';
	else
		$content.= '<th> &nbsp; </th>';
	$content.= '</tr>
';
	for($i=1; $i<=3; $i++)
	{
		//$costs[i]=array(2);
		$content.='<tr><td>'.$i. $GLOBALS['TSFE']->sL('LLL:EXT:cas_reservation/pilib/locallang.xml:time_periods') . '</td>';
		for($j=0; $j<=$material; $j++)
		{
			$content.= '<td>'.tx_casreservation_pilib::formatFranc($costs[$i * $material + $j]).'</td>';
		}
		$content.= '</tr>';
	}
	$GLOBALS['TYPO3_DB']->sql_free_result($result);

	$content.='
</table>
<br />';

	return $content;
}

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cas_reservation/pilib/class.tx_casreservation_pilib.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cas_reservation/pilib/class.tx_casreservation_pilib.php']);
}

?>



