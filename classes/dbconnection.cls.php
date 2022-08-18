<?php
  class DbConnection
    {
      protected function connect()
        {
          try
            {
              $dbUsername = "root";
              $dbPassword = "";
              $dbHandler = new PDO('mysql:host=localhost;dbname=db_kafka', $dbUsername, $dbPassword);
              return $dbHandler;
            }
          catch (PDOException $e)
            {
              print "Error: " . $e->getMessage() . "<br/>";
              die();
            }
        }
    }
