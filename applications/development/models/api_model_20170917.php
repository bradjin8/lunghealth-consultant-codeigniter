<?php

class Api_model 
{
    protected      $strTheAPIAddress   = "";
    protected      $arrHeaders         = array('GET'=>array ('Accept: application/xml'),
                                                'POST'=>array('Content-Type: application/xml','Accept: application/xml'));
    
    function __construct()
    {
        // Call the Model constructor
        $CI =& get_instance();
        $CI->load->config('api', TRUE);
        $this->strTheAPIAddress .= $CI->config->item('api_url','api').$CI->config->item('api_target','api');
    }
  
    
    private function getHeaders($strMethod,$booSecure = false)
    {
        $arrReturn = $this->arrHeaders[$strMethod];
        if ($booSecure)
        {
            $CI =& get_instance();
            $arrReturn[] = "Authorization: Bearer ".$CI->input->cookie('agc_APIVars_token');
        }
        return $arrReturn;
    }
    
    
    private function getCurlPostOptions($strController,$strMethod,$strPostData,$booSecure = false) 
    {
        $strUrl = $this->strTheAPIAddress.$strController."/".$strMethod;
        
        
        $arrReturn = array(     CURLOPT_URL             => $strUrl,
                                CURLOPT_FAILONERROR     => true,
                                CURLOPT_RETURNTRANSFER  => true,
                                CURLOPT_TIMEOUT         => 600,
                                CURLOPT_HTTPHEADER      => $this->getHeaders('POST',$booSecure),
                                CURLOPT_POSTFIELDS      => $strPostData,
                                CURLINFO_HEADER_OUT     => true
                            );
        
        return $arrReturn;
    }

    
    private function checkURL()
    {
        if (strlen($this->strTheAPIAddress) === 0)
        {
            $CI =& get_instance();
            $CI->session->set_flashdata('strTitle','ERROR API CheckURL');
            $CI->session->set_flashdata('strMessage','The API URL was not set.  The following may be helpful: ');
            redirect('/error/', 'refresh');
        }
    }
    
    private function getCurlGetOptions($strController,$strMethod,$arrGetVars,$booSecure = false) 
    {
        $strUrl = $this->strTheAPIAddress.$strController."/".$strMethod;
        if (count($arrGetVars) > 0)
        {
            $arrGetOptions = array();
            foreach($arrGetVars as $strIndex => $strValue)
            {
                $arrGetOptions[] = $strIndex."=".$strValue;
            }
            $strUrl .= "?".implode('&',$arrGetOptions);
        }
        $arrReturn = array(     CURLOPT_URL             => $strUrl,
                                CURLOPT_FAILONERROR     => true,
                                CURLOPT_RETURNTRANSFER  => true,
                                CURLOPT_TIMEOUT         => 100,
                                CURLOPT_HTTPHEADER      => $this->getHeaders('GET',$booSecure),
                                CURLINFO_HEADER_OUT     => true
                              );
        
        return $arrReturn;
    }
    
    protected function doCurlRequest($resCurl, $strMethod,$xml_expected = false)
    {
        $objReturn = new stdClass;
        $objReturn->response = curl_exec($resCurl); // execute and get response
        $objReturn->error    = curl_error($resCurl);
        $objReturn->info     = curl_getinfo($resCurl);
        
        
        $objReturn->data     = null;
        $objReturn->success  = false;
        
        $arrCurlInfo = curl_getinfo($resCurl);
        
        if ($objReturn->info['http_code'] !== 401)
        {
            
            if ($strMethod === 'GET')
            {
                if (($objReturn->info['http_code'] == 200) && (trim($objReturn->response) != ''))
                {
                    $resDOM = new DOMDocument;
                    $resDOM->preserveWhiteSpace = false;
                    if ($resDOM->loadXML(str_replace('xmlns=', 'ns=', $objReturn->response)))
                    {
                        $resXPath = new DOMXPath($resDOM);
                        $objReturn->data = $resXPath;
                        $objReturn->success  = true;          
                    }
                }
                else
                {
                    if (trim($objReturn->response == ''))
                    {
                        $objReturn->error = "THE API RETURNED A BLANK RESPONSE";
                        log_message('debug', 'AGC-ERROR-API2: THE API RETURNED STATUS 200, BUT BLANK RESPONSE');
                        log_message('debug', 'AGC-ERROR-API2 CURL HEADER: '.$arrCurlInfo['request_header']);
                    }
                }
            }
            else
            {
                if ($strMethod === 'POST')
                {
                    if (($objReturn->info['http_code'] == 200) 
                            && (strlen($objReturn->response)>0)) /*
                            && ($objReturn->response === 'The consultation values have been stored in the database'))*/
                    {
                        $objReturn->success  = true;
                        if ($xml_expected)
                        {
                            $resDOM = new DOMDocument;
                            $resDOM->preserveWhiteSpace = false;
                            if ($resDOM->loadXML(str_replace('xmlns=', 'ns=', $objReturn->response)))
                            {
                                $resXPath = new DOMXPath($resDOM);
                                $objReturn->data = $resXPath;

                            }
                        }
                    }
                }
            }
        }
        else
        {
            
            log_message('error', 'AGC-ERROR-API3: THE API RETURNED STATUS 401 - cURL HEADER FOLLOWS');
            log_message('error', 'CURL HEADER: '.$arrCurlInfo['request_header']);
            
            
        }
        return $objReturn;
    }
    
    
    protected function getCurlData($strController,$strMethod,$arrTheData,$booSecure=false) {
        $resCurl = curl_init();    // create curl resource
        
        $this->checkURL();
        
        curl_setopt_array($resCurl, $this->getCurlGetOptions($strController,$strMethod,$arrTheData,$booSecure)); 
        $objReturn = $this->doCurlRequest($resCurl,'GET');
        if (!$objReturn->success)
        {
            $i = 0;
            while($i < 5)
            {
                log_message('debug', 'AGC-ERROR-API1: THE API CONTACT FAILED - '.$strController.'/'.$strMethod);
                sleep(2);
                $objReturn = $this->doCurlRequest($resCurl,'GET');
                
                if ($objReturn->success)
                {
                    return $objReturn;
                }
                
                $i++;
            }
            $CI =& get_instance();
            $CI->session->set_flashdata('strTitle','ERROR API 1 - '.$strController.'/'.$strMethod);
            $CI->session->set_flashdata('strMessage','The API was uncontactable for 6 tries (including 5 re-tries every 2 seconds).  Please try another time.  The following may be helpful: '.$objReturn->error);
            redirect('/error/', 'refresh');

        }
        else
        {
            return $objReturn;
        }    
    }
    
    protected function sendCurlData($strController,$strMethod,$strTheData,$booSecure=false,$xml_expected=false)
    {
        $resCurl = curl_init();    // create curl resource
        
        $this->checkURL();
        
        curl_setopt_array($resCurl, $this->getCurlPostOptions($strController,$strMethod,$strTheData,$booSecure));
        
        $objReturn = $this->doCurlRequest($resCurl,'POST',$xml_expected);
        if (!$objReturn->success)
        {
            $i = 0;
            while($i < 5)
            {
                
                log_message('debug', 'AGC-ERROR-API1: THE API CONTACT FAILED - '.$strController.'/'.$strMethod);
                sleep(2);
                $objReturn = $this->doCurlRequest($resCurl,'POST');
                
                if ($objReturn->success)
                {
                    return $objReturn;
                }
                
                $i++;
            }
            $CI =& get_instance();
            $CI->session->set_flashdata('strTitle','ERROR API 1 - '.$strController.'/'.$strMethod);
            $CI->session->set_flashdata('strMessage','The API was uncontactable for 6 tries (including 5 re-tries every 2 seconds).  Please try another time.  The following may be helpful: '.$objReturn->error);
            redirect('/error/', 'refresh');

        }
        else
        {
            return $objReturn;
        }
        
        
    }
    
    public function getTimestampFromAPIDate($raw_date)
    {   
        $arrDateTimeParts = explode('T',$raw_date);
        $arrDateParts = explode('-',$arrDateTimeParts[0]);
        $arrTimeParts = explode(':',explode('.',$arrDateTimeParts[1])[0]);
                
        return mktime((int) $arrTimeParts[0]
                                ,(int) $arrTimeParts[1]
                                ,(int) $arrTimeParts[2]
                                ,(int) $arrDateParts[1]
                                ,(int) $arrDateParts[2]
                                ,(int) $arrDateParts[0]);
    }
}