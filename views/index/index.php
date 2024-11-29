<?php

/** @var \Ilch\View $this */
?>
<form method="POST" action="">
    <?=$this->getTokenField() ?>

    <div align="center">
        <?php if ($this->getUser()) { ?>
        <a class="btn btn-outline-secondary" href="<?= $this->getUrl(['action' => 'treat'])?>" title="<?= $this->getTrans('mapEntry')?>"><?= $this->getTrans('mapEntry')?></a> &nbsp;
        <?php } ?>
        <a class="btn btn-outline-secondary" href="<?= $this->getUrl(['action' => 'map'])?>" title="<?= $this->getTrans('mapView')?>"><?= $this->getTrans('mapView')?></a>
    </div>

</form>
