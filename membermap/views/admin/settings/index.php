<form method="POST" class="form-horizontal" action="">
    <?=$this->getTokenField() ?>
    <div class="alert alert-info">
        <?= $this->getTrans('getyourkeys', '<a href="https://developer.mapquest.com/" target="_blank">https://developer.mapquest.com/</a>') ?>
    </div>
    <div class="form-group">
        <label for="apiKey" class="col-lg-2 control-label">
            <?=$this->getTrans('apiKeyLabel') ?>
        </label>
        <div class="col-lg-4">
            <input type="text" class="form-control" id="apiKey" name="apiKey" value="<?=$this->get('apiKey') ?>" placeholder="API-Key">
        </div>
    </div>
    

    <?=$this->getSaveBar(); ?>
</form>
