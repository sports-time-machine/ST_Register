<script type="text/javascript">
$(function(){
    $('#prev').click(function(){
        location.href = '/ST_Register/Register/registername';
        return false;
    });
});
</script>
<div class="clear">
    <form action="/ST_Register/Register/oath" id="ConfirmForm" method="post" accept-charset="utf-8">
        <div>この「せんしゅめい」でよろしいですか？</div>
        <div>せんしゅID:<?php echo $register['player_id']; ?></div>
        <div><?php echo $disp_name; ?></div>
        <?php echo $this->Form->submit('けってい',array('label' => false, 'class' => 'btn')); ?>
        <?php echo $this->Form->button('やりなおし',array('label' => false, 'class' => 'btn', 'id' => 'prev')); ?>
    </form>
</div>
<div>
    このせんしゅめいをぜったいに忘れないでくださいね
</div>
