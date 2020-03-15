<?php


class Outcomes_Model extends CI_Model 
{
    public function getStatus($strFlowId)
    {
        
        ///exit($strFlowId);
        if (strlen($strFlowId) > 0)
        {
            $this->db->select('State,Message');
            $this->db->from('Outcomes');
            $this->db->where('FlowID',      $strFlowId);
            $this->db->limit(1);
            $objQuery = $this->db->get();

            if ($objQuery->num_rows() > 0)
            {
                return $objQuery->row();
            }
            else
            {
                $objStatus = new stdClass();
                $objStatus->State       = "Aborted";
                $objStatus->Message     = "Consultation was ended prematurely.";

                return $objStatus;
            }
        }
        else
        {
            $objStatus = new stdClass();
            $objStatus->State       = "Unknown";
            $objStatus->Message     = "System was not able to determine a Status of this Consultation.";
            
            return $objStatus;
        }
    }
    
    
}
    
