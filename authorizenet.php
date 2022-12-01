<?
class AuthorizenetClass { 
    var $vendor;    // Credit card processor name 
    var $postURL;    // URL to post to 
      
    // Set default values.  Changes these with $ac->setParameter(string key, mixed value) 
    var $params = Array(); 
    var $results = Array(); 
      
    // Constructor: Defaults to authorizenet.  Also accepts planetpayment and quickcommerce 
    function AuthorizenetClass($vendor = "authorizenet") { 
        if (!curl_version()) die ("ERROR (AuthrorizenetClass): cURL not installed"); 
        $this->vendor = $vendor; 
        $this->postURL = $this->vendorPostURLs[$vendor]; 
        $this->_setDefaultParams(); 
    } 
      
    // setPostURL(string url): Set the URL to post to. 
    function setPostURL($url) { 
        $this->postURL = $url; 
    } 
      
    // setParemeter(string key, string value):  Used to set each name/value pair to be sent 
    function setParameter($key, $value) { 
        $this->params[$key] = $value; 
    } 
      
    // process(): Submit to gateway 
    function process() { 
        if (!$this->params['x_Login']) 
            die("Error (AuthorizenetClass): x_Login is a required field"); 
        if (!$this->params['x_Card_Num']) 
            die("Error (AuthorizenetClass): x_Card_Num is a required field"); 
        if (!$this->params['x_Exp_Date']) 
            die("Error (AuthorizenetClass): x_Exp_Date is a required field"); 
          
        $qString = ""; 
        while(list($key, $val) = each($this->params)) 
            $qString .= "$key=".urlencode($val)."&"; 
        $qString = substr($qString, 0, strlen($qString)-1);    // remove the last ampersand 

        $ch = curl_init(); 
        curl_setopt ($ch, CURLOPT_URL, $this->postURL); 
        curl_setopt ($ch, CURLOPT_HEADER, 0); 
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt ($ch, CURLOPT_POST, 1); 
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $qString); 
        $result = curl_exec ($ch); 
        curl_close ($ch); 
          
        if (!$result) 
            return 0; 
        return $this->_processResult($result); 
    } 
      
    // getResults(): Returns the results array 
    function getResults() { 
        return $this->results; 
    } 
      
    // reset(): Call before beginning a new transaction 
    function reset() { 
        $this->results = Array(); 
        $this->params = Array(); 
        $this->_setDefaultParams(); 
    } 
	
	/////////////////////////////////////////////////////// 
    // Internal Functions 
    

    // _processResults(string $results): Internal Function.  Creates the results array 
    function _processResult($result) { 
        if ($result == 'Invalid Merchant Login or Account Inactive') { 
            $result = "3,0,0,".$result;    // Bogus string with the proper error message 
        } 
            
        $resArray = explode(",", $result); 
                $len = count($this->resultFields); 
        $j = 0; 
        for($i=0; $i< $len; $i++) { 
            if ($this->resultFields[$i]) 
                $this->results[$this->resultFields[$i]] = $resArray[$i]; 
            else { 
                $j++; 
                $this->results["x_custom_$j"] = $resArray[$i];
            } 
        } 
        return $this->results['x_response_code']; 
    } 
      
    // _setDefaultParams(): Internal Function.  Reset the params array. 
    function _setDefaultParams() 
	{ 
        $this->params["x_Version"] = "3.1"; 
        $this->params["x_Delim_Data"] = "TRUE"; 
        //$this->params["x_Echo_Data"] = "TRUE"; 
        //$this->params["x_ADC_URL"] = "FALSE"; 
        $this->params["x_Type"] = "AUTH_CAPTURE"; 
		//$this->params["x_Type"] = "AUTH_ONLY"; 
        $this->params["x_Method"] = "CC"; 
		$this->params["x_Tran_Key"] = "5gmuf6TFcW6K878S"; 
		$this->params["x_Delim_Char"] = ", "; 
		$this->params["x_Encap_Char"] = ""; 
     } 
    /////////////////////////////////////////////////////// 
    // Associative arrays containing matching data 

    // holds vendor/url pairls 
    var $vendorPostURLs = Array ( 
        'authorizenet'    =>    'https://secure2.authorize.net/gateway/transact.dll');
		          
    // Array of response names.  Used in the results array. 
    var $resultFields = Array( 
        "x_response_code", 
        "x_response_subcode", 
        "x_response_reason_code", 
        "x_response_reason_text", 
        "x_auth_code", 
        "x_avs_code", 
        "x_trans_id", 
        "x_invoice_num", 
        "x_Description", 
        "x_amount", 
        "x_method", 
        "x_type", 
        "x_cust_id", 
        "x_First_Name", 
        "x_last_name", 
        "x_company", 
        "x_address", 
        "x_city", 
        "x_state", 
        "x_zip", 
        "x_country", 
        "x_phone", 
        "x_fax", 
        "x_email", 
        "x_ship_to_first_name", 
        "x_ship_to_last_name", 
        "x_ship_to_company", 
        "x_ship_to_address", 
        "x_ship_to_city", 
        "x_ship_to_state", 
        "x_ship_to_zip", 
        "x_ship_to_country", 
        "x_tax", 
        "x_duty", 
        "x_freight", 
        "x_tax_exempt", 
        "x_po_num", 
        "x_md5_hash", 
        "x_card_code",
		"x_currency_code"
		); 
} ?>