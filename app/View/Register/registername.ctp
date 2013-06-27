<script type="text/javascript">
$(function(){
    $('#decide').click(function(){
        $('form').submit(); 
    });
    
    $('#noName').click(function(){
        $('#username').val(""); //記入なしに変更
        $('form').submit();        
        return false;
    });
});
</script>
<div class="clear">
    <form action="<?php echo $this->Html->webroot?>Register/confirm" id="RegisternameForm" method="post" accept-charset="utf-8">
        <div id="register_name">
                <div>せんしゅID:<?php echo $register['player_id']; ?></div>
                <div>せんしゅめい(ほんみょう)を入力してください</div>
                <?php echo $this->Form->text('username',array('label' => false, 'default' => $register['name'] , "maxlength" => $maxlength)); ?>
                <?php echo $this->Form->hidden('player_id'); ?>
                <div>
                    <?php echo $this->Form->button('けってい',array('label' => false, 'class' => 'btn', 'id' => 'decide')); ?>    
                </div>
        <div>
            <div>ここで「せんしゅめい」を入力しておくと</div>
            <div>お家のインターネットで自分の情報を見たり、変更したりできます</div>
            <?php echo $this->Form->button('入力しない',array('label' => false, 'class' => 'btn', 'id' => 'noName')); ?>
            <div>ちゅうい！</div>
            <div>「せんしゅめい」を忘れた場合は、過去の情報は見られなくなります</div>
            <div>忘れないでください</div>
        </div>
    </form>
</div>