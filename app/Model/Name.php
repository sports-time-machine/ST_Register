<?php
App::uses('AppModel', 'Model');

class Name extends AppModel
{
	public $name = 'Name';
	public $useTable = 'names';
	public $primaryKey = 'id';

    public $validate = array(

    );
 
}
