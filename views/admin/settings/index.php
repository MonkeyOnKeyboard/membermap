<?php

/** @var \Ilch\View $this */
?>
<form method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <label for="service" class="col-lg-2 col-form-label">
            <?=$this->getTrans('service') ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-select" id="service" name="service">
                <option disabled <?=(!$this->get('service') ? 'selected="selected"' : '') ?> value="0"><?=$this->getTrans('pleaseselect') ?></option>
                <option <?=($this->get('service') == 1 ? 'selected="selected"' : '') ?> value="1">MapQuest</option>
                <option <?=($this->get('service') == 2 ? 'selected="selected"' : '') ?> value="2">Google</option>
                <option <?=($this->get('service') == 3 ? 'selected="selected"' : '') ?> value="3">OSM</option>
            </select>
        </div>
    </div>
    <div id="apikey_info_mapquest" class="alert alert-info">
        <?= $this->getTrans('getyourkeys', '<a href="https://developer.mapquest.com/" target="_blank">https://developer.mapquest.com/</a>') ?>
    </div>
    <div id="apikey_info_google" class="alert alert-info">
        <?= $this->getTrans('getyourkeys', '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key/" target="_blank">https://developers.google.com/maps/documentation/javascript/get-api-key/</a>') ?>
    </div>
    <div id="apikey_info_osm" class="alert alert-info">
        <?= $this->getTrans('nokeysneed') ?>
    </div>
    <div id="apiKey" class="row mb-3">
        <label for="apiKey" class="col-lg-2 col-form-label">
            <?=$this->getTrans('apiKeyLabel') ?>
        </label>
        <div class="col-lg-4">
            <input type="text" class="form-control" id="apiKey" name="apiKey" value="<?=$this->get('apiKey') ?>" placeholder="API-Key">
        </div>
    </div>

    <?=$this->getSaveBar(); ?>
</form>
<script>
    $('[name="service"]').change(function () {
        if ($(this).val() === "1") {
            $('#apiKey').removeAttr('hidden');
            $('#apikey_info_mapquest').removeAttr('hidden');
            $('#apikey_info_google').attr('hidden', '');
            $('#apikey_info_osm').attr('hidden', '');
        } else if ($(this).val() === "2") {
            $('#apiKey').removeAttr('hidden');
            $('#apikey_info_mapquest').attr('hidden', '');
            $('#apikey_info_google').removeAttr('hidden');
            $('#apikey_info_osm').attr('hidden', '');
        } else if ($(this).val() === "3") {
            $('#apiKey').removeAttr('hidden');
            $('#apikey_info_mapquest').attr('hidden', '');
            $('#apikey_info_google').attr('hidden', '');
            $('#apikey_info_osm').removeAttr('hidden');
        } else {
            $('#apiKey').attr('hidden', '');
            $('#apikey_info_mapquest').attr('hidden', '');
            $('#apikey_info_google').attr('hidden', '');
            $('#apikey_info_osm').attr('hidden', '');
        }
    });
    $('[name="service"]').change();
</script>
