<?php
App::uses('AppController', 'Controller');

class MirrorController extends AppController {
    public $uses = array('Stm', 'Remote', 'User', 'Record', 'Mirror');
    public $layout = 'admin';

	public function beforeFilter() {
		parent::beforeFilter();
		
	}
	
	public function index(){
		// 選手はnamesにデータがあって is_synced = 0 のものを送りつける
		// OKだったら is_synced = 1 にする
		
		// 記録データは、走った日の最新を取ってきて、それより新しいものを送りつける
		
		$player_data = $this->Mirror->getUserForRemoteUpdate();
		if (!empty($player_data)) {
			$player_lastupdate_time = date('Y-m-d H:i:s', strtotime($player_data[0]['names']['created']));
			$player_update_num = count($player_data);
		} else {
			$player_lastupdate_time = null;
			$player_update_num = 0;
		}
		
		
		// ローカルの記録情報を検索
		$record_data = $this->Mirror->getRecordForRemoteUpdate();
		//pr($record_data);
		if (!empty($record_data)) {
			$record_lastupdate_time = date('Y-m-d H:i:s', strtotime($record_data[0]['records']['register_date']));
			$record_update_num = count($record_data);
		} else {
			$record_lastupdate_time = null;
			$record_update_num = 0;
		}
		
		
		
		
		$this->set('player_lastupdate_time', $player_lastupdate_time);
		$this->set('player_update_num', $player_update_num);
		$this->set('player_data', $player_data);
		
		$this->set('record_lastupdate_time', $record_lastupdate_time);
		$this->set('record_update_num', $record_update_num);
		$this->set('record_data', $record_data);
	}
	
	// 同期実行
	public function execute() {
		// タイムアウトを無制限にする
		set_time_limit(0);
		$player_success_count = 0;
		$record_success_count = 0;
		
		// 選手データ同期
		$player_data = $this->Mirror->getUserForRemoteUpdate();
		if (!empty($player_data)) {
			//pr($player_data);
			//pr($this->User->find('first'));
			//pr($this->RemoteUser->find('first'));
			//exit;
			
			foreach($player_data as $v) {
				// WebAPIで選手を登録
				$a = array();
				$a['User']['player_id'] = $this->Stm->generateShortPlayerId($v['users']['player_id']);
				$a['User']['username' ] = $v['names']['username'];
				$a['Profile']['gender' ] = $v['names']['gender'];
				$a['Profile']['age' ]   = $v['names']['age'];
				//pr($a);
				$json = json_encode($a);
				
				$url = MIRROR_API_URL . 'userSave';
				$options = array(
					'http' => array(
						'method'  => 'POST',
						'content' => http_build_query(array('json' => $json)),
						'header'  => 'Content-Type: application/x-www-form-urlencoded',
					),
				);
				$context = stream_context_create($options);
				$result_json = file_get_contents($url, false, $context);
				$result = json_decode($result_json, true);
				if ($result['code'] == 200) {
					$player_success_count++;
					// 成功したら同期フラグを立てる
					$r = $this->User->findByPlayer_id($v['users']['player_id']);
					$r['User']['is_synced'] = 1;
					$this->User->save($r, false);
				}
				//pr($result);
			}
			//pr($player_success_count);
		}
		
		
		// 記録データ同期
		$record_data = $this->Mirror->getRecordForRemoteUpdate();
		if (!empty($record_data)) {
			//pr($record_data);
			//pr($this->User->find('first'));
			//pr($this->RemoteUser->find('first'));
			//exit;
			
			foreach($record_data as $v) {
				// WebAPIで記録を登録
				$record = $this->Stm->record($v['records']['record_id']);
				$record['Record']['md5hex'] = $this->Stm->generateRecordMd5($record);
				//pr($record); exit;
				
				$json = json_encode($record);
				
				$url = MIRROR_API_URL . 'recordSave';
				$options = array(
					'http' => array(
						'method'  => 'POST',
						'content' => http_build_query(array('json' => $json)),
						'header'  => 'Content-Type: application/x-www-form-urlencoded',
					),
				);
				$context = stream_context_create($options);
				$result_json = file_get_contents($url, false, $context);
				$result = json_decode($result_json, true);
				if ($result['code'] == 200) {
					$record_success_count++;
					// 成功したら同期フラグを立てる
					$r = $this->Record->findByRecord_id($v['records']['record_id']);
					$r['Record']['is_synced'] = 1;
					$this->Record->save($r, false, array('is_synced'));
				}
				//pr($result);
			}
			//pr($record_success_count);
		}
		//exit;
		
		$msg = @"選手 {$player_success_count} 件、記録 {$record_success_count} 件 の同期を実行しました。";
		$this->log($msg);
		$this->Session->setFlash($msg, SET_FLASH_INFO);
		$this->redirect(array('action' => 'index'));
	}
}

?>
