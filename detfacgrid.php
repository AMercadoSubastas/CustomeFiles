<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($detfac_grid)) $detfac_grid = new cdetfac_grid();

// Page init
$detfac_grid->Page_Init();

// Page main
$detfac_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$detfac_grid->Page_Render();
?>
<?php if ($detfac->Export == "") { ?>
<script type="text/javascript">

// Page object
var detfac_grid = new ew_Page("detfac_grid");
detfac_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = detfac_grid.PageID; // For backward compatibility

// Form object
var fdetfacgrid = new ew_Form("fdetfacgrid");
fdetfacgrid.FormKeyCountName = '<?php echo $detfac_grid->FormKeyCountName ?>';

// Validate form
fdetfacgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_tcomp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $detfac->tcomp->FldCaption(), $detfac->tcomp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tcomp");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->tcomp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_serie");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $detfac->serie->FldCaption(), $detfac->serie->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_serie");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->serie->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ncomp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $detfac->ncomp->FldCaption(), $detfac->ncomp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ncomp");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->ncomp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nreng");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $detfac->nreng->FldCaption(), $detfac->nreng->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nreng");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->nreng->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_codrem");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->codrem->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_codlote");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->codlote->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_neto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->neto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bruto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->bruto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_iva");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->iva->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_imp");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->imp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_comcob");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->comcob->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_compag");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->compag->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechahora");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->fechahora->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_usuario");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->usuario->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_porciva");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->porciva->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tieneresol");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->tieneresol->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_concafac");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->concafac->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tcomsal");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->tcomsal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_seriesal");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->seriesal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ncompsal");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfac->ncompsal->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fdetfacgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "tcomp", false)) return false;
	if (ew_ValueChanged(fobj, infix, "serie", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ncomp", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nreng", false)) return false;
	if (ew_ValueChanged(fobj, infix, "codrem", false)) return false;
	if (ew_ValueChanged(fobj, infix, "codlote", false)) return false;
	if (ew_ValueChanged(fobj, infix, "descrip", false)) return false;
	if (ew_ValueChanged(fobj, infix, "neto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "bruto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "iva", false)) return false;
	if (ew_ValueChanged(fobj, infix, "imp", false)) return false;
	if (ew_ValueChanged(fobj, infix, "comcob", false)) return false;
	if (ew_ValueChanged(fobj, infix, "compag", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fechahora", false)) return false;
	if (ew_ValueChanged(fobj, infix, "usuario", false)) return false;
	if (ew_ValueChanged(fobj, infix, "porciva", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tieneresol", false)) return false;
	if (ew_ValueChanged(fobj, infix, "concafac", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tcomsal", false)) return false;
	if (ew_ValueChanged(fobj, infix, "seriesal", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ncompsal", false)) return false;
	return true;
}

// Form_CustomValidate event
fdetfacgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdetfacgrid.ValidateRequired = true;
<?php } else { ?>
fdetfacgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($detfac->CurrentAction == "gridadd") {
	if ($detfac->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$detfac_grid->TotalRecs = $detfac->SelectRecordCount();
			$detfac_grid->Recordset = $detfac_grid->LoadRecordset($detfac_grid->StartRec-1, $detfac_grid->DisplayRecs);
		} else {
			if ($detfac_grid->Recordset = $detfac_grid->LoadRecordset())
				$detfac_grid->TotalRecs = $detfac_grid->Recordset->RecordCount();
		}
		$detfac_grid->StartRec = 1;
		$detfac_grid->DisplayRecs = $detfac_grid->TotalRecs;
	} else {
		$detfac->CurrentFilter = "0=1";
		$detfac_grid->StartRec = 1;
		$detfac_grid->DisplayRecs = $detfac->GridAddRowCount;
	}
	$detfac_grid->TotalRecs = $detfac_grid->DisplayRecs;
	$detfac_grid->StopRec = $detfac_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($detfac_grid->TotalRecs <= 0)
			$detfac_grid->TotalRecs = $detfac->SelectRecordCount();
	} else {
		if (!$detfac_grid->Recordset && ($detfac_grid->Recordset = $detfac_grid->LoadRecordset()))
			$detfac_grid->TotalRecs = $detfac_grid->Recordset->RecordCount();
	}
	$detfac_grid->StartRec = 1;
	$detfac_grid->DisplayRecs = $detfac_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$detfac_grid->Recordset = $detfac_grid->LoadRecordset($detfac_grid->StartRec-1, $detfac_grid->DisplayRecs);

	// Set no record found message
	if ($detfac->CurrentAction == "" && $detfac_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$detfac_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($detfac_grid->SearchWhere == "0=101")
			$detfac_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$detfac_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$detfac_grid->RenderOtherOptions();
?>
<?php $detfac_grid->ShowPageHeader(); ?>
<?php
$detfac_grid->ShowMessage();
?>
<?php if ($detfac_grid->TotalRecs > 0 || $detfac->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fdetfacgrid" class="ewForm form-inline">
<div id="gmp_detfac" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_detfacgrid" class="table ewTable">
<?php echo $detfac->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$detfac_grid->RenderListOptions();

// Render list options (header, left)
$detfac_grid->ListOptions->Render("header", "left");
?>
<?php if ($detfac->codnum->Visible) { // codnum ?>
	<?php if ($detfac->SortUrl($detfac->codnum) == "") { ?>
		<th data-name="codnum"><div id="elh_detfac_codnum" class="detfac_codnum"><div class="ewTableHeaderCaption"><?php echo $detfac->codnum->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codnum"><div><div id="elh_detfac_codnum" class="detfac_codnum">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->codnum->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->codnum->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->codnum->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->tcomp->Visible) { // tcomp ?>
	<?php if ($detfac->SortUrl($detfac->tcomp) == "") { ?>
		<th data-name="tcomp"><div id="elh_detfac_tcomp" class="detfac_tcomp"><div class="ewTableHeaderCaption"><?php echo $detfac->tcomp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tcomp"><div><div id="elh_detfac_tcomp" class="detfac_tcomp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->tcomp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->tcomp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->tcomp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->serie->Visible) { // serie ?>
	<?php if ($detfac->SortUrl($detfac->serie) == "") { ?>
		<th data-name="serie"><div id="elh_detfac_serie" class="detfac_serie"><div class="ewTableHeaderCaption"><?php echo $detfac->serie->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="serie"><div><div id="elh_detfac_serie" class="detfac_serie">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->serie->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->serie->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->serie->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->ncomp->Visible) { // ncomp ?>
	<?php if ($detfac->SortUrl($detfac->ncomp) == "") { ?>
		<th data-name="ncomp"><div id="elh_detfac_ncomp" class="detfac_ncomp"><div class="ewTableHeaderCaption"><?php echo $detfac->ncomp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ncomp"><div><div id="elh_detfac_ncomp" class="detfac_ncomp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->ncomp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->ncomp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->ncomp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->nreng->Visible) { // nreng ?>
	<?php if ($detfac->SortUrl($detfac->nreng) == "") { ?>
		<th data-name="nreng"><div id="elh_detfac_nreng" class="detfac_nreng"><div class="ewTableHeaderCaption"><?php echo $detfac->nreng->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nreng"><div><div id="elh_detfac_nreng" class="detfac_nreng">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->nreng->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->nreng->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->nreng->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->codrem->Visible) { // codrem ?>
	<?php if ($detfac->SortUrl($detfac->codrem) == "") { ?>
		<th data-name="codrem"><div id="elh_detfac_codrem" class="detfac_codrem"><div class="ewTableHeaderCaption"><?php echo $detfac->codrem->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codrem"><div><div id="elh_detfac_codrem" class="detfac_codrem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->codrem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->codrem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->codrem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->codlote->Visible) { // codlote ?>
	<?php if ($detfac->SortUrl($detfac->codlote) == "") { ?>
		<th data-name="codlote"><div id="elh_detfac_codlote" class="detfac_codlote"><div class="ewTableHeaderCaption"><?php echo $detfac->codlote->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codlote"><div><div id="elh_detfac_codlote" class="detfac_codlote">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->codlote->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->codlote->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->codlote->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->descrip->Visible) { // descrip ?>
	<?php if ($detfac->SortUrl($detfac->descrip) == "") { ?>
		<th data-name="descrip"><div id="elh_detfac_descrip" class="detfac_descrip"><div class="ewTableHeaderCaption"><?php echo $detfac->descrip->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descrip"><div><div id="elh_detfac_descrip" class="detfac_descrip">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->descrip->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->descrip->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->descrip->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->neto->Visible) { // neto ?>
	<?php if ($detfac->SortUrl($detfac->neto) == "") { ?>
		<th data-name="neto"><div id="elh_detfac_neto" class="detfac_neto"><div class="ewTableHeaderCaption"><?php echo $detfac->neto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="neto"><div><div id="elh_detfac_neto" class="detfac_neto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->neto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->neto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->neto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->bruto->Visible) { // bruto ?>
	<?php if ($detfac->SortUrl($detfac->bruto) == "") { ?>
		<th data-name="bruto"><div id="elh_detfac_bruto" class="detfac_bruto"><div class="ewTableHeaderCaption"><?php echo $detfac->bruto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bruto"><div><div id="elh_detfac_bruto" class="detfac_bruto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->bruto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->bruto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->bruto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->iva->Visible) { // iva ?>
	<?php if ($detfac->SortUrl($detfac->iva) == "") { ?>
		<th data-name="iva"><div id="elh_detfac_iva" class="detfac_iva"><div class="ewTableHeaderCaption"><?php echo $detfac->iva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="iva"><div><div id="elh_detfac_iva" class="detfac_iva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->iva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->iva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->iva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->imp->Visible) { // imp ?>
	<?php if ($detfac->SortUrl($detfac->imp) == "") { ?>
		<th data-name="imp"><div id="elh_detfac_imp" class="detfac_imp"><div class="ewTableHeaderCaption"><?php echo $detfac->imp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="imp"><div><div id="elh_detfac_imp" class="detfac_imp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->imp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->imp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->imp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->comcob->Visible) { // comcob ?>
	<?php if ($detfac->SortUrl($detfac->comcob) == "") { ?>
		<th data-name="comcob"><div id="elh_detfac_comcob" class="detfac_comcob"><div class="ewTableHeaderCaption"><?php echo $detfac->comcob->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="comcob"><div><div id="elh_detfac_comcob" class="detfac_comcob">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->comcob->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->comcob->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->comcob->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->compag->Visible) { // compag ?>
	<?php if ($detfac->SortUrl($detfac->compag) == "") { ?>
		<th data-name="compag"><div id="elh_detfac_compag" class="detfac_compag"><div class="ewTableHeaderCaption"><?php echo $detfac->compag->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="compag"><div><div id="elh_detfac_compag" class="detfac_compag">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->compag->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->compag->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->compag->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->fechahora->Visible) { // fechahora ?>
	<?php if ($detfac->SortUrl($detfac->fechahora) == "") { ?>
		<th data-name="fechahora"><div id="elh_detfac_fechahora" class="detfac_fechahora"><div class="ewTableHeaderCaption"><?php echo $detfac->fechahora->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechahora"><div><div id="elh_detfac_fechahora" class="detfac_fechahora">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->fechahora->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->fechahora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->fechahora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->usuario->Visible) { // usuario ?>
	<?php if ($detfac->SortUrl($detfac->usuario) == "") { ?>
		<th data-name="usuario"><div id="elh_detfac_usuario" class="detfac_usuario"><div class="ewTableHeaderCaption"><?php echo $detfac->usuario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="usuario"><div><div id="elh_detfac_usuario" class="detfac_usuario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->usuario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->usuario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->usuario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->porciva->Visible) { // porciva ?>
	<?php if ($detfac->SortUrl($detfac->porciva) == "") { ?>
		<th data-name="porciva"><div id="elh_detfac_porciva" class="detfac_porciva"><div class="ewTableHeaderCaption"><?php echo $detfac->porciva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="porciva"><div><div id="elh_detfac_porciva" class="detfac_porciva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->porciva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->porciva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->porciva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->tieneresol->Visible) { // tieneresol ?>
	<?php if ($detfac->SortUrl($detfac->tieneresol) == "") { ?>
		<th data-name="tieneresol"><div id="elh_detfac_tieneresol" class="detfac_tieneresol"><div class="ewTableHeaderCaption"><?php echo $detfac->tieneresol->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tieneresol"><div><div id="elh_detfac_tieneresol" class="detfac_tieneresol">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->tieneresol->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->tieneresol->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->tieneresol->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->concafac->Visible) { // concafac ?>
	<?php if ($detfac->SortUrl($detfac->concafac) == "") { ?>
		<th data-name="concafac"><div id="elh_detfac_concafac" class="detfac_concafac"><div class="ewTableHeaderCaption"><?php echo $detfac->concafac->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="concafac"><div><div id="elh_detfac_concafac" class="detfac_concafac">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->concafac->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->concafac->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->concafac->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->tcomsal->Visible) { // tcomsal ?>
	<?php if ($detfac->SortUrl($detfac->tcomsal) == "") { ?>
		<th data-name="tcomsal"><div id="elh_detfac_tcomsal" class="detfac_tcomsal"><div class="ewTableHeaderCaption"><?php echo $detfac->tcomsal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tcomsal"><div><div id="elh_detfac_tcomsal" class="detfac_tcomsal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->tcomsal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->tcomsal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->tcomsal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->seriesal->Visible) { // seriesal ?>
	<?php if ($detfac->SortUrl($detfac->seriesal) == "") { ?>
		<th data-name="seriesal"><div id="elh_detfac_seriesal" class="detfac_seriesal"><div class="ewTableHeaderCaption"><?php echo $detfac->seriesal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="seriesal"><div><div id="elh_detfac_seriesal" class="detfac_seriesal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->seriesal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->seriesal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->seriesal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfac->ncompsal->Visible) { // ncompsal ?>
	<?php if ($detfac->SortUrl($detfac->ncompsal) == "") { ?>
		<th data-name="ncompsal"><div id="elh_detfac_ncompsal" class="detfac_ncompsal"><div class="ewTableHeaderCaption"><?php echo $detfac->ncompsal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ncompsal"><div><div id="elh_detfac_ncompsal" class="detfac_ncompsal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfac->ncompsal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfac->ncompsal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfac->ncompsal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$detfac_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$detfac_grid->StartRec = 1;
$detfac_grid->StopRec = $detfac_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($detfac_grid->FormKeyCountName) && ($detfac->CurrentAction == "gridadd" || $detfac->CurrentAction == "gridedit" || $detfac->CurrentAction == "F")) {
		$detfac_grid->KeyCount = $objForm->GetValue($detfac_grid->FormKeyCountName);
		$detfac_grid->StopRec = $detfac_grid->StartRec + $detfac_grid->KeyCount - 1;
	}
}
$detfac_grid->RecCnt = $detfac_grid->StartRec - 1;
if ($detfac_grid->Recordset && !$detfac_grid->Recordset->EOF) {
	$detfac_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $detfac_grid->StartRec > 1)
		$detfac_grid->Recordset->Move($detfac_grid->StartRec - 1);
} elseif (!$detfac->AllowAddDeleteRow && $detfac_grid->StopRec == 0) {
	$detfac_grid->StopRec = $detfac->GridAddRowCount;
}

// Initialize aggregate
$detfac->RowType = EW_ROWTYPE_AGGREGATEINIT;
$detfac->ResetAttrs();
$detfac_grid->RenderRow();
if ($detfac->CurrentAction == "gridadd")
	$detfac_grid->RowIndex = 0;
if ($detfac->CurrentAction == "gridedit")
	$detfac_grid->RowIndex = 0;
while ($detfac_grid->RecCnt < $detfac_grid->StopRec) {
	$detfac_grid->RecCnt++;
	if (intval($detfac_grid->RecCnt) >= intval($detfac_grid->StartRec)) {
		$detfac_grid->RowCnt++;
		if ($detfac->CurrentAction == "gridadd" || $detfac->CurrentAction == "gridedit" || $detfac->CurrentAction == "F") {
			$detfac_grid->RowIndex++;
			$objForm->Index = $detfac_grid->RowIndex;
			if ($objForm->HasValue($detfac_grid->FormActionName))
				$detfac_grid->RowAction = strval($objForm->GetValue($detfac_grid->FormActionName));
			elseif ($detfac->CurrentAction == "gridadd")
				$detfac_grid->RowAction = "insert";
			else
				$detfac_grid->RowAction = "";
		}

		// Set up key count
		$detfac_grid->KeyCount = $detfac_grid->RowIndex;

		// Init row class and style
		$detfac->ResetAttrs();
		$detfac->CssClass = "";
		if ($detfac->CurrentAction == "gridadd") {
			if ($detfac->CurrentMode == "copy") {
				$detfac_grid->LoadRowValues($detfac_grid->Recordset); // Load row values
				$detfac_grid->SetRecordKey($detfac_grid->RowOldKey, $detfac_grid->Recordset); // Set old record key
			} else {
				$detfac_grid->LoadDefaultValues(); // Load default values
				$detfac_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$detfac_grid->LoadRowValues($detfac_grid->Recordset); // Load row values
		}
		$detfac->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($detfac->CurrentAction == "gridadd") // Grid add
			$detfac->RowType = EW_ROWTYPE_ADD; // Render add
		if ($detfac->CurrentAction == "gridadd" && $detfac->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$detfac_grid->RestoreCurrentRowFormValues($detfac_grid->RowIndex); // Restore form values
		if ($detfac->CurrentAction == "gridedit") { // Grid edit
			if ($detfac->EventCancelled) {
				$detfac_grid->RestoreCurrentRowFormValues($detfac_grid->RowIndex); // Restore form values
			}
			if ($detfac_grid->RowAction == "insert")
				$detfac->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$detfac->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($detfac->CurrentAction == "gridedit" && ($detfac->RowType == EW_ROWTYPE_EDIT || $detfac->RowType == EW_ROWTYPE_ADD) && $detfac->EventCancelled) // Update failed
			$detfac_grid->RestoreCurrentRowFormValues($detfac_grid->RowIndex); // Restore form values
		if ($detfac->RowType == EW_ROWTYPE_EDIT) // Edit row
			$detfac_grid->EditRowCnt++;
		if ($detfac->CurrentAction == "F") // Confirm row
			$detfac_grid->RestoreCurrentRowFormValues($detfac_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$detfac->RowAttrs = array_merge($detfac->RowAttrs, array('data-rowindex'=>$detfac_grid->RowCnt, 'id'=>'r' . $detfac_grid->RowCnt . '_detfac', 'data-rowtype'=>$detfac->RowType));

		// Render row
		$detfac_grid->RenderRow();

		// Render list options
		$detfac_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($detfac_grid->RowAction <> "delete" && $detfac_grid->RowAction <> "insertdelete" && !($detfac_grid->RowAction == "insert" && $detfac->CurrentAction == "F" && $detfac_grid->EmptyRow())) {
?>
	<tr<?php echo $detfac->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detfac_grid->ListOptions->Render("body", "left", $detfac_grid->RowCnt);
?>
	<?php if ($detfac->codnum->Visible) { // codnum ?>
		<td data-name="codnum"<?php echo $detfac->codnum->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_codnum" name="o<?php echo $detfac_grid->RowIndex ?>_codnum" id="o<?php echo $detfac_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($detfac->codnum->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_codnum" class="form-group detfac_codnum">
<span<?php echo $detfac->codnum->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->codnum->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_codnum" name="x<?php echo $detfac_grid->RowIndex ?>_codnum" id="x<?php echo $detfac_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($detfac->codnum->CurrentValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->codnum->ViewAttributes() ?>>
<?php echo $detfac->codnum->ListViewValue() ?></span>
<input type="hidden" data-field="x_codnum" name="x<?php echo $detfac_grid->RowIndex ?>_codnum" id="x<?php echo $detfac_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($detfac->codnum->FormValue) ?>">
<input type="hidden" data-field="x_codnum" name="o<?php echo $detfac_grid->RowIndex ?>_codnum" id="o<?php echo $detfac_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($detfac->codnum->OldValue) ?>">
<?php } ?>
<a id="<?php echo $detfac_grid->PageObjName . "_row_" . $detfac_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($detfac->tcomp->Visible) { // tcomp ?>
		<td data-name="tcomp"<?php echo $detfac->tcomp->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($detfac->tcomp->getSessionValue() <> "") { ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_tcomp" class="form-group detfac_tcomp">
<span<?php echo $detfac->tcomp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->tcomp->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detfac_grid->RowIndex ?>_tcomp" name="x<?php echo $detfac_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($detfac->tcomp->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_tcomp" class="form-group detfac_tcomp">
<input type="text" data-field="x_tcomp" name="x<?php echo $detfac_grid->RowIndex ?>_tcomp" id="x<?php echo $detfac_grid->RowIndex ?>_tcomp" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->tcomp->PlaceHolder) ?>" value="<?php echo $detfac->tcomp->EditValue ?>"<?php echo $detfac->tcomp->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-field="x_tcomp" name="o<?php echo $detfac_grid->RowIndex ?>_tcomp" id="o<?php echo $detfac_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($detfac->tcomp->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($detfac->tcomp->getSessionValue() <> "") { ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_tcomp" class="form-group detfac_tcomp">
<span<?php echo $detfac->tcomp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->tcomp->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detfac_grid->RowIndex ?>_tcomp" name="x<?php echo $detfac_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($detfac->tcomp->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_tcomp" class="form-group detfac_tcomp">
<input type="text" data-field="x_tcomp" name="x<?php echo $detfac_grid->RowIndex ?>_tcomp" id="x<?php echo $detfac_grid->RowIndex ?>_tcomp" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->tcomp->PlaceHolder) ?>" value="<?php echo $detfac->tcomp->EditValue ?>"<?php echo $detfac->tcomp->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->tcomp->ViewAttributes() ?>>
<?php echo $detfac->tcomp->ListViewValue() ?></span>
<input type="hidden" data-field="x_tcomp" name="x<?php echo $detfac_grid->RowIndex ?>_tcomp" id="x<?php echo $detfac_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($detfac->tcomp->FormValue) ?>">
<input type="hidden" data-field="x_tcomp" name="o<?php echo $detfac_grid->RowIndex ?>_tcomp" id="o<?php echo $detfac_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($detfac->tcomp->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->serie->Visible) { // serie ?>
		<td data-name="serie"<?php echo $detfac->serie->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($detfac->serie->getSessionValue() <> "") { ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_serie" class="form-group detfac_serie">
<span<?php echo $detfac->serie->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->serie->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detfac_grid->RowIndex ?>_serie" name="x<?php echo $detfac_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($detfac->serie->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_serie" class="form-group detfac_serie">
<input type="text" data-field="x_serie" name="x<?php echo $detfac_grid->RowIndex ?>_serie" id="x<?php echo $detfac_grid->RowIndex ?>_serie" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->serie->PlaceHolder) ?>" value="<?php echo $detfac->serie->EditValue ?>"<?php echo $detfac->serie->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-field="x_serie" name="o<?php echo $detfac_grid->RowIndex ?>_serie" id="o<?php echo $detfac_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($detfac->serie->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($detfac->serie->getSessionValue() <> "") { ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_serie" class="form-group detfac_serie">
<span<?php echo $detfac->serie->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->serie->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detfac_grid->RowIndex ?>_serie" name="x<?php echo $detfac_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($detfac->serie->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_serie" class="form-group detfac_serie">
<input type="text" data-field="x_serie" name="x<?php echo $detfac_grid->RowIndex ?>_serie" id="x<?php echo $detfac_grid->RowIndex ?>_serie" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->serie->PlaceHolder) ?>" value="<?php echo $detfac->serie->EditValue ?>"<?php echo $detfac->serie->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->serie->ViewAttributes() ?>>
<?php echo $detfac->serie->ListViewValue() ?></span>
<input type="hidden" data-field="x_serie" name="x<?php echo $detfac_grid->RowIndex ?>_serie" id="x<?php echo $detfac_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($detfac->serie->FormValue) ?>">
<input type="hidden" data-field="x_serie" name="o<?php echo $detfac_grid->RowIndex ?>_serie" id="o<?php echo $detfac_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($detfac->serie->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->ncomp->Visible) { // ncomp ?>
		<td data-name="ncomp"<?php echo $detfac->ncomp->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($detfac->ncomp->getSessionValue() <> "") { ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_ncomp" class="form-group detfac_ncomp">
<span<?php echo $detfac->ncomp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->ncomp->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detfac_grid->RowIndex ?>_ncomp" name="x<?php echo $detfac_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($detfac->ncomp->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_ncomp" class="form-group detfac_ncomp">
<input type="text" data-field="x_ncomp" name="x<?php echo $detfac_grid->RowIndex ?>_ncomp" id="x<?php echo $detfac_grid->RowIndex ?>_ncomp" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->ncomp->PlaceHolder) ?>" value="<?php echo $detfac->ncomp->EditValue ?>"<?php echo $detfac->ncomp->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-field="x_ncomp" name="o<?php echo $detfac_grid->RowIndex ?>_ncomp" id="o<?php echo $detfac_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($detfac->ncomp->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($detfac->ncomp->getSessionValue() <> "") { ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_ncomp" class="form-group detfac_ncomp">
<span<?php echo $detfac->ncomp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->ncomp->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detfac_grid->RowIndex ?>_ncomp" name="x<?php echo $detfac_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($detfac->ncomp->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_ncomp" class="form-group detfac_ncomp">
<input type="text" data-field="x_ncomp" name="x<?php echo $detfac_grid->RowIndex ?>_ncomp" id="x<?php echo $detfac_grid->RowIndex ?>_ncomp" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->ncomp->PlaceHolder) ?>" value="<?php echo $detfac->ncomp->EditValue ?>"<?php echo $detfac->ncomp->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->ncomp->ViewAttributes() ?>>
<?php echo $detfac->ncomp->ListViewValue() ?></span>
<input type="hidden" data-field="x_ncomp" name="x<?php echo $detfac_grid->RowIndex ?>_ncomp" id="x<?php echo $detfac_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($detfac->ncomp->FormValue) ?>">
<input type="hidden" data-field="x_ncomp" name="o<?php echo $detfac_grid->RowIndex ?>_ncomp" id="o<?php echo $detfac_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($detfac->ncomp->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->nreng->Visible) { // nreng ?>
		<td data-name="nreng"<?php echo $detfac->nreng->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_nreng" class="form-group detfac_nreng">
<input type="text" data-field="x_nreng" name="x<?php echo $detfac_grid->RowIndex ?>_nreng" id="x<?php echo $detfac_grid->RowIndex ?>_nreng" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->nreng->PlaceHolder) ?>" value="<?php echo $detfac->nreng->EditValue ?>"<?php echo $detfac->nreng->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nreng" name="o<?php echo $detfac_grid->RowIndex ?>_nreng" id="o<?php echo $detfac_grid->RowIndex ?>_nreng" value="<?php echo ew_HtmlEncode($detfac->nreng->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_nreng" class="form-group detfac_nreng">
<input type="text" data-field="x_nreng" name="x<?php echo $detfac_grid->RowIndex ?>_nreng" id="x<?php echo $detfac_grid->RowIndex ?>_nreng" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->nreng->PlaceHolder) ?>" value="<?php echo $detfac->nreng->EditValue ?>"<?php echo $detfac->nreng->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->nreng->ViewAttributes() ?>>
<?php echo $detfac->nreng->ListViewValue() ?></span>
<input type="hidden" data-field="x_nreng" name="x<?php echo $detfac_grid->RowIndex ?>_nreng" id="x<?php echo $detfac_grid->RowIndex ?>_nreng" value="<?php echo ew_HtmlEncode($detfac->nreng->FormValue) ?>">
<input type="hidden" data-field="x_nreng" name="o<?php echo $detfac_grid->RowIndex ?>_nreng" id="o<?php echo $detfac_grid->RowIndex ?>_nreng" value="<?php echo ew_HtmlEncode($detfac->nreng->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->codrem->Visible) { // codrem ?>
		<td data-name="codrem"<?php echo $detfac->codrem->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_codrem" class="form-group detfac_codrem">
<input type="text" data-field="x_codrem" name="x<?php echo $detfac_grid->RowIndex ?>_codrem" id="x<?php echo $detfac_grid->RowIndex ?>_codrem" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->codrem->PlaceHolder) ?>" value="<?php echo $detfac->codrem->EditValue ?>"<?php echo $detfac->codrem->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_codrem" name="o<?php echo $detfac_grid->RowIndex ?>_codrem" id="o<?php echo $detfac_grid->RowIndex ?>_codrem" value="<?php echo ew_HtmlEncode($detfac->codrem->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_codrem" class="form-group detfac_codrem">
<input type="text" data-field="x_codrem" name="x<?php echo $detfac_grid->RowIndex ?>_codrem" id="x<?php echo $detfac_grid->RowIndex ?>_codrem" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->codrem->PlaceHolder) ?>" value="<?php echo $detfac->codrem->EditValue ?>"<?php echo $detfac->codrem->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->codrem->ViewAttributes() ?>>
<?php echo $detfac->codrem->ListViewValue() ?></span>
<input type="hidden" data-field="x_codrem" name="x<?php echo $detfac_grid->RowIndex ?>_codrem" id="x<?php echo $detfac_grid->RowIndex ?>_codrem" value="<?php echo ew_HtmlEncode($detfac->codrem->FormValue) ?>">
<input type="hidden" data-field="x_codrem" name="o<?php echo $detfac_grid->RowIndex ?>_codrem" id="o<?php echo $detfac_grid->RowIndex ?>_codrem" value="<?php echo ew_HtmlEncode($detfac->codrem->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->codlote->Visible) { // codlote ?>
		<td data-name="codlote"<?php echo $detfac->codlote->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_codlote" class="form-group detfac_codlote">
<input type="text" data-field="x_codlote" name="x<?php echo $detfac_grid->RowIndex ?>_codlote" id="x<?php echo $detfac_grid->RowIndex ?>_codlote" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->codlote->PlaceHolder) ?>" value="<?php echo $detfac->codlote->EditValue ?>"<?php echo $detfac->codlote->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_codlote" name="o<?php echo $detfac_grid->RowIndex ?>_codlote" id="o<?php echo $detfac_grid->RowIndex ?>_codlote" value="<?php echo ew_HtmlEncode($detfac->codlote->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_codlote" class="form-group detfac_codlote">
<input type="text" data-field="x_codlote" name="x<?php echo $detfac_grid->RowIndex ?>_codlote" id="x<?php echo $detfac_grid->RowIndex ?>_codlote" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->codlote->PlaceHolder) ?>" value="<?php echo $detfac->codlote->EditValue ?>"<?php echo $detfac->codlote->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->codlote->ViewAttributes() ?>>
<?php echo $detfac->codlote->ListViewValue() ?></span>
<input type="hidden" data-field="x_codlote" name="x<?php echo $detfac_grid->RowIndex ?>_codlote" id="x<?php echo $detfac_grid->RowIndex ?>_codlote" value="<?php echo ew_HtmlEncode($detfac->codlote->FormValue) ?>">
<input type="hidden" data-field="x_codlote" name="o<?php echo $detfac_grid->RowIndex ?>_codlote" id="o<?php echo $detfac_grid->RowIndex ?>_codlote" value="<?php echo ew_HtmlEncode($detfac->codlote->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->descrip->Visible) { // descrip ?>
		<td data-name="descrip"<?php echo $detfac->descrip->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_descrip" class="form-group detfac_descrip">
<input type="text" data-field="x_descrip" name="x<?php echo $detfac_grid->RowIndex ?>_descrip" id="x<?php echo $detfac_grid->RowIndex ?>_descrip" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($detfac->descrip->PlaceHolder) ?>" value="<?php echo $detfac->descrip->EditValue ?>"<?php echo $detfac->descrip->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_descrip" name="o<?php echo $detfac_grid->RowIndex ?>_descrip" id="o<?php echo $detfac_grid->RowIndex ?>_descrip" value="<?php echo ew_HtmlEncode($detfac->descrip->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_descrip" class="form-group detfac_descrip">
<input type="text" data-field="x_descrip" name="x<?php echo $detfac_grid->RowIndex ?>_descrip" id="x<?php echo $detfac_grid->RowIndex ?>_descrip" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($detfac->descrip->PlaceHolder) ?>" value="<?php echo $detfac->descrip->EditValue ?>"<?php echo $detfac->descrip->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->descrip->ViewAttributes() ?>>
<?php echo $detfac->descrip->ListViewValue() ?></span>
<input type="hidden" data-field="x_descrip" name="x<?php echo $detfac_grid->RowIndex ?>_descrip" id="x<?php echo $detfac_grid->RowIndex ?>_descrip" value="<?php echo ew_HtmlEncode($detfac->descrip->FormValue) ?>">
<input type="hidden" data-field="x_descrip" name="o<?php echo $detfac_grid->RowIndex ?>_descrip" id="o<?php echo $detfac_grid->RowIndex ?>_descrip" value="<?php echo ew_HtmlEncode($detfac->descrip->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->neto->Visible) { // neto ?>
		<td data-name="neto"<?php echo $detfac->neto->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_neto" class="form-group detfac_neto">
<input type="text" data-field="x_neto" name="x<?php echo $detfac_grid->RowIndex ?>_neto" id="x<?php echo $detfac_grid->RowIndex ?>_neto" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->neto->PlaceHolder) ?>" value="<?php echo $detfac->neto->EditValue ?>"<?php echo $detfac->neto->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_neto" name="o<?php echo $detfac_grid->RowIndex ?>_neto" id="o<?php echo $detfac_grid->RowIndex ?>_neto" value="<?php echo ew_HtmlEncode($detfac->neto->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_neto" class="form-group detfac_neto">
<input type="text" data-field="x_neto" name="x<?php echo $detfac_grid->RowIndex ?>_neto" id="x<?php echo $detfac_grid->RowIndex ?>_neto" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->neto->PlaceHolder) ?>" value="<?php echo $detfac->neto->EditValue ?>"<?php echo $detfac->neto->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->neto->ViewAttributes() ?>>
<?php echo $detfac->neto->ListViewValue() ?></span>
<input type="hidden" data-field="x_neto" name="x<?php echo $detfac_grid->RowIndex ?>_neto" id="x<?php echo $detfac_grid->RowIndex ?>_neto" value="<?php echo ew_HtmlEncode($detfac->neto->FormValue) ?>">
<input type="hidden" data-field="x_neto" name="o<?php echo $detfac_grid->RowIndex ?>_neto" id="o<?php echo $detfac_grid->RowIndex ?>_neto" value="<?php echo ew_HtmlEncode($detfac->neto->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->bruto->Visible) { // bruto ?>
		<td data-name="bruto"<?php echo $detfac->bruto->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_bruto" class="form-group detfac_bruto">
<input type="text" data-field="x_bruto" name="x<?php echo $detfac_grid->RowIndex ?>_bruto" id="x<?php echo $detfac_grid->RowIndex ?>_bruto" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->bruto->PlaceHolder) ?>" value="<?php echo $detfac->bruto->EditValue ?>"<?php echo $detfac->bruto->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_bruto" name="o<?php echo $detfac_grid->RowIndex ?>_bruto" id="o<?php echo $detfac_grid->RowIndex ?>_bruto" value="<?php echo ew_HtmlEncode($detfac->bruto->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_bruto" class="form-group detfac_bruto">
<input type="text" data-field="x_bruto" name="x<?php echo $detfac_grid->RowIndex ?>_bruto" id="x<?php echo $detfac_grid->RowIndex ?>_bruto" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->bruto->PlaceHolder) ?>" value="<?php echo $detfac->bruto->EditValue ?>"<?php echo $detfac->bruto->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->bruto->ViewAttributes() ?>>
<?php echo $detfac->bruto->ListViewValue() ?></span>
<input type="hidden" data-field="x_bruto" name="x<?php echo $detfac_grid->RowIndex ?>_bruto" id="x<?php echo $detfac_grid->RowIndex ?>_bruto" value="<?php echo ew_HtmlEncode($detfac->bruto->FormValue) ?>">
<input type="hidden" data-field="x_bruto" name="o<?php echo $detfac_grid->RowIndex ?>_bruto" id="o<?php echo $detfac_grid->RowIndex ?>_bruto" value="<?php echo ew_HtmlEncode($detfac->bruto->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->iva->Visible) { // iva ?>
		<td data-name="iva"<?php echo $detfac->iva->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_iva" class="form-group detfac_iva">
<input type="text" data-field="x_iva" name="x<?php echo $detfac_grid->RowIndex ?>_iva" id="x<?php echo $detfac_grid->RowIndex ?>_iva" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->iva->PlaceHolder) ?>" value="<?php echo $detfac->iva->EditValue ?>"<?php echo $detfac->iva->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_iva" name="o<?php echo $detfac_grid->RowIndex ?>_iva" id="o<?php echo $detfac_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($detfac->iva->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_iva" class="form-group detfac_iva">
<input type="text" data-field="x_iva" name="x<?php echo $detfac_grid->RowIndex ?>_iva" id="x<?php echo $detfac_grid->RowIndex ?>_iva" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->iva->PlaceHolder) ?>" value="<?php echo $detfac->iva->EditValue ?>"<?php echo $detfac->iva->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->iva->ViewAttributes() ?>>
<?php echo $detfac->iva->ListViewValue() ?></span>
<input type="hidden" data-field="x_iva" name="x<?php echo $detfac_grid->RowIndex ?>_iva" id="x<?php echo $detfac_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($detfac->iva->FormValue) ?>">
<input type="hidden" data-field="x_iva" name="o<?php echo $detfac_grid->RowIndex ?>_iva" id="o<?php echo $detfac_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($detfac->iva->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->imp->Visible) { // imp ?>
		<td data-name="imp"<?php echo $detfac->imp->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_imp" class="form-group detfac_imp">
<input type="text" data-field="x_imp" name="x<?php echo $detfac_grid->RowIndex ?>_imp" id="x<?php echo $detfac_grid->RowIndex ?>_imp" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->imp->PlaceHolder) ?>" value="<?php echo $detfac->imp->EditValue ?>"<?php echo $detfac->imp->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_imp" name="o<?php echo $detfac_grid->RowIndex ?>_imp" id="o<?php echo $detfac_grid->RowIndex ?>_imp" value="<?php echo ew_HtmlEncode($detfac->imp->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_imp" class="form-group detfac_imp">
<input type="text" data-field="x_imp" name="x<?php echo $detfac_grid->RowIndex ?>_imp" id="x<?php echo $detfac_grid->RowIndex ?>_imp" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->imp->PlaceHolder) ?>" value="<?php echo $detfac->imp->EditValue ?>"<?php echo $detfac->imp->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->imp->ViewAttributes() ?>>
<?php echo $detfac->imp->ListViewValue() ?></span>
<input type="hidden" data-field="x_imp" name="x<?php echo $detfac_grid->RowIndex ?>_imp" id="x<?php echo $detfac_grid->RowIndex ?>_imp" value="<?php echo ew_HtmlEncode($detfac->imp->FormValue) ?>">
<input type="hidden" data-field="x_imp" name="o<?php echo $detfac_grid->RowIndex ?>_imp" id="o<?php echo $detfac_grid->RowIndex ?>_imp" value="<?php echo ew_HtmlEncode($detfac->imp->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->comcob->Visible) { // comcob ?>
		<td data-name="comcob"<?php echo $detfac->comcob->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_comcob" class="form-group detfac_comcob">
<input type="text" data-field="x_comcob" name="x<?php echo $detfac_grid->RowIndex ?>_comcob" id="x<?php echo $detfac_grid->RowIndex ?>_comcob" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->comcob->PlaceHolder) ?>" value="<?php echo $detfac->comcob->EditValue ?>"<?php echo $detfac->comcob->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_comcob" name="o<?php echo $detfac_grid->RowIndex ?>_comcob" id="o<?php echo $detfac_grid->RowIndex ?>_comcob" value="<?php echo ew_HtmlEncode($detfac->comcob->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_comcob" class="form-group detfac_comcob">
<input type="text" data-field="x_comcob" name="x<?php echo $detfac_grid->RowIndex ?>_comcob" id="x<?php echo $detfac_grid->RowIndex ?>_comcob" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->comcob->PlaceHolder) ?>" value="<?php echo $detfac->comcob->EditValue ?>"<?php echo $detfac->comcob->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->comcob->ViewAttributes() ?>>
<?php echo $detfac->comcob->ListViewValue() ?></span>
<input type="hidden" data-field="x_comcob" name="x<?php echo $detfac_grid->RowIndex ?>_comcob" id="x<?php echo $detfac_grid->RowIndex ?>_comcob" value="<?php echo ew_HtmlEncode($detfac->comcob->FormValue) ?>">
<input type="hidden" data-field="x_comcob" name="o<?php echo $detfac_grid->RowIndex ?>_comcob" id="o<?php echo $detfac_grid->RowIndex ?>_comcob" value="<?php echo ew_HtmlEncode($detfac->comcob->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->compag->Visible) { // compag ?>
		<td data-name="compag"<?php echo $detfac->compag->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_compag" class="form-group detfac_compag">
<input type="text" data-field="x_compag" name="x<?php echo $detfac_grid->RowIndex ?>_compag" id="x<?php echo $detfac_grid->RowIndex ?>_compag" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->compag->PlaceHolder) ?>" value="<?php echo $detfac->compag->EditValue ?>"<?php echo $detfac->compag->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_compag" name="o<?php echo $detfac_grid->RowIndex ?>_compag" id="o<?php echo $detfac_grid->RowIndex ?>_compag" value="<?php echo ew_HtmlEncode($detfac->compag->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_compag" class="form-group detfac_compag">
<input type="text" data-field="x_compag" name="x<?php echo $detfac_grid->RowIndex ?>_compag" id="x<?php echo $detfac_grid->RowIndex ?>_compag" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->compag->PlaceHolder) ?>" value="<?php echo $detfac->compag->EditValue ?>"<?php echo $detfac->compag->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->compag->ViewAttributes() ?>>
<?php echo $detfac->compag->ListViewValue() ?></span>
<input type="hidden" data-field="x_compag" name="x<?php echo $detfac_grid->RowIndex ?>_compag" id="x<?php echo $detfac_grid->RowIndex ?>_compag" value="<?php echo ew_HtmlEncode($detfac->compag->FormValue) ?>">
<input type="hidden" data-field="x_compag" name="o<?php echo $detfac_grid->RowIndex ?>_compag" id="o<?php echo $detfac_grid->RowIndex ?>_compag" value="<?php echo ew_HtmlEncode($detfac->compag->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->fechahora->Visible) { // fechahora ?>
		<td data-name="fechahora"<?php echo $detfac->fechahora->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_fechahora" class="form-group detfac_fechahora">
<input type="text" data-field="x_fechahora" name="x<?php echo $detfac_grid->RowIndex ?>_fechahora" id="x<?php echo $detfac_grid->RowIndex ?>_fechahora" placeholder="<?php echo ew_HtmlEncode($detfac->fechahora->PlaceHolder) ?>" value="<?php echo $detfac->fechahora->EditValue ?>"<?php echo $detfac->fechahora->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_fechahora" name="o<?php echo $detfac_grid->RowIndex ?>_fechahora" id="o<?php echo $detfac_grid->RowIndex ?>_fechahora" value="<?php echo ew_HtmlEncode($detfac->fechahora->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_fechahora" class="form-group detfac_fechahora">
<input type="text" data-field="x_fechahora" name="x<?php echo $detfac_grid->RowIndex ?>_fechahora" id="x<?php echo $detfac_grid->RowIndex ?>_fechahora" placeholder="<?php echo ew_HtmlEncode($detfac->fechahora->PlaceHolder) ?>" value="<?php echo $detfac->fechahora->EditValue ?>"<?php echo $detfac->fechahora->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->fechahora->ViewAttributes() ?>>
<?php echo $detfac->fechahora->ListViewValue() ?></span>
<input type="hidden" data-field="x_fechahora" name="x<?php echo $detfac_grid->RowIndex ?>_fechahora" id="x<?php echo $detfac_grid->RowIndex ?>_fechahora" value="<?php echo ew_HtmlEncode($detfac->fechahora->FormValue) ?>">
<input type="hidden" data-field="x_fechahora" name="o<?php echo $detfac_grid->RowIndex ?>_fechahora" id="o<?php echo $detfac_grid->RowIndex ?>_fechahora" value="<?php echo ew_HtmlEncode($detfac->fechahora->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->usuario->Visible) { // usuario ?>
		<td data-name="usuario"<?php echo $detfac->usuario->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_usuario" class="form-group detfac_usuario">
<input type="text" data-field="x_usuario" name="x<?php echo $detfac_grid->RowIndex ?>_usuario" id="x<?php echo $detfac_grid->RowIndex ?>_usuario" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->usuario->PlaceHolder) ?>" value="<?php echo $detfac->usuario->EditValue ?>"<?php echo $detfac->usuario->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_usuario" name="o<?php echo $detfac_grid->RowIndex ?>_usuario" id="o<?php echo $detfac_grid->RowIndex ?>_usuario" value="<?php echo ew_HtmlEncode($detfac->usuario->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_usuario" class="form-group detfac_usuario">
<input type="text" data-field="x_usuario" name="x<?php echo $detfac_grid->RowIndex ?>_usuario" id="x<?php echo $detfac_grid->RowIndex ?>_usuario" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->usuario->PlaceHolder) ?>" value="<?php echo $detfac->usuario->EditValue ?>"<?php echo $detfac->usuario->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->usuario->ViewAttributes() ?>>
<?php echo $detfac->usuario->ListViewValue() ?></span>
<input type="hidden" data-field="x_usuario" name="x<?php echo $detfac_grid->RowIndex ?>_usuario" id="x<?php echo $detfac_grid->RowIndex ?>_usuario" value="<?php echo ew_HtmlEncode($detfac->usuario->FormValue) ?>">
<input type="hidden" data-field="x_usuario" name="o<?php echo $detfac_grid->RowIndex ?>_usuario" id="o<?php echo $detfac_grid->RowIndex ?>_usuario" value="<?php echo ew_HtmlEncode($detfac->usuario->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->porciva->Visible) { // porciva ?>
		<td data-name="porciva"<?php echo $detfac->porciva->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_porciva" class="form-group detfac_porciva">
<input type="text" data-field="x_porciva" name="x<?php echo $detfac_grid->RowIndex ?>_porciva" id="x<?php echo $detfac_grid->RowIndex ?>_porciva" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->porciva->PlaceHolder) ?>" value="<?php echo $detfac->porciva->EditValue ?>"<?php echo $detfac->porciva->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_porciva" name="o<?php echo $detfac_grid->RowIndex ?>_porciva" id="o<?php echo $detfac_grid->RowIndex ?>_porciva" value="<?php echo ew_HtmlEncode($detfac->porciva->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_porciva" class="form-group detfac_porciva">
<input type="text" data-field="x_porciva" name="x<?php echo $detfac_grid->RowIndex ?>_porciva" id="x<?php echo $detfac_grid->RowIndex ?>_porciva" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->porciva->PlaceHolder) ?>" value="<?php echo $detfac->porciva->EditValue ?>"<?php echo $detfac->porciva->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->porciva->ViewAttributes() ?>>
<?php echo $detfac->porciva->ListViewValue() ?></span>
<input type="hidden" data-field="x_porciva" name="x<?php echo $detfac_grid->RowIndex ?>_porciva" id="x<?php echo $detfac_grid->RowIndex ?>_porciva" value="<?php echo ew_HtmlEncode($detfac->porciva->FormValue) ?>">
<input type="hidden" data-field="x_porciva" name="o<?php echo $detfac_grid->RowIndex ?>_porciva" id="o<?php echo $detfac_grid->RowIndex ?>_porciva" value="<?php echo ew_HtmlEncode($detfac->porciva->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->tieneresol->Visible) { // tieneresol ?>
		<td data-name="tieneresol"<?php echo $detfac->tieneresol->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_tieneresol" class="form-group detfac_tieneresol">
<input type="text" data-field="x_tieneresol" name="x<?php echo $detfac_grid->RowIndex ?>_tieneresol" id="x<?php echo $detfac_grid->RowIndex ?>_tieneresol" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->tieneresol->PlaceHolder) ?>" value="<?php echo $detfac->tieneresol->EditValue ?>"<?php echo $detfac->tieneresol->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_tieneresol" name="o<?php echo $detfac_grid->RowIndex ?>_tieneresol" id="o<?php echo $detfac_grid->RowIndex ?>_tieneresol" value="<?php echo ew_HtmlEncode($detfac->tieneresol->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_tieneresol" class="form-group detfac_tieneresol">
<input type="text" data-field="x_tieneresol" name="x<?php echo $detfac_grid->RowIndex ?>_tieneresol" id="x<?php echo $detfac_grid->RowIndex ?>_tieneresol" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->tieneresol->PlaceHolder) ?>" value="<?php echo $detfac->tieneresol->EditValue ?>"<?php echo $detfac->tieneresol->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->tieneresol->ViewAttributes() ?>>
<?php echo $detfac->tieneresol->ListViewValue() ?></span>
<input type="hidden" data-field="x_tieneresol" name="x<?php echo $detfac_grid->RowIndex ?>_tieneresol" id="x<?php echo $detfac_grid->RowIndex ?>_tieneresol" value="<?php echo ew_HtmlEncode($detfac->tieneresol->FormValue) ?>">
<input type="hidden" data-field="x_tieneresol" name="o<?php echo $detfac_grid->RowIndex ?>_tieneresol" id="o<?php echo $detfac_grid->RowIndex ?>_tieneresol" value="<?php echo ew_HtmlEncode($detfac->tieneresol->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->concafac->Visible) { // concafac ?>
		<td data-name="concafac"<?php echo $detfac->concafac->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_concafac" class="form-group detfac_concafac">
<input type="text" data-field="x_concafac" name="x<?php echo $detfac_grid->RowIndex ?>_concafac" id="x<?php echo $detfac_grid->RowIndex ?>_concafac" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->concafac->PlaceHolder) ?>" value="<?php echo $detfac->concafac->EditValue ?>"<?php echo $detfac->concafac->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_concafac" name="o<?php echo $detfac_grid->RowIndex ?>_concafac" id="o<?php echo $detfac_grid->RowIndex ?>_concafac" value="<?php echo ew_HtmlEncode($detfac->concafac->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_concafac" class="form-group detfac_concafac">
<input type="text" data-field="x_concafac" name="x<?php echo $detfac_grid->RowIndex ?>_concafac" id="x<?php echo $detfac_grid->RowIndex ?>_concafac" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->concafac->PlaceHolder) ?>" value="<?php echo $detfac->concafac->EditValue ?>"<?php echo $detfac->concafac->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->concafac->ViewAttributes() ?>>
<?php echo $detfac->concafac->ListViewValue() ?></span>
<input type="hidden" data-field="x_concafac" name="x<?php echo $detfac_grid->RowIndex ?>_concafac" id="x<?php echo $detfac_grid->RowIndex ?>_concafac" value="<?php echo ew_HtmlEncode($detfac->concafac->FormValue) ?>">
<input type="hidden" data-field="x_concafac" name="o<?php echo $detfac_grid->RowIndex ?>_concafac" id="o<?php echo $detfac_grid->RowIndex ?>_concafac" value="<?php echo ew_HtmlEncode($detfac->concafac->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->tcomsal->Visible) { // tcomsal ?>
		<td data-name="tcomsal"<?php echo $detfac->tcomsal->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_tcomsal" class="form-group detfac_tcomsal">
<input type="text" data-field="x_tcomsal" name="x<?php echo $detfac_grid->RowIndex ?>_tcomsal" id="x<?php echo $detfac_grid->RowIndex ?>_tcomsal" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->tcomsal->PlaceHolder) ?>" value="<?php echo $detfac->tcomsal->EditValue ?>"<?php echo $detfac->tcomsal->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_tcomsal" name="o<?php echo $detfac_grid->RowIndex ?>_tcomsal" id="o<?php echo $detfac_grid->RowIndex ?>_tcomsal" value="<?php echo ew_HtmlEncode($detfac->tcomsal->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_tcomsal" class="form-group detfac_tcomsal">
<input type="text" data-field="x_tcomsal" name="x<?php echo $detfac_grid->RowIndex ?>_tcomsal" id="x<?php echo $detfac_grid->RowIndex ?>_tcomsal" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->tcomsal->PlaceHolder) ?>" value="<?php echo $detfac->tcomsal->EditValue ?>"<?php echo $detfac->tcomsal->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->tcomsal->ViewAttributes() ?>>
<?php echo $detfac->tcomsal->ListViewValue() ?></span>
<input type="hidden" data-field="x_tcomsal" name="x<?php echo $detfac_grid->RowIndex ?>_tcomsal" id="x<?php echo $detfac_grid->RowIndex ?>_tcomsal" value="<?php echo ew_HtmlEncode($detfac->tcomsal->FormValue) ?>">
<input type="hidden" data-field="x_tcomsal" name="o<?php echo $detfac_grid->RowIndex ?>_tcomsal" id="o<?php echo $detfac_grid->RowIndex ?>_tcomsal" value="<?php echo ew_HtmlEncode($detfac->tcomsal->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->seriesal->Visible) { // seriesal ?>
		<td data-name="seriesal"<?php echo $detfac->seriesal->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_seriesal" class="form-group detfac_seriesal">
<input type="text" data-field="x_seriesal" name="x<?php echo $detfac_grid->RowIndex ?>_seriesal" id="x<?php echo $detfac_grid->RowIndex ?>_seriesal" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->seriesal->PlaceHolder) ?>" value="<?php echo $detfac->seriesal->EditValue ?>"<?php echo $detfac->seriesal->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_seriesal" name="o<?php echo $detfac_grid->RowIndex ?>_seriesal" id="o<?php echo $detfac_grid->RowIndex ?>_seriesal" value="<?php echo ew_HtmlEncode($detfac->seriesal->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_seriesal" class="form-group detfac_seriesal">
<input type="text" data-field="x_seriesal" name="x<?php echo $detfac_grid->RowIndex ?>_seriesal" id="x<?php echo $detfac_grid->RowIndex ?>_seriesal" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->seriesal->PlaceHolder) ?>" value="<?php echo $detfac->seriesal->EditValue ?>"<?php echo $detfac->seriesal->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->seriesal->ViewAttributes() ?>>
<?php echo $detfac->seriesal->ListViewValue() ?></span>
<input type="hidden" data-field="x_seriesal" name="x<?php echo $detfac_grid->RowIndex ?>_seriesal" id="x<?php echo $detfac_grid->RowIndex ?>_seriesal" value="<?php echo ew_HtmlEncode($detfac->seriesal->FormValue) ?>">
<input type="hidden" data-field="x_seriesal" name="o<?php echo $detfac_grid->RowIndex ?>_seriesal" id="o<?php echo $detfac_grid->RowIndex ?>_seriesal" value="<?php echo ew_HtmlEncode($detfac->seriesal->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfac->ncompsal->Visible) { // ncompsal ?>
		<td data-name="ncompsal"<?php echo $detfac->ncompsal->CellAttributes() ?>>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_ncompsal" class="form-group detfac_ncompsal">
<input type="text" data-field="x_ncompsal" name="x<?php echo $detfac_grid->RowIndex ?>_ncompsal" id="x<?php echo $detfac_grid->RowIndex ?>_ncompsal" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->ncompsal->PlaceHolder) ?>" value="<?php echo $detfac->ncompsal->EditValue ?>"<?php echo $detfac->ncompsal->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_ncompsal" name="o<?php echo $detfac_grid->RowIndex ?>_ncompsal" id="o<?php echo $detfac_grid->RowIndex ?>_ncompsal" value="<?php echo ew_HtmlEncode($detfac->ncompsal->OldValue) ?>">
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfac_grid->RowCnt ?>_detfac_ncompsal" class="form-group detfac_ncompsal">
<input type="text" data-field="x_ncompsal" name="x<?php echo $detfac_grid->RowIndex ?>_ncompsal" id="x<?php echo $detfac_grid->RowIndex ?>_ncompsal" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->ncompsal->PlaceHolder) ?>" value="<?php echo $detfac->ncompsal->EditValue ?>"<?php echo $detfac->ncompsal->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfac->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfac->ncompsal->ViewAttributes() ?>>
<?php echo $detfac->ncompsal->ListViewValue() ?></span>
<input type="hidden" data-field="x_ncompsal" name="x<?php echo $detfac_grid->RowIndex ?>_ncompsal" id="x<?php echo $detfac_grid->RowIndex ?>_ncompsal" value="<?php echo ew_HtmlEncode($detfac->ncompsal->FormValue) ?>">
<input type="hidden" data-field="x_ncompsal" name="o<?php echo $detfac_grid->RowIndex ?>_ncompsal" id="o<?php echo $detfac_grid->RowIndex ?>_ncompsal" value="<?php echo ew_HtmlEncode($detfac->ncompsal->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detfac_grid->ListOptions->Render("body", "right", $detfac_grid->RowCnt);
?>
	</tr>
<?php if ($detfac->RowType == EW_ROWTYPE_ADD || $detfac->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdetfacgrid.UpdateOpts(<?php echo $detfac_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($detfac->CurrentAction <> "gridadd" || $detfac->CurrentMode == "copy")
		if (!$detfac_grid->Recordset->EOF) $detfac_grid->Recordset->MoveNext();
}
?>
<?php
	if ($detfac->CurrentMode == "add" || $detfac->CurrentMode == "copy" || $detfac->CurrentMode == "edit") {
		$detfac_grid->RowIndex = '$rowindex$';
		$detfac_grid->LoadDefaultValues();

		// Set row properties
		$detfac->ResetAttrs();
		$detfac->RowAttrs = array_merge($detfac->RowAttrs, array('data-rowindex'=>$detfac_grid->RowIndex, 'id'=>'r0_detfac', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($detfac->RowAttrs["class"], "ewTemplate");
		$detfac->RowType = EW_ROWTYPE_ADD;

		// Render row
		$detfac_grid->RenderRow();

		// Render list options
		$detfac_grid->RenderListOptions();
		$detfac_grid->StartRowCnt = 0;
?>
	<tr<?php echo $detfac->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detfac_grid->ListOptions->Render("body", "left", $detfac_grid->RowIndex);
?>
	<?php if ($detfac->codnum->Visible) { // codnum ?>
		<td data-name="codnum">
<?php if ($detfac->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_detfac_codnum" class="form-group detfac_codnum">
<span<?php echo $detfac->codnum->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->codnum->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codnum" name="x<?php echo $detfac_grid->RowIndex ?>_codnum" id="x<?php echo $detfac_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($detfac->codnum->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codnum" name="o<?php echo $detfac_grid->RowIndex ?>_codnum" id="o<?php echo $detfac_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($detfac->codnum->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->tcomp->Visible) { // tcomp ?>
		<td data-name="tcomp">
<?php if ($detfac->CurrentAction <> "F") { ?>
<?php if ($detfac->tcomp->getSessionValue() <> "") { ?>
<span id="el$rowindex$_detfac_tcomp" class="form-group detfac_tcomp">
<span<?php echo $detfac->tcomp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->tcomp->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detfac_grid->RowIndex ?>_tcomp" name="x<?php echo $detfac_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($detfac->tcomp->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_detfac_tcomp" class="form-group detfac_tcomp">
<input type="text" data-field="x_tcomp" name="x<?php echo $detfac_grid->RowIndex ?>_tcomp" id="x<?php echo $detfac_grid->RowIndex ?>_tcomp" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->tcomp->PlaceHolder) ?>" value="<?php echo $detfac->tcomp->EditValue ?>"<?php echo $detfac->tcomp->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_detfac_tcomp" class="form-group detfac_tcomp">
<span<?php echo $detfac->tcomp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->tcomp->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_tcomp" name="x<?php echo $detfac_grid->RowIndex ?>_tcomp" id="x<?php echo $detfac_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($detfac->tcomp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_tcomp" name="o<?php echo $detfac_grid->RowIndex ?>_tcomp" id="o<?php echo $detfac_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($detfac->tcomp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->serie->Visible) { // serie ?>
		<td data-name="serie">
<?php if ($detfac->CurrentAction <> "F") { ?>
<?php if ($detfac->serie->getSessionValue() <> "") { ?>
<span id="el$rowindex$_detfac_serie" class="form-group detfac_serie">
<span<?php echo $detfac->serie->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->serie->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detfac_grid->RowIndex ?>_serie" name="x<?php echo $detfac_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($detfac->serie->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_detfac_serie" class="form-group detfac_serie">
<input type="text" data-field="x_serie" name="x<?php echo $detfac_grid->RowIndex ?>_serie" id="x<?php echo $detfac_grid->RowIndex ?>_serie" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->serie->PlaceHolder) ?>" value="<?php echo $detfac->serie->EditValue ?>"<?php echo $detfac->serie->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_detfac_serie" class="form-group detfac_serie">
<span<?php echo $detfac->serie->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->serie->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_serie" name="x<?php echo $detfac_grid->RowIndex ?>_serie" id="x<?php echo $detfac_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($detfac->serie->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_serie" name="o<?php echo $detfac_grid->RowIndex ?>_serie" id="o<?php echo $detfac_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($detfac->serie->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->ncomp->Visible) { // ncomp ?>
		<td data-name="ncomp">
<?php if ($detfac->CurrentAction <> "F") { ?>
<?php if ($detfac->ncomp->getSessionValue() <> "") { ?>
<span id="el$rowindex$_detfac_ncomp" class="form-group detfac_ncomp">
<span<?php echo $detfac->ncomp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->ncomp->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detfac_grid->RowIndex ?>_ncomp" name="x<?php echo $detfac_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($detfac->ncomp->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_detfac_ncomp" class="form-group detfac_ncomp">
<input type="text" data-field="x_ncomp" name="x<?php echo $detfac_grid->RowIndex ?>_ncomp" id="x<?php echo $detfac_grid->RowIndex ?>_ncomp" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->ncomp->PlaceHolder) ?>" value="<?php echo $detfac->ncomp->EditValue ?>"<?php echo $detfac->ncomp->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_detfac_ncomp" class="form-group detfac_ncomp">
<span<?php echo $detfac->ncomp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->ncomp->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_ncomp" name="x<?php echo $detfac_grid->RowIndex ?>_ncomp" id="x<?php echo $detfac_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($detfac->ncomp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ncomp" name="o<?php echo $detfac_grid->RowIndex ?>_ncomp" id="o<?php echo $detfac_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($detfac->ncomp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->nreng->Visible) { // nreng ?>
		<td data-name="nreng">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_nreng" class="form-group detfac_nreng">
<input type="text" data-field="x_nreng" name="x<?php echo $detfac_grid->RowIndex ?>_nreng" id="x<?php echo $detfac_grid->RowIndex ?>_nreng" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->nreng->PlaceHolder) ?>" value="<?php echo $detfac->nreng->EditValue ?>"<?php echo $detfac->nreng->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_nreng" class="form-group detfac_nreng">
<span<?php echo $detfac->nreng->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->nreng->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_nreng" name="x<?php echo $detfac_grid->RowIndex ?>_nreng" id="x<?php echo $detfac_grid->RowIndex ?>_nreng" value="<?php echo ew_HtmlEncode($detfac->nreng->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nreng" name="o<?php echo $detfac_grid->RowIndex ?>_nreng" id="o<?php echo $detfac_grid->RowIndex ?>_nreng" value="<?php echo ew_HtmlEncode($detfac->nreng->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->codrem->Visible) { // codrem ?>
		<td data-name="codrem">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_codrem" class="form-group detfac_codrem">
<input type="text" data-field="x_codrem" name="x<?php echo $detfac_grid->RowIndex ?>_codrem" id="x<?php echo $detfac_grid->RowIndex ?>_codrem" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->codrem->PlaceHolder) ?>" value="<?php echo $detfac->codrem->EditValue ?>"<?php echo $detfac->codrem->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_codrem" class="form-group detfac_codrem">
<span<?php echo $detfac->codrem->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->codrem->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codrem" name="x<?php echo $detfac_grid->RowIndex ?>_codrem" id="x<?php echo $detfac_grid->RowIndex ?>_codrem" value="<?php echo ew_HtmlEncode($detfac->codrem->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codrem" name="o<?php echo $detfac_grid->RowIndex ?>_codrem" id="o<?php echo $detfac_grid->RowIndex ?>_codrem" value="<?php echo ew_HtmlEncode($detfac->codrem->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->codlote->Visible) { // codlote ?>
		<td data-name="codlote">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_codlote" class="form-group detfac_codlote">
<input type="text" data-field="x_codlote" name="x<?php echo $detfac_grid->RowIndex ?>_codlote" id="x<?php echo $detfac_grid->RowIndex ?>_codlote" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->codlote->PlaceHolder) ?>" value="<?php echo $detfac->codlote->EditValue ?>"<?php echo $detfac->codlote->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_codlote" class="form-group detfac_codlote">
<span<?php echo $detfac->codlote->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->codlote->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codlote" name="x<?php echo $detfac_grid->RowIndex ?>_codlote" id="x<?php echo $detfac_grid->RowIndex ?>_codlote" value="<?php echo ew_HtmlEncode($detfac->codlote->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codlote" name="o<?php echo $detfac_grid->RowIndex ?>_codlote" id="o<?php echo $detfac_grid->RowIndex ?>_codlote" value="<?php echo ew_HtmlEncode($detfac->codlote->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->descrip->Visible) { // descrip ?>
		<td data-name="descrip">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_descrip" class="form-group detfac_descrip">
<input type="text" data-field="x_descrip" name="x<?php echo $detfac_grid->RowIndex ?>_descrip" id="x<?php echo $detfac_grid->RowIndex ?>_descrip" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($detfac->descrip->PlaceHolder) ?>" value="<?php echo $detfac->descrip->EditValue ?>"<?php echo $detfac->descrip->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_descrip" class="form-group detfac_descrip">
<span<?php echo $detfac->descrip->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->descrip->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_descrip" name="x<?php echo $detfac_grid->RowIndex ?>_descrip" id="x<?php echo $detfac_grid->RowIndex ?>_descrip" value="<?php echo ew_HtmlEncode($detfac->descrip->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_descrip" name="o<?php echo $detfac_grid->RowIndex ?>_descrip" id="o<?php echo $detfac_grid->RowIndex ?>_descrip" value="<?php echo ew_HtmlEncode($detfac->descrip->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->neto->Visible) { // neto ?>
		<td data-name="neto">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_neto" class="form-group detfac_neto">
<input type="text" data-field="x_neto" name="x<?php echo $detfac_grid->RowIndex ?>_neto" id="x<?php echo $detfac_grid->RowIndex ?>_neto" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->neto->PlaceHolder) ?>" value="<?php echo $detfac->neto->EditValue ?>"<?php echo $detfac->neto->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_neto" class="form-group detfac_neto">
<span<?php echo $detfac->neto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->neto->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_neto" name="x<?php echo $detfac_grid->RowIndex ?>_neto" id="x<?php echo $detfac_grid->RowIndex ?>_neto" value="<?php echo ew_HtmlEncode($detfac->neto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_neto" name="o<?php echo $detfac_grid->RowIndex ?>_neto" id="o<?php echo $detfac_grid->RowIndex ?>_neto" value="<?php echo ew_HtmlEncode($detfac->neto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->bruto->Visible) { // bruto ?>
		<td data-name="bruto">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_bruto" class="form-group detfac_bruto">
<input type="text" data-field="x_bruto" name="x<?php echo $detfac_grid->RowIndex ?>_bruto" id="x<?php echo $detfac_grid->RowIndex ?>_bruto" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->bruto->PlaceHolder) ?>" value="<?php echo $detfac->bruto->EditValue ?>"<?php echo $detfac->bruto->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_bruto" class="form-group detfac_bruto">
<span<?php echo $detfac->bruto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->bruto->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_bruto" name="x<?php echo $detfac_grid->RowIndex ?>_bruto" id="x<?php echo $detfac_grid->RowIndex ?>_bruto" value="<?php echo ew_HtmlEncode($detfac->bruto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_bruto" name="o<?php echo $detfac_grid->RowIndex ?>_bruto" id="o<?php echo $detfac_grid->RowIndex ?>_bruto" value="<?php echo ew_HtmlEncode($detfac->bruto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->iva->Visible) { // iva ?>
		<td data-name="iva">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_iva" class="form-group detfac_iva">
<input type="text" data-field="x_iva" name="x<?php echo $detfac_grid->RowIndex ?>_iva" id="x<?php echo $detfac_grid->RowIndex ?>_iva" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->iva->PlaceHolder) ?>" value="<?php echo $detfac->iva->EditValue ?>"<?php echo $detfac->iva->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_iva" class="form-group detfac_iva">
<span<?php echo $detfac->iva->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->iva->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_iva" name="x<?php echo $detfac_grid->RowIndex ?>_iva" id="x<?php echo $detfac_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($detfac->iva->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_iva" name="o<?php echo $detfac_grid->RowIndex ?>_iva" id="o<?php echo $detfac_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($detfac->iva->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->imp->Visible) { // imp ?>
		<td data-name="imp">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_imp" class="form-group detfac_imp">
<input type="text" data-field="x_imp" name="x<?php echo $detfac_grid->RowIndex ?>_imp" id="x<?php echo $detfac_grid->RowIndex ?>_imp" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->imp->PlaceHolder) ?>" value="<?php echo $detfac->imp->EditValue ?>"<?php echo $detfac->imp->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_imp" class="form-group detfac_imp">
<span<?php echo $detfac->imp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->imp->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_imp" name="x<?php echo $detfac_grid->RowIndex ?>_imp" id="x<?php echo $detfac_grid->RowIndex ?>_imp" value="<?php echo ew_HtmlEncode($detfac->imp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_imp" name="o<?php echo $detfac_grid->RowIndex ?>_imp" id="o<?php echo $detfac_grid->RowIndex ?>_imp" value="<?php echo ew_HtmlEncode($detfac->imp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->comcob->Visible) { // comcob ?>
		<td data-name="comcob">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_comcob" class="form-group detfac_comcob">
<input type="text" data-field="x_comcob" name="x<?php echo $detfac_grid->RowIndex ?>_comcob" id="x<?php echo $detfac_grid->RowIndex ?>_comcob" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->comcob->PlaceHolder) ?>" value="<?php echo $detfac->comcob->EditValue ?>"<?php echo $detfac->comcob->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_comcob" class="form-group detfac_comcob">
<span<?php echo $detfac->comcob->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->comcob->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_comcob" name="x<?php echo $detfac_grid->RowIndex ?>_comcob" id="x<?php echo $detfac_grid->RowIndex ?>_comcob" value="<?php echo ew_HtmlEncode($detfac->comcob->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_comcob" name="o<?php echo $detfac_grid->RowIndex ?>_comcob" id="o<?php echo $detfac_grid->RowIndex ?>_comcob" value="<?php echo ew_HtmlEncode($detfac->comcob->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->compag->Visible) { // compag ?>
		<td data-name="compag">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_compag" class="form-group detfac_compag">
<input type="text" data-field="x_compag" name="x<?php echo $detfac_grid->RowIndex ?>_compag" id="x<?php echo $detfac_grid->RowIndex ?>_compag" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->compag->PlaceHolder) ?>" value="<?php echo $detfac->compag->EditValue ?>"<?php echo $detfac->compag->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_compag" class="form-group detfac_compag">
<span<?php echo $detfac->compag->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->compag->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_compag" name="x<?php echo $detfac_grid->RowIndex ?>_compag" id="x<?php echo $detfac_grid->RowIndex ?>_compag" value="<?php echo ew_HtmlEncode($detfac->compag->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_compag" name="o<?php echo $detfac_grid->RowIndex ?>_compag" id="o<?php echo $detfac_grid->RowIndex ?>_compag" value="<?php echo ew_HtmlEncode($detfac->compag->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->fechahora->Visible) { // fechahora ?>
		<td data-name="fechahora">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_fechahora" class="form-group detfac_fechahora">
<input type="text" data-field="x_fechahora" name="x<?php echo $detfac_grid->RowIndex ?>_fechahora" id="x<?php echo $detfac_grid->RowIndex ?>_fechahora" placeholder="<?php echo ew_HtmlEncode($detfac->fechahora->PlaceHolder) ?>" value="<?php echo $detfac->fechahora->EditValue ?>"<?php echo $detfac->fechahora->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_fechahora" class="form-group detfac_fechahora">
<span<?php echo $detfac->fechahora->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->fechahora->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fechahora" name="x<?php echo $detfac_grid->RowIndex ?>_fechahora" id="x<?php echo $detfac_grid->RowIndex ?>_fechahora" value="<?php echo ew_HtmlEncode($detfac->fechahora->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fechahora" name="o<?php echo $detfac_grid->RowIndex ?>_fechahora" id="o<?php echo $detfac_grid->RowIndex ?>_fechahora" value="<?php echo ew_HtmlEncode($detfac->fechahora->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->usuario->Visible) { // usuario ?>
		<td data-name="usuario">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_usuario" class="form-group detfac_usuario">
<input type="text" data-field="x_usuario" name="x<?php echo $detfac_grid->RowIndex ?>_usuario" id="x<?php echo $detfac_grid->RowIndex ?>_usuario" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->usuario->PlaceHolder) ?>" value="<?php echo $detfac->usuario->EditValue ?>"<?php echo $detfac->usuario->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_usuario" class="form-group detfac_usuario">
<span<?php echo $detfac->usuario->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->usuario->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_usuario" name="x<?php echo $detfac_grid->RowIndex ?>_usuario" id="x<?php echo $detfac_grid->RowIndex ?>_usuario" value="<?php echo ew_HtmlEncode($detfac->usuario->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_usuario" name="o<?php echo $detfac_grid->RowIndex ?>_usuario" id="o<?php echo $detfac_grid->RowIndex ?>_usuario" value="<?php echo ew_HtmlEncode($detfac->usuario->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->porciva->Visible) { // porciva ?>
		<td data-name="porciva">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_porciva" class="form-group detfac_porciva">
<input type="text" data-field="x_porciva" name="x<?php echo $detfac_grid->RowIndex ?>_porciva" id="x<?php echo $detfac_grid->RowIndex ?>_porciva" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->porciva->PlaceHolder) ?>" value="<?php echo $detfac->porciva->EditValue ?>"<?php echo $detfac->porciva->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_porciva" class="form-group detfac_porciva">
<span<?php echo $detfac->porciva->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->porciva->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_porciva" name="x<?php echo $detfac_grid->RowIndex ?>_porciva" id="x<?php echo $detfac_grid->RowIndex ?>_porciva" value="<?php echo ew_HtmlEncode($detfac->porciva->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_porciva" name="o<?php echo $detfac_grid->RowIndex ?>_porciva" id="o<?php echo $detfac_grid->RowIndex ?>_porciva" value="<?php echo ew_HtmlEncode($detfac->porciva->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->tieneresol->Visible) { // tieneresol ?>
		<td data-name="tieneresol">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_tieneresol" class="form-group detfac_tieneresol">
<input type="text" data-field="x_tieneresol" name="x<?php echo $detfac_grid->RowIndex ?>_tieneresol" id="x<?php echo $detfac_grid->RowIndex ?>_tieneresol" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->tieneresol->PlaceHolder) ?>" value="<?php echo $detfac->tieneresol->EditValue ?>"<?php echo $detfac->tieneresol->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_tieneresol" class="form-group detfac_tieneresol">
<span<?php echo $detfac->tieneresol->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->tieneresol->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_tieneresol" name="x<?php echo $detfac_grid->RowIndex ?>_tieneresol" id="x<?php echo $detfac_grid->RowIndex ?>_tieneresol" value="<?php echo ew_HtmlEncode($detfac->tieneresol->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_tieneresol" name="o<?php echo $detfac_grid->RowIndex ?>_tieneresol" id="o<?php echo $detfac_grid->RowIndex ?>_tieneresol" value="<?php echo ew_HtmlEncode($detfac->tieneresol->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->concafac->Visible) { // concafac ?>
		<td data-name="concafac">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_concafac" class="form-group detfac_concafac">
<input type="text" data-field="x_concafac" name="x<?php echo $detfac_grid->RowIndex ?>_concafac" id="x<?php echo $detfac_grid->RowIndex ?>_concafac" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->concafac->PlaceHolder) ?>" value="<?php echo $detfac->concafac->EditValue ?>"<?php echo $detfac->concafac->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_concafac" class="form-group detfac_concafac">
<span<?php echo $detfac->concafac->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->concafac->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_concafac" name="x<?php echo $detfac_grid->RowIndex ?>_concafac" id="x<?php echo $detfac_grid->RowIndex ?>_concafac" value="<?php echo ew_HtmlEncode($detfac->concafac->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_concafac" name="o<?php echo $detfac_grid->RowIndex ?>_concafac" id="o<?php echo $detfac_grid->RowIndex ?>_concafac" value="<?php echo ew_HtmlEncode($detfac->concafac->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->tcomsal->Visible) { // tcomsal ?>
		<td data-name="tcomsal">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_tcomsal" class="form-group detfac_tcomsal">
<input type="text" data-field="x_tcomsal" name="x<?php echo $detfac_grid->RowIndex ?>_tcomsal" id="x<?php echo $detfac_grid->RowIndex ?>_tcomsal" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->tcomsal->PlaceHolder) ?>" value="<?php echo $detfac->tcomsal->EditValue ?>"<?php echo $detfac->tcomsal->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_tcomsal" class="form-group detfac_tcomsal">
<span<?php echo $detfac->tcomsal->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->tcomsal->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_tcomsal" name="x<?php echo $detfac_grid->RowIndex ?>_tcomsal" id="x<?php echo $detfac_grid->RowIndex ?>_tcomsal" value="<?php echo ew_HtmlEncode($detfac->tcomsal->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_tcomsal" name="o<?php echo $detfac_grid->RowIndex ?>_tcomsal" id="o<?php echo $detfac_grid->RowIndex ?>_tcomsal" value="<?php echo ew_HtmlEncode($detfac->tcomsal->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->seriesal->Visible) { // seriesal ?>
		<td data-name="seriesal">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_seriesal" class="form-group detfac_seriesal">
<input type="text" data-field="x_seriesal" name="x<?php echo $detfac_grid->RowIndex ?>_seriesal" id="x<?php echo $detfac_grid->RowIndex ?>_seriesal" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->seriesal->PlaceHolder) ?>" value="<?php echo $detfac->seriesal->EditValue ?>"<?php echo $detfac->seriesal->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_seriesal" class="form-group detfac_seriesal">
<span<?php echo $detfac->seriesal->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->seriesal->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_seriesal" name="x<?php echo $detfac_grid->RowIndex ?>_seriesal" id="x<?php echo $detfac_grid->RowIndex ?>_seriesal" value="<?php echo ew_HtmlEncode($detfac->seriesal->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_seriesal" name="o<?php echo $detfac_grid->RowIndex ?>_seriesal" id="o<?php echo $detfac_grid->RowIndex ?>_seriesal" value="<?php echo ew_HtmlEncode($detfac->seriesal->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfac->ncompsal->Visible) { // ncompsal ?>
		<td data-name="ncompsal">
<?php if ($detfac->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfac_ncompsal" class="form-group detfac_ncompsal">
<input type="text" data-field="x_ncompsal" name="x<?php echo $detfac_grid->RowIndex ?>_ncompsal" id="x<?php echo $detfac_grid->RowIndex ?>_ncompsal" size="30" placeholder="<?php echo ew_HtmlEncode($detfac->ncompsal->PlaceHolder) ?>" value="<?php echo $detfac->ncompsal->EditValue ?>"<?php echo $detfac->ncompsal->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfac_ncompsal" class="form-group detfac_ncompsal">
<span<?php echo $detfac->ncompsal->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfac->ncompsal->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_ncompsal" name="x<?php echo $detfac_grid->RowIndex ?>_ncompsal" id="x<?php echo $detfac_grid->RowIndex ?>_ncompsal" value="<?php echo ew_HtmlEncode($detfac->ncompsal->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ncompsal" name="o<?php echo $detfac_grid->RowIndex ?>_ncompsal" id="o<?php echo $detfac_grid->RowIndex ?>_ncompsal" value="<?php echo ew_HtmlEncode($detfac->ncompsal->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detfac_grid->ListOptions->Render("body", "right", $detfac_grid->RowCnt);
?>
<script type="text/javascript">
fdetfacgrid.UpdateOpts(<?php echo $detfac_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($detfac->CurrentMode == "add" || $detfac->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $detfac_grid->FormKeyCountName ?>" id="<?php echo $detfac_grid->FormKeyCountName ?>" value="<?php echo $detfac_grid->KeyCount ?>">
<?php echo $detfac_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($detfac->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $detfac_grid->FormKeyCountName ?>" id="<?php echo $detfac_grid->FormKeyCountName ?>" value="<?php echo $detfac_grid->KeyCount ?>">
<?php echo $detfac_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($detfac->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdetfacgrid">
</div>
<?php

// Close recordset
if ($detfac_grid->Recordset)
	$detfac_grid->Recordset->Close();
?>
<?php if ($detfac_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($detfac_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($detfac_grid->TotalRecs == 0 && $detfac->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($detfac_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($detfac->Export == "") { ?>
<script type="text/javascript">
fdetfacgrid.Init();
</script>
<?php } ?>
<?php
$detfac_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$detfac_grid->Page_Terminate();
?>
