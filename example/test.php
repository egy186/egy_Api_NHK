<pre>
<?php
require_once('test_egyApiNHK.php');

$eaNHK = new egy\Api\NHK('your_apikey');

echo $eaNHK->programListAPI(':area', ':service', date('Y-m-d')) . "\n";
echo $eaNHK->programGenreAPI(':area', ':genre', date('Y-m-d')) . "\n";
echo $eaNHK->programInfoAPI(':area', ':id') . "\n";
echo $eaNHK->nowOnAirAPI(':area', ':service') . "\n";
/* EOF */