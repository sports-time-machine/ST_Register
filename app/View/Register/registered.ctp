<script type="text/javascript">
$(function(){
    $('#first').click(function(){
        location.href = '<?php echo $this->Html->webroot?>';
        return false;
    });
});
</script>
<div class="clear">
    <div>せんしゅとしてとうろくされました！</div>
    <div>あなたのせんしゅページは</div>
    <div>http://sptmy.net/<?php echo $player_id ?></div>
    <div>です</div>
    </div>
    <div>
        <?php echo $this->Form->button('わかりました',array('label' => false, 'class' => 'btn', 'id' => 'first')); ?>    
    </div>
</div>


