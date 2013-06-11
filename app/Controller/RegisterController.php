<?php
App::uses('AppController', 'Controller');

class RegisterController extends AppController {
	
    public $uses = array('User');
    public $layout='stm';

	public function beforeFilter() {
    }
	
	public function beforeRender() {
		parent::beforeRender();
    
	}
	
	function index() { 
        
        
	}
    
    //QRコード読み込み
    function qrread() {
        
    }
    
    //選手名登録
    function registername(){
        
    }
    
    //選手QRCodeチェック
    function check() {
        if ($this->request->is('ajax')) {
            
            $this->autoRender = false;
            // POSTデータがなかったらNG
            if (empty($this->request->data)){
                echo "NG";
                return;
            }
           
            //ハッシュ化
            $this->request->data['code'] = AuthComponent::password($this->request->data['code']);
            
            $user = $this->User->findByPlayerId($this->request->data['code']);
            
            if ($user === false){
                //データが見つからなかった
                echo "NoData";
                return;
            }else if ($user['User']['create_user'] == 0){
                //create_userが0だったら未登録(DBのcreate_userの使い方間違ってたらスミマセン)
                echo "OK";
                return;
            }else{
                //すでに選手登録済み
                echo "Registered";
                return;
            }

        }
    }

    /**
     * 選手追加機能
     */
    function add() {
        
        if ($this->request->is('post')) {
            $this->User->create();
            
            //プレイヤーIDをハッシュ化
            $this->request->data['User']['player_id'] = AuthComponent::password($this->request->data['User']['player_id']);
            $this->User->set($this->request->data);
 
            if ($this->User->validates()) {
                $this->User->save($this->request->data);
                $this->Session->setFlash('選手登録が完了しました！');
            }else{
                $this->Session->setFlash('選手登録に失敗しました。この選手IDはすでに登録されています。');
            }
         
        }
        
    }
    
}

?>
