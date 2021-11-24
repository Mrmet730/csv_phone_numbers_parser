<?php
class numeri{
    
    
 function getRows() {
     
        $sql_ = "SELECT * FROM sms_numbers";
       
		
        try {
            $database = new Database();
            $database->query($sql_);
            $Vdata=$database->resultset();
            
	        return $Vdata;
	        } 
        catch (PDOException $e) {
	            echo $e->getMessage();
	        }
    }
 
    function insertRow($ID, $number, $type)
    {
        $res = $this->getRowByID($ID);
        if($res)
        {
            return false;
        }
        else
        {
            $numero = intval($number);
            $sql_ = "INSERT INTO sms_numbers (ID, sms_number, type) VALUES (:ID, :number, :type)";

            try {
                $database = new Database();
                $database->query($sql_);
                $database->bind(':ID', $ID);
                $database->bind(':number', $numero);
                $database->bind(':type', $type);
                $database->execute();
            
	            } catch (PDOException $e) {
	                echo $e->getMessage();
	            }
        }
    }

    function getRowByID($ID)
    {
        $sql_ = "SELECT * FROM sms_numbers WHERE ID=:ID";
       
		
        try {
            $database = new Database();
            $database->query($sql_);
            $database->bind(':ID', $ID);
            $Vdata=$database->single();
            
	        return $Vdata;
	        } 
        catch (PDOException $e) {
	            echo $e->getMessage();
	        }
    }

    function checkNumber($number)
    {
        if(strlen($number)==11 && substr($number, 0, 2)=="27" && ctype_digit($number))
        {
            return 0;
        }
        elseif(strlen($number)==9 && ctype_digit($number))
        {
            return 2;
        }
        else
        {
            return 1;
        }
    }

    function correctNumber($number)
    {
        $str = "27".$number;
        return $str;
    }
  
}

 
