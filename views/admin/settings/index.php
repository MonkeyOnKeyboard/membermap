<form method="POST" class="form-horizontal">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="service" class="col-lg-2 control-label">
            <?=$this->getTrans('service') ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" id="service" name="service">
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
    <div id="apiKey" class="form-group">
        <label for="apiKey" class="col-lg-2 control-label">
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
            $('#apiKey').removeClass('hidden');
            $('#apikey_info_mapquest').removeClass('hidden');
            $('#apikey_info_google').addClass('hidden');
            $('#apikey_info_osm').addClass('hidden');
        } else if ($(this).val() === "2") {
            $('#apiKey').removeClass('hidden');
            $('#apikey_info_mapquest').addClass('hidden');
            $('#apikey_info_google').removeClass('hidden');
            $('#apikey_info_osm').addClass('hidden');
        } else if ($(this).val() === "3") {
            $('#apiKey').removeClass('hidden');
            $('#apikey_info_mapquest').addClass('hidden');
            $('#apikey_info_google').addClass('hidden');
            $('#apikey_info_osm').removeClass('hidden');
        } else {
            $('#apiKey').addClass('hidden');
            $('#apikey_info_mapquest').addClass('hidden');
            $('#apikey_info_google').addClass('hidden');
            $('#apikey_info_osm').addClass('hidden');
        }
    });
    $('[name="service"]').change();
</script>
