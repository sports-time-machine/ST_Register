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
function checkPlayerRegister_Ajax(code){
    var url = "<?php echo $this->Html->webroot . 'register/check'; ?>";
    var data = { code : code};

	$.ajax({
		type: "POST",
		url: url + '?time=' + (new Date).getTime(),
		data: data,
		async: true,
		success: function(html){  
            if (html=="OK") {
                $('#player_id').val(code);
                $('form').submit();
            }else if (html == "NoData") {
                showModal("<div>とうろくされたせんしゅ</div><div>QRコードではありません</div>");
            }else if (html == "Registered") {
                showModal("<div>このせんしゅQRコードは</div><div>すでにとうろくされています</div>");
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
            //ST_RegisterはOperaでブラウズする。
            //カメラを常に許可できるため。
            //Operaのみ対応
            //video.srcがstreamで受け取るブラウザと
            //navigator.getUserMedia(stream)で受け取るブラウザ
            //にわかれている。。。
   
            video.src = stream; 
            localMediaStream = stream;
        },
        function(err){
            alert("カメラがありません。");
        }
    );

    // QRコード取得時のコールバックを設定
    qrcode.callback = function(result) {
         
      // QRコード取得結果を表示
      if (result != null) {
        //QRコード出力値のバリデーションを行わなければならない
        var reg = /[A-Z0-9]{4}/;    //文字アルファベット４つだとマッチ
        
        if (result.match(reg)){
            //読み込みできたら時間経過のイベント消去
            //非同期通信時にタイムアウトや読み込みを防ぐため
            clearInterval(intervalId);
            clearTimeout(timeoutId);        
            checkPlayerRegister_Ajax(result); 
        }else{
            showModal("<div>せんしゅQRコードではありません</div>");
        }
      }

    };
      
    //ボタンイベント
    $("#read").click(function() {
    
        intervalId = setInterval(function(){
          
            if (localMediaStream) {
                ctx.drawImage(video, 0, 0);
                // QRコード取得開始
                qrcode.decode(canvas.toDataURL('image/webp'));        
            }     
            
        },500);

        //10秒経過するとタイムアウト
        timeoutId = setTimeout(function(){
            showModal("<div>もういちどよみこみボタンを押してください</div>");
        },10000);

        $("#read").attr('disabled', true);
        $('#info').html("<div>よみとり中です…</div><div>せんしゅカードのQRコードをうつしてください</div>");
       
    });
    
    //エラー表示時には、時間経過系のイベントを全て消去
    $("#errorModal").on('show',function(){
        clearInterval(intervalId);
        clearTimeout(timeoutId);        
    });
    
    //読み込みボタンの復活
    $("#errorModal").on('hidden',function(){
        $('#read').removeAttr('disabled');
        $('#info').html("<div>せんしゅカードのQRコードをうつして</div><div>「よみこみ」ボタンを押してください</div>");  
    });
    
     
});
</script>
<form action="/ST_Register/Register/registername" id="QrreadForm" method="post" accept-charset="utf-8">
<div id="camera">
    <video id="video" autoplay width="480" height="360"></video> 
    <canvas id="canvas" ></canvas>
</div>
<div id="info">
    <div>せんしゅカードのQRコードをうつして</div>
    <div>「よみこみ」ボタンを押してください</div>
</div>
<div class="modal hide fade" id="errorModal">
    <div class="error modal-body" id="result"></div>
</div>
<?php echo $this->Form->button('よみこみ',array('type' => 'button', 'div' => false, 'id' => 'read', 'class' => 'btn')) ?>
<?php echo $this->Form->hidden('player_id'); ?>
</form>