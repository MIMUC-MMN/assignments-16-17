<?php

class DBHandler
{
    var $connection;
    function __construct($host,$user,$password,$db){
        $this->connection = new mysqli($host, $user, $password, $db);
        $this->ensureRankingTable();
    }

    function insertResult($player,$guessedword,$attempts){
        date_default_timezone_set("Europe/Berlin");
        if($this->connection){
            $query = "INSERT INTO Ranking(`player`, `guessedword`, `attempts`, `dates`) VALUES (?, ?, ?, ?)";
            $statement = $this->connection->prepare($query);
            $result = $statement->bind_param('ssis', $player, $guessedword, $attempts, date('Y-m-d H:i:s'));
            return $statement->execute();
        }
        return false;
    }
    function ensureRankingTable(){
        if($this->connection){
            $query = "CREATE TABLE IF NOT EXISTS Ranking(
                id INT NOT NULL AUTO_INCREMENT,
                player VARCHAR(255) NOT NULL,
                guessedword VARCHAR(255) NOT NULL,
                attempts INT NOT NULL,
                dates DATE NOT NULL, PRIMARY KEY(id)
            )";
            $this->connection->query($query);
        }
    }
    function fetchResults(){
        $ranking = array();
        if ($this->connection) {
            $query = "SELECT * FROM Ranking";
            $result = $this->connection->query($query);
            while ($row = $result->fetch_assoc()) {
                $ranking[] = array(
                    'id'=>$row['id'],
                    'player'=>$row['player'],
                    'guessedword'=>$row['guessedword'],
                    'attempts'=>$row['attempts'],
                    'dates'=>$row['dates']);
            }
        }
        return $ranking;
    }
}
