<script type="text/javascript">
$(function(){
    $('#first').click(function(){
        location.href = '<?php echo $this->Html->webroot?>';
        return false;
    });
});
</script>
<div class="clear">
    <div><?= __("せんしゅとしてとうろくされました！") ?></div>
    <div><?= __("「せんしゅめい」がなかったので") ?></div>
    <div><?= __("せんしゅページにはアクセスできません") ?></div>
    <div>
        <?php echo $this->Form->button(__('わかりました'),array('label' => false, 'class' => 'btn', 'id' => 'first')); ?>    
    </div>
</div>



