<!-- Code generated by Indicator-Reference-Code-Generator.py -->
<? 
include(DOCS_RESOURCES."/qcalgorithm-api/_method_container.html");

$typeName = 'BollingerBands';
$helperName = 'BB';
$helperArguments = '"SPY", 30, 2';
$properties = array("StandardDeviation","MiddleBand","UpperBand","LowerBand","BandWidth","PercentB","Price");
$updateParameterType = 'time/number pair, or an <code>IndicatorDataPoint</code>';
$constructorArguments = '30, 2';
$updateParameterValue = 'bar.EndTime, bar.Close';
$hasMovingAverageTypeParameter = True;
$constructorBox = 'bollinger-bands';
include(DOCS_RESOURCES."/indicators/using-indicator.php");
?>