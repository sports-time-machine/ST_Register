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
    
    //選手名登録
    function registername() {
                
        if ($this->request->is('post')) {
            $this->Session->write('Register.player_id',h($this->request->data['player_id']));    //セッションに保存
            $this->Session->write('Register.name',"");    //セッションに保存             
        }
        
        //セッションが無かったらリダイレクト
        if ($this->Session->check('Register') == false){
            $this->redirect(array('action' => 'qrread'));
        }
        $this->set('maxlength', NAME_MAX_LENGTH);
        $this->set('register', $this->Session->read('Register'));
    }
    //確認
    function confirm() {
        
        //セッションが無かったらリダイレクト
        if ($this->Session->check('Register') == false){
            $this->redirect(array('action' => 'qrread'));
        }
       
       if ($this->request->is('post')) {
                             
           $username = h($this->request->data['username']);    
           
           //通常の操作で選手名が最大文字数を超えることはないが、
           //リクエストの改ざんなどがあるかもしれないのでチェック
           //（ローカルしか使わないのでいらないかもしれないけど）
           //もし引っかかったらログイン画面へリダイレクト
           if (mb_strlen($username) > NAME_MAX_LENGTH){
               $this->redirect(array('action' => 'qrread'));
           }
          
           $username = mb_convert_kana($username, "s"); //全角スペースを半角スペースに変換
           $username = trim($username);    //前後の半角スペースを削除
           

           
           $this->Session->write('Register.name',$username);    //セッションに保存   
       }

        $disp_name = $this->Session->read('Register.name');    //名前を読み込み
        if (empty($disp_name)){
            $disp_name = "(せんしゅめいはありません)";
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

            //名無しだったらNULLを代入
            if (strcmp($name['Name']['username'],"") == 0){
                $name['Name']['username']=NULL;
                $this->render("registered_noname");  //Viewを変える
            }
            
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
            $this->request->data['code'] = substr($this->request->data['code'], 1);
            
            //DBから検索
            $user = $this->User->findByPlayerId($this->request->data['code']);   

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
