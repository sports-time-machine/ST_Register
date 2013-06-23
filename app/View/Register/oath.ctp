<script type="text/javascript">
$(function(){
    $('#first').click(function(){
        location.href = '/ST_Register/';
        return false;
    });
});
</script>
<div class="clear">
    <form action="/ST_Register/Register/registered" id="OathForm" method="post" accept-charset="utf-8">
    <div>せんしゅせんせい</div>
    選手宣誓的な何か
    <?php echo $this->Form->submit('せんせいします',array('label' => false, 'class' => 'btn')); ?>
    <?php echo $this->Form->button('せんせいしません',array('label' => false, 'class' => 'btn', 'id' => 'first')); ?>
    </form>
</div>
