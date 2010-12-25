<?php

if(!(tx_casreservation_pilib::isAdmin($this))) exit(0);

/*------------------------------------------------------------------------------------------------------*/
$colors = array('blue', 'red', 'yellow', 'green', 'cyan', 'orange', 'pink');
$ydata = array();
$xlabel = array();
$xname="jour";

// Get room names
$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('room_name',
	'tx_casreservation_room',
	"id IN(".implode(',',$this->rooms).')',
	'id')
	or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());


foreach($this->rooms as $room) 
{
    $ydata[$room] = array();
}

// Fill the data arrays
	for ($i=0;$i<7;$i++)
	{
		foreach($this->rooms as $room) 
		{
			$ydata[$room][$i] = 0;
		}
		$date1="$start-08-01";
		$date2=($start+1)."-07-31";

		$wday=($i+1) % 7 + 1;
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('room, count(*)',
			'tx_casreservation_reservation',
			"date_reserv > '$date1' and date_reserv < '$date2' and  DAYOFWEEK(date_reserv)='$wday' and status>0 and room IN(".implode(',',$this->rooms).')',
			'room', 'room')
			or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());

		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_row($result))
		{
			list($room, $sum) = $row;
			$ydata[$room][$i] = $sum;
		}
		$xlabel[] = tx_casreservation_pilib::explainWeekday($i+1);
	}
	$xlabel[] = "";

// Width and height of the graph
$width = 700; $height = 300;
 
// Create a graph instance
$graph = new Graph($width,$height);
 
// Specify what scale we want to use,
$graph->SetScale('intint');
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
 
// Setup a title for the graph
$graph->title->Set("Reservations de aout ".$start." a juillet ".($start+1));
 
// Setup titles and X-axis labels
$graph->xaxis->title->Set($xname);
$graph->xaxis->SetTickLabels($xlabel);
 
// Setup Y-axis title
$graph->yaxis->title->Set('Periodes reservees');

$b = array();

foreach($this->rooms as $room) 
{
	$b[]= new BarPlot($ydata[$room]);
	$n = count($b)-1;
	$b[$n]->SetColor("white");
	$b[$n]->SetFillColor($colors[$n % count($colors)]);
	$b[$n]->SetLegend($this->room_names[$room]);
}

// Create the box plot

// Add the plot to the graph
$gbplot  = new GroupBarPlot ($b);
$graph->Add($gbplot);

// Display the graph
$graph->Stroke( $file );
?>
