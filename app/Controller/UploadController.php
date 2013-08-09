<?php
App::uses('AppController', 'Controller');

class UploadController extends AppController {
    public $uses = array('Stm', 'Upload');
    public $layout = 'admin';

	public function beforeFilter() {
		parent::beforeFilter();
		
	}
	
	public function index() {
		$path  = null;
		$depth = 2;
		$list = $this->Upload->getDirectoryList($path, $depth);
		
		$data = array();
		foreach($list as $k0 => $v0) {
			foreach($v0 as $k1 => $v1) {
				$data[] = $k0 . '/' . $k1;
			}
		}
		//pr($data);
		$this->set('data', $data);
	}
	
	// 同期実行
	public function execute($data, $time) {
		// タイムアウトを無制限にする
		set_time_limit(0);
		// メモリリミットを増やす
		ini_set('memory_limit', '2048M');
		
		echo '<html><head><meta charset="UTF-8"></head><body>';
		
		
		$datetime = $data . '/' . $time;
		//pr($path);
		$list = $this->Upload->getDirectoryList($datetime);
		//pr($list);
		
		$total = count($list) * 2;
		$count = 0;
		
		$success_count = 0;
		$fail_count    = 0;
		$start = time();
		
		// 記録ごとに処理
		foreach($list as $record_id => $filename_list) {
			// オブジェクトと画像に分ける
			$obj_list = array();
			$img_list = array();
			foreach ($filename_list as $filename) {
				$ext = substr($filename, strrpos($filename, '.') + 1);
				if (strtolower($ext) == 'obj') {
					$obj_list[] = $filename;
				} else if (strtolower($ext) == 'png') {
					$img_list[] = $filename;
				}
			}
			//pr($obj_list);
			//pr($img_list);
			
			// 画像アップロード
			ob_start();
			$r = $this->Upload->recordImageAdd($record_id, $datetime, $img_list);
			if ($r) { $success_count++; } else { $fail_count++; }
			$msg = ob_get_contents(); ob_end_clean();
			$count++; $sec = time() - $start; echo sprintf('%05d', $sec). "s [{$count}/{$total}] " . $msg;
			flush(); ob_flush(); // バッファをフラッシュ
			
			// オブジェクトアップロード
			ob_start();
			$r = $this->Upload->recordObjectAdd($record_id, $datetime, $obj_list);
			if ($r) { $success_count++; } else { $fail_count++; }
			$msg = ob_get_contents(); ob_end_clean();
			$count++; $sec = time() - $start; echo sprintf('%05d', $sec). "s [{$count}/{$total}] " . $msg;
			flush(); ob_flush(); // バッファをフラッシュ
			
		}
		
		echo "<br><br>";
		echo "アップロードが完了しました。成功: {$success_count}　失敗: {$fail_count}<br>";
		echo "</body></html>";
		exit;
	}
}

?>
