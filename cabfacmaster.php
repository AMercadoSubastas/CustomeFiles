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
// en_liquid
// CAE
// CAEFchVto
// Resultado
// usuarioultmod
// fecultmod

?>
<?php if ($cabfac->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $cabfac->TableCaption() ?></h4> -->
<div id="t_cabfac" class="<?php if (ew_IsResponsiveLayout()) echo "table-responsive "; ?>ewGrid">
<table id="tbl_cabfacmaster" class="table ewTable">
	<thead>
		<tr>
<?php if ($cabfac->codnum->Visible) { // codnum ?>
			<th class="ewTableHeader"><?php echo $cabfac->codnum->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->tcomp->Visible) { // tcomp ?>
			<th class="ewTableHeader"><?php echo $cabfac->tcomp->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->serie->Visible) { // serie ?>
			<th class="ewTableHeader"><?php echo $cabfac->serie->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->ncomp->Visible) { // ncomp ?>
			<th class="ewTableHeader"><?php echo $cabfac->ncomp->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->fecval->Visible) { // fecval ?>
			<th class="ewTableHeader"><?php echo $cabfac->fecval->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->fecdoc->Visible) { // fecdoc ?>
			<th class="ewTableHeader"><?php echo $cabfac->fecdoc->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->fecreg->Visible) { // fecreg ?>
			<th class="ewTableHeader"><?php echo $cabfac->fecreg->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->cliente->Visible) { // cliente ?>
			<th class="ewTableHeader"><?php echo $cabfac->cliente->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->cpago->Visible) { // cpago ?>
			<th class="ewTableHeader"><?php echo $cabfac->cpago->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->fecvenc->Visible) { // fecvenc ?>
			<th class="ewTableHeader"><?php echo $cabfac->fecvenc->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->direc->Visible) { // direc ?>
			<th class="ewTableHeader"><?php echo $cabfac->direc->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->dnro->Visible) { // dnro ?>
			<th class="ewTableHeader"><?php echo $cabfac->dnro->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->pisodto->Visible) { // pisodto ?>
			<th class="ewTableHeader"><?php echo $cabfac->pisodto->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->codpost->Visible) { // codpost ?>
			<th class="ewTableHeader"><?php echo $cabfac->codpost->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->codpais->Visible) { // codpais ?>
			<th class="ewTableHeader"><?php echo $cabfac->codpais->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->codprov->Visible) { // codprov ?>
			<th class="ewTableHeader"><?php echo $cabfac->codprov->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->codloc->Visible) { // codloc ?>
			<th class="ewTableHeader"><?php echo $cabfac->codloc->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->telef->Visible) { // telef ?>
			<th class="ewTableHeader"><?php echo $cabfac->telef->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->codrem->Visible) { // codrem ?>
			<th class="ewTableHeader"><?php echo $cabfac->codrem->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->estado->Visible) { // estado ?>
			<th class="ewTableHeader"><?php echo $cabfac->estado->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->emitido->Visible) { // emitido ?>
			<th class="ewTableHeader"><?php echo $cabfac->emitido->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->moneda->Visible) { // moneda ?>
			<th class="ewTableHeader"><?php echo $cabfac->moneda->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->totneto->Visible) { // totneto ?>
			<th class="ewTableHeader"><?php echo $cabfac->totneto->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->totbruto->Visible) { // totbruto ?>
			<th class="ewTableHeader"><?php echo $cabfac->totbruto->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->totiva105->Visible) { // totiva105 ?>
			<th class="ewTableHeader"><?php echo $cabfac->totiva105->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->totiva21->Visible) { // totiva21 ?>
			<th class="ewTableHeader"><?php echo $cabfac->totiva21->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->totimp->Visible) { // totimp ?>
			<th class="ewTableHeader"><?php echo $cabfac->totimp->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->totcomis->Visible) { // totcomis ?>
			<th class="ewTableHeader"><?php echo $cabfac->totcomis->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->totneto105->Visible) { // totneto105 ?>
			<th class="ewTableHeader"><?php echo $cabfac->totneto105->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->totneto21->Visible) { // totneto21 ?>
			<th class="ewTableHeader"><?php echo $cabfac->totneto21->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->tipoiva->Visible) { // tipoiva ?>
			<th class="ewTableHeader"><?php echo $cabfac->tipoiva->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->porciva->Visible) { // porciva ?>
			<th class="ewTableHeader"><?php echo $cabfac->porciva->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->nrengs->Visible) { // nrengs ?>
			<th class="ewTableHeader"><?php echo $cabfac->nrengs->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->fechahora->Visible) { // fechahora ?>
			<th class="ewTableHeader"><?php echo $cabfac->fechahora->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->usuario->Visible) { // usuario ?>
			<th class="ewTableHeader"><?php echo $cabfac->usuario->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->tieneresol->Visible) { // tieneresol ?>
			<th class="ewTableHeader"><?php echo $cabfac->tieneresol->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->concepto->Visible) { // concepto ?>
			<th class="ewTableHeader"><?php echo $cabfac->concepto->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->nrodoc->Visible) { // nrodoc ?>
			<th class="ewTableHeader"><?php echo $cabfac->nrodoc->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->tcompsal->Visible) { // tcompsal ?>
			<th class="ewTableHeader"><?php echo $cabfac->tcompsal->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->seriesal->Visible) { // seriesal ?>
			<th class="ewTableHeader"><?php echo $cabfac->seriesal->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->ncompsal->Visible) { // ncompsal ?>
			<th class="ewTableHeader"><?php echo $cabfac->ncompsal->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->en_liquid->Visible) { // en_liquid ?>
			<th class="ewTableHeader"><?php echo $cabfac->en_liquid->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->CAE->Visible) { // CAE ?>
			<th class="ewTableHeader"><?php echo $cabfac->CAE->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->CAEFchVto->Visible) { // CAEFchVto ?>
			<th class="ewTableHeader"><?php echo $cabfac->CAEFchVto->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->Resultado->Visible) { // Resultado ?>
			<th class="ewTableHeader"><?php echo $cabfac->Resultado->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->usuarioultmod->Visible) { // usuarioultmod ?>
			<th class="ewTableHeader"><?php echo $cabfac->usuarioultmod->FldCaption() ?></th>
<?php } ?>
<?php if ($cabfac->fecultmod->Visible) { // fecultmod ?>
			<th class="ewTableHeader"><?php echo $cabfac->fecultmod->FldCaption() ?></th>
<?php } ?>
		</tr>
	</thead>
	<tbody>
		<tr>
<?php if ($cabfac->codnum->Visible) { // codnum ?>
			<td<?php echo $cabfac->codnum->CellAttributes() ?>>
<span id="el_cabfac_codnum" class="form-group">
<span<?php echo $cabfac->codnum->ViewAttributes() ?>>
<?php echo $cabfac->codnum->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->tcomp->Visible) { // tcomp ?>
			<td<?php echo $cabfac->tcomp->CellAttributes() ?>>
<span id="el_cabfac_tcomp" class="form-group">
<span<?php echo $cabfac->tcomp->ViewAttributes() ?>>
<?php echo $cabfac->tcomp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->serie->Visible) { // serie ?>
			<td<?php echo $cabfac->serie->CellAttributes() ?>>
<span id="el_cabfac_serie" class="form-group">
<span<?php echo $cabfac->serie->ViewAttributes() ?>>
<?php echo $cabfac->serie->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->ncomp->Visible) { // ncomp ?>
			<td<?php echo $cabfac->ncomp->CellAttributes() ?>>
<span id="el_cabfac_ncomp" class="form-group">
<span<?php echo $cabfac->ncomp->ViewAttributes() ?>>
<?php echo $cabfac->ncomp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->fecval->Visible) { // fecval ?>
			<td<?php echo $cabfac->fecval->CellAttributes() ?>>
<span id="el_cabfac_fecval" class="form-group">
<span<?php echo $cabfac->fecval->ViewAttributes() ?>>
<?php echo $cabfac->fecval->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->fecdoc->Visible) { // fecdoc ?>
			<td<?php echo $cabfac->fecdoc->CellAttributes() ?>>
<span id="el_cabfac_fecdoc" class="form-group">
<span<?php echo $cabfac->fecdoc->ViewAttributes() ?>>
<?php echo $cabfac->fecdoc->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->fecreg->Visible) { // fecreg ?>
			<td<?php echo $cabfac->fecreg->CellAttributes() ?>>
<span id="el_cabfac_fecreg" class="form-group">
<span<?php echo $cabfac->fecreg->ViewAttributes() ?>>
<?php echo $cabfac->fecreg->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->cliente->Visible) { // cliente ?>
			<td<?php echo $cabfac->cliente->CellAttributes() ?>>
<span id="el_cabfac_cliente" class="form-group">
<span<?php echo $cabfac->cliente->ViewAttributes() ?>>
<?php echo $cabfac->cliente->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->cpago->Visible) { // cpago ?>
			<td<?php echo $cabfac->cpago->CellAttributes() ?>>
<span id="el_cabfac_cpago" class="form-group">
<span<?php echo $cabfac->cpago->ViewAttributes() ?>>
<?php echo $cabfac->cpago->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->fecvenc->Visible) { // fecvenc ?>
			<td<?php echo $cabfac->fecvenc->CellAttributes() ?>>
<span id="el_cabfac_fecvenc" class="form-group">
<span<?php echo $cabfac->fecvenc->ViewAttributes() ?>>
<?php echo $cabfac->fecvenc->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->direc->Visible) { // direc ?>
			<td<?php echo $cabfac->direc->CellAttributes() ?>>
<span id="el_cabfac_direc" class="form-group">
<span<?php echo $cabfac->direc->ViewAttributes() ?>>
<?php echo $cabfac->direc->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->dnro->Visible) { // dnro ?>
			<td<?php echo $cabfac->dnro->CellAttributes() ?>>
<span id="el_cabfac_dnro" class="form-group">
<span<?php echo $cabfac->dnro->ViewAttributes() ?>>
<?php echo $cabfac->dnro->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->pisodto->Visible) { // pisodto ?>
			<td<?php echo $cabfac->pisodto->CellAttributes() ?>>
<span id="el_cabfac_pisodto" class="form-group">
<span<?php echo $cabfac->pisodto->ViewAttributes() ?>>
<?php echo $cabfac->pisodto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->codpost->Visible) { // codpost ?>
			<td<?php echo $cabfac->codpost->CellAttributes() ?>>
<span id="el_cabfac_codpost" class="form-group">
<span<?php echo $cabfac->codpost->ViewAttributes() ?>>
<?php echo $cabfac->codpost->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->codpais->Visible) { // codpais ?>
			<td<?php echo $cabfac->codpais->CellAttributes() ?>>
<span id="el_cabfac_codpais" class="form-group">
<span<?php echo $cabfac->codpais->ViewAttributes() ?>>
<?php echo $cabfac->codpais->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->codprov->Visible) { // codprov ?>
			<td<?php echo $cabfac->codprov->CellAttributes() ?>>
<span id="el_cabfac_codprov" class="form-group">
<span<?php echo $cabfac->codprov->ViewAttributes() ?>>
<?php echo $cabfac->codprov->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->codloc->Visible) { // codloc ?>
			<td<?php echo $cabfac->codloc->CellAttributes() ?>>
<span id="el_cabfac_codloc" class="form-group">
<span<?php echo $cabfac->codloc->ViewAttributes() ?>>
<?php echo $cabfac->codloc->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->telef->Visible) { // telef ?>
			<td<?php echo $cabfac->telef->CellAttributes() ?>>
<span id="el_cabfac_telef" class="form-group">
<span<?php echo $cabfac->telef->ViewAttributes() ?>>
<?php echo $cabfac->telef->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->codrem->Visible) { // codrem ?>
			<td<?php echo $cabfac->codrem->CellAttributes() ?>>
<span id="el_cabfac_codrem" class="form-group">
<span<?php echo $cabfac->codrem->ViewAttributes() ?>>
<?php echo $cabfac->codrem->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->estado->Visible) { // estado ?>
			<td<?php echo $cabfac->estado->CellAttributes() ?>>
<span id="el_cabfac_estado" class="form-group">
<span<?php echo $cabfac->estado->ViewAttributes() ?>>
<?php echo $cabfac->estado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->emitido->Visible) { // emitido ?>
			<td<?php echo $cabfac->emitido->CellAttributes() ?>>
<span id="el_cabfac_emitido" class="form-group">
<span<?php echo $cabfac->emitido->ViewAttributes() ?>>
<?php echo $cabfac->emitido->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->moneda->Visible) { // moneda ?>
			<td<?php echo $cabfac->moneda->CellAttributes() ?>>
<span id="el_cabfac_moneda" class="form-group">
<span<?php echo $cabfac->moneda->ViewAttributes() ?>>
<?php echo $cabfac->moneda->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->totneto->Visible) { // totneto ?>
			<td<?php echo $cabfac->totneto->CellAttributes() ?>>
<span id="el_cabfac_totneto" class="form-group">
<span<?php echo $cabfac->totneto->ViewAttributes() ?>>
<?php echo $cabfac->totneto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->totbruto->Visible) { // totbruto ?>
			<td<?php echo $cabfac->totbruto->CellAttributes() ?>>
<span id="el_cabfac_totbruto" class="form-group">
<span<?php echo $cabfac->totbruto->ViewAttributes() ?>>
<?php echo $cabfac->totbruto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->totiva105->Visible) { // totiva105 ?>
			<td<?php echo $cabfac->totiva105->CellAttributes() ?>>
<span id="el_cabfac_totiva105" class="form-group">
<span<?php echo $cabfac->totiva105->ViewAttributes() ?>>
<?php echo $cabfac->totiva105->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->totiva21->Visible) { // totiva21 ?>
			<td<?php echo $cabfac->totiva21->CellAttributes() ?>>
<span id="el_cabfac_totiva21" class="form-group">
<span<?php echo $cabfac->totiva21->ViewAttributes() ?>>
<?php echo $cabfac->totiva21->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->totimp->Visible) { // totimp ?>
			<td<?php echo $cabfac->totimp->CellAttributes() ?>>
<span id="el_cabfac_totimp" class="form-group">
<span<?php echo $cabfac->totimp->ViewAttributes() ?>>
<?php echo $cabfac->totimp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->totcomis->Visible) { // totcomis ?>
			<td<?php echo $cabfac->totcomis->CellAttributes() ?>>
<span id="el_cabfac_totcomis" class="form-group">
<span<?php echo $cabfac->totcomis->ViewAttributes() ?>>
<?php echo $cabfac->totcomis->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->totneto105->Visible) { // totneto105 ?>
			<td<?php echo $cabfac->totneto105->CellAttributes() ?>>
<span id="el_cabfac_totneto105" class="form-group">
<span<?php echo $cabfac->totneto105->ViewAttributes() ?>>
<?php echo $cabfac->totneto105->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->totneto21->Visible) { // totneto21 ?>
			<td<?php echo $cabfac->totneto21->CellAttributes() ?>>
<span id="el_cabfac_totneto21" class="form-group">
<span<?php echo $cabfac->totneto21->ViewAttributes() ?>>
<?php echo $cabfac->totneto21->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->tipoiva->Visible) { // tipoiva ?>
			<td<?php echo $cabfac->tipoiva->CellAttributes() ?>>
<span id="el_cabfac_tipoiva" class="form-group">
<span<?php echo $cabfac->tipoiva->ViewAttributes() ?>>
<?php echo $cabfac->tipoiva->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->porciva->Visible) { // porciva ?>
			<td<?php echo $cabfac->porciva->CellAttributes() ?>>
<span id="el_cabfac_porciva" class="form-group">
<span<?php echo $cabfac->porciva->ViewAttributes() ?>>
<?php echo $cabfac->porciva->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->nrengs->Visible) { // nrengs ?>
			<td<?php echo $cabfac->nrengs->CellAttributes() ?>>
<span id="el_cabfac_nrengs" class="form-group">
<span<?php echo $cabfac->nrengs->ViewAttributes() ?>>
<?php echo $cabfac->nrengs->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->fechahora->Visible) { // fechahora ?>
			<td<?php echo $cabfac->fechahora->CellAttributes() ?>>
<span id="el_cabfac_fechahora" class="form-group">
<span<?php echo $cabfac->fechahora->ViewAttributes() ?>>
<?php echo $cabfac->fechahora->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->usuario->Visible) { // usuario ?>
			<td<?php echo $cabfac->usuario->CellAttributes() ?>>
<span id="el_cabfac_usuario" class="form-group">
<span<?php echo $cabfac->usuario->ViewAttributes() ?>>
<?php echo $cabfac->usuario->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->tieneresol->Visible) { // tieneresol ?>
			<td<?php echo $cabfac->tieneresol->CellAttributes() ?>>
<span id="el_cabfac_tieneresol" class="form-group">
<span<?php echo $cabfac->tieneresol->ViewAttributes() ?>>
<?php echo $cabfac->tieneresol->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->concepto->Visible) { // concepto ?>
			<td<?php echo $cabfac->concepto->CellAttributes() ?>>
<span id="el_cabfac_concepto" class="form-group">
<span<?php echo $cabfac->concepto->ViewAttributes() ?>>
<?php echo $cabfac->concepto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->nrodoc->Visible) { // nrodoc ?>
			<td<?php echo $cabfac->nrodoc->CellAttributes() ?>>
<span id="el_cabfac_nrodoc" class="form-group">
<span<?php echo $cabfac->nrodoc->ViewAttributes() ?>>
<?php echo $cabfac->nrodoc->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->tcompsal->Visible) { // tcompsal ?>
			<td<?php echo $cabfac->tcompsal->CellAttributes() ?>>
<span id="el_cabfac_tcompsal" class="form-group">
<span<?php echo $cabfac->tcompsal->ViewAttributes() ?>>
<?php echo $cabfac->tcompsal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->seriesal->Visible) { // seriesal ?>
			<td<?php echo $cabfac->seriesal->CellAttributes() ?>>
<span id="el_cabfac_seriesal" class="form-group">
<span<?php echo $cabfac->seriesal->ViewAttributes() ?>>
<?php echo $cabfac->seriesal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->ncompsal->Visible) { // ncompsal ?>
			<td<?php echo $cabfac->ncompsal->CellAttributes() ?>>
<span id="el_cabfac_ncompsal" class="form-group">
<span<?php echo $cabfac->ncompsal->ViewAttributes() ?>>
<?php echo $cabfac->ncompsal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->en_liquid->Visible) { // en_liquid ?>
			<td<?php echo $cabfac->en_liquid->CellAttributes() ?>>
<span id="el_cabfac_en_liquid" class="form-group">
<span<?php echo $cabfac->en_liquid->ViewAttributes() ?>>
<?php echo $cabfac->en_liquid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->CAE->Visible) { // CAE ?>
			<td<?php echo $cabfac->CAE->CellAttributes() ?>>
<span id="el_cabfac_CAE" class="form-group">
<span<?php echo $cabfac->CAE->ViewAttributes() ?>>
<?php echo $cabfac->CAE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->CAEFchVto->Visible) { // CAEFchVto ?>
			<td<?php echo $cabfac->CAEFchVto->CellAttributes() ?>>
<span id="el_cabfac_CAEFchVto" class="form-group">
<span<?php echo $cabfac->CAEFchVto->ViewAttributes() ?>>
<?php echo $cabfac->CAEFchVto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->Resultado->Visible) { // Resultado ?>
			<td<?php echo $cabfac->Resultado->CellAttributes() ?>>
<span id="el_cabfac_Resultado" class="form-group">
<span<?php echo $cabfac->Resultado->ViewAttributes() ?>>
<?php echo $cabfac->Resultado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->usuarioultmod->Visible) { // usuarioultmod ?>
			<td<?php echo $cabfac->usuarioultmod->CellAttributes() ?>>
<span id="el_cabfac_usuarioultmod" class="form-group">
<span<?php echo $cabfac->usuarioultmod->ViewAttributes() ?>>
<?php echo $cabfac->usuarioultmod->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cabfac->fecultmod->Visible) { // fecultmod ?>
			<td<?php echo $cabfac->fecultmod->CellAttributes() ?>>
<span id="el_cabfac_fecultmod" class="form-group">
<span<?php echo $cabfac->fecultmod->ViewAttributes() ?>>
<?php echo $cabfac->fecultmod->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
		</tr>
	</tbody>
</table>
</div>
<?php } ?>
