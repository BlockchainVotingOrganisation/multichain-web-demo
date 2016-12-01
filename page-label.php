<?
	if (@$_POST['setlabel']) {
		$address=$_POST['address'];
		
		if (no_displayed_error_result($labeltxid, multichain(
			'publishfrom', $address, 'root', '', bin2hex($_POST['label'])
		)))
			output_success_text('Label successfully updated in transaction '.$labeltxid);
	} else
		$address=$_GET['address'];
	
	$labels=multichain_labels();
?>

			<div class="row">

				<div class="col-sm-12">
					<h3>Set address label</h3>
					
					<form class="form-horizontal" method="post" action="./?chain=<?php=html($_GET['chain'])?>&page=<?php=html($_GET['page'])?>">
						<div class="form-group">
							<label for="address" class="col-sm-2 control-label">For address:</label>
							<div class="col-sm-9">
								<input class="form-control" name="address" id="address" value="<?php=html($address)?>">
							</div>
						</div>
						<div class="form-group">
							<label for="label" class="col-sm-2 control-label">Label:</label>
							<div class="col-sm-9">
								<input class="form-control" name="label" id="label" value="<?php=html($labels[$address])?>">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-9">
								<input class="btn btn-default" type="submit" name="setlabel" value="Set Label">
							</div>
						</div>
					</form>

				</div>
			</div>