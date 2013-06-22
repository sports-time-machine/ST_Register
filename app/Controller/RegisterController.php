<?php
App::uses('AppController', 'Controller');

class RegisterController extends AppController {
	
    public $uses = array('User','Name');
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
            $this->Session->write('Register.player_id',$this->request->data['player_id']);    //セッションに保存
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
       
       if ($this->request->is('post')) {
            $data['username'] = $this->request->data['username'];
            $data['username'] = mb_convert_kana($data['username'], "s"); //全角スペースを半角スペースに変換
            $data['username'] = trim($data['username']);    //前後の半角スペースを削除
            $this->Session->write('Register.name',$data['username']);    //セッションに保存   
       }

        $disp_name = $this->Session->read('Register.name');    //名前を読み込み
        if (empty($disp_name)){
            $disp_name = "記入なし";
        }
   
        $this->set('register',$this->Session->read('Register'));              
        $this->set('disp_name',$disp_name);
       
       
    }
    
    //選手宣誓
    function oath() {
        //セッションが無かったらリダイレクト
        if ($this->Session->check('Register') == false){
            $this->redirect(array('action' => 'qrread'));
        }
    }
    
    //選手追加
    function registered() {
        
        if ($this->request->is('post')) {
            //セッションが無かったらリダイレクト
            if ($this->Session->check('Register') == false){
                $this->redirect(array('action' => 'qrread'));
            }

            $this->Name->create();

            //プレイヤーを逆引き
            $player=$this->User->findByPlayerId($this->Session->read('Register.player_id'));
            
            $name['Name']['user_id'] = $player['User']['id'];
            $name['Name']['username'] = $this->Session->read('Register.name'); //名前を決定
            
            $this->Name->set($name);
            if ($this->Name->validates()) {
                $this->Name->save();
                $this->Session->delete('Register');
            }else{
                $this->Session->delete('Register');
            }
        }else{
            //POST以外で来たらQRコード読み込み画面へリダイレクト
            $this->redirect(array('action' => 'qrread'));  
        }  
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
           
            $user = $this->User->findByPlayerId($this->request->data['code']);
                        
            if ($user === false){
                //データが見つからなかった
                echo "NoData";
                return;
            }else{
                            
                $name = $this->Name->findByUserId($user['User']['id']);
    
                //対象ユーザIDが無かったら未登録と判定
                if ($name == false){
                    //未登録
                    echo "OK";
                    return;
                }else{
                    //すでに選手登録済み
                    echo "Registered";
                    return;            
                }
            }
        }
    }

    
}

?>
