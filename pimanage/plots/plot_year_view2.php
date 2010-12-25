<?php

if(!(tx_casreservation_pilib::isAdmin($this))) exit(0);

/*------------------------------------------------------------------------------------------------------*/

//$start = intval($_GET['start']);
//$no =    intval($_GET['no']);
//$end = clean($_GET['end']);

$xdata = array();
$ydata1 = array();
$ydata2 = array();
$ydata3 = array();
$ydata4 = array();
$xlabel = array();

//$year=$start;
//$end=$start+1;

  $xname="mois";

// Fill the data arrays
	for ($i=0;$i<12;$i++)
	{
	  $month=($i+7)%12+1;
	  $year=$start+(($i+8)>12);

	  if($month<10)$month="0".$month;
	  for ($status=1;$status<=4;$status++)
	  {
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('count(*)',
			'tx_casreservation_reservation',
			"date_reserv like '$year-$month-%' and status='$status' and room IN(".implode(',',$this->rooms).')')
			or die('Error, query failed. line '.__LINE__ . $GLOBALS['TYPO3_DB']->sql_error());
		
		if($GLOBALS['TYPO3_DB']->sql_num_rows($result) != 0)
		{
		  $row = $row = $GLOBALS['TYPO3_DB']->sql_fetch_row($result);
		  list($cpt2) = $row;
		  if($status==1)$ydata1[$i]=$cpt2;
		  elseif($status==2)$ydata2[$i]=$cpt2;
		  elseif($status==3)$ydata3[$i]=$cpt2;
		  elseif($status==4)$ydata4[$i]=$cpt2;
		 }
	  }
	  $xdata[]=$i;
	  $xlabel[]=tx_casreservation_pilib::explainMonth($month);
	  $ydata2[$i]+=$ydata1[$i];
	  $ydata3[$i]+=$ydata2[$i];
	  $ydata4[$i]+=$ydata3[$i];
	}
  $xlabel[]="";

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

// Create the box plot
/* Plot all detail */
$b1=new BarPlot($ydata1,$xdata);
$b1->SetFillColor('orange');
$b1->SetLegend('Demande');

$b2 = new BarPlot($ydata2);
$b2->SetFillColor("green");
$b2->SetLegend("Accepte");

$b3 = new BarPlot($ydata3);
$b3->SetFillColor("yellow");
$b3->SetLegend("Facture");

$b4 = new BarPlot($ydata4);
$b4->SetFillColor("blue");
$b4->SetLegend("Paye");

// Add the plot to the graph
$graph->Add($b4);
$graph->Add($b3);
$graph->Add($b2);
$graph->Add($b1);

// Display the graph
$graph->Stroke( $file );

?>
