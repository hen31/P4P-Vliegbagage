<?php
require("includeAll.php");
/**
 * @Auteur Hendrik de Jonge
 * @Datum 13-5-2013
 */
 
class  DbHandler
{
    
    static public function Query($sql, $parameters)
    {    
        
      try
      {
            //Connectie string klaarmaken
            $connectionString = "mysql:host=" . DATABASE_HOST . ";port=" . DATABASE_PORT . ";dbname=" . DATABASE_NAME . ";charset=utf8";
           //Connectie aanmaken deze variabelen staan in ../Config.php
            $connection = new PDO($connectionString, DATABASE_USERNAME, DATABASE_PASSWORD);
            $result = array();
            if ($statement = $connection->prepare($sql))
            {


                /* Parameters toevoegen */
                if ($parameters != null)
                {
                    foreach ($parameters as $key => &$value)
                    {
                        $statement->bindParam(':' . $key, $value);

                    }
                }
                $statement->execute();
                /* qeury uitvoeren */
                while ($row = $statement->fetch(PDO::FETCH_ASSOC))
                {
                    $result[] = $row;
                }
                $statement = null;
            }
            
            return $result;
        }
        catch (PDOException $exception)
        {
      ErrorLog::LogError($exception->GetMessage(),"DbHandler.php");
                

            return array('null' => null);
        }
    }  
      static public function NonQuery($sql, $parameters)
    {    
        
      try
      {
            //Connectie string klaarmaken
            $connectionString = "mysql:host=" . DATABASE_HOST . ";port=" . DATABASE_PORT . ";dbname=" . DATABASE_NAME . ";charset=utf8";
           //Connectie aanmaken deze variabelen staan in ../Config.php
            $connection = new PDO($connectionString, DATABASE_USERNAME, DATABASE_PASSWORD);
            $result = array();
            if ($statement = $connection->prepare($sql))
            {


                /* Parameters toevoegen */
                if ($parameters != null)
                {
                    foreach ($parameters as $key =>  &$value)
                    {
                        $statement->bindParam(':' . $key, $value);

                    }
                }
                $statement->execute();
                $statement = null;
            }
        }
        catch (PDOException $exception)
        {
             ErrorLog::LogError($exception->GetMessage(),"DbHandler.php");
            return array('null' => null);
        }
    }
    public static function QueryScalar($sql, $parameters)
    {
        try
        {
              //Connectie string klaarmaken
            $connectionString = "mysql:host=" . DATABASE_HOST . ";port=" . DATABASE_PORT . ";dbname=" . DATABASE_NAME . ";charset=utf8";
           //Connectie aanmaken deze variabelen staan in ../Config.php
            $connection = new PDO($connectionString, DATABASE_USERNAME, DATABASE_PASSWORD);
            $result = array();
            if ($statement = $connection->prepare($sql))
            {


                /* bind parameters for markers */
                if ($parameters)
                {
                    foreach ($parameters as $key => &$value)
                    {
                        $statement->bindParam(':' . $key, $value);

                    }
                }
                $statement->execute();
                /* execute query */
                while ($row = $statement->fetch(PDO::FETCH_ASSOC))
                {
                    $result[] = $row;
                }

                $statement = null;
            }
            var_dump($result);
            $keys =array_keys($result);
            $row = $result[$keys[0]];
              $keys =array_keys($row);
            return $row[$keys[0]];
        }
        catch (PDOException $exception)
        {
            ErrorLog::LogError($exception->GetMessage(),"DbHandler.php");
            return null;
        }
    }  
}
?>