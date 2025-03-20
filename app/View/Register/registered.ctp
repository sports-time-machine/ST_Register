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
        <?= nl2br(__("せんしゅとしてとうろくされました！\nあなたのせんしゅページは\n{0}\nです", "http://www.sptmy.net/p/".$player_id)) ?><br />
    </div>
    <div>
        <?php echo $this->Form->button(__('わかりました'),array('label' => false, 'class' => 'btn', 'id' => 'first')); ?>    
    </div>
</div>


