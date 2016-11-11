<?php

require_once './Utils.php';

abstract class DBConnection
{
  // --- ATTRIBUTES ---

  /**
   * Reference to the MySQLi-Database that is
   * accessed by all operations of the class.
   */
  protected $_database = null;

  // --- OPERATIONS ---

  /**
   * Connects to DB and stores
   * the connection in member $_database.
   * Needs name of DB, user, password.
   *
   * @return none
   */
  protected function __construct()
  {
    //$this->_database = /* to do: create instance of class MySQLi */;
    $hostname="localhost";
    $username="mmn";
    $password="mmn";
    $databaseName="notetaker";
    $this->_database = new mysqli($hostname, $username, $password, $databaseName);
    if($this->_database->connect_error){
      printf("Verbindung fehlgeschlagen:%s\n", mysqli_connect_error());
    }
    Utils::start_session_onlyif_no_session();
    error_reporting(E_ALL);
  }

  /**
   * Closes the DB connection and cleans up
   *
   * @return none
   */
  protected function __destruct()
  {
    // to do: close database
    $this->_database->close();
  }


  /**
   * useful to sanitize data before trying to insert it into the database.
   * @param $string String to be escaped from malicious SQL statements and script tags
   * @return $string sanitized string
   */
  public function sanitize_input($string){
    return $this->_database->real_escape_string(htmlspecialchars($string));
  }

}