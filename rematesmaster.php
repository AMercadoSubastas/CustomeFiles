<?php

// ncomp
// codcli
// direccion
// codpais
// codprov
// codloc
// fecreal
// horareal
// observacion
// tipoind
// sello
// plazoSAP
// servicios
// gastos
// tasa

?>
<?php if ($remates->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $remates->TableCaption() ?></h4> -->
<div id="t_remates" class="<?php if (ew_IsResponsiveLayout()) echo "table-responsive "; ?>ewGrid">
<table id="tbl_rematesmaster" class="table ewTable">
	<thead>
		<tr>
<?php if ($remates->ncomp->Visible) { // ncomp ?>
			<th class="ewTableHeader"><?php echo $remates->ncomp->FldCaption() ?></th>
<?php } ?>
<?php if ($remates->codcli->Visible) { // codcli ?>
			<th class="ewTableHeader"><?php echo $remates->codcli->FldCaption() ?></th>
<?php } ?>
<?php if ($remates->direccion->Visible) { // direccion ?>
			<th class="ewTableHeader"><?php echo $remates->direccion->FldCaption() ?></th>
<?php } ?>
<?php if ($remates->codpais->Visible) { // codpais ?>
			<th class="ewTableHeader"><?php echo $remates->codpais->FldCaption() ?></th>
<?php } ?>
<?php if ($remates->codprov->Visible) { // codprov ?>
			<th class="ewTableHeader"><?php echo $remates->codprov->FldCaption() ?></th>
<?php } ?>
<?php if ($remates->codloc->Visible) { // codloc ?>
			<th class="ewTableHeader"><?php echo $remates->codloc->FldCaption() ?></th>
<?php } ?>
<?php if ($remates->fecreal->Visible) { // fecreal ?>
			<th class="ewTableHeader"><?php echo $remates->fecreal->FldCaption() ?></th>
<?php } ?>
<?php if ($remates->horareal->Visible) { // horareal ?>
			<th class="ewTableHeader"><?php echo $remates->horareal->FldCaption() ?></th>
<?php } ?>
<?php if ($remates->observacion->Visible) { // observacion ?>
			<th class="ewTableHeader"><?php echo $remates->observacion->FldCaption() ?></th>
<?php } ?>
<?php if ($remates->tipoind->Visible) { // tipoind ?>
			<th class="ewTableHeader"><?php echo $remates->tipoind->FldCaption() ?></th>
<?php } ?>
<?php if ($remates->sello->Visible) { // sello ?>
			<th class="ewTableHeader"><?php echo $remates->sello->FldCaption() ?></th>
<?php } ?>
<?php if ($remates->plazoSAP->Visible) { // plazoSAP ?>
			<th class="ewTableHeader"><?php echo $remates->plazoSAP->FldCaption() ?></th>
<?php } ?>
<?php if ($remates->servicios->Visible) { // servicios ?>
			<th class="ewTableHeader"><?php echo $remates->servicios->FldCaption() ?></th>
<?php } ?>
<?php if ($remates->gastos->Visible) { // gastos ?>
			<th class="ewTableHeader"><?php echo $remates->gastos->FldCaption() ?></th>
<?php } ?>
<?php if ($remates->tasa->Visible) { // tasa ?>
			<th class="ewTableHeader"><?php echo $remates->tasa->FldCaption() ?></th>
<?php } ?>
		</tr>
	</thead>
	<tbody>
		<tr>
<?php if ($remates->ncomp->Visible) { // ncomp ?>
			<td<?php echo $remates->ncomp->CellAttributes() ?>>
<span id="el_remates_ncomp" class="form-group">
<span<?php echo $remates->ncomp->ViewAttributes() ?>>
<?php echo $remates->ncomp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($remates->codcli->Visible) { // codcli ?>
			<td<?php echo $remates->codcli->CellAttributes() ?>>
<span id="el_remates_codcli" class="form-group">
<span<?php echo $remates->codcli->ViewAttributes() ?>>
<?php echo $remates->codcli->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($remates->direccion->Visible) { // direccion ?>
			<td<?php echo $remates->direccion->CellAttributes() ?>>
<span id="el_remates_direccion" class="form-group">
<span<?php echo $remates->direccion->ViewAttributes() ?>>
<?php echo $remates->direccion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($remates->codpais->Visible) { // codpais ?>
			<td<?php echo $remates->codpais->CellAttributes() ?>>
<span id="el_remates_codpais" class="form-group">
<span<?php echo $remates->codpais->ViewAttributes() ?>>
<?php echo $remates->codpais->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($remates->codprov->Visible) { // codprov ?>
			<td<?php echo $remates->codprov->CellAttributes() ?>>
<span id="el_remates_codprov" class="form-group">
<span<?php echo $remates->codprov->ViewAttributes() ?>>
<?php echo $remates->codprov->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($remates->codloc->Visible) { // codloc ?>
			<td<?php echo $remates->codloc->CellAttributes() ?>>
<span id="el_remates_codloc" class="form-group">
<span<?php echo $remates->codloc->ViewAttributes() ?>>
<?php echo $remates->codloc->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($remates->fecreal->Visible) { // fecreal ?>
			<td<?php echo $remates->fecreal->CellAttributes() ?>>
<span id="el_remates_fecreal" class="form-group">
<span<?php echo $remates->fecreal->ViewAttributes() ?>>
<?php echo $remates->fecreal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($remates->horareal->Visible) { // horareal ?>
			<td<?php echo $remates->horareal->CellAttributes() ?>>
<span id="el_remates_horareal" class="form-group">
<span<?php echo $remates->horareal->ViewAttributes() ?>>
<?php echo $remates->horareal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($remates->observacion->Visible) { // observacion ?>
			<td<?php echo $remates->observacion->CellAttributes() ?>>
<span id="el_remates_observacion" class="form-group">
<span<?php echo $remates->observacion->ViewAttributes() ?>>
<?php echo $remates->observacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($remates->tipoind->Visible) { // tipoind ?>
			<td<?php echo $remates->tipoind->CellAttributes() ?>>
<span id="el_remates_tipoind" class="form-group">
<span<?php echo $remates->tipoind->ViewAttributes() ?>>
<?php echo $remates->tipoind->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($remates->sello->Visible) { // sello ?>
			<td<?php echo $remates->sello->CellAttributes() ?>>
<span id="el_remates_sello" class="form-group">
<span<?php echo $remates->sello->ViewAttributes() ?>>
<?php echo $remates->sello->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($remates->plazoSAP->Visible) { // plazoSAP ?>
			<td<?php echo $remates->plazoSAP->CellAttributes() ?>>
<span id="el_remates_plazoSAP" class="form-group">
<span<?php echo $remates->plazoSAP->ViewAttributes() ?>>
<?php echo $remates->plazoSAP->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($remates->servicios->Visible) { // servicios ?>
			<td<?php echo $remates->servicios->CellAttributes() ?>>
<span id="el_remates_servicios" class="form-group">
<span<?php echo $remates->servicios->ViewAttributes() ?>>
<?php echo $remates->servicios->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($remates->gastos->Visible) { // gastos ?>
			<td<?php echo $remates->gastos->CellAttributes() ?>>
<span id="el_remates_gastos" class="form-group">
<span<?php echo $remates->gastos->ViewAttributes() ?>>
<?php echo $remates->gastos->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($remates->tasa->Visible) { // tasa ?>
			<td<?php echo $remates->tasa->CellAttributes() ?>>
<span id="el_remates_tasa" class="form-group">
<span<?php echo $remates->tasa->ViewAttributes() ?>>
<?php echo $remates->tasa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
		</tr>
	</tbody>
</table>
</div>
<?php } ?>
