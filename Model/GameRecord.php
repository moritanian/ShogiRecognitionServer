<?php
require_once( 'Model/ModelBase.php');

class GameRecord extends ModelBase
{
	protected $tableName = 'game_record';

    public function insertData($data){
        $sql = sprintf('INSERT INTO %s  (game_id, epoch, position, target, is_promotion, revival, kihu, create_time) values (:game_id, :epoch, :position, :target, :is_promotion, :revival, :kihu, :create_time)', $this->tableName);   
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':game_id', $data['game_id']);
        $stmt->bindValue(':epoch', $data['epoch']);
        $stmt->bindValue(':position', $data['position']);
        $stmt->bindValue(':target', $data['target']);
        $stmt->bindValue(':is_promotion', $data['is_promotion']);
        $stmt->bindValue(':revival', $data['revival']);
        $stmt->bindValue(':kihu', $data['kihu']); 
        $stmt->bindValue(':create_time', $data['create_time']);
        $res = $stmt->execute();
        return $res;
    }

    public function GetByGameid($game_id){
        $sql = sprintf('SELECT * FROM %s where game_id = :game_id ', $this->tableName);
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':game_id', $game_id);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if(!isset($rows[0]))return null;
        return $rows;
    }

    public function GetByGameidAndEpoch($game_id, $from, $to){
        $sql = sprintf('select * from %s where game_id = :game_id AND epoch between :from and :to ORDER BY epoch', $this->tableName);
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':game_id', $game_id);
        $stmt->bindValue(':from', $from);
        $stmt->bindValue(':to', $to);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if(!isset($rows[0]))return null;
        return $rows;
    }
}

?>