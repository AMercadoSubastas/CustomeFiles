<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($detfaccon_grid)) $detfaccon_grid = new cdetfaccon_grid();

// Page init
$detfaccon_grid->Page_Init();

// Page main
$detfaccon_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$detfaccon_grid->Page_Render();
?>
<?php if ($detfaccon->Export == "") { ?>
<script type="text/javascript">

// Page object
var detfaccon_grid = new ew_Page("detfaccon_grid");
detfaccon_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = detfaccon_grid.PageID; // For backward compatibility

// Form object
var fdetfaccongrid = new ew_Form("fdetfaccongrid");
fdetfaccongrid.FormKeyCountName = '<?php echo $detfaccon_grid->FormKeyCountName ?>';

// Validate form
fdetfaccongrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_neto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detfaccon->neto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_iva");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $detfaccon->iva->FldCaption(), $detfaccon->iva->ReqErrMsg)) ?>");

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
fdetfaccongrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "descrip", false)) return false;
	if (ew_ValueChanged(fobj, infix, "neto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "iva", false)) return false;
	return true;
}

// Form_CustomValidate event
fdetfaccongrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdetfaccongrid.ValidateRequired = true;
<?php } else { ?>
fdetfaccongrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($detfaccon->CurrentAction == "gridadd") {
	if ($detfaccon->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$detfaccon_grid->TotalRecs = $detfaccon->SelectRecordCount();
			$detfaccon_grid->Recordset = $detfaccon_grid->LoadRecordset($detfaccon_grid->StartRec-1, $detfaccon_grid->DisplayRecs);
		} else {
			if ($detfaccon_grid->Recordset = $detfaccon_grid->LoadRecordset())
				$detfaccon_grid->TotalRecs = $detfaccon_grid->Recordset->RecordCount();
		}
		$detfaccon_grid->StartRec = 1;
		$detfaccon_grid->DisplayRecs = $detfaccon_grid->TotalRecs;
	} else {
		$detfaccon->CurrentFilter = "0=1";
		$detfaccon_grid->StartRec = 1;
		$detfaccon_grid->DisplayRecs = $detfaccon->GridAddRowCount;
	}
	$detfaccon_grid->TotalRecs = $detfaccon_grid->DisplayRecs;
	$detfaccon_grid->StopRec = $detfaccon_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$detfaccon_grid->TotalRecs = $detfaccon->SelectRecordCount();
	} else {
		if ($detfaccon_grid->Recordset = $detfaccon_grid->LoadRecordset())
			$detfaccon_grid->TotalRecs = $detfaccon_grid->Recordset->RecordCount();
	}
	$detfaccon_grid->StartRec = 1;
	$detfaccon_grid->DisplayRecs = $detfaccon_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$detfaccon_grid->Recordset = $detfaccon_grid->LoadRecordset($detfaccon_grid->StartRec-1, $detfaccon_grid->DisplayRecs);

	// Set no record found message
	if ($detfaccon->CurrentAction == "" && $detfaccon_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$detfaccon_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($detfaccon_grid->SearchWhere == "0=101")
			$detfaccon_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$detfaccon_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$detfaccon_grid->RenderOtherOptions();
?>
<?php $detfaccon_grid->ShowPageHeader(); ?>
<?php
$detfaccon_grid->ShowMessage();
?>
<?php if ($detfaccon_grid->TotalRecs > 0 || $detfaccon->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fdetfaccongrid" class="ewForm form-inline">
<div id="gmp_detfaccon" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_detfaccongrid" class="table ewTable">
<?php echo $detfaccon->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$detfaccon_grid->RenderListOptions();

// Render list options (header, left)
$detfaccon_grid->ListOptions->Render("header", "left");
?>
<?php if ($detfaccon->descrip->Visible) { // descrip ?>
	<?php if ($detfaccon->SortUrl($detfaccon->descrip) == "") { ?>
		<th data-name="descrip"><div id="elh_detfaccon_descrip" class="detfaccon_descrip"><div class="ewTableHeaderCaption"><?php echo $detfaccon->descrip->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descrip"><div><div id="elh_detfaccon_descrip" class="detfaccon_descrip">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfaccon->descrip->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfaccon->descrip->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfaccon->descrip->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfaccon->neto->Visible) { // neto ?>
	<?php if ($detfaccon->SortUrl($detfaccon->neto) == "") { ?>
		<th data-name="neto"><div id="elh_detfaccon_neto" class="detfaccon_neto"><div class="ewTableHeaderCaption"><?php echo $detfaccon->neto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="neto"><div><div id="elh_detfaccon_neto" class="detfaccon_neto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfaccon->neto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfaccon->neto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfaccon->neto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detfaccon->iva->Visible) { // iva ?>
	<?php if ($detfaccon->SortUrl($detfaccon->iva) == "") { ?>
		<th data-name="iva"><div id="elh_detfaccon_iva" class="detfaccon_iva"><div class="ewTableHeaderCaption"><?php echo $detfaccon->iva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="iva"><div><div id="elh_detfaccon_iva" class="detfaccon_iva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detfaccon->iva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detfaccon->iva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detfaccon->iva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$detfaccon_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$detfaccon_grid->StartRec = 1;
$detfaccon_grid->StopRec = $detfaccon_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($detfaccon_grid->FormKeyCountName) && ($detfaccon->CurrentAction == "gridadd" || $detfaccon->CurrentAction == "gridedit" || $detfaccon->CurrentAction == "F")) {
		$detfaccon_grid->KeyCount = $objForm->GetValue($detfaccon_grid->FormKeyCountName);
		$detfaccon_grid->StopRec = $detfaccon_grid->StartRec + $detfaccon_grid->KeyCount - 1;
	}
}
$detfaccon_grid->RecCnt = $detfaccon_grid->StartRec - 1;
if ($detfaccon_grid->Recordset && !$detfaccon_grid->Recordset->EOF) {
	$detfaccon_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $detfaccon_grid->StartRec > 1)
		$detfaccon_grid->Recordset->Move($detfaccon_grid->StartRec - 1);
} elseif (!$detfaccon->AllowAddDeleteRow && $detfaccon_grid->StopRec == 0) {
	$detfaccon_grid->StopRec = $detfaccon->GridAddRowCount;
}

// Initialize aggregate
$detfaccon->RowType = EW_ROWTYPE_AGGREGATEINIT;
$detfaccon->ResetAttrs();
$detfaccon_grid->RenderRow();
if ($detfaccon->CurrentAction == "gridadd")
	$detfaccon_grid->RowIndex = 0;
if ($detfaccon->CurrentAction == "gridedit")
	$detfaccon_grid->RowIndex = 0;
while ($detfaccon_grid->RecCnt < $detfaccon_grid->StopRec) {
	$detfaccon_grid->RecCnt++;
	if (intval($detfaccon_grid->RecCnt) >= intval($detfaccon_grid->StartRec)) {
		$detfaccon_grid->RowCnt++;
		if ($detfaccon->CurrentAction == "gridadd" || $detfaccon->CurrentAction == "gridedit" || $detfaccon->CurrentAction == "F") {
			$detfaccon_grid->RowIndex++;
			$objForm->Index = $detfaccon_grid->RowIndex;
			if ($objForm->HasValue($detfaccon_grid->FormActionName))
				$detfaccon_grid->RowAction = strval($objForm->GetValue($detfaccon_grid->FormActionName));
			elseif ($detfaccon->CurrentAction == "gridadd")
				$detfaccon_grid->RowAction = "insert";
			else
				$detfaccon_grid->RowAction = "";
		}

		// Set up key count
		$detfaccon_grid->KeyCount = $detfaccon_grid->RowIndex;

		// Init row class and style
		$detfaccon->ResetAttrs();
		$detfaccon->CssClass = "";
		if ($detfaccon->CurrentAction == "gridadd") {
			if ($detfaccon->CurrentMode == "copy") {
				$detfaccon_grid->LoadRowValues($detfaccon_grid->Recordset); // Load row values
				$detfaccon_grid->SetRecordKey($detfaccon_grid->RowOldKey, $detfaccon_grid->Recordset); // Set old record key
			} else {
				$detfaccon_grid->LoadDefaultValues(); // Load default values
				$detfaccon_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$detfaccon_grid->LoadRowValues($detfaccon_grid->Recordset); // Load row values
		}
		$detfaccon->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($detfaccon->CurrentAction == "gridadd") // Grid add
			$detfaccon->RowType = EW_ROWTYPE_ADD; // Render add
		if ($detfaccon->CurrentAction == "gridadd" && $detfaccon->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$detfaccon_grid->RestoreCurrentRowFormValues($detfaccon_grid->RowIndex); // Restore form values
		if ($detfaccon->CurrentAction == "gridedit") { // Grid edit
			if ($detfaccon->EventCancelled) {
				$detfaccon_grid->RestoreCurrentRowFormValues($detfaccon_grid->RowIndex); // Restore form values
			}
			if ($detfaccon_grid->RowAction == "insert")
				$detfaccon->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$detfaccon->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($detfaccon->CurrentAction == "gridedit" && ($detfaccon->RowType == EW_ROWTYPE_EDIT || $detfaccon->RowType == EW_ROWTYPE_ADD) && $detfaccon->EventCancelled) // Update failed
			$detfaccon_grid->RestoreCurrentRowFormValues($detfaccon_grid->RowIndex); // Restore form values
		if ($detfaccon->RowType == EW_ROWTYPE_EDIT) // Edit row
			$detfaccon_grid->EditRowCnt++;
		if ($detfaccon->CurrentAction == "F") // Confirm row
			$detfaccon_grid->RestoreCurrentRowFormValues($detfaccon_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$detfaccon->RowAttrs = array_merge($detfaccon->RowAttrs, array('data-rowindex'=>$detfaccon_grid->RowCnt, 'id'=>'r' . $detfaccon_grid->RowCnt . '_detfaccon', 'data-rowtype'=>$detfaccon->RowType));

		// Render row
		$detfaccon_grid->RenderRow();

		// Render list options
		$detfaccon_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($detfaccon_grid->RowAction <> "delete" && $detfaccon_grid->RowAction <> "insertdelete" && !($detfaccon_grid->RowAction == "insert" && $detfaccon->CurrentAction == "F" && $detfaccon_grid->EmptyRow())) {
?>
	<tr<?php echo $detfaccon->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detfaccon_grid->ListOptions->Render("body", "left", $detfaccon_grid->RowCnt);
?>
	<?php if ($detfaccon->descrip->Visible) { // descrip ?>
		<td data-name="descrip"<?php echo $detfaccon->descrip->CellAttributes() ?>>
<?php if ($detfaccon->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfaccon_grid->RowCnt ?>_detfaccon_descrip" class="form-group detfaccon_descrip">
<input type="text" data-field="x_descrip" name="x<?php echo $detfaccon_grid->RowIndex ?>_descrip" id="x<?php echo $detfaccon_grid->RowIndex ?>_descrip" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($detfaccon->descrip->PlaceHolder) ?>" value="<?php echo $detfaccon->descrip->EditValue ?>"<?php echo $detfaccon->descrip->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_descrip" name="o<?php echo $detfaccon_grid->RowIndex ?>_descrip" id="o<?php echo $detfaccon_grid->RowIndex ?>_descrip" value="<?php echo ew_HtmlEncode($detfaccon->descrip->OldValue) ?>">
<?php } ?>
<?php if ($detfaccon->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfaccon_grid->RowCnt ?>_detfaccon_descrip" class="form-group detfaccon_descrip">
<input type="text" data-field="x_descrip" name="x<?php echo $detfaccon_grid->RowIndex ?>_descrip" id="x<?php echo $detfaccon_grid->RowIndex ?>_descrip" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($detfaccon->descrip->PlaceHolder) ?>" value="<?php echo $detfaccon->descrip->EditValue ?>"<?php echo $detfaccon->descrip->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfaccon->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfaccon->descrip->ViewAttributes() ?>>
<?php echo $detfaccon->descrip->ListViewValue() ?></span>
<input type="hidden" data-field="x_descrip" name="x<?php echo $detfaccon_grid->RowIndex ?>_descrip" id="x<?php echo $detfaccon_grid->RowIndex ?>_descrip" value="<?php echo ew_HtmlEncode($detfaccon->descrip->FormValue) ?>">
<input type="hidden" data-field="x_descrip" name="o<?php echo $detfaccon_grid->RowIndex ?>_descrip" id="o<?php echo $detfaccon_grid->RowIndex ?>_descrip" value="<?php echo ew_HtmlEncode($detfaccon->descrip->OldValue) ?>">
<?php } ?>
<a id="<?php echo $detfaccon_grid->PageObjName . "_row_" . $detfaccon_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($detfaccon->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_codnum" name="x<?php echo $detfaccon_grid->RowIndex ?>_codnum" id="x<?php echo $detfaccon_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($detfaccon->codnum->CurrentValue) ?>">
<input type="hidden" data-field="x_codnum" name="o<?php echo $detfaccon_grid->RowIndex ?>_codnum" id="o<?php echo $detfaccon_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($detfaccon->codnum->OldValue) ?>">
<?php } ?>
<?php if ($detfaccon->RowType == EW_ROWTYPE_EDIT || $detfaccon->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_codnum" name="x<?php echo $detfaccon_grid->RowIndex ?>_codnum" id="x<?php echo $detfaccon_grid->RowIndex ?>_codnum" value="<?php echo ew_HtmlEncode($detfaccon->codnum->CurrentValue) ?>">
<?php } ?>
	<?php if ($detfaccon->neto->Visible) { // neto ?>
		<td data-name="neto"<?php echo $detfaccon->neto->CellAttributes() ?>>
<?php if ($detfaccon->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfaccon_grid->RowCnt ?>_detfaccon_neto" class="form-group detfaccon_neto">
<input type="text" data-field="x_neto" name="x<?php echo $detfaccon_grid->RowIndex ?>_neto" id="x<?php echo $detfaccon_grid->RowIndex ?>_neto" size="30" placeholder="<?php echo ew_HtmlEncode($detfaccon->neto->PlaceHolder) ?>" value="<?php echo $detfaccon->neto->EditValue ?>"<?php echo $detfaccon->neto->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_neto" name="o<?php echo $detfaccon_grid->RowIndex ?>_neto" id="o<?php echo $detfaccon_grid->RowIndex ?>_neto" value="<?php echo ew_HtmlEncode($detfaccon->neto->OldValue) ?>">
<?php } ?>
<?php if ($detfaccon->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfaccon_grid->RowCnt ?>_detfaccon_neto" class="form-group detfaccon_neto">
<input type="text" data-field="x_neto" name="x<?php echo $detfaccon_grid->RowIndex ?>_neto" id="x<?php echo $detfaccon_grid->RowIndex ?>_neto" size="30" placeholder="<?php echo ew_HtmlEncode($detfaccon->neto->PlaceHolder) ?>" value="<?php echo $detfaccon->neto->EditValue ?>"<?php echo $detfaccon->neto->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detfaccon->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfaccon->neto->ViewAttributes() ?>>
<?php echo $detfaccon->neto->ListViewValue() ?></span>
<input type="hidden" data-field="x_neto" name="x<?php echo $detfaccon_grid->RowIndex ?>_neto" id="x<?php echo $detfaccon_grid->RowIndex ?>_neto" value="<?php echo ew_HtmlEncode($detfaccon->neto->FormValue) ?>">
<input type="hidden" data-field="x_neto" name="o<?php echo $detfaccon_grid->RowIndex ?>_neto" id="o<?php echo $detfaccon_grid->RowIndex ?>_neto" value="<?php echo ew_HtmlEncode($detfaccon->neto->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detfaccon->iva->Visible) { // iva ?>
		<td data-name="iva"<?php echo $detfaccon->iva->CellAttributes() ?>>
<?php if ($detfaccon->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detfaccon_grid->RowCnt ?>_detfaccon_iva" class="form-group detfaccon_iva">
<select data-field="x_iva" id="x<?php echo $detfaccon_grid->RowIndex ?>_iva" name="x<?php echo $detfaccon_grid->RowIndex ?>_iva"<?php echo $detfaccon->iva->EditAttributes() ?>>
<?php
if (is_array($detfaccon->iva->EditValue)) {
	$arwrk = $detfaccon->iva->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($detfaccon->iva->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $detfaccon->iva->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_iva" name="o<?php echo $detfaccon_grid->RowIndex ?>_iva" id="o<?php echo $detfaccon_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($detfaccon->iva->OldValue) ?>">
<?php } ?>
<?php if ($detfaccon->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detfaccon_grid->RowCnt ?>_detfaccon_iva" class="form-group detfaccon_iva">
<select data-field="x_iva" id="x<?php echo $detfaccon_grid->RowIndex ?>_iva" name="x<?php echo $detfaccon_grid->RowIndex ?>_iva"<?php echo $detfaccon->iva->EditAttributes() ?>>
<?php
if (is_array($detfaccon->iva->EditValue)) {
	$arwrk = $detfaccon->iva->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($detfaccon->iva->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $detfaccon->iva->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($detfaccon->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detfaccon->iva->ViewAttributes() ?>>
<?php echo $detfaccon->iva->ListViewValue() ?></span>
<input type="hidden" data-field="x_iva" name="x<?php echo $detfaccon_grid->RowIndex ?>_iva" id="x<?php echo $detfaccon_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($detfaccon->iva->FormValue) ?>">
<input type="hidden" data-field="x_iva" name="o<?php echo $detfaccon_grid->RowIndex ?>_iva" id="o<?php echo $detfaccon_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($detfaccon->iva->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detfaccon_grid->ListOptions->Render("body", "right", $detfaccon_grid->RowCnt);
?>
	</tr>
<?php if ($detfaccon->RowType == EW_ROWTYPE_ADD || $detfaccon->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdetfaccongrid.UpdateOpts(<?php echo $detfaccon_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($detfaccon->CurrentAction <> "gridadd" || $detfaccon->CurrentMode == "copy")
		if (!$detfaccon_grid->Recordset->EOF) $detfaccon_grid->Recordset->MoveNext();
}
?>
<?php
	if ($detfaccon->CurrentMode == "add" || $detfaccon->CurrentMode == "copy" || $detfaccon->CurrentMode == "edit") {
		$detfaccon_grid->RowIndex = '$rowindex$';
		$detfaccon_grid->LoadDefaultValues();

		// Set row properties
		$detfaccon->ResetAttrs();
		$detfaccon->RowAttrs = array_merge($detfaccon->RowAttrs, array('data-rowindex'=>$detfaccon_grid->RowIndex, 'id'=>'r0_detfaccon', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($detfaccon->RowAttrs["class"], "ewTemplate");
		$detfaccon->RowType = EW_ROWTYPE_ADD;

		// Render row
		$detfaccon_grid->RenderRow();

		// Render list options
		$detfaccon_grid->RenderListOptions();
		$detfaccon_grid->StartRowCnt = 0;
?>
	<tr<?php echo $detfaccon->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detfaccon_grid->ListOptions->Render("body", "left", $detfaccon_grid->RowIndex);
?>
	<?php if ($detfaccon->descrip->Visible) { // descrip ?>
		<td>
<?php if ($detfaccon->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfaccon_descrip" class="form-group detfaccon_descrip">
<input type="text" data-field="x_descrip" name="x<?php echo $detfaccon_grid->RowIndex ?>_descrip" id="x<?php echo $detfaccon_grid->RowIndex ?>_descrip" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($detfaccon->descrip->PlaceHolder) ?>" value="<?php echo $detfaccon->descrip->EditValue ?>"<?php echo $detfaccon->descrip->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfaccon_descrip" class="form-group detfaccon_descrip">
<span<?php echo $detfaccon->descrip->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfaccon->descrip->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_descrip" name="x<?php echo $detfaccon_grid->RowIndex ?>_descrip" id="x<?php echo $detfaccon_grid->RowIndex ?>_descrip" value="<?php echo ew_HtmlEncode($detfaccon->descrip->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_descrip" name="o<?php echo $detfaccon_grid->RowIndex ?>_descrip" id="o<?php echo $detfaccon_grid->RowIndex ?>_descrip" value="<?php echo ew_HtmlEncode($detfaccon->descrip->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfaccon->neto->Visible) { // neto ?>
		<td>
<?php if ($detfaccon->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfaccon_neto" class="form-group detfaccon_neto">
<input type="text" data-field="x_neto" name="x<?php echo $detfaccon_grid->RowIndex ?>_neto" id="x<?php echo $detfaccon_grid->RowIndex ?>_neto" size="30" placeholder="<?php echo ew_HtmlEncode($detfaccon->neto->PlaceHolder) ?>" value="<?php echo $detfaccon->neto->EditValue ?>"<?php echo $detfaccon->neto->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfaccon_neto" class="form-group detfaccon_neto">
<span<?php echo $detfaccon->neto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfaccon->neto->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_neto" name="x<?php echo $detfaccon_grid->RowIndex ?>_neto" id="x<?php echo $detfaccon_grid->RowIndex ?>_neto" value="<?php echo ew_HtmlEncode($detfaccon->neto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_neto" name="o<?php echo $detfaccon_grid->RowIndex ?>_neto" id="o<?php echo $detfaccon_grid->RowIndex ?>_neto" value="<?php echo ew_HtmlEncode($detfaccon->neto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detfaccon->iva->Visible) { // iva ?>
		<td>
<?php if ($detfaccon->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detfaccon_iva" class="form-group detfaccon_iva">
<select data-field="x_iva" id="x<?php echo $detfaccon_grid->RowIndex ?>_iva" name="x<?php echo $detfaccon_grid->RowIndex ?>_iva"<?php echo $detfaccon->iva->EditAttributes() ?>>
<?php
if (is_array($detfaccon->iva->EditValue)) {
	$arwrk = $detfaccon->iva->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($detfaccon->iva->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $detfaccon->iva->OldValue = "";
?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_detfaccon_iva" class="form-group detfaccon_iva">
<span<?php echo $detfaccon->iva->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detfaccon->iva->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_iva" name="x<?php echo $detfaccon_grid->RowIndex ?>_iva" id="x<?php echo $detfaccon_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($detfaccon->iva->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_iva" name="o<?php echo $detfaccon_grid->RowIndex ?>_iva" id="o<?php echo $detfaccon_grid->RowIndex ?>_iva" value="<?php echo ew_HtmlEncode($detfaccon->iva->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detfaccon_grid->ListOptions->Render("body", "right", $detfaccon_grid->RowCnt);
?>
<script type="text/javascript">
fdetfaccongrid.UpdateOpts(<?php echo $detfaccon_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($detfaccon->CurrentMode == "add" || $detfaccon->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $detfaccon_grid->FormKeyCountName ?>" id="<?php echo $detfaccon_grid->FormKeyCountName ?>" value="<?php echo $detfaccon_grid->KeyCount ?>">
<?php echo $detfaccon_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($detfaccon->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $detfaccon_grid->FormKeyCountName ?>" id="<?php echo $detfaccon_grid->FormKeyCountName ?>" value="<?php echo $detfaccon_grid->KeyCount ?>">
<?php echo $detfaccon_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($detfaccon->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdetfaccongrid">
</div>
<?php

// Close recordset
if ($detfaccon_grid->Recordset)
	$detfaccon_grid->Recordset->Close();
?>
<?php if ($detfaccon_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($detfaccon_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($detfaccon_grid->TotalRecs == 0 && $detfaccon->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($detfaccon_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($detfaccon->Export == "") { ?>
<script type="text/javascript">
fdetfaccongrid.Init();
</script>
<?php } ?>
<?php
$detfaccon_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$detfaccon_grid->Page_Terminate();
?>
