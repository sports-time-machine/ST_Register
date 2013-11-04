<?php
App::uses('AppModel', 'Model');

// オブジェクトデータアップロード用
class Upload extends AppModel
{
	public $name = 'Upload';
	public $useTable = false;
	
	// コンストラクタ
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		
		$this->BASE_DIR = "C:\\ST-output";
		//$this->loadModel(array('Remote', 'User', 'Stm'));
	}
	
	// アップロード対象ディレクトリの一覧
	public function getDirectoryList($date = null, $depth = -1) {
		$list = $this->_getSubDirectoryList($this->BASE_DIR . DS . $date, $depth);
		return $list;
	}
	
	public function _getSubDirectoryList($path, $depth) {
		$list = array();
		if ($depth == 0) {
			return;
		} else if (0 < $depth) {
			$depth--;
		}
		
		if ($handle = opendir($path)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != '.' && $file != '..') {
					if (is_dir($path . DS . $file)) {
						$list[$file] = $this->_getSubDirectoryList($path . DS . $file, $depth);
					} else {
						$list[] = $file;
					}
				}
			}
			closedir($handle);
		}

		return $list;
	}
	
	// WebAPIで画像を追加
	public function recordImageAdd($record_id, $datetime, $file_list) {
		$a = array();
		$a['Record']['record_id'] = $this->fixRecordId($record_id);
		foreach($file_list as $k => $filename) {
			$image = base64_encode(file_get_contents($this->BASE_DIR . DS . $datetime . DS . $record_id . DS . $filename));

			$f = explode('.', $filename);
			$a['Image'][$k]['filename'] = $this->fixRecordId($f[0]);
			$a['Image'][$k]['ext']      = $f[1];
			$a['Image'][$k]['mime']     = 'iamge/png';
			$a['Image'][$k]['data']     = $image;
			
		}
		
		//pr($a);exit;
		$json = json_encode($a);

		$url = MIRROR_API_URL . 'recordImageAdd';
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
			$msg = "{$record_id} recordImageAdd success";
			$this->log($msg);
			echo $msg . '<br><br>';
			$this->log($msg);
			return true;
		} else {
			$msg = "{$record_id} recordImageAdd fail. {$result['code']}: {$result['result']['message']}";
			$this->log($msg);
			echo $msg . '<br><br>';
			$this->log($msg);
			return false;
		}
	}
	
	// WebAPIでオブジェクトを追加
	public function recordObjectAdd($record_id, $datetime, $file_list) {
		$a = array();
		$a['Record']['record_id'] = $this->fixRecordId($record_id);
		foreach($file_list as $k => $filename) {

			$f = explode('.', $filename);
			$a['Object'][$k]['filename'] = $this->fixRecordId($f[0]);
			$a['Object'][$k]['ext']      = $f[1];
			$a['Object'][$k]['mime']     = 'application/octet-stream';
			$a['Object'][$k]['data']     = base64_encode(file_get_contents($this->BASE_DIR . DS . $datetime . DS . $record_id . DS . $filename));
			
		}
		
		//pr($a);exit;
		//$json = json_encode($a);

		$url = MIRROR_API_URL . 'recordObjectAdd';
		$options = array(
			'http' => array(
				'method'  => 'POST',
				'content' => http_build_query(array('json' => json_encode($a))),
				'header'  => 'Content-Type: application/x-www-form-urlencoded',
			),
		);
		$context = stream_context_create($options);
		$result_json = file_get_contents($url, false, $context);
		$result = json_decode($result_json, true);
		if ($result['code'] == 200) {
			$msg = "{$record_id} recordObjectAdd success";
			echo $msg . '<br><br>';
			$this->log($msg);
			return true;
		} else {
			$msg = "{$record_id} recordObjectAdd fail. {$result['code']}: {$result['result']['message']}";
			echo $msg . '<br><br>';
			$this->log($msg);
			return false;
		}
	}
	
	// WebAPIでオブジェクトを追加
	public function recordMovieAdd($record_id, $zip_path) {
		$a = array();
		$a['Record']['record_id'] = $this->fixRecordId($record_id);

		$f = explode('.', $record_id . '.zip');
		$a['Movie'][0]['filename'] = $this->fixRecordId($f[0]);
		$a['Movie'][0]['ext']      = $f[1];
		$a['Movie'][0]['mime']     = 'application/octet-stream';
		$a['Movie'][0]['data']     = base64_encode(file_get_contents($zip_path));
		$a['Movie'][0]['size']     = filesize($zip_path);

		
		//pr($a);exit;
		//$json = json_encode($a);

		$url = MIRROR_API_URL . 'recordMovieAdd';
		$options = array(
			'http' => array(
				'method'  => 'POST',
				'content' => http_build_query($a),
				'header'  => 'Content-Type: application/x-www-form-urlencoded',
			),
		);
		$context = stream_context_create($options);
		$result_json = file_get_contents($url, false, $context);
		$result = json_decode($result_json, true);
		if ($result['code'] == 200) {
			$msg = "{$record_id} recordMovieAdd success";
			echo $msg . '<br><br>';
			$this->log($msg);
			return true;
		} else {
			$msg = "{$record_id} recordMovieAdd fail. {$result['code']}: {$result['result']['message']}";
			echo $msg . '<br><br>';
			$this->log($msg);
			return false;
		}
	}
	
	// 先頭にGが付いていなかったらGを付与する
	public function fixRecordId($record_id) {
		if (stripos($record_id, 'G') !== 0) {
			$record_id = 'G' . $record_id;
		}
		return $record_id;
	}
	
}
