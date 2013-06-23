<script type="text/javascript">
$(function(){
    $('#first').click(function(){
        location.href = '/ST_Register/';
        return false;
    });
});
</script>
<div class="clear">
    <div>せんしゅとしてとうろくされました！</div>
    <div>「せんしゅめい」がなかったので</div>
    <div>せんしゅページにはアクセスできません</div>
    <div>
        <?php echo $this->Form->button('わかりました',array('label' => false, 'class' => 'btn', 'id' => 'first')); ?>    
    </div>
</div>



