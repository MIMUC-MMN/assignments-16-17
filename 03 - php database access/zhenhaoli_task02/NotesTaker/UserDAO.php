<?php
require_once './DBConnection.php';

class UserDAO extends DBConnection
{

  function __construct()
  {
    parent::__construct();
  }

  function __destruct()
  {
    parent::__destruct();
  }

  /**
   * useful to sanitize data before trying to insert it into the database.
   * @param $string String to be escaped from malicious SQL statements
   * @return $string sanitized string
   */
  function sanitize_input($string){
    return $this->connection->real_escape_string($string);
  }

  /**
   * creates a database record for the given $username and $password.
   * @param $username String name of the user
   * @param $password String password of the user
   * @return bool true for success, false for error
   */
  function add_user($username,$password){

    $hashed = password_hash($password,PASSWORD_DEFAULT);

    if($this->_database){
      $sql = "
INSERT INTO `notetaker`.`user` 
(id, username, password) 
VALUES (NULL, $username, $hashed);";

      if($this->_database->query($sql))
        return true;

    }
    return false;
  }

}