<!-- Header -->
<script type="text/javascript">
$(function(){ 
    $('#home').click(function(){
        location.href = '<?php echo $this->Html->webroot?>';
        return false;
    });
});
</script>
<div id="header">
    <?php if ($this->action != 'qrread') : ?>
    <a href="#" id="home" class="left">最初の画面へ</a>
    <br>
    <?php endif; ?>
    <div>
        せんしゅとうろくマシン
    </div>
    <div>
        <?php echo $this->Html->image('line.gif', array('alt' => 'Line')); ?>
    </div>

</div>
<!-- Header -->
