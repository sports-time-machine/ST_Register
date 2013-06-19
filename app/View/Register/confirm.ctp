<?php echo $this->Html->link("前に戻る",array('controller' => 'Register', 'action' => 'registername' ),array('class' => 'left')) ?>

<div class="clear">
    <?php echo $this->Form->create('User',array( 'url' => array('controller' => 'Register', 'action' => 'oath'))); ?>
    <div>選手ID:<?php echo $register['player_id']; ?></div>
    <div>選手名:<?php echo $disp_name; ?></div>
    <?php echo $this->Form->submit('確認',array('label' => false, 'class' => 'btn')); ?>
</div>
<div>
    この選手名を絶対に忘れないでくださいね
</div>
