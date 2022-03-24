<div class="table-responsive">

    <table class="table table-hover table-striped">
        <colgroup>
            <col class="col-lg-1">
            <col class="col-lg-3">
            <col class="col-lg-1">
            <col class="col-lg-1">
            <col class="col-lg-1">
            <col class="col-lg-3">
            <col class="col-lg-3">
            <col class="col-lg-1">
            
        </colgroup>
        <thead>
            <tr>
                <th><?=$this->getTrans('membername') ?></th>
                <th><?=$this->getTrans('street') ?></th>
                <th><?=$this->getTrans('city') ?></th>
                <th><?=$this->getTrans('zip_code') ?></th>
                <th><?=$this->getTrans('cc') ?></th>
                <th><?=$this->getTrans('lat') ?></th>
                <th><?=$this->getTrans('lng') ?></th>
                <th><?=$this->getTrans('update') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($this->get('memberlocations') != ''): ?>

                    <?php foreach ($this->get('memberlocations') as $location): ?>
                        <tr id="<?=$location->getId() ?>">
                            <td><?=$this->escape($location->getName())?></td>
                            <td><?=$this->escape($location->getStreet()) ?></td>
                            <td><?=$this->escape($location->getCity()) ?></td>
                           	<td><?=$this->escape($location->getZip_code()) ?></td>
                           	<td><?=$this->escape($location->getCountry_code()) ?></td>
                           	<td><?=$this->escape($location->getLat()) ?></td>
                           	<td><?=$this->escape($location->getLng()) ?></td>
                           	<td><a href="<?=$this->getUrl(['action' => 'updateLatLng', 'user_id' => $location->getUser_Id()]) ?>"><i class="far fa-edit"></i></a></td>
                   <?php endforeach; ?>
                </tbody>
        <?php else: ?>
            <tr>
                <td colspan="7"><?=$this->getTrans('noEntrys') ?></td>
            </tr>
        <?php endif; ?>
    </table>
</div>

