<?php echo $this->Html->link("前に戻る",array('controller' => 'Register', 'action' => 'confirm' ),array('class' => 'left')) ?>

<div class="clear">
    <?php echo $this->Form->create('User',array( 'url' => array('controller' => 'Register', 'action' => 'registered'))); ?>
    選手宣誓的な何か
    <?php echo $this->Form->submit('宣誓します',array('label' => false)); ?>
</div>
