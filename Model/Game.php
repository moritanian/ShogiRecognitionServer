<?php
require_once( 'Model/ModelBase.php');

class Game extends ModelBase
{
	protected $tableName = 'game';

    public function createGame($data){
        $sql = sprintf('INSERT INTO %s (description, epoch, first_player_name, second_player_name, start_time, winner) values (:description, 0, :first_player_name, :second_player_name, :start_time, 0)', $this->tableName);
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':description', isset($data['description']) ? $data['description'] : "");
        $stmt->bindValue(':first_player_name', isset($data['first_player_name']) ? $data['first_player_name'] : "");
        $stmt->bindValue(':second_player_name', isset($data['second_player_name']) ? $data['second_player_name'] : "");
        $stmt->bindValue(':start_time', $data['start_time']);
        $res = $stmt->execute();

        $sql = 'SELECT LAST_INSERT_ID()';
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute();
        $row = $stmt->fetch();
        return $row["LAST_INSERT_ID()"];
    }

    public function updateGame($data){
        $sql = sprintf('UPDATE %s SET epoch = :epoch where id = :game_id', $this->tableName);
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':epoch', $data['epoch']);
        $stmt->bindValue(':game_id', $data['game_id']);
   
        $res = $stmt->execute();
        return $res; 
    }

	public function EndGame($data){

		$sql = sprintf('INSERT INTO %s  (id, winner, end_time) values (:id, :winner, :end_time)', $this->tableName);
               
		$stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $data['game_id']);
        $stmt->bindValue(':winner', $data['winner']);
        $stmt->bindValue(':end_time', $data['end_time']);
        $res = $stmt->execute();
		return $res;
	}

    public function insertData($data){

    
    }

    public function GetAll(){
        $sql = sprintf('SELECT * FROM %s ORDER BY start_time', $this->tableName);
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();
        return $rows;
    }

    public function GetById($id){
        $sql = sprintf('SELECT * FROM %s where id = :id ', $this->tableName);
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if(!isset($rows[0]))return null;
        return $rows;
    }
}

?>