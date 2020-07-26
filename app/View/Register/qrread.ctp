<?php
echo $this -> Html -> script('webcam/grid', array( 'inline' => false ));
echo $this -> Html -> script('webcam/version', array( 'inline' => false ));
echo $this -> Html -> script('webcam/detector', array( 'inline' => false ));
echo $this -> Html -> script('webcam/formatinf', array( 'inline' => false ));
echo $this -> Html -> script('webcam/errorlevel', array( 'inline' => false ));
echo $this -> Html -> script('webcam/bitmat', array( 'inline' => false ));
echo $this -> Html -> script('webcam/datablock', array( 'inline' => false ));
echo $this -> Html -> script('webcam/bmparser', array( 'inline' => false ));
echo $this -> Html -> script('webcam/datamask', array( 'inline' => false ));
echo $this -> Html -> script('webcam/rsdecoder', array( 'inline' => false ));
echo $this -> Html -> script('webcam/gf256poly', array( 'inline' => false ));
echo $this -> Html -> script('webcam/gf256', array( 'inline' => false ));
echo $this -> Html -> script('webcam/decoder', array( 'inline' => false ));
echo $this -> Html -> script('webcam/qrcode', array( 'inline' => false ));
echo $this -> Html -> script('webcam/findpat', array( 'inline' => false ));
echo $this -> Html -> script('webcam/alignpat', array( 'inline' => false ));
echo $this -> Html -> script('webcam/databr', array( 'inline' => false ));
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
                //プレフィックスを削除
                $('#player_id').val(code.substr(1));
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
    if (!navigator.mediaDevices) {
        alert("未対応ブラウザです。");
    }
    window.URL = window.URL || window.webkitURL;

    navigator.mediaDevices.getUserMedia({video:true})
        .then( function(stream) {
            video.srcObject = stream
        })
        .catch(function(err){
            alert("カメラがありません。");
        });

    // QRコード取得時のコールバックを設定
    qrcode.callback = function(result) {

      // QRコード取得結果を表示
      if (result != null) {
        //QRコード出力値のバリデーションを行わなければならない
        var reg = /^P[A-Z0-9]{8}$/;    //P+文字アルファベット8つだとマッチ

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
        $('#info').html("<div>せんしゅカードのQRコードをうつして「よみこみ」ボタンを押してください</div>");
    });

    $("#to_input").click(function(){
        location.href = "<?php echo $this->Html->webroot?>Register/input_code";
    });


});
</script>
<form action="<?php echo $this->Html->webroot?>Register/registername" id="QrreadForm" method="post" accept-charset="utf-8">

<div class="cameraLogin">
    <div class="camera">
        <video id="video" autoplay width="400" height="300"></video>
        <canvas id="canvas" ></canvas>
    </div>
    <div>
        <div class="info">
            せんしゅカードのQRコードをうつして「よみこみ」ボタンを押してください
        </div>
        <div class="info">
            <?php echo $this->Form->button('よみこみ', array('type' => 'button', 'div' => false, 'id' => 'read', 'class' => 'btn')) ?>
            <?php echo $this->Form->hidden('player_id'); ?>
        </div>
    </div>

    <div class="inputLogin">
        <div><a href="<?php echo $this->Html->webroot?>Register/input_code">QRコードがよみこめないときはこちら</a></div>
    </div>
</div>
</form>

<div class="modal hide fade" id="errorModal">
    <div class="error modal-body" id="result"></div>
</div>