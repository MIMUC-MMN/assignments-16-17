<?php

require_once './DBConnection.php';
require_once './Utils.php';

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
    return $this->_database->real_escape_string($string);
  }

  /**
   * creates a database record for the given $username and $password.
   * @param $username String name of the user
   * @param $password String password of the user
   * @return string message to inform about the result of the db operation
   */
  function add_user($username,$password){

    $hashed = password_hash($password,PASSWORD_DEFAULT);

    if($this->_database){
      $sql = "
INSERT INTO `notetaker`.`user` 
(`id`, `username`, `password`) 
VALUES (NULL, '$username', '$hashed');";

      if(!$this->_database->query($sql)) {
        $is_username_taken = Utils::contains('Duplicate', $this->_database->error);
        if($is_username_taken)
          return "Username already exists, please choose another name!";
        else
          return $this->_database->error;
      }
      else {
        return "Successfully registered, now you can log in!";
      }
    }
    return $this->_database->error;
  }


  function find_user_by_name($name){
    if($this->_database){
      $sql = "
SELECT *
FROM `notetaker`.`user` u
WHERE u.`username` = '$name';";

      if($users = $this->_database->query($sql)) {
        return $users->fetch_assoc();
      }
      else {
        return $this->_database->error;
      }
    }
    return $this->_database->error;
  }

}