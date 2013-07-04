<?php
App::uses('AppModel', 'Model');

class Record extends AppModel
{
	public $name = 'Record';
	public $useTable = 'records';
	public $primaryKey = 'id';
	
	// app_model.phpでconfig/column_list/Record.php, config/validate/Record.phpを読み込み
	public $column_list = array();
	public $validate = array();
	
	// view用bind
	public function bindForView() {
		// Imageを読み込むため recursive = 2
		$this->recursive = 2;
		
		$bind = array(
			'hasMany' => array(
				'RecordImage' => array(
					'className' => 'RecordImage',
					'fields' => array('record_id', 'image_id'),
					//'order' => 'RecordImage.no',
				),
				'Partner' => array(
					'className' => 'Partner',
					'fields' => array('record_id', 'partner_id'),
				),
			),
		);
		$this->bindModel($bind, false);
	}
	public function unbindForView() {
		// Imageを読み込むため recursive = 2
		$this->recursive = -1;
		
		$bind = array(
			'hasMany' => array(
				'RecordImage',
				'Partner',
			),
		);
		$this->unbindModel($bind, false);
	}
}
