<form method="POST" class="form-horizontal" action="">
    <?=$this->getTokenField() ?>
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
