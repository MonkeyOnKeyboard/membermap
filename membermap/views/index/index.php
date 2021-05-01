<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>

    <div align="center">
        <br>
        <br>
        <span style=" font-size: 30px;">
            <a href="<?= $this->getUrl(['action' => 'treat'])?>" title="<?= $this->getTrans('mapEntry')?>"><?= $this->getTrans('mapEntry')?></a>
        </span>
        <br>
        <br>
        <span style=" font-size: 30px;">
            <a href="<?= $this->getUrl(['action' => 'map'])?>" title="<?= $this->getTrans('mapView')?>"><?= $this->getTrans('mapView')?></a>
        </span>            	
    </div>

</form>
