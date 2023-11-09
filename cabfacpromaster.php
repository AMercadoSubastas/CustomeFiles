<?php

// codnum
// tcomp
// serie
// ncomp
// fecval
// fecdoc
// fecreg
// cliente
// cpago
// fecvenc
// direc
// dnro
// pisodto
// codpost
// codpais
// codprov
// codloc
// telef
// codrem
// estado
// emitido
// moneda
// totneto
// totbruto
// totiva105
// totiva21
// totimp
// totcomis
// totneto105
// totneto21
// tipoiva
// porciva
// nrengs
// fechahora
// usuario
// tieneresol
// concepto
// nrodoc
// tcompsal
// seriesal
// ncompsal

?>
<?php if ($cabfacpro->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $cabfacpro->TableCaption() ?></h4> -->
<table id="tbl_cabfacpromaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($cabfacpro->codnum->Visible) { // codnum ?>
		<tr id="r_codnum">
			<td><?php echo $cabfacpro->codnum->FldCaption() ?></td>
			<td<?php echo $cabfacpro->codnum->CellAttributes() ?>>
<span id="el_cabfacpro_codnum" class="form-group">
<span<?php echo $cabfacpro->codnum->ViewAttributes() ?>>
<?php echo $cabfacpro->codnum->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->tcomp->Visible) { // tcomp ?>
		<tr id="r_tcomp">
			<td><?php echo $cabfacpro->tcomp->FldCaption() ?></td>
			<td<?php echo $cabfacpro->tcomp->CellAttributes() ?>>
<span id="el_cabfacpro_tcomp" class="form-group">
<span<?php echo $cabfacpro->tcomp->ViewAttributes() ?>>
<?php echo $cabfacpro->tcomp->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->serie->Visible) { // serie ?>
		<tr id="r_serie">
			<td><?php echo $cabfacpro->serie->FldCaption() ?></td>
			<td<?php echo $cabfacpro->serie->CellAttributes() ?>>
<span id="el_cabfacpro_serie" class="form-group">
<span<?php echo $cabfacpro->serie->ViewAttributes() ?>>
<?php echo $cabfacpro->serie->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->ncomp->Visible) { // ncomp ?>
		<tr id="r_ncomp">
			<td><?php echo $cabfacpro->ncomp->FldCaption() ?></td>
			<td<?php echo $cabfacpro->ncomp->CellAttributes() ?>>
<span id="el_cabfacpro_ncomp" class="form-group">
<span<?php echo $cabfacpro->ncomp->ViewAttributes() ?>>
<?php echo $cabfacpro->ncomp->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->fecval->Visible) { // fecval ?>
		<tr id="r_fecval">
			<td><?php echo $cabfacpro->fecval->FldCaption() ?></td>
			<td<?php echo $cabfacpro->fecval->CellAttributes() ?>>
<span id="el_cabfacpro_fecval" class="form-group">
<span<?php echo $cabfacpro->fecval->ViewAttributes() ?>>
<?php echo $cabfacpro->fecval->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->fecdoc->Visible) { // fecdoc ?>
		<tr id="r_fecdoc">
			<td><?php echo $cabfacpro->fecdoc->FldCaption() ?></td>
			<td<?php echo $cabfacpro->fecdoc->CellAttributes() ?>>
<span id="el_cabfacpro_fecdoc" class="form-group">
<span<?php echo $cabfacpro->fecdoc->ViewAttributes() ?>>
<?php echo $cabfacpro->fecdoc->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->fecreg->Visible) { // fecreg ?>
		<tr id="r_fecreg">
			<td><?php echo $cabfacpro->fecreg->FldCaption() ?></td>
			<td<?php echo $cabfacpro->fecreg->CellAttributes() ?>>
<span id="el_cabfacpro_fecreg" class="form-group">
<span<?php echo $cabfacpro->fecreg->ViewAttributes() ?>>
<?php echo $cabfacpro->fecreg->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->cliente->Visible) { // cliente ?>
		<tr id="r_cliente">
			<td><?php echo $cabfacpro->cliente->FldCaption() ?></td>
			<td<?php echo $cabfacpro->cliente->CellAttributes() ?>>
<span id="el_cabfacpro_cliente" class="form-group">
<span<?php echo $cabfacpro->cliente->ViewAttributes() ?>>
<?php echo $cabfacpro->cliente->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->cpago->Visible) { // cpago ?>
		<tr id="r_cpago">
			<td><?php echo $cabfacpro->cpago->FldCaption() ?></td>
			<td<?php echo $cabfacpro->cpago->CellAttributes() ?>>
<span id="el_cabfacpro_cpago" class="form-group">
<span<?php echo $cabfacpro->cpago->ViewAttributes() ?>>
<?php echo $cabfacpro->cpago->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->fecvenc->Visible) { // fecvenc ?>
		<tr id="r_fecvenc">
			<td><?php echo $cabfacpro->fecvenc->FldCaption() ?></td>
			<td<?php echo $cabfacpro->fecvenc->CellAttributes() ?>>
<span id="el_cabfacpro_fecvenc" class="form-group">
<span<?php echo $cabfacpro->fecvenc->ViewAttributes() ?>>
<?php echo $cabfacpro->fecvenc->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->direc->Visible) { // direc ?>
		<tr id="r_direc">
			<td><?php echo $cabfacpro->direc->FldCaption() ?></td>
			<td<?php echo $cabfacpro->direc->CellAttributes() ?>>
<span id="el_cabfacpro_direc" class="form-group">
<span<?php echo $cabfacpro->direc->ViewAttributes() ?>>
<?php echo $cabfacpro->direc->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->dnro->Visible) { // dnro ?>
		<tr id="r_dnro">
			<td><?php echo $cabfacpro->dnro->FldCaption() ?></td>
			<td<?php echo $cabfacpro->dnro->CellAttributes() ?>>
<span id="el_cabfacpro_dnro" class="form-group">
<span<?php echo $cabfacpro->dnro->ViewAttributes() ?>>
<?php echo $cabfacpro->dnro->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->pisodto->Visible) { // pisodto ?>
		<tr id="r_pisodto">
			<td><?php echo $cabfacpro->pisodto->FldCaption() ?></td>
			<td<?php echo $cabfacpro->pisodto->CellAttributes() ?>>
<span id="el_cabfacpro_pisodto" class="form-group">
<span<?php echo $cabfacpro->pisodto->ViewAttributes() ?>>
<?php echo $cabfacpro->pisodto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->codpost->Visible) { // codpost ?>
		<tr id="r_codpost">
			<td><?php echo $cabfacpro->codpost->FldCaption() ?></td>
			<td<?php echo $cabfacpro->codpost->CellAttributes() ?>>
<span id="el_cabfacpro_codpost" class="form-group">
<span<?php echo $cabfacpro->codpost->ViewAttributes() ?>>
<?php echo $cabfacpro->codpost->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->codpais->Visible) { // codpais ?>
		<tr id="r_codpais">
			<td><?php echo $cabfacpro->codpais->FldCaption() ?></td>
			<td<?php echo $cabfacpro->codpais->CellAttributes() ?>>
<span id="el_cabfacpro_codpais" class="form-group">
<span<?php echo $cabfacpro->codpais->ViewAttributes() ?>>
<?php echo $cabfacpro->codpais->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->codprov->Visible) { // codprov ?>
		<tr id="r_codprov">
			<td><?php echo $cabfacpro->codprov->FldCaption() ?></td>
			<td<?php echo $cabfacpro->codprov->CellAttributes() ?>>
<span id="el_cabfacpro_codprov" class="form-group">
<span<?php echo $cabfacpro->codprov->ViewAttributes() ?>>
<?php echo $cabfacpro->codprov->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->codloc->Visible) { // codloc ?>
		<tr id="r_codloc">
			<td><?php echo $cabfacpro->codloc->FldCaption() ?></td>
			<td<?php echo $cabfacpro->codloc->CellAttributes() ?>>
<span id="el_cabfacpro_codloc" class="form-group">
<span<?php echo $cabfacpro->codloc->ViewAttributes() ?>>
<?php echo $cabfacpro->codloc->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->telef->Visible) { // telef ?>
		<tr id="r_telef">
			<td><?php echo $cabfacpro->telef->FldCaption() ?></td>
			<td<?php echo $cabfacpro->telef->CellAttributes() ?>>
<span id="el_cabfacpro_telef" class="form-group">
<span<?php echo $cabfacpro->telef->ViewAttributes() ?>>
<?php echo $cabfacpro->telef->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->codrem->Visible) { // codrem ?>
		<tr id="r_codrem">
			<td><?php echo $cabfacpro->codrem->FldCaption() ?></td>
			<td<?php echo $cabfacpro->codrem->CellAttributes() ?>>
<span id="el_cabfacpro_codrem" class="form-group">
<span<?php echo $cabfacpro->codrem->ViewAttributes() ?>>
<?php echo $cabfacpro->codrem->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $cabfacpro->estado->FldCaption() ?></td>
			<td<?php echo $cabfacpro->estado->CellAttributes() ?>>
<span id="el_cabfacpro_estado" class="form-group">
<span<?php echo $cabfacpro->estado->ViewAttributes() ?>>
<?php echo $cabfacpro->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->emitido->Visible) { // emitido ?>
		<tr id="r_emitido">
			<td><?php echo $cabfacpro->emitido->FldCaption() ?></td>
			<td<?php echo $cabfacpro->emitido->CellAttributes() ?>>
<span id="el_cabfacpro_emitido" class="form-group">
<span<?php echo $cabfacpro->emitido->ViewAttributes() ?>>
<?php echo $cabfacpro->emitido->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->moneda->Visible) { // moneda ?>
		<tr id="r_moneda">
			<td><?php echo $cabfacpro->moneda->FldCaption() ?></td>
			<td<?php echo $cabfacpro->moneda->CellAttributes() ?>>
<span id="el_cabfacpro_moneda" class="form-group">
<span<?php echo $cabfacpro->moneda->ViewAttributes() ?>>
<?php echo $cabfacpro->moneda->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->totneto->Visible) { // totneto ?>
		<tr id="r_totneto">
			<td><?php echo $cabfacpro->totneto->FldCaption() ?></td>
			<td<?php echo $cabfacpro->totneto->CellAttributes() ?>>
<span id="el_cabfacpro_totneto" class="form-group">
<span<?php echo $cabfacpro->totneto->ViewAttributes() ?>>
<?php echo $cabfacpro->totneto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->totbruto->Visible) { // totbruto ?>
		<tr id="r_totbruto">
			<td><?php echo $cabfacpro->totbruto->FldCaption() ?></td>
			<td<?php echo $cabfacpro->totbruto->CellAttributes() ?>>
<span id="el_cabfacpro_totbruto" class="form-group">
<span<?php echo $cabfacpro->totbruto->ViewAttributes() ?>>
<?php echo $cabfacpro->totbruto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->totiva105->Visible) { // totiva105 ?>
		<tr id="r_totiva105">
			<td><?php echo $cabfacpro->totiva105->FldCaption() ?></td>
			<td<?php echo $cabfacpro->totiva105->CellAttributes() ?>>
<span id="el_cabfacpro_totiva105" class="form-group">
<span<?php echo $cabfacpro->totiva105->ViewAttributes() ?>>
<?php echo $cabfacpro->totiva105->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->totiva21->Visible) { // totiva21 ?>
		<tr id="r_totiva21">
			<td><?php echo $cabfacpro->totiva21->FldCaption() ?></td>
			<td<?php echo $cabfacpro->totiva21->CellAttributes() ?>>
<span id="el_cabfacpro_totiva21" class="form-group">
<span<?php echo $cabfacpro->totiva21->ViewAttributes() ?>>
<?php echo $cabfacpro->totiva21->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->totimp->Visible) { // totimp ?>
		<tr id="r_totimp">
			<td><?php echo $cabfacpro->totimp->FldCaption() ?></td>
			<td<?php echo $cabfacpro->totimp->CellAttributes() ?>>
<span id="el_cabfacpro_totimp" class="form-group">
<span<?php echo $cabfacpro->totimp->ViewAttributes() ?>>
<?php echo $cabfacpro->totimp->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->totcomis->Visible) { // totcomis ?>
		<tr id="r_totcomis">
			<td><?php echo $cabfacpro->totcomis->FldCaption() ?></td>
			<td<?php echo $cabfacpro->totcomis->CellAttributes() ?>>
<span id="el_cabfacpro_totcomis" class="form-group">
<span<?php echo $cabfacpro->totcomis->ViewAttributes() ?>>
<?php echo $cabfacpro->totcomis->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->totneto105->Visible) { // totneto105 ?>
		<tr id="r_totneto105">
			<td><?php echo $cabfacpro->totneto105->FldCaption() ?></td>
			<td<?php echo $cabfacpro->totneto105->CellAttributes() ?>>
<span id="el_cabfacpro_totneto105" class="form-group">
<span<?php echo $cabfacpro->totneto105->ViewAttributes() ?>>
<?php echo $cabfacpro->totneto105->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->totneto21->Visible) { // totneto21 ?>
		<tr id="r_totneto21">
			<td><?php echo $cabfacpro->totneto21->FldCaption() ?></td>
			<td<?php echo $cabfacpro->totneto21->CellAttributes() ?>>
<span id="el_cabfacpro_totneto21" class="form-group">
<span<?php echo $cabfacpro->totneto21->ViewAttributes() ?>>
<?php echo $cabfacpro->totneto21->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->tipoiva->Visible) { // tipoiva ?>
		<tr id="r_tipoiva">
			<td><?php echo $cabfacpro->tipoiva->FldCaption() ?></td>
			<td<?php echo $cabfacpro->tipoiva->CellAttributes() ?>>
<span id="el_cabfacpro_tipoiva" class="form-group">
<span<?php echo $cabfacpro->tipoiva->ViewAttributes() ?>>
<?php echo $cabfacpro->tipoiva->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->porciva->Visible) { // porciva ?>
		<tr id="r_porciva">
			<td><?php echo $cabfacpro->porciva->FldCaption() ?></td>
			<td<?php echo $cabfacpro->porciva->CellAttributes() ?>>
<span id="el_cabfacpro_porciva" class="form-group">
<span<?php echo $cabfacpro->porciva->ViewAttributes() ?>>
<?php echo $cabfacpro->porciva->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->nrengs->Visible) { // nrengs ?>
		<tr id="r_nrengs">
			<td><?php echo $cabfacpro->nrengs->FldCaption() ?></td>
			<td<?php echo $cabfacpro->nrengs->CellAttributes() ?>>
<span id="el_cabfacpro_nrengs" class="form-group">
<span<?php echo $cabfacpro->nrengs->ViewAttributes() ?>>
<?php echo $cabfacpro->nrengs->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->fechahora->Visible) { // fechahora ?>
		<tr id="r_fechahora">
			<td><?php echo $cabfacpro->fechahora->FldCaption() ?></td>
			<td<?php echo $cabfacpro->fechahora->CellAttributes() ?>>
<span id="el_cabfacpro_fechahora" class="form-group">
<span<?php echo $cabfacpro->fechahora->ViewAttributes() ?>>
<?php echo $cabfacpro->fechahora->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->usuario->Visible) { // usuario ?>
		<tr id="r_usuario">
			<td><?php echo $cabfacpro->usuario->FldCaption() ?></td>
			<td<?php echo $cabfacpro->usuario->CellAttributes() ?>>
<span id="el_cabfacpro_usuario" class="form-group">
<span<?php echo $cabfacpro->usuario->ViewAttributes() ?>>
<?php echo $cabfacpro->usuario->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->tieneresol->Visible) { // tieneresol ?>
		<tr id="r_tieneresol">
			<td><?php echo $cabfacpro->tieneresol->FldCaption() ?></td>
			<td<?php echo $cabfacpro->tieneresol->CellAttributes() ?>>
<span id="el_cabfacpro_tieneresol" class="form-group">
<span<?php echo $cabfacpro->tieneresol->ViewAttributes() ?>>
<?php echo $cabfacpro->tieneresol->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->concepto->Visible) { // concepto ?>
		<tr id="r_concepto">
			<td><?php echo $cabfacpro->concepto->FldCaption() ?></td>
			<td<?php echo $cabfacpro->concepto->CellAttributes() ?>>
<span id="el_cabfacpro_concepto" class="form-group">
<span<?php echo $cabfacpro->concepto->ViewAttributes() ?>>
<?php echo $cabfacpro->concepto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->nrodoc->Visible) { // nrodoc ?>
		<tr id="r_nrodoc">
			<td><?php echo $cabfacpro->nrodoc->FldCaption() ?></td>
			<td<?php echo $cabfacpro->nrodoc->CellAttributes() ?>>
<span id="el_cabfacpro_nrodoc" class="form-group">
<span<?php echo $cabfacpro->nrodoc->ViewAttributes() ?>>
<?php echo $cabfacpro->nrodoc->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->tcompsal->Visible) { // tcompsal ?>
		<tr id="r_tcompsal">
			<td><?php echo $cabfacpro->tcompsal->FldCaption() ?></td>
			<td<?php echo $cabfacpro->tcompsal->CellAttributes() ?>>
<span id="el_cabfacpro_tcompsal" class="form-group">
<span<?php echo $cabfacpro->tcompsal->ViewAttributes() ?>>
<?php echo $cabfacpro->tcompsal->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->seriesal->Visible) { // seriesal ?>
		<tr id="r_seriesal">
			<td><?php echo $cabfacpro->seriesal->FldCaption() ?></td>
			<td<?php echo $cabfacpro->seriesal->CellAttributes() ?>>
<span id="el_cabfacpro_seriesal" class="form-group">
<span<?php echo $cabfacpro->seriesal->ViewAttributes() ?>>
<?php echo $cabfacpro->seriesal->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cabfacpro->ncompsal->Visible) { // ncompsal ?>
		<tr id="r_ncompsal">
			<td><?php echo $cabfacpro->ncompsal->FldCaption() ?></td>
			<td<?php echo $cabfacpro->ncompsal->CellAttributes() ?>>
<span id="el_cabfacpro_ncompsal" class="form-group">
<span<?php echo $cabfacpro->ncompsal->ViewAttributes() ?>>
<?php echo $cabfacpro->ncompsal->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
