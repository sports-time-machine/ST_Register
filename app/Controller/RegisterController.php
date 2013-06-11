<?php
App::uses('AppController', 'Controller');
App::uses('Security', 'Utility');

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
        $this->Session->delete('Register');
    }
    
    //選手名登録
    function registername() {
                
        if ($this->request->is('post')) {
            $this->Session->write('Register.player_id',$this->request->data['User']['player_id']);    //セッションに保存
            $this->Session->write('Register.name',"");    //セッションに保存             
        }
        
        //セッションが無かったらリダイレクト
        if ($this->Session->check('Register') == false){
            $this->redirect(array('action' => 'qrread'));
        }
        $this->set('register',$this->Session->read('Register'));
    }
    
    //確認
    function confirm() {
        
        //セッションが無かったらリダイレクト
        if ($this->Session->check('Register') == false){
            $this->redirect(array('action' => 'qrread'));
        }
       
       $this->Session->write('Register.name',"");   //POSTがなければ名前はなし
       if ($this->request->is('post')) {
            $this->Session->write('Register.name',$this->request->data['User']['username']);    //セッションに保存             
       }
    
        //名前の空白判定は後でもう少ししっかりとやる。(全角スペースのみを空白判定、後ろの空白除去など)
        $disp_name = $this->Session->read('Register.name');    //名前を読み込み
        if (empty($disp_name)){
            $disp_name = "記入なし";
        }
        
        $this->set('disp_name',$disp_name);
        $this->set('register',$this->Session->read('Register'));      
    }
    
    //選手宣誓
    function oath() {
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
            $hash = Security::hash($this->request->data['code'], null, true);
            
            $user = $this->User->findByPlayerId($hash);
            
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
            $this->request->data['User']['player_id'] = Security::hash($this->request->data['User']['player_id'], null, true);
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
