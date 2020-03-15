<?php

class Apiuser_model extends Api_model
{   
    protected $resObjectUser = null;
    
    public function retrieveCurrentUser()
    {
        if (($this->resObjectUser === null) || (!$this->resObjectUser->success))
        {
            $objU = $this->getCurlData('User','GetCurrentUser',array(),true);
            if ($objU)
            {
                $this->resObjectUser = $objU;
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return true;
        }
    }
    
    public function getUserObject()
    {
        $objU = new stdClass();
        $this->resObjectUser = null;
        
        if ($this->retrieveCurrentUser())
        {
            $strQuery = '/User';
            
            $objNodeList = $this->resObjectUser->data->query($strQuery);
            
            $objNode = $objNodeList->item(0);
            
            $objU->Email            = $objNode->getElementsByTagName("Email")->item(0)->nodeValue;
            $objU->FirstName        = $objNode->getElementsByTagName("FirstName")->item(0)->nodeValue;
            $objU->LastName         = $objNode->getElementsByTagName("LastName")->item(0)->nodeValue;
            $objU->Title            = $objNode->getElementsByTagName("Title")->item(0)->nodeValue;
            $objU->UserId           = $objNode->getElementsByTagName("UserId")->item(0)->nodeValue;
            
        }
        
        
        return $objU;
    }
    
    public function retrieveUser($intUserId)
    {
        if (($this->resObjectUser === null) || (!$this->resObjectUser->success))
        {
            $objU = $this->getCurlData('User','GetUserById',array('userId'=>$intUserId),true);
            if ($objU)
            {
                $this->resObjectUser = $objU;
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return true;
        }
    }
    
    public function getUserObjectById($intUserId)
    {
        $objU = new stdClass();
        $this->resObjectUser = null;
        
        
        
        if ($this->retrieveUser($intUserId))
        {
            $strQuery = '/User';
            
            $objNodeList = $this->resObjectUser->data->query($strQuery);
            
            $objNode = $objNodeList->item(0);
            
            $objU->Email            = $objNode->getElementsByTagName("Email")->item(0)->nodeValue;
            $objU->FirstName        = $objNode->getElementsByTagName("FirstName")->item(0)->nodeValue;
            $objU->LastName         = $objNode->getElementsByTagName("LastName")->item(0)->nodeValue;
            $objU->Title            = $objNode->getElementsByTagName("Title")->item(0)->nodeValue;
            $objU->UserId           = $objNode->getElementsByTagName("UserId")->item(0)->nodeValue;
            
        }
        
        
        return $objU;
    }
}