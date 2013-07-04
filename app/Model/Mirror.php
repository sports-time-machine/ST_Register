<?php
App::uses('AppModel', 'Model');

// 同期機能用
class Mirror extends AppModel
{
	public $name = 'Mirror';
	public $useTable = false;
	
	// コンストラクタ
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		
		$this->loadModel(array('Remote', 'User', 'Stm'));
	}
	
	// リモートに同期する選手データを取得
	public function getUserForRemoteUpdate() {
		$sql = "SELECT
					users.player_id, users.is_synced, names.username, names.created
				FROM
					names
				LEFT JOIN
					users ON names.user_id = users.id
				WHERE
					users.is_synced = 0
				ORDER BY
					names.created ASC
				";
		$data = $this->User->query($sql);
		
		return $data;
	}
	
	// リモートの最終記録データを取得
	public function getLastRemoteRecordTimestamp() {
		$sql = "SELECT * FROM records ORDER BY register_date DESC LIMIT 1";
		$r = $this->Remote->query($sql);
		if (!empty($r)) {
			$timestamp = $r[0]['records']['register_date'];
		} else {
			$timestamp = '2013-07-05 00:00:00'; // 7月5日以降のデータを同期対象にする
		}
		
		return $timestamp;
	}
	
	// リモートに同期する記録データを取得
	public function getRecordForRemoteUpdate($timestamp = null) {
		if (is_null($timestamp)) {
			$timestamp = $this->getLastRemoteRecordTimestamp();
		}
		$sql = "SELECT * FROM records WHERE '{$timestamp}' < register_date ORDER BY register_date DESC";
		$data = $this->Stm->query($sql);
		
		return $data;
	}
	
}
