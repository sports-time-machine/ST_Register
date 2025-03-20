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
    <div style="display:flex; justify-content: space-between">
        <div>
            <a class="lang_link" href="?lang=kor">한국어</a>
            <a class="lang_link" href="?lang=eng">English</a>
            <a class="lang_link" href="?lang=jpn">日本語</a>
        </div>
        <div>
            <?php if ($this->action != 'qrread' && $this->action != 'registered') : ?>
                <a href="#" id="home"><?=__("最初の画面へ")?></a>
            <?php endif; ?>
        </div>
    </div>
    <div>
        <?=__("せんしゅとうろくマシン")?>
    </div>
    <div>
        <?php echo $this->Html->image('line.gif', array('alt' => 'Line')); ?>
    </div>

</div>
<!-- Header -->
