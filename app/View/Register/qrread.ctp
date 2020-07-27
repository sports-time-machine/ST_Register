<?= $this->Html->script('jsQR.js', ['inline' => false]); ?>

<script type="text/javascript">
var sending = false;

function showModal(mes){
    $('#result').html(mes);
    $("#errorModal").modal("show");
}

//読み込まれたQRコードが既に選手登録済み、もしくは、予め登録されていないQRコードかチェック
function checkPlayerRegister_Ajax(code){
    if (sending === true) {
        console.log('qrcode is already sending.');
        return;
    }
    var url = "<?php echo $this->Html->webroot . 'register/check'; ?>";
    var data = { code : code};
    sending = true;
	$.ajax({
		type: "POST",
		url: url + '?time=' + (new Date).getTime(),
        data: data,
    }).done((data) => {
        if (data === "OK") {
            $('#player_id').val(code.substr(1));
            $('form').submit();
        }else if (data == "NoData") {
            showModal("<div>とうろくされたせんしゅ</div><div>QRコードではありません</div>");
        }else if (data == "Registered") {
            showModal("<div>このせんしゅQRコードは</div><div>すでにとうろくされています</div>");
        }else {
            showModal("<div>もういちどよみこみボタンを押してください</div>");
        }
    }).fail(() => {
        showModal("<div>サーバエラーです。</div><div>かかりの人をよんでください</div>");
    }).always(() => {
        sending = false;
    }) ;
}

$(function(){
    var video = document.getElementById('video');
    var canvas = document.getElementById('canvas');
    var ctx = canvas.getContext('2d');
    var localMediaStream = null;

    //カメラ使えるかチェック
    if (!navigator.mediaDevices) {
        alert("カメラが利用できません。");
    }
    window.URL = window.URL || window.webkitURL;

    var constraints = {
        video:{ facingMode: 'user' },
        audio: false,
    }

    var readQrCode = () => {
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const image = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(image.data, canvas.width, canvas.height);
        if (code) {
            console.log(code.data);
            //P+文字アルファベット8つだとマッチ
            if (code.data.match(/^P[A-Z0-9]{8}$/)) {
                checkPlayerRegister_Ajax(code.data);
            }
        }
    };

    navigator.mediaDevices.getUserMedia(constraints)
        .then( function(stream) {
            video.srcObject = stream;
            video.onloadedmetadata = (_) => {
                setInterval(readQrCode, 300);
            } 
        })
        .catch(function(err){
            alert("カメラが利用できません。");
        });
});
</script>
<form action="<?php echo $this->Html->webroot?>Register/registername" id="QrreadForm" method="post" accept-charset="utf-8">

<div class="cameraLogin">
    <div class="camera">
        <video id="video" autoplay width="400" height="300" style="transform: scale(-1, 1);"></video>
        <canvas id="canvas" width="400" height="300"></canvas>
    </div>
    <div>
        <div class="info">
            せんしゅカードのQRコードをうつしてください
        </div>
    </div>

    <div class="inputLogin">
        <div><a href="<?php echo $this->Html->webroot?>Register/input_code">QRコードがよみこめないときはこちら</a></div>
    </div>
</div>
<?php echo $this->Form->hidden('player_id'); ?>
</form>

<div class="modal hide fade" id="errorModal">
    <div class="error modal-body" id="result"></div>
</div>