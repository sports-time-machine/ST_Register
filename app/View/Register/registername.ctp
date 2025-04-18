<script type="text/javascript">
$(function(){
    
    $(function(){
        $('button.radio').each(function(){
            if ($(this).attr('name') === $("#gender").val()){
                $(this).addClass("btn-info");
            }
        });
    });
    
    //エラーをモーダルで表示
    function showModal(mes){
        $('#result').html(mes); 
        $("#errorModal").modal("show");
    }
    
    $('#decide').click(function(){
        var errorStr = "";        
        var errorFlag = false;

        if (!$("#username").val()){
           errorStr += "<div><?= __("せんしゅめいを入力してください")?></div>";   
           errorFlag = true;
        }
               
        if (!$("#gender").val()){
           errorStr += "<div><?= __("せいべつを選んでください")?></div>";   
           errorFlag = true;
        }
        
        if (errorFlag === false) $('form').submit();
        else showModal(errorStr);
        
        return false;
    });
  
    $('button.radio').click(function() {
        
        $('button.radio').each(function(){
            $(this).removeClass("btn-info");
        });
        
        $("#gender").val($(this).attr('name'));
        $(this).addClass("btn-info");
    });
    
});
</script>
<div class="clear">
    <form action="<?php echo $this->Html->webroot?>Register/confirm" id="RegisternameForm" method="post" accept-charset="utf-8">
        <div id="register_name">
                <div><?=__("せんしゅID")?>:<?php echo $register['player_id']; ?></div>

		<div style="margin-top: 16px;"></div>

                <div><?=__("せんしゅめいを入力してください")?></div>
                <div>
                    <?php echo $this->Form->text('username',array('label' => false, 'default' => $register['name'] , "maxlength" => $maxlength, 'autocomplete' => 'off')); ?>
                </div>

        <div style="margin-top: 16px;"></div>

                <div><?=__("せいべつを選んでください")?></div>
                <?php echo $this->Form->hidden('gender',array('default' => $register['gender'])); ?>
                <div class="btn-group" data-toggle="buttons-radio">
                    <button type="button" class="btn radio " name="male"><?=__("男性(おとこのこ)")?></button>
                    <button type="button" class="btn radio " name="female"><?=__("女性(おんなのこ)")?></button>
                    <button type="button" class="btn radio " name="other"><?=__("その他(そのた)")?></button>         
                </div>

        <div style="margin-top: 16px;"></div>
                <div><?=__("ねんれいを選んでください")?></div>
                <div>
                    <?php 
                        $selectAges = [];
                        foreach ($ages as $age) {
                            $selectAges []= $age.__("さい");
                        }
                    ?>
                    <?php echo $this->Form->select('age', $selectAges, array('label'=>false, 'class' => 'btn', 'default' => $register['age'], 'empty' => false)); ?>  
                </div>
                <br />
                <?php echo $this->Form->hidden('player_id'); ?>
                <div>
                    <?php echo $this->Form->button(__('けってい'),array('label' => false, 'class' => 'btn', 'id' => 'decide')); ?>    
                </div>
                <div class="modal hide fade" id="errorModal">
                    <div class="error modal-body" id="result"></div>
                </div>
<!--        <div>
            <div>ここで「せんしゅめい」を入力しておくと</div>
            <div>お家のインターネットで自分の情報を見たり、変更したりできます</div>
            <?php echo $this->Form->button(__('入力しない'),array('label' => false, 'class' => 'btn', 'id' => 'noName')); ?>
            <div>ちゅうい！</div>
            <div>「せんしゅめい」を忘れた場合は、過去の情報は見られなくなります</div>
            <div>忘れないでください</div>
        </div>-->
    </form>
</div>