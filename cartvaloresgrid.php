<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($cartvalores_grid)) $cartvalores_grid = new ccartvalores_grid();

// Page init
$cartvalores_grid->Page_Init();

// Page main
$cartvalores_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cartvalores_grid->Page_Render();
?>
<?php if ($cartvalores->Export == "") { ?>
<script type="text/javascript">

// Page object
var cartvalores_grid = new ew_Page("cartvalores_grid");
cartvalores_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = cartvalores_grid.PageID; // For backward compatibility

// Form object
var fcartvaloresgrid = new ew_Form("fcartvaloresgrid");
fcartvaloresgrid.FormKeyCountName = '<?php echo $cartvalores_grid->FormKeyCountName ?>';

// Validate form
fcartvaloresgrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cartvalores->tcomp->FldCaption(), $cartvalores->tcomp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_serie");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cartvalores->serie->FldCaption(), $cartvalores->serie->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ncomp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cartvalores->ncomp->FldCaption(), $cartvalores->ncomp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ncomp");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->ncomp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_importe");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cartvalores->importe->FldCaption(), $cartvalores->importe->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_importe");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->importe->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechaemis");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->fechaemis->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechapago");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->fechapago->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_entrego");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->entrego->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_recibio");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->recibio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechaingr");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->fechaingr->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechaentrega");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->fechaentrega->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tcomprel");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->tcomprel->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_serierel");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->serierel->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ncomprel");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->ncomprel->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cartvalores->estado->FldCaption(), $cartvalores->estado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_moneda");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cartvalores->moneda->FldCaption(), $cartvalores->moneda->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_moneda");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->moneda->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechahora");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->fechahora->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_usuario");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->usuario->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tcompsal");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->tcompsal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_seriesal");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->seriesal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ncompsal");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->ncompsal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cotiz");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->cotiz->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_usurel");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->usurel->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecharel");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->fecharel->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ususal");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->ususal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechasal");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cartvalores->fechasal->FldErrMsg()) ?>");

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
fcartvaloresgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "tcomp", false)) return false;
	if (ew_ValueChanged(fobj, infix, "serie", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ncomp", false)) return false;
	if (ew_ValueChanged(fobj, infix, "codban", false)) return false;
	if (ew_ValueChanged(fobj, infix, "codsuc", false)) return false;
	if (ew_ValueChanged(fobj, infix, "codcta", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tipcta", false)) return false;
	if (ew_ValueChanged(fobj, infix, "codchq", false)) return false;
	if (ew_ValueChanged(fobj, infix, "codpais", false)) return false;
	if (ew_ValueChanged(fobj, infix, "importe", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fechaemis", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fechapago", false)) return false;
	if (ew_ValueChanged(fobj, infix, "entrego", false)) return false;
	if (ew_ValueChanged(fobj, infix, "recibio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fechaingr", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fechaentrega", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tcomprel", false)) return false;
	if (ew_ValueChanged(fobj, infix, "serierel", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ncomprel", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "moneda", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fechahora", false)) return false;
	if (ew_ValueChanged(fobj, infix, "usuario", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tcompsal", false)) return false;
	if (ew_ValueChanged(fobj, infix, "seriesal", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ncompsal", false)) return false;
	if (ew_ValueChanged(fobj, infix, "codrem", false)) return false;
	if (ew_ValueChanged(fobj, infix, "cotiz", false)) return false;
	if (ew_ValueChanged(fobj, infix, "usurel", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecharel", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ususal", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fechasal", false)) return false;
	return true;
}

// Form_CustomValidate event
fcartvaloresgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcartvaloresgrid.ValidateRequired = true;
<?php } else { ?>
fcartvaloresgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcartvaloresgrid.Lists["x_tcomp"] = {"LinkField":"x_codnum","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcartvaloresgrid.Lists["x_serie"] = {"LinkField":"x_codnum","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcartvaloresgrid.Lists["x_codban"] = {"LinkField":"x_codnum","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcartvaloresgrid.Lists["x_codsuc"] = {"LinkField":"x_codnum","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":["x_codban"],"FilterFields":["x_codbanco"],"Options":[]};
fcartvaloresgrid.Lists["x_codpais"] = {"LinkField":"x_codnum","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcartvaloresgrid.Lists["x_codrem"] = {"LinkField":"x_ncomp","Ajax":true,"AutoFill":false,"DisplayFields":["x_ncomp","x_direccion","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($cartvalores->CurrentAction == "gridadd") {
	if ($cartvalores->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$cartvalores_grid->TotalRecs = $cartvalores->SelectRecordCount();
			$cartvalores_grid->Recordset = $cartvalores_grid->LoadRecordset($cartvalores_grid->StartRec-1, $cartvalores_grid->DisplayRecs);
		} else {
			if ($cartvalores_grid->Recordset = $cartvalores_grid->LoadRecordset())
				$cartvalores_grid->TotalRecs = $cartvalores_grid->Recordset->RecordCount();
		}
		$cartvalores_grid->StartRec = 1;
		$cartvalores_grid->DisplayRecs = $cartvalores_grid->TotalRecs;
	} else {
		$cartvalores->CurrentFilter = "0=1";
		$cartvalores_grid->StartRec = 1;
		$cartvalores_grid->DisplayRecs = $cartvalores->GridAddRowCount;
	}
	$cartvalores_grid->TotalRecs = $cartvalores_grid->DisplayRecs;
	$cartvalores_grid->StopRec = $cartvalores_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($cartvalores_grid->TotalRecs <= 0)
			$cartvalores_grid->TotalRecs = $cartvalores->SelectRecordCount();
	} else {
		if (!$cartvalores_grid->Recordset && ($cartvalores_grid->Recordset = $cartvalores_grid->LoadRecordset()))
			$cartvalores_grid->TotalRecs = $cartvalores_grid->Recordset->RecordCount();
	}
	$cartvalores_grid->StartRec = 1;
	$cartvalores_grid->DisplayRecs = $cartvalores_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$cartvalores_grid->Recordset = $cartvalores_grid->LoadRecordset($cartvalores_grid->StartRec-1, $cartvalores_grid->DisplayRecs);

	// Set no record found message
	if ($cartvalores->CurrentAction == "" && $cartvalores_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$cartvalores_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($cartvalores_grid->SearchWhere == "0=101")
			$cartvalores_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$cartvalores_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$cartvalores_grid->RenderOtherOptions();
?>
<?php $cartvalores_grid->ShowPageHeader(); ?>
<?php
$cartvalores_grid->ShowMessage();
?>
<?php if ($cartvalores_grid->TotalRecs > 0 || $cartvalores->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fcartvaloresgrid" class="ewForm form-inline">
<div id="gmp_cartvalores" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_cartvaloresgrid" class="table ewTable">
<?php echo $cartvalores->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$cartvalores_grid->RenderListOptions();

// Render list options (header, left)
$cartvalores_grid->ListOptions->Render("header", "left");
?>
<?php if ($cartvalores->codnum->Visible) { // codnum ?>
	<?php if ($cartvalores->SortUrl($cartvalores->codnum) == "") { ?>
		<th data-name="codnum"><div id="elh_cartvalores_codnum" class="cartvalores_codnum"><div class="ewTableHeaderCaption"><?php echo $cartvalores->codnum->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codnum"><div><div id="elh_cartvalores_codnum" class="cartvalores_codnum">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->codnum->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->codnum->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->codnum->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->tcomp->Visible) { // tcomp ?>
	<?php if ($cartvalores->SortUrl($cartvalores->tcomp) == "") { ?>
		<th data-name="tcomp"><div id="elh_cartvalores_tcomp" class="cartvalores_tcomp"><div class="ewTableHeaderCaption"><?php echo $cartvalores->tcomp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tcomp"><div><div id="elh_cartvalores_tcomp" class="cartvalores_tcomp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->tcomp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->tcomp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->tcomp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->serie->Visible) { // serie ?>
	<?php if ($cartvalores->SortUrl($cartvalores->serie) == "") { ?>
		<th data-name="serie"><div id="elh_cartvalores_serie" class="cartvalores_serie"><div class="ewTableHeaderCaption"><?php echo $cartvalores->serie->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="serie"><div><div id="elh_cartvalores_serie" class="cartvalores_serie">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->serie->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->serie->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->serie->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->ncomp->Visible) { // ncomp ?>
	<?php if ($cartvalores->SortUrl($cartvalores->ncomp) == "") { ?>
		<th data-name="ncomp"><div id="elh_cartvalores_ncomp" class="cartvalores_ncomp"><div class="ewTableHeaderCaption"><?php echo $cartvalores->ncomp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ncomp"><div><div id="elh_cartvalores_ncomp" class="cartvalores_ncomp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->ncomp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->ncomp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->ncomp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->codban->Visible) { // codban ?>
	<?php if ($cartvalores->SortUrl($cartvalores->codban) == "") { ?>
		<th data-name="codban"><div id="elh_cartvalores_codban" class="cartvalores_codban"><div class="ewTableHeaderCaption"><?php echo $cartvalores->codban->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codban"><div><div id="elh_cartvalores_codban" class="cartvalores_codban">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->codban->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->codban->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->codban->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->codsuc->Visible) { // codsuc ?>
	<?php if ($cartvalores->SortUrl($cartvalores->codsuc) == "") { ?>
		<th data-name="codsuc"><div id="elh_cartvalores_codsuc" class="cartvalores_codsuc"><div class="ewTableHeaderCaption"><?php echo $cartvalores->codsuc->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codsuc"><div><div id="elh_cartvalores_codsuc" class="cartvalores_codsuc">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->codsuc->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->codsuc->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->codsuc->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->codcta->Visible) { // codcta ?>
	<?php if ($cartvalores->SortUrl($cartvalores->codcta) == "") { ?>
		<th data-name="codcta"><div id="elh_cartvalores_codcta" class="cartvalores_codcta"><div class="ewTableHeaderCaption"><?php echo $cartvalores->codcta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codcta"><div><div id="elh_cartvalores_codcta" class="cartvalores_codcta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->codcta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->codcta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->codcta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->tipcta->Visible) { // tipcta ?>
	<?php if ($cartvalores->SortUrl($cartvalores->tipcta) == "") { ?>
		<th data-name="tipcta"><div id="elh_cartvalores_tipcta" class="cartvalores_tipcta"><div class="ewTableHeaderCaption"><?php echo $cartvalores->tipcta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipcta"><div><div id="elh_cartvalores_tipcta" class="cartvalores_tipcta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->tipcta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->tipcta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->tipcta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->codchq->Visible) { // codchq ?>
	<?php if ($cartvalores->SortUrl($cartvalores->codchq) == "") { ?>
		<th data-name="codchq"><div id="elh_cartvalores_codchq" class="cartvalores_codchq"><div class="ewTableHeaderCaption"><?php echo $cartvalores->codchq->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codchq"><div><div id="elh_cartvalores_codchq" class="cartvalores_codchq">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->codchq->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->codchq->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->codchq->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->codpais->Visible) { // codpais ?>
	<?php if ($cartvalores->SortUrl($cartvalores->codpais) == "") { ?>
		<th data-name="codpais"><div id="elh_cartvalores_codpais" class="cartvalores_codpais"><div class="ewTableHeaderCaption"><?php echo $cartvalores->codpais->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codpais"><div><div id="elh_cartvalores_codpais" class="cartvalores_codpais">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->codpais->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->codpais->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->codpais->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->importe->Visible) { // importe ?>
	<?php if ($cartvalores->SortUrl($cartvalores->importe) == "") { ?>
		<th data-name="importe"><div id="elh_cartvalores_importe" class="cartvalores_importe"><div class="ewTableHeaderCaption"><?php echo $cartvalores->importe->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importe"><div><div id="elh_cartvalores_importe" class="cartvalores_importe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->importe->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->importe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->importe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->fechaemis->Visible) { // fechaemis ?>
	<?php if ($cartvalores->SortUrl($cartvalores->fechaemis) == "") { ?>
		<th data-name="fechaemis"><div id="elh_cartvalores_fechaemis" class="cartvalores_fechaemis"><div class="ewTableHeaderCaption"><?php echo $cartvalores->fechaemis->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechaemis"><div><div id="elh_cartvalores_fechaemis" class="cartvalores_fechaemis">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->fechaemis->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->fechaemis->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->fechaemis->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->fechapago->Visible) { // fechapago ?>
	<?php if ($cartvalores->SortUrl($cartvalores->fechapago) == "") { ?>
		<th data-name="fechapago"><div id="elh_cartvalores_fechapago" class="cartvalores_fechapago"><div class="ewTableHeaderCaption"><?php echo $cartvalores->fechapago->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechapago"><div><div id="elh_cartvalores_fechapago" class="cartvalores_fechapago">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->fechapago->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->fechapago->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->fechapago->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->entrego->Visible) { // entrego ?>
	<?php if ($cartvalores->SortUrl($cartvalores->entrego) == "") { ?>
		<th data-name="entrego"><div id="elh_cartvalores_entrego" class="cartvalores_entrego"><div class="ewTableHeaderCaption"><?php echo $cartvalores->entrego->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="entrego"><div><div id="elh_cartvalores_entrego" class="cartvalores_entrego">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->entrego->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->entrego->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->entrego->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->recibio->Visible) { // recibio ?>
	<?php if ($cartvalores->SortUrl($cartvalores->recibio) == "") { ?>
		<th data-name="recibio"><div id="elh_cartvalores_recibio" class="cartvalores_recibio"><div class="ewTableHeaderCaption"><?php echo $cartvalores->recibio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="recibio"><div><div id="elh_cartvalores_recibio" class="cartvalores_recibio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->recibio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->recibio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->recibio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->fechaingr->Visible) { // fechaingr ?>
	<?php if ($cartvalores->SortUrl($cartvalores->fechaingr) == "") { ?>
		<th data-name="fechaingr"><div id="elh_cartvalores_fechaingr" class="cartvalores_fechaingr"><div class="ewTableHeaderCaption"><?php echo $cartvalores->fechaingr->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechaingr"><div><div id="elh_cartvalores_fechaingr" class="cartvalores_fechaingr">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->fechaingr->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->fechaingr->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->fechaingr->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->fechaentrega->Visible) { // fechaentrega ?>
	<?php if ($cartvalores->SortUrl($cartvalores->fechaentrega) == "") { ?>
		<th data-name="fechaentrega"><div id="elh_cartvalores_fechaentrega" class="cartvalores_fechaentrega"><div class="ewTableHeaderCaption"><?php echo $cartvalores->fechaentrega->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechaentrega"><div><div id="elh_cartvalores_fechaentrega" class="cartvalores_fechaentrega">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->fechaentrega->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->fechaentrega->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->fechaentrega->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->tcomprel->Visible) { // tcomprel ?>
	<?php if ($cartvalores->SortUrl($cartvalores->tcomprel) == "") { ?>
		<th data-name="tcomprel"><div id="elh_cartvalores_tcomprel" class="cartvalores_tcomprel"><div class="ewTableHeaderCaption"><?php echo $cartvalores->tcomprel->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tcomprel"><div><div id="elh_cartvalores_tcomprel" class="cartvalores_tcomprel">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->tcomprel->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->tcomprel->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->tcomprel->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->serierel->Visible) { // serierel ?>
	<?php if ($cartvalores->SortUrl($cartvalores->serierel) == "") { ?>
		<th data-name="serierel"><div id="elh_cartvalores_serierel" class="cartvalores_serierel"><div class="ewTableHeaderCaption"><?php echo $cartvalores->serierel->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="serierel"><div><div id="elh_cartvalores_serierel" class="cartvalores_serierel">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->serierel->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->serierel->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->serierel->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->ncomprel->Visible) { // ncomprel ?>
	<?php if ($cartvalores->SortUrl($cartvalores->ncomprel) == "") { ?>
		<th data-name="ncomprel"><div id="elh_cartvalores_ncomprel" class="cartvalores_ncomprel"><div class="ewTableHeaderCaption"><?php echo $cartvalores->ncomprel->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ncomprel"><div><div id="elh_cartvalores_ncomprel" class="cartvalores_ncomprel">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->ncomprel->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->ncomprel->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->ncomprel->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->estado->Visible) { // estado ?>
	<?php if ($cartvalores->SortUrl($cartvalores->estado) == "") { ?>
		<th data-name="estado"><div id="elh_cartvalores_estado" class="cartvalores_estado"><div class="ewTableHeaderCaption"><?php echo $cartvalores->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div><div id="elh_cartvalores_estado" class="cartvalores_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->moneda->Visible) { // moneda ?>
	<?php if ($cartvalores->SortUrl($cartvalores->moneda) == "") { ?>
		<th data-name="moneda"><div id="elh_cartvalores_moneda" class="cartvalores_moneda"><div class="ewTableHeaderCaption"><?php echo $cartvalores->moneda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="moneda"><div><div id="elh_cartvalores_moneda" class="cartvalores_moneda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->moneda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->moneda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->moneda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->fechahora->Visible) { // fechahora ?>
	<?php if ($cartvalores->SortUrl($cartvalores->fechahora) == "") { ?>
		<th data-name="fechahora"><div id="elh_cartvalores_fechahora" class="cartvalores_fechahora"><div class="ewTableHeaderCaption"><?php echo $cartvalores->fechahora->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechahora"><div><div id="elh_cartvalores_fechahora" class="cartvalores_fechahora">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->fechahora->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->fechahora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->fechahora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->usuario->Visible) { // usuario ?>
	<?php if ($cartvalores->SortUrl($cartvalores->usuario) == "") { ?>
		<th data-name="usuario"><div id="elh_cartvalores_usuario" class="cartvalores_usuario"><div class="ewTableHeaderCaption"><?php echo $cartvalores->usuario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="usuario"><div><div id="elh_cartvalores_usuario" class="cartvalores_usuario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->usuario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->usuario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->usuario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->tcompsal->Visible) { // tcompsal ?>
	<?php if ($cartvalores->SortUrl($cartvalores->tcompsal) == "") { ?>
		<th data-name="tcompsal"><div id="elh_cartvalores_tcompsal" class="cartvalores_tcompsal"><div class="ewTableHeaderCaption"><?php echo $cartvalores->tcompsal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tcompsal"><div><div id="elh_cartvalores_tcompsal" class="cartvalores_tcompsal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->tcompsal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->tcompsal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->tcompsal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->seriesal->Visible) { // seriesal ?>
	<?php if ($cartvalores->SortUrl($cartvalores->seriesal) == "") { ?>
		<th data-name="seriesal"><div id="elh_cartvalores_seriesal" class="cartvalores_seriesal"><div class="ewTableHeaderCaption"><?php echo $cartvalores->seriesal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="seriesal"><div><div id="elh_cartvalores_seriesal" class="cartvalores_seriesal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->seriesal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->seriesal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->seriesal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->ncompsal->Visible) { // ncompsal ?>
	<?php if ($cartvalores->SortUrl($cartvalores->ncompsal) == "") { ?>
		<th data-name="ncompsal"><div id="elh_cartvalores_ncompsal" class="cartvalores_ncompsal"><div class="ewTableHeaderCaption"><?php echo $cartvalores->ncompsal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ncompsal"><div><div id="elh_cartvalores_ncompsal" class="cartvalores_ncompsal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->ncompsal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->ncompsal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->ncompsal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->codrem->Visible) { // codrem ?>
	<?php if ($cartvalores->SortUrl($cartvalores->codrem) == "") { ?>
		<th data-name="codrem"><div id="elh_cartvalores_codrem" class="cartvalores_codrem"><div class="ewTableHeaderCaption"><?php echo $cartvalores->codrem->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codrem"><div><div id="elh_cartvalores_codrem" class="cartvalores_codrem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->codrem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->codrem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->codrem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->cotiz->Visible) { // cotiz ?>
	<?php if ($cartvalores->SortUrl($cartvalores->cotiz) == "") { ?>
		<th data-name="cotiz"><div id="elh_cartvalores_cotiz" class="cartvalores_cotiz"><div class="ewTableHeaderCaption"><?php echo $cartvalores->cotiz->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cotiz"><div><div id="elh_cartvalores_cotiz" class="cartvalores_cotiz">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->cotiz->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->cotiz->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->cotiz->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->usurel->Visible) { // usurel ?>
	<?php if ($cartvalores->SortUrl($cartvalores->usurel) == "") { ?>
		<th data-name="usurel"><div id="elh_cartvalores_usurel" class="cartvalores_usurel"><div class="ewTableHeaderCaption"><?php echo $cartvalores->usurel->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="usurel"><div><div id="elh_cartvalores_usurel" class="cartvalores_usurel">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->usurel->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->usurel->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->usurel->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->fecharel->Visible) { // fecharel ?>
	<?php if ($cartvalores->SortUrl($cartvalores->fecharel) == "") { ?>
		<th data-name="fecharel"><div id="elh_cartvalores_fecharel" class="cartvalores_fecharel"><div class="ewTableHeaderCaption"><?php echo $cartvalores->fecharel->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecharel"><div><div id="elh_cartvalores_fecharel" class="cartvalores_fecharel">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->fecharel->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->fecharel->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->fecharel->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->ususal->Visible) { // ususal ?>
	<?php if ($cartvalores->SortUrl($cartvalores->ususal) == "") { ?>
		<th data-name="ususal"><div id="elh_cartvalores_ususal" class="cartvalores_ususal"><div class="ewTableHeaderCaption"><?php echo $cartvalores->ususal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ususal"><div><div id="elh_cartvalores_ususal" class="cartvalores_ususal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->ususal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->ususal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->ususal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cartvalores->fechasal->Visible) { // fechasal ?>
	<?php if ($cartvalores->SortUrl($cartvalores->fechasal) == "") { ?>
		<th data-name="fechasal"><div id="elh_cartvalores_fechasal" class="cartvalores_fechasal"><div class="ewTableHeaderCaption"><?php echo $cartvalores->fechasal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechasal"><div><div id="elh_cartvalores_fechasal" class="cartvalores_fechasal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cartvalores->fechasal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cartvalores->fechasal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cartvalores->fechasal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$cartvalores_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$cartvalores_grid->StartRec = 1;
$cartvalores_grid->StopRec = $cartvalores_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($cartvalores_grid->FormKeyCountName) && ($cartvalores->CurrentAction == "gridadd" || $cartvalores->CurrentAction == "gridedit" || $cartvalores->CurrentAction == "F")) {
		$cartvalores_grid->KeyCount = $objForm->GetValue($cartvalores_grid->FormKeyCountName);
		$cartvalores_grid->StopRec = $cartvalores_grid->StartRec + $cartvalores_grid->KeyCount - 1;
	}
}
$cartvalores_grid->RecCnt = $cartvalores_grid->StartRec - 1;
if ($cartvalores_grid->Recordset && !$cartvalores_grid->Recordset->EOF) {
	$cartvalores_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $cartvalores_grid->StartRec > 1)
		$cartvalores_grid->Recordset->Move($cartvalores_grid->StartRec - 1);
} elseif (!$cartvalores->AllowAddDeleteRow && $cartvalores_grid->StopRec == 0) {
	$cartvalores_grid->StopRec = $cartvalores->GridAddRowCount;
}

// Initialize aggregate
$cartvalores->RowType = EW_ROWTYPE_AGGREGATEINIT;
$cartvalores->ResetAttrs();
$cartvalores_grid->RenderRow();
if ($cartvalores->CurrentAction == "gridadd")
	$cartvalores_grid->RowIndex = 0;
if ($cartvalores->CurrentAction == "gridedit")
	$cartvalores_grid->RowIndex = 0;
while ($cartvalores_grid->RecCnt < $cartvalores_grid->StopRec) {
	$cartvalores_grid->RecCnt++;
	if (intval($cartvalores_grid->RecCnt) >= intval($cartvalores_grid->StartRec)) {
		$cartvalores_grid->RowCnt++;
		if ($cartvalores->CurrentAction == "gridadd" || $cartvalores->CurrentAction == "gridedit" || $cartvalores->CurrentAction == "F") {
			$cartvalores_grid->RowIndex++;
			$objForm->Index = $cartvalores_grid->RowIndex;
			if ($objForm->HasValue($cartvalores_grid->FormActionName))
				$cartvalores_grid->RowAction = strval($objForm->GetValue($cartvalores_grid->FormActionName));
			elseif ($cartvalores->CurrentAction == "gridadd")
				$cartvalores_grid->RowAction = "insert";
			else
				$cartvalores_grid->RowAction = "";
		}

		// Set up key count
		$cartvalores_grid->KeyCount = $cartvalores_grid->RowIndex;

		// Init row class and style
		$cartvalores->ResetAttrs();
		$cartvalores->CssClass = "";
		if ($cartvalores->CurrentAction == "gridadd") {
			if ($cartvalores->CurrentMode == "copy") {
				$cartvalores_grid->LoadRowValues($cartvalores_grid->Recordset); // Load row values
				$cartvalores_grid->SetRecordKey($cartvalores_grid->RowOldKey, $cartvalores_grid->Recordset); // Set old record key
			} else {
				$cartvalores_grid->LoadDefaultValues(); // Load default values
				$cartvalores_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$cartvalores_grid->LoadRowValues($cartvalores_grid->Recordset); // Load row values
		}
		$cartvalores->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($cartvalores->CurrentAction == "gridadd") // Grid add
			$cartvalores->RowType = EW_ROWTYPE_ADD; // Render add
		if ($cartvalores->CurrentAction == "gridadd" && $cartvalores->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$cartvalores_grid->RestoreCurrentRowFormValues($cartvalores_grid->RowIndex); // Restore form values
		if ($cartvalores->CurrentAction == "gridedit") { // Grid edit
			if ($cartvalores->EventCancelled) {
				$cartvalores_grid->RestoreCurrentRowFormValues($cartvalores_grid->RowIndex); // Restore form values
			}
			if ($cartvalores_grid->RowAction == "insert")
				$cartvalores->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$cartvalores->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($cartvalores->CurrentAction == "gridedit" && ($cartvalores->RowType == EW_ROWTYPE_EDIT || $cartvalores->RowType == EW_ROWTYPE_ADD) && $cartvalores->EventCancelled) // Update failed
			$cartvalores_grid->RestoreCurrentRowFormValues($cartvalores_grid->RowIndex); // Restore form values
		if ($cartvalores->RowType == EW_ROWTYPE_EDIT) // Edit row
			$cartvalores_grid->EditRowCnt++;
		if ($cartvalores->CurrentAction == "F") // Confirm row
			$cartvalores_grid->RestoreCurrentRowFormValues($cartvalores_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$cartvalores->RowAttrs = array_merge($cartvalores->RowAttrs, array('data-rowindex'=>$cartvalores_grid->RowCnt, 'id'=>'r' . $cartvalores_grid->RowCnt . '_cartvalores', 'data-rowtype'=>$cartvalores->RowType));

		// Render row
		$cartvalores_grid->RenderRow();

		// Render list options
		$cartvalores_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($cartvalores_grid->RowAction <> "delete" && $cartvalores_grid->RowAction <> "insertdelete" && !($cartvalores_grid->RowAction == "insert" && $cartvalores->CurrentAction == "F" && $cartvalores_grid->EmptyRow())) {
?>
	<tr<?php echo $cartvalores->RowAttributes() ?>>
<?php

// Render list options (body, left)
$cartvalores_grid->ListOptions->Render("body", "left", $cartvalores_grid->RowCnt);
?>
	<?php if ($cartvalores->codnum->Visible) { // codnum ?>
		<td data-name="codnum"<?php echo $cartvalores->codnum->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_codnum" name="o<?php echo $cartvalores_grid->RowIndex ?>_codnum" id="o<?php echo $cartvalores_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($cartvalores->codnum->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_codnum" class="form-group cartvalores_codnum">
<span<?php echo $cartvalores->codnum->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->codnum->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_codnum" name="x<?php echo $cartvalores_grid->RowIndex ?>_codnum" id="x<?php echo $cartvalores_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($cartvalores->codnum->CurrentValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->codnum->ViewAttributes() ?>>
<?php echo $cartvalores->codnum->ListViewValue() ?></span>
<input type="hidden" data-field="x_codnum" name="x<?php echo $cartvalores_grid->RowIndex ?>_codnum" id="x<?php echo $cartvalores_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($cartvalores->codnum->FormValue) ?>">
<input type="hidden" data-field="x_codnum" name="o<?php echo $cartvalores_grid->RowIndex ?>_codnum" id="o<?php echo $cartvalores_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($cartvalores->codnum->OldValue) ?>">
<?php } ?>
<a id="<?php echo $cartvalores_grid->PageObjName . "_row_" . $cartvalores_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($cartvalores->tcomp->Visible) { // tcomp ?>
		<td data-name="tcomp"<?php echo $cartvalores->tcomp->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_tcomp" class="form-group cartvalores_tcomp">
<select data-field="x_tcomp" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcomp" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcomp"<?php echo $cartvalores->tcomp->EditAttributes() ?>>
<?php
if (is_array($cartvalores->tcomp->EditValue)) {
	$arwrk = $cartvalores->tcomp->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->tcomp->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->tcomp->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipcomp`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->tcomp, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `descripcion` ASC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_tcomp" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_tcomp" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_tcomp" name="o<?php echo $cartvalores_grid->RowIndex ?>_tcomp" id="o<?php echo $cartvalores_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($cartvalores->tcomp->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_tcomp" class="form-group cartvalores_tcomp">
<select data-field="x_tcomp" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcomp" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcomp"<?php echo $cartvalores->tcomp->EditAttributes() ?>>
<?php
if (is_array($cartvalores->tcomp->EditValue)) {
	$arwrk = $cartvalores->tcomp->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->tcomp->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->tcomp->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipcomp`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->tcomp, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `descripcion` ASC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_tcomp" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_tcomp" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->tcomp->ViewAttributes() ?>>
<?php echo $cartvalores->tcomp->ListViewValue() ?></span>
<input type="hidden" data-field="x_tcomp" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcomp" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($cartvalores->tcomp->FormValue) ?>">
<input type="hidden" data-field="x_tcomp" name="o<?php echo $cartvalores_grid->RowIndex ?>_tcomp" id="o<?php echo $cartvalores_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($cartvalores->tcomp->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->serie->Visible) { // serie ?>
		<td data-name="serie"<?php echo $cartvalores->serie->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_serie" class="form-group cartvalores_serie">
<select data-field="x_serie" id="x<?php echo $cartvalores_grid->RowIndex ?>_serie" name="x<?php echo $cartvalores_grid->RowIndex ?>_serie"<?php echo $cartvalores->serie->EditAttributes() ?>>
<?php
if (is_array($cartvalores->serie->EditValue)) {
	$arwrk = $cartvalores->serie->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->serie->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->serie->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `series`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->serie, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `descripcion` ASC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_serie" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_serie" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_serie" name="o<?php echo $cartvalores_grid->RowIndex ?>_serie" id="o<?php echo $cartvalores_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($cartvalores->serie->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_serie" class="form-group cartvalores_serie">
<select data-field="x_serie" id="x<?php echo $cartvalores_grid->RowIndex ?>_serie" name="x<?php echo $cartvalores_grid->RowIndex ?>_serie"<?php echo $cartvalores->serie->EditAttributes() ?>>
<?php
if (is_array($cartvalores->serie->EditValue)) {
	$arwrk = $cartvalores->serie->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->serie->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->serie->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `series`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->serie, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `descripcion` ASC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_serie" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_serie" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->serie->ViewAttributes() ?>>
<?php echo $cartvalores->serie->ListViewValue() ?></span>
<input type="hidden" data-field="x_serie" name="x<?php echo $cartvalores_grid->RowIndex ?>_serie" id="x<?php echo $cartvalores_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($cartvalores->serie->FormValue) ?>">
<input type="hidden" data-field="x_serie" name="o<?php echo $cartvalores_grid->RowIndex ?>_serie" id="o<?php echo $cartvalores_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($cartvalores->serie->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->ncomp->Visible) { // ncomp ?>
		<td data-name="ncomp"<?php echo $cartvalores->ncomp->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_ncomp" class="form-group cartvalores_ncomp">
<input type="text" data-field="x_ncomp" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncomp" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncomp" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->ncomp->PlaceHolder) ?>" value="<?php echo $cartvalores->ncomp->EditValue ?>"<?php echo $cartvalores->ncomp->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_ncomp" name="o<?php echo $cartvalores_grid->RowIndex ?>_ncomp" id="o<?php echo $cartvalores_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($cartvalores->ncomp->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_ncomp" class="form-group cartvalores_ncomp">
<input type="text" data-field="x_ncomp" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncomp" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncomp" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->ncomp->PlaceHolder) ?>" value="<?php echo $cartvalores->ncomp->EditValue ?>"<?php echo $cartvalores->ncomp->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->ncomp->ViewAttributes() ?>>
<?php echo $cartvalores->ncomp->ListViewValue() ?></span>
<input type="hidden" data-field="x_ncomp" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncomp" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($cartvalores->ncomp->FormValue) ?>">
<input type="hidden" data-field="x_ncomp" name="o<?php echo $cartvalores_grid->RowIndex ?>_ncomp" id="o<?php echo $cartvalores_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($cartvalores->ncomp->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->codban->Visible) { // codban ?>
		<td data-name="codban"<?php echo $cartvalores->codban->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_codban" class="form-group cartvalores_codban">
<?php $cartvalores->codban->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x" . $cartvalores_grid->RowIndex . "_codsuc']); " . @$cartvalores->codban->EditAttrs["onchange"]; ?>
<select data-field="x_codban" id="x<?php echo $cartvalores_grid->RowIndex ?>_codban" name="x<?php echo $cartvalores_grid->RowIndex ?>_codban"<?php echo $cartvalores->codban->EditAttributes() ?>>
<?php
if (is_array($cartvalores->codban->EditValue)) {
	$arwrk = $cartvalores->codban->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->codban->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->codban->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bancos`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->codban, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `nombre` ASC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_codban" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_codban" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_codban" name="o<?php echo $cartvalores_grid->RowIndex ?>_codban" id="o<?php echo $cartvalores_grid->RowIndex ?>_codban" value="<?php echo ew_HtmlEncode($cartvalores->codban->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_codban" class="form-group cartvalores_codban">
<?php $cartvalores->codban->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x" . $cartvalores_grid->RowIndex . "_codsuc']); " . @$cartvalores->codban->EditAttrs["onchange"]; ?>
<select data-field="x_codban" id="x<?php echo $cartvalores_grid->RowIndex ?>_codban" name="x<?php echo $cartvalores_grid->RowIndex ?>_codban"<?php echo $cartvalores->codban->EditAttributes() ?>>
<?php
if (is_array($cartvalores->codban->EditValue)) {
	$arwrk = $cartvalores->codban->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->codban->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->codban->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bancos`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->codban, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `nombre` ASC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_codban" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_codban" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->codban->ViewAttributes() ?>>
<?php echo $cartvalores->codban->ListViewValue() ?></span>
<input type="hidden" data-field="x_codban" name="x<?php echo $cartvalores_grid->RowIndex ?>_codban" id="x<?php echo $cartvalores_grid->RowIndex ?>_codban" value="<?php echo ew_HtmlEncode($cartvalores->codban->FormValue) ?>">
<input type="hidden" data-field="x_codban" name="o<?php echo $cartvalores_grid->RowIndex ?>_codban" id="o<?php echo $cartvalores_grid->RowIndex ?>_codban" value="<?php echo ew_HtmlEncode($cartvalores->codban->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->codsuc->Visible) { // codsuc ?>
		<td data-name="codsuc"<?php echo $cartvalores->codsuc->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_codsuc" class="form-group cartvalores_codsuc">
<select data-field="x_codsuc" id="x<?php echo $cartvalores_grid->RowIndex ?>_codsuc" name="x<?php echo $cartvalores_grid->RowIndex ?>_codsuc"<?php echo $cartvalores->codsuc->EditAttributes() ?>>
<?php
if (is_array($cartvalores->codsuc->EditValue)) {
	$arwrk = $cartvalores->codsuc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->codsuc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->codsuc->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sucbancos`";
 $sWhereWrk = "{filter}";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->codsuc, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `nombre` ASC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_codsuc" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_codsuc" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=3&amp;f1=<?php echo ew_Encrypt("`codbanco` IN ({filter_value})"); ?>&amp;t1=3">
</span>
<input type="hidden" data-field="x_codsuc" name="o<?php echo $cartvalores_grid->RowIndex ?>_codsuc" id="o<?php echo $cartvalores_grid->RowIndex ?>_codsuc" value="<?php echo ew_HtmlEncode($cartvalores->codsuc->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_codsuc" class="form-group cartvalores_codsuc">
<select data-field="x_codsuc" id="x<?php echo $cartvalores_grid->RowIndex ?>_codsuc" name="x<?php echo $cartvalores_grid->RowIndex ?>_codsuc"<?php echo $cartvalores->codsuc->EditAttributes() ?>>
<?php
if (is_array($cartvalores->codsuc->EditValue)) {
	$arwrk = $cartvalores->codsuc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->codsuc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->codsuc->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sucbancos`";
 $sWhereWrk = "{filter}";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->codsuc, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `nombre` ASC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_codsuc" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_codsuc" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=3&amp;f1=<?php echo ew_Encrypt("`codbanco` IN ({filter_value})"); ?>&amp;t1=3">
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->codsuc->ViewAttributes() ?>>
<?php echo $cartvalores->codsuc->ListViewValue() ?></span>
<input type="hidden" data-field="x_codsuc" name="x<?php echo $cartvalores_grid->RowIndex ?>_codsuc" id="x<?php echo $cartvalores_grid->RowIndex ?>_codsuc" value="<?php echo ew_HtmlEncode($cartvalores->codsuc->FormValue) ?>">
<input type="hidden" data-field="x_codsuc" name="o<?php echo $cartvalores_grid->RowIndex ?>_codsuc" id="o<?php echo $cartvalores_grid->RowIndex ?>_codsuc" value="<?php echo ew_HtmlEncode($cartvalores->codsuc->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->codcta->Visible) { // codcta ?>
		<td data-name="codcta"<?php echo $cartvalores->codcta->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_codcta" class="form-group cartvalores_codcta">
<input type="text" data-field="x_codcta" name="x<?php echo $cartvalores_grid->RowIndex ?>_codcta" id="x<?php echo $cartvalores_grid->RowIndex ?>_codcta" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($cartvalores->codcta->PlaceHolder) ?>" value="<?php echo $cartvalores->codcta->EditValue ?>"<?php echo $cartvalores->codcta->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_codcta" name="o<?php echo $cartvalores_grid->RowIndex ?>_codcta" id="o<?php echo $cartvalores_grid->RowIndex ?>_codcta" value="<?php echo ew_HtmlEncode($cartvalores->codcta->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_codcta" class="form-group cartvalores_codcta">
<input type="text" data-field="x_codcta" name="x<?php echo $cartvalores_grid->RowIndex ?>_codcta" id="x<?php echo $cartvalores_grid->RowIndex ?>_codcta" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($cartvalores->codcta->PlaceHolder) ?>" value="<?php echo $cartvalores->codcta->EditValue ?>"<?php echo $cartvalores->codcta->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->codcta->ViewAttributes() ?>>
<?php echo $cartvalores->codcta->ListViewValue() ?></span>
<input type="hidden" data-field="x_codcta" name="x<?php echo $cartvalores_grid->RowIndex ?>_codcta" id="x<?php echo $cartvalores_grid->RowIndex ?>_codcta" value="<?php echo ew_HtmlEncode($cartvalores->codcta->FormValue) ?>">
<input type="hidden" data-field="x_codcta" name="o<?php echo $cartvalores_grid->RowIndex ?>_codcta" id="o<?php echo $cartvalores_grid->RowIndex ?>_codcta" value="<?php echo ew_HtmlEncode($cartvalores->codcta->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->tipcta->Visible) { // tipcta ?>
		<td data-name="tipcta"<?php echo $cartvalores->tipcta->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_tipcta" class="form-group cartvalores_tipcta">
<input type="text" data-field="x_tipcta" name="x<?php echo $cartvalores_grid->RowIndex ?>_tipcta" id="x<?php echo $cartvalores_grid->RowIndex ?>_tipcta" size="30" maxlength="3" placeholder="<?php echo ew_HtmlEncode($cartvalores->tipcta->PlaceHolder) ?>" value="<?php echo $cartvalores->tipcta->EditValue ?>"<?php echo $cartvalores->tipcta->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_tipcta" name="o<?php echo $cartvalores_grid->RowIndex ?>_tipcta" id="o<?php echo $cartvalores_grid->RowIndex ?>_tipcta" value="<?php echo ew_HtmlEncode($cartvalores->tipcta->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_tipcta" class="form-group cartvalores_tipcta">
<input type="text" data-field="x_tipcta" name="x<?php echo $cartvalores_grid->RowIndex ?>_tipcta" id="x<?php echo $cartvalores_grid->RowIndex ?>_tipcta" size="30" maxlength="3" placeholder="<?php echo ew_HtmlEncode($cartvalores->tipcta->PlaceHolder) ?>" value="<?php echo $cartvalores->tipcta->EditValue ?>"<?php echo $cartvalores->tipcta->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->tipcta->ViewAttributes() ?>>
<?php echo $cartvalores->tipcta->ListViewValue() ?></span>
<input type="hidden" data-field="x_tipcta" name="x<?php echo $cartvalores_grid->RowIndex ?>_tipcta" id="x<?php echo $cartvalores_grid->RowIndex ?>_tipcta" value="<?php echo ew_HtmlEncode($cartvalores->tipcta->FormValue) ?>">
<input type="hidden" data-field="x_tipcta" name="o<?php echo $cartvalores_grid->RowIndex ?>_tipcta" id="o<?php echo $cartvalores_grid->RowIndex ?>_tipcta" value="<?php echo ew_HtmlEncode($cartvalores->tipcta->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->codchq->Visible) { // codchq ?>
		<td data-name="codchq"<?php echo $cartvalores->codchq->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_codchq" class="form-group cartvalores_codchq">
<input type="text" data-field="x_codchq" name="x<?php echo $cartvalores_grid->RowIndex ?>_codchq" id="x<?php echo $cartvalores_grid->RowIndex ?>_codchq" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($cartvalores->codchq->PlaceHolder) ?>" value="<?php echo $cartvalores->codchq->EditValue ?>"<?php echo $cartvalores->codchq->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_codchq" name="o<?php echo $cartvalores_grid->RowIndex ?>_codchq" id="o<?php echo $cartvalores_grid->RowIndex ?>_codchq" value="<?php echo ew_HtmlEncode($cartvalores->codchq->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_codchq" class="form-group cartvalores_codchq">
<input type="text" data-field="x_codchq" name="x<?php echo $cartvalores_grid->RowIndex ?>_codchq" id="x<?php echo $cartvalores_grid->RowIndex ?>_codchq" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($cartvalores->codchq->PlaceHolder) ?>" value="<?php echo $cartvalores->codchq->EditValue ?>"<?php echo $cartvalores->codchq->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->codchq->ViewAttributes() ?>>
<?php echo $cartvalores->codchq->ListViewValue() ?></span>
<input type="hidden" data-field="x_codchq" name="x<?php echo $cartvalores_grid->RowIndex ?>_codchq" id="x<?php echo $cartvalores_grid->RowIndex ?>_codchq" value="<?php echo ew_HtmlEncode($cartvalores->codchq->FormValue) ?>">
<input type="hidden" data-field="x_codchq" name="o<?php echo $cartvalores_grid->RowIndex ?>_codchq" id="o<?php echo $cartvalores_grid->RowIndex ?>_codchq" value="<?php echo ew_HtmlEncode($cartvalores->codchq->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->codpais->Visible) { // codpais ?>
		<td data-name="codpais"<?php echo $cartvalores->codpais->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_codpais" class="form-group cartvalores_codpais">
<select data-field="x_codpais" id="x<?php echo $cartvalores_grid->RowIndex ?>_codpais" name="x<?php echo $cartvalores_grid->RowIndex ?>_codpais"<?php echo $cartvalores->codpais->EditAttributes() ?>>
<?php
if (is_array($cartvalores->codpais->EditValue)) {
	$arwrk = $cartvalores->codpais->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->codpais->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->codpais->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paises`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->codpais, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `descripcion` DESC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_codpais" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_codpais" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=19">
</span>
<input type="hidden" data-field="x_codpais" name="o<?php echo $cartvalores_grid->RowIndex ?>_codpais" id="o<?php echo $cartvalores_grid->RowIndex ?>_codpais" value="<?php echo ew_HtmlEncode($cartvalores->codpais->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_codpais" class="form-group cartvalores_codpais">
<select data-field="x_codpais" id="x<?php echo $cartvalores_grid->RowIndex ?>_codpais" name="x<?php echo $cartvalores_grid->RowIndex ?>_codpais"<?php echo $cartvalores->codpais->EditAttributes() ?>>
<?php
if (is_array($cartvalores->codpais->EditValue)) {
	$arwrk = $cartvalores->codpais->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->codpais->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->codpais->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paises`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->codpais, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `descripcion` DESC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_codpais" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_codpais" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=19">
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->codpais->ViewAttributes() ?>>
<?php echo $cartvalores->codpais->ListViewValue() ?></span>
<input type="hidden" data-field="x_codpais" name="x<?php echo $cartvalores_grid->RowIndex ?>_codpais" id="x<?php echo $cartvalores_grid->RowIndex ?>_codpais" value="<?php echo ew_HtmlEncode($cartvalores->codpais->FormValue) ?>">
<input type="hidden" data-field="x_codpais" name="o<?php echo $cartvalores_grid->RowIndex ?>_codpais" id="o<?php echo $cartvalores_grid->RowIndex ?>_codpais" value="<?php echo ew_HtmlEncode($cartvalores->codpais->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->importe->Visible) { // importe ?>
		<td data-name="importe"<?php echo $cartvalores->importe->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_importe" class="form-group cartvalores_importe">
<input type="text" data-field="x_importe" name="x<?php echo $cartvalores_grid->RowIndex ?>_importe" id="x<?php echo $cartvalores_grid->RowIndex ?>_importe" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->importe->PlaceHolder) ?>" value="<?php echo $cartvalores->importe->EditValue ?>"<?php echo $cartvalores->importe->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_importe" name="o<?php echo $cartvalores_grid->RowIndex ?>_importe" id="o<?php echo $cartvalores_grid->RowIndex ?>_importe" value="<?php echo ew_HtmlEncode($cartvalores->importe->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_importe" class="form-group cartvalores_importe">
<input type="text" data-field="x_importe" name="x<?php echo $cartvalores_grid->RowIndex ?>_importe" id="x<?php echo $cartvalores_grid->RowIndex ?>_importe" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->importe->PlaceHolder) ?>" value="<?php echo $cartvalores->importe->EditValue ?>"<?php echo $cartvalores->importe->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->importe->ViewAttributes() ?>>
<?php echo $cartvalores->importe->ListViewValue() ?></span>
<input type="hidden" data-field="x_importe" name="x<?php echo $cartvalores_grid->RowIndex ?>_importe" id="x<?php echo $cartvalores_grid->RowIndex ?>_importe" value="<?php echo ew_HtmlEncode($cartvalores->importe->FormValue) ?>">
<input type="hidden" data-field="x_importe" name="o<?php echo $cartvalores_grid->RowIndex ?>_importe" id="o<?php echo $cartvalores_grid->RowIndex ?>_importe" value="<?php echo ew_HtmlEncode($cartvalores->importe->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->fechaemis->Visible) { // fechaemis ?>
		<td data-name="fechaemis"<?php echo $cartvalores->fechaemis->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_fechaemis" class="form-group cartvalores_fechaemis">
<input type="text" data-field="x_fechaemis" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechaemis" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechaemis" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechaemis->PlaceHolder) ?>" value="<?php echo $cartvalores->fechaemis->EditValue ?>"<?php echo $cartvalores->fechaemis->EditAttributes() ?>>
<?php if (!$cartvalores->fechaemis->ReadOnly && !$cartvalores->fechaemis->Disabled && @$cartvalores->fechaemis->EditAttrs["readonly"] == "" && @$cartvalores->fechaemis->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fcartvaloresgrid", "x<?php echo $cartvalores_grid->RowIndex ?>_fechaemis", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fechaemis" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechaemis" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechaemis" value="<?php echo ew_HtmlEncode($cartvalores->fechaemis->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_fechaemis" class="form-group cartvalores_fechaemis">
<input type="text" data-field="x_fechaemis" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechaemis" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechaemis" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechaemis->PlaceHolder) ?>" value="<?php echo $cartvalores->fechaemis->EditValue ?>"<?php echo $cartvalores->fechaemis->EditAttributes() ?>>
<?php if (!$cartvalores->fechaemis->ReadOnly && !$cartvalores->fechaemis->Disabled && @$cartvalores->fechaemis->EditAttrs["readonly"] == "" && @$cartvalores->fechaemis->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fcartvaloresgrid", "x<?php echo $cartvalores_grid->RowIndex ?>_fechaemis", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->fechaemis->ViewAttributes() ?>>
<?php echo $cartvalores->fechaemis->ListViewValue() ?></span>
<input type="hidden" data-field="x_fechaemis" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechaemis" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechaemis" value="<?php echo ew_HtmlEncode($cartvalores->fechaemis->FormValue) ?>">
<input type="hidden" data-field="x_fechaemis" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechaemis" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechaemis" value="<?php echo ew_HtmlEncode($cartvalores->fechaemis->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->fechapago->Visible) { // fechapago ?>
		<td data-name="fechapago"<?php echo $cartvalores->fechapago->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_fechapago" class="form-group cartvalores_fechapago">
<input type="text" data-field="x_fechapago" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechapago" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechapago" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechapago->PlaceHolder) ?>" value="<?php echo $cartvalores->fechapago->EditValue ?>"<?php echo $cartvalores->fechapago->EditAttributes() ?>>
<?php if (!$cartvalores->fechapago->ReadOnly && !$cartvalores->fechapago->Disabled && @$cartvalores->fechapago->EditAttrs["readonly"] == "" && @$cartvalores->fechapago->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fcartvaloresgrid", "x<?php echo $cartvalores_grid->RowIndex ?>_fechapago", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fechapago" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechapago" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechapago" value="<?php echo ew_HtmlEncode($cartvalores->fechapago->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_fechapago" class="form-group cartvalores_fechapago">
<input type="text" data-field="x_fechapago" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechapago" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechapago" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechapago->PlaceHolder) ?>" value="<?php echo $cartvalores->fechapago->EditValue ?>"<?php echo $cartvalores->fechapago->EditAttributes() ?>>
<?php if (!$cartvalores->fechapago->ReadOnly && !$cartvalores->fechapago->Disabled && @$cartvalores->fechapago->EditAttrs["readonly"] == "" && @$cartvalores->fechapago->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fcartvaloresgrid", "x<?php echo $cartvalores_grid->RowIndex ?>_fechapago", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->fechapago->ViewAttributes() ?>>
<?php echo $cartvalores->fechapago->ListViewValue() ?></span>
<input type="hidden" data-field="x_fechapago" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechapago" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechapago" value="<?php echo ew_HtmlEncode($cartvalores->fechapago->FormValue) ?>">
<input type="hidden" data-field="x_fechapago" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechapago" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechapago" value="<?php echo ew_HtmlEncode($cartvalores->fechapago->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->entrego->Visible) { // entrego ?>
		<td data-name="entrego"<?php echo $cartvalores->entrego->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_entrego" class="form-group cartvalores_entrego">
<input type="text" data-field="x_entrego" name="x<?php echo $cartvalores_grid->RowIndex ?>_entrego" id="x<?php echo $cartvalores_grid->RowIndex ?>_entrego" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->entrego->PlaceHolder) ?>" value="<?php echo $cartvalores->entrego->EditValue ?>"<?php echo $cartvalores->entrego->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_entrego" name="o<?php echo $cartvalores_grid->RowIndex ?>_entrego" id="o<?php echo $cartvalores_grid->RowIndex ?>_entrego" value="<?php echo ew_HtmlEncode($cartvalores->entrego->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_entrego" class="form-group cartvalores_entrego">
<input type="text" data-field="x_entrego" name="x<?php echo $cartvalores_grid->RowIndex ?>_entrego" id="x<?php echo $cartvalores_grid->RowIndex ?>_entrego" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->entrego->PlaceHolder) ?>" value="<?php echo $cartvalores->entrego->EditValue ?>"<?php echo $cartvalores->entrego->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->entrego->ViewAttributes() ?>>
<?php echo $cartvalores->entrego->ListViewValue() ?></span>
<input type="hidden" data-field="x_entrego" name="x<?php echo $cartvalores_grid->RowIndex ?>_entrego" id="x<?php echo $cartvalores_grid->RowIndex ?>_entrego" value="<?php echo ew_HtmlEncode($cartvalores->entrego->FormValue) ?>">
<input type="hidden" data-field="x_entrego" name="o<?php echo $cartvalores_grid->RowIndex ?>_entrego" id="o<?php echo $cartvalores_grid->RowIndex ?>_entrego" value="<?php echo ew_HtmlEncode($cartvalores->entrego->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->recibio->Visible) { // recibio ?>
		<td data-name="recibio"<?php echo $cartvalores->recibio->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_recibio" class="form-group cartvalores_recibio">
<input type="text" data-field="x_recibio" name="x<?php echo $cartvalores_grid->RowIndex ?>_recibio" id="x<?php echo $cartvalores_grid->RowIndex ?>_recibio" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->recibio->PlaceHolder) ?>" value="<?php echo $cartvalores->recibio->EditValue ?>"<?php echo $cartvalores->recibio->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_recibio" name="o<?php echo $cartvalores_grid->RowIndex ?>_recibio" id="o<?php echo $cartvalores_grid->RowIndex ?>_recibio" value="<?php echo ew_HtmlEncode($cartvalores->recibio->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_recibio" class="form-group cartvalores_recibio">
<input type="text" data-field="x_recibio" name="x<?php echo $cartvalores_grid->RowIndex ?>_recibio" id="x<?php echo $cartvalores_grid->RowIndex ?>_recibio" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->recibio->PlaceHolder) ?>" value="<?php echo $cartvalores->recibio->EditValue ?>"<?php echo $cartvalores->recibio->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->recibio->ViewAttributes() ?>>
<?php echo $cartvalores->recibio->ListViewValue() ?></span>
<input type="hidden" data-field="x_recibio" name="x<?php echo $cartvalores_grid->RowIndex ?>_recibio" id="x<?php echo $cartvalores_grid->RowIndex ?>_recibio" value="<?php echo ew_HtmlEncode($cartvalores->recibio->FormValue) ?>">
<input type="hidden" data-field="x_recibio" name="o<?php echo $cartvalores_grid->RowIndex ?>_recibio" id="o<?php echo $cartvalores_grid->RowIndex ?>_recibio" value="<?php echo ew_HtmlEncode($cartvalores->recibio->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->fechaingr->Visible) { // fechaingr ?>
		<td data-name="fechaingr"<?php echo $cartvalores->fechaingr->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_fechaingr" class="form-group cartvalores_fechaingr">
<input type="text" data-field="x_fechaingr" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechaingr" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechaingr" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechaingr->PlaceHolder) ?>" value="<?php echo $cartvalores->fechaingr->EditValue ?>"<?php echo $cartvalores->fechaingr->EditAttributes() ?>>
<?php if (!$cartvalores->fechaingr->ReadOnly && !$cartvalores->fechaingr->Disabled && @$cartvalores->fechaingr->EditAttrs["readonly"] == "" && @$cartvalores->fechaingr->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fcartvaloresgrid", "x<?php echo $cartvalores_grid->RowIndex ?>_fechaingr", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fechaingr" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechaingr" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechaingr" value="<?php echo ew_HtmlEncode($cartvalores->fechaingr->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_fechaingr" class="form-group cartvalores_fechaingr">
<input type="text" data-field="x_fechaingr" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechaingr" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechaingr" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechaingr->PlaceHolder) ?>" value="<?php echo $cartvalores->fechaingr->EditValue ?>"<?php echo $cartvalores->fechaingr->EditAttributes() ?>>
<?php if (!$cartvalores->fechaingr->ReadOnly && !$cartvalores->fechaingr->Disabled && @$cartvalores->fechaingr->EditAttrs["readonly"] == "" && @$cartvalores->fechaingr->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fcartvaloresgrid", "x<?php echo $cartvalores_grid->RowIndex ?>_fechaingr", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->fechaingr->ViewAttributes() ?>>
<?php echo $cartvalores->fechaingr->ListViewValue() ?></span>
<input type="hidden" data-field="x_fechaingr" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechaingr" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechaingr" value="<?php echo ew_HtmlEncode($cartvalores->fechaingr->FormValue) ?>">
<input type="hidden" data-field="x_fechaingr" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechaingr" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechaingr" value="<?php echo ew_HtmlEncode($cartvalores->fechaingr->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->fechaentrega->Visible) { // fechaentrega ?>
		<td data-name="fechaentrega"<?php echo $cartvalores->fechaentrega->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_fechaentrega" class="form-group cartvalores_fechaentrega">
<input type="text" data-field="x_fechaentrega" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechaentrega->PlaceHolder) ?>" value="<?php echo $cartvalores->fechaentrega->EditValue ?>"<?php echo $cartvalores->fechaentrega->EditAttributes() ?>>
<?php if (!$cartvalores->fechaentrega->ReadOnly && !$cartvalores->fechaentrega->Disabled && @$cartvalores->fechaentrega->EditAttrs["readonly"] == "" && @$cartvalores->fechaentrega->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fcartvaloresgrid", "x<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fechaentrega" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega" value="<?php echo ew_HtmlEncode($cartvalores->fechaentrega->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_fechaentrega" class="form-group cartvalores_fechaentrega">
<input type="text" data-field="x_fechaentrega" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechaentrega->PlaceHolder) ?>" value="<?php echo $cartvalores->fechaentrega->EditValue ?>"<?php echo $cartvalores->fechaentrega->EditAttributes() ?>>
<?php if (!$cartvalores->fechaentrega->ReadOnly && !$cartvalores->fechaentrega->Disabled && @$cartvalores->fechaentrega->EditAttrs["readonly"] == "" && @$cartvalores->fechaentrega->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fcartvaloresgrid", "x<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->fechaentrega->ViewAttributes() ?>>
<?php echo $cartvalores->fechaentrega->ListViewValue() ?></span>
<input type="hidden" data-field="x_fechaentrega" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega" value="<?php echo ew_HtmlEncode($cartvalores->fechaentrega->FormValue) ?>">
<input type="hidden" data-field="x_fechaentrega" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega" value="<?php echo ew_HtmlEncode($cartvalores->fechaentrega->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->tcomprel->Visible) { // tcomprel ?>
		<td data-name="tcomprel"<?php echo $cartvalores->tcomprel->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($cartvalores->tcomprel->getSessionValue() <> "") { ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_tcomprel" class="form-group cartvalores_tcomprel">
<span<?php echo $cartvalores->tcomprel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->tcomprel->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" value="<?php echo ew_HtmlEncode($cartvalores->tcomprel->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_tcomprel" class="form-group cartvalores_tcomprel">
<input type="text" data-field="x_tcomprel" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->tcomprel->PlaceHolder) ?>" value="<?php echo $cartvalores->tcomprel->EditValue ?>"<?php echo $cartvalores->tcomprel->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-field="x_tcomprel" name="o<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" id="o<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" value="<?php echo ew_HtmlEncode($cartvalores->tcomprel->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($cartvalores->tcomprel->getSessionValue() <> "") { ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_tcomprel" class="form-group cartvalores_tcomprel">
<span<?php echo $cartvalores->tcomprel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->tcomprel->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" value="<?php echo ew_HtmlEncode($cartvalores->tcomprel->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_tcomprel" class="form-group cartvalores_tcomprel">
<input type="text" data-field="x_tcomprel" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->tcomprel->PlaceHolder) ?>" value="<?php echo $cartvalores->tcomprel->EditValue ?>"<?php echo $cartvalores->tcomprel->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->tcomprel->ViewAttributes() ?>>
<?php echo $cartvalores->tcomprel->ListViewValue() ?></span>
<input type="hidden" data-field="x_tcomprel" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" value="<?php echo ew_HtmlEncode($cartvalores->tcomprel->FormValue) ?>">
<input type="hidden" data-field="x_tcomprel" name="o<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" id="o<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" value="<?php echo ew_HtmlEncode($cartvalores->tcomprel->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->serierel->Visible) { // serierel ?>
		<td data-name="serierel"<?php echo $cartvalores->serierel->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($cartvalores->serierel->getSessionValue() <> "") { ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_serierel" class="form-group cartvalores_serierel">
<span<?php echo $cartvalores->serierel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->serierel->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $cartvalores_grid->RowIndex ?>_serierel" name="x<?php echo $cartvalores_grid->RowIndex ?>_serierel" value="<?php echo ew_HtmlEncode($cartvalores->serierel->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_serierel" class="form-group cartvalores_serierel">
<input type="text" data-field="x_serierel" name="x<?php echo $cartvalores_grid->RowIndex ?>_serierel" id="x<?php echo $cartvalores_grid->RowIndex ?>_serierel" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->serierel->PlaceHolder) ?>" value="<?php echo $cartvalores->serierel->EditValue ?>"<?php echo $cartvalores->serierel->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-field="x_serierel" name="o<?php echo $cartvalores_grid->RowIndex ?>_serierel" id="o<?php echo $cartvalores_grid->RowIndex ?>_serierel" value="<?php echo ew_HtmlEncode($cartvalores->serierel->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($cartvalores->serierel->getSessionValue() <> "") { ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_serierel" class="form-group cartvalores_serierel">
<span<?php echo $cartvalores->serierel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->serierel->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $cartvalores_grid->RowIndex ?>_serierel" name="x<?php echo $cartvalores_grid->RowIndex ?>_serierel" value="<?php echo ew_HtmlEncode($cartvalores->serierel->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_serierel" class="form-group cartvalores_serierel">
<input type="text" data-field="x_serierel" name="x<?php echo $cartvalores_grid->RowIndex ?>_serierel" id="x<?php echo $cartvalores_grid->RowIndex ?>_serierel" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->serierel->PlaceHolder) ?>" value="<?php echo $cartvalores->serierel->EditValue ?>"<?php echo $cartvalores->serierel->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->serierel->ViewAttributes() ?>>
<?php echo $cartvalores->serierel->ListViewValue() ?></span>
<input type="hidden" data-field="x_serierel" name="x<?php echo $cartvalores_grid->RowIndex ?>_serierel" id="x<?php echo $cartvalores_grid->RowIndex ?>_serierel" value="<?php echo ew_HtmlEncode($cartvalores->serierel->FormValue) ?>">
<input type="hidden" data-field="x_serierel" name="o<?php echo $cartvalores_grid->RowIndex ?>_serierel" id="o<?php echo $cartvalores_grid->RowIndex ?>_serierel" value="<?php echo ew_HtmlEncode($cartvalores->serierel->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->ncomprel->Visible) { // ncomprel ?>
		<td data-name="ncomprel"<?php echo $cartvalores->ncomprel->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($cartvalores->ncomprel->getSessionValue() <> "") { ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_ncomprel" class="form-group cartvalores_ncomprel">
<span<?php echo $cartvalores->ncomprel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->ncomprel->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" value="<?php echo ew_HtmlEncode($cartvalores->ncomprel->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_ncomprel" class="form-group cartvalores_ncomprel">
<input type="text" data-field="x_ncomprel" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->ncomprel->PlaceHolder) ?>" value="<?php echo $cartvalores->ncomprel->EditValue ?>"<?php echo $cartvalores->ncomprel->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-field="x_ncomprel" name="o<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" id="o<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" value="<?php echo ew_HtmlEncode($cartvalores->ncomprel->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($cartvalores->ncomprel->getSessionValue() <> "") { ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_ncomprel" class="form-group cartvalores_ncomprel">
<span<?php echo $cartvalores->ncomprel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->ncomprel->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" value="<?php echo ew_HtmlEncode($cartvalores->ncomprel->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_ncomprel" class="form-group cartvalores_ncomprel">
<input type="text" data-field="x_ncomprel" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->ncomprel->PlaceHolder) ?>" value="<?php echo $cartvalores->ncomprel->EditValue ?>"<?php echo $cartvalores->ncomprel->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->ncomprel->ViewAttributes() ?>>
<?php echo $cartvalores->ncomprel->ListViewValue() ?></span>
<input type="hidden" data-field="x_ncomprel" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" value="<?php echo ew_HtmlEncode($cartvalores->ncomprel->FormValue) ?>">
<input type="hidden" data-field="x_ncomprel" name="o<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" id="o<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" value="<?php echo ew_HtmlEncode($cartvalores->ncomprel->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $cartvalores->estado->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_estado" class="form-group cartvalores_estado">
<select data-field="x_estado" id="x<?php echo $cartvalores_grid->RowIndex ?>_estado" name="x<?php echo $cartvalores_grid->RowIndex ?>_estado"<?php echo $cartvalores->estado->EditAttributes() ?>>
<?php
if (is_array($cartvalores->estado->EditValue)) {
	$arwrk = $cartvalores->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->estado->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_estado" name="o<?php echo $cartvalores_grid->RowIndex ?>_estado" id="o<?php echo $cartvalores_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($cartvalores->estado->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_estado" class="form-group cartvalores_estado">
<select data-field="x_estado" id="x<?php echo $cartvalores_grid->RowIndex ?>_estado" name="x<?php echo $cartvalores_grid->RowIndex ?>_estado"<?php echo $cartvalores->estado->EditAttributes() ?>>
<?php
if (is_array($cartvalores->estado->EditValue)) {
	$arwrk = $cartvalores->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->estado->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->estado->ViewAttributes() ?>>
<?php echo $cartvalores->estado->ListViewValue() ?></span>
<input type="hidden" data-field="x_estado" name="x<?php echo $cartvalores_grid->RowIndex ?>_estado" id="x<?php echo $cartvalores_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($cartvalores->estado->FormValue) ?>">
<input type="hidden" data-field="x_estado" name="o<?php echo $cartvalores_grid->RowIndex ?>_estado" id="o<?php echo $cartvalores_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($cartvalores->estado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->moneda->Visible) { // moneda ?>
		<td data-name="moneda"<?php echo $cartvalores->moneda->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_moneda" class="form-group cartvalores_moneda">
<input type="text" data-field="x_moneda" name="x<?php echo $cartvalores_grid->RowIndex ?>_moneda" id="x<?php echo $cartvalores_grid->RowIndex ?>_moneda" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->moneda->PlaceHolder) ?>" value="<?php echo $cartvalores->moneda->EditValue ?>"<?php echo $cartvalores->moneda->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_moneda" name="o<?php echo $cartvalores_grid->RowIndex ?>_moneda" id="o<?php echo $cartvalores_grid->RowIndex ?>_moneda" value="<?php echo ew_HtmlEncode($cartvalores->moneda->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_moneda" class="form-group cartvalores_moneda">
<input type="text" data-field="x_moneda" name="x<?php echo $cartvalores_grid->RowIndex ?>_moneda" id="x<?php echo $cartvalores_grid->RowIndex ?>_moneda" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->moneda->PlaceHolder) ?>" value="<?php echo $cartvalores->moneda->EditValue ?>"<?php echo $cartvalores->moneda->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->moneda->ViewAttributes() ?>>
<?php echo $cartvalores->moneda->ListViewValue() ?></span>
<input type="hidden" data-field="x_moneda" name="x<?php echo $cartvalores_grid->RowIndex ?>_moneda" id="x<?php echo $cartvalores_grid->RowIndex ?>_moneda" value="<?php echo ew_HtmlEncode($cartvalores->moneda->FormValue) ?>">
<input type="hidden" data-field="x_moneda" name="o<?php echo $cartvalores_grid->RowIndex ?>_moneda" id="o<?php echo $cartvalores_grid->RowIndex ?>_moneda" value="<?php echo ew_HtmlEncode($cartvalores->moneda->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->fechahora->Visible) { // fechahora ?>
		<td data-name="fechahora"<?php echo $cartvalores->fechahora->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_fechahora" class="form-group cartvalores_fechahora">
<input type="text" data-field="x_fechahora" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechahora" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechahora" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechahora->PlaceHolder) ?>" value="<?php echo $cartvalores->fechahora->EditValue ?>"<?php echo $cartvalores->fechahora->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_fechahora" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechahora" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechahora" value="<?php echo ew_HtmlEncode($cartvalores->fechahora->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_fechahora" class="form-group cartvalores_fechahora">
<input type="text" data-field="x_fechahora" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechahora" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechahora" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechahora->PlaceHolder) ?>" value="<?php echo $cartvalores->fechahora->EditValue ?>"<?php echo $cartvalores->fechahora->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->fechahora->ViewAttributes() ?>>
<?php echo $cartvalores->fechahora->ListViewValue() ?></span>
<input type="hidden" data-field="x_fechahora" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechahora" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechahora" value="<?php echo ew_HtmlEncode($cartvalores->fechahora->FormValue) ?>">
<input type="hidden" data-field="x_fechahora" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechahora" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechahora" value="<?php echo ew_HtmlEncode($cartvalores->fechahora->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->usuario->Visible) { // usuario ?>
		<td data-name="usuario"<?php echo $cartvalores->usuario->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_usuario" class="form-group cartvalores_usuario">
<input type="text" data-field="x_usuario" name="x<?php echo $cartvalores_grid->RowIndex ?>_usuario" id="x<?php echo $cartvalores_grid->RowIndex ?>_usuario" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->usuario->PlaceHolder) ?>" value="<?php echo $cartvalores->usuario->EditValue ?>"<?php echo $cartvalores->usuario->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_usuario" name="o<?php echo $cartvalores_grid->RowIndex ?>_usuario" id="o<?php echo $cartvalores_grid->RowIndex ?>_usuario" value="<?php echo ew_HtmlEncode($cartvalores->usuario->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_usuario" class="form-group cartvalores_usuario">
<input type="text" data-field="x_usuario" name="x<?php echo $cartvalores_grid->RowIndex ?>_usuario" id="x<?php echo $cartvalores_grid->RowIndex ?>_usuario" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->usuario->PlaceHolder) ?>" value="<?php echo $cartvalores->usuario->EditValue ?>"<?php echo $cartvalores->usuario->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->usuario->ViewAttributes() ?>>
<?php echo $cartvalores->usuario->ListViewValue() ?></span>
<input type="hidden" data-field="x_usuario" name="x<?php echo $cartvalores_grid->RowIndex ?>_usuario" id="x<?php echo $cartvalores_grid->RowIndex ?>_usuario" value="<?php echo ew_HtmlEncode($cartvalores->usuario->FormValue) ?>">
<input type="hidden" data-field="x_usuario" name="o<?php echo $cartvalores_grid->RowIndex ?>_usuario" id="o<?php echo $cartvalores_grid->RowIndex ?>_usuario" value="<?php echo ew_HtmlEncode($cartvalores->usuario->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->tcompsal->Visible) { // tcompsal ?>
		<td data-name="tcompsal"<?php echo $cartvalores->tcompsal->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_tcompsal" class="form-group cartvalores_tcompsal">
<input type="text" data-field="x_tcompsal" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcompsal" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcompsal" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->tcompsal->PlaceHolder) ?>" value="<?php echo $cartvalores->tcompsal->EditValue ?>"<?php echo $cartvalores->tcompsal->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_tcompsal" name="o<?php echo $cartvalores_grid->RowIndex ?>_tcompsal" id="o<?php echo $cartvalores_grid->RowIndex ?>_tcompsal" value="<?php echo ew_HtmlEncode($cartvalores->tcompsal->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_tcompsal" class="form-group cartvalores_tcompsal">
<input type="text" data-field="x_tcompsal" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcompsal" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcompsal" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->tcompsal->PlaceHolder) ?>" value="<?php echo $cartvalores->tcompsal->EditValue ?>"<?php echo $cartvalores->tcompsal->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->tcompsal->ViewAttributes() ?>>
<?php echo $cartvalores->tcompsal->ListViewValue() ?></span>
<input type="hidden" data-field="x_tcompsal" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcompsal" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcompsal" value="<?php echo ew_HtmlEncode($cartvalores->tcompsal->FormValue) ?>">
<input type="hidden" data-field="x_tcompsal" name="o<?php echo $cartvalores_grid->RowIndex ?>_tcompsal" id="o<?php echo $cartvalores_grid->RowIndex ?>_tcompsal" value="<?php echo ew_HtmlEncode($cartvalores->tcompsal->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->seriesal->Visible) { // seriesal ?>
		<td data-name="seriesal"<?php echo $cartvalores->seriesal->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_seriesal" class="form-group cartvalores_seriesal">
<input type="text" data-field="x_seriesal" name="x<?php echo $cartvalores_grid->RowIndex ?>_seriesal" id="x<?php echo $cartvalores_grid->RowIndex ?>_seriesal" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->seriesal->PlaceHolder) ?>" value="<?php echo $cartvalores->seriesal->EditValue ?>"<?php echo $cartvalores->seriesal->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_seriesal" name="o<?php echo $cartvalores_grid->RowIndex ?>_seriesal" id="o<?php echo $cartvalores_grid->RowIndex ?>_seriesal" value="<?php echo ew_HtmlEncode($cartvalores->seriesal->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_seriesal" class="form-group cartvalores_seriesal">
<input type="text" data-field="x_seriesal" name="x<?php echo $cartvalores_grid->RowIndex ?>_seriesal" id="x<?php echo $cartvalores_grid->RowIndex ?>_seriesal" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->seriesal->PlaceHolder) ?>" value="<?php echo $cartvalores->seriesal->EditValue ?>"<?php echo $cartvalores->seriesal->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->seriesal->ViewAttributes() ?>>
<?php echo $cartvalores->seriesal->ListViewValue() ?></span>
<input type="hidden" data-field="x_seriesal" name="x<?php echo $cartvalores_grid->RowIndex ?>_seriesal" id="x<?php echo $cartvalores_grid->RowIndex ?>_seriesal" value="<?php echo ew_HtmlEncode($cartvalores->seriesal->FormValue) ?>">
<input type="hidden" data-field="x_seriesal" name="o<?php echo $cartvalores_grid->RowIndex ?>_seriesal" id="o<?php echo $cartvalores_grid->RowIndex ?>_seriesal" value="<?php echo ew_HtmlEncode($cartvalores->seriesal->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->ncompsal->Visible) { // ncompsal ?>
		<td data-name="ncompsal"<?php echo $cartvalores->ncompsal->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_ncompsal" class="form-group cartvalores_ncompsal">
<input type="text" data-field="x_ncompsal" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncompsal" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncompsal" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->ncompsal->PlaceHolder) ?>" value="<?php echo $cartvalores->ncompsal->EditValue ?>"<?php echo $cartvalores->ncompsal->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_ncompsal" name="o<?php echo $cartvalores_grid->RowIndex ?>_ncompsal" id="o<?php echo $cartvalores_grid->RowIndex ?>_ncompsal" value="<?php echo ew_HtmlEncode($cartvalores->ncompsal->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_ncompsal" class="form-group cartvalores_ncompsal">
<input type="text" data-field="x_ncompsal" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncompsal" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncompsal" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->ncompsal->PlaceHolder) ?>" value="<?php echo $cartvalores->ncompsal->EditValue ?>"<?php echo $cartvalores->ncompsal->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->ncompsal->ViewAttributes() ?>>
<?php echo $cartvalores->ncompsal->ListViewValue() ?></span>
<input type="hidden" data-field="x_ncompsal" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncompsal" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncompsal" value="<?php echo ew_HtmlEncode($cartvalores->ncompsal->FormValue) ?>">
<input type="hidden" data-field="x_ncompsal" name="o<?php echo $cartvalores_grid->RowIndex ?>_ncompsal" id="o<?php echo $cartvalores_grid->RowIndex ?>_ncompsal" value="<?php echo ew_HtmlEncode($cartvalores->ncompsal->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->codrem->Visible) { // codrem ?>
		<td data-name="codrem"<?php echo $cartvalores->codrem->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_codrem" class="form-group cartvalores_codrem">
<select data-field="x_codrem" id="x<?php echo $cartvalores_grid->RowIndex ?>_codrem" name="x<?php echo $cartvalores_grid->RowIndex ?>_codrem"<?php echo $cartvalores->codrem->EditAttributes() ?>>
<?php
if (is_array($cartvalores->codrem->EditValue)) {
	$arwrk = $cartvalores->codrem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->codrem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$cartvalores->codrem) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->codrem->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `ncomp`, `ncomp` AS `DispFld`, `direccion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `remates`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->codrem, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `ncomp` DESC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_codrem" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_codrem" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`ncomp` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_codrem" name="o<?php echo $cartvalores_grid->RowIndex ?>_codrem" id="o<?php echo $cartvalores_grid->RowIndex ?>_codrem" value="<?php echo ew_HtmlEncode($cartvalores->codrem->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_codrem" class="form-group cartvalores_codrem">
<select data-field="x_codrem" id="x<?php echo $cartvalores_grid->RowIndex ?>_codrem" name="x<?php echo $cartvalores_grid->RowIndex ?>_codrem"<?php echo $cartvalores->codrem->EditAttributes() ?>>
<?php
if (is_array($cartvalores->codrem->EditValue)) {
	$arwrk = $cartvalores->codrem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->codrem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$cartvalores->codrem) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->codrem->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `ncomp`, `ncomp` AS `DispFld`, `direccion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `remates`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->codrem, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `ncomp` DESC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_codrem" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_codrem" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`ncomp` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->codrem->ViewAttributes() ?>>
<?php echo $cartvalores->codrem->ListViewValue() ?></span>
<input type="hidden" data-field="x_codrem" name="x<?php echo $cartvalores_grid->RowIndex ?>_codrem" id="x<?php echo $cartvalores_grid->RowIndex ?>_codrem" value="<?php echo ew_HtmlEncode($cartvalores->codrem->FormValue) ?>">
<input type="hidden" data-field="x_codrem" name="o<?php echo $cartvalores_grid->RowIndex ?>_codrem" id="o<?php echo $cartvalores_grid->RowIndex ?>_codrem" value="<?php echo ew_HtmlEncode($cartvalores->codrem->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->cotiz->Visible) { // cotiz ?>
		<td data-name="cotiz"<?php echo $cartvalores->cotiz->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_cotiz" class="form-group cartvalores_cotiz">
<input type="text" data-field="x_cotiz" name="x<?php echo $cartvalores_grid->RowIndex ?>_cotiz" id="x<?php echo $cartvalores_grid->RowIndex ?>_cotiz" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->cotiz->PlaceHolder) ?>" value="<?php echo $cartvalores->cotiz->EditValue ?>"<?php echo $cartvalores->cotiz->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_cotiz" name="o<?php echo $cartvalores_grid->RowIndex ?>_cotiz" id="o<?php echo $cartvalores_grid->RowIndex ?>_cotiz" value="<?php echo ew_HtmlEncode($cartvalores->cotiz->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_cotiz" class="form-group cartvalores_cotiz">
<input type="text" data-field="x_cotiz" name="x<?php echo $cartvalores_grid->RowIndex ?>_cotiz" id="x<?php echo $cartvalores_grid->RowIndex ?>_cotiz" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->cotiz->PlaceHolder) ?>" value="<?php echo $cartvalores->cotiz->EditValue ?>"<?php echo $cartvalores->cotiz->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->cotiz->ViewAttributes() ?>>
<?php echo $cartvalores->cotiz->ListViewValue() ?></span>
<input type="hidden" data-field="x_cotiz" name="x<?php echo $cartvalores_grid->RowIndex ?>_cotiz" id="x<?php echo $cartvalores_grid->RowIndex ?>_cotiz" value="<?php echo ew_HtmlEncode($cartvalores->cotiz->FormValue) ?>">
<input type="hidden" data-field="x_cotiz" name="o<?php echo $cartvalores_grid->RowIndex ?>_cotiz" id="o<?php echo $cartvalores_grid->RowIndex ?>_cotiz" value="<?php echo ew_HtmlEncode($cartvalores->cotiz->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->usurel->Visible) { // usurel ?>
		<td data-name="usurel"<?php echo $cartvalores->usurel->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_usurel" class="form-group cartvalores_usurel">
<input type="text" data-field="x_usurel" name="x<?php echo $cartvalores_grid->RowIndex ?>_usurel" id="x<?php echo $cartvalores_grid->RowIndex ?>_usurel" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->usurel->PlaceHolder) ?>" value="<?php echo $cartvalores->usurel->EditValue ?>"<?php echo $cartvalores->usurel->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_usurel" name="o<?php echo $cartvalores_grid->RowIndex ?>_usurel" id="o<?php echo $cartvalores_grid->RowIndex ?>_usurel" value="<?php echo ew_HtmlEncode($cartvalores->usurel->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_usurel" class="form-group cartvalores_usurel">
<input type="text" data-field="x_usurel" name="x<?php echo $cartvalores_grid->RowIndex ?>_usurel" id="x<?php echo $cartvalores_grid->RowIndex ?>_usurel" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->usurel->PlaceHolder) ?>" value="<?php echo $cartvalores->usurel->EditValue ?>"<?php echo $cartvalores->usurel->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->usurel->ViewAttributes() ?>>
<?php echo $cartvalores->usurel->ListViewValue() ?></span>
<input type="hidden" data-field="x_usurel" name="x<?php echo $cartvalores_grid->RowIndex ?>_usurel" id="x<?php echo $cartvalores_grid->RowIndex ?>_usurel" value="<?php echo ew_HtmlEncode($cartvalores->usurel->FormValue) ?>">
<input type="hidden" data-field="x_usurel" name="o<?php echo $cartvalores_grid->RowIndex ?>_usurel" id="o<?php echo $cartvalores_grid->RowIndex ?>_usurel" value="<?php echo ew_HtmlEncode($cartvalores->usurel->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->fecharel->Visible) { // fecharel ?>
		<td data-name="fecharel"<?php echo $cartvalores->fecharel->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_fecharel" class="form-group cartvalores_fecharel">
<input type="text" data-field="x_fecharel" name="x<?php echo $cartvalores_grid->RowIndex ?>_fecharel" id="x<?php echo $cartvalores_grid->RowIndex ?>_fecharel" placeholder="<?php echo ew_HtmlEncode($cartvalores->fecharel->PlaceHolder) ?>" value="<?php echo $cartvalores->fecharel->EditValue ?>"<?php echo $cartvalores->fecharel->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_fecharel" name="o<?php echo $cartvalores_grid->RowIndex ?>_fecharel" id="o<?php echo $cartvalores_grid->RowIndex ?>_fecharel" value="<?php echo ew_HtmlEncode($cartvalores->fecharel->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_fecharel" class="form-group cartvalores_fecharel">
<input type="text" data-field="x_fecharel" name="x<?php echo $cartvalores_grid->RowIndex ?>_fecharel" id="x<?php echo $cartvalores_grid->RowIndex ?>_fecharel" placeholder="<?php echo ew_HtmlEncode($cartvalores->fecharel->PlaceHolder) ?>" value="<?php echo $cartvalores->fecharel->EditValue ?>"<?php echo $cartvalores->fecharel->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->fecharel->ViewAttributes() ?>>
<?php echo $cartvalores->fecharel->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecharel" name="x<?php echo $cartvalores_grid->RowIndex ?>_fecharel" id="x<?php echo $cartvalores_grid->RowIndex ?>_fecharel" value="<?php echo ew_HtmlEncode($cartvalores->fecharel->FormValue) ?>">
<input type="hidden" data-field="x_fecharel" name="o<?php echo $cartvalores_grid->RowIndex ?>_fecharel" id="o<?php echo $cartvalores_grid->RowIndex ?>_fecharel" value="<?php echo ew_HtmlEncode($cartvalores->fecharel->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->ususal->Visible) { // ususal ?>
		<td data-name="ususal"<?php echo $cartvalores->ususal->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_ususal" class="form-group cartvalores_ususal">
<input type="text" data-field="x_ususal" name="x<?php echo $cartvalores_grid->RowIndex ?>_ususal" id="x<?php echo $cartvalores_grid->RowIndex ?>_ususal" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->ususal->PlaceHolder) ?>" value="<?php echo $cartvalores->ususal->EditValue ?>"<?php echo $cartvalores->ususal->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_ususal" name="o<?php echo $cartvalores_grid->RowIndex ?>_ususal" id="o<?php echo $cartvalores_grid->RowIndex ?>_ususal" value="<?php echo ew_HtmlEncode($cartvalores->ususal->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_ususal" class="form-group cartvalores_ususal">
<input type="text" data-field="x_ususal" name="x<?php echo $cartvalores_grid->RowIndex ?>_ususal" id="x<?php echo $cartvalores_grid->RowIndex ?>_ususal" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->ususal->PlaceHolder) ?>" value="<?php echo $cartvalores->ususal->EditValue ?>"<?php echo $cartvalores->ususal->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->ususal->ViewAttributes() ?>>
<?php echo $cartvalores->ususal->ListViewValue() ?></span>
<input type="hidden" data-field="x_ususal" name="x<?php echo $cartvalores_grid->RowIndex ?>_ususal" id="x<?php echo $cartvalores_grid->RowIndex ?>_ususal" value="<?php echo ew_HtmlEncode($cartvalores->ususal->FormValue) ?>">
<input type="hidden" data-field="x_ususal" name="o<?php echo $cartvalores_grid->RowIndex ?>_ususal" id="o<?php echo $cartvalores_grid->RowIndex ?>_ususal" value="<?php echo ew_HtmlEncode($cartvalores->ususal->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cartvalores->fechasal->Visible) { // fechasal ?>
		<td data-name="fechasal"<?php echo $cartvalores->fechasal->CellAttributes() ?>>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_fechasal" class="form-group cartvalores_fechasal">
<input type="text" data-field="x_fechasal" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechasal" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechasal" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechasal->PlaceHolder) ?>" value="<?php echo $cartvalores->fechasal->EditValue ?>"<?php echo $cartvalores->fechasal->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_fechasal" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechasal" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechasal" value="<?php echo ew_HtmlEncode($cartvalores->fechasal->OldValue) ?>">
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cartvalores_grid->RowCnt ?>_cartvalores_fechasal" class="form-group cartvalores_fechasal">
<input type="text" data-field="x_fechasal" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechasal" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechasal" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechasal->PlaceHolder) ?>" value="<?php echo $cartvalores->fechasal->EditValue ?>"<?php echo $cartvalores->fechasal->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cartvalores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cartvalores->fechasal->ViewAttributes() ?>>
<?php echo $cartvalores->fechasal->ListViewValue() ?></span>
<input type="hidden" data-field="x_fechasal" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechasal" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechasal" value="<?php echo ew_HtmlEncode($cartvalores->fechasal->FormValue) ?>">
<input type="hidden" data-field="x_fechasal" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechasal" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechasal" value="<?php echo ew_HtmlEncode($cartvalores->fechasal->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$cartvalores_grid->ListOptions->Render("body", "right", $cartvalores_grid->RowCnt);
?>
	</tr>
<?php if ($cartvalores->RowType == EW_ROWTYPE_ADD || $cartvalores->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fcartvaloresgrid.UpdateOpts(<?php echo $cartvalores_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($cartvalores->CurrentAction <> "gridadd" || $cartvalores->CurrentMode == "copy")
		if (!$cartvalores_grid->Recordset->EOF) $cartvalores_grid->Recordset->MoveNext();
}
?>
<?php
	if ($cartvalores->CurrentMode == "add" || $cartvalores->CurrentMode == "copy" || $cartvalores->CurrentMode == "edit") {
		$cartvalores_grid->RowIndex = '$rowindex$';
		$cartvalores_grid->LoadDefaultValues();

		// Set row properties
		$cartvalores->ResetAttrs();
		$cartvalores->RowAttrs = array_merge($cartvalores->RowAttrs, array('data-rowindex'=>$cartvalores_grid->RowIndex, 'id'=>'r0_cartvalores', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($cartvalores->RowAttrs["class"], "ewTemplate");
		$cartvalores->RowType = EW_ROWTYPE_ADD;

		// Render row
		$cartvalores_grid->RenderRow();

		// Render list options
		$cartvalores_grid->RenderListOptions();
		$cartvalores_grid->StartRowCnt = 0;
?>
	<tr<?php echo $cartvalores->RowAttributes() ?>>
<?php

// Render list options (body, left)
$cartvalores_grid->ListOptions->Render("body", "left", $cartvalores_grid->RowIndex);
?>
	<?php if ($cartvalores->codnum->Visible) { // codnum ?>
		<td data-name="codnum">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_codnum" class="form-group cartvalores_codnum">
<span<?php echo $cartvalores->codnum->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->codnum->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codnum" name="x<?php echo $cartvalores_grid->RowIndex ?>_codnum" id="x<?php echo $cartvalores_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($cartvalores->codnum->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codnum" name="o<?php echo $cartvalores_grid->RowIndex ?>_codnum" id="o<?php echo $cartvalores_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($cartvalores->codnum->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->tcomp->Visible) { // tcomp ?>
		<td data-name="tcomp">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_tcomp" class="form-group cartvalores_tcomp">
<select data-field="x_tcomp" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcomp" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcomp"<?php echo $cartvalores->tcomp->EditAttributes() ?>>
<?php
if (is_array($cartvalores->tcomp->EditValue)) {
	$arwrk = $cartvalores->tcomp->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->tcomp->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->tcomp->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipcomp`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->tcomp, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `descripcion` ASC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_tcomp" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_tcomp" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_tcomp" class="form-group cartvalores_tcomp">
<span<?php echo $cartvalores->tcomp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->tcomp->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_tcomp" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcomp" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($cartvalores->tcomp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_tcomp" name="o<?php echo $cartvalores_grid->RowIndex ?>_tcomp" id="o<?php echo $cartvalores_grid->RowIndex ?>_tcomp" value="<?php echo ew_HtmlEncode($cartvalores->tcomp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->serie->Visible) { // serie ?>
		<td data-name="serie">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_serie" class="form-group cartvalores_serie">
<select data-field="x_serie" id="x<?php echo $cartvalores_grid->RowIndex ?>_serie" name="x<?php echo $cartvalores_grid->RowIndex ?>_serie"<?php echo $cartvalores->serie->EditAttributes() ?>>
<?php
if (is_array($cartvalores->serie->EditValue)) {
	$arwrk = $cartvalores->serie->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->serie->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->serie->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `series`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->serie, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `descripcion` ASC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_serie" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_serie" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_serie" class="form-group cartvalores_serie">
<span<?php echo $cartvalores->serie->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->serie->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_serie" name="x<?php echo $cartvalores_grid->RowIndex ?>_serie" id="x<?php echo $cartvalores_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($cartvalores->serie->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_serie" name="o<?php echo $cartvalores_grid->RowIndex ?>_serie" id="o<?php echo $cartvalores_grid->RowIndex ?>_serie" value="<?php echo ew_HtmlEncode($cartvalores->serie->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->ncomp->Visible) { // ncomp ?>
		<td data-name="ncomp">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_ncomp" class="form-group cartvalores_ncomp">
<input type="text" data-field="x_ncomp" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncomp" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncomp" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->ncomp->PlaceHolder) ?>" value="<?php echo $cartvalores->ncomp->EditValue ?>"<?php echo $cartvalores->ncomp->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_ncomp" class="form-group cartvalores_ncomp">
<span<?php echo $cartvalores->ncomp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->ncomp->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_ncomp" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncomp" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($cartvalores->ncomp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ncomp" name="o<?php echo $cartvalores_grid->RowIndex ?>_ncomp" id="o<?php echo $cartvalores_grid->RowIndex ?>_ncomp" value="<?php echo ew_HtmlEncode($cartvalores->ncomp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->codban->Visible) { // codban ?>
		<td data-name="codban">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_codban" class="form-group cartvalores_codban">
<?php $cartvalores->codban->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x" . $cartvalores_grid->RowIndex . "_codsuc']); " . @$cartvalores->codban->EditAttrs["onchange"]; ?>
<select data-field="x_codban" id="x<?php echo $cartvalores_grid->RowIndex ?>_codban" name="x<?php echo $cartvalores_grid->RowIndex ?>_codban"<?php echo $cartvalores->codban->EditAttributes() ?>>
<?php
if (is_array($cartvalores->codban->EditValue)) {
	$arwrk = $cartvalores->codban->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->codban->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->codban->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bancos`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->codban, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `nombre` ASC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_codban" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_codban" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_codban" class="form-group cartvalores_codban">
<span<?php echo $cartvalores->codban->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->codban->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codban" name="x<?php echo $cartvalores_grid->RowIndex ?>_codban" id="x<?php echo $cartvalores_grid->RowIndex ?>_codban" value="<?php echo ew_HtmlEncode($cartvalores->codban->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codban" name="o<?php echo $cartvalores_grid->RowIndex ?>_codban" id="o<?php echo $cartvalores_grid->RowIndex ?>_codban" value="<?php echo ew_HtmlEncode($cartvalores->codban->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->codsuc->Visible) { // codsuc ?>
		<td data-name="codsuc">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_codsuc" class="form-group cartvalores_codsuc">
<select data-field="x_codsuc" id="x<?php echo $cartvalores_grid->RowIndex ?>_codsuc" name="x<?php echo $cartvalores_grid->RowIndex ?>_codsuc"<?php echo $cartvalores->codsuc->EditAttributes() ?>>
<?php
if (is_array($cartvalores->codsuc->EditValue)) {
	$arwrk = $cartvalores->codsuc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->codsuc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->codsuc->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sucbancos`";
 $sWhereWrk = "{filter}";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->codsuc, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `nombre` ASC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_codsuc" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_codsuc" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=3&amp;f1=<?php echo ew_Encrypt("`codbanco` IN ({filter_value})"); ?>&amp;t1=3">
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_codsuc" class="form-group cartvalores_codsuc">
<span<?php echo $cartvalores->codsuc->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->codsuc->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codsuc" name="x<?php echo $cartvalores_grid->RowIndex ?>_codsuc" id="x<?php echo $cartvalores_grid->RowIndex ?>_codsuc" value="<?php echo ew_HtmlEncode($cartvalores->codsuc->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codsuc" name="o<?php echo $cartvalores_grid->RowIndex ?>_codsuc" id="o<?php echo $cartvalores_grid->RowIndex ?>_codsuc" value="<?php echo ew_HtmlEncode($cartvalores->codsuc->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->codcta->Visible) { // codcta ?>
		<td data-name="codcta">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_codcta" class="form-group cartvalores_codcta">
<input type="text" data-field="x_codcta" name="x<?php echo $cartvalores_grid->RowIndex ?>_codcta" id="x<?php echo $cartvalores_grid->RowIndex ?>_codcta" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($cartvalores->codcta->PlaceHolder) ?>" value="<?php echo $cartvalores->codcta->EditValue ?>"<?php echo $cartvalores->codcta->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_codcta" class="form-group cartvalores_codcta">
<span<?php echo $cartvalores->codcta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->codcta->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codcta" name="x<?php echo $cartvalores_grid->RowIndex ?>_codcta" id="x<?php echo $cartvalores_grid->RowIndex ?>_codcta" value="<?php echo ew_HtmlEncode($cartvalores->codcta->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codcta" name="o<?php echo $cartvalores_grid->RowIndex ?>_codcta" id="o<?php echo $cartvalores_grid->RowIndex ?>_codcta" value="<?php echo ew_HtmlEncode($cartvalores->codcta->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->tipcta->Visible) { // tipcta ?>
		<td data-name="tipcta">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_tipcta" class="form-group cartvalores_tipcta">
<input type="text" data-field="x_tipcta" name="x<?php echo $cartvalores_grid->RowIndex ?>_tipcta" id="x<?php echo $cartvalores_grid->RowIndex ?>_tipcta" size="30" maxlength="3" placeholder="<?php echo ew_HtmlEncode($cartvalores->tipcta->PlaceHolder) ?>" value="<?php echo $cartvalores->tipcta->EditValue ?>"<?php echo $cartvalores->tipcta->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_tipcta" class="form-group cartvalores_tipcta">
<span<?php echo $cartvalores->tipcta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->tipcta->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_tipcta" name="x<?php echo $cartvalores_grid->RowIndex ?>_tipcta" id="x<?php echo $cartvalores_grid->RowIndex ?>_tipcta" value="<?php echo ew_HtmlEncode($cartvalores->tipcta->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_tipcta" name="o<?php echo $cartvalores_grid->RowIndex ?>_tipcta" id="o<?php echo $cartvalores_grid->RowIndex ?>_tipcta" value="<?php echo ew_HtmlEncode($cartvalores->tipcta->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->codchq->Visible) { // codchq ?>
		<td data-name="codchq">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_codchq" class="form-group cartvalores_codchq">
<input type="text" data-field="x_codchq" name="x<?php echo $cartvalores_grid->RowIndex ?>_codchq" id="x<?php echo $cartvalores_grid->RowIndex ?>_codchq" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($cartvalores->codchq->PlaceHolder) ?>" value="<?php echo $cartvalores->codchq->EditValue ?>"<?php echo $cartvalores->codchq->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_codchq" class="form-group cartvalores_codchq">
<span<?php echo $cartvalores->codchq->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->codchq->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codchq" name="x<?php echo $cartvalores_grid->RowIndex ?>_codchq" id="x<?php echo $cartvalores_grid->RowIndex ?>_codchq" value="<?php echo ew_HtmlEncode($cartvalores->codchq->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codchq" name="o<?php echo $cartvalores_grid->RowIndex ?>_codchq" id="o<?php echo $cartvalores_grid->RowIndex ?>_codchq" value="<?php echo ew_HtmlEncode($cartvalores->codchq->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->codpais->Visible) { // codpais ?>
		<td data-name="codpais">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_codpais" class="form-group cartvalores_codpais">
<select data-field="x_codpais" id="x<?php echo $cartvalores_grid->RowIndex ?>_codpais" name="x<?php echo $cartvalores_grid->RowIndex ?>_codpais"<?php echo $cartvalores->codpais->EditAttributes() ?>>
<?php
if (is_array($cartvalores->codpais->EditValue)) {
	$arwrk = $cartvalores->codpais->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->codpais->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->codpais->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paises`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->codpais, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `descripcion` DESC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_codpais" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_codpais" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`codnum` = {filter_value}"); ?>&amp;t0=19">
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_codpais" class="form-group cartvalores_codpais">
<span<?php echo $cartvalores->codpais->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->codpais->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codpais" name="x<?php echo $cartvalores_grid->RowIndex ?>_codpais" id="x<?php echo $cartvalores_grid->RowIndex ?>_codpais" value="<?php echo ew_HtmlEncode($cartvalores->codpais->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codpais" name="o<?php echo $cartvalores_grid->RowIndex ?>_codpais" id="o<?php echo $cartvalores_grid->RowIndex ?>_codpais" value="<?php echo ew_HtmlEncode($cartvalores->codpais->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->importe->Visible) { // importe ?>
		<td data-name="importe">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_importe" class="form-group cartvalores_importe">
<input type="text" data-field="x_importe" name="x<?php echo $cartvalores_grid->RowIndex ?>_importe" id="x<?php echo $cartvalores_grid->RowIndex ?>_importe" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->importe->PlaceHolder) ?>" value="<?php echo $cartvalores->importe->EditValue ?>"<?php echo $cartvalores->importe->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_importe" class="form-group cartvalores_importe">
<span<?php echo $cartvalores->importe->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->importe->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_importe" name="x<?php echo $cartvalores_grid->RowIndex ?>_importe" id="x<?php echo $cartvalores_grid->RowIndex ?>_importe" value="<?php echo ew_HtmlEncode($cartvalores->importe->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_importe" name="o<?php echo $cartvalores_grid->RowIndex ?>_importe" id="o<?php echo $cartvalores_grid->RowIndex ?>_importe" value="<?php echo ew_HtmlEncode($cartvalores->importe->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->fechaemis->Visible) { // fechaemis ?>
		<td data-name="fechaemis">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_fechaemis" class="form-group cartvalores_fechaemis">
<input type="text" data-field="x_fechaemis" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechaemis" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechaemis" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechaemis->PlaceHolder) ?>" value="<?php echo $cartvalores->fechaemis->EditValue ?>"<?php echo $cartvalores->fechaemis->EditAttributes() ?>>
<?php if (!$cartvalores->fechaemis->ReadOnly && !$cartvalores->fechaemis->Disabled && @$cartvalores->fechaemis->EditAttrs["readonly"] == "" && @$cartvalores->fechaemis->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fcartvaloresgrid", "x<?php echo $cartvalores_grid->RowIndex ?>_fechaemis", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_fechaemis" class="form-group cartvalores_fechaemis">
<span<?php echo $cartvalores->fechaemis->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->fechaemis->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fechaemis" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechaemis" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechaemis" value="<?php echo ew_HtmlEncode($cartvalores->fechaemis->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fechaemis" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechaemis" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechaemis" value="<?php echo ew_HtmlEncode($cartvalores->fechaemis->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->fechapago->Visible) { // fechapago ?>
		<td data-name="fechapago">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_fechapago" class="form-group cartvalores_fechapago">
<input type="text" data-field="x_fechapago" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechapago" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechapago" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechapago->PlaceHolder) ?>" value="<?php echo $cartvalores->fechapago->EditValue ?>"<?php echo $cartvalores->fechapago->EditAttributes() ?>>
<?php if (!$cartvalores->fechapago->ReadOnly && !$cartvalores->fechapago->Disabled && @$cartvalores->fechapago->EditAttrs["readonly"] == "" && @$cartvalores->fechapago->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fcartvaloresgrid", "x<?php echo $cartvalores_grid->RowIndex ?>_fechapago", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_fechapago" class="form-group cartvalores_fechapago">
<span<?php echo $cartvalores->fechapago->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->fechapago->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fechapago" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechapago" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechapago" value="<?php echo ew_HtmlEncode($cartvalores->fechapago->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fechapago" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechapago" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechapago" value="<?php echo ew_HtmlEncode($cartvalores->fechapago->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->entrego->Visible) { // entrego ?>
		<td data-name="entrego">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_entrego" class="form-group cartvalores_entrego">
<input type="text" data-field="x_entrego" name="x<?php echo $cartvalores_grid->RowIndex ?>_entrego" id="x<?php echo $cartvalores_grid->RowIndex ?>_entrego" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->entrego->PlaceHolder) ?>" value="<?php echo $cartvalores->entrego->EditValue ?>"<?php echo $cartvalores->entrego->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_entrego" class="form-group cartvalores_entrego">
<span<?php echo $cartvalores->entrego->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->entrego->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_entrego" name="x<?php echo $cartvalores_grid->RowIndex ?>_entrego" id="x<?php echo $cartvalores_grid->RowIndex ?>_entrego" value="<?php echo ew_HtmlEncode($cartvalores->entrego->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_entrego" name="o<?php echo $cartvalores_grid->RowIndex ?>_entrego" id="o<?php echo $cartvalores_grid->RowIndex ?>_entrego" value="<?php echo ew_HtmlEncode($cartvalores->entrego->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->recibio->Visible) { // recibio ?>
		<td data-name="recibio">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_recibio" class="form-group cartvalores_recibio">
<input type="text" data-field="x_recibio" name="x<?php echo $cartvalores_grid->RowIndex ?>_recibio" id="x<?php echo $cartvalores_grid->RowIndex ?>_recibio" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->recibio->PlaceHolder) ?>" value="<?php echo $cartvalores->recibio->EditValue ?>"<?php echo $cartvalores->recibio->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_recibio" class="form-group cartvalores_recibio">
<span<?php echo $cartvalores->recibio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->recibio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_recibio" name="x<?php echo $cartvalores_grid->RowIndex ?>_recibio" id="x<?php echo $cartvalores_grid->RowIndex ?>_recibio" value="<?php echo ew_HtmlEncode($cartvalores->recibio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_recibio" name="o<?php echo $cartvalores_grid->RowIndex ?>_recibio" id="o<?php echo $cartvalores_grid->RowIndex ?>_recibio" value="<?php echo ew_HtmlEncode($cartvalores->recibio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->fechaingr->Visible) { // fechaingr ?>
		<td data-name="fechaingr">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_fechaingr" class="form-group cartvalores_fechaingr">
<input type="text" data-field="x_fechaingr" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechaingr" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechaingr" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechaingr->PlaceHolder) ?>" value="<?php echo $cartvalores->fechaingr->EditValue ?>"<?php echo $cartvalores->fechaingr->EditAttributes() ?>>
<?php if (!$cartvalores->fechaingr->ReadOnly && !$cartvalores->fechaingr->Disabled && @$cartvalores->fechaingr->EditAttrs["readonly"] == "" && @$cartvalores->fechaingr->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fcartvaloresgrid", "x<?php echo $cartvalores_grid->RowIndex ?>_fechaingr", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_fechaingr" class="form-group cartvalores_fechaingr">
<span<?php echo $cartvalores->fechaingr->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->fechaingr->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fechaingr" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechaingr" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechaingr" value="<?php echo ew_HtmlEncode($cartvalores->fechaingr->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fechaingr" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechaingr" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechaingr" value="<?php echo ew_HtmlEncode($cartvalores->fechaingr->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->fechaentrega->Visible) { // fechaentrega ?>
		<td data-name="fechaentrega">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_fechaentrega" class="form-group cartvalores_fechaentrega">
<input type="text" data-field="x_fechaentrega" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechaentrega->PlaceHolder) ?>" value="<?php echo $cartvalores->fechaentrega->EditValue ?>"<?php echo $cartvalores->fechaentrega->EditAttributes() ?>>
<?php if (!$cartvalores->fechaentrega->ReadOnly && !$cartvalores->fechaentrega->Disabled && @$cartvalores->fechaentrega->EditAttrs["readonly"] == "" && @$cartvalores->fechaentrega->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fcartvaloresgrid", "x<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_fechaentrega" class="form-group cartvalores_fechaentrega">
<span<?php echo $cartvalores->fechaentrega->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->fechaentrega->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fechaentrega" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega" value="<?php echo ew_HtmlEncode($cartvalores->fechaentrega->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fechaentrega" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechaentrega" value="<?php echo ew_HtmlEncode($cartvalores->fechaentrega->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->tcomprel->Visible) { // tcomprel ?>
		<td data-name="tcomprel">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<?php if ($cartvalores->tcomprel->getSessionValue() <> "") { ?>
<span id="el$rowindex$_cartvalores_tcomprel" class="form-group cartvalores_tcomprel">
<span<?php echo $cartvalores->tcomprel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->tcomprel->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" value="<?php echo ew_HtmlEncode($cartvalores->tcomprel->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_cartvalores_tcomprel" class="form-group cartvalores_tcomprel">
<input type="text" data-field="x_tcomprel" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->tcomprel->PlaceHolder) ?>" value="<?php echo $cartvalores->tcomprel->EditValue ?>"<?php echo $cartvalores->tcomprel->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_tcomprel" class="form-group cartvalores_tcomprel">
<span<?php echo $cartvalores->tcomprel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->tcomprel->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_tcomprel" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" value="<?php echo ew_HtmlEncode($cartvalores->tcomprel->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_tcomprel" name="o<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" id="o<?php echo $cartvalores_grid->RowIndex ?>_tcomprel" value="<?php echo ew_HtmlEncode($cartvalores->tcomprel->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->serierel->Visible) { // serierel ?>
		<td data-name="serierel">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<?php if ($cartvalores->serierel->getSessionValue() <> "") { ?>
<span id="el$rowindex$_cartvalores_serierel" class="form-group cartvalores_serierel">
<span<?php echo $cartvalores->serierel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->serierel->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $cartvalores_grid->RowIndex ?>_serierel" name="x<?php echo $cartvalores_grid->RowIndex ?>_serierel" value="<?php echo ew_HtmlEncode($cartvalores->serierel->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_cartvalores_serierel" class="form-group cartvalores_serierel">
<input type="text" data-field="x_serierel" name="x<?php echo $cartvalores_grid->RowIndex ?>_serierel" id="x<?php echo $cartvalores_grid->RowIndex ?>_serierel" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->serierel->PlaceHolder) ?>" value="<?php echo $cartvalores->serierel->EditValue ?>"<?php echo $cartvalores->serierel->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_serierel" class="form-group cartvalores_serierel">
<span<?php echo $cartvalores->serierel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->serierel->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_serierel" name="x<?php echo $cartvalores_grid->RowIndex ?>_serierel" id="x<?php echo $cartvalores_grid->RowIndex ?>_serierel" value="<?php echo ew_HtmlEncode($cartvalores->serierel->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_serierel" name="o<?php echo $cartvalores_grid->RowIndex ?>_serierel" id="o<?php echo $cartvalores_grid->RowIndex ?>_serierel" value="<?php echo ew_HtmlEncode($cartvalores->serierel->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->ncomprel->Visible) { // ncomprel ?>
		<td data-name="ncomprel">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<?php if ($cartvalores->ncomprel->getSessionValue() <> "") { ?>
<span id="el$rowindex$_cartvalores_ncomprel" class="form-group cartvalores_ncomprel">
<span<?php echo $cartvalores->ncomprel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->ncomprel->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" value="<?php echo ew_HtmlEncode($cartvalores->ncomprel->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_cartvalores_ncomprel" class="form-group cartvalores_ncomprel">
<input type="text" data-field="x_ncomprel" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->ncomprel->PlaceHolder) ?>" value="<?php echo $cartvalores->ncomprel->EditValue ?>"<?php echo $cartvalores->ncomprel->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_ncomprel" class="form-group cartvalores_ncomprel">
<span<?php echo $cartvalores->ncomprel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->ncomprel->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_ncomprel" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" value="<?php echo ew_HtmlEncode($cartvalores->ncomprel->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ncomprel" name="o<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" id="o<?php echo $cartvalores_grid->RowIndex ?>_ncomprel" value="<?php echo ew_HtmlEncode($cartvalores->ncomprel->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->estado->Visible) { // estado ?>
		<td data-name="estado">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_estado" class="form-group cartvalores_estado">
<select data-field="x_estado" id="x<?php echo $cartvalores_grid->RowIndex ?>_estado" name="x<?php echo $cartvalores_grid->RowIndex ?>_estado"<?php echo $cartvalores->estado->EditAttributes() ?>>
<?php
if (is_array($cartvalores->estado->EditValue)) {
	$arwrk = $cartvalores->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->estado->OldValue = "";
?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_estado" class="form-group cartvalores_estado">
<span<?php echo $cartvalores->estado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->estado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_estado" name="x<?php echo $cartvalores_grid->RowIndex ?>_estado" id="x<?php echo $cartvalores_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($cartvalores->estado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_estado" name="o<?php echo $cartvalores_grid->RowIndex ?>_estado" id="o<?php echo $cartvalores_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($cartvalores->estado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->moneda->Visible) { // moneda ?>
		<td data-name="moneda">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_moneda" class="form-group cartvalores_moneda">
<input type="text" data-field="x_moneda" name="x<?php echo $cartvalores_grid->RowIndex ?>_moneda" id="x<?php echo $cartvalores_grid->RowIndex ?>_moneda" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->moneda->PlaceHolder) ?>" value="<?php echo $cartvalores->moneda->EditValue ?>"<?php echo $cartvalores->moneda->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_moneda" class="form-group cartvalores_moneda">
<span<?php echo $cartvalores->moneda->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->moneda->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_moneda" name="x<?php echo $cartvalores_grid->RowIndex ?>_moneda" id="x<?php echo $cartvalores_grid->RowIndex ?>_moneda" value="<?php echo ew_HtmlEncode($cartvalores->moneda->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_moneda" name="o<?php echo $cartvalores_grid->RowIndex ?>_moneda" id="o<?php echo $cartvalores_grid->RowIndex ?>_moneda" value="<?php echo ew_HtmlEncode($cartvalores->moneda->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->fechahora->Visible) { // fechahora ?>
		<td data-name="fechahora">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_fechahora" class="form-group cartvalores_fechahora">
<input type="text" data-field="x_fechahora" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechahora" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechahora" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechahora->PlaceHolder) ?>" value="<?php echo $cartvalores->fechahora->EditValue ?>"<?php echo $cartvalores->fechahora->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_fechahora" class="form-group cartvalores_fechahora">
<span<?php echo $cartvalores->fechahora->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->fechahora->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fechahora" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechahora" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechahora" value="<?php echo ew_HtmlEncode($cartvalores->fechahora->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fechahora" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechahora" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechahora" value="<?php echo ew_HtmlEncode($cartvalores->fechahora->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->usuario->Visible) { // usuario ?>
		<td data-name="usuario">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_usuario" class="form-group cartvalores_usuario">
<input type="text" data-field="x_usuario" name="x<?php echo $cartvalores_grid->RowIndex ?>_usuario" id="x<?php echo $cartvalores_grid->RowIndex ?>_usuario" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->usuario->PlaceHolder) ?>" value="<?php echo $cartvalores->usuario->EditValue ?>"<?php echo $cartvalores->usuario->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_usuario" class="form-group cartvalores_usuario">
<span<?php echo $cartvalores->usuario->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->usuario->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_usuario" name="x<?php echo $cartvalores_grid->RowIndex ?>_usuario" id="x<?php echo $cartvalores_grid->RowIndex ?>_usuario" value="<?php echo ew_HtmlEncode($cartvalores->usuario->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_usuario" name="o<?php echo $cartvalores_grid->RowIndex ?>_usuario" id="o<?php echo $cartvalores_grid->RowIndex ?>_usuario" value="<?php echo ew_HtmlEncode($cartvalores->usuario->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->tcompsal->Visible) { // tcompsal ?>
		<td data-name="tcompsal">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_tcompsal" class="form-group cartvalores_tcompsal">
<input type="text" data-field="x_tcompsal" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcompsal" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcompsal" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->tcompsal->PlaceHolder) ?>" value="<?php echo $cartvalores->tcompsal->EditValue ?>"<?php echo $cartvalores->tcompsal->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_tcompsal" class="form-group cartvalores_tcompsal">
<span<?php echo $cartvalores->tcompsal->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->tcompsal->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_tcompsal" name="x<?php echo $cartvalores_grid->RowIndex ?>_tcompsal" id="x<?php echo $cartvalores_grid->RowIndex ?>_tcompsal" value="<?php echo ew_HtmlEncode($cartvalores->tcompsal->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_tcompsal" name="o<?php echo $cartvalores_grid->RowIndex ?>_tcompsal" id="o<?php echo $cartvalores_grid->RowIndex ?>_tcompsal" value="<?php echo ew_HtmlEncode($cartvalores->tcompsal->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->seriesal->Visible) { // seriesal ?>
		<td data-name="seriesal">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_seriesal" class="form-group cartvalores_seriesal">
<input type="text" data-field="x_seriesal" name="x<?php echo $cartvalores_grid->RowIndex ?>_seriesal" id="x<?php echo $cartvalores_grid->RowIndex ?>_seriesal" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->seriesal->PlaceHolder) ?>" value="<?php echo $cartvalores->seriesal->EditValue ?>"<?php echo $cartvalores->seriesal->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_seriesal" class="form-group cartvalores_seriesal">
<span<?php echo $cartvalores->seriesal->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->seriesal->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_seriesal" name="x<?php echo $cartvalores_grid->RowIndex ?>_seriesal" id="x<?php echo $cartvalores_grid->RowIndex ?>_seriesal" value="<?php echo ew_HtmlEncode($cartvalores->seriesal->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_seriesal" name="o<?php echo $cartvalores_grid->RowIndex ?>_seriesal" id="o<?php echo $cartvalores_grid->RowIndex ?>_seriesal" value="<?php echo ew_HtmlEncode($cartvalores->seriesal->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->ncompsal->Visible) { // ncompsal ?>
		<td data-name="ncompsal">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_ncompsal" class="form-group cartvalores_ncompsal">
<input type="text" data-field="x_ncompsal" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncompsal" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncompsal" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->ncompsal->PlaceHolder) ?>" value="<?php echo $cartvalores->ncompsal->EditValue ?>"<?php echo $cartvalores->ncompsal->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_ncompsal" class="form-group cartvalores_ncompsal">
<span<?php echo $cartvalores->ncompsal->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->ncompsal->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_ncompsal" name="x<?php echo $cartvalores_grid->RowIndex ?>_ncompsal" id="x<?php echo $cartvalores_grid->RowIndex ?>_ncompsal" value="<?php echo ew_HtmlEncode($cartvalores->ncompsal->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ncompsal" name="o<?php echo $cartvalores_grid->RowIndex ?>_ncompsal" id="o<?php echo $cartvalores_grid->RowIndex ?>_ncompsal" value="<?php echo ew_HtmlEncode($cartvalores->ncompsal->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->codrem->Visible) { // codrem ?>
		<td data-name="codrem">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_codrem" class="form-group cartvalores_codrem">
<select data-field="x_codrem" id="x<?php echo $cartvalores_grid->RowIndex ?>_codrem" name="x<?php echo $cartvalores_grid->RowIndex ?>_codrem"<?php echo $cartvalores->codrem->EditAttributes() ?>>
<?php
if (is_array($cartvalores->codrem->EditValue)) {
	$arwrk = $cartvalores->codrem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cartvalores->codrem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$cartvalores->codrem) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $cartvalores->codrem->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `ncomp`, `ncomp` AS `DispFld`, `direccion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `remates`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cartvalores->Lookup_Selecting($cartvalores->codrem, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `ncomp` DESC";
?>
<input type="hidden" name="s_x<?php echo $cartvalores_grid->RowIndex ?>_codrem" id="s_x<?php echo $cartvalores_grid->RowIndex ?>_codrem" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`ncomp` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_codrem" class="form-group cartvalores_codrem">
<span<?php echo $cartvalores->codrem->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->codrem->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codrem" name="x<?php echo $cartvalores_grid->RowIndex ?>_codrem" id="x<?php echo $cartvalores_grid->RowIndex ?>_codrem" value="<?php echo ew_HtmlEncode($cartvalores->codrem->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codrem" name="o<?php echo $cartvalores_grid->RowIndex ?>_codrem" id="o<?php echo $cartvalores_grid->RowIndex ?>_codrem" value="<?php echo ew_HtmlEncode($cartvalores->codrem->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->cotiz->Visible) { // cotiz ?>
		<td data-name="cotiz">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_cotiz" class="form-group cartvalores_cotiz">
<input type="text" data-field="x_cotiz" name="x<?php echo $cartvalores_grid->RowIndex ?>_cotiz" id="x<?php echo $cartvalores_grid->RowIndex ?>_cotiz" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->cotiz->PlaceHolder) ?>" value="<?php echo $cartvalores->cotiz->EditValue ?>"<?php echo $cartvalores->cotiz->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_cotiz" class="form-group cartvalores_cotiz">
<span<?php echo $cartvalores->cotiz->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->cotiz->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_cotiz" name="x<?php echo $cartvalores_grid->RowIndex ?>_cotiz" id="x<?php echo $cartvalores_grid->RowIndex ?>_cotiz" value="<?php echo ew_HtmlEncode($cartvalores->cotiz->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_cotiz" name="o<?php echo $cartvalores_grid->RowIndex ?>_cotiz" id="o<?php echo $cartvalores_grid->RowIndex ?>_cotiz" value="<?php echo ew_HtmlEncode($cartvalores->cotiz->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->usurel->Visible) { // usurel ?>
		<td data-name="usurel">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_usurel" class="form-group cartvalores_usurel">
<input type="text" data-field="x_usurel" name="x<?php echo $cartvalores_grid->RowIndex ?>_usurel" id="x<?php echo $cartvalores_grid->RowIndex ?>_usurel" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->usurel->PlaceHolder) ?>" value="<?php echo $cartvalores->usurel->EditValue ?>"<?php echo $cartvalores->usurel->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_usurel" class="form-group cartvalores_usurel">
<span<?php echo $cartvalores->usurel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->usurel->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_usurel" name="x<?php echo $cartvalores_grid->RowIndex ?>_usurel" id="x<?php echo $cartvalores_grid->RowIndex ?>_usurel" value="<?php echo ew_HtmlEncode($cartvalores->usurel->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_usurel" name="o<?php echo $cartvalores_grid->RowIndex ?>_usurel" id="o<?php echo $cartvalores_grid->RowIndex ?>_usurel" value="<?php echo ew_HtmlEncode($cartvalores->usurel->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->fecharel->Visible) { // fecharel ?>
		<td data-name="fecharel">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_fecharel" class="form-group cartvalores_fecharel">
<input type="text" data-field="x_fecharel" name="x<?php echo $cartvalores_grid->RowIndex ?>_fecharel" id="x<?php echo $cartvalores_grid->RowIndex ?>_fecharel" placeholder="<?php echo ew_HtmlEncode($cartvalores->fecharel->PlaceHolder) ?>" value="<?php echo $cartvalores->fecharel->EditValue ?>"<?php echo $cartvalores->fecharel->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_fecharel" class="form-group cartvalores_fecharel">
<span<?php echo $cartvalores->fecharel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->fecharel->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecharel" name="x<?php echo $cartvalores_grid->RowIndex ?>_fecharel" id="x<?php echo $cartvalores_grid->RowIndex ?>_fecharel" value="<?php echo ew_HtmlEncode($cartvalores->fecharel->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecharel" name="o<?php echo $cartvalores_grid->RowIndex ?>_fecharel" id="o<?php echo $cartvalores_grid->RowIndex ?>_fecharel" value="<?php echo ew_HtmlEncode($cartvalores->fecharel->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->ususal->Visible) { // ususal ?>
		<td data-name="ususal">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_ususal" class="form-group cartvalores_ususal">
<input type="text" data-field="x_ususal" name="x<?php echo $cartvalores_grid->RowIndex ?>_ususal" id="x<?php echo $cartvalores_grid->RowIndex ?>_ususal" size="30" placeholder="<?php echo ew_HtmlEncode($cartvalores->ususal->PlaceHolder) ?>" value="<?php echo $cartvalores->ususal->EditValue ?>"<?php echo $cartvalores->ususal->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_ususal" class="form-group cartvalores_ususal">
<span<?php echo $cartvalores->ususal->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->ususal->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_ususal" name="x<?php echo $cartvalores_grid->RowIndex ?>_ususal" id="x<?php echo $cartvalores_grid->RowIndex ?>_ususal" value="<?php echo ew_HtmlEncode($cartvalores->ususal->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ususal" name="o<?php echo $cartvalores_grid->RowIndex ?>_ususal" id="o<?php echo $cartvalores_grid->RowIndex ?>_ususal" value="<?php echo ew_HtmlEncode($cartvalores->ususal->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cartvalores->fechasal->Visible) { // fechasal ?>
		<td data-name="fechasal">
<?php if ($cartvalores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cartvalores_fechasal" class="form-group cartvalores_fechasal">
<input type="text" data-field="x_fechasal" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechasal" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechasal" placeholder="<?php echo ew_HtmlEncode($cartvalores->fechasal->PlaceHolder) ?>" value="<?php echo $cartvalores->fechasal->EditValue ?>"<?php echo $cartvalores->fechasal->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cartvalores_fechasal" class="form-group cartvalores_fechasal">
<span<?php echo $cartvalores->fechasal->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cartvalores->fechasal->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fechasal" name="x<?php echo $cartvalores_grid->RowIndex ?>_fechasal" id="x<?php echo $cartvalores_grid->RowIndex ?>_fechasal" value="<?php echo ew_HtmlEncode($cartvalores->fechasal->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fechasal" name="o<?php echo $cartvalores_grid->RowIndex ?>_fechasal" id="o<?php echo $cartvalores_grid->RowIndex ?>_fechasal" value="<?php echo ew_HtmlEncode($cartvalores->fechasal->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$cartvalores_grid->ListOptions->Render("body", "right", $cartvalores_grid->RowCnt);
?>
<script type="text/javascript">
fcartvaloresgrid.UpdateOpts(<?php echo $cartvalores_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($cartvalores->CurrentMode == "add" || $cartvalores->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $cartvalores_grid->FormKeyCountName ?>" id="<?php echo $cartvalores_grid->FormKeyCountName ?>" value="<?php echo $cartvalores_grid->KeyCount ?>">
<?php echo $cartvalores_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($cartvalores->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $cartvalores_grid->FormKeyCountName ?>" id="<?php echo $cartvalores_grid->FormKeyCountName ?>" value="<?php echo $cartvalores_grid->KeyCount ?>">
<?php echo $cartvalores_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($cartvalores->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fcartvaloresgrid">
</div>
<?php

// Close recordset
if ($cartvalores_grid->Recordset)
	$cartvalores_grid->Recordset->Close();
?>
<?php if ($cartvalores_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($cartvalores_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($cartvalores_grid->TotalRecs == 0 && $cartvalores->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($cartvalores_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($cartvalores->Export == "") { ?>
<script type="text/javascript">
fcartvaloresgrid.Init();
</script>
<?php } ?>
<?php
$cartvalores_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$cartvalores_grid->Page_Terminate();
?>
