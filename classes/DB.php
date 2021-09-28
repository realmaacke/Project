<?php

class DB{

  private static function connect()
  {
    // Connecting to database
    $pdo = new PDO('mysql:host=localhost;dbname=project;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;  // returning the connection
  }

  public static function query($query, $params = array()) // querrying db and returning fetch all in $data
  {
    $statement = self::connect()->prepare($query);
    $statement->execute($params);

    if(explode(' ', $query)[0] == 'SELECT') // Error fix with query function
    { // so duplicate accounts wont exist
      $data = $statement->fetchAll();
      return $data;
    }
  }



}
