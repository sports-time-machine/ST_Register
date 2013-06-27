<script type="text/javascript">
$(function(){
    $('#decide').click(function(){
        $('form').submit(); 
    });    
    
    $('#first').click(function(){
        location.href = '<?php echo $this->Html->webroot?>';
        return false;
    });
});
</script>
<div class="clear">
    <form action="<?php echo $this->Html->webroot?>Register/registered" id="OathForm" method="post" accept-charset="utf-8">
    <div>せんしゅせんせい</div>
    <div>選手宣誓的な何か</div>
    <div>
        <?php echo $this->Form->button('せんせいします',array('label' => false, 'class' => 'btn w350', 'id' => 'decide')); ?>
    </div>
    <div>
        <?php echo $this->Form->button('せんせいしません',array('label' => false, 'class' => 'btn w350', 'id' => 'first')); ?>
    </div>
    </form>
</div>
