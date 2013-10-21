<script type="text/javascript">
//読み込まれたQRコードが既に選手登録済み、もしくは、予め登録されていないQRコードかチェック
function checkPlayerRegister_Ajax(code){
    var url = "<?php echo $this->Html->webroot . 'register/check_input'; ?>";
    var data = { code : code};

	$.ajax({
		type: "POST",
		url: url + '?time=' + (new Date).getTime(),
		data: data,
		async: true,
		success: function(html){  
            if (html=="OK") {
                code = ("00000000" + code).slice(-8);
                $('#player_id').val(code);
                $('form').submit();
            }else if (html == "NoData") {
                showModal("<div>とうろくされたせんしゅ</div><div>コードではありません</div>");
            }else if (html == "Registered") {
                showModal("<div>このせんしゅコードは</div><div>すでにとうろくされています</div>");
            }else {
                showModal("<div>もういちどよみこみボタンを押してください</div>");
            }
        },
        error: function(a,b,c){
            showModal("<div>サーバエラーです。</div><div>かかりの人をよんでください</div>");
        }
	});
}

//エラーをモーダルで表示
function showModal(mes){
    $('#result').html(mes); 
    $("#errorModal").modal("show");
}
  
$(function(){
    
    $('#read').removeAttr('disabled');
    $('#info').html("<div>せんしゅカードに書いてあるコードを書いて</div><div>「よみこみ」ボタンを押してください</div>");  
    
    //ボタンイベント
    $("#read").click(function() {
        $("#read").attr('disabled', true);
        var result = $('#input_code').val();
        checkPlayerRegister_Ajax(result.toUpperCase()); 
    });
    
    //バブリングの抑制
    $("#input_code").keypress(function(e){
        if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
			$("#read").attr('disabled', true);
            var result = $('#input_code').val();
            checkPlayerRegister_Ajax(result.toUpperCase());      
            return false;
		} else {   
			return true;
		} 
    });
    
    //読み込みボタンの復活
    $("#errorModal").on('hidden',function(){
        $('#read').removeAttr('disabled');
        $('#info').html("<div>せんしゅカードに書いてあるコードを書いて</div><div>「よみこみ」ボタンを押してください</div>");  
    });
  
});
</script>
<form action="<?php echo $this->Html->webroot?>Register/registername" id="InputCodeForm" method="post" accept-charset="utf-8">
<div class="info">
    <div>せんしゅカードに書いてあるコードを書いて</div>
    <div>「よみこみ」ボタンを押してください</div>
</div>
<div>
    <div>
        <?php echo $this->Form->text('input_code',array('label' => false, 'class' => "input" , "maxlength" => 8)); ?>
    </div>
    <div>
        <?php echo $this->Form->button('よみこみ',array('type' => 'button', 'div' => false, 'id' => 'read', 'class' => 'btn')) ?>
    </div>
    <?php echo $this->Form->hidden('player_id'); ?>
</div>
<div class="modal hide fade" id="errorModal">
    <div class="error modal-body" id="result"></div>
</div>
</form>