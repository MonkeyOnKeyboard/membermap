<?php $membermap = $this->get('memberEntry'); ?>

<h1><?=($membermap != '') ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form role="form" class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>
    <div class="form-group" <?=$this->validation()->hasError('user_id') ? 'has-error' : '' ?>>
        
        <div class="col-lg-4">

            <input class="form-control"
                   type="hidden"
                   id="user_id"
                   name="user_id"
                   value="<?php if ($membermap != '') { echo $this->escape($membermap->getUser_id()); } ?>" />
        </div>
    </div>
    <div class="form-group <?=$this->validation()->hasError('city') ? 'has-error' : '' ?>">
        <label for="city" class="col-lg-2 control-label">
            <?=$this->getTrans('city') ?>
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   id="city"
                   name="city"
                   value="<?php if ($membermap != '') { echo $this->escape($membermap->getCity()); } ?>" />
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
                   value="<?php if ($membermap != '') { echo $this->escape($membermap->getZip_code()); } ?>" />
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
                   value="<?php if ($membermap != '') { echo $this->escape($membermap->getCountry_code()); } ?>" />
        </div>
    </div>
    

    <?=($membermap != '') ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>
