<?= $this->Html->script('jsQR.js', ['inline' => false]); ?>

<script type="text/javascript">
var sending = false;
var cameraStream = null;
var facingFront = true;


function showModal(mes){
    $('#result').html(mes);
    $('#errorModal').modal('show');
}

//読み込まれたQRコードが既に選手登録済み、もしくは、予め登録されていないQRコードかチェック
function checkPlayerRegister_Ajax(code){
    if (sending === true) {
        return;
    }
    var url = "<?php echo $this->Html->webroot . 'register/check'; ?>";
    var data = { code : code};
    sending = true;
    $('#info').text("<?= __("よみこみました。かくにんちゅうです…") ?>");
	$.ajax({
		type: "POST",
		url: url + '?time=' + (new Date).getTime(),
        data: data,
    }).done((data) => {
        if (data === "OK") {
            $('#player_id').val(code.substr(1));
            $('form').submit();
        }else if (data === "NoData") {
            showModal("<div><?= __("とうろくされたせんしゅQRコードではありません")?></div>");
        }else if (data === "Registered") {
            showModal("<div><?= __("このせんしゅQRコードはすでにとうろくされています")?></div>");
        }else {
            showModal("<div><?= __("もういちどよみこみボタンを押してください")?></div>");
        }
    }).fail(() => {
        showModal("<div><?= __("サーバエラーです")?><br><?= __("かかりの人をよんでください")?></div>");
    }).always(() => {
        $('#info').text('<?= __("せんしゅカードのQRコードをうつしてください")?>');
    }) ;
}

function syncCamera(video) {
    const constraints = {
        video: {
            width: 640,
            height: 480,
            facingMode: (facingFront) ? 'user' : { exact: "environment" },
        },
        audio: false,
    }

    if (cameraStream) {
        cameraStream.getVideoTracks().forEach(cam => cam.stop());
    }

    navigator.mediaDevices.getUserMedia(constraints)
        .then( function(stream) {
            cameraStream = stream;        
            video.srcObject = stream;
            if (facingFront) {
                $("#video").css('transform', 'scale(-1, 1)');
            } else {
                $("#video").css('transform', '');
            }
        })
        .catch(function(err){
            alert("<?= __("カメラが利用できません。")?>");
        });
}

$(function(){    
    var video = document.getElementById('video');
    var canvas = document.getElementById('canvas');
    var ctx = canvas.getContext('2d');

    //カメラ使えるかチェック
    if (!navigator.mediaDevices) {
        alert("<?= __("カメラが利用できません。")?>");
    }

    var readQrCode = () => {
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const image = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(image.data, canvas.width, canvas.height);
        if (code) {
            //P+文字アルファベット8つだとマッチ
            if (code.data.match(/^P[A-Z0-9]{8}$/)) {
                checkPlayerRegister_Ajax(code.data);
            }
        }
    };
    video.onloadedmetadata = (_) => {
        setInterval(readQrCode, 300);
    } 
    syncCamera(video);

    $('#turnCamera').click((e) => {
        e.preventDefault();
        facingFront = !facingFront;        
        syncCamera(video);
    });

    $('#errorModal').on('hidden.bs.modal', function () {
        sending = false;
    });
});
</script>
<form action="<?php echo $this->Html->webroot?>Register/registername" id="QrreadForm" method="post" accept-charset="utf-8">

<div class="cameraLogin">
    <button id="turnCamera" style="margin-bottom:5px; padding: 5px; -webkit-appearance: none; background-color: #eee;"><?= $this->Html->image('turn_camera.png', ['alt' => __('カメラ切り替え')]); ?></button>
    <div class="camera">
        <video id="video" autoplay width="400" height="300"></video>
        <canvas id="canvas" width="640" height="480"></canvas>
    </div>
    <div>
        <div id="info" class="info">
            <?= __("せんしゅカードのQRコードをうつしてください") ?>
        </div>
    </div>
    <div class="inputLogin">
        <div><a href="<?php echo $this->Html->webroot?>Register/input_code"><?= __("QRコードがよみこめないときはこちら") ?></a></div>
    </div>
</div>
<?php echo $this->Form->hidden('player_id'); ?>
</form>

<div class="modal hide fade" id="errorModal">
    <div class="error modal-body" id="result"></div>
</div>