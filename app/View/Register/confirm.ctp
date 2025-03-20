<script type="text/javascript">
$(function(){
    $('#decide').click(function(){
        $('form').submit(); 
    });
    
    $('#prev').click(function(){
        location.href = '<?php echo $this->Html->webroot?>Register/registername';
        return false;
    });
});
</script>
<div class="clear">
    <form action="<?php echo $this->Html->webroot?>Register/oath" id="ConfirmForm" method="post" accept-charset="utf-8">
        <div><?= __("これでよろしいですか？")?></div>
        <div><?= __("せんしゅID")?>:<?php echo $register['player_id']; ?></div>
        <div class="input_disp">
            <?= __("せんしゅ名")?>
            <div class="detail">
                <?php echo $register['name']; ?>
            </div>
        </div>
        <div class="input_disp">
            <?= __("せいべつ")?>
            <div class="detail">
                <?php echo $disp_gender; ?>
            </div>
        </div>
        <div class="input_disp" style="margin-bottom: 8px;">
            <?= __("ねんれい")?>
            <div class="detail">
                <?php echo $disp_age; ?>
            </div>
        </div>
        <?php echo $this->Form->button(__('やりなおし'),array('label' => false, 'class' => 'btn', 'id' => 'prev')); ?>        
        <?php echo $this->Form->button(__('けってい'),array('label' => false, 'class' => 'btn', 'id' => 'decide')); ?>
       
    </form>
</div>
<div>
    <?= __("このせんしゅめいをぜったいに忘れないでくださいね") ?>
</div>
