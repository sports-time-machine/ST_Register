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
    <div class="oath">
        <div class="text-center" style="padding:20px;"> スポーツタイムマシン選手宣誓　</div>
        <div class="text-left"> 
       わたし<?php echo $register['name']; ?>は、<br /><br />
       ・スポーツマンシップにのっとり正々堂々とプレイします。<br /><br />
       ・タイムマシンマンシップにのっとり情報を、<br />
       &nbsp;&nbsp;&nbsp;未来の自分・家族・友達・鹿児島・地球・宇宙のために残します。<br />
        </div>
    </div>
    <div>        
        <?php echo $this->Form->button('せんせいしません',array('label' => false, 'class' => 'btn w350', 'id' => 'first')); ?>
        <?php echo $this->Form->button('せんせいします',array('label' => false, 'class' => 'btn w350', 'id' => 'decide')); ?>
    </div>
    </form>
</div>
