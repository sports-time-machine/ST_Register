<?php
App::uses('ApiController', 'Controller');

/**
 * ApiController Test Case
 *
 */
class ApiControllerTest extends ControllerTestCase {
	var $start_time_ms = 0.000;
/**
 * test case startup
 *
 * @return void
 */
	public static function setupBeforeClass() {
	}
/**
 * cleanup after test case.
 *
 * @return void
 */
	public static function teardownAfterClass() {
	}

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Stm = ClassRegistry::init('Stm');
		// テーブルを空にする
		$this->Stm->query('DELETE FROM users;');
		$this->Stm->query('DELETE FROM profiles;');
		$this->Stm->query('DELETE FROM records;');
		$this->Stm->query('DELETE FROM record_images;');
		$this->Stm->query('DELETE FROM partners;');
		$this->Stm->query('DELETE FROM images;');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
	}
	
	// 標準出力と実行時間(s)を返す
	public function myTestAction($url, $params = array()) {
		ob_start();
		
		$this->start_time_ms = microtime(true);
		parent::testAction($url, $params);
		$t = sprintf("%.3f", microtime(true) - $this->start_time_ms);
		
		$r = ob_get_contents();
		ob_end_clean();
		
		$b = number_format(strlen($r));
		echo ": $t s / $b bytes";
		return $r;
	}
	
	
	public function test_Api() {
		// API メソッドが存在しない場合はエラー
		echo "API not found";
		$data = array('key' => 'value');
		$r = $this->myTestAction('/api/hoge', array('data' => $data, 'method' => 'post'));
		pr($r);
		$expected = array(
			'code' => '401',
			'result' => array('message' => 'API Method was not found', 'data' => null),
			);
		$this->assertEquals($expected, json_decode($r, true));
		
		
		// 適切なデータを渡さなかった場合はエラー
		echo "userSave - NG";
		$array = array();
		$data = array('json' => json_encode($array));
		$r = $this->myTestAction('/api/userSave', array('data' => $data, 'method' => 'post'));
		pr($r);
		$expected = array(
			'code' => '402',
			'result' => array('message' => 'No data posted', 'data' => null),
		);
		$this->assertEquals($expected, json_decode($r, true));
		
		
		// 選手データ登録
		echo "userSave - OK";
		$array = array(
			'User' => array(
				'player_id'  => 'ABCD',
				'username' => 'やまぐちたろう',
			),
		);
		$data = array('json' => json_encode($array));
		$r = $this->myTestAction('/api/userSave', array('data' => $data, 'method' => 'post'));
		pr($r);
		$expected = array(
			'code' => '200',
			'result' => array('message' => 'success', 'data' => null),
			);
		$this->assertEquals($expected, json_decode($r, true));
		
		
		// テスト用画像データ
		$image = base64_encode(file_get_contents(APP . 'webroot' . DS . 'img' . DS . 'test-pass-icon.png'));
		//pr($image);
		
		// 記録データ登録
		echo "recordSave - OK";
		$array = array(
			'User' => array( // 選手を特定するデータ
				'player_id' => 'ABCD',			// 選手ID 文字列
				),
			'Record' => array( // 走った記録
				'player_id' => 'ABCD',			// 選手ID 文字列
				'record_id' => 'ABCD3',			// 記録ID(QRコード)
				'movie_path'   => '',			// 動画のパス 文字列
				'movie_length' => '',			// 動画の長さ 文字列 
				'register_date' => '',			// 登録日 'YYYY-MM-DD HH:MM:SS' 空なら現在時刻を生成
				'data' => '',					// 記録データ 文字列
				'tags' => '小学生,男子',			// タグ カンマ区切りの文字列
				'comment' => '',				// コメント 文字列
				'pattern' => 'まだら',			// 自分の色 文字列
				'sound' => 'ぽっぷ',				// 音 文字列
				'pattern' => '宇宙',				// 背景 文字列
				),
			'Partner' => array( // 一緒に走った相手
				0 => array(
					'partner_id' => 'ABCD2',			// 一緒に走った相手のID 文字列
					),
				1 => array(
					'partner_id' => 'ABCD3',			// 一緒に走った相手のID 文字列
					),
				// ... 当面は1人
				),
			'Image' => array( // 画像 6枚
				0 => array(
					'filename' => 'ABCD-1',		// ファイル名 文字列
					'ext' => 'png',				// 拡張子 文字列
					'mime' => 'image/png',		// jpgはimage/jpeg、pngはimage/png
					'width' => 1024,			// 画像の幅 数値
					'height' => 768,			// 画像の高さ 数値
					'data' => $image,			// 画像データをBASE64エンコードしたもの 文字列
					),
				1 => array(
					'filename' => 'ABCD-2',		// ファイル名 文字列
					'ext' => 'png',				// 拡張子 文字列
					'mime' => 'image/png',		// jpgはimage/jpeg、pngはimage/png
					'width' => 1024,			// 画像の幅 数値
					'height' => 768,			// 画像の高さ 数値
					'data' => $image,			// 画像データをBASE64エンコードしたもの 文字列
					),
				// ... 6枚登録？
				),
			);
		$array['Record']['md5hex'] = md5($array['Record']['player_id'] . ', ' . $array['Record']['record_id'] . ', ' . $array['Record']['register_date']);
		
		$data = array('json' => json_encode($array));
		//$r = $this->myTestAction('/api/recordSaveDebug', array('data' => $data, 'method' => 'post'));
		$r = $this->myTestAction('/api/recordSave', array('data' => $data, 'method' => 'post'));
		pr($r);
		$expected = array(
			'code' => '200',
			'result' => array('message' => 'success', 'data' => null),
			);
		$this->assertEquals($expected, json_decode($r, true));
		
		
		// 同じ記録は2回登録できない
		echo "recordSave - NG";
		$r = $this->myTestAction('/api/recordSave', array('data' => $data, 'method' => 'post'));
		pr($r);
		$expected = array(
			'code' => '405',
			'result' => array('message' => 'exist data', 'data' => null),
			);
		$this->assertEquals($expected, json_decode($r, true));
		
	}
}
