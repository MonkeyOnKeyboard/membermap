<?php

/** @var \Ilch\View $this */
?>
<div class="text-center">
<?php if ($this->getUser()) { ?>
    <a class="btn btn-outline-secondary" href="<?= $this->getUrl(['action' => 'treat'])?>" title="<?= $this->getTrans('mapEntry')?>"><?= $this->getTrans('mapEntry')?></a> &nbsp;
<?php } ?>
    <a class="btn btn-outline-secondary" href="<?= $this->getUrl(['action' => 'map'])?>" title="<?= $this->getTrans('mapView')?>"><?= $this->getTrans('mapView')?></a>
</div>
<h1><?=$this->getTrans('membermap') ?></h1>
<?php if (!$this->get('apiKey')) : ?>
<div class="alert alert-danger">
    <?=$this->getTrans('noApiKey') ?>
</div>
<?php else :?>
<div class="alert alert-danger">
    <?=$this->getTrans('noMap') ?>
</div>
<?php endif; ?>
