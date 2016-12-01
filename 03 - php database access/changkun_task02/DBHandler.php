<?php
class Note{
    var $id;
    var $title;
    var $content;
    function __construct($id=-1,$title,$content){
        $this->id=$id;
        $this->title=$title;
        $this->content=$content;
    }
}

class DBHandler{
    var $connection;

    function __construct($host,$user,$password,$db){
        $this->connection = new mysqli($host,$user,$password,$db);
        $this->connection->set_charset('utf8'); // for charset error
        $this->_ensureUsersTable();
        $this->_ensureNotesTable();
    }
    function insertUser($userName, $password){
        assert($this->connection);
        $queryString = "INSERT INTO users (`name`,`password`) VALUES (?,?)";
        $statement = $this->connection->prepare($queryString);
        $statement->bind_param("ss",$userName,$password);
        return $statement->execute();
    }
    function getUserByUserName($userName){
        assert($this->connection);
        $queryString = "SELECT `id`,`name`,`password` AS `hash` FROM `users` WHERE `name`=?";
        $statement = $this->connection->prepare($queryString);
        $statement->bind_param("s",$userName);
        $statement->execute();
        $statement->bind_result($id,$name,$hash);
        $statement->fetch();
        return array("id"=>$id,"name"=>$name,"hash"=>$hash);
    }
    function insertNote($title,$content,$username){
        assert($this->connection);
        if(strlen($content)>0){
            $queryString = "INSERT INTO notes (`title`,`content`,`user`) VALUES(?,?,?)";
            $statement = $this->connection->prepare($queryString);
            $statement->bind_param("sss",$title,$content,$username);
            return $statement->execute();
        }
        return false;
    }
    function deleteNotes($noteIdArray){
        assert($this->connection);
        $param = implode(',',$noteIdArray);
        $queryString = "DELETE FROM notes WHERE `id` IN (".$param.")";
        return $this->connection->query($queryString);
    }
    function getNotesByUserID($userID){
        if(!isset($userID)){
            $userID = $_SESSION['userID'];
        }
        if(!isset($userID)) return array();

        $queryString = "SELECT id, title, content FROM notes WHERE `user`=?";
        $statement = $this->connection->prepare($queryString);
        $statement->bind_param("i",$userID);
        $statement->execute();
        $result = array();
        $statement->bind_result($id, $title, $content);
        while($statement->fetch()){
            $result[] = new Note($id,$title,$content);
        }
        return $result;
    }
    function _ensureUsersTable(){
        assert($this->connection);
        $queryString =  "CREATE TABLE IF NOT EXISTS users(
            id INT(5) PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL)";
        $this->connection->query($queryString);
    }
    function _ensureNotesTable(){
        // TODO
        assert($this->connection);
        $queryString =  "CREATE TABLE IF NOT EXISTS notes (id INT(5) PRIMARY KEY AUTO_INCREMENT, ".
            "title VARCHAR(255), content TEXT(255) NOT NULL, user INT(5))";
        $this->connection->query($queryString);
    }
}
?>
