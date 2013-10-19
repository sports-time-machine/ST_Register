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
        <div>せんしゅID:<?php echo $register['player_id']; ?></div>
        <br />
        せんしゅ名
        <div id="disp_name">
            <p>
                <?php echo $register['name']; ?>
            </p>
        </div>
        せいべつ
        <div id="disp_name">
            <p>
                <?php echo $disp_gender; ?>
            </p>
        </div>
        ねんれい
        <div id="disp_name">
            <p>
                <?php echo $disp_age; ?>
            </p>
        </div>

        <?php echo $this->Form->button('やりなおし',array('label' => false, 'class' => 'btn', 'id' => 'prev')); ?>        
        <?php echo $this->Form->button('けってい',array('label' => false, 'class' => 'btn', 'id' => 'decide')); ?>
       
    </form>
</div>
<div>
    このせんしゅめいをぜったいに忘れないでくださいね
</div>
