<?php include_once "detfacproinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php

//
// Page class
//

$detfacpro_grid = NULL; // Initialize page object first

class cdetfacpro_grid extends cdetfacpro {

	// Page ID
	var $PageID = 'grid';

	// Project ID
	var $ProjectID = "{C8A55938-38ED-41FC-AF90-E3261742AEC3}";

	// Table name
	var $TableName = 'detfacpro';

	// Page object name
	var $PageObjName = 'detfacpro_grid';

	// Grid form hidden field names
	var $FormName = 'fdetfacprogrid';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$this->FormActionName .= '_' . $this->FormName;
		$this->FormKeyName .= '_' . $this->FormName;
		$this->FormOldKeyName .= '_' . $this->FormName;
		$this->FormBlankRowName .= '_' . $this->FormName;
		$this->FormKeyCountName .= '_' . $this->FormName;
		$GLOBALS["Grid"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (detfacpro)
		if (!isset($GLOBALS["detfacpro"]) || get_class($GLOBALS["detfacpro"]) == "cdetfacpro") {
			$GLOBALS["detfacpro"] = &$this;

//			$GLOBALS["MasterTable"] = &$GLOBALS["Table"];
//			if (!isset($GLOBALS["Table"])) $GLOBALS["Table"] = &$GLOBALS["detfacpro"];

		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'grid');

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'detfacpro');

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("login.php");
		}

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->fechahora->Visible = !$this->IsAddOrEdit();
		$this->usuario->Visible = !$this->IsAddOrEdit();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();

		// Setup other options
		$this->SetupOtherOptions();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn, $gsExportFile, $gTmpImages;

		// Export
		global $EW_EXPORT, $detfacpro;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($detfacpro);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}

//		$GLOBALS["Table"] = &$GLOBALS["MasterTable"];
		unset($GLOBALS["Grid"]);
		if ($url == "")
			return;
		$this->Page_Redirecting($url);

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $ShowOtherOptions = FALSE;
	var $DisplayRecs = 10;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Handle reset command
			$this->ResetCmd();

			// Set up master detail parameters
			$this->SetUpMasterParms();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 10; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "cabfacpro") {
			global $cabfacpro;
			$rsmaster = $cabfacpro->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("cabfacprolist.php"); // Return to master page
			} else {
				$cabfacpro->LoadListRowValues($rsmaster);
				$cabfacpro->RowType = EW_ROWTYPE_MASTER; // Master row
				$cabfacpro->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount();
		}
	}

	//  Exit inline mode
	function ClearInlineMode() {
		$this->neto->FormValue = ""; // Clear form value
		$this->bruto->FormValue = ""; // Clear form value
		$this->iva->FormValue = ""; // Clear form value
		$this->imp->FormValue = ""; // Clear form value
		$this->comcob->FormValue = ""; // Clear form value
		$this->compag->FormValue = ""; // Clear form value
		$this->porciva->FormValue = ""; // Clear form value
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Perform update to grid
	function GridUpdate() {
		global $conn, $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		if ($this->CurrentFilter == "")
			$this->CurrentFilter = "0=1";
		$sSql = $this->SQL();
		if ($rs = $conn-> executeUpdate($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}

		// Call Grid Updating event
		if (!$this->Grid_Updating($rsold)) {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("GridEditCancelled")); // Set grid edit cancelled message
			return FALSE;
		}
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {

			// Get new recordset
			if ($rs = $conn-> executeUpdate($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
		}
		return $bGridUpdate;
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 0) {
		}
		return TRUE;
	}

	// Perform Grid Add
	function GridInsert() {
		global $conn, $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;

		// Call Grid Inserting event
		if (!$this->Grid_Inserting()) {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("GridAddCancelled")); // Set grid add cancelled message
			}
			return FALSE;
		}

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			if ($rowaction == "insert") {
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
				$this->LoadOldRecord(); // Load old recordset
			}
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->ClearInlineMode(); // Clear grid add mode and return
			return TRUE;
		}
		if ($bGridInsert) {

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn-> executeUpdate($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Inserted event
			$this->Grid_Inserted($rsnew);
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_codnum") && $objForm->HasValue("o_codnum") && $this->codnum->CurrentValue <> $this->codnum->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tcomp") && $objForm->HasValue("o_tcomp") && $this->tcomp->CurrentValue <> $this->tcomp->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_serie") && $objForm->HasValue("o_serie") && $this->serie->CurrentValue <> $this->serie->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ncomp") && $objForm->HasValue("o_ncomp") && $this->ncomp->CurrentValue <> $this->ncomp->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_nreng") && $objForm->HasValue("o_nreng") && $this->nreng->CurrentValue <> $this->nreng->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_codrem") && $objForm->HasValue("o_codrem") && $this->codrem->CurrentValue <> $this->codrem->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_codlote") && $objForm->HasValue("o_codlote") && $this->codlote->CurrentValue <> $this->codlote->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_descrip") && $objForm->HasValue("o_descrip") && $this->descrip->CurrentValue <> $this->descrip->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_neto") && $objForm->HasValue("o_neto") && $this->neto->CurrentValue <> $this->neto->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_bruto") && $objForm->HasValue("o_bruto") && $this->bruto->CurrentValue <> $this->bruto->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_iva") && $objForm->HasValue("o_iva") && $this->iva->CurrentValue <> $this->iva->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_imp") && $objForm->HasValue("o_imp") && $this->imp->CurrentValue <> $this->imp->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_comcob") && $objForm->HasValue("o_comcob") && $this->comcob->CurrentValue <> $this->comcob->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_compag") && $objForm->HasValue("o_compag") && $this->compag->CurrentValue <> $this->compag->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_porciva") && $objForm->HasValue("o_porciva") && $this->porciva->CurrentValue <> $this->porciva->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tieneresol") && $objForm->HasValue("o_tieneresol") && $this->tieneresol->CurrentValue <> $this->tieneresol->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_concafac") && $objForm->HasValue("o_concafac") && $this->concafac->CurrentValue <> $this->concafac->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tcomsal") && $objForm->HasValue("o_tcomsal") && $this->tcomsal->CurrentValue <> $this->tcomsal->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_seriesal") && $objForm->HasValue("o_seriesal") && $this->seriesal->CurrentValue <> $this->seriesal->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ncompsal") && $objForm->HasValue("o_ncompsal") && $this->ncompsal->CurrentValue <> $this->ncompsal->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Get all form values of the grid
	function GetGridFormValues() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;
		$rows = array();

		// Loop through all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else {
					$rows[] = $this->GetFieldValues("FormValue"); // Return row as array
				}
			}
		}
		return $rows; // Return as array of array
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->tcomp->setSessionValue("");
				$this->serie->setSessionValue("");
				$this->ncomp->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = TRUE;
			$item->Visible = FALSE; // Default hidden
		}

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($objForm->HasValue($this->FormOldKeyName))
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
			if ($this->RowOldKey <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $OldKeyName . "\" id=\"" . $OldKeyName . "\" value=\"" . ew_HtmlEncode($this->RowOldKey) . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				if (is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"javascript:void(0);\" onclick=\"ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
		}
		if ($this->CurrentMode == "edit" && is_numeric($this->RowIndex)) {
		}
		$this->RenderListOptionsExt();
	}

	// Set record key
	function SetRecordKey(&$key, $rs) {
		$key = "";
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$option = &$this->OtherOptions["addedit"];
		$option->UseDropDownButton = FALSE;
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$option->UseButtonGroup = TRUE;
		$option->ButtonClass = "btn-sm"; // Class for button group
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		if (($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") && $this->CurrentAction != "F") { // Check add/copy/edit mode
			if ($this->AllowAddDeleteRow) {
				$option = &$options["addedit"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
				$item = &$option->Add("addblankrow");
				$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
				$item->Visible = FALSE;
				$this->ShowOtherOptions = $item->Visible;
			}
		}
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->codnum->CurrentValue = 0;
		$this->codnum->OldValue = $this->codnum->CurrentValue;
		$this->tcomp->CurrentValue = NULL;
		$this->tcomp->OldValue = $this->tcomp->CurrentValue;
		$this->serie->CurrentValue = NULL;
		$this->serie->OldValue = $this->serie->CurrentValue;
		$this->ncomp->CurrentValue = NULL;
		$this->ncomp->OldValue = $this->ncomp->CurrentValue;
		$this->nreng->CurrentValue = NULL;
		$this->nreng->OldValue = $this->nreng->CurrentValue;
		$this->codrem->CurrentValue = NULL;
		$this->codrem->OldValue = $this->codrem->CurrentValue;
		$this->codlote->CurrentValue = NULL;
		$this->codlote->OldValue = $this->codlote->CurrentValue;
		$this->descrip->CurrentValue = NULL;
		$this->descrip->OldValue = $this->descrip->CurrentValue;
		$this->neto->CurrentValue = 0.00;
		$this->neto->OldValue = $this->neto->CurrentValue;
		$this->bruto->CurrentValue = 0.00;
		$this->bruto->OldValue = $this->bruto->CurrentValue;
		$this->iva->CurrentValue = 0.00;
		$this->iva->OldValue = $this->iva->CurrentValue;
		$this->imp->CurrentValue = 0.00;
		$this->imp->OldValue = $this->imp->CurrentValue;
		$this->comcob->CurrentValue = 0.00;
		$this->comcob->OldValue = $this->comcob->CurrentValue;
		$this->compag->CurrentValue = 0.00;
		$this->compag->OldValue = $this->compag->CurrentValue;
		$this->fechahora->CurrentValue = NULL;
		$this->fechahora->OldValue = $this->fechahora->CurrentValue;
		$this->usuario->CurrentValue = NULL;
		$this->usuario->OldValue = $this->usuario->CurrentValue;
		$this->porciva->CurrentValue = NULL;
		$this->porciva->OldValue = $this->porciva->CurrentValue;
		$this->tieneresol->CurrentValue = 0;
		$this->tieneresol->OldValue = $this->tieneresol->CurrentValue;
		$this->concafac->CurrentValue = NULL;
		$this->concafac->OldValue = $this->concafac->CurrentValue;
		$this->tcomsal->CurrentValue = NULL;
		$this->tcomsal->OldValue = $this->tcomsal->CurrentValue;
		$this->seriesal->CurrentValue = NULL;
		$this->seriesal->OldValue = $this->seriesal->CurrentValue;
		$this->ncompsal->CurrentValue = NULL;
		$this->ncompsal->OldValue = $this->ncompsal->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$objForm->FormName = $this->FormName;
		if (!$this->codnum->FldIsDetailKey) {
			$this->codnum->setFormValue($objForm->GetValue("x_codnum"));
		}
		$this->codnum->setOldValue($objForm->GetValue("o_codnum"));
		if (!$this->tcomp->FldIsDetailKey) {
			$this->tcomp->setFormValue($objForm->GetValue("x_tcomp"));
		}
		$this->tcomp->setOldValue($objForm->GetValue("o_tcomp"));
		if (!$this->serie->FldIsDetailKey) {
			$this->serie->setFormValue($objForm->GetValue("x_serie"));
		}
		$this->serie->setOldValue($objForm->GetValue("o_serie"));
		if (!$this->ncomp->FldIsDetailKey) {
			$this->ncomp->setFormValue($objForm->GetValue("x_ncomp"));
		}
		$this->ncomp->setOldValue($objForm->GetValue("o_ncomp"));
		if (!$this->nreng->FldIsDetailKey) {
			$this->nreng->setFormValue($objForm->GetValue("x_nreng"));
		}
		$this->nreng->setOldValue($objForm->GetValue("o_nreng"));
		if (!$this->codrem->FldIsDetailKey) {
			$this->codrem->setFormValue($objForm->GetValue("x_codrem"));
		}
		$this->codrem->setOldValue($objForm->GetValue("o_codrem"));
		if (!$this->codlote->FldIsDetailKey) {
			$this->codlote->setFormValue($objForm->GetValue("x_codlote"));
		}
		$this->codlote->setOldValue($objForm->GetValue("o_codlote"));
		if (!$this->descrip->FldIsDetailKey) {
			$this->descrip->setFormValue($objForm->GetValue("x_descrip"));
		}
		$this->descrip->setOldValue($objForm->GetValue("o_descrip"));
		if (!$this->neto->FldIsDetailKey) {
			$this->neto->setFormValue($objForm->GetValue("x_neto"));
		}
		$this->neto->setOldValue($objForm->GetValue("o_neto"));
		if (!$this->bruto->FldIsDetailKey) {
			$this->bruto->setFormValue($objForm->GetValue("x_bruto"));
		}
		$this->bruto->setOldValue($objForm->GetValue("o_bruto"));
		if (!$this->iva->FldIsDetailKey) {
			$this->iva->setFormValue($objForm->GetValue("x_iva"));
		}
		$this->iva->setOldValue($objForm->GetValue("o_iva"));
		if (!$this->imp->FldIsDetailKey) {
			$this->imp->setFormValue($objForm->GetValue("x_imp"));
		}
		$this->imp->setOldValue($objForm->GetValue("o_imp"));
		if (!$this->comcob->FldIsDetailKey) {
			$this->comcob->setFormValue($objForm->GetValue("x_comcob"));
		}
		$this->comcob->setOldValue($objForm->GetValue("o_comcob"));
		if (!$this->compag->FldIsDetailKey) {
			$this->compag->setFormValue($objForm->GetValue("x_compag"));
		}
		$this->compag->setOldValue($objForm->GetValue("o_compag"));
		if (!$this->fechahora->FldIsDetailKey) {
			$this->fechahora->setFormValue($objForm->GetValue("x_fechahora"));
			$this->fechahora->CurrentValue = ew_UnFormatDateTime($this->fechahora->CurrentValue, 7);
		}
		$this->fechahora->setOldValue($objForm->GetValue("o_fechahora"));
		if (!$this->usuario->FldIsDetailKey) {
			$this->usuario->setFormValue($objForm->GetValue("x_usuario"));
		}
		$this->usuario->setOldValue($objForm->GetValue("o_usuario"));
		if (!$this->porciva->FldIsDetailKey) {
			$this->porciva->setFormValue($objForm->GetValue("x_porciva"));
		}
		$this->porciva->setOldValue($objForm->GetValue("o_porciva"));
		if (!$this->tieneresol->FldIsDetailKey) {
			$this->tieneresol->setFormValue($objForm->GetValue("x_tieneresol"));
		}
		$this->tieneresol->setOldValue($objForm->GetValue("o_tieneresol"));
		if (!$this->concafac->FldIsDetailKey) {
			$this->concafac->setFormValue($objForm->GetValue("x_concafac"));
		}
		$this->concafac->setOldValue($objForm->GetValue("o_concafac"));
		if (!$this->tcomsal->FldIsDetailKey) {
			$this->tcomsal->setFormValue($objForm->GetValue("x_tcomsal"));
		}
		$this->tcomsal->setOldValue($objForm->GetValue("o_tcomsal"));
		if (!$this->seriesal->FldIsDetailKey) {
			$this->seriesal->setFormValue($objForm->GetValue("x_seriesal"));
		}
		$this->seriesal->setOldValue($objForm->GetValue("o_seriesal"));
		if (!$this->ncompsal->FldIsDetailKey) {
			$this->ncompsal->setFormValue($objForm->GetValue("x_ncompsal"));
		}
		$this->ncompsal->setOldValue($objForm->GetValue("o_ncompsal"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->codnum->CurrentValue = $this->codnum->FormValue;
		$this->tcomp->CurrentValue = $this->tcomp->FormValue;
		$this->serie->CurrentValue = $this->serie->FormValue;
		$this->ncomp->CurrentValue = $this->ncomp->FormValue;
		$this->nreng->CurrentValue = $this->nreng->FormValue;
		$this->codrem->CurrentValue = $this->codrem->FormValue;
		$this->codlote->CurrentValue = $this->codlote->FormValue;
		$this->descrip->CurrentValue = $this->descrip->FormValue;
		$this->neto->CurrentValue = $this->neto->FormValue;
		$this->bruto->CurrentValue = $this->bruto->FormValue;
		$this->iva->CurrentValue = $this->iva->FormValue;
		$this->imp->CurrentValue = $this->imp->FormValue;
		$this->comcob->CurrentValue = $this->comcob->FormValue;
		$this->compag->CurrentValue = $this->compag->FormValue;
		$this->fechahora->CurrentValue = $this->fechahora->FormValue;
		$this->fechahora->CurrentValue = ew_UnFormatDateTime($this->fechahora->CurrentValue, 7);
		$this->usuario->CurrentValue = $this->usuario->FormValue;
		$this->porciva->CurrentValue = $this->porciva->FormValue;
		$this->tieneresol->CurrentValue = $this->tieneresol->FormValue;
		$this->concafac->CurrentValue = $this->concafac->FormValue;
		$this->tcomsal->CurrentValue = $this->tcomsal->FormValue;
		$this->seriesal->CurrentValue = $this->seriesal->FormValue;
		$this->ncompsal->CurrentValue = $this->ncompsal->FormValue;
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->codnum->setDbValue($rs->fields('codnum'));
		$this->tcomp->setDbValue($rs->fields('tcomp'));
		$this->serie->setDbValue($rs->fields('serie'));
		$this->ncomp->setDbValue($rs->fields('ncomp'));
		$this->nreng->setDbValue($rs->fields('nreng'));
		$this->codrem->setDbValue($rs->fields('codrem'));
		$this->codlote->setDbValue($rs->fields('codlote'));
		$this->descrip->setDbValue($rs->fields('descrip'));
		$this->neto->setDbValue($rs->fields('neto'));
		$this->bruto->setDbValue($rs->fields('bruto'));
		$this->iva->setDbValue($rs->fields('iva'));
		$this->imp->setDbValue($rs->fields('imp'));
		$this->comcob->setDbValue($rs->fields('comcob'));
		$this->compag->setDbValue($rs->fields('compag'));
		$this->fechahora->setDbValue($rs->fields('fechahora'));
		$this->usuario->setDbValue($rs->fields('usuario'));
		$this->porciva->setDbValue($rs->fields('porciva'));
		$this->tieneresol->setDbValue($rs->fields('tieneresol'));
		$this->concafac->setDbValue($rs->fields('concafac'));
		$this->tcomsal->setDbValue($rs->fields('tcomsal'));
		$this->seriesal->setDbValue($rs->fields('seriesal'));
		$this->ncompsal->setDbValue($rs->fields('ncompsal'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codnum->DbValue = $row['codnum'];
		$this->tcomp->DbValue = $row['tcomp'];
		$this->serie->DbValue = $row['serie'];
		$this->ncomp->DbValue = $row['ncomp'];
		$this->nreng->DbValue = $row['nreng'];
		$this->codrem->DbValue = $row['codrem'];
		$this->codlote->DbValue = $row['codlote'];
		$this->descrip->DbValue = $row['descrip'];
		$this->neto->DbValue = $row['neto'];
		$this->bruto->DbValue = $row['bruto'];
		$this->iva->DbValue = $row['iva'];
		$this->imp->DbValue = $row['imp'];
		$this->comcob->DbValue = $row['comcob'];
		$this->compag->DbValue = $row['compag'];
		$this->fechahora->DbValue = $row['fechahora'];
		$this->usuario->DbValue = $row['usuario'];
		$this->porciva->DbValue = $row['porciva'];
		$this->tieneresol->DbValue = $row['tieneresol'];
		$this->concafac->DbValue = $row['concafac'];
		$this->tcomsal->DbValue = $row['tcomsal'];
		$this->seriesal->DbValue = $row['seriesal'];
		$this->ncompsal->DbValue = $row['ncompsal'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		$arKeys[] = $this->RowOldKey;
		$cnt = count($arKeys);
		if ($cnt >= 0) {
		} else {
			$bValidKey = FALSE;
		}

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->neto->FormValue == $this->neto->CurrentValue && is_numeric(ew_StrToFloat($this->neto->CurrentValue)))
			$this->neto->CurrentValue = ew_StrToFloat($this->neto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bruto->FormValue == $this->bruto->CurrentValue && is_numeric(ew_StrToFloat($this->bruto->CurrentValue)))
			$this->bruto->CurrentValue = ew_StrToFloat($this->bruto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->iva->FormValue == $this->iva->CurrentValue && is_numeric(ew_StrToFloat($this->iva->CurrentValue)))
			$this->iva->CurrentValue = ew_StrToFloat($this->iva->CurrentValue);

		// Convert decimal values if posted back
		if ($this->imp->FormValue == $this->imp->CurrentValue && is_numeric(ew_StrToFloat($this->imp->CurrentValue)))
			$this->imp->CurrentValue = ew_StrToFloat($this->imp->CurrentValue);

		// Convert decimal values if posted back
		if ($this->comcob->FormValue == $this->comcob->CurrentValue && is_numeric(ew_StrToFloat($this->comcob->CurrentValue)))
			$this->comcob->CurrentValue = ew_StrToFloat($this->comcob->CurrentValue);

		// Convert decimal values if posted back
		if ($this->compag->FormValue == $this->compag->CurrentValue && is_numeric(ew_StrToFloat($this->compag->CurrentValue)))
			$this->compag->CurrentValue = ew_StrToFloat($this->compag->CurrentValue);

		// Convert decimal values if posted back
		if ($this->porciva->FormValue == $this->porciva->CurrentValue && is_numeric(ew_StrToFloat($this->porciva->CurrentValue)))
			$this->porciva->CurrentValue = ew_StrToFloat($this->porciva->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// codnum
		// tcomp
		// serie
		// ncomp
		// nreng
		// codrem
		// codlote
		// descrip
		// neto
		// bruto
		// iva
		// imp
		// comcob
		// compag
		// fechahora
		// usuario
		// porciva
		// tieneresol
		// concafac
		// tcomsal
		// seriesal
		// ncompsal

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codnum
			$this->codnum->ViewValue = $this->codnum->CurrentValue;
			$this->codnum->ViewCustomAttributes = "";

			// tcomp
			$this->tcomp->ViewValue = $this->tcomp->CurrentValue;
			$this->tcomp->ViewCustomAttributes = "";

			// serie
			$this->serie->ViewValue = $this->serie->CurrentValue;
			$this->serie->ViewCustomAttributes = "";

			// ncomp
			$this->ncomp->ViewValue = $this->ncomp->CurrentValue;
			$this->ncomp->ViewCustomAttributes = "";

			// nreng
			$this->nreng->ViewValue = $this->nreng->CurrentValue;
			$this->nreng->ViewCustomAttributes = "";

			// codrem
			$this->codrem->ViewValue = $this->codrem->CurrentValue;
			$this->codrem->ViewCustomAttributes = "";

			// codlote
			$this->codlote->ViewValue = $this->codlote->CurrentValue;
			$this->codlote->ViewCustomAttributes = "";

			// descrip
			$this->descrip->ViewValue = $this->descrip->CurrentValue;
			$this->descrip->ViewCustomAttributes = "";

			// neto
			$this->neto->ViewValue = $this->neto->CurrentValue;
			$this->neto->ViewCustomAttributes = "";

			// bruto
			$this->bruto->ViewValue = $this->bruto->CurrentValue;
			$this->bruto->ViewCustomAttributes = "";

			// iva
			$this->iva->ViewValue = $this->iva->CurrentValue;
			$this->iva->ViewCustomAttributes = "";

			// imp
			$this->imp->ViewValue = $this->imp->CurrentValue;
			$this->imp->ViewCustomAttributes = "";

			// comcob
			$this->comcob->ViewValue = $this->comcob->CurrentValue;
			$this->comcob->ViewCustomAttributes = "";

			// compag
			$this->compag->ViewValue = $this->compag->CurrentValue;
			$this->compag->ViewCustomAttributes = "";

			// fechahora
			$this->fechahora->ViewValue = $this->fechahora->CurrentValue;
			$this->fechahora->ViewValue = ew_FormatDateTime($this->fechahora->ViewValue, 7);
			$this->fechahora->ViewCustomAttributes = "";

			// usuario
			$this->usuario->ViewValue = $this->usuario->CurrentValue;
			$this->usuario->ViewCustomAttributes = "";

			// porciva
			$this->porciva->ViewValue = $this->porciva->CurrentValue;
			$this->porciva->ViewCustomAttributes = "";

			// tieneresol
			$this->tieneresol->ViewValue = $this->tieneresol->CurrentValue;
			$this->tieneresol->ViewCustomAttributes = "";

			// concafac
			if (strval($this->concafac->CurrentValue) <> "") {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->concafac->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codnum`, `descrip` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `concafact`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->concafac, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `descrip` ASC";
				$rswrk = $conn-> executeUpdate($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->concafac->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->concafac->ViewValue = $this->concafac->CurrentValue;
				}
			} else {
				$this->concafac->ViewValue = NULL;
			}
			$this->concafac->ViewCustomAttributes = "";

			// tcomsal
			$this->tcomsal->ViewValue = $this->tcomsal->CurrentValue;
			$this->tcomsal->ViewCustomAttributes = "";

			// seriesal
			$this->seriesal->ViewValue = $this->seriesal->CurrentValue;
			$this->seriesal->ViewCustomAttributes = "";

			// ncompsal
			$this->ncompsal->ViewValue = $this->ncompsal->CurrentValue;
			$this->ncompsal->ViewCustomAttributes = "";

			// codnum
			$this->codnum->LinkCustomAttributes = "";
			$this->codnum->HrefValue = "";
			$this->codnum->TooltipValue = "";

			// tcomp
			$this->tcomp->LinkCustomAttributes = "";
			$this->tcomp->HrefValue = "";
			$this->tcomp->TooltipValue = "";

			// serie
			$this->serie->LinkCustomAttributes = "";
			$this->serie->HrefValue = "";
			$this->serie->TooltipValue = "";

			// ncomp
			$this->ncomp->LinkCustomAttributes = "";
			$this->ncomp->HrefValue = "";
			$this->ncomp->TooltipValue = "";

			// nreng
			$this->nreng->LinkCustomAttributes = "";
			$this->nreng->HrefValue = "";
			$this->nreng->TooltipValue = "";

			// codrem
			$this->codrem->LinkCustomAttributes = "";
			$this->codrem->HrefValue = "";
			$this->codrem->TooltipValue = "";

			// codlote
			$this->codlote->LinkCustomAttributes = "";
			$this->codlote->HrefValue = "";
			$this->codlote->TooltipValue = "";

			// descrip
			$this->descrip->LinkCustomAttributes = "";
			$this->descrip->HrefValue = "";
			$this->descrip->TooltipValue = "";

			// neto
			$this->neto->LinkCustomAttributes = "";
			$this->neto->HrefValue = "";
			$this->neto->TooltipValue = "";

			// bruto
			$this->bruto->LinkCustomAttributes = "";
			$this->bruto->HrefValue = "";
			$this->bruto->TooltipValue = "";

			// iva
			$this->iva->LinkCustomAttributes = "";
			$this->iva->HrefValue = "";
			$this->iva->TooltipValue = "";

			// imp
			$this->imp->LinkCustomAttributes = "";
			$this->imp->HrefValue = "";
			$this->imp->TooltipValue = "";

			// comcob
			$this->comcob->LinkCustomAttributes = "";
			$this->comcob->HrefValue = "";
			$this->comcob->TooltipValue = "";

			// compag
			$this->compag->LinkCustomAttributes = "";
			$this->compag->HrefValue = "";
			$this->compag->TooltipValue = "";

			// fechahora
			$this->fechahora->LinkCustomAttributes = "";
			$this->fechahora->HrefValue = "";
			$this->fechahora->TooltipValue = "";

			// usuario
			$this->usuario->LinkCustomAttributes = "";
			$this->usuario->HrefValue = "";
			$this->usuario->TooltipValue = "";

			// porciva
			$this->porciva->LinkCustomAttributes = "";
			$this->porciva->HrefValue = "";
			$this->porciva->TooltipValue = "";

			// tieneresol
			$this->tieneresol->LinkCustomAttributes = "";
			$this->tieneresol->HrefValue = "";
			$this->tieneresol->TooltipValue = "";

			// concafac
			$this->concafac->LinkCustomAttributes = "";
			$this->concafac->HrefValue = "";
			$this->concafac->TooltipValue = "";

			// tcomsal
			$this->tcomsal->LinkCustomAttributes = "";
			$this->tcomsal->HrefValue = "";
			$this->tcomsal->TooltipValue = "";

			// seriesal
			$this->seriesal->LinkCustomAttributes = "";
			$this->seriesal->HrefValue = "";
			$this->seriesal->TooltipValue = "";

			// ncompsal
			$this->ncompsal->LinkCustomAttributes = "";
			$this->ncompsal->HrefValue = "";
			$this->ncompsal->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// codnum
			$this->codnum->EditAttrs["class"] = "form-control";
			$this->codnum->EditCustomAttributes = "";
			$this->codnum->EditValue = ew_HtmlEncode($this->codnum->CurrentValue);
			$this->codnum->PlaceHolder = ew_RemoveHtml($this->codnum->FldCaption());

			// tcomp
			$this->tcomp->EditAttrs["class"] = "form-control";
			$this->tcomp->EditCustomAttributes = "";
			if ($this->tcomp->getSessionValue() <> "") {
				$this->tcomp->CurrentValue = $this->tcomp->getSessionValue();
				$this->tcomp->OldValue = $this->tcomp->CurrentValue;
			$this->tcomp->ViewValue = $this->tcomp->CurrentValue;
			$this->tcomp->ViewCustomAttributes = "";
			} else {
			$this->tcomp->EditValue = ew_HtmlEncode($this->tcomp->CurrentValue);
			$this->tcomp->PlaceHolder = ew_RemoveHtml($this->tcomp->FldCaption());
			}

			// serie
			$this->serie->EditAttrs["class"] = "form-control";
			$this->serie->EditCustomAttributes = "";
			if ($this->serie->getSessionValue() <> "") {
				$this->serie->CurrentValue = $this->serie->getSessionValue();
				$this->serie->OldValue = $this->serie->CurrentValue;
			$this->serie->ViewValue = $this->serie->CurrentValue;
			$this->serie->ViewCustomAttributes = "";
			} else {
			$this->serie->EditValue = ew_HtmlEncode($this->serie->CurrentValue);
			$this->serie->PlaceHolder = ew_RemoveHtml($this->serie->FldCaption());
			}

			// ncomp
			$this->ncomp->EditAttrs["class"] = "form-control";
			$this->ncomp->EditCustomAttributes = "";
			if ($this->ncomp->getSessionValue() <> "") {
				$this->ncomp->CurrentValue = $this->ncomp->getSessionValue();
				$this->ncomp->OldValue = $this->ncomp->CurrentValue;
			$this->ncomp->ViewValue = $this->ncomp->CurrentValue;
			$this->ncomp->ViewCustomAttributes = "";
			} else {
			$this->ncomp->EditValue = ew_HtmlEncode($this->ncomp->CurrentValue);
			$this->ncomp->PlaceHolder = ew_RemoveHtml($this->ncomp->FldCaption());
			}

			// nreng
			$this->nreng->EditAttrs["class"] = "form-control";
			$this->nreng->EditCustomAttributes = "";
			$this->nreng->EditValue = ew_HtmlEncode($this->nreng->CurrentValue);
			$this->nreng->PlaceHolder = ew_RemoveHtml($this->nreng->FldCaption());

			// codrem
			$this->codrem->EditAttrs["class"] = "form-control";
			$this->codrem->EditCustomAttributes = "";
			$this->codrem->EditValue = ew_HtmlEncode($this->codrem->CurrentValue);
			$this->codrem->PlaceHolder = ew_RemoveHtml($this->codrem->FldCaption());

			// codlote
			$this->codlote->EditAttrs["class"] = "form-control";
			$this->codlote->EditCustomAttributes = "";
			$this->codlote->EditValue = ew_HtmlEncode($this->codlote->CurrentValue);
			$this->codlote->PlaceHolder = ew_RemoveHtml($this->codlote->FldCaption());

			// descrip
			$this->descrip->EditAttrs["class"] = "form-control";
			$this->descrip->EditCustomAttributes = "";
			$this->descrip->EditValue = ew_HtmlEncode($this->descrip->CurrentValue);
			$this->descrip->PlaceHolder = ew_RemoveHtml($this->descrip->FldCaption());

			// neto
			$this->neto->EditAttrs["class"] = "form-control";
			$this->neto->EditCustomAttributes = "";
			$this->neto->EditValue = ew_HtmlEncode($this->neto->CurrentValue);
			$this->neto->PlaceHolder = ew_RemoveHtml($this->neto->FldCaption());
			if (strval($this->neto->EditValue) <> "" && is_numeric($this->neto->EditValue)) {
			$this->neto->EditValue = ew_FormatNumber($this->neto->EditValue, -2, -1, -2, 0);
			$this->neto->OldValue = $this->neto->EditValue;
			}

			// bruto
			$this->bruto->EditAttrs["class"] = "form-control";
			$this->bruto->EditCustomAttributes = "";
			$this->bruto->EditValue = ew_HtmlEncode($this->bruto->CurrentValue);
			$this->bruto->PlaceHolder = ew_RemoveHtml($this->bruto->FldCaption());
			if (strval($this->bruto->EditValue) <> "" && is_numeric($this->bruto->EditValue)) {
			$this->bruto->EditValue = ew_FormatNumber($this->bruto->EditValue, -2, -1, -2, 0);
			$this->bruto->OldValue = $this->bruto->EditValue;
			}

			// iva
			$this->iva->EditAttrs["class"] = "form-control";
			$this->iva->EditCustomAttributes = "";
			$this->iva->EditValue = ew_HtmlEncode($this->iva->CurrentValue);
			$this->iva->PlaceHolder = ew_RemoveHtml($this->iva->FldCaption());
			if (strval($this->iva->EditValue) <> "" && is_numeric($this->iva->EditValue)) {
			$this->iva->EditValue = ew_FormatNumber($this->iva->EditValue, -2, -1, -2, 0);
			$this->iva->OldValue = $this->iva->EditValue;
			}

			// imp
			$this->imp->EditAttrs["class"] = "form-control";
			$this->imp->EditCustomAttributes = "";
			$this->imp->EditValue = ew_HtmlEncode($this->imp->CurrentValue);
			$this->imp->PlaceHolder = ew_RemoveHtml($this->imp->FldCaption());
			if (strval($this->imp->EditValue) <> "" && is_numeric($this->imp->EditValue)) {
			$this->imp->EditValue = ew_FormatNumber($this->imp->EditValue, -2, -1, -2, 0);
			$this->imp->OldValue = $this->imp->EditValue;
			}

			// comcob
			$this->comcob->EditAttrs["class"] = "form-control";
			$this->comcob->EditCustomAttributes = "";
			$this->comcob->EditValue = ew_HtmlEncode($this->comcob->CurrentValue);
			$this->comcob->PlaceHolder = ew_RemoveHtml($this->comcob->FldCaption());
			if (strval($this->comcob->EditValue) <> "" && is_numeric($this->comcob->EditValue)) {
			$this->comcob->EditValue = ew_FormatNumber($this->comcob->EditValue, -2, -1, -2, 0);
			$this->comcob->OldValue = $this->comcob->EditValue;
			}

			// compag
			$this->compag->EditAttrs["class"] = "form-control";
			$this->compag->EditCustomAttributes = "";
			$this->compag->EditValue = ew_HtmlEncode($this->compag->CurrentValue);
			$this->compag->PlaceHolder = ew_RemoveHtml($this->compag->FldCaption());
			if (strval($this->compag->EditValue) <> "" && is_numeric($this->compag->EditValue)) {
			$this->compag->EditValue = ew_FormatNumber($this->compag->EditValue, -2, -1, -2, 0);
			$this->compag->OldValue = $this->compag->EditValue;
			}

			// fechahora
			// usuario
			// porciva

			$this->porciva->EditAttrs["class"] = "form-control";
			$this->porciva->EditCustomAttributes = "";
			$this->porciva->EditValue = ew_HtmlEncode($this->porciva->CurrentValue);
			$this->porciva->PlaceHolder = ew_RemoveHtml($this->porciva->FldCaption());
			if (strval($this->porciva->EditValue) <> "" && is_numeric($this->porciva->EditValue)) {
			$this->porciva->EditValue = ew_FormatNumber($this->porciva->EditValue, -2, -1, -2, 0);
			$this->porciva->OldValue = $this->porciva->EditValue;
			}

			// tieneresol
			$this->tieneresol->EditAttrs["class"] = "form-control";
			$this->tieneresol->EditCustomAttributes = "";
			$this->tieneresol->EditValue = ew_HtmlEncode($this->tieneresol->CurrentValue);
			$this->tieneresol->PlaceHolder = ew_RemoveHtml($this->tieneresol->FldCaption());

			// concafac
			$this->concafac->EditAttrs["class"] = "form-control";
			$this->concafac->EditCustomAttributes = "";
			if (trim(strval($this->concafac->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->concafac->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codnum`, `descrip` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `concafact`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->concafac, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `descrip` ASC";
			$rswrk = $conn-> executeUpdate($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->concafac->EditValue = $arwrk;

			// tcomsal
			$this->tcomsal->EditAttrs["class"] = "form-control";
			$this->tcomsal->EditCustomAttributes = "";
			$this->tcomsal->EditValue = ew_HtmlEncode($this->tcomsal->CurrentValue);
			$this->tcomsal->PlaceHolder = ew_RemoveHtml($this->tcomsal->FldCaption());

			// seriesal
			$this->seriesal->EditAttrs["class"] = "form-control";
			$this->seriesal->EditCustomAttributes = "";
			$this->seriesal->EditValue = ew_HtmlEncode($this->seriesal->CurrentValue);
			$this->seriesal->PlaceHolder = ew_RemoveHtml($this->seriesal->FldCaption());

			// ncompsal
			$this->ncompsal->EditAttrs["class"] = "form-control";
			$this->ncompsal->EditCustomAttributes = "";
			$this->ncompsal->EditValue = ew_HtmlEncode($this->ncompsal->CurrentValue);
			$this->ncompsal->PlaceHolder = ew_RemoveHtml($this->ncompsal->FldCaption());

			// Edit refer script
			// codnum

			$this->codnum->HrefValue = "";

			// tcomp
			$this->tcomp->HrefValue = "";

			// serie
			$this->serie->HrefValue = "";

			// ncomp
			$this->ncomp->HrefValue = "";

			// nreng
			$this->nreng->HrefValue = "";

			// codrem
			$this->codrem->HrefValue = "";

			// codlote
			$this->codlote->HrefValue = "";

			// descrip
			$this->descrip->HrefValue = "";

			// neto
			$this->neto->HrefValue = "";

			// bruto
			$this->bruto->HrefValue = "";

			// iva
			$this->iva->HrefValue = "";

			// imp
			$this->imp->HrefValue = "";

			// comcob
			$this->comcob->HrefValue = "";

			// compag
			$this->compag->HrefValue = "";

			// fechahora
			$this->fechahora->HrefValue = "";

			// usuario
			$this->usuario->HrefValue = "";

			// porciva
			$this->porciva->HrefValue = "";

			// tieneresol
			$this->tieneresol->HrefValue = "";

			// concafac
			$this->concafac->HrefValue = "";

			// tcomsal
			$this->tcomsal->HrefValue = "";

			// seriesal
			$this->seriesal->HrefValue = "";

			// ncompsal
			$this->ncompsal->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// codnum
			$this->codnum->EditAttrs["class"] = "form-control";
			$this->codnum->EditCustomAttributes = "";
			$this->codnum->EditValue = ew_HtmlEncode($this->codnum->CurrentValue);
			$this->codnum->PlaceHolder = ew_RemoveHtml($this->codnum->FldCaption());

			// tcomp
			$this->tcomp->EditAttrs["class"] = "form-control";
			$this->tcomp->EditCustomAttributes = "";
			if ($this->tcomp->getSessionValue() <> "") {
				$this->tcomp->CurrentValue = $this->tcomp->getSessionValue();
				$this->tcomp->OldValue = $this->tcomp->CurrentValue;
			$this->tcomp->ViewValue = $this->tcomp->CurrentValue;
			$this->tcomp->ViewCustomAttributes = "";
			} else {
			$this->tcomp->EditValue = ew_HtmlEncode($this->tcomp->CurrentValue);
			$this->tcomp->PlaceHolder = ew_RemoveHtml($this->tcomp->FldCaption());
			}

			// serie
			$this->serie->EditAttrs["class"] = "form-control";
			$this->serie->EditCustomAttributes = "";
			if ($this->serie->getSessionValue() <> "") {
				$this->serie->CurrentValue = $this->serie->getSessionValue();
				$this->serie->OldValue = $this->serie->CurrentValue;
			$this->serie->ViewValue = $this->serie->CurrentValue;
			$this->serie->ViewCustomAttributes = "";
			} else {
			$this->serie->EditValue = ew_HtmlEncode($this->serie->CurrentValue);
			$this->serie->PlaceHolder = ew_RemoveHtml($this->serie->FldCaption());
			}

			// ncomp
			$this->ncomp->EditAttrs["class"] = "form-control";
			$this->ncomp->EditCustomAttributes = "";
			if ($this->ncomp->getSessionValue() <> "") {
				$this->ncomp->CurrentValue = $this->ncomp->getSessionValue();
				$this->ncomp->OldValue = $this->ncomp->CurrentValue;
			$this->ncomp->ViewValue = $this->ncomp->CurrentValue;
			$this->ncomp->ViewCustomAttributes = "";
			} else {
			$this->ncomp->EditValue = ew_HtmlEncode($this->ncomp->CurrentValue);
			$this->ncomp->PlaceHolder = ew_RemoveHtml($this->ncomp->FldCaption());
			}

			// nreng
			$this->nreng->EditAttrs["class"] = "form-control";
			$this->nreng->EditCustomAttributes = "";
			$this->nreng->EditValue = ew_HtmlEncode($this->nreng->CurrentValue);
			$this->nreng->PlaceHolder = ew_RemoveHtml($this->nreng->FldCaption());

			// codrem
			$this->codrem->EditAttrs["class"] = "form-control";
			$this->codrem->EditCustomAttributes = "";
			$this->codrem->EditValue = ew_HtmlEncode($this->codrem->CurrentValue);
			$this->codrem->PlaceHolder = ew_RemoveHtml($this->codrem->FldCaption());

			// codlote
			$this->codlote->EditAttrs["class"] = "form-control";
			$this->codlote->EditCustomAttributes = "";
			$this->codlote->EditValue = ew_HtmlEncode($this->codlote->CurrentValue);
			$this->codlote->PlaceHolder = ew_RemoveHtml($this->codlote->FldCaption());

			// descrip
			$this->descrip->EditAttrs["class"] = "form-control";
			$this->descrip->EditCustomAttributes = "";
			$this->descrip->EditValue = ew_HtmlEncode($this->descrip->CurrentValue);
			$this->descrip->PlaceHolder = ew_RemoveHtml($this->descrip->FldCaption());

			// neto
			$this->neto->EditAttrs["class"] = "form-control";
			$this->neto->EditCustomAttributes = "";
			$this->neto->EditValue = ew_HtmlEncode($this->neto->CurrentValue);
			$this->neto->PlaceHolder = ew_RemoveHtml($this->neto->FldCaption());
			if (strval($this->neto->EditValue) <> "" && is_numeric($this->neto->EditValue)) {
			$this->neto->EditValue = ew_FormatNumber($this->neto->EditValue, -2, -1, -2, 0);
			$this->neto->OldValue = $this->neto->EditValue;
			}

			// bruto
			$this->bruto->EditAttrs["class"] = "form-control";
			$this->bruto->EditCustomAttributes = "";
			$this->bruto->EditValue = ew_HtmlEncode($this->bruto->CurrentValue);
			$this->bruto->PlaceHolder = ew_RemoveHtml($this->bruto->FldCaption());
			if (strval($this->bruto->EditValue) <> "" && is_numeric($this->bruto->EditValue)) {
			$this->bruto->EditValue = ew_FormatNumber($this->bruto->EditValue, -2, -1, -2, 0);
			$this->bruto->OldValue = $this->bruto->EditValue;
			}

			// iva
			$this->iva->EditAttrs["class"] = "form-control";
			$this->iva->EditCustomAttributes = "";
			$this->iva->EditValue = ew_HtmlEncode($this->iva->CurrentValue);
			$this->iva->PlaceHolder = ew_RemoveHtml($this->iva->FldCaption());
			if (strval($this->iva->EditValue) <> "" && is_numeric($this->iva->EditValue)) {
			$this->iva->EditValue = ew_FormatNumber($this->iva->EditValue, -2, -1, -2, 0);
			$this->iva->OldValue = $this->iva->EditValue;
			}

			// imp
			$this->imp->EditAttrs["class"] = "form-control";
			$this->imp->EditCustomAttributes = "";
			$this->imp->EditValue = ew_HtmlEncode($this->imp->CurrentValue);
			$this->imp->PlaceHolder = ew_RemoveHtml($this->imp->FldCaption());
			if (strval($this->imp->EditValue) <> "" && is_numeric($this->imp->EditValue)) {
			$this->imp->EditValue = ew_FormatNumber($this->imp->EditValue, -2, -1, -2, 0);
			$this->imp->OldValue = $this->imp->EditValue;
			}

			// comcob
			$this->comcob->EditAttrs["class"] = "form-control";
			$this->comcob->EditCustomAttributes = "";
			$this->comcob->EditValue = ew_HtmlEncode($this->comcob->CurrentValue);
			$this->comcob->PlaceHolder = ew_RemoveHtml($this->comcob->FldCaption());
			if (strval($this->comcob->EditValue) <> "" && is_numeric($this->comcob->EditValue)) {
			$this->comcob->EditValue = ew_FormatNumber($this->comcob->EditValue, -2, -1, -2, 0);
			$this->comcob->OldValue = $this->comcob->EditValue;
			}

			// compag
			$this->compag->EditAttrs["class"] = "form-control";
			$this->compag->EditCustomAttributes = "";
			$this->compag->EditValue = ew_HtmlEncode($this->compag->CurrentValue);
			$this->compag->PlaceHolder = ew_RemoveHtml($this->compag->FldCaption());
			if (strval($this->compag->EditValue) <> "" && is_numeric($this->compag->EditValue)) {
			$this->compag->EditValue = ew_FormatNumber($this->compag->EditValue, -2, -1, -2, 0);
			$this->compag->OldValue = $this->compag->EditValue;
			}

			// fechahora
			// usuario
			// porciva

			$this->porciva->EditAttrs["class"] = "form-control";
			$this->porciva->EditCustomAttributes = "";
			$this->porciva->EditValue = ew_HtmlEncode($this->porciva->CurrentValue);
			$this->porciva->PlaceHolder = ew_RemoveHtml($this->porciva->FldCaption());
			if (strval($this->porciva->EditValue) <> "" && is_numeric($this->porciva->EditValue)) {
			$this->porciva->EditValue = ew_FormatNumber($this->porciva->EditValue, -2, -1, -2, 0);
			$this->porciva->OldValue = $this->porciva->EditValue;
			}

			// tieneresol
			$this->tieneresol->EditAttrs["class"] = "form-control";
			$this->tieneresol->EditCustomAttributes = "";
			$this->tieneresol->EditValue = ew_HtmlEncode($this->tieneresol->CurrentValue);
			$this->tieneresol->PlaceHolder = ew_RemoveHtml($this->tieneresol->FldCaption());

			// concafac
			$this->concafac->EditAttrs["class"] = "form-control";
			$this->concafac->EditCustomAttributes = "";
			if (trim(strval($this->concafac->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->concafac->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codnum`, `descrip` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `concafact`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->concafac, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `descrip` ASC";
			$rswrk = $conn-> executeUpdate($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->concafac->EditValue = $arwrk;

			// tcomsal
			$this->tcomsal->EditAttrs["class"] = "form-control";
			$this->tcomsal->EditCustomAttributes = "";
			$this->tcomsal->EditValue = ew_HtmlEncode($this->tcomsal->CurrentValue);
			$this->tcomsal->PlaceHolder = ew_RemoveHtml($this->tcomsal->FldCaption());

			// seriesal
			$this->seriesal->EditAttrs["class"] = "form-control";
			$this->seriesal->EditCustomAttributes = "";
			$this->seriesal->EditValue = ew_HtmlEncode($this->seriesal->CurrentValue);
			$this->seriesal->PlaceHolder = ew_RemoveHtml($this->seriesal->FldCaption());

			// ncompsal
			$this->ncompsal->EditAttrs["class"] = "form-control";
			$this->ncompsal->EditCustomAttributes = "";
			$this->ncompsal->EditValue = ew_HtmlEncode($this->ncompsal->CurrentValue);
			$this->ncompsal->PlaceHolder = ew_RemoveHtml($this->ncompsal->FldCaption());

			// Edit refer script
			// codnum

			$this->codnum->HrefValue = "";

			// tcomp
			$this->tcomp->HrefValue = "";

			// serie
			$this->serie->HrefValue = "";

			// ncomp
			$this->ncomp->HrefValue = "";

			// nreng
			$this->nreng->HrefValue = "";

			// codrem
			$this->codrem->HrefValue = "";

			// codlote
			$this->codlote->HrefValue = "";

			// descrip
			$this->descrip->HrefValue = "";

			// neto
			$this->neto->HrefValue = "";

			// bruto
			$this->bruto->HrefValue = "";

			// iva
			$this->iva->HrefValue = "";

			// imp
			$this->imp->HrefValue = "";

			// comcob
			$this->comcob->HrefValue = "";

			// compag
			$this->compag->HrefValue = "";

			// fechahora
			$this->fechahora->HrefValue = "";

			// usuario
			$this->usuario->HrefValue = "";

			// porciva
			$this->porciva->HrefValue = "";

			// tieneresol
			$this->tieneresol->HrefValue = "";

			// concafac
			$this->concafac->HrefValue = "";

			// tcomsal
			$this->tcomsal->HrefValue = "";

			// seriesal
			$this->seriesal->HrefValue = "";

			// ncompsal
			$this->ncompsal->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->codnum->FldIsDetailKey && !is_null($this->codnum->FormValue) && $this->codnum->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->codnum->FldCaption(), $this->codnum->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->codnum->FormValue)) {
			ew_AddMessage($gsFormError, $this->codnum->FldErrMsg());
		}
		if (!$this->tcomp->FldIsDetailKey && !is_null($this->tcomp->FormValue) && $this->tcomp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tcomp->FldCaption(), $this->tcomp->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->tcomp->FormValue)) {
			ew_AddMessage($gsFormError, $this->tcomp->FldErrMsg());
		}
		if (!$this->serie->FldIsDetailKey && !is_null($this->serie->FormValue) && $this->serie->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->serie->FldCaption(), $this->serie->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->serie->FormValue)) {
			ew_AddMessage($gsFormError, $this->serie->FldErrMsg());
		}
		if (!$this->ncomp->FldIsDetailKey && !is_null($this->ncomp->FormValue) && $this->ncomp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ncomp->FldCaption(), $this->ncomp->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->ncomp->FormValue)) {
			ew_AddMessage($gsFormError, $this->ncomp->FldErrMsg());
		}
		if (!$this->nreng->FldIsDetailKey && !is_null($this->nreng->FormValue) && $this->nreng->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nreng->FldCaption(), $this->nreng->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->nreng->FormValue)) {
			ew_AddMessage($gsFormError, $this->nreng->FldErrMsg());
		}
		if (!ew_CheckInteger($this->codrem->FormValue)) {
			ew_AddMessage($gsFormError, $this->codrem->FldErrMsg());
		}
		if (!ew_CheckInteger($this->codlote->FormValue)) {
			ew_AddMessage($gsFormError, $this->codlote->FldErrMsg());
		}
		if (!ew_CheckNumber($this->neto->FormValue)) {
			ew_AddMessage($gsFormError, $this->neto->FldErrMsg());
		}
		if (!ew_CheckNumber($this->bruto->FormValue)) {
			ew_AddMessage($gsFormError, $this->bruto->FldErrMsg());
		}
		if (!ew_CheckNumber($this->iva->FormValue)) {
			ew_AddMessage($gsFormError, $this->iva->FldErrMsg());
		}
		if (!ew_CheckNumber($this->imp->FormValue)) {
			ew_AddMessage($gsFormError, $this->imp->FldErrMsg());
		}
		if (!ew_CheckNumber($this->comcob->FormValue)) {
			ew_AddMessage($gsFormError, $this->comcob->FldErrMsg());
		}
		if (!ew_CheckNumber($this->compag->FormValue)) {
			ew_AddMessage($gsFormError, $this->compag->FldErrMsg());
		}
		if (!ew_CheckNumber($this->porciva->FormValue)) {
			ew_AddMessage($gsFormError, $this->porciva->FldErrMsg());
		}
		if (!ew_CheckInteger($this->tieneresol->FormValue)) {
			ew_AddMessage($gsFormError, $this->tieneresol->FldErrMsg());
		}
		if (!ew_CheckInteger($this->tcomsal->FormValue)) {
			ew_AddMessage($gsFormError, $this->tcomsal->FldErrMsg());
		}
		if (!ew_CheckInteger($this->seriesal->FormValue)) {
			ew_AddMessage($gsFormError, $this->seriesal->FldErrMsg());
		}
		if (!ew_CheckInteger($this->ncompsal->FormValue)) {
			ew_AddMessage($gsFormError, $this->ncompsal->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn-> executeUpdate($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn-> executeUpdate($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// codnum
			$this->codnum->SetDbValueDef($rsnew, $this->codnum->CurrentValue, 0, $this->codnum->ReadOnly);

			// tcomp
			$this->tcomp->SetDbValueDef($rsnew, $this->tcomp->CurrentValue, 0, $this->tcomp->ReadOnly);

			// serie
			$this->serie->SetDbValueDef($rsnew, $this->serie->CurrentValue, 0, $this->serie->ReadOnly);

			// ncomp
			$this->ncomp->SetDbValueDef($rsnew, $this->ncomp->CurrentValue, 0, $this->ncomp->ReadOnly);

			// nreng
			$this->nreng->SetDbValueDef($rsnew, $this->nreng->CurrentValue, 0, $this->nreng->ReadOnly);

			// codrem
			$this->codrem->SetDbValueDef($rsnew, $this->codrem->CurrentValue, NULL, $this->codrem->ReadOnly);

			// codlote
			$this->codlote->SetDbValueDef($rsnew, $this->codlote->CurrentValue, NULL, $this->codlote->ReadOnly);

			// descrip
			$this->descrip->SetDbValueDef($rsnew, $this->descrip->CurrentValue, NULL, $this->descrip->ReadOnly);

			// neto
			$this->neto->SetDbValueDef($rsnew, $this->neto->CurrentValue, NULL, $this->neto->ReadOnly);

			// bruto
			$this->bruto->SetDbValueDef($rsnew, $this->bruto->CurrentValue, NULL, $this->bruto->ReadOnly);

			// iva
			$this->iva->SetDbValueDef($rsnew, $this->iva->CurrentValue, NULL, $this->iva->ReadOnly);

			// imp
			$this->imp->SetDbValueDef($rsnew, $this->imp->CurrentValue, NULL, $this->imp->ReadOnly);

			// comcob
			$this->comcob->SetDbValueDef($rsnew, $this->comcob->CurrentValue, NULL, $this->comcob->ReadOnly);

			// compag
			$this->compag->SetDbValueDef($rsnew, $this->compag->CurrentValue, NULL, $this->compag->ReadOnly);

			// fechahora
			$this->fechahora->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
			$rsnew['fechahora'] = &$this->fechahora->DbValue;

			// usuario
			$this->usuario->SetDbValueDef($rsnew, CurrentUserID(), NULL);
			$rsnew['usuario'] = &$this->usuario->DbValue;

			// porciva
			$this->porciva->SetDbValueDef($rsnew, $this->porciva->CurrentValue, NULL, $this->porciva->ReadOnly);

			// tieneresol
			$this->tieneresol->SetDbValueDef($rsnew, $this->tieneresol->CurrentValue, NULL, $this->tieneresol->ReadOnly);

			// concafac
			$this->concafac->SetDbValueDef($rsnew, $this->concafac->CurrentValue, NULL, $this->concafac->ReadOnly);

			// tcomsal
			$this->tcomsal->SetDbValueDef($rsnew, $this->tcomsal->CurrentValue, NULL, $this->tcomsal->ReadOnly);

			// seriesal
			$this->seriesal->SetDbValueDef($rsnew, $this->seriesal->CurrentValue, NULL, $this->seriesal->ReadOnly);

			// ncompsal
			$this->ncompsal->SetDbValueDef($rsnew, $this->ncompsal->CurrentValue, NULL, $this->ncompsal->ReadOnly);

			// Check referential integrity for master table 'cabfacpro'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_cabfacpro();
			$KeyValue = isset($rsnew['tcomp']) ? $rsnew['tcomp'] : $rsold['tcomp'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@tcomp@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			$KeyValue = isset($rsnew['serie']) ? $rsnew['serie'] : $rsold['serie'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@serie@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			$KeyValue = isset($rsnew['ncomp']) ? $rsnew['ncomp'] : $rsold['ncomp'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@ncomp@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				$rsmaster = $GLOBALS["cabfacpro"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "cabfacpro", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Set up foreign key field value from Session
			if ($this->getCurrentMasterTable() == "cabfacpro") {
				$this->tcomp->CurrentValue = $this->tcomp->getSessionValue();
				$this->serie->CurrentValue = $this->serie->getSessionValue();
				$this->ncomp->CurrentValue = $this->ncomp->getSessionValue();
			}

		// Check referential integrity for master table 'cabfacpro'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_cabfacpro();
		if (strval($this->tcomp->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@tcomp@", ew_AdjustSql($this->tcomp->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if (strval($this->serie->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@serie@", ew_AdjustSql($this->serie->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if (strval($this->ncomp->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@ncomp@", ew_AdjustSql($this->ncomp->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["cabfacpro"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "cabfacpro", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// codnum
		$this->codnum->SetDbValueDef($rsnew, $this->codnum->CurrentValue, 0, strval($this->codnum->CurrentValue) == "");

		// tcomp
		$this->tcomp->SetDbValueDef($rsnew, $this->tcomp->CurrentValue, 0, FALSE);

		// serie
		$this->serie->SetDbValueDef($rsnew, $this->serie->CurrentValue, 0, FALSE);

		// ncomp
		$this->ncomp->SetDbValueDef($rsnew, $this->ncomp->CurrentValue, 0, FALSE);

		// nreng
		$this->nreng->SetDbValueDef($rsnew, $this->nreng->CurrentValue, 0, FALSE);

		// codrem
		$this->codrem->SetDbValueDef($rsnew, $this->codrem->CurrentValue, NULL, FALSE);

		// codlote
		$this->codlote->SetDbValueDef($rsnew, $this->codlote->CurrentValue, NULL, FALSE);

		// descrip
		$this->descrip->SetDbValueDef($rsnew, $this->descrip->CurrentValue, NULL, FALSE);

		// neto
		$this->neto->SetDbValueDef($rsnew, $this->neto->CurrentValue, NULL, strval($this->neto->CurrentValue) == "");

		// bruto
		$this->bruto->SetDbValueDef($rsnew, $this->bruto->CurrentValue, NULL, strval($this->bruto->CurrentValue) == "");

		// iva
		$this->iva->SetDbValueDef($rsnew, $this->iva->CurrentValue, NULL, strval($this->iva->CurrentValue) == "");

		// imp
		$this->imp->SetDbValueDef($rsnew, $this->imp->CurrentValue, NULL, strval($this->imp->CurrentValue) == "");

		// comcob
		$this->comcob->SetDbValueDef($rsnew, $this->comcob->CurrentValue, NULL, strval($this->comcob->CurrentValue) == "");

		// compag
		$this->compag->SetDbValueDef($rsnew, $this->compag->CurrentValue, NULL, strval($this->compag->CurrentValue) == "");

		// fechahora
		$this->fechahora->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['fechahora'] = &$this->fechahora->DbValue;

		// usuario
		$this->usuario->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['usuario'] = &$this->usuario->DbValue;

		// porciva
		$this->porciva->SetDbValueDef($rsnew, $this->porciva->CurrentValue, NULL, FALSE);

		// tieneresol
		$this->tieneresol->SetDbValueDef($rsnew, $this->tieneresol->CurrentValue, NULL, strval($this->tieneresol->CurrentValue) == "");

		// concafac
		$this->concafac->SetDbValueDef($rsnew, $this->concafac->CurrentValue, NULL, FALSE);

		// tcomsal
		$this->tcomsal->SetDbValueDef($rsnew, $this->tcomsal->CurrentValue, NULL, FALSE);

		// seriesal
		$this->seriesal->SetDbValueDef($rsnew, $this->seriesal->CurrentValue, NULL, FALSE);

		// ncompsal
		$this->ncompsal->SetDbValueDef($rsnew, $this->ncompsal->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {

		// Hide foreign keys
		$sMasterTblVar = $this->getCurrentMasterTable();
		if ($sMasterTblVar == "cabfacpro") {
			$this->tcomp->Visible = FALSE;
			if ($GLOBALS["cabfacpro"]->EventCancelled) $this->EventCancelled = TRUE;
			$this->serie->Visible = FALSE;
			if ($GLOBALS["cabfacpro"]->EventCancelled) $this->EventCancelled = TRUE;
			$this->ncomp->Visible = FALSE;
			if ($GLOBALS["cabfacpro"]->EventCancelled) $this->EventCancelled = TRUE;
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
