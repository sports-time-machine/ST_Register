<script type="text/javascript">
$(function(){
    $('#noName').click(function(){
        $('#username').val(""); //記入なしに変更
        $('form').submit();        
        return false;
    });
});
</script>
<?php echo $this->Html->link("最初からやり直し",array('controller' => 'Register', 'action' => 'qrread' ),array('class' => 'left')) ?>
<?php echo $this->Html->link("入力しない","#",array('class' => 'right', 'id' => 'noName')) ?>
<div class="clear">
    <div id="register_name">
    <form action="/ST_Register/Register/confirm" id="RegisternameForm" method="post" accept-charset="utf-8">
        <div>選手ID:<?php echo $register['player_id']; ?></div>
        <div>選手名(本名)を入力してください</div>
        <?php echo $this->Form->text('username',array('label' => false, 'default' => $register['name'], "maxlength" => "255")); ?>
        <?php echo $this->Form->hidden('player_id'); ?>
        <?php echo $this->Form->submit('入力完了',array('label' => false, 'class' => 'btn')); ?>
    </form>
    </div>
    <div>
        ここで名前を入力しておくと<br />
        インターネットで自分の情報を見たり、変更したりできます<br />
        名前を忘れた場合は、新しく選手登録になり過去のデータは見られなくなります。<br />
    </div>
</div>