<? include('connect.php');
$EVS_USERID = '026MUTUA3865'; // Your USPS Username
$FromName = 'MUTUAL INDUSTRIES';
$FromAddress1 = 'STE 1';
$FromAddress2 = '707 W GRANGE AVE';
$FromCity = 'PHILADELPHIA';
$FromState = 'PA';
$FromZip5 = '19120';
$FromZip4 ='2298';
$FromPhone ='2159276000';
$TotalWeight=$_GET['STotWeight'];
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
?>
<table border="0" cellspacing="0" width="100%" cellpadding="10" style="border:1px solid #cecece;">
		<? if($RateS1!=''){?>
		  <tr>
			<td  width="50" align="left" valign="top" style="font-size:14px;"><img src="images/USPS-priority-mail-rate-box.jpg" width="50"></td>
			<td align="left" valign="middle" style="font-size:14px;">Priority Mail 3-Day</td>
			<td align="right" valign="middle" style="font-size:14px;"><span style="color: #ed1c24;">$<?=$RateS1;?></span></td>
		  </tr>
		  <? }?>
		  <? if($RateS28!=''){?>
		  <tr>
			<td align="left" valign="top" style="font-size:14px;"><img src="images/USPS-priority-mail-small-flat-rate-box.jpg" width="50"></td>
			<td align="left" valign="middle" style="font-size:14px;">Priority Mail 3-Day /  Small Flat Rate Box</td>
			<td align="right" valign="middle" style="font-size:14px;"><span style="color: #ed1c24;">$<?=$RateS28;?></span></td>
		  </tr>
		  <? }?>
		  <? if($RateS17!=''){?>
		  <tr>
			<td align="left" valign="top" style="font-size:14px;"><img src="images/USPS-priority-mail-medium-flat-rate-box.jpg" width="50"></td>
			<td align="left" valign="middle" style="font-size:14px;">Priority Mail 3-Day / Medium Flat Rate Box</td>
			<td align="right" valign="middle" style="font-size:14px;"><span style="color: #ed1c24;">$<?=$RateS17;?></span></td>
		  </tr>
		  <? }?>
		  <? if($RateS22!=''){?>
		  <tr>
			<td align="left" valign="top" style="font-size:14px;"><img src="images/USPS-priority-mail-large-flat-rate-box.jpg" width="50"></td>
			<td align="left" valign="middle" style="font-size:14px;">Priority Mail 3-Day / Large Flat Rate Box</td>
			<td align="right" valign="middle" style="font-size:14px;"><span style="color: #ed1c24;">$<?=$RateS22;?></span></td>
		  </tr>
		  <? }?>
		  <? if($RateS16!=''){?>
		  <tr>
			<td align="left" valign="top" style="font-size:14px;"><img src="images/USPS-priority-mail-flat-rate-envelope.jpg" width="80"></td>
			<td align="left" valign="middle" style="font-size:14px;">Priority Mail 3-Day / Flat Rate Envelope</td>
			<td align="right" valign="middle" style="font-size:14px;"><span style="color: #ed1c24;">$<?=$RateS16;?></span></td>
		  </tr>
		  <? }?>
		  <? if($RateS44!=''){?>
		  <tr>
			<td align="left" valign="top" style="font-size:14px;"><img src="images/USPS-priority-mail-legal-flat-rate-envelope.jpg" width="50"></td>
			<td align="left" valign="middle" style="font-size:14px;">Priority Mail 3-Day / Legal Flat Rate Envelope</td>
			<td align="right" valign="middle" style="font-size:14px;"><span style="color: #ed1c24;">$<?=$RateS44;?></span></td>
		  </tr>
		  <? }?>
		  <? if($RateS42!=''){?>
		  <tr>
			<td align="left" valign="top" style="font-size:14px;"><img src="images/USPS-priority-mail-small-flat-rate-envelope.jpg" width="50"></td>
			<td align="left" valign="middle" style="font-size:14px;">Priority Mail 3-Day / Small Flat Rate Envelope</td>
			<td align="right" valign="middle" style="font-size:14px;"><span style="color: #ed1c24;">$<?=$RateS42;?></span></td>
		  </tr>
		  <? }?>
		  
		  <? if($RateS3!=''){?>
		  <tr>
			<td align="left" valign="top" style="font-size:14px;"><img src="images/USPS-priority-mail-express.jpg" width="50"></td>	
			<td align="left" valign="middle" style="font-size:14px;">Priority Mail Express 1-Day</td>
			<td align="right" valign="middle" style="font-size:14px;"><span style="color: #ed1c24;">$<?=$RateS3;?></span></td>
		  </tr>
		  <? }?>
		  <? if($RateS13!=''){?>
		  <tr>
			<td align="left" valign="top" style="font-size:14px;"><img src="images/USPS-priority-mail-express-flat-rate-envelope.jpg" width="50"></td>	
			<td align="left" valign="middle" style="font-size:14px;">Priority Mail Express 1-Day / Flat Rate Envelope</td>
			<td align="right" valign="middle" style="font-size:14px;"><span style="color: #ed1c24;">$<?=$RateS13;?></span></td>
		  </tr>
		  <? }?>
		  <? if($RateS30!=''){?>
		  <tr>
			<td align="left" valign="top" style="font-size:14px;"><img src="images/USPS-priority-mail-express-legal-flat-rate-envelope.jpg" width="50"></td>	
			<td align="left" valign="middle" style="font-size:14px;">Priority Mail Express 1-Day / Legal Flat Rate Envelope</td>
			<td align="right" valign="middle" style="font-size:14px;"><span style="color: #ed1c24;">$<?=$RateS30;?></span></td>
		  </tr>
		  <? }?>
		  <? if($RateS62!=''){?>
		  <tr>
			<td align="left" valign="top" style="font-size:14px;"><img src="images/USPS-priority-mail-padded-flat-rate-envelope.jpg" width="50"></td>	
			<td align="left" valign="middle" style="font-size:14px;">Priority Mail Express 1-Day / Padded Flat Rate Envelope</td>
			<td align="right" valign="middle" style="font-size:14px;"><span style="color: #ed1c24;">$<?=$RateS62;?></span></td>
		  </tr>
		  <? }?>
		  <? if($RateS0!=''){?>
		  <tr>
			<td align="left" valign="top" style="font-size:14px;">&nbsp;</td>
			<td align="left" valign="middle" style="font-size:14px;">First Class</td>
			<td align="right" valign="middle" style="font-size:14px;"><span style="color: #ed1c24;">$<?=$RateS0;?></span></td>
		  </tr>
		  <? }?>
		  <? if($RateS4!=''){?>
		  <tr>
			<td align="left" valign="top" style="font-size:14px;">&nbsp;</td>
			<td align="left" valign="middle" style="font-size:14px;">Parcel Select Ground</td>
			<td align="right" valign="middle" style="font-size:14px;"><span style="color: #ed1c24;">$<?=$RateS4;?></span></td>
		  </tr>
		  <? }?>
		  <?php /*?><? if($RateS1!=''){?>
		  <tr>
			<td align="left" valign="top" style="font-size:14px;">&nbsp;</td>
			<td align="left" valign="top" style="font-size:14px;">Custom Freight Shipping. Shipping Charges will be billed separately.</td>
			<td align="right" valign="top" style="font-size:14px;">&nbsp;</td>
		  </tr>
		  <? }?>
		  <? if($RateS1!=''){?>
		  <tr>
			<td align="left" valign="top" style="font-size:14px;">&nbsp;</td>
			<td align="left" valign="top" style="font-size:14px;">Please call for a shipping quote.:$0.00</td>
			<td align="right" valign="top" style="font-size:14px;">&nbsp;</td>
		  </tr><? }?><?php */?>
		</table>
<? }
else{
echo "<span style='color:red;margin-bottom:20px;margin-top:20px;display:block;'>".$params['RATEV4RESPONSE']['1ST']['ERROR']['DESCRIPTION']."</span>";
}
?>