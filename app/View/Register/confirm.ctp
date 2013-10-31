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
        <div>これでよろしいですか？</div>
        <br />
        <div>せんしゅID:<?php echo $register['player_id']; ?></div>
        <br />
        <div class="input_disp">
            せんしゅ名
            <div class="detail">
                <?php echo $register['name']; ?>
            </div>
        </div>
        <div class="input_disp">
            せいべつ
            <div class="detail">
                <?php echo $disp_gender; ?>
            </div>
        </div>
        <div class="input_disp">
            ねんれい
            <div class="detail">
                <?php echo $disp_age; ?>
            </div>
        </div>
        <br />
        <?php echo $this->Form->button('やりなおし',array('label' => false, 'class' => 'btn', 'id' => 'prev')); ?>        
        <?php echo $this->Form->button('けってい',array('label' => false, 'class' => 'btn', 'id' => 'decide')); ?>
       
    </form>
</div>
<div>
    このせんしゅめいをぜったいに忘れないでくださいね
</div>
