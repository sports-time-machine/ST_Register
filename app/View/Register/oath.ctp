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
        <div class="text-center" style="padding:20px 16px;"><?=__("スポーツタイムマシン選手宣誓")?></div>
        <div class="text-left">
            <p><?=__("わたし{0}は、", $register['name'])?></p>
            <p>・<?=__("スポーツマンシップにのっとり正々堂々とプレイします。")?></p>
            <p>・<?= nl2br(__("タイムマシンマンシップにのっとり情報を<br/>未来の自分・家族・友達・小松・石川・地球・宇宙のために残します。")) ?></p>
        </div>
    </div>
    <div>
        <?php echo $this->Form->button(__('せんせいしません'),array('label' => false, 'class' => 'btn w350', 'id' => 'first')); ?>
        <?php echo $this->Form->button(__('せんせいします'),array('label' => false, 'class' => 'btn w350', 'id' => 'decide')); ?>
    </div>
    </form>
</div>
