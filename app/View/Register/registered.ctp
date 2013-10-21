<script type="text/javascript">
$(function(){
    $('#first').click(function(){
        location.href = '<?php echo $this->Html->webroot?>';
        return false;
    });
});
</script>
<div class="clear">
    <div class="info">
        せんしゅとしてとうろくされました！<br />
        あなたのせんしゅページは<br />
        http://www.sptmy.net/p/<?php echo $player_id ?><br />
        です
    </div>
    <div>
        <?php echo $this->Form->button('わかりました',array('label' => false, 'class' => 'btn', 'id' => 'first')); ?>    
    </div>
</div>


