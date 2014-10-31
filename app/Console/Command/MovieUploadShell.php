<?php
class MovieUploadShell extends Shell {
	public $uses = array('Upload', 'Remote');

	public function _welcome() {
	}

	public function startup() {
		parent::startup();
	}

	public function main() {
		$this->printScpUploadCommand();
	}
	public function uploadAsc() {
		$sql = "SELECT records.record_id FROM records
			LEFT JOIN record_movies ON record_movies.record_id = records.id
			WHERE record_movies.record_id IS NULL
			ORDER BY records.register_date ASC
			LIMIT 1000
			";
		$r = $this->Remote->query($sql);
		$msg = print_r($r, true);

		foreach($r as $v) {
			pr($v['records']['record_id']);
			//$this->movieUploadOne($v['records']['record_id']);
		}
		$this->out($msg);
	}
	public function uploadDesc() {
	}
	public function uploadOne($record_id) {
		
	}
	
	public function printScpUploadCommand() {
		$files = $this->getZippedMovieRecordId();
		$msg = array();
		foreach ($files as $file) {
			$record_id = substr($file, 0, stripos($file, '.zip'));
			$cmd = $this->Upload->getScpUploadCommand($record_id);
			if (!is_null($cmd)) {
				$msg[] = $cmd;
				$msg[] = "sleep 3\n";
			}
		}
		$msg = implode("\n", $msg);
		$this->out($msg);
	}
	public function regist() {
		$files = $this->getZippedMovieRecordId();
		$msg = array();
		foreach ($files as $file) {
			$record_id = substr($file, 0, stripos($file, '.zip'));
			$result = $this->Upload->recordMovieAddWithoutFile($record_id);
			if ($result === true) {
				$msg[] = "rm -f {$record_id}.zip" . "\n";
			} else {
				$msg[] = "; register {$record_id} failed." . "\n";
			}
		}
		$msg = implode("\n", $msg);
		$this->out($msg);
	}




	// 10分前までに作られたzipファイル一覧
	public function getZippedMovieRecordId($min = 10) {
		$files = array();
		$tmp_dir = sys_get_temp_dir();
		$handle = opendir($tmp_dir);
		if ($handle) {
			while (false !== ($file = readdir($handle))) {
				if (!is_file($tmp_dir . DS . $file)) {
					continue;
				}
				if (filemtime($tmp_dir . DS . $file) > time() - $min * 60) {
					continue;
				}
				if (preg_match('/.zip$/', $file)) {
					$files[] = $file;
				}
			}
		}
		return $files;
	}
}
