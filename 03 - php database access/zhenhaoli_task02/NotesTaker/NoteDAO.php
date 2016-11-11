<?php

require_once './DBConnection.php';
require_once './Utils.php';

class NoteDAO extends DBConnection
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
   * creates a database record for the given $username and $password.
   * @param $username String name of the user
   * @param $password String password of the user
   * @return string message to inform about the result of the db operation
   */
  function add_note($title, $text, $userid){

    if($this->_database){
      $sql = "
INSERT INTO `notetaker`.`note` 
(`id`, `title`, `text`, `userid`) 
VALUES (NULL, '$title', '$text', '$userid');";

      if(!$this->_database->query($sql)) {
        return $this->_database->error;
      }
      else {
        return "Successfully added note!";
      }
    }
    return $this->_database->error;
  }

  function edit_note_by_id($id, $title, $text){
    if($this->_database){
      $sql = "
UPDATE `note` 
SET `title` = '$title', `text` = '$text' 
WHERE `note`.`id` = $id;";

      if(!$this->_database->query($sql)) {
        return $this->_database->error;
      }
      else {
        return "Successfully updated note!";
      }
    }
    return $this->_database->error;
  }


  /**
   * find a user record by the username
   * @param $name String name of the user
   * @return mixed message to inform about the result of the db operation or the result
   */
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

  function find_all_notes_by_userid($userid)
  {
    if ($this->_database) {
      $sql = "
SELECT *
FROM `notetaker`.`note` n
WHERE n.`userid` = '$userid';";

      if ($notes = $this->_database->query($sql)) {
        $fetched_notes = [];
        while ($fetched_notes [] =  $notes->fetch_assoc());
        return $fetched_notes;
      } else {
        return $this->_database->error;
      }
    }
    return $this->_database->error;
  }

}