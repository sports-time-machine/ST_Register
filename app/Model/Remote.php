<?php
App::uses('AppModel', 'Model');

class Remote extends AppModel
{
	public $name = 'Remote';
	public $useTable = false;
	public $useDbConfig = 'remote';
}
