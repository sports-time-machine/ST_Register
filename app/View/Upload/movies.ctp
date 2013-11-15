<div class="mirror index">
	<h2>ムービーのアップロード</h2>

	<form action="<?php echo $this->Html->webroot; ?>upload/movie" method="post" id="InputCodeForm" accept-charset="utf-8" class="form-inline">
		record_id: (ex. G00000HS200)
		<?php echo $this->Form->text('record_id', array('label' => 'record_id', 'class' => "input", "maxlength" => 12, 'autocomplete' => 'off')); ?>
		<?php echo $this->Form->button('アップロード',array('div' => false, 'class' => 'btn', 'id' => 'submit', 'onclick' => "$(this).text('アップロード中です...'); this.form.submit(); $(this).attr('disabled', true);")) ?>
	</form>

	
	<h2>ムービーのアップロード（レコードのみ。ファイルアップロード無し）</h2>

	<form action="<?php echo $this->Html->webroot; ?>upload/movieWithoutFile" method="post" id="InputCodeForm" accept-charset="utf-8" class="form-inline">
		record_id: (ex. G00000HS200)
		<?php echo $this->Form->text('record_id', array('label' => 'record_id', 'class' => "input", "maxlength" => 12, 'autocomplete' => 'off')); ?>
		<?php echo $this->Form->button('登録',array('div' => false, 'class' => 'btn', 'id' => 'submit', 'onclick' => "$(this).text('登録中です...'); this.form.submit(); $(this).attr('disabled', true);")) ?>
	</form>

</div>

