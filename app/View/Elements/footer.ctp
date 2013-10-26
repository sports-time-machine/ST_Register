<!-- Footer -->
<?php if ($this->action == 'qrread' || $this->action == 'input_code') : ?>
<script type="text/javascript">
$(function(){
    $("#content,#header").mouseover(function(){ 
        $("div.sptmy > div.link").css("text-decoration", "none");
        $("div.sptmy > div.info").css("text-decoration", "none");
        $("div.share > div.link").css("text-decoration", "none");
        $("div.share > div.info").css("text-decoration", "none");   
    });
    
    $("div.sptmy").hover( 
        function () {
            $("div.sptmy").css("cursor", "pointer");
            $("div.sptmy > div.link").css("text-decoration", "underline");
            $("div.sptmy > div.info").css("text-decoration", "underline");
            
            $("div.share > div.link").css("text-decoration", "none");
            $("div.share > div.info").css("text-decoration", "none");            
        },
        function () {
            $("div.sptmy").css("cursor", "pointer");
            $("div.sptmy > div.link").css("text-decoration", "none");
            $("div.sptmy > div.info").css("text-decoration", "none");
            
        }
    );
    $("div.sptmy").click(function(){
        window.location="http://sptmy.net/";
        return false;
    });
   
    $("div.share").hover( 
        function () {
            $("div.share").css("cursor", "pointer");
            $("div.share > div.link").css("text-decoration", "underline");
            $("div.share > div.info").css("text-decoration", "underline");
            
            $("div.sptmy > div.link").css("text-decoration", "none");
            $("div.sptmy > div.info").css("text-decoration", "none");            
        },
        function () {
            $("div.share").css("cursor", "pointer");
            $("div.share > div.link").css("text-decoration", "none");
            $("div.share > div.info").css("text-decoration", "none");
        }
    );
        
    $("div.share").click(function(){
        return false;
    });
});
</script>

<div class="sptmy">
    <div class="link">
        <?php echo $this->Html->image('arrow-0.gif'); ?>
        <span>SPTMせんしゅWEBサイトへ</span>
    </div>
    <div class="info">
        いっしょに走りたいデータを探せます。<br />
        「選手情報」「走った情報」を<br />
        みんなで使うためのWEBへのリンクです。<br />
    </div>
</div>

<div class="share" style="float:left;">
    <div class="link">
        <?php echo $this->Html->image('arrow-1.gif'); ?>
        どこまで情報共有するかマシン
    </div>
    <div class="info">
        「選手情報」「走った情報」を<br />
        誰に使ってもらうのか設定する<br />
        ページヘのリンクです。<br />
    </div>
</div>
<?php endif; ?>
<!-- Footer -->
