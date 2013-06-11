<?php
echo $this -> Html -> script( 'webcam/grid', array( 'inline' => false ) );
echo $this -> Html -> script( 'webcam/version', array( 'inline' => false ) );
echo $this -> Html -> script( 'webcam/detector', array( 'inline' => false ) );
echo $this -> Html -> script( 'webcam/formatinf', array( 'inline' => false ) );
echo $this -> Html -> script( 'webcam/errorlevel', array( 'inline' => false ) );
echo $this -> Html -> script( 'webcam/bitmat', array( 'inline' => false ) );
echo $this -> Html -> script( 'webcam/datablock', array( 'inline' => false ) );
echo $this -> Html -> script( 'webcam/bmparser', array( 'inline' => false ) );
echo $this -> Html -> script( 'webcam/datamask', array( 'inline' => false ) );
echo $this -> Html -> script( 'webcam/rsdecoder', array( 'inline' => false ) );
echo $this -> Html -> script( 'webcam/gf256poly', array( 'inline' => false ) );
echo $this -> Html -> script( 'webcam/gf256', array( 'inline' => false ) );
echo $this -> Html -> script( 'webcam/decoder', array( 'inline' => false ) );
echo $this -> Html -> script( 'webcam/qrcode', array( 'inline' => false ) );
echo $this -> Html -> script( 'webcam/findpat', array( 'inline' => false ) );
echo $this -> Html -> script( 'webcam/alignpat', array( 'inline' => false ) );
echo $this -> Html -> script( 'webcam/databr', array( 'inline' => false ) );
?>
<script type="text/javascript">
    
//読み込まれたQRコードが既に選手登録済み、もしくは、予め登録されていないQRコードかチェック
function CheckPlayerRegister_Ajax(code){
    var url = "<?php echo $this->Html->webroot . 'register/check'; ?>";
    var data = { code : code};

	$.ajax({
		type: "POST",
		url: url + '?time=' + (new Date).getTime(),
		data: data,
		async: true,
		success: function(html){

            if (html=="OK") {
                $('#result').text("読み込み成功！");
                $('#UserPlayerId').val(code);
                $('form').submit();
            }else if (html == "NoData") {
                $('#result').text("エラー！あらかじめ登録された選手QRコードではありません");
            }else if (html == "Registered") {
                $('#result').text("エラー！この選手QRコードはすでに登録されています");
            }else {
                $('#result').text("エラー！");
            }
        }
	});
}

    
$(function(){
    var video = document.getElementById('video');
    var canvas = document.getElementById('canvas');
    
    //canvasは２倍の大きさにしないと駄目みたい？
    canvas.width = video.width*2;
    canvas.height = video.height*2;
    
    var ctx = canvas.getContext('2d');
    var localMediaStream = null;

    //カメラ使えるかチェック
    var hasGetUserMedia = function() {
        return !!(navigator.getUserMedia || navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia || navigator.msGetUserMedia);
    }

    if (!hasGetUserMedia()) {
        alert("未対応ブラウザです。");
    }
    window.URL = window.URL || window.webkitURL;
    navigator.getUserMedia  = navigator.getUserMedia || navigator.webkitGetUserMedia ||
                              navigator.mozGetUserMedia || navigator.msGetUserMedia;

    navigator.getUserMedia({video: true},
        function(stream) {
          video.src = window.URL.createObjectURL(stream);
          localMediaStream = stream;
        },
        function(err){
            alert("未対応ブラウザです。");
        }
    );

    // QRコード取得時のコールバックを設定
    qrcode.callback = function(result) {
         
      // QRコード取得結果を表示
      if (result != null) {
        //QRコード出力値のバリデーションを行わなければならない
        var reg = /[A-Z0-9]{4}/;    //文字アルファベット４つだとマッチ
        
        if (result.match(reg)){
            CheckPlayerRegister_Ajax(result); 
            $('#read').removeAttr('disabled');
        }else{
            $('#result').text("読み込みに失敗しました。読み込んだQRコードは選手QRコードではありません。");   
            $('#read').removeAttr('disabled');
        }
      }else{
          $('#result').text("読み込みに失敗しました。読み込んだQRコードを読み取れませんでした。");  
          $('#read').removeAttr('disabled');
      }
    };
      
    //ボタンイベント
    $("#read").click(function() {
    
        $("#read").attr('disabled', true);
        $('#result').text("QRコードを読み取り中です…");
        
        if (localMediaStream) {
            ctx.drawImage(video, 0, 0);
            // QRコード取得開始
            qrcode.decode(canvas.toDataURL('image/webp'));        
        }else{
            $('#read').removeAttr('disabled');
        }            
    });
    
});
</script>

<?php echo $this->Form->create('User',array( 'url' => array('controller' => 'Register', 'action' => 'registername'))); ?>
<div>選手カードにあるQRコードをかざしてボタンを押してください</div>
<div id="camera">
    <video id="video" autoplay width="320" height="240"></video> 
    <canvas id="canvas" ></canvas>
</div>
<?php echo $this->Form->button('読み込み',array('type' => 'button', 'div' => false, 'id' => 'read')) ?>


<?php echo $this->Form->hidden('player_id'); ?>

<div  class="error" id="result"></div>