<?php

/** @var \Ilch\View $this */

/** @var Modules\Membermap\Models\MemberMap $membermap */
$membermap = $this->get('membermap');
?>
<h1><?=($membermap->getCity()) ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form role="form" method="POST">
    <?=$this->getTokenField() ?>
    <div class="row mb-3">
        <label for="street" class="col-lg-4 col-form-label">
            <?=$this->getTrans('street') ?>
        </label>
        <div class="col-lg-8">
            <input class="form-control"
                   type="text"
                   id="street"
                   name="street"
                   value="<?=$this->escape($this->originalInput('street', $membermap->getStreet())) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('city') ? ' has-error' : '' ?>">
        <label for="city" class="col-lg-4 col-form-label">
            <?=$this->getTrans('city') ?>
        </label>
        <div class="col-lg-8">
            <input class="form-control"
                   type="text"
                   id="city"
                   name="city"
                   value="<?=$this->escape($this->originalInput('city', $membermap->getCity())) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('zip_code') ? ' has-error' : '' ?>">
        <label for="zip_code" class="col-lg-4 col-form-label">
            <?=$this->getTrans('zip_code') ?>
        </label>
        <div class="col-lg-8">
            <input class="form-control"
                   type="text"
                   id="zip_code"
                   name="zip_code"
                   value="<?=$this->escape($this->originalInput('zip_code', $membermap->getZipCode())) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('country_code') ? ' has-error' : '' ?>">
        <label for="country_code" class="col-lg-4 col-form-label">
            <?=$this->getTrans('country_code') ?>
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   type="text"
                   id="country_code"
                   name="country_code"
                   value="<?=$this->escape($this->originalInput('country_code', $membermap->getCountryCode())) ?>" />
        </div>
        <div class="col-lg-8 offset-lg-4"><?=$this->getTrans('cc_search') ?></div>
    </div>

    <a style="float:right;" href="<?=$this->getUrl(['action' => 'del', 'user_id' => ($membermap)->getUserid()], null, true) ?>" class="btn btn-danger" role="button"><?=$this->getTrans('delete') ?></a>
    <?=($membermap->getCity() != '') ?  $this->getSaveBar('updateButton') : $this->getSaveBar('add') ?>

</form>
