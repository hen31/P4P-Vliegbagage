<?php

class ErrorLog{
    
    static public function LogError($errmsg,$page)
    {
          if (DEBUG){
                echo $errmsg;
                }
            else{
                echo ERROR_MSG; //user friendly message
                }
        DbHandler::NonQuery("INSERT INTO errorlog(page, error_msg) VALUES(:pa,:err)",
        array("pa"=>$errmsg,"err"=>$page));
    
    }
}

?>