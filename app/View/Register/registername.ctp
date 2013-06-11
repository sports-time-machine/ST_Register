<?php echo $this->Form->create('User'); ?>
<div class="left">最初からやり直し</div> 
<div class="right">入力しない</div>
<div class="clear">
<div id="register_name">
    <div id="disp_id"></div>
    <div>選手名(本名)を入力してください</div>
    <?php echo $this->Form->text('username',array('label' => false, 'value' => "")); ?>
    <?php echo $this->Form->hidden('player_id'); ?>
    <?php echo $this->Form->submit('入力完了',array('label' => false)); ?>
</div>
<div>
    ここで名前を入力しておくと<br />
    インターネットで自分の情報を見たり、変更したりできます<br />
    名前を忘れた場合は、新しく選手登録になり過去のデータは見られなくなります。<br />
</div>
</div>