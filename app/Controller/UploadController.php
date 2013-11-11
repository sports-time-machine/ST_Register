<?php
App::uses('AppController', 'Controller');

class UploadController extends AppController {
    public $uses = array('Stm', 'Upload', 'Remote');
    public $layout = 'admin';

	public function beforeFilter() {
		parent::beforeFilter();
		
		// タイムアウトを無制限にする
		set_time_limit(0);
		// メモリリミットを増やす
		ini_set('memory_limit', '2048M');
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
	
	public function movies() {
		// TODO 一覧リンク表示
		
	}
	public function movie($record_id = null) {
		//pr($this->request->data['record_id']);exit;
		$record_id = $this->request->data['record_id'];
		//echo '<html><head><meta charset="UTF-8"></head><body>';
		
		
		$total = 1;
		$count = 0;
		
		$success_count = 0;
		$fail_count    = 0;
		$start = time();
		
		
		//$record_id = $this->Upload->fixRecordId($record_id); // Gあり
		$record_id = $this->Stm->generateRecordIdWithoutG($record_id); // Gなし
		$path = MOVIE_PATH . DS . $this->Stm->generateMoviePathFromRecordId($record_id);
		//pr($path);
		
		if (!file_exists($path . DS . $record_id . "-1.stmov")) {
			$this->Session->setFlash('データがありません', SET_FLASH_WARNING);
			$this->redirect(array('action' => 'movies'));
		}
		// zipアーカイブを作成
		$zip_file = sys_get_temp_dir() . DS. $record_id . '.zip';
		
		$zip = new ZipArchive();
		$res = $zip->open($zip_file, ZipArchive::CREATE);
		if ($res === true) {
			for ($i = 1; $i <= 6; $i++) {
				$movie_file = $record_id . "-$i.stmov";
				//pr($movie_file);
				if (file_exists($path . DS . $movie_file)) {
					$zip->addFromString($movie_file, file_get_contents($path . DS . $movie_file));
				}
			}
			$zip->close();
		}
		
		
		// ファイルアップロード
		ob_start();
		$r = $this->Upload->recordMovieAdd($record_id, $zip_file);
		if ($r) { $success_count++; } else { $fail_count++; }
		$msg = ob_get_contents(); ob_end_clean();
		$count++; $sec = time() - $start;
		$msg = sprintf('%05d', $sec). "s [{$count}/{$total}] " . $msg;
		//flush(); ob_flush(); // バッファをフラッシュ
		
		// ファイル削除
		unlink($zip_file);
		/*
		echo "<br><br>";
		echo "アップロードが完了しました。成功: {$success_count}　失敗: {$fail_count}<br>";
		echo "</body></html>";
		exit;
		 */
		
		$msg .= "アップロードが完了しました。成功: {$success_count}　失敗: {$fail_count}<br>";
		
		$this->Session->setFlash($msg, SET_FLASH_SUCCESS);
		$this->redirect(array('controller' => 'upload', 'action' => 'movies'));
	}
	
	public function movieWithoutFile($record_id = null) {
		//pr($this->request->data['record_id']);exit;
		$record_id = $this->request->data['record_id'];
		//echo '<html><head><meta charset="UTF-8"></head><body>';
		
		
		$total = 1;
		$count = 0;
		
		$success_count = 0;
		$fail_count    = 0;
		$start = time();
		
		
		//$record_id = $this->Upload->fixRecordId($record_id); // Gあり
		$record_id = $this->Stm->generateRecordIdWithoutG($record_id); // Gなし
		$path = MOVIE_PATH . DS . $this->Stm->generateMoviePathFromRecordId($record_id);
		//pr($path);
		
		/*
		if (!file_exists($path . DS . $record_id . "-1.stmov")) {
			$this->Session->setFlash('データがありません', SET_FLASH_WARNING);
			$this->redirect(array('action' => 'movies'));
		}
		// zipアーカイブを作成
		$zip_file = sys_get_temp_dir() . DS. $record_id . '.zip';
		
		$zip = new ZipArchive();
		$res = $zip->open($zip_file, ZipArchive::CREATE);
		if ($res === true) {
			for ($i = 1; $i <= 6; $i++) {
				$movie_file = $record_id . "-$i.stmov";
				//pr($movie_file);
				if (file_exists($path . DS . $movie_file)) {
					$zip->addFromString($movie_file, file_get_contents($path . DS . $movie_file));
				}
			}
			$zip->close();
		}
		*/
		
		// ファイルアップロード
		ob_start();
		$r = $this->Upload->recordMovieAddWithoutFile($record_id);
		if ($r) { $success_count++; } else { $fail_count++; }
		$msg = ob_get_contents(); ob_end_clean();
		$count++; $sec = time() - $start;
		$msg = sprintf('%05d', $sec). "s [{$count}/{$total}] " . $msg;
		//flush(); ob_flush(); // バッファをフラッシュ
		
		$msg .= "アップロードが完了しました。成功: {$success_count}　失敗: {$fail_count}<br>";
		
		$this->Session->setFlash($msg, SET_FLASH_SUCCESS);
		$this->redirect(array('controller' => 'upload', 'action' => 'movies'));
	}
	
	public function movieAutoUpload() {
		$sql = "SELECT * FROM records
				LEFT JOIN record_movies ON record_movies.record_id = records.id
				WHERE record_movies.record_id IS NULL
				LIMIT 5
				";
		$r = $this->Remote->query($sql);
		//pr($r);
		
		$start = time();
		
		foreach($r as $v) {
			pr($v['records']['record_id']);
		}
		
		exit;
	}
	
	public function movieUploadOne($record_id) {
		$record_id = $this->Stm->generateRecordIdWithoutG($record_id); // Gなし
		$path = MOVIE_PATH . DS . $this->Stm->generateMoviePathFromRecordId($record_id);
		
		if (!file_exists($path . DS . $record_id . "-1.stmov")) {
			pr("ムービーファイルがありません: {$record_id}");
			return;
		}
		// zipアーカイブを作成
		$zip_file = sys_get_temp_dir() . DS. $record_id . '.zip';
		
		$zip = new ZipArchive();
		$res = $zip->open($zip_file, ZipArchive::CREATE);
		if ($res === true) {
			for ($i = 1; $i <= 6; $i++) {
				$movie_file = $record_id . "-$i.stmov";
				//pr($movie_file);
				if (file_exists($path . DS . $movie_file)) {
					$zip->addFromString($movie_file, file_get_contents($path . DS . $movie_file));
				}
			}
			$zip->close();
		}
		
		
		// ファイルアップロード
		ob_start();
		$r = $this->Upload->recordMovieAdd($record_id, $zip_file);
		if ($r) { $success_count++; } else { $fail_count++; }
		$msg = ob_get_contents(); ob_end_clean();
		$count++; $sec = time() - $start;
		$msg = sprintf('%05d', $sec). "s" . $msg;
		//flush(); ob_flush(); // バッファをフラッシュ
		
		// ファイル削除
		unlink($zip_file);
		
		$msg .= "アップロードが完了しました。";
		pr($msg);
	}
	
}

?>
