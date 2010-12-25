
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
require_once(t3lib_extMgm::extPath('cas_reservation').'pilib/class.tx_casreservation_pilib.php'); // 

/**
 * Plugin 'Display reservations' for the 'cas_reservation' extension.
 *
 * @author	Laurent Winkler <laurent.winkler@bluewin.ch>
 * @package	TYPO3
 * @subpackage	tx_casreservation
 */
class tx_casreservation_pidisplay extends tslib_pibase {
	var $prefixId      = 'tx_casreservation_pidisplay';		// Same as class name
	var $scriptRelPath = 'pidisplay/class.tx_casreservation_pidisplay.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'cas_reservation';	// The extension key.
	var $pi_checkCHash = true;
	
	// Delai min pour affichage
	var $delaymin = "-6 day";
	// Delai max pour affichage
	var $delaymax = "+52 weeks";
	// values are imported from flexform
	var $admin = "";
	var $rooms= array();

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

		/// Get plugin parameters from flexform
		$this->pi_initPIflexForm(); // Init and get the flexform data of the plugin
		$piFlexForm = $this->cObj->data['pi_flexform'];
		$this->admin = $this->pi_getFFvalue($piFlexForm, "admin", "sDEF");
		$this->rooms = explode(',', $this->pi_getFFvalue($piFlexForm, "room", "sDEF"));

		// Get variables form parameters
		$dateSelectedMonday = '';
		$dateThisMonday = tx_casreservation_pilib::getMonday();
		if(isset($this->piVars['week']))
			$dateSelectedMonday= htmlspecialchars($this->piVars['week']);

		if( $dateSelectedMonday == '' || strtotime($dateSelectedMonday) < strtotime($this->delaymin, strtotime($dateThisMonday))
				|| strtotime($dateSelectedMonday) > strtotime($this->delaymax, strtotime($dateThisMonday)))
			$dateSelectedMonday = $dateThisMonday;
			
		$room='';
		if(isset($this->piVars['room'])) $room = htmlspecialchars($this->piVars['room']);
		if($room=='' || count($this->rooms)==1){
			$room= $this->rooms[0];
		}

		// Get template
		$this->templateCode = $this->cObj->fileResource($conf['templateFile']);
		
		// Get the parts out of the template
		$template['total'] = $this->cObj->getSubpart($this->templateCode,'###TEMPLATE###');
		
		// Fill markers
		$markerArray['###SELECT_WEEK###'] = tx_casreservation_pilib::displaySelectMonday($this->delaymin, $this->delaymax, $this, $dateSelectedMonday, $room);
		$markerArray['###SELECT_ROOM###'] = tx_casreservation_pilib::displaySelectRoom($this->rooms, $this);
		$markerArray['###DATE###'] = tx_casreservation_pilib::getDate();
		$markerArray['###SHOW_PERIOD###'] = tx_casreservation_pilib::displayGrid($room, $dateSelectedMonday, false, $this->delaymin, $this->delaymax, $this);
		
		// Create the content by replacing the content markers in the template
		$content = $this->cObj->substituteMarkerArrayCached($template['total'],$markerArray);

		return $this->pi_wrapInBaseClass($content);
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cas_reservation/pidisplay/class.tx_casreservation_pidisplay.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cas_reservation/pidisplay/class.tx_casreservation_pidisplay.php']);
}

?>
