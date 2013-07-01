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
    <div id="outh_str">
        <div class="text-center"> スポーツタイムマシン選手宣誓　</div>
        <div class="text-left"> わたし<?php echo $register['name']; ?>は、</div>
        <div class="text-left">・スポーツマンシップにのっとり正々堂々とプレイします。</div>
        <div class="text-left">・タイムマシンマンシップにのっとり情報を、</div>
        <div class="text-left">&nbsp;&nbsp;&nbsp;未来の自分・家族・友達・山口・地球・宇宙のために残します。</div>
    </div>
    <div>
        <?php echo $this->Form->button('せんせいします',array('label' => false, 'class' => 'btn w350', 'id' => 'decide')); ?>
    </div>
    <div>
        <?php echo $this->Form->button('せんせいしません',array('label' => false, 'class' => 'btn w350', 'id' => 'first')); ?>
    </div>
    </form>
</div>
