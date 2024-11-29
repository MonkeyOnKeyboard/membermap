<?php

/** @var \Ilch\View $this */

if ($this->get('memberlocations')) : ?>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="icon_width" />
            <col />
            <col />
            <col />
            <col />
            <col />
        </colgroup>
        <thead>
            <tr>
                <th></th>
                <th><?=$this->getTrans('username') ?></th>
                <th><?=$this->getTrans('street') ?></th>
                <th><?=$this->getTrans('city') ?></th>
                <th><?=$this->getTrans('zip_code') ?></th>
                <th><?=$this->getTrans('cc') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
        /** @var Modules\Membermap\Models\MemberMap $memberlocation */
        foreach ($this->get('memberlocations') as $memberlocation) : ?>
            <tr>
                <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $memberlocation->getId()]) ?></td>
                <td><?=$this->escape($memberlocation->getName()) ?></td>
                <td><?=$this->escape($memberlocation->getStreet()) ?></td>
                <td><?=$this->escape($memberlocation->getCity()) ?></td>
                <td><?=$this->escape($memberlocation->getZipCode()) ?></td>
                <td><?=$this->escape($memberlocation->getCountryCode()) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>  
<?php else : ?>  
<div class="alert alert-danger">
    <?=$this->getTrans('noEntries') ?>
</div>
<?php endif; ?>  
