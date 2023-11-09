<?php

// tcomp
// serie
// ncomp
// fecreg
// cliente
// direc
// dnro
// pisodto
// codpost
// codpais
// codprov
// codloc
// codrem
// totneto
// totbruto
// totiva105
// totiva21
// totimp
// totcomis
// totneto105
// totneto21
// nrengs
// nrodoc

?>
<?php if ($cabfaccon->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $cabfaccon->TableCaption() ?></h4> -->
<table id="tbl_cabfacconmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($cabfaccon->tcomp->Visible) { // tcomp ?>
		<tr id="r_tcomp">
			<td><?php echo $cabfaccon->tcomp->FldCaption() ?></td>
			<td<?php echo $cabfaccon->tcomp->CellAttributes() ?>>
<span id="el_cabfaccon_tcomp" class="form-group">
<span<?php echo $cabfaccon->tcomp->ViewAttributes() ?>>
<?php echo $cabfaccon->tcomp->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->serie->Visible) { // serie ?>
		<tr id="r_serie">
			<td><?php echo $cabfaccon->serie->FldCaption() ?></td>
			<td<?php echo $cabfaccon->serie->CellAttributes() ?>>
<span id="el_cabfaccon_serie" class="form-group">
<span<?php echo $cabfaccon->serie->ViewAttributes() ?>>
<?php echo $cabfaccon->serie->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->ncomp->Visible) { // ncomp ?>
		<tr id="r_ncomp">
			<td><?php echo $cabfaccon->ncomp->FldCaption() ?></td>
			<td<?php echo $cabfaccon->ncomp->CellAttributes() ?>>
<span id="el_cabfaccon_ncomp" class="form-group">
<span<?php echo $cabfaccon->ncomp->ViewAttributes() ?>>
<?php echo $cabfaccon->ncomp->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->fecreg->Visible) { // fecreg ?>
		<tr id="r_fecreg">
			<td><?php echo $cabfaccon->fecreg->FldCaption() ?></td>
			<td<?php echo $cabfaccon->fecreg->CellAttributes() ?>>
<span id="el_cabfaccon_fecreg" class="form-group">
<span<?php echo $cabfaccon->fecreg->ViewAttributes() ?>>
<?php echo $cabfaccon->fecreg->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->cliente->Visible) { // cliente ?>
		<tr id="r_cliente">
			<td><?php echo $cabfaccon->cliente->FldCaption() ?></td>
			<td<?php echo $cabfaccon->cliente->CellAttributes() ?>>
<span id="el_cabfaccon_cliente" class="form-group">
<span<?php echo $cabfaccon->cliente->ViewAttributes() ?>>
<?php echo $cabfaccon->cliente->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->direc->Visible) { // direc ?>
		<tr id="r_direc">
			<td><?php echo $cabfaccon->direc->FldCaption() ?></td>
			<td<?php echo $cabfaccon->direc->CellAttributes() ?>>
<span id="el_cabfaccon_direc" class="form-group">
<span<?php echo $cabfaccon->direc->ViewAttributes() ?>>
<?php echo $cabfaccon->direc->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->dnro->Visible) { // dnro ?>
		<tr id="r_dnro">
			<td><?php echo $cabfaccon->dnro->FldCaption() ?></td>
			<td<?php echo $cabfaccon->dnro->CellAttributes() ?>>
<span id="el_cabfaccon_dnro" class="form-group">
<span<?php echo $cabfaccon->dnro->ViewAttributes() ?>>
<?php echo $cabfaccon->dnro->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->pisodto->Visible) { // pisodto ?>
		<tr id="r_pisodto">
			<td><?php echo $cabfaccon->pisodto->FldCaption() ?></td>
			<td<?php echo $cabfaccon->pisodto->CellAttributes() ?>>
<span id="el_cabfaccon_pisodto" class="form-group">
<span<?php echo $cabfaccon->pisodto->ViewAttributes() ?>>
<?php echo $cabfaccon->pisodto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->codpost->Visible) { // codpost ?>
		<tr id="r_codpost">
			<td><?php echo $cabfaccon->codpost->FldCaption() ?></td>
			<td<?php echo $cabfaccon->codpost->CellAttributes() ?>>
<span id="el_cabfaccon_codpost" class="form-group">
<span<?php echo $cabfaccon->codpost->ViewAttributes() ?>>
<?php echo $cabfaccon->codpost->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->codpais->Visible) { // codpais ?>
		<tr id="r_codpais">
			<td><?php echo $cabfaccon->codpais->FldCaption() ?></td>
			<td<?php echo $cabfaccon->codpais->CellAttributes() ?>>
<span id="el_cabfaccon_codpais" class="form-group">
<span<?php echo $cabfaccon->codpais->ViewAttributes() ?>>
<?php echo $cabfaccon->codpais->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->codprov->Visible) { // codprov ?>
		<tr id="r_codprov">
			<td><?php echo $cabfaccon->codprov->FldCaption() ?></td>
			<td<?php echo $cabfaccon->codprov->CellAttributes() ?>>
<span id="el_cabfaccon_codprov" class="form-group">
<span<?php echo $cabfaccon->codprov->ViewAttributes() ?>>
<?php echo $cabfaccon->codprov->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->codloc->Visible) { // codloc ?>
		<tr id="r_codloc">
			<td><?php echo $cabfaccon->codloc->FldCaption() ?></td>
			<td<?php echo $cabfaccon->codloc->CellAttributes() ?>>
<span id="el_cabfaccon_codloc" class="form-group">
<span<?php echo $cabfaccon->codloc->ViewAttributes() ?>>
<?php echo $cabfaccon->codloc->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->codrem->Visible) { // codrem ?>
		<tr id="r_codrem">
			<td><?php echo $cabfaccon->codrem->FldCaption() ?></td>
			<td<?php echo $cabfaccon->codrem->CellAttributes() ?>>
<span id="el_cabfaccon_codrem" class="form-group">
<span<?php echo $cabfaccon->codrem->ViewAttributes() ?>>
<?php echo $cabfaccon->codrem->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->totneto->Visible) { // totneto ?>
		<tr id="r_totneto">
			<td><?php echo $cabfaccon->totneto->FldCaption() ?></td>
			<td<?php echo $cabfaccon->totneto->CellAttributes() ?>>
<span id="el_cabfaccon_totneto" class="form-group">
<span<?php echo $cabfaccon->totneto->ViewAttributes() ?>>
<?php echo $cabfaccon->totneto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->totbruto->Visible) { // totbruto ?>
		<tr id="r_totbruto">
			<td><?php echo $cabfaccon->totbruto->FldCaption() ?></td>
			<td<?php echo $cabfaccon->totbruto->CellAttributes() ?>>
<span id="el_cabfaccon_totbruto" class="form-group">
<span<?php echo $cabfaccon->totbruto->ViewAttributes() ?>>
<?php echo $cabfaccon->totbruto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->totiva105->Visible) { // totiva105 ?>
		<tr id="r_totiva105">
			<td><?php echo $cabfaccon->totiva105->FldCaption() ?></td>
			<td<?php echo $cabfaccon->totiva105->CellAttributes() ?>>
<span id="el_cabfaccon_totiva105" class="form-group">
<span<?php echo $cabfaccon->totiva105->ViewAttributes() ?>>
<?php echo $cabfaccon->totiva105->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->totiva21->Visible) { // totiva21 ?>
		<tr id="r_totiva21">
			<td><?php echo $cabfaccon->totiva21->FldCaption() ?></td>
			<td<?php echo $cabfaccon->totiva21->CellAttributes() ?>>
<span id="el_cabfaccon_totiva21" class="form-group">
<span<?php echo $cabfaccon->totiva21->ViewAttributes() ?>>
<?php echo $cabfaccon->totiva21->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->totimp->Visible) { // totimp ?>
		<tr id="r_totimp">
			<td><?php echo $cabfaccon->totimp->FldCaption() ?></td>
			<td<?php echo $cabfaccon->totimp->CellAttributes() ?>>
<span id="el_cabfaccon_totimp" class="form-group">
<span<?php echo $cabfaccon->totimp->ViewAttributes() ?>>
<?php echo $cabfaccon->totimp->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->totcomis->Visible) { // totcomis ?>
		<tr id="r_totcomis">
			<td><?php echo $cabfaccon->totcomis->FldCaption() ?></td>
			<td<?php echo $cabfaccon->totcomis->CellAttributes() ?>>
<span id="el_cabfaccon_totcomis" class="form-group">
<span<?php echo $cabfaccon->totcomis->ViewAttributes() ?>>
<?php echo $cabfaccon->totcomis->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->totneto105->Visible) { // totneto105 ?>
		<tr id="r_totneto105">
			<td><?php echo $cabfaccon->totneto105->FldCaption() ?></td>
			<td<?php echo $cabfaccon->totneto105->CellAttributes() ?>>
<span id="el_cabfaccon_totneto105" class="form-group">
<span<?php echo $cabfaccon->totneto105->ViewAttributes() ?>>
<?php echo $cabfaccon->totneto105->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->totneto21->Visible) { // totneto21 ?>
		<tr id="r_totneto21">
			<td><?php echo $cabfaccon->totneto21->FldCaption() ?></td>
			<td<?php echo $cabfaccon->totneto21->CellAttributes() ?>>
<span id="el_cabfaccon_totneto21" class="form-group">
<span<?php echo $cabfaccon->totneto21->ViewAttributes() ?>>
<?php echo $cabfaccon->totneto21->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->nrengs->Visible) { // nrengs ?>
		<tr id="r_nrengs">
			<td><?php echo $cabfaccon->nrengs->FldCaption() ?></td>
			<td<?php echo $cabfaccon->nrengs->CellAttributes() ?>>
<span id="el_cabfaccon_nrengs" class="form-group">
<span<?php echo $cabfaccon->nrengs->ViewAttributes() ?>>
<?php echo $cabfaccon->nrengs->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfaccon->nrodoc->Visible) { // nrodoc ?>
		<tr id="r_nrodoc">
			<td><?php echo $cabfaccon->nrodoc->FldCaption() ?></td>
			<td<?php echo $cabfaccon->nrodoc->CellAttributes() ?>>
<span id="el_cabfaccon_nrodoc" class="form-group">
<span<?php echo $cabfaccon->nrodoc->ViewAttributes() ?>>
<?php echo $cabfaccon->nrodoc->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
