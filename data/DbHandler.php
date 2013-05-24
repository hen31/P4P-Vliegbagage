<?php
require("includeAll.php");
/**
 * @Auteur Hendrik de Jonge
 * @Datum 13-5-2013
 * @uses Wordt gebruikt om queries uit te voeren op de database
 */
 
class  DbHandler
{
    //een query uitvoeren en het resultaat terug geven
    static public function Query($sql, $parameters = null)
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
    //wordt gebruikt om een query uit te voeren zonder dat er resultaat terug komt.
      static public function NonQuery($sql, $parameters = null)
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
    //wordt gebruikt om een query uit te voeren en er komt een colum aan resultaten terug.
    public static function QueryScalar($sql, $parameters = null)
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
                 if ($parameters != null)
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
//$fetchPictures->bindValue(':skip', (int) trim($_GET['skip']), PDO::PARAM_INT);
static public function QueryLimit($sql, $parameters = null, $limitBegin,$limitEnd)
    {    
        
      try
      {
            //Connectie string klaarmaken
            $connectionString = "mysql:host=" . DATABASE_HOST . ";port=" . DATABASE_PORT . ";dbname=" . DATABASE_NAME . ";charset=utf8";
           //Connectie aanmaken deze variabelen staan in ../Config.php
            $connection = new PDO($connectionString, DATABASE_USERNAME, DATABASE_PASSWORD);
            $result = array();
            if ($statement = $connection->prepare($sql . " LIMIT :begin :end"))
            {


                /* Parameters toevoegen */
                if ($parameters != null)
                {
                    foreach ($parameters as $key => &$value)
                    {
                        $statement->bindParam(':' . $key, $value);

                    }
                }
                $statement->bindValue(':begin', (int) trim($limitBegin), PDO::PARAM_INT);
                $statement->bindValue(':end', (int) trim($limitEnd), PDO::PARAM_INT);
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
}
?>