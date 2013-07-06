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
	
	// リモートに同期する記録データを取得
	public function getRecordForRemoteUpdate() {
		$sql = "SELECT * FROM records WHERE player_id IS NOT NULL AND is_synced = 0 ORDER BY register_date";
		$data = $this->Stm->query($sql);
		
		return $data;
	}
	
}
