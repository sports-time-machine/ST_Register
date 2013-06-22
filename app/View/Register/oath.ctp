<?php echo $this->Html->link("前に戻る",array('controller' => 'Register', 'action' => 'confirm' ),array('class' => 'left')) ?>

<div class="clear">
    <form action="/ST_Register/Register/registered" id="OathForm" method="post" accept-charset="utf-8">
    選手宣誓的な何か
    <?php echo $this->Form->submit('宣誓します',array('label' => false, 'class' => 'btn')); ?>
    </form>
</div>
