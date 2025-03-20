<?php

App::uses('AppController', 'Controller');
define("NAME_MAX_LENGTH",32);
    
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
    
    //キーボードから入力
    function input_code() {
        $this->Session->delete('Register');
    }
    
    //選手名登録
    function registername() {
                        
        //セッションが無かったらリダイレクト
        if ($this->Session->check('Register') == false){
            $this->redirect(array('action' => 'qrread'));
        }
        
        $ages = array();
        for ($i=0;$i<=100;$i++){
            $ages+= array($i => $i);
        }

        $this->set('maxlength', NAME_MAX_LENGTH);
        $this->set('register', $this->Session->read('Register'));
        $this->set('ages', $ages);
    }
    //確認
    function confirm() {
        //セッションが無かったらリダイレクト
        if ($this->Session->check('Register') == false){
            $this->redirect(array('action' => 'qrread'));
        }
       
       if ($this->request->is('post')) {
                             
           $username = h($this->request->data['username']);    
           $gender = h($this->request->data['gender']);   
           $age = h($this->request->data['age']);   
           
           //通常の操作で選手名が最大文字数を超えることはないが、
           //リクエストの改ざんなどがあるかもしれないのでチェック
           //（ローカルしか使わないのでいらないかもしれないけど）
           //もし引っかかったらログイン画面へリダイレクト
           if (mb_strlen($username) > NAME_MAX_LENGTH){
               $this->redirect(array('action' => 'qrread'));
           }
          
           $username = mb_convert_kana($username, "as"); //全角英数字スペースを半角英数字スペースに変換
           $username = trim($username);    //前後の半角スペースを削除
           
           //セッションに保存  
           $this->Session->write('Register.name', $username);     
           $this->Session->write('Register.gender', $gender);    
           $this->Session->write('Register.age', $age);
           
       }
       
       $this->set('register',$this->Session->read('Register'));
       
    }
    
    //選手宣誓
    function oath() {
        //セッションが無かったらリダイレクト
        if ($this->Session->check('Register') == false){
           $this->redirect(array('action' => 'qrread'));
        }
        
        $this->set('register',$this->Session->read('Register'));  
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
            
            $gender_str="";
            if ($this->Session->read('Register.gender') == "male") $gender_str = "男性";
            if ($this->Session->read('Register.gender') == "female") $gender_str = "女性";
            if ($this->Session->read('Register.gender') == "other") $gender_str = "その他";

            $name['Name']['gender'] = $gender_str; //性別を決定
            $name['Name']['age'] = $this->Session->read('Register.age'); //年齢を決定
            
            $this->Name->set($name);
            $this->Name->save();
            $this->set('player_id', $player['User']['player_id']);

            $this->Session->delete('Register');
        
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
           
            //最初のプレフィックスを無視
            $code = substr($this->request->data['code'], 1);
            
            //DBから検索
            $user = $this->User->findByPlayerId($code);   

            if ($user == false){
                //データが見つからなかった
                echo "NoData";
                return;
            }else{
                            
                $count = $this->Name->find('count', array( 'conditions' => array( "user_id" => $user['User']['id'])));
                
                //対象ユーザIDが無かったら未登録と判定
                if ($count == 0){
                    //未登録
                    echo "OK";
                    //初期値設定
                    $this->Session->write('Register.player_id',h($code));    
                    $this->Session->write('Register.name',"");
                    $this->Session->write('Register.gender',"");
                    $this->Session->write('Register.age',"");
                    return;
                }else{
                    //すでに選手登録済み
                    echo "Registered";
                    return;            
                }
            }
        }
    }
    
    //選手コード(入力)チェック
    function check_input() {
        if ($this->request->is('ajax')) {
            
            $this->autoRender = false;
            // POSTデータがなかったらNG
            if (empty($this->request->data)){
                echo "NG";
                return;
            }
           
            //8文字まで0を詰める
            $code = sprintf("%08s",$this->request->data['code']);
            
            //DBから検索
            $user = $this->User->findByPlayerId($code);   

            if ($user == false){
                //データが見つからなかった
                echo "NoData";
                return;
            }else{
                            
                $count = $this->Name->find('count', array( 'conditions' => array( "user_id" => $user['User']['id'])));
                
                //対象ユーザIDが無かったら未登録と判定
                if ($count == 0){
                    //未登録
                    echo "OK";
                    //初期値設定
                    $this->Session->write('Register.player_id',h($code));    
                    $this->Session->write('Register.name',"");
                    $this->Session->write('Register.gender',"");
                    $this->Session->write('Register.age',"");
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
