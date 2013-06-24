<!-- Header -->
<script type="text/javascript">
$(function(){
    $('#home').click(function(){
        location.href = '/ST_Register/';
        return false;
    });
});
</script>
<div id="header">
    <a href="#" id="home" class="left">最初の画面へ</a>
    <br>
    <div>
        せんしゅとうろくマシン
    </div>
    <div>
        <?php echo $this->Html->image('line.gif', array('alt' => 'Line')); ?>
    </div>

</div>
<!-- Header -->
