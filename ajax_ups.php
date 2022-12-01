<? include('connect.php');
$tozip=$_GET['zipcode'];
$fromzip="19120";
$length=$_GET['length'];
$width=$_GET['width'];
$height=$_GET['height'];
$weight=$_SESSION['STotWeight'];

error_reporting(E_ALL & ~E_NOTICE);


 $errors = false;
 $errmessage = "";
 if(is_numeric($fromzip)){
  $fromzip = $fromzip;
 }else{
  $errors = true;
  $errmessage = "Please enter a valid Shipping from Zip.<br />\n";
 }
 if(is_numeric($tozip)){
  $tozip = $tozip;
 }else{
  $errors = true;
  $errmessage = "Please enter a valid Shipping from Zip.<br />\n";
 }
 if(is_numeric($length)){
  $length = $_REQUEST['length'];
 }else{
  $errors = true;
  $errmessage = "Please enter a valid length.<br />\n";
 }
 if(is_numeric($width)){
  $width = $width;
 }else{
  $errors = true;
  $errmessage = "Please enter a valid width.<br />\n";
 }
 if(is_numeric($height)){
  $height=$height;
 }else{
  $errors = true;
  $errmessage = "Please enter a valid height.<br />\n";
 }
 if(is_numeric($weight)){
  $weight = $weight;
 }else{
  $errors = true;
  $errmessage = "Please enter a valid weight.<br />\n";
 }

if(!$errors){
  
  require("calculate_cart.php");

  /*************************************
  Get your own credentials from ups.com
  *************************************/
  $ups_accessnumber = "DD12A419D3715D28";
  $ups_username = "zadaman";
  $ups_password = "Mutual#123";
  $ups_shippernumber = "178942";
 
  // just doing domestic for demonstration purposes
  $services = array(
      "UPS Ground Rate"=>"03", 
      "UPS 3 Day Select Rate"=>"12", 
      "UPS 2nd Day Air Rate"=>"02", 
      "UPS Next Day Air Saver Rate"=>"13", 
      "UPS Next Day Air Rate"=>"01", 
      "UPS Next Day Early A.M. Rate"=>"14"
     );
  
  $myRate = new upsRate;
  $myRate->setCredentials($ups_accessnumber, $ups_username, $ups_password, $ups_shippernumber);
  $Drop.="<select name='shipping' id='shipping' class='c_textbox' onchange='Getusps_price();'>
			<option value=''>Please Select Shipping Method</option>";
  foreach($services as $name=>$value){
   $service = $value;
   $rate = $myRate->getRate($fromzip, $tozip, $service, $length, $width, $height, $weight);
    if($rate!=''){$Drop.="<option value='".$name." $".$rate."'"; if($_SESSION['shipmethod']==$name){ $Drop.="selected"; } $Drop.=">".$name." (1 package) [$".$rate."]</option>";}
  }
 }else{
  
  echo "<span style='color:red;margin-bottom:20px;margin-top:20px;display:block;'>Form Errors:<p>$errmessage</p></span>\n";
 
 }
 $EVS_USERID = '026MUTUA3865'; // Your USPS Username
$FromName = 'MUTUAL INDUSTRIES';
$FromAddress1 = 'STE 1';
$FromAddress2 = '707 W GRANGE AVE';
$FromCity = 'PHILADELPHIA';
$FromState = 'PA';
$FromZip5 = '19120';
$FromZip4 ='2298';
$FromPhone ='2159276000';
$TotalWeight=$_SESSION['STotWeight'];
$length=$_GET['length'];
$width=$_GET['width'];
$height=$_GET['height'];
$shipping_to_zipcode = $_GET['zipcode'];
class uspsxmlParser {

var $params = array(); //Stores the object representation of XML data
var $root = NULL;
var $global_index = -1;
var $fold = false;

/* Constructor for the class
* Takes in XML data as input( do not include the <xml> tag
*/
function xmlparser($input, $xmlParams=array(XML_OPTION_CASE_FOLDING => 0)) {
    $xmlp = xml_parser_create();
        foreach($xmlParams as $opt => $optVal) {
            switch( $opt ) {
            case XML_OPTION_CASE_FOLDING:
                $this->fold = $optVal;
            break;
            default:
            break;
            }
            xml_parser_set_option($xmlp, $opt, $optVal);
    }

    if(xml_parse_into_struct($xmlp, $input, $vals, $index)) {
        $this->root = $this->_foldCase($vals[0]['tag']);
        $this->params = $this->xml2ary($vals);
    }
    xml_parser_free($xmlp);
}

function _foldCase($arg) {
    return( $this->fold ? strtoupper($arg) : $arg);
}


function xml2ary($vals) {

    $mnary=array();
    $ary=&$mnary;
    foreach ($vals as $r) {
        $t=$r['tag'];
        if ($r['type']=='open') {
            if (isset($ary[$t]) && !empty($ary[$t])) {
                if (isset($ary[$t][0])){
                    $ary[$t][]=array();
                } else {
                    $ary[$t]=array($ary[$t], array());
                }
                $cv=&$ary[$t][count($ary[$t])-1];
            } else {
                $cv=&$ary[$t];
            }
            $cv=array();
            if (isset($r['attributes'])) {
                foreach ($r['attributes'] as $k=>$v) {
                $cv[$k]=$v;
                }
            }

            $cv['_p']=&$ary;
            $ary=&$cv;

            } else if ($r['type']=='complete') {
                if (isset($ary[$t]) && !empty($ary[$t])) { // same as open
                    if (isset($ary[$t][0])) {
                        $ary[$t][]=array();
                    } else {
                        $ary[$t]=array($ary[$t], array());
                    }
                $cv=&$ary[$t][count($ary[$t])-1];
            } else {
                $cv=&$ary[$t];
            }
            if (isset($r['attributes'])) {
                foreach ($r['attributes'] as $k=>$v) {
                    $cv[$k]=$v;
                }
            }
            $cv['VALUE'] = (isset($r['value']) ? $r['value'] : '');

            } elseif ($r['type']=='close') {
                $ary=&$ary['_p'];
            }
    }

    $this->_del_p($mnary);
    return $mnary;
}

// _Internal: Remove recursion in result array
function _del_p(&$ary) {
    foreach ($ary as $k=>$v) {
    if ($k==='_p') {
          unset($ary[$k]);
        }
        else if(is_array($ary[$k])) {
          $this->_del_p($ary[$k]);
        }
    }
}

/* Returns the root of the XML data */
function GetRoot() {
  return $this->root;
}

/* Returns the array representing the XML data */
function GetData() {
  return $this->params;
}}

$url = "http://Production.ShippingAPIs.com/ShippingAPI.dll";//https://www.usps.com/business/web-tools-apis/rate-calculator-api.pdf
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
//$data = "API=RateV4&XML=<RateV4Request USERID=\"$EVS_USERID\"><Revision>2</Revision><Package ID=\"1ST\"><Service>All</Service><ZipOrigination>$FromZip5</ZipOrigination><ZipDestination>$shipping_to_zipcode</ZipDestination><Pounds>$TotalWeight</Pounds><Ounces>0</Ounces><Container/><Width>62.75</Width><Length>41.75</Length><Height>13.75</Height><Machinable>FALSE</Machinable></Package></RateV4Request>";
$data = "API=RateV4&XML=<RateV4Request USERID=\"$EVS_USERID\"><Revision>2</Revision><Package ID=\"1ST\"><Service>All</Service><ZipOrigination>$FromZip5</ZipOrigination><ZipDestination>$shipping_to_zipcode</ZipDestination><Pounds>$TotalWeight</Pounds><Ounces>0</Ounces><Container/><Size>REGULAR</Size><Width>$width</Width><Length>$length</Length><Height>$height</Height><Machinable>FALSE</Machinable></Package></RateV4Request>";
curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
$result=curl_exec ($ch);
$data = strstr($result, '<?');$xml_parser = xml_parser_create();xml_parse_into_struct($xml_parser, $data, $vals, $index);xml_parser_free($xml_parser);$params = array();$level = array();
foreach ($vals as $xml_elem){if ($xml_elem['type'] == 'open') {if (array_key_exists('attributes',$xml_elem)) {list($level[$xml_elem['level']],$extra) = array_values($xml_elem['attributes']);} else {$level[$xml_elem['level']] = $xml_elem['tag'];}}if ($xml_elem['type'] == 'complete') {$start_level = 1;$php_stmt = '$params';while($start_level < $xml_elem['level']) {$php_stmt .= '[$level['.$start_level.']]';$start_level++;}$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';eval($php_stmt);}}
curl_close($ch);//print_r($params);
if($params['RATEV4RESPONSE']['1ST'][1]['RATE']>0)
{
	//print_r($params['RATEV4RESPONSE']);
	$RateS1=$params['RATEV4RESPONSE']['1ST'][1]['RATE'];//Priority Mail 3-Day
	$RateS22=$params['RATEV4RESPONSE']['1ST'][22]['RATE'];//Priority Mail 3-Day - Large Flat Rate Box
	$RateS17=$params['RATEV4RESPONSE']['1ST'][17]['RATE'];//Priority Mail 3-Day - Medium Flat Rate Box
	$RateS28=$params['RATEV4RESPONSE']['1ST'][28]['RATE'];//Priority Mail 3-Day -  Small Flat Rate Box
	$RateS16=$params['RATEV4RESPONSE']['1ST'][16]['RATE'];//Priority Mail 3-Day -  Flat Rate Envelope
	$RateS44=$params['RATEV4RESPONSE']['1ST'][44]['RATE'];//Priority Mail 3-Day -  Legal Flat Rate Envelope
	$RateS42=$params['RATEV4RESPONSE']['1ST'][42]['RATE'];//Priority Mail 3-Day -  Small Flat Rate Envelope
	$RateS0=$params['RATEV4RESPONSE']['1ST'][0]['RATE'];//First-Class Package Service
	$RateS4=$params['RATEV4RESPONSE']['1ST'][4]['RATE'];//USPS Retail Ground
	$RateS3=$params['RATEV4RESPONSE']['1ST'][3]['RATE'];//Priority Mail Express 1-Day
	$RateS13=$params['RATEV4RESPONSE']['1ST'][13]['RATE'];//Priority Mail Express 1-Day - Flat Rate Envelope
	$RateS30=$params['RATEV4RESPONSE']['1ST'][30]['RATE'];//Priority Mail Express 1-Day - Legal Flat Rate Envelope
	$RateS62=$params['RATEV4RESPONSE']['1ST'][62]['RATE'];//Priority Mail Express 1-Day - Padded Flat Rate Envelope


if($RateS1!=''){$Drop.="<option value='Priority Mail 3-Day $".$RateS1."'"; if($_SESSION['shipmethod']=="Priority Mail 3-Day"){ $Drop.="selected"; } $Drop.=">Priority Mail 3-Day [$".$RateS1."]</option>";}
if($RateS28!=''){$Drop.="<option value='Priority Mail 3-Day /  Small Flat Rate Box $".$RateS28."'"; if($_SESSION['shipmethod']=="Priority Mail 3-Day /  Small Flat Rate Box"){ $Drop.="selected";} $Drop.=">Priority Mail 3-Day /  Small Flat Rate Box [$".$RateS28."]</option>";}
if($RateS17!=''){$Drop.="<option value='Priority Mail 3-Day / Medium Flat Rate Box $".$RateS17."'"; if($_SESSION['shipmethod']=="Priority Mail 3-Day / Medium Flat Rate Box"){$Drop.="selected";}$Drop.=">Priority Mail 3-Day / Medium Flat Rate Box [$".$RateS17."]</option>";}
if($RateS22!=''){$Drop.="<option value='Priority Mail 3-Day / Large Flat Rate Box $".$RateS22."'"; if($_SESSION['shipmethod']=="Priority Mail 3-Day / Large Flat Rate Box"){$Drop.="selected";}$Drop.=">Priority Mail 3-Day / Large Flat Rate Box [$".$RateS22."]</option>";}
if($RateS16!=''){$Drop.="<option value='Priority Mail 3-Day / Flat Rate Envelope $".$RateS16."'"; if($_SESSION['shipmethod']=="Priority Mail 3-Day / Flat Rate Envelope"){$Drop.="selected";}$Drop.=">Priority Mail 3-Day / Flat Rate Envelope [$".$RateS16."]</option>";}
if($RateS44!=''){$Drop.="<option value='Priority Mail 3-Day / Legal Flat Rate Envelope $".$RateS44."'"; if($_SESSION['shipmethod']=="Priority Mail 3-Day / Legal Flat Rate Envelope"){$Drop.="selected";}$Drop.=">Priority Mail 3-Day / Legal Flat Rate Envelope [$".$RateS44."]</option>";}
if($RateS42!=''){$Drop.="<option value='Priority Mail 3-Day / Small Flat Rate Envelope $".$RateS42."'"; if($_SESSION['shipmethod']=="Priority Mail 3-Day / Small Flat Rate Envelope"){$Drop.="selected";}$Drop.=">Priority Mail 3-Day / Small Flat Rate Envelope [$".$RateS42."]</option>";}
if($RateS3!=''){$Drop.="<option value='Priority Mail Express 1-Day $".$RateS3."'"; if($_SESSION['shipmethod']=="Priority Mail Express 1-Day"){$Drop.="selected";}$Drop.=">Priority Mail Express 1-Day [$".$RateS3."]</option>";}
if($RateS13!=''){$Drop.="<option value='Priority Mail Express 1-Day / Flat Rate Envelope $".$RateS13."'"; if($_SESSION['shipmethod']=="Priority Mail Express 1-Day / Flat Rate Envelope"){$Drop.="selected";}$Drop.=">Priority Mail Express 1-Day / Flat Rate Envelope [$".$RateS13."]</option>";}
if($RateS30!=''){$Drop.="<option value='Priority Mail Express 1-Day / Legal Flat Rate Envelope $".$RateS30."'"; if($_SESSION['shipmethod']=="Priority Mail Express 1-Day / Legal Flat Rate Envelope"){$Drop.="selected";}$Drop.=">Priority Mail Express 1-Day / Legal Flat Rate Envelope [$".$RateS30."]</option>";}
if($RateS62!=''){$Drop.="<option value='Priority Mail Express 1-Day / Padded Flat Rate Envelope $".$RateS62."'"; if($_SESSION['shipmethod']=="Priority Mail Express 1-Day / Padded Flat Rate Envelope"){$Drop.="selected";}$Drop.=">Priority Mail Express 1-Day / Padded Flat Rate Envelope [$".$RateS62."]</option>";}
if($RateS0!=''){$Drop.="<option value='First Class $".$RateS0."'"; if($_SESSION['shipmethod']=="First Class"){$Drop.="selected";}$Drop.=">First Class [$".$RateS0."]</option>";}
if($RateS4!=''){$Drop.="<option value='Parcel Select Ground $".$RateS4."'"; if($_SESSION['shipmethod']=="Parcel Select Ground"){$Drop.="selected";}$Drop.=">Parcel Select Ground [$".$RateS4."]</option>";}
$Drop.="</select>";
echo $Drop;
}
else{
echo "<span style='color:red;margin-bottom:20px;margin-top:20px;display:block;'>".$params['RATEV4RESPONSE']['1ST']['ERROR']['DESCRIPTION']."</span>";
}
?>