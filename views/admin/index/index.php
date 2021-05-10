<div class="alert alert-info">
        <?=$this->getTrans('infoText') ?>
</div>
<?php if ($this->get('memberlocations')): ?>
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
                <th><?=$this->getTrans('user') ?></th>
                <th><?=$this->getTrans('street') ?></th>
                <th><?=$this->getTrans('city') ?></th>
                <th><?=$this->getTrans('zip_code') ?></th>
                <th><?=$this->getTrans('country_code') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($this->get('memberlocations') as $memberlocation): ?>
            <tr>
                <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $memberlocation->getId()]) ?></td>
                <td><?=$this->escape($memberlocation->getName() !== '' ? $memberlocation->getName() : 'ERROR ('.$memberlocation->getUser_Id().')') ?></td>
                <td><?=$this->escape($memberlocation->getStreet()) ?></td>
                <td><?=$this->escape($memberlocation->getCity()) ?></td>
                <td><?=$this->escape($memberlocation->getZip_code()) ?></td>
                <td><?=$this->escape($memberlocation->getCountry_code()) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>  
<?php else: ?>  
<div class="alert alert-danger">
        <?=$this->getTrans('nofailEntrys') ?>
</div>
<?php endif; ?>  
