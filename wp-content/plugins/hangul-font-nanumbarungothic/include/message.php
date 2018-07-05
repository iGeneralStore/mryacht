<?php if ($this->Msg): ?>
    <div class='updated'>
        <p><?php echo $this->Msg; ?></p>
    </div>
<?php endif ?>
<?php if($_REQUEST['settings-updated']=='true'): ?>
    <div class='updated'>
        <p>설정을 저장하였습니다.</p>
    </div>
<?php endif ?>
