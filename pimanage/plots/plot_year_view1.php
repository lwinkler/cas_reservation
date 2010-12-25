<?php

if(!(tx_casreservation_pilib::isAdmin($this))) exit(0);

/*------------------------------------------------------------------------------------------------------*/
$colors = array('blue', 'red', 'yellow', 'green', 'cyan', 'orange', 'pink');
$ydata = array();
$xlabel = array();

//$year=$start;
//$end=$start+1;
foreach($this->rooms as $room) 
{
    $ydata[$room] = array();
}

// Fill the data arrays
for ($i=0;$i<12;$i++)
{
	foreach($this->rooms as $room) 
	{
		$ydata[$room][$i] = 0;
	}
	$month=($i+7)%12+1;
	$year=$start+(($i+8)>12);

	if($month<10)$month="0".$month;
	$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('room, count(*)',
		'tx_casreservation_reservation',
		"date_reserv like '$year-$month-%' and status>0 and room IN(".implode(',',$this->rooms).')',
		'room', 'room')
		or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
	
	while($row = $GLOBALS['TYPO3_DB']->sql_fetch_row($result))
	{
		list($room, $sum) = $row;
		$ydata[$room][$i] = $sum;
	}
	$xlabel[] = tx_casreservation_pilib::explainMonth($month);
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
$graph->xaxis->title->Set("mois");
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

// Add the plot to the graph
$gbplot = new GroupBarPlot($b);
$graph->Add($gbplot);

// Display the graph
$graph->Stroke( $file );

?>
