<?php

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
    $username="root";
    $password="1234";
    $databaseName="pizzaservice";
    $this->_database = new mysqli($hostname, $username, $password, $databaseName);
    if($this->_database->connect_error){
      printf("Verbindung fehlgeschlagen:%s\n", mysqli_connect_error());
    }
    session_start();
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

}