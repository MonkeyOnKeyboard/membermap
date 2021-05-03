<?php
$membermap = $this->get('membermap');
?>
<h1><?=($membermap->getCity()) ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form role="form" class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    
    <div class="form-group <?=$this->validation()->hasError('city') ? 'has-error' : '' ?>">
        <label for="city" class="col-lg-2 control-label">
            <?=$this->getTrans('city') ?>
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   id="city"
                   name="city"
                   value="<?=$this->escape($this->originalInput('city', $membermap->getCity())) ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('zip_code') ? 'has-error' : '' ?>">
        <label for="zip_code" class="col-lg-2 control-label">
            <?=$this->getTrans('zip_code') ?>
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   id="zip_code"
                   name="zip_code"
                   value="<?=$this->escape($this->originalInput('zip_code', $membermap->getZip_code())) ?>" />
        </div>
    </div>
        <div class="form-group <?=$this->validation()->hasError('country_code') ? 'has-error' : '' ?>">
        <label for="country_code" class="col-lg-2 control-label">
            <?=$this->getTrans('country_code') ?>
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   id="country_code"
                   name="country_code"
                   value="<?=$this->escape($this->originalInput('country_code', $membermap->getCountry_code())) ?>" />
        </div>
    </div>
    

    <?php
    if ($membermap) {
        echo $this->getSaveBar('updateButton');
    ?>
    <a href="<?=$this->getUrl(['action' => 'del', 'user_id' => ($membermap)->getUser_id()], null, true) ?>" class="btn btn-danger" role="button"><?=$this->getTrans('delete') ?></a>
    <?php
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>
