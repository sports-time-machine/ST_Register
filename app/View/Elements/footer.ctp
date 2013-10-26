<!-- Footer -->
<?php if ($this->action == 'qrread' || $this->action == 'input_code') : ?>
<div class="sptmy">
    <div class="link">
        <?php echo $this->Html->image('arrow-0.gif'); ?>
        <a href="http://sptmy.net">SPTMせんしゅWEBサイトへ</a>
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
