<form class="form-horizontal" method="POST" action="">
<?=$this->getTokenField() ?>
<?php if ($this->get('memberlocations') != ''): ?>
<div class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                </colgroup>
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th><?=$this->getTrans('id') ?></th>
                        <th><?=$this->getTrans('user_id') ?></th>
                        <th><?=$this->getTrans('city') ?></th>
                        <th><?=$this->getTrans('zip_code') ?></th>
                        <th><?=$this->getTrans('country_code') ?></th>
                	</tr>
                </thead>
                <tbody>
                    <?php foreach ($this->get('memberlocations') as $memberlocation): ?>
                    	<tr>
                            <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $memberlocation->getId()]) ?></td>
                            <td></td>
                            <td><?=$this->escape($memberlocation->getId()) ?></td>
                            <td><?=$this->escape($memberlocation->getUser_id()) ?></td>
                            <td><?=$this->escape($memberlocation->getCity()) ?></td>
                            <td><?=$this->escape($memberlocation->getZip_code()) ?></td>
                            <td><?=$this->escape($memberlocation->getCountry_code()) ?></td>
                        </tr>
					<?php endforeach; ?>
                </tbody>
            </table>
	
        </div>  
  
<?php else: ?>
		<div align="center">
		<br>
		<br>
			<span style=" font-size: 30px;"><?=$this->getTrans('noEntrys') ?></span>
		</div>	  
		
<?php endif; ?>  
  
	


</form>
