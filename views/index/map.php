<?php

/** @var \Ilch\View $this */

if (!$this->get('apiKey')) : ?>
<div class="alert alert-danger">
    <?=$this->getTrans('noApiKey') ?>
</div>
<?php else :?>
<div class="alert alert-danger">
    <?=$this->getTrans('noMap') ?>
</div>
<?php endif; ?>
