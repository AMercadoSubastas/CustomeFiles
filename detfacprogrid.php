<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($detfacpro_grid)) $detfacpro_grid = new cdetfacpro_grid();

// Page init
$detfacpro_grid->Page_Init();

// Page main
$detfacpro_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$detfacpro_grid->Page_Render();
?>
<?php if ($detfacpro->Export == "") { ?>
<script type="text/javascript">

// Page object
var detfacpro_grid = new ew_Page("detfacpro_grid");
detfacpro_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = detfacpro_grid.PageID; // For backward compatibility

// Form object
var fdetfacprogrid = new ew_Form("fdetfacprogrid");
fdetfacprogrid.FormKeyCountName = '<?php echo $detfacpro_grid->FormKeyCountName ?>';

// Validate form
fdetfacprogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_codnum");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $detfacpro->codnum->FldCaption(), $detfacpro->codnum->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_codnum");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->codnum->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tcomp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $detfacpro->tcomp->FldCaption(), $detfacpro->tcomp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tcomp");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->tcomp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_serie");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $detfacpro->serie->FldCaption(), $detfacpro->serie->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_serie");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->serie->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ncomp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $detfacpro->ncomp->FldCaption(), $detfacpro->ncomp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ncomp");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->ncomp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nreng");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $detfacpro->nreng->FldCaption(), $detfacpro->nreng->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nreng");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->nreng->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_codrem");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->codrem->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_codlote");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->codlote->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_neto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->neto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bruto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->bruto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_iva");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->iva->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_imp");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->imp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_comcob");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->comcob->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_compag");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->compag->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_porciva");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->porciva->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tieneresol");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->tieneresol->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tcomsal");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->tcomsal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_seriesal");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->seriesal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ncompsal");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfacpro->ncompsal->FldErrMsg()) ?>");

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
fdetfacprogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "codnum", false)) return false;
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
	if (ew_ValueChanged(fobj, infix, "porciva", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tieneresol", false)) return false;
	if (ew_ValueChanged(fobj, infix, "concafac", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tcomsal", false)) return false;
	if (ew_ValueChanged(fobj, infix, "seriesal", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ncompsal", false)) return false;
	return true;
}

// Form_CustomValidate event
fdetfacprogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdetfacprogrid.ValidateRequired = true;
<?php } else { ?>
fdetfacprogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdetfacprogrid.Lists["x_concafac"] = {"LinkField":"x_codnum","Ajax":true,"AutoFill":false,"DisplayFields":["x_descrip","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($detfacpro->CurrentAction == "gridadd") {
	if ($detfacpro->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$detfacpro_grid->TotalRecs = $detfacpro->SelectRecordCount();
			$detfacpro_grid->Recordset = $detfacpro_grid->LoadRecordset($detfacpro_grid->StartRec-1, $detfacpro_grid->DisplayRecs);
		} else {
			if ($detfacpro_grid->Recordset = $detfacpro_grid->LoadRecordset())
				$detfacpro_grid->TotalRecs = $detfacpro_grid->Recordset->RecordCount();
		}
		$detfacpro_grid->StartRec = 1;
		$detfacpro_grid->DisplayRecs = $detfacpro_grid->TotalRecs;
	} else {
		$detfacpro->CurrentFilter = "0=1";
		$detfacpro_grid->StartRec = 1;
		$detfacpro_grid->DisplayRecs = $detfacpro->GridAddRowCount;
	}
	$detfacpro_grid->TotalRecs = $detfacpro_grid->DisplayRecs;
	$detfacpro_grid->StopRec = $detfacpro_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$detfacpro_grid->TotalRecs = $detfacpro->SelectRecordCount();
	} else {
		if ($detfacpro_grid->Recordset = $detfacpro_grid->LoadRecordset())
			$detfacpro_grid->TotalRecs = $detfacpro_grid->Recordset->RecordCount();
	}
	$detfacpro_grid->StartRec = 1;
	$detfacpro_grid->DisplayRecs = $detfacpro_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$detfacpro_grid->Recordset = $detfacpro_grid->LoadRecordset($detfacpro_grid->StartRec-1, $detfacpro_grid->DisplayRecs);

	// Set no record found message
	if ($detfacpro->CurrentAction == "" && $detfacpro_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$detfacpro_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($detfacpro_grid->SearchWhere == "0=101")
			$detfacpro_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$detfacpro_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$detfacpro_grid->RenderOtherOptions();
?>
<?php $detfacpro_grid->ShowPageHeader(); ?>
<?php
$detfacpro_grid->ShowMessage();
?>
<?php if ($detfacpro_grid->TotalRecs > 0 || $detfacpro->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fdetfacprogrid" class="ewForm form-inline">
<div id="gmp_detfacpro" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_detfacprogrid" class="table ewTable">
<?php echo $detfacpro->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$detfacpro_grid->RenderListOptions();

// Render list options (header, left)
$detfacpro_grid->ListOptions->Render("header", "left");
?>
<?php if ($detfacpro->codnum->Visible) { // codnum ?>
	<?php if ($detfacpro->SortUrl($detfacpro->codnum) == "") { ?>
		<th data-name="codnum"><div id="elh_detfacpro_codnum" class="detfacpro_codnum"><div class="ewTableHeaderCaption"><?php echo $detfacpro->codnum->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codnum"><div><div id="elh_detfacpro_codnum" class="detfacpro_codnum">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->codnum->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->codnum->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->codnum->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->tcomp->Visible) { // tcomp ?>
	<?php if ($detfacpro->SortUrl($detfacpro->tcomp) == "") { ?>
		<th data-name="tcomp"><div id="elh_detfacpro_tcomp" class="detfacpro_tcomp"><div class="ewTableHeaderCaption"><?php echo $detfacpro->tcomp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tcomp"><div><div id="elh_detfacpro_tcomp" class="detfacpro_tcomp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->tcomp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->tcomp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->tcomp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->serie->Visible) { // serie ?>
	<?php if ($detfacpro->SortUrl($detfacpro->serie) == "") { ?>
		<th data-name="serie"><div id="elh_detfacpro_serie" class="detfacpro_serie"><div class="ewTableHeaderCaption"><?php echo $detfacpro->serie->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="serie"><div><div id="elh_detfacpro_serie" class="detfacpro_serie">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->serie->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->serie->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->serie->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->ncomp->Visible) { // ncomp ?>
	<?php if ($detfacpro->SortUrl($detfacpro->ncomp) == "") { ?>
		<th data-name="ncomp"><div id="elh_detfacpro_ncomp" class="detfacpro_ncomp"><div class="ewTableHeaderCaption"><?php echo $detfacpro->ncomp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ncomp"><div><div id="elh_detfacpro_ncomp" class="detfacpro_ncomp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->ncomp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->ncomp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->ncomp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->nreng->Visible) { // nreng ?>
	<?php if ($detfacpro->SortUrl($detfacpro->nreng) == "") { ?>
		<th data-name="nreng"><div id="elh_detfacpro_nreng" class="detfacpro_nreng"><div class="ewTableHeaderCaption"><?php echo $detfacpro->nreng->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nreng"><div><div id="elh_detfacpro_nreng" class="detfacpro_nreng">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->nreng->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->nreng->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->nreng->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->codrem->Visible) { // codrem ?>
	<?php if ($detfacpro->SortUrl($detfacpro->codrem) == "") { ?>
		<th data-name="codrem"><div id="elh_detfacpro_codrem" class="detfacpro_codrem"><div class="ewTableHeaderCaption"><?php echo $detfacpro->codrem->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codrem"><div><div id="elh_detfacpro_codrem" class="detfacpro_codrem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->codrem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->codrem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->codrem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->codlote->Visible) { // codlote ?>
	<?php if ($detfacpro->SortUrl($detfacpro->codlote) == "") { ?>
		<th data-name="codlote"><div id="elh_detfacpro_codlote" class="detfacpro_codlote"><div class="ewTableHeaderCaption"><?php echo $detfacpro->codlote->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codlote"><div><div id="elh_detfacpro_codlote" class="detfacpro_codlote">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->codlote->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->codlote->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->codlote->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->descrip->Visible) { // descrip ?>
	<?php if ($detfacpro->SortUrl($detfacpro->descrip) == "") { ?>
		<th data-name="descrip"><div id="elh_detfacpro_descrip" class="detfacpro_descrip"><div class="ewTableHeaderCaption"><?php echo $detfacpro->descrip->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descrip"><div><div id="elh_detfacpro_descrip" class="detfacpro_descrip">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->descrip->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->descrip->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->descrip->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->neto->Visible) { // neto ?>
	<?php if ($detfacpro->SortUrl($detfacpro->neto) == "") { ?>
		<th data-name="neto"><div id="elh_detfacpro_neto" class="detfacpro_neto"><div class="ewTableHeaderCaption"><?php echo $detfacpro->neto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="neto"><div><div id="elh_detfacpro_neto" class="detfacpro_neto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->neto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->neto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->neto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->bruto->Visible) { // bruto ?>
	<?php if ($detfacpro->SortUrl($detfacpro->bruto) == "") { ?>
		<th data-name="bruto"><div id="elh_detfacpro_bruto" class="detfacpro_bruto"><div class="ewTableHeaderCaption"><?php echo $detfacpro->bruto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bruto"><div><div id="elh_detfacpro_bruto" class="detfacpro_bruto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->bruto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->bruto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->bruto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->iva->Visible) { // iva ?>
	<?php if ($detfacpro->SortUrl($detfacpro->iva) == "") { ?>
		<th data-name="iva"><div id="elh_detfacpro_iva" class="detfacpro_iva"><div class="ewTableHeaderCaption"><?php echo $detfacpro->iva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="iva"><div><div id="elh_detfacpro_iva" class="detfacpro_iva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->iva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->iva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->iva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->imp->Visible) { // imp ?>
	<?php if ($detfacpro->SortUrl($detfacpro->imp) == "") { ?>
		<th data-name="imp"><div id="elh_detfacpro_imp" class="detfacpro_imp"><div class="ewTableHeaderCaption"><?php echo $detfacpro->imp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="imp"><div><div id="elh_detfacpro_imp" class="detfacpro_imp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->imp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->imp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->imp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->comcob->Visible) { // comcob ?>
	<?php if ($detfacpro->SortUrl($detfacpro->comcob) == "") { ?>
		<th data-name="comcob"><div id="elh_detfacpro_comcob" class="detfacpro_comcob"><div class="ewTableHeaderCaption"><?php echo $detfacpro->comcob->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="comcob"><div><div id="elh_detfacpro_comcob" class="detfacpro_comcob">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->comcob->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->comcob->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->comcob->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->compag->Visible) { // compag ?>
	<?php if ($detfacpro->SortUrl($detfacpro->compag) == "") { ?>
		<th data-name="compag"><div id="elh_detfacpro_compag" class="detfacpro_compag"><div class="ewTableHeaderCaption"><?php echo $detfacpro->compag->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="compag"><div><div id="elh_detfacpro_compag" class="detfacpro_compag">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->compag->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->compag->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->compag->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->fechahora->Visible) { // fechahora ?>
	<?php if ($detfacpro->SortUrl($detfacpro->fechahora) == "") { ?>
		<th data-name="fechahora"><div id="elh_detfacpro_fechahora" class="detfacpro_fechahora"><div class="ewTableHeaderCaption"><?php echo $detfacpro->fechahora->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechahora"><div><div id="elh_detfacpro_fechahora" class="detfacpro_fechahora">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->fechahora->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->fechahora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->fechahora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->usuario->Visible) { // usuario ?>
	<?php if ($detfacpro->SortUrl($detfacpro->usuario) == "") { ?>
		<th data-name="usuario"><div id="elh_detfacpro_usuario" class="detfacpro_usuario"><div class="ewTableHeaderCaption"><?php echo $detfacpro->usuario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="usuario"><div><div id="elh_detfacpro_usuario" class="detfacpro_usuario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->usuario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->usuario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->usuario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->porciva->Visible) { // porciva ?>
	<?php if ($detfacpro->SortUrl($detfacpro->porciva) == "") { ?>
		<th data-name="porciva"><div id="elh_detfacpro_porciva" class="detfacpro_porciva"><div class="ewTableHeaderCaption"><?php echo $detfacpro->porciva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="porciva"><div><div id="elh_detfacpro_porciva" class="detfacpro_porciva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->porciva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->porciva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->porciva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->tieneresol->Visible) { // tieneresol ?>
	<?php if ($detfacpro->SortUrl($detfacpro->tieneresol) == "") { ?>
		<th data-name="tieneresol"><div id="elh_detfacpro_tieneresol" class="detfacpro_tieneresol"><div class="ewTableHeaderCaption"><?php echo $detfacpro->tieneresol->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tieneresol"><div><div id="elh_detfacpro_tieneresol" class="detfacpro_tieneresol">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->tieneresol->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->tieneresol->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->tieneresol->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->concafac->Visible) { // concafac ?>
	<?php if ($detfacpro->SortUrl($detfacpro->concafac) == "") { ?>
		<th data-name="concafac"><div id="elh_detfacpro_concafac" class="detfacpro_concafac"><div class="ewTableHeaderCaption"><?php echo $detfacpro->concafac->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="concafac"><div><div id="elh_detfacpro_concafac" class="detfacpro_concafac">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->concafac->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->concafac->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->concafac->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->tcomsal->Visible) { // tcomsal ?>
	<?php if ($detfacpro->SortUrl($detfacpro->tcomsal) == "") { ?>
		<th data-name="tcomsal"><div id="elh_detfacpro_tcomsal" class="detfacpro_tcomsal"><div class="ewTableHeaderCaption"><?php echo $detfacpro->tcomsal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tcomsal"><div><div id="elh_detfacpro_tcomsal" class="detfacpro_tcomsal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->tcomsal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->tcomsal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->tcomsal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->seriesal->Visible) { // seriesal ?>
	<?php if ($detfacpro->SortUrl($detfacpro->seriesal) == "") { ?>
		<th data-name="seriesal"><div id="elh_detfacpro_seriesal" class="detfacpro_seriesal"><div class="ewTableHeaderCaption"><?php echo $detfacpro->seriesal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="seriesal"><div><div id="elh_detfacpro_seriesal" class="detfacpro_seriesal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->seriesal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->seriesal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->seriesal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfacpro->ncompsal->Visible) { // ncompsal ?>
	<?php if ($detfacpro->SortUrl($detfacpro->ncompsal) == "") { ?>
		<th data-name="ncompsal"><div id="elh_detfacpro_ncompsal" class="detfacpro_ncompsal"><div class="ewTableHeaderCaption"><?php echo $detfacpro->ncompsal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ncompsal"><div><div id="elh_detfacpro_ncompsal" class="detfacpro_ncompsal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfacpro->ncompsal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfacpro->ncompsal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfacpro->ncompsal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$detfacpro_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$detfacpro_grid->StartRec = 1;
$detfacpro_grid->StopRec = $detfacpro_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($detfacpro_grid->FormKeyCountName) && ($detfacpro->CurrentAction == "gridadd" || $detfacpro->CurrentAction == "gridedit" || $detfacpro->CurrentAction == "F")) {
		$detfacpro_grid->KeyCount = $objForm->GetValue($detfacpro_grid->FormKeyCountName);
		$detfacpro_grid->StopRec = $detfacpro_grid->StartRec + $detfacpro_grid->KeyCount - 1;
	}
}
$detfacpro_grid->RecCnt = $detfacpro_grid->StartRec - 1;
if ($detfacpro_grid->Recordset && !$detfacpro_grid->Recordset->EOF) {
	$detfacpro_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $detfacpro_grid->StartRec > 1)
		$detfacpro_grid->Recordset->Move($detfacpro_grid->StartRec - 1);
} elseif (!$detfacpro->AllowAddDeleteRow && $detfacpro_grid->StopRec == 0) {
	$detfacpro_grid->StopRec = $detfacpro->GridAddRowCount;
}

// Initialize aggregate
$detfacpro->RowType = EW_ROWTYPE_AGGREGATEINIT;
$detfacpro->ResetAttrs();
$detfacpro_grid->RenderRow();
if ($detfacpro->CurrentAction == "gridadd")
	$detfacpro_grid->RowIndex = 0;
if ($detfacpro->CurrentAction == "gridedit")
	$detfacpro_grid->RowIndex = 0;
while ($detfacpro_grid->RecCnt < $detfacpro_grid->StopRec) {
	$detfacpro_grid->RecCnt++;
	if (intval($detfacpro_grid->RecCnt) >= intval($detfacpro_grid->StartRec)) {
		$detfacpro_grid->RowCnt++;
		if ($detfacpro->CurrentAction == "gridadd" || $detfacpro->CurrentAction == "gridedit" || $detfacpro->CurrentAction == "F") {
			$detfacpro_grid->RowIndex++;
			$objForm->Index = $detfacpro_grid->RowIndex;
			if ($objForm->HasValue($detfacpro_grid->FormActionName))
				$detfacpro_grid->RowAction = strval($objForm->GetValue($detfacpro_grid->FormActionName));
			elseif ($detfacpro->CurrentAction == "gridadd")
				$detfacpro_grid->RowAction = "insert";
			else
				$detfacpro_grid->RowAction = "";
		}

		// Set up key count
		$detfacpro_grid->KeyCount = $detfacpro_grid->RowIndex;

		// Init row class and style
		$detfacpro->ResetAttrs();
		$detfacpro->CssClass = "";
		if ($detfacpro->CurrentAction == "gridadd") {
			if ($detfacpro->CurrentMode == "copy") {
				$detfacpro_grid->LoadRowValues($detfacpro_grid->Recordset); // Load row values
				$detfacpro_grid->SetRecordKey($detfacpro_grid->RowOldKey, $detfacpro_grid->Recordset); // Set old record key
			} else {
				$detfacpro_grid->LoadDefaultValues(); // Load default values
				$detfacpro_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$detfacpro_grid->LoadRowValues($detfacpro_grid->Recordset); // Load row values
		}
		$detfacpro->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($detfacpro->CurrentAction == "gridadd") // Grid add
			$detfacpro->RowType = EW_ROWTYPE_ADD; // Render add
		if ($detfacpro->CurrentAction == "gridadd" && $detfacpro->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$detfacpro_grid->RestoreCurrentRowFormValues($detfacpro_grid->RowIndex); // Restore form values
		if ($detfacpro->CurrentAction == "gridedit") { // Grid edit
			if ($detfacpro->EventCancelled) {
				$detfacpro_grid->RestoreCurrentRowFormValues($detfacpro_grid->RowIndex); // Restore form values
			}
			if ($detfacpro_grid->RowAction == "insert")
				$detfacpro->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$detfacpro->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($detfacpro->CurrentAction == "gridedit" && ($detfacpro->RowType == EW_ROWTYPE_EDIT || $detfacpro->RowType == EW_ROWTYPE_ADD) && $detfacpro->EventCancelled) // Update failed
			$detfacpro_grid->RestoreCurrentRowFormValues($detfacpro_grid->RowIndex); // Restore form values
		if ($detfacpro->RowType == EW_ROWTYPE_EDIT) // Edit row
			$detfacpro_grid->EditRowCnt++;
		if ($detfacpro->CurrentAction == "F") // Confirm row
			$detfacpro_grid->RestoreCurrentRowFormValues($detfacpro_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$detfacpro->RowAttrs = array_merge($detfacpro->RowAttrs, array('data-rowindex'=>$detfacpro_grid->RowCnt, 'id'=>'r' . $detfacpro_grid->RowCnt . '_detfacpro', 'data-rowtype'=>$detfacpro->RowType));

		// Render row
		$detfacpro_grid->RenderRow();

		// Render list options
		$detfacpro_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($detfacpro_grid->RowAction <> "delete" && $detfacpro_grid->RowAction <> "insertdelete" && !($detfacpro_grid->RowAction == "insert" && $detfacpro->CurrentAction == "F" && $detfacpro_grid->EmptyRow())) {
?>
	<tr<?php echo $detfacpro->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detfacpro_grid->ListOptions->Render("body", "left", $detfacpro_grid->RowCnt);
?>
	<?php if ($detfacpro->codnum->Visible) { // codnum ?>
		<td data-name="codnum"<?php echo $detfacpro->codnum->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_codnum" class="form-group detfacpro_codnum">
<input type="text" data-field="x_codnum" name="x<?php echo $detfacpro_grid->RowIndex ?>_codnum" id="x<?php echo $detfacpro_grid->RowIndex ?>_codnum" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->codnum->PlaceHolder) ?>" value="<?php echo $detfacpro->codnum->EditValue ?>"<?php echo $detfacpro->codnum->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_codnum" name="o<?php echo $detfacpro_grid->RowIndex ?>_codnum" id="o<?php echo $detfacpro_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($detfacpro->codnum->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_codnum" class="form-group detfacpro_codnum">
<input type="text" data-field="x_codnum" name="x<?php echo $detfacpro_grid->RowIndex ?>_codnum" id="x<?php echo $detfacpro_grid->RowIndex ?>_codnum" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->codnum->PlaceHolder) ?>" value="<?php echo $detfacpro->codnum->EditValue ?>"<?php echo $detfacpro->codnum->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->codnum->ViewAttributes() ?>>
<?php echo $detfacpro->codnum->ListViewValue() ?></span>
<input type="hidden" data-field="x_codnum" name="x<?php echo $detfacpro_grid->RowIndex ?>_codnum" id="x<?php echo $detfacpro_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($detfacpro->codnum->FormValue) ?>">
<input type="hidden" data-field="x_codnum" name="o<?php echo $detfacpro_grid->RowIndex ?>_codnum" id="o<?php echo $detfacpro_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($detfacpro->codnum->OldValue) ?>">
<?php } ?>
<a id="<?php echo $detfacpro_grid->PageObjName . "_row_" . $detfacpro_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($detfacpro->tcomp->Visible) { // tcomp ?>
		<td data-name="tcomp"<?php echo $detfacpro->tcomp->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($detfacpro->tcomp->getSessionValue() <> "") { ?>
<span<?php echo $detfacpro->tcomp->ViewAttributes() ?>>
<?php echo $detfacpro->tcomp->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $detfacpro_grid->RowIndex ?>_tcomp" name="x<?php echo $detfacpro_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($detfacpro->tcomp->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_tcomp" name="x<?php echo $detfacpro_grid->RowIndex ?>_tcomp" id="x<?php echo $detfacpro_grid->RowIndex ?>_tcomp" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->tcomp->PlaceHolder) ?>" value="<?php echo $detfacpro->tcomp->EditValue ?>"<?php echo $detfacpro->tcomp->EditAttributes() ?>>
<?php } ?>
<input type="hidden" data-field="x_tcomp" name="o<?php echo $detfacpro_grid->RowIndex ?>_tcomp" id="o<?php echo $detfacpro_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($detfacpro->tcomp->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($detfacpro->tcomp->getSessionValue() <> "") { ?>
<span<?php echo $detfacpro->tcomp->ViewAttributes() ?>>
<?php echo $detfacpro->tcomp->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $detfacpro_grid->RowIndex ?>_tcomp" name="x<?php echo $detfacpro_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($detfacpro->tcomp->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_tcomp" name="x<?php echo $detfacpro_grid->RowIndex ?>_tcomp" id="x<?php echo $detfacpro_grid->RowIndex ?>_tcomp" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->tcomp->PlaceHolder) ?>" value="<?php echo $detfacpro->tcomp->EditValue ?>"<?php echo $detfacpro->tcomp->EditAttributes() ?>>
<?php } ?>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->tcomp->ViewAttributes() ?>>
<?php echo $detfacpro->tcomp->ListViewValue() ?></span>
<input type="hidden" data-field="x_tcomp" name="x<?php echo $detfacpro_grid->RowIndex ?>_tcomp" id="x<?php echo $detfacpro_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($detfacpro->tcomp->FormValue) ?>">
<input type="hidden" data-field="x_tcomp" name="o<?php echo $detfacpro_grid->RowIndex ?>_tcomp" id="o<?php echo $detfacpro_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($detfacpro->tcomp->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->serie->Visible) { // serie ?>
		<td data-name="serie"<?php echo $detfacpro->serie->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($detfacpro->serie->getSessionValue() <> "") { ?>
<span<?php echo $detfacpro->serie->ViewAttributes() ?>>
<?php echo $detfacpro->serie->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $detfacpro_grid->RowIndex ?>_serie" name="x<?php echo $detfacpro_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($detfacpro->serie->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_serie" name="x<?php echo $detfacpro_grid->RowIndex ?>_serie" id="x<?php echo $detfacpro_grid->RowIndex ?>_serie" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->serie->PlaceHolder) ?>" value="<?php echo $detfacpro->serie->EditValue ?>"<?php echo $detfacpro->serie->EditAttributes() ?>>
<?php } ?>
<input type="hidden" data-field="x_serie" name="o<?php echo $detfacpro_grid->RowIndex ?>_serie" id="o<?php echo $detfacpro_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($detfacpro->serie->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($detfacpro->serie->getSessionValue() <> "") { ?>
<span<?php echo $detfacpro->serie->ViewAttributes() ?>>
<?php echo $detfacpro->serie->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $detfacpro_grid->RowIndex ?>_serie" name="x<?php echo $detfacpro_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($detfacpro->serie->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_serie" name="x<?php echo $detfacpro_grid->RowIndex ?>_serie" id="x<?php echo $detfacpro_grid->RowIndex ?>_serie" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->serie->PlaceHolder) ?>" value="<?php echo $detfacpro->serie->EditValue ?>"<?php echo $detfacpro->serie->EditAttributes() ?>>
<?php } ?>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->serie->ViewAttributes() ?>>
<?php echo $detfacpro->serie->ListViewValue() ?></span>
<input type="hidden" data-field="x_serie" name="x<?php echo $detfacpro_grid->RowIndex ?>_serie" id="x<?php echo $detfacpro_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($detfacpro->serie->FormValue) ?>">
<input type="hidden" data-field="x_serie" name="o<?php echo $detfacpro_grid->RowIndex ?>_serie" id="o<?php echo $detfacpro_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($detfacpro->serie->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->ncomp->Visible) { // ncomp ?>
		<td data-name="ncomp"<?php echo $detfacpro->ncomp->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($detfacpro->ncomp->getSessionValue() <> "") { ?>
<span<?php echo $detfacpro->ncomp->ViewAttributes() ?>>
<?php echo $detfacpro->ncomp->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $detfacpro_grid->RowIndex ?>_ncomp" name="x<?php echo $detfacpro_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($detfacpro->ncomp->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_ncomp" name="x<?php echo $detfacpro_grid->RowIndex ?>_ncomp" id="x<?php echo $detfacpro_grid->RowIndex ?>_ncomp" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->ncomp->PlaceHolder) ?>" value="<?php echo $detfacpro->ncomp->EditValue ?>"<?php echo $detfacpro->ncomp->EditAttributes() ?>>
<?php } ?>
<input type="hidden" data-field="x_ncomp" name="o<?php echo $detfacpro_grid->RowIndex ?>_ncomp" id="o<?php echo $detfacpro_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($detfacpro->ncomp->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($detfacpro->ncomp->getSessionValue() <> "") { ?>
<span<?php echo $detfacpro->ncomp->ViewAttributes() ?>>
<?php echo $detfacpro->ncomp->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $detfacpro_grid->RowIndex ?>_ncomp" name="x<?php echo $detfacpro_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($detfacpro->ncomp->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_ncomp" name="x<?php echo $detfacpro_grid->RowIndex ?>_ncomp" id="x<?php echo $detfacpro_grid->RowIndex ?>_ncomp" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->ncomp->PlaceHolder) ?>" value="<?php echo $detfacpro->ncomp->EditValue ?>"<?php echo $detfacpro->ncomp->EditAttributes() ?>>
<?php } ?>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->ncomp->ViewAttributes() ?>>
<?php echo $detfacpro->ncomp->ListViewValue() ?></span>
<input type="hidden" data-field="x_ncomp" name="x<?php echo $detfacpro_grid->RowIndex ?>_ncomp" id="x<?php echo $detfacpro_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($detfacpro->ncomp->FormValue) ?>">
<input type="hidden" data-field="x_ncomp" name="o<?php echo $detfacpro_grid->RowIndex ?>_ncomp" id="o<?php echo $detfacpro_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($detfacpro->ncomp->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->nreng->Visible) { // nreng ?>
		<td data-name="nreng"<?php echo $detfacpro->nreng->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_nreng" class="form-group detfacpro_nreng">
<input type="text" data-field="x_nreng" name="x<?php echo $detfacpro_grid->RowIndex ?>_nreng" id="x<?php echo $detfacpro_grid->RowIndex ?>_nreng" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->nreng->PlaceHolder) ?>" value="<?php echo $detfacpro->nreng->EditValue ?>"<?php echo $detfacpro->nreng->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nreng" name="o<?php echo $detfacpro_grid->RowIndex ?>_nreng" id="o<?php echo $detfacpro_grid->RowIndex ?>_nreng" value="<?php echo ew_HtmlEncode($detfacpro->nreng->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_nreng" class="form-group detfacpro_nreng">
<input type="text" data-field="x_nreng" name="x<?php echo $detfacpro_grid->RowIndex ?>_nreng" id="x<?php echo $detfacpro_grid->RowIndex ?>_nreng" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->nreng->PlaceHolder) ?>" value="<?php echo $detfacpro->nreng->EditValue ?>"<?php echo $detfacpro->nreng->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->nreng->ViewAttributes() ?>>
<?php echo $detfacpro->nreng->ListViewValue() ?></span>
<input type="hidden" data-field="x_nreng" name="x<?php echo $detfacpro_grid->RowIndex ?>_nreng" id="x<?php echo $detfacpro_grid->RowIndex ?>_nreng" value="<?php echo ew_HtmlEncode($detfacpro->nreng->FormValue) ?>">
<input type="hidden" data-field="x_nreng" name="o<?php echo $detfacpro_grid->RowIndex ?>_nreng" id="o<?php echo $detfacpro_grid->RowIndex ?>_nreng" value="<?php echo ew_HtmlEncode($detfacpro->nreng->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->codrem->Visible) { // codrem ?>
		<td data-name="codrem"<?php echo $detfacpro->codrem->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_codrem" class="form-group detfacpro_codrem">
<input type="text" data-field="x_codrem" name="x<?php echo $detfacpro_grid->RowIndex ?>_codrem" id="x<?php echo $detfacpro_grid->RowIndex ?>_codrem" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->codrem->PlaceHolder) ?>" value="<?php echo $detfacpro->codrem->EditValue ?>"<?php echo $detfacpro->codrem->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_codrem" name="o<?php echo $detfacpro_grid->RowIndex ?>_codrem" id="o<?php echo $detfacpro_grid->RowIndex ?>_codrem" value="<?php echo ew_HtmlEncode($detfacpro->codrem->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_codrem" class="form-group detfacpro_codrem">
<input type="text" data-field="x_codrem" name="x<?php echo $detfacpro_grid->RowIndex ?>_codrem" id="x<?php echo $detfacpro_grid->RowIndex ?>_codrem" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->codrem->PlaceHolder) ?>" value="<?php echo $detfacpro->codrem->EditValue ?>"<?php echo $detfacpro->codrem->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->codrem->ViewAttributes() ?>>
<?php echo $detfacpro->codrem->ListViewValue() ?></span>
<input type="hidden" data-field="x_codrem" name="x<?php echo $detfacpro_grid->RowIndex ?>_codrem" id="x<?php echo $detfacpro_grid->RowIndex ?>_codrem" value="<?php echo ew_HtmlEncode($detfacpro->codrem->FormValue) ?>">
<input type="hidden" data-field="x_codrem" name="o<?php echo $detfacpro_grid->RowIndex ?>_codrem" id="o<?php echo $detfacpro_grid->RowIndex ?>_codrem" value="<?php echo ew_HtmlEncode($detfacpro->codrem->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->codlote->Visible) { // codlote ?>
		<td data-name="codlote"<?php echo $detfacpro->codlote->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_codlote" class="form-group detfacpro_codlote">
<input type="text" data-field="x_codlote" name="x<?php echo $detfacpro_grid->RowIndex ?>_codlote" id="x<?php echo $detfacpro_grid->RowIndex ?>_codlote" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->codlote->PlaceHolder) ?>" value="<?php echo $detfacpro->codlote->EditValue ?>"<?php echo $detfacpro->codlote->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_codlote" name="o<?php echo $detfacpro_grid->RowIndex ?>_codlote" id="o<?php echo $detfacpro_grid->RowIndex ?>_codlote" value="<?php echo ew_HtmlEncode($detfacpro->codlote->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_codlote" class="form-group detfacpro_codlote">
<input type="text" data-field="x_codlote" name="x<?php echo $detfacpro_grid->RowIndex ?>_codlote" id="x<?php echo $detfacpro_grid->RowIndex ?>_codlote" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->codlote->PlaceHolder) ?>" value="<?php echo $detfacpro->codlote->EditValue ?>"<?php echo $detfacpro->codlote->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->codlote->ViewAttributes() ?>>
<?php echo $detfacpro->codlote->ListViewValue() ?></span>
<input type="hidden" data-field="x_codlote" name="x<?php echo $detfacpro_grid->RowIndex ?>_codlote" id="x<?php echo $detfacpro_grid->RowIndex ?>_codlote" value="<?php echo ew_HtmlEncode($detfacpro->codlote->FormValue) ?>">
<input type="hidden" data-field="x_codlote" name="o<?php echo $detfacpro_grid->RowIndex ?>_codlote" id="o<?php echo $detfacpro_grid->RowIndex ?>_codlote" value="<?php echo ew_HtmlEncode($detfacpro->codlote->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->descrip->Visible) { // descrip ?>
		<td data-name="descrip"<?php echo $detfacpro->descrip->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_descrip" class="form-group detfacpro_descrip">
<input type="text" data-field="x_descrip" name="x<?php echo $detfacpro_grid->RowIndex ?>_descrip" id="x<?php echo $detfacpro_grid->RowIndex ?>_descrip" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($detfacpro->descrip->PlaceHolder) ?>" value="<?php echo $detfacpro->descrip->EditValue ?>"<?php echo $detfacpro->descrip->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_descrip" name="o<?php echo $detfacpro_grid->RowIndex ?>_descrip" id="o<?php echo $detfacpro_grid->RowIndex ?>_descrip" value="<?php echo ew_HtmlEncode($detfacpro->descrip->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_descrip" class="form-group detfacpro_descrip">
<input type="text" data-field="x_descrip" name="x<?php echo $detfacpro_grid->RowIndex ?>_descrip" id="x<?php echo $detfacpro_grid->RowIndex ?>_descrip" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($detfacpro->descrip->PlaceHolder) ?>" value="<?php echo $detfacpro->descrip->EditValue ?>"<?php echo $detfacpro->descrip->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->descrip->ViewAttributes() ?>>
<?php echo $detfacpro->descrip->ListViewValue() ?></span>
<input type="hidden" data-field="x_descrip" name="x<?php echo $detfacpro_grid->RowIndex ?>_descrip" id="x<?php echo $detfacpro_grid->RowIndex ?>_descrip" value="<?php echo ew_HtmlEncode($detfacpro->descrip->FormValue) ?>">
<input type="hidden" data-field="x_descrip" name="o<?php echo $detfacpro_grid->RowIndex ?>_descrip" id="o<?php echo $detfacpro_grid->RowIndex ?>_descrip" value="<?php echo ew_HtmlEncode($detfacpro->descrip->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->neto->Visible) { // neto ?>
		<td data-name="neto"<?php echo $detfacpro->neto->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_neto" class="form-group detfacpro_neto">
<input type="text" data-field="x_neto" name="x<?php echo $detfacpro_grid->RowIndex ?>_neto" id="x<?php echo $detfacpro_grid->RowIndex ?>_neto" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->neto->PlaceHolder) ?>" value="<?php echo $detfacpro->neto->EditValue ?>"<?php echo $detfacpro->neto->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_neto" name="o<?php echo $detfacpro_grid->RowIndex ?>_neto" id="o<?php echo $detfacpro_grid->RowIndex ?>_neto" value="<?php echo ew_HtmlEncode($detfacpro->neto->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_neto" class="form-group detfacpro_neto">
<input type="text" data-field="x_neto" name="x<?php echo $detfacpro_grid->RowIndex ?>_neto" id="x<?php echo $detfacpro_grid->RowIndex ?>_neto" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->neto->PlaceHolder) ?>" value="<?php echo $detfacpro->neto->EditValue ?>"<?php echo $detfacpro->neto->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->neto->ViewAttributes() ?>>
<?php echo $detfacpro->neto->ListViewValue() ?></span>
<input type="hidden" data-field="x_neto" name="x<?php echo $detfacpro_grid->RowIndex ?>_neto" id="x<?php echo $detfacpro_grid->RowIndex ?>_neto" value="<?php echo ew_HtmlEncode($detfacpro->neto->FormValue) ?>">
<input type="hidden" data-field="x_neto" name="o<?php echo $detfacpro_grid->RowIndex ?>_neto" id="o<?php echo $detfacpro_grid->RowIndex ?>_neto" value="<?php echo ew_HtmlEncode($detfacpro->neto->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->bruto->Visible) { // bruto ?>
		<td data-name="bruto"<?php echo $detfacpro->bruto->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_bruto" class="form-group detfacpro_bruto">
<input type="text" data-field="x_bruto" name="x<?php echo $detfacpro_grid->RowIndex ?>_bruto" id="x<?php echo $detfacpro_grid->RowIndex ?>_bruto" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->bruto->PlaceHolder) ?>" value="<?php echo $detfacpro->bruto->EditValue ?>"<?php echo $detfacpro->bruto->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_bruto" name="o<?php echo $detfacpro_grid->RowIndex ?>_bruto" id="o<?php echo $detfacpro_grid->RowIndex ?>_bruto" value="<?php echo ew_HtmlEncode($detfacpro->bruto->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_bruto" class="form-group detfacpro_bruto">
<input type="text" data-field="x_bruto" name="x<?php echo $detfacpro_grid->RowIndex ?>_bruto" id="x<?php echo $detfacpro_grid->RowIndex ?>_bruto" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->bruto->PlaceHolder) ?>" value="<?php echo $detfacpro->bruto->EditValue ?>"<?php echo $detfacpro->bruto->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->bruto->ViewAttributes() ?>>
<?php echo $detfacpro->bruto->ListViewValue() ?></span>
<input type="hidden" data-field="x_bruto" name="x<?php echo $detfacpro_grid->RowIndex ?>_bruto" id="x<?php echo $detfacpro_grid->RowIndex ?>_bruto" value="<?php echo ew_HtmlEncode($detfacpro->bruto->FormValue) ?>">
<input type="hidden" data-field="x_bruto" name="o<?php echo $detfacpro_grid->RowIndex ?>_bruto" id="o<?php echo $detfacpro_grid->RowIndex ?>_bruto" value="<?php echo ew_HtmlEncode($detfacpro->bruto->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->iva->Visible) { // iva ?>
		<td data-name="iva"<?php echo $detfacpro->iva->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_iva" class="form-group detfacpro_iva">
<input type="text" data-field="x_iva" name="x<?php echo $detfacpro_grid->RowIndex ?>_iva" id="x<?php echo $detfacpro_grid->RowIndex ?>_iva" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->iva->PlaceHolder) ?>" value="<?php echo $detfacpro->iva->EditValue ?>"<?php echo $detfacpro->iva->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_iva" name="o<?php echo $detfacpro_grid->RowIndex ?>_iva" id="o<?php echo $detfacpro_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($detfacpro->iva->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_iva" class="form-group detfacpro_iva">
<input type="text" data-field="x_iva" name="x<?php echo $detfacpro_grid->RowIndex ?>_iva" id="x<?php echo $detfacpro_grid->RowIndex ?>_iva" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->iva->PlaceHolder) ?>" value="<?php echo $detfacpro->iva->EditValue ?>"<?php echo $detfacpro->iva->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->iva->ViewAttributes() ?>>
<?php echo $detfacpro->iva->ListViewValue() ?></span>
<input type="hidden" data-field="x_iva" name="x<?php echo $detfacpro_grid->RowIndex ?>_iva" id="x<?php echo $detfacpro_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($detfacpro->iva->FormValue) ?>">
<input type="hidden" data-field="x_iva" name="o<?php echo $detfacpro_grid->RowIndex ?>_iva" id="o<?php echo $detfacpro_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($detfacpro->iva->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->imp->Visible) { // imp ?>
		<td data-name="imp"<?php echo $detfacpro->imp->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_imp" class="form-group detfacpro_imp">
<input type="text" data-field="x_imp" name="x<?php echo $detfacpro_grid->RowIndex ?>_imp" id="x<?php echo $detfacpro_grid->RowIndex ?>_imp" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->imp->PlaceHolder) ?>" value="<?php echo $detfacpro->imp->EditValue ?>"<?php echo $detfacpro->imp->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_imp" name="o<?php echo $detfacpro_grid->RowIndex ?>_imp" id="o<?php echo $detfacpro_grid->RowIndex ?>_imp" value="<?php echo ew_HtmlEncode($detfacpro->imp->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_imp" class="form-group detfacpro_imp">
<input type="text" data-field="x_imp" name="x<?php echo $detfacpro_grid->RowIndex ?>_imp" id="x<?php echo $detfacpro_grid->RowIndex ?>_imp" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->imp->PlaceHolder) ?>" value="<?php echo $detfacpro->imp->EditValue ?>"<?php echo $detfacpro->imp->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->imp->ViewAttributes() ?>>
<?php echo $detfacpro->imp->ListViewValue() ?></span>
<input type="hidden" data-field="x_imp" name="x<?php echo $detfacpro_grid->RowIndex ?>_imp" id="x<?php echo $detfacpro_grid->RowIndex ?>_imp" value="<?php echo ew_HtmlEncode($detfacpro->imp->FormValue) ?>">
<input type="hidden" data-field="x_imp" name="o<?php echo $detfacpro_grid->RowIndex ?>_imp" id="o<?php echo $detfacpro_grid->RowIndex ?>_imp" value="<?php echo ew_HtmlEncode($detfacpro->imp->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->comcob->Visible) { // comcob ?>
		<td data-name="comcob"<?php echo $detfacpro->comcob->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_comcob" class="form-group detfacpro_comcob">
<input type="text" data-field="x_comcob" name="x<?php echo $detfacpro_grid->RowIndex ?>_comcob" id="x<?php echo $detfacpro_grid->RowIndex ?>_comcob" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->comcob->PlaceHolder) ?>" value="<?php echo $detfacpro->comcob->EditValue ?>"<?php echo $detfacpro->comcob->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_comcob" name="o<?php echo $detfacpro_grid->RowIndex ?>_comcob" id="o<?php echo $detfacpro_grid->RowIndex ?>_comcob" value="<?php echo ew_HtmlEncode($detfacpro->comcob->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_comcob" class="form-group detfacpro_comcob">
<input type="text" data-field="x_comcob" name="x<?php echo $detfacpro_grid->RowIndex ?>_comcob" id="x<?php echo $detfacpro_grid->RowIndex ?>_comcob" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->comcob->PlaceHolder) ?>" value="<?php echo $detfacpro->comcob->EditValue ?>"<?php echo $detfacpro->comcob->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->comcob->ViewAttributes() ?>>
<?php echo $detfacpro->comcob->ListViewValue() ?></span>
<input type="hidden" data-field="x_comcob" name="x<?php echo $detfacpro_grid->RowIndex ?>_comcob" id="x<?php echo $detfacpro_grid->RowIndex ?>_comcob" value="<?php echo ew_HtmlEncode($detfacpro->comcob->FormValue) ?>">
<input type="hidden" data-field="x_comcob" name="o<?php echo $detfacpro_grid->RowIndex ?>_comcob" id="o<?php echo $detfacpro_grid->RowIndex ?>_comcob" value="<?php echo ew_HtmlEncode($detfacpro->comcob->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->compag->Visible) { // compag ?>
		<td data-name="compag"<?php echo $detfacpro->compag->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_compag" class="form-group detfacpro_compag">
<input type="text" data-field="x_compag" name="x<?php echo $detfacpro_grid->RowIndex ?>_compag" id="x<?php echo $detfacpro_grid->RowIndex ?>_compag" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->compag->PlaceHolder) ?>" value="<?php echo $detfacpro->compag->EditValue ?>"<?php echo $detfacpro->compag->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_compag" name="o<?php echo $detfacpro_grid->RowIndex ?>_compag" id="o<?php echo $detfacpro_grid->RowIndex ?>_compag" value="<?php echo ew_HtmlEncode($detfacpro->compag->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_compag" class="form-group detfacpro_compag">
<input type="text" data-field="x_compag" name="x<?php echo $detfacpro_grid->RowIndex ?>_compag" id="x<?php echo $detfacpro_grid->RowIndex ?>_compag" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->compag->PlaceHolder) ?>" value="<?php echo $detfacpro->compag->EditValue ?>"<?php echo $detfacpro->compag->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->compag->ViewAttributes() ?>>
<?php echo $detfacpro->compag->ListViewValue() ?></span>
<input type="hidden" data-field="x_compag" name="x<?php echo $detfacpro_grid->RowIndex ?>_compag" id="x<?php echo $detfacpro_grid->RowIndex ?>_compag" value="<?php echo ew_HtmlEncode($detfacpro->compag->FormValue) ?>">
<input type="hidden" data-field="x_compag" name="o<?php echo $detfacpro_grid->RowIndex ?>_compag" id="o<?php echo $detfacpro_grid->RowIndex ?>_compag" value="<?php echo ew_HtmlEncode($detfacpro->compag->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->fechahora->Visible) { // fechahora ?>
		<td data-name="fechahora"<?php echo $detfacpro->fechahora->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_fechahora" name="o<?php echo $detfacpro_grid->RowIndex ?>_fechahora" id="o<?php echo $detfacpro_grid->RowIndex ?>_fechahora" value="<?php echo ew_HtmlEncode($detfacpro->fechahora->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->fechahora->ViewAttributes() ?>>
<?php echo $detfacpro->fechahora->ListViewValue() ?></span>
<input type="hidden" data-field="x_fechahora" name="x<?php echo $detfacpro_grid->RowIndex ?>_fechahora" id="x<?php echo $detfacpro_grid->RowIndex ?>_fechahora" value="<?php echo ew_HtmlEncode($detfacpro->fechahora->FormValue) ?>">
<input type="hidden" data-field="x_fechahora" name="o<?php echo $detfacpro_grid->RowIndex ?>_fechahora" id="o<?php echo $detfacpro_grid->RowIndex ?>_fechahora" value="<?php echo ew_HtmlEncode($detfacpro->fechahora->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->usuario->Visible) { // usuario ?>
		<td data-name="usuario"<?php echo $detfacpro->usuario->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_usuario" name="o<?php echo $detfacpro_grid->RowIndex ?>_usuario" id="o<?php echo $detfacpro_grid->RowIndex ?>_usuario" value="<?php echo ew_HtmlEncode($detfacpro->usuario->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->usuario->ViewAttributes() ?>>
<?php echo $detfacpro->usuario->ListViewValue() ?></span>
<input type="hidden" data-field="x_usuario" name="x<?php echo $detfacpro_grid->RowIndex ?>_usuario" id="x<?php echo $detfacpro_grid->RowIndex ?>_usuario" value="<?php echo ew_HtmlEncode($detfacpro->usuario->FormValue) ?>">
<input type="hidden" data-field="x_usuario" name="o<?php echo $detfacpro_grid->RowIndex ?>_usuario" id="o<?php echo $detfacpro_grid->RowIndex ?>_usuario" value="<?php echo ew_HtmlEncode($detfacpro->usuario->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->porciva->Visible) { // porciva ?>
		<td data-name="porciva"<?php echo $detfacpro->porciva->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_porciva" class="form-group detfacpro_porciva">
<input type="text" data-field="x_porciva" name="x<?php echo $detfacpro_grid->RowIndex ?>_porciva" id="x<?php echo $detfacpro_grid->RowIndex ?>_porciva" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->porciva->PlaceHolder) ?>" value="<?php echo $detfacpro->porciva->EditValue ?>"<?php echo $detfacpro->porciva->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_porciva" name="o<?php echo $detfacpro_grid->RowIndex ?>_porciva" id="o<?php echo $detfacpro_grid->RowIndex ?>_porciva" value="<?php echo ew_HtmlEncode($detfacpro->porciva->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_porciva" class="form-group detfacpro_porciva">
<input type="text" data-field="x_porciva" name="x<?php echo $detfacpro_grid->RowIndex ?>_porciva" id="x<?php echo $detfacpro_grid->RowIndex ?>_porciva" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->porciva->PlaceHolder) ?>" value="<?php echo $detfacpro->porciva->EditValue ?>"<?php echo $detfacpro->porciva->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->porciva->ViewAttributes() ?>>
<?php echo $detfacpro->porciva->ListViewValue() ?></span>
<input type="hidden" data-field="x_porciva" name="x<?php echo $detfacpro_grid->RowIndex ?>_porciva" id="x<?php echo $detfacpro_grid->RowIndex ?>_porciva" value="<?php echo ew_HtmlEncode($detfacpro->porciva->FormValue) ?>">
<input type="hidden" data-field="x_porciva" name="o<?php echo $detfacpro_grid->RowIndex ?>_porciva" id="o<?php echo $detfacpro_grid->RowIndex ?>_porciva" value="<?php echo ew_HtmlEncode($detfacpro->porciva->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->tieneresol->Visible) { // tieneresol ?>
		<td data-name="tieneresol"<?php echo $detfacpro->tieneresol->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_tieneresol" class="form-group detfacpro_tieneresol">
<input type="text" data-field="x_tieneresol" name="x<?php echo $detfacpro_grid->RowIndex ?>_tieneresol" id="x<?php echo $detfacpro_grid->RowIndex ?>_tieneresol" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->tieneresol->PlaceHolder) ?>" value="<?php echo $detfacpro->tieneresol->EditValue ?>"<?php echo $detfacpro->tieneresol->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_tieneresol" name="o<?php echo $detfacpro_grid->RowIndex ?>_tieneresol" id="o<?php echo $detfacpro_grid->RowIndex ?>_tieneresol" value="<?php echo ew_HtmlEncode($detfacpro->tieneresol->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_tieneresol" class="form-group detfacpro_tieneresol">
<input type="text" data-field="x_tieneresol" name="x<?php echo $detfacpro_grid->RowIndex ?>_tieneresol" id="x<?php echo $detfacpro_grid->RowIndex ?>_tieneresol" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->tieneresol->PlaceHolder) ?>" value="<?php echo $detfacpro->tieneresol->EditValue ?>"<?php echo $detfacpro->tieneresol->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->tieneresol->ViewAttributes() ?>>
<?php echo $detfacpro->tieneresol->ListViewValue() ?></span>
<input type="hidden" data-field="x_tieneresol" name="x<?php echo $detfacpro_grid->RowIndex ?>_tieneresol" id="x<?php echo $detfacpro_grid->RowIndex ?>_tieneresol" value="<?php echo ew_HtmlEncode($detfacpro->tieneresol->FormValue) ?>">
<input type="hidden" data-field="x_tieneresol" name="o<?php echo $detfacpro_grid->RowIndex ?>_tieneresol" id="o<?php echo $detfacpro_grid->RowIndex ?>_tieneresol" value="<?php echo ew_HtmlEncode($detfacpro->tieneresol->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->concafac->Visible) { // concafac ?>
		<td data-name="concafac"<?php echo $detfacpro->concafac->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_concafac" class="form-group detfacpro_concafac">
<select data-field="x_concafac" id="x<?php echo $detfacpro_grid->RowIndex ?>_concafac" name="x<?php echo $detfacpro_grid->RowIndex ?>_concafac"<?php echo $detfacpro->concafac->EditAttributes() ?>>
<?php
if (is_array($detfacpro->concafac->EditValue)) {
	$arwrk = $detfacpro->concafac->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($detfacpro->concafac->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $detfacpro->concafac->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `descrip` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `concafact`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $detfacpro->Lookup_Selecting($detfacpro->concafac, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `descrip` ASC";
?>
<input type="hidden" name="s_x<?php echo $detfacpro_grid->RowIndex ?>_concafac" id="s_x<?php echo $detfacpro_grid->RowIndex ?>_concafac" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_concafac" name="o<?php echo $detfacpro_grid->RowIndex ?>_concafac" id="o<?php echo $detfacpro_grid->RowIndex ?>_concafac" value="<?php echo ew_HtmlEncode($detfacpro->concafac->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_concafac" class="form-group detfacpro_concafac">
<select data-field="x_concafac" id="x<?php echo $detfacpro_grid->RowIndex ?>_concafac" name="x<?php echo $detfacpro_grid->RowIndex ?>_concafac"<?php echo $detfacpro->concafac->EditAttributes() ?>>
<?php
if (is_array($detfacpro->concafac->EditValue)) {
	$arwrk = $detfacpro->concafac->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($detfacpro->concafac->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $detfacpro->concafac->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `descrip` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `concafact`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $detfacpro->Lookup_Selecting($detfacpro->concafac, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `descrip` ASC";
?>
<input type="hidden" name="s_x<?php echo $detfacpro_grid->RowIndex ?>_concafac" id="s_x<?php echo $detfacpro_grid->RowIndex ?>_concafac" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->concafac->ViewAttributes() ?>>
<?php echo $detfacpro->concafac->ListViewValue() ?></span>
<input type="hidden" data-field="x_concafac" name="x<?php echo $detfacpro_grid->RowIndex ?>_concafac" id="x<?php echo $detfacpro_grid->RowIndex ?>_concafac" value="<?php echo ew_HtmlEncode($detfacpro->concafac->FormValue) ?>">
<input type="hidden" data-field="x_concafac" name="o<?php echo $detfacpro_grid->RowIndex ?>_concafac" id="o<?php echo $detfacpro_grid->RowIndex ?>_concafac" value="<?php echo ew_HtmlEncode($detfacpro->concafac->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->tcomsal->Visible) { // tcomsal ?>
		<td data-name="tcomsal"<?php echo $detfacpro->tcomsal->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_tcomsal" class="form-group detfacpro_tcomsal">
<input type="text" data-field="x_tcomsal" name="x<?php echo $detfacpro_grid->RowIndex ?>_tcomsal" id="x<?php echo $detfacpro_grid->RowIndex ?>_tcomsal" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->tcomsal->PlaceHolder) ?>" value="<?php echo $detfacpro->tcomsal->EditValue ?>"<?php echo $detfacpro->tcomsal->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_tcomsal" name="o<?php echo $detfacpro_grid->RowIndex ?>_tcomsal" id="o<?php echo $detfacpro_grid->RowIndex ?>_tcomsal" value="<?php echo ew_HtmlEncode($detfacpro->tcomsal->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_tcomsal" class="form-group detfacpro_tcomsal">
<input type="text" data-field="x_tcomsal" name="x<?php echo $detfacpro_grid->RowIndex ?>_tcomsal" id="x<?php echo $detfacpro_grid->RowIndex ?>_tcomsal" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->tcomsal->PlaceHolder) ?>" value="<?php echo $detfacpro->tcomsal->EditValue ?>"<?php echo $detfacpro->tcomsal->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->tcomsal->ViewAttributes() ?>>
<?php echo $detfacpro->tcomsal->ListViewValue() ?></span>
<input type="hidden" data-field="x_tcomsal" name="x<?php echo $detfacpro_grid->RowIndex ?>_tcomsal" id="x<?php echo $detfacpro_grid->RowIndex ?>_tcomsal" value="<?php echo ew_HtmlEncode($detfacpro->tcomsal->FormValue) ?>">
<input type="hidden" data-field="x_tcomsal" name="o<?php echo $detfacpro_grid->RowIndex ?>_tcomsal" id="o<?php echo $detfacpro_grid->RowIndex ?>_tcomsal" value="<?php echo ew_HtmlEncode($detfacpro->tcomsal->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->seriesal->Visible) { // seriesal ?>
		<td data-name="seriesal"<?php echo $detfacpro->seriesal->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_seriesal" class="form-group detfacpro_seriesal">
<input type="text" data-field="x_seriesal" name="x<?php echo $detfacpro_grid->RowIndex ?>_seriesal" id="x<?php echo $detfacpro_grid->RowIndex ?>_seriesal" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->seriesal->PlaceHolder) ?>" value="<?php echo $detfacpro->seriesal->EditValue ?>"<?php echo $detfacpro->seriesal->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_seriesal" name="o<?php echo $detfacpro_grid->RowIndex ?>_seriesal" id="o<?php echo $detfacpro_grid->RowIndex ?>_seriesal" value="<?php echo ew_HtmlEncode($detfacpro->seriesal->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_seriesal" class="form-group detfacpro_seriesal">
<input type="text" data-field="x_seriesal" name="x<?php echo $detfacpro_grid->RowIndex ?>_seriesal" id="x<?php echo $detfacpro_grid->RowIndex ?>_seriesal" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->seriesal->PlaceHolder) ?>" value="<?php echo $detfacpro->seriesal->EditValue ?>"<?php echo $detfacpro->seriesal->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->seriesal->ViewAttributes() ?>>
<?php echo $detfacpro->seriesal->ListViewValue() ?></span>
<input type="hidden" data-field="x_seriesal" name="x<?php echo $detfacpro_grid->RowIndex ?>_seriesal" id="x<?php echo $detfacpro_grid->RowIndex ?>_seriesal" value="<?php echo ew_HtmlEncode($detfacpro->seriesal->FormValue) ?>">
<input type="hidden" data-field="x_seriesal" name="o<?php echo $detfacpro_grid->RowIndex ?>_seriesal" id="o<?php echo $detfacpro_grid->RowIndex ?>_seriesal" value="<?php echo ew_HtmlEncode($detfacpro->seriesal->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfacpro->ncompsal->Visible) { // ncompsal ?>
		<td data-name="ncompsal"<?php echo $detfacpro->ncompsal->CellAttributes() ?>>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_ncompsal" class="form-group detfacpro_ncompsal">
<input type="text" data-field="x_ncompsal" name="x<?php echo $detfacpro_grid->RowIndex ?>_ncompsal" id="x<?php echo $detfacpro_grid->RowIndex ?>_ncompsal" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->ncompsal->PlaceHolder) ?>" value="<?php echo $detfacpro->ncompsal->EditValue ?>"<?php echo $detfacpro->ncompsal->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_ncompsal" name="o<?php echo $detfacpro_grid->RowIndex ?>_ncompsal" id="o<?php echo $detfacpro_grid->RowIndex ?>_ncompsal" value="<?php echo ew_HtmlEncode($detfacpro->ncompsal->OldValue) ?>">
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfacpro_grid->RowCnt ?>_detfacpro_ncompsal" class="form-group detfacpro_ncompsal">
<input type="text" data-field="x_ncompsal" name="x<?php echo $detfacpro_grid->RowIndex ?>_ncompsal" id="x<?php echo $detfacpro_grid->RowIndex ?>_ncompsal" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->ncompsal->PlaceHolder) ?>" value="<?php echo $detfacpro->ncompsal->EditValue ?>"<?php echo $detfacpro->ncompsal->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfacpro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfacpro->ncompsal->ViewAttributes() ?>>
<?php echo $detfacpro->ncompsal->ListViewValue() ?></span>
<input type="hidden" data-field="x_ncompsal" name="x<?php echo $detfacpro_grid->RowIndex ?>_ncompsal" id="x<?php echo $detfacpro_grid->RowIndex ?>_ncompsal" value="<?php echo ew_HtmlEncode($detfacpro->ncompsal->FormValue) ?>">
<input type="hidden" data-field="x_ncompsal" name="o<?php echo $detfacpro_grid->RowIndex ?>_ncompsal" id="o<?php echo $detfacpro_grid->RowIndex ?>_ncompsal" value="<?php echo ew_HtmlEncode($detfacpro->ncompsal->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detfacpro_grid->ListOptions->Render("body", "right", $detfacpro_grid->RowCnt);
?>
	</tr>
<?php if ($detfacpro->RowType == EW_ROWTYPE_ADD || $detfacpro->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdetfacprogrid.UpdateOpts(<?php echo $detfacpro_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($detfacpro->CurrentAction <> "gridadd" || $detfacpro->CurrentMode == "copy")
		if (!$detfacpro_grid->Recordset->EOF) $detfacpro_grid->Recordset->MoveNext();
}
?>
<?php
	if ($detfacpro->CurrentMode == "add" || $detfacpro->CurrentMode == "copy" || $detfacpro->CurrentMode == "edit") {
		$detfacpro_grid->RowIndex = '$rowindex$';
		$detfacpro_grid->LoadDefaultValues();

		// Set row properties
		$detfacpro->ResetAttrs();
		$detfacpro->RowAttrs = array_merge($detfacpro->RowAttrs, array('data-rowindex'=>$detfacpro_grid->RowIndex, 'id'=>'r0_detfacpro', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($detfacpro->RowAttrs["class"], "ewTemplate");
		$detfacpro->RowType = EW_ROWTYPE_ADD;

		// Render row
		$detfacpro_grid->RenderRow();

		// Render list options
		$detfacpro_grid->RenderListOptions();
		$detfacpro_grid->StartRowCnt = 0;
?>
	<tr<?php echo $detfacpro->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detfacpro_grid->ListOptions->Render("body", "left", $detfacpro_grid->RowIndex);
?>
	<?php if ($detfacpro->codnum->Visible) { // codnum ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfacpro_codnum" class="form-group detfacpro_codnum">
<input type="text" data-field="x_codnum" name="x<?php echo $detfacpro_grid->RowIndex ?>_codnum" id="x<?php echo $detfacpro_grid->RowIndex ?>_codnum" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->codnum->PlaceHolder) ?>" value="<?php echo $detfacpro->codnum->EditValue ?>"<?php echo $detfacpro->codnum->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_codnum" class="form-group detfacpro_codnum">
<span<?php echo $detfacpro->codnum->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->codnum->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codnum" name="x<?php echo $detfacpro_grid->RowIndex ?>_codnum" id="x<?php echo $detfacpro_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($detfacpro->codnum->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codnum" name="o<?php echo $detfacpro_grid->RowIndex ?>_codnum" id="o<?php echo $detfacpro_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($detfacpro->codnum->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->tcomp->Visible) { // tcomp ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<?php if ($detfacpro->tcomp->getSessionValue() <> "") { ?>
<span<?php echo $detfacpro->tcomp->ViewAttributes() ?>>
<?php echo $detfacpro->tcomp->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $detfacpro_grid->RowIndex ?>_tcomp" name="x<?php echo $detfacpro_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($detfacpro->tcomp->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_tcomp" name="x<?php echo $detfacpro_grid->RowIndex ?>_tcomp" id="x<?php echo $detfacpro_grid->RowIndex ?>_tcomp" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->tcomp->PlaceHolder) ?>" value="<?php echo $detfacpro->tcomp->EditValue ?>"<?php echo $detfacpro->tcomp->EditAttributes() ?>>
<?php } ?>
<?php } else { ?>
<span<?php echo $detfacpro->tcomp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->tcomp->ViewValue ?></p></span>
<input type="hidden" data-field="x_tcomp" name="x<?php echo $detfacpro_grid->RowIndex ?>_tcomp" id="x<?php echo $detfacpro_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($detfacpro->tcomp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_tcomp" name="o<?php echo $detfacpro_grid->RowIndex ?>_tcomp" id="o<?php echo $detfacpro_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($detfacpro->tcomp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->serie->Visible) { // serie ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<?php if ($detfacpro->serie->getSessionValue() <> "") { ?>
<span<?php echo $detfacpro->serie->ViewAttributes() ?>>
<?php echo $detfacpro->serie->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $detfacpro_grid->RowIndex ?>_serie" name="x<?php echo $detfacpro_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($detfacpro->serie->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_serie" name="x<?php echo $detfacpro_grid->RowIndex ?>_serie" id="x<?php echo $detfacpro_grid->RowIndex ?>_serie" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->serie->PlaceHolder) ?>" value="<?php echo $detfacpro->serie->EditValue ?>"<?php echo $detfacpro->serie->EditAttributes() ?>>
<?php } ?>
<?php } else { ?>
<span<?php echo $detfacpro->serie->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->serie->ViewValue ?></p></span>
<input type="hidden" data-field="x_serie" name="x<?php echo $detfacpro_grid->RowIndex ?>_serie" id="x<?php echo $detfacpro_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($detfacpro->serie->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_serie" name="o<?php echo $detfacpro_grid->RowIndex ?>_serie" id="o<?php echo $detfacpro_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($detfacpro->serie->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->ncomp->Visible) { // ncomp ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<?php if ($detfacpro->ncomp->getSessionValue() <> "") { ?>
<span<?php echo $detfacpro->ncomp->ViewAttributes() ?>>
<?php echo $detfacpro->ncomp->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $detfacpro_grid->RowIndex ?>_ncomp" name="x<?php echo $detfacpro_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($detfacpro->ncomp->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_ncomp" name="x<?php echo $detfacpro_grid->RowIndex ?>_ncomp" id="x<?php echo $detfacpro_grid->RowIndex ?>_ncomp" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->ncomp->PlaceHolder) ?>" value="<?php echo $detfacpro->ncomp->EditValue ?>"<?php echo $detfacpro->ncomp->EditAttributes() ?>>
<?php } ?>
<?php } else { ?>
<span<?php echo $detfacpro->ncomp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->ncomp->ViewValue ?></p></span>
<input type="hidden" data-field="x_ncomp" name="x<?php echo $detfacpro_grid->RowIndex ?>_ncomp" id="x<?php echo $detfacpro_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($detfacpro->ncomp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ncomp" name="o<?php echo $detfacpro_grid->RowIndex ?>_ncomp" id="o<?php echo $detfacpro_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($detfacpro->ncomp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->nreng->Visible) { // nreng ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfacpro_nreng" class="form-group detfacpro_nreng">
<input type="text" data-field="x_nreng" name="x<?php echo $detfacpro_grid->RowIndex ?>_nreng" id="x<?php echo $detfacpro_grid->RowIndex ?>_nreng" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->nreng->PlaceHolder) ?>" value="<?php echo $detfacpro->nreng->EditValue ?>"<?php echo $detfacpro->nreng->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_nreng" class="form-group detfacpro_nreng">
<span<?php echo $detfacpro->nreng->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->nreng->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_nreng" name="x<?php echo $detfacpro_grid->RowIndex ?>_nreng" id="x<?php echo $detfacpro_grid->RowIndex ?>_nreng" value="<?php echo ew_HtmlEncode($detfacpro->nreng->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nreng" name="o<?php echo $detfacpro_grid->RowIndex ?>_nreng" id="o<?php echo $detfacpro_grid->RowIndex ?>_nreng" value="<?php echo ew_HtmlEncode($detfacpro->nreng->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->codrem->Visible) { // codrem ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfacpro_codrem" class="form-group detfacpro_codrem">
<input type="text" data-field="x_codrem" name="x<?php echo $detfacpro_grid->RowIndex ?>_codrem" id="x<?php echo $detfacpro_grid->RowIndex ?>_codrem" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->codrem->PlaceHolder) ?>" value="<?php echo $detfacpro->codrem->EditValue ?>"<?php echo $detfacpro->codrem->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_codrem" class="form-group detfacpro_codrem">
<span<?php echo $detfacpro->codrem->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->codrem->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codrem" name="x<?php echo $detfacpro_grid->RowIndex ?>_codrem" id="x<?php echo $detfacpro_grid->RowIndex ?>_codrem" value="<?php echo ew_HtmlEncode($detfacpro->codrem->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codrem" name="o<?php echo $detfacpro_grid->RowIndex ?>_codrem" id="o<?php echo $detfacpro_grid->RowIndex ?>_codrem" value="<?php echo ew_HtmlEncode($detfacpro->codrem->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->codlote->Visible) { // codlote ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfacpro_codlote" class="form-group detfacpro_codlote">
<input type="text" data-field="x_codlote" name="x<?php echo $detfacpro_grid->RowIndex ?>_codlote" id="x<?php echo $detfacpro_grid->RowIndex ?>_codlote" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->codlote->PlaceHolder) ?>" value="<?php echo $detfacpro->codlote->EditValue ?>"<?php echo $detfacpro->codlote->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_codlote" class="form-group detfacpro_codlote">
<span<?php echo $detfacpro->codlote->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->codlote->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codlote" name="x<?php echo $detfacpro_grid->RowIndex ?>_codlote" id="x<?php echo $detfacpro_grid->RowIndex ?>_codlote" value="<?php echo ew_HtmlEncode($detfacpro->codlote->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codlote" name="o<?php echo $detfacpro_grid->RowIndex ?>_codlote" id="o<?php echo $detfacpro_grid->RowIndex ?>_codlote" value="<?php echo ew_HtmlEncode($detfacpro->codlote->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->descrip->Visible) { // descrip ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfacpro_descrip" class="form-group detfacpro_descrip">
<input type="text" data-field="x_descrip" name="x<?php echo $detfacpro_grid->RowIndex ?>_descrip" id="x<?php echo $detfacpro_grid->RowIndex ?>_descrip" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($detfacpro->descrip->PlaceHolder) ?>" value="<?php echo $detfacpro->descrip->EditValue ?>"<?php echo $detfacpro->descrip->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_descrip" class="form-group detfacpro_descrip">
<span<?php echo $detfacpro->descrip->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->descrip->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_descrip" name="x<?php echo $detfacpro_grid->RowIndex ?>_descrip" id="x<?php echo $detfacpro_grid->RowIndex ?>_descrip" value="<?php echo ew_HtmlEncode($detfacpro->descrip->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_descrip" name="o<?php echo $detfacpro_grid->RowIndex ?>_descrip" id="o<?php echo $detfacpro_grid->RowIndex ?>_descrip" value="<?php echo ew_HtmlEncode($detfacpro->descrip->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->neto->Visible) { // neto ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfacpro_neto" class="form-group detfacpro_neto">
<input type="text" data-field="x_neto" name="x<?php echo $detfacpro_grid->RowIndex ?>_neto" id="x<?php echo $detfacpro_grid->RowIndex ?>_neto" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->neto->PlaceHolder) ?>" value="<?php echo $detfacpro->neto->EditValue ?>"<?php echo $detfacpro->neto->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_neto" class="form-group detfacpro_neto">
<span<?php echo $detfacpro->neto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->neto->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_neto" name="x<?php echo $detfacpro_grid->RowIndex ?>_neto" id="x<?php echo $detfacpro_grid->RowIndex ?>_neto" value="<?php echo ew_HtmlEncode($detfacpro->neto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_neto" name="o<?php echo $detfacpro_grid->RowIndex ?>_neto" id="o<?php echo $detfacpro_grid->RowIndex ?>_neto" value="<?php echo ew_HtmlEncode($detfacpro->neto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->bruto->Visible) { // bruto ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfacpro_bruto" class="form-group detfacpro_bruto">
<input type="text" data-field="x_bruto" name="x<?php echo $detfacpro_grid->RowIndex ?>_bruto" id="x<?php echo $detfacpro_grid->RowIndex ?>_bruto" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->bruto->PlaceHolder) ?>" value="<?php echo $detfacpro->bruto->EditValue ?>"<?php echo $detfacpro->bruto->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_bruto" class="form-group detfacpro_bruto">
<span<?php echo $detfacpro->bruto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->bruto->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_bruto" name="x<?php echo $detfacpro_grid->RowIndex ?>_bruto" id="x<?php echo $detfacpro_grid->RowIndex ?>_bruto" value="<?php echo ew_HtmlEncode($detfacpro->bruto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_bruto" name="o<?php echo $detfacpro_grid->RowIndex ?>_bruto" id="o<?php echo $detfacpro_grid->RowIndex ?>_bruto" value="<?php echo ew_HtmlEncode($detfacpro->bruto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->iva->Visible) { // iva ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfacpro_iva" class="form-group detfacpro_iva">
<input type="text" data-field="x_iva" name="x<?php echo $detfacpro_grid->RowIndex ?>_iva" id="x<?php echo $detfacpro_grid->RowIndex ?>_iva" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->iva->PlaceHolder) ?>" value="<?php echo $detfacpro->iva->EditValue ?>"<?php echo $detfacpro->iva->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_iva" class="form-group detfacpro_iva">
<span<?php echo $detfacpro->iva->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->iva->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_iva" name="x<?php echo $detfacpro_grid->RowIndex ?>_iva" id="x<?php echo $detfacpro_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($detfacpro->iva->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_iva" name="o<?php echo $detfacpro_grid->RowIndex ?>_iva" id="o<?php echo $detfacpro_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($detfacpro->iva->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->imp->Visible) { // imp ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfacpro_imp" class="form-group detfacpro_imp">
<input type="text" data-field="x_imp" name="x<?php echo $detfacpro_grid->RowIndex ?>_imp" id="x<?php echo $detfacpro_grid->RowIndex ?>_imp" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->imp->PlaceHolder) ?>" value="<?php echo $detfacpro->imp->EditValue ?>"<?php echo $detfacpro->imp->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_imp" class="form-group detfacpro_imp">
<span<?php echo $detfacpro->imp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->imp->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_imp" name="x<?php echo $detfacpro_grid->RowIndex ?>_imp" id="x<?php echo $detfacpro_grid->RowIndex ?>_imp" value="<?php echo ew_HtmlEncode($detfacpro->imp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_imp" name="o<?php echo $detfacpro_grid->RowIndex ?>_imp" id="o<?php echo $detfacpro_grid->RowIndex ?>_imp" value="<?php echo ew_HtmlEncode($detfacpro->imp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->comcob->Visible) { // comcob ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfacpro_comcob" class="form-group detfacpro_comcob">
<input type="text" data-field="x_comcob" name="x<?php echo $detfacpro_grid->RowIndex ?>_comcob" id="x<?php echo $detfacpro_grid->RowIndex ?>_comcob" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->comcob->PlaceHolder) ?>" value="<?php echo $detfacpro->comcob->EditValue ?>"<?php echo $detfacpro->comcob->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_comcob" class="form-group detfacpro_comcob">
<span<?php echo $detfacpro->comcob->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->comcob->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_comcob" name="x<?php echo $detfacpro_grid->RowIndex ?>_comcob" id="x<?php echo $detfacpro_grid->RowIndex ?>_comcob" value="<?php echo ew_HtmlEncode($detfacpro->comcob->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_comcob" name="o<?php echo $detfacpro_grid->RowIndex ?>_comcob" id="o<?php echo $detfacpro_grid->RowIndex ?>_comcob" value="<?php echo ew_HtmlEncode($detfacpro->comcob->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->compag->Visible) { // compag ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfacpro_compag" class="form-group detfacpro_compag">
<input type="text" data-field="x_compag" name="x<?php echo $detfacpro_grid->RowIndex ?>_compag" id="x<?php echo $detfacpro_grid->RowIndex ?>_compag" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->compag->PlaceHolder) ?>" value="<?php echo $detfacpro->compag->EditValue ?>"<?php echo $detfacpro->compag->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_compag" class="form-group detfacpro_compag">
<span<?php echo $detfacpro->compag->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->compag->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_compag" name="x<?php echo $detfacpro_grid->RowIndex ?>_compag" id="x<?php echo $detfacpro_grid->RowIndex ?>_compag" value="<?php echo ew_HtmlEncode($detfacpro->compag->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_compag" name="o<?php echo $detfacpro_grid->RowIndex ?>_compag" id="o<?php echo $detfacpro_grid->RowIndex ?>_compag" value="<?php echo ew_HtmlEncode($detfacpro->compag->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->fechahora->Visible) { // fechahora ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_fechahora" class="form-group detfacpro_fechahora">
<span<?php echo $detfacpro->fechahora->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->fechahora->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fechahora" name="x<?php echo $detfacpro_grid->RowIndex ?>_fechahora" id="x<?php echo $detfacpro_grid->RowIndex ?>_fechahora" value="<?php echo ew_HtmlEncode($detfacpro->fechahora->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fechahora" name="o<?php echo $detfacpro_grid->RowIndex ?>_fechahora" id="o<?php echo $detfacpro_grid->RowIndex ?>_fechahora" value="<?php echo ew_HtmlEncode($detfacpro->fechahora->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->usuario->Visible) { // usuario ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_usuario" class="form-group detfacpro_usuario">
<span<?php echo $detfacpro->usuario->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->usuario->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_usuario" name="x<?php echo $detfacpro_grid->RowIndex ?>_usuario" id="x<?php echo $detfacpro_grid->RowIndex ?>_usuario" value="<?php echo ew_HtmlEncode($detfacpro->usuario->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_usuario" name="o<?php echo $detfacpro_grid->RowIndex ?>_usuario" id="o<?php echo $detfacpro_grid->RowIndex ?>_usuario" value="<?php echo ew_HtmlEncode($detfacpro->usuario->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->porciva->Visible) { // porciva ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfacpro_porciva" class="form-group detfacpro_porciva">
<input type="text" data-field="x_porciva" name="x<?php echo $detfacpro_grid->RowIndex ?>_porciva" id="x<?php echo $detfacpro_grid->RowIndex ?>_porciva" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->porciva->PlaceHolder) ?>" value="<?php echo $detfacpro->porciva->EditValue ?>"<?php echo $detfacpro->porciva->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_porciva" class="form-group detfacpro_porciva">
<span<?php echo $detfacpro->porciva->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->porciva->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_porciva" name="x<?php echo $detfacpro_grid->RowIndex ?>_porciva" id="x<?php echo $detfacpro_grid->RowIndex ?>_porciva" value="<?php echo ew_HtmlEncode($detfacpro->porciva->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_porciva" name="o<?php echo $detfacpro_grid->RowIndex ?>_porciva" id="o<?php echo $detfacpro_grid->RowIndex ?>_porciva" value="<?php echo ew_HtmlEncode($detfacpro->porciva->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->tieneresol->Visible) { // tieneresol ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfacpro_tieneresol" class="form-group detfacpro_tieneresol">
<input type="text" data-field="x_tieneresol" name="x<?php echo $detfacpro_grid->RowIndex ?>_tieneresol" id="x<?php echo $detfacpro_grid->RowIndex ?>_tieneresol" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->tieneresol->PlaceHolder) ?>" value="<?php echo $detfacpro->tieneresol->EditValue ?>"<?php echo $detfacpro->tieneresol->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_tieneresol" class="form-group detfacpro_tieneresol">
<span<?php echo $detfacpro->tieneresol->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->tieneresol->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_tieneresol" name="x<?php echo $detfacpro_grid->RowIndex ?>_tieneresol" id="x<?php echo $detfacpro_grid->RowIndex ?>_tieneresol" value="<?php echo ew_HtmlEncode($detfacpro->tieneresol->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_tieneresol" name="o<?php echo $detfacpro_grid->RowIndex ?>_tieneresol" id="o<?php echo $detfacpro_grid->RowIndex ?>_tieneresol" value="<?php echo ew_HtmlEncode($detfacpro->tieneresol->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->concafac->Visible) { // concafac ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfacpro_concafac" class="form-group detfacpro_concafac">
<select data-field="x_concafac" id="x<?php echo $detfacpro_grid->RowIndex ?>_concafac" name="x<?php echo $detfacpro_grid->RowIndex ?>_concafac"<?php echo $detfacpro->concafac->EditAttributes() ?>>
<?php
if (is_array($detfacpro->concafac->EditValue)) {
	$arwrk = $detfacpro->concafac->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($detfacpro->concafac->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $detfacpro->concafac->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `descrip` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `concafact`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $detfacpro->Lookup_Selecting($detfacpro->concafac, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `descrip` ASC";
?>
<input type="hidden" name="s_x<?php echo $detfacpro_grid->RowIndex ?>_concafac" id="s_x<?php echo $detfacpro_grid->RowIndex ?>_concafac" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_concafac" class="form-group detfacpro_concafac">
<span<?php echo $detfacpro->concafac->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->concafac->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_concafac" name="x<?php echo $detfacpro_grid->RowIndex ?>_concafac" id="x<?php echo $detfacpro_grid->RowIndex ?>_concafac" value="<?php echo ew_HtmlEncode($detfacpro->concafac->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_concafac" name="o<?php echo $detfacpro_grid->RowIndex ?>_concafac" id="o<?php echo $detfacpro_grid->RowIndex ?>_concafac" value="<?php echo ew_HtmlEncode($detfacpro->concafac->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->tcomsal->Visible) { // tcomsal ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfacpro_tcomsal" class="form-group detfacpro_tcomsal">
<input type="text" data-field="x_tcomsal" name="x<?php echo $detfacpro_grid->RowIndex ?>_tcomsal" id="x<?php echo $detfacpro_grid->RowIndex ?>_tcomsal" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->tcomsal->PlaceHolder) ?>" value="<?php echo $detfacpro->tcomsal->EditValue ?>"<?php echo $detfacpro->tcomsal->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_tcomsal" class="form-group detfacpro_tcomsal">
<span<?php echo $detfacpro->tcomsal->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->tcomsal->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_tcomsal" name="x<?php echo $detfacpro_grid->RowIndex ?>_tcomsal" id="x<?php echo $detfacpro_grid->RowIndex ?>_tcomsal" value="<?php echo ew_HtmlEncode($detfacpro->tcomsal->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_tcomsal" name="o<?php echo $detfacpro_grid->RowIndex ?>_tcomsal" id="o<?php echo $detfacpro_grid->RowIndex ?>_tcomsal" value="<?php echo ew_HtmlEncode($detfacpro->tcomsal->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->seriesal->Visible) { // seriesal ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfacpro_seriesal" class="form-group detfacpro_seriesal">
<input type="text" data-field="x_seriesal" name="x<?php echo $detfacpro_grid->RowIndex ?>_seriesal" id="x<?php echo $detfacpro_grid->RowIndex ?>_seriesal" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->seriesal->PlaceHolder) ?>" value="<?php echo $detfacpro->seriesal->EditValue ?>"<?php echo $detfacpro->seriesal->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_seriesal" class="form-group detfacpro_seriesal">
<span<?php echo $detfacpro->seriesal->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->seriesal->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_seriesal" name="x<?php echo $detfacpro_grid->RowIndex ?>_seriesal" id="x<?php echo $detfacpro_grid->RowIndex ?>_seriesal" value="<?php echo ew_HtmlEncode($detfacpro->seriesal->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_seriesal" name="o<?php echo $detfacpro_grid->RowIndex ?>_seriesal" id="o<?php echo $detfacpro_grid->RowIndex ?>_seriesal" value="<?php echo ew_HtmlEncode($detfacpro->seriesal->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfacpro->ncompsal->Visible) { // ncompsal ?>
		<td>
<?php if ($detfacpro->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfacpro_ncompsal" class="form-group detfacpro_ncompsal">
<input type="text" data-field="x_ncompsal" name="x<?php echo $detfacpro_grid->RowIndex ?>_ncompsal" id="x<?php echo $detfacpro_grid->RowIndex ?>_ncompsal" size="30" placeholder="<?php echo ew_HtmlEncode($detfacpro->ncompsal->PlaceHolder) ?>" value="<?php echo $detfacpro->ncompsal->EditValue ?>"<?php echo $detfacpro->ncompsal->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfacpro_ncompsal" class="form-group detfacpro_ncompsal">
<span<?php echo $detfacpro->ncompsal->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfacpro->ncompsal->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_ncompsal" name="x<?php echo $detfacpro_grid->RowIndex ?>_ncompsal" id="x<?php echo $detfacpro_grid->RowIndex ?>_ncompsal" value="<?php echo ew_HtmlEncode($detfacpro->ncompsal->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ncompsal" name="o<?php echo $detfacpro_grid->RowIndex ?>_ncompsal" id="o<?php echo $detfacpro_grid->RowIndex ?>_ncompsal" value="<?php echo ew_HtmlEncode($detfacpro->ncompsal->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detfacpro_grid->ListOptions->Render("body", "right", $detfacpro_grid->RowCnt);
?>
<script type="text/javascript">
fdetfacprogrid.UpdateOpts(<?php echo $detfacpro_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($detfacpro->CurrentMode == "add" || $detfacpro->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $detfacpro_grid->FormKeyCountName ?>" id="<?php echo $detfacpro_grid->FormKeyCountName ?>" value="<?php echo $detfacpro_grid->KeyCount ?>">
<?php echo $detfacpro_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($detfacpro->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $detfacpro_grid->FormKeyCountName ?>" id="<?php echo $detfacpro_grid->FormKeyCountName ?>" value="<?php echo $detfacpro_grid->KeyCount ?>">
<?php echo $detfacpro_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($detfacpro->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdetfacprogrid">
</div>
<?php

// Close recordset
if ($detfacpro_grid->Recordset)
	$detfacpro_grid->Recordset->Close();
?>
<?php if ($detfacpro_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($detfacpro_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($detfacpro_grid->TotalRecs == 0 && $detfacpro->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($detfacpro_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($detfacpro->Export == "") { ?>
<script type="text/javascript">
fdetfacprogrid.Init();
</script>
<?php } ?>
<?php
$detfacpro_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$detfacpro_grid->Page_Terminate();
?>
