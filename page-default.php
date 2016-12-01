<?php
	if (@$_POST['getnewaddress'])
		no_displayed_error_result($getnewaddress, multichain('getnewaddress'));
?>

			<div class="row">

				<div class="col-sm-6">
					<h3>My Node</h3>
<?php
	$getinfo=multichain_getinfo();

	if (is_array($getinfo)) {
?>
					<table class="table table-bordered table-striped">
						<tr>
							<th>Name</th>
							<td><?php=html($getinfo['chainname'])?></td>
						</tr>
						<tr>
							<th>Version</th>
							<td><?php=html($getinfo['version'])?></td>
						</tr>
						<tr>
							<th>Protocol</th>
							<td><?php=html($getinfo['protocolversion'])?></td>
						</tr>
						<tr>
							<th>Node address</th>
							<td><?php=html($getinfo['nodeaddress'])?></td>
						</tr>
						<tr>
							<th>Blocks</th>
							<td><?php=html($getinfo['blocks'])?></td>
						</tr>
						<tr>
							<th>Peers</th>
							<td><?php=html($getinfo['connections'])?></td>
						</tr>
					</table>
<?php
	}
?>

					<h3>Connected Nodes</h3>
<?php
	if (no_displayed_error_result($peerinfo, multichain('getpeerinfo'))) {
?>
					<table class="table table-bordered table-striped table-break-words">
<?php
		foreach ($peerinfo as $peer) {
?>
						<tr>
							<th>Node IP address</th>
							<td><?php=html(strtok($peer['addr'], ':'))?></td>
						</tr>
						<tr>
							<th>Handshake address</th>
							<td class="td-break-words small"><?php=html($peer['handshake'])?></td>
						</tr>
						<tr>
							<th>Latency</th>
							<td><?php=html(number_format($peer['pingtime'], 3))?> sec</td>
						</tr>
<?php
		}
?>
					</table>
<?php
	}
?>
				</div>
				<div class="col-sm-6">
					<h3>My Addresses</h3>
			
<?php
	if (no_displayed_error_result($getaddresses, multichain('getaddresses', true))) {
		$addressmine=array();
		
		foreach ($getaddresses as $getaddress)
			$addressmine[$getaddress['address']]=$getaddress['ismine'];
		
		$addresspermissions=array();
		
		if (no_displayed_error_result($listpermissions,
			multichain('listpermissions', 'all', implode(',', array_keys($addressmine)))
		))
			foreach ($listpermissions as $listpermission)
				$addresspermissions[$listpermission['address']][$listpermission['type']]=true;
		
		no_displayed_error_result($getmultibalances, multichain('getmultibalances', array(), array(), 0, true));
		
		$labels=multichain_labels();
	
		foreach ($addressmine as $address => $ismine) {
			if (count(@$addresspermissions[$address]))
				$permissions=implode(', ', @array_keys($addresspermissions[$address]));
			else
				$permissions='none';
				
			$label=@$labels[$address];
			$cansetlabel=$ismine && @$addresspermissions[$address]['send'];
			
			if ($ismine && !$cansetlabel)
				$permissions.=' (cannot set label)';
?>
						<table class="table table-bordered table-condensed table-break-words <?php=($address==@$getnewaddress) ? 'bg-success' : 'table-striped'?>">
<?php
			if (isset($label) || $cansetlabel) {
?>
							<tr>
								<th style="width:30%;">Label</th>
								<td><?php=html(@$label)?><?
								
				if ($cansetlabel)
					echo (isset($label) ? ' &ndash; ' : '').
					'<a href="'.chain_page_url_html($chain, 'label', array('address' => $address)).'">'.
					(isset($label) ? 'change label' : 'Set label').
					'</a>';
				
								?></td>
							</tr>
<?php
			}
?>
							<tr>
								<th style="width:30%;">Address</th>
								<td class="td-break-words small"><?php=html($address)?><?php=$ismine ? '' : ' (watch-only)'?></td>
							</tr>
							<tr>
								<th>Permissions</th>
								<td><?php=html($permissions)?><?

					echo ' &ndash; <a href="'.chain_page_url_html($chain, 'permissions', array('address' => $address)).'">change</a>';

							?></td></tr>
<?php
			if (isset($getmultibalances[$address])) {
				foreach ($getmultibalances[$address] as $addressbalance) {
?>
							<tr>
								<th><?php=html($addressbalance['name'])?></th>
								<td><?php=html($addressbalance['qty'])?></td>
							</tr>
<?php
				}
			}
?>
						</table>
<?php
		}
	}
?>
					<form class="form-horizontal" method="post" action="<?php=chain_page_url_html($chain)?>">
						<div class="form-group">
							<div class="col-xs-12">
								<input class="btn btn-default" name="getnewaddress" type="submit" value="Get new address">
							</div>
						</div>
					</form>
				</div>
			</div>
