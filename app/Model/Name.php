<?php
App::uses('AppModel', 'Model');

class Name extends AppModel
{
	public $name = 'Name';
	public $useTable = 'names';
	public $primaryKey = 'id';

    public $validate = array(
        'username' => array(
            'maxLength' => array(
                'rule' => array('maxLength',255),
                'message' => 'せんしゅ名は255文字以下にしてください'
             ),
         ),
         'gender' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'どれかひとつ選んでください'
             ),
         ),
         'age' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'どれかひとつ選んでください'
             ),
         ),
    );
 
}
