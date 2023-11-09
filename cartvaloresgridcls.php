<?php include_once "cartvaloresinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php

//
// Page class
//

$cartvalores_grid = NULL; // Initialize page object first

class ccartvalores_grid extends ccartvalores {

	// Page ID
	var $PageID = 'grid';

	// Project ID
	var $ProjectID = "{C8A55938-38ED-41FC-AF90-E3261742AEC3}";

	// Table name
	var $TableName = 'cartvalores';

	// Page object name
	var $PageObjName = 'cartvalores_grid';

	// Grid form hidden field names
	var $FormName = 'fcartvaloresgrid';
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
		$hidden = TRUE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
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

		// Table object (cartvalores)
		if (!isset($GLOBALS["cartvalores"]) || get_class($GLOBALS["cartvalores"]) == "ccartvalores") {
			$GLOBALS["cartvalores"] = &$this;

//			$GLOBALS["MasterTable"] = &$GLOBALS["Table"];
//			if (!isset($GLOBALS["Table"])) $GLOBALS["Table"] = &$GLOBALS["cartvalores"];

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
			define("EW_TABLE_NAME", 'cartvalores');

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
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->codnum->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $cartvalores;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cartvalores);
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
	var $DisplayRecs = 20;
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
			$this->DisplayRecs = 20; // Load default
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
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "cabfac") {
			global $cabfac;
			$rsmaster = $cabfac->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("cabfaclist.php"); // Return to master page
			} else {
				$cabfac->LoadListRowValues($rsmaster);
				$cabfac->RowType = EW_ROWTYPE_MASTER; // Master row
				$cabfac->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = EW_SELECT_LIMIT;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}
	}

	//  Exit inline mode
	function ClearInlineMode() {
		$this->importe->FormValue = ""; // Clear form value
		$this->cotiz->FormValue = ""; // Clear form value
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
		if (count($arrKeyFlds) >= 1) {
			$this->codnum->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->codnum->FormValue))
				return FALSE;
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
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->codnum->CurrentValue;

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
		if ($objForm->HasValue("x_tcomp") && $objForm->HasValue("o_tcomp") && $this->tcomp->CurrentValue <> $this->tcomp->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_serie") && $objForm->HasValue("o_serie") && $this->serie->CurrentValue <> $this->serie->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ncomp") && $objForm->HasValue("o_ncomp") && $this->ncomp->CurrentValue <> $this->ncomp->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_codban") && $objForm->HasValue("o_codban") && $this->codban->CurrentValue <> $this->codban->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_codsuc") && $objForm->HasValue("o_codsuc") && $this->codsuc->CurrentValue <> $this->codsuc->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_codcta") && $objForm->HasValue("o_codcta") && $this->codcta->CurrentValue <> $this->codcta->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tipcta") && $objForm->HasValue("o_tipcta") && $this->tipcta->CurrentValue <> $this->tipcta->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_codchq") && $objForm->HasValue("o_codchq") && $this->codchq->CurrentValue <> $this->codchq->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_codpais") && $objForm->HasValue("o_codpais") && $this->codpais->CurrentValue <> $this->codpais->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_importe") && $objForm->HasValue("o_importe") && $this->importe->CurrentValue <> $this->importe->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_fechaemis") && $objForm->HasValue("o_fechaemis") && $this->fechaemis->CurrentValue <> $this->fechaemis->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_fechapago") && $objForm->HasValue("o_fechapago") && $this->fechapago->CurrentValue <> $this->fechapago->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_entrego") && $objForm->HasValue("o_entrego") && $this->entrego->CurrentValue <> $this->entrego->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_recibio") && $objForm->HasValue("o_recibio") && $this->recibio->CurrentValue <> $this->recibio->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_fechaingr") && $objForm->HasValue("o_fechaingr") && $this->fechaingr->CurrentValue <> $this->fechaingr->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_fechaentrega") && $objForm->HasValue("o_fechaentrega") && $this->fechaentrega->CurrentValue <> $this->fechaentrega->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tcomprel") && $objForm->HasValue("o_tcomprel") && $this->tcomprel->CurrentValue <> $this->tcomprel->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_serierel") && $objForm->HasValue("o_serierel") && $this->serierel->CurrentValue <> $this->serierel->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ncomprel") && $objForm->HasValue("o_ncomprel") && $this->ncomprel->CurrentValue <> $this->ncomprel->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_estado") && $objForm->HasValue("o_estado") && $this->estado->CurrentValue <> $this->estado->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_moneda") && $objForm->HasValue("o_moneda") && $this->moneda->CurrentValue <> $this->moneda->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_fechahora") && $objForm->HasValue("o_fechahora") && $this->fechahora->CurrentValue <> $this->fechahora->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_usuario") && $objForm->HasValue("o_usuario") && $this->usuario->CurrentValue <> $this->usuario->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tcompsal") && $objForm->HasValue("o_tcompsal") && $this->tcompsal->CurrentValue <> $this->tcompsal->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_seriesal") && $objForm->HasValue("o_seriesal") && $this->seriesal->CurrentValue <> $this->seriesal->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ncompsal") && $objForm->HasValue("o_ncompsal") && $this->ncompsal->CurrentValue <> $this->ncompsal->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_codrem") && $objForm->HasValue("o_codrem") && $this->codrem->CurrentValue <> $this->codrem->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_cotiz") && $objForm->HasValue("o_cotiz") && $this->cotiz->CurrentValue <> $this->cotiz->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_usurel") && $objForm->HasValue("o_usurel") && $this->usurel->CurrentValue <> $this->usurel->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_fecharel") && $objForm->HasValue("o_fecharel") && $this->fecharel->CurrentValue <> $this->fecharel->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ususal") && $objForm->HasValue("o_ususal") && $this->ususal->CurrentValue <> $this->ususal->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_fechasal") && $objForm->HasValue("o_fechasal") && $this->fechasal->CurrentValue <> $this->fechasal->OldValue)
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
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
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
				$this->tcomprel->setSessionValue("");
				$this->serierel->setSessionValue("");
				$this->ncomprel->setSessionValue("");
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
				if (!$Security->CanDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"javascript:void(0);\" onclick=\"ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
		}
		if ($this->CurrentMode == "edit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->codnum->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();
	}

	// Set record key
	function SetRecordKey(&$key, $rs) {
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs->fields('codnum');
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
				$item->Visible = $Security->CanAdd();
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
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->codnum->CurrentValue = NULL;
		$this->codnum->OldValue = $this->codnum->CurrentValue;
		$this->tcomp->CurrentValue = NULL;
		$this->tcomp->OldValue = $this->tcomp->CurrentValue;
		$this->serie->CurrentValue = NULL;
		$this->serie->OldValue = $this->serie->CurrentValue;
		$this->ncomp->CurrentValue = NULL;
		$this->ncomp->OldValue = $this->ncomp->CurrentValue;
		$this->codban->CurrentValue = NULL;
		$this->codban->OldValue = $this->codban->CurrentValue;
		$this->codsuc->CurrentValue = NULL;
		$this->codsuc->OldValue = $this->codsuc->CurrentValue;
		$this->codcta->CurrentValue = NULL;
		$this->codcta->OldValue = $this->codcta->CurrentValue;
		$this->tipcta->CurrentValue = NULL;
		$this->tipcta->OldValue = $this->tipcta->CurrentValue;
		$this->codchq->CurrentValue = NULL;
		$this->codchq->OldValue = $this->codchq->CurrentValue;
		$this->codpais->CurrentValue = NULL;
		$this->codpais->OldValue = $this->codpais->CurrentValue;
		$this->importe->CurrentValue = 0.00;
		$this->importe->OldValue = $this->importe->CurrentValue;
		$this->fechaemis->CurrentValue = NULL;
		$this->fechaemis->OldValue = $this->fechaemis->CurrentValue;
		$this->fechapago->CurrentValue = NULL;
		$this->fechapago->OldValue = $this->fechapago->CurrentValue;
		$this->entrego->CurrentValue = NULL;
		$this->entrego->OldValue = $this->entrego->CurrentValue;
		$this->recibio->CurrentValue = NULL;
		$this->recibio->OldValue = $this->recibio->CurrentValue;
		$this->fechaingr->CurrentValue = NULL;
		$this->fechaingr->OldValue = $this->fechaingr->CurrentValue;
		$this->fechaentrega->CurrentValue = NULL;
		$this->fechaentrega->OldValue = $this->fechaentrega->CurrentValue;
		$this->tcomprel->CurrentValue = NULL;
		$this->tcomprel->OldValue = $this->tcomprel->CurrentValue;
		$this->serierel->CurrentValue = NULL;
		$this->serierel->OldValue = $this->serierel->CurrentValue;
		$this->ncomprel->CurrentValue = NULL;
		$this->ncomprel->OldValue = $this->ncomprel->CurrentValue;
		$this->estado->CurrentValue = NULL;
		$this->estado->OldValue = $this->estado->CurrentValue;
		$this->moneda->CurrentValue = 1;
		$this->moneda->OldValue = $this->moneda->CurrentValue;
		$this->fechahora->CurrentValue = NULL;
		$this->fechahora->OldValue = $this->fechahora->CurrentValue;
		$this->usuario->CurrentValue = NULL;
		$this->usuario->OldValue = $this->usuario->CurrentValue;
		$this->tcompsal->CurrentValue = NULL;
		$this->tcompsal->OldValue = $this->tcompsal->CurrentValue;
		$this->seriesal->CurrentValue = NULL;
		$this->seriesal->OldValue = $this->seriesal->CurrentValue;
		$this->ncompsal->CurrentValue = NULL;
		$this->ncompsal->OldValue = $this->ncompsal->CurrentValue;
		$this->codrem->CurrentValue = NULL;
		$this->codrem->OldValue = $this->codrem->CurrentValue;
		$this->cotiz->CurrentValue = NULL;
		$this->cotiz->OldValue = $this->cotiz->CurrentValue;
		$this->usurel->CurrentValue = NULL;
		$this->usurel->OldValue = $this->usurel->CurrentValue;
		$this->fecharel->CurrentValue = NULL;
		$this->fecharel->OldValue = $this->fecharel->CurrentValue;
		$this->ususal->CurrentValue = NULL;
		$this->ususal->OldValue = $this->ususal->CurrentValue;
		$this->fechasal->CurrentValue = NULL;
		$this->fechasal->OldValue = $this->fechasal->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$objForm->FormName = $this->FormName;
		if (!$this->codnum->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->codnum->setFormValue($objForm->GetValue("x_codnum"));
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
		if (!$this->codban->FldIsDetailKey) {
			$this->codban->setFormValue($objForm->GetValue("x_codban"));
		}
		$this->codban->setOldValue($objForm->GetValue("o_codban"));
		if (!$this->codsuc->FldIsDetailKey) {
			$this->codsuc->setFormValue($objForm->GetValue("x_codsuc"));
		}
		$this->codsuc->setOldValue($objForm->GetValue("o_codsuc"));
		if (!$this->codcta->FldIsDetailKey) {
			$this->codcta->setFormValue($objForm->GetValue("x_codcta"));
		}
		$this->codcta->setOldValue($objForm->GetValue("o_codcta"));
		if (!$this->tipcta->FldIsDetailKey) {
			$this->tipcta->setFormValue($objForm->GetValue("x_tipcta"));
		}
		$this->tipcta->setOldValue($objForm->GetValue("o_tipcta"));
		if (!$this->codchq->FldIsDetailKey) {
			$this->codchq->setFormValue($objForm->GetValue("x_codchq"));
		}
		$this->codchq->setOldValue($objForm->GetValue("o_codchq"));
		if (!$this->codpais->FldIsDetailKey) {
			$this->codpais->setFormValue($objForm->GetValue("x_codpais"));
		}
		$this->codpais->setOldValue($objForm->GetValue("o_codpais"));
		if (!$this->importe->FldIsDetailKey) {
			$this->importe->setFormValue($objForm->GetValue("x_importe"));
		}
		$this->importe->setOldValue($objForm->GetValue("o_importe"));
		if (!$this->fechaemis->FldIsDetailKey) {
			$this->fechaemis->setFormValue($objForm->GetValue("x_fechaemis"));
			$this->fechaemis->CurrentValue = ew_UnFormatDateTime($this->fechaemis->CurrentValue, 7);
		}
		$this->fechaemis->setOldValue($objForm->GetValue("o_fechaemis"));
		if (!$this->fechapago->FldIsDetailKey) {
			$this->fechapago->setFormValue($objForm->GetValue("x_fechapago"));
			$this->fechapago->CurrentValue = ew_UnFormatDateTime($this->fechapago->CurrentValue, 7);
		}
		$this->fechapago->setOldValue($objForm->GetValue("o_fechapago"));
		if (!$this->entrego->FldIsDetailKey) {
			$this->entrego->setFormValue($objForm->GetValue("x_entrego"));
		}
		$this->entrego->setOldValue($objForm->GetValue("o_entrego"));
		if (!$this->recibio->FldIsDetailKey) {
			$this->recibio->setFormValue($objForm->GetValue("x_recibio"));
		}
		$this->recibio->setOldValue($objForm->GetValue("o_recibio"));
		if (!$this->fechaingr->FldIsDetailKey) {
			$this->fechaingr->setFormValue($objForm->GetValue("x_fechaingr"));
			$this->fechaingr->CurrentValue = ew_UnFormatDateTime($this->fechaingr->CurrentValue, 7);
		}
		$this->fechaingr->setOldValue($objForm->GetValue("o_fechaingr"));
		if (!$this->fechaentrega->FldIsDetailKey) {
			$this->fechaentrega->setFormValue($objForm->GetValue("x_fechaentrega"));
			$this->fechaentrega->CurrentValue = ew_UnFormatDateTime($this->fechaentrega->CurrentValue, 7);
		}
		$this->fechaentrega->setOldValue($objForm->GetValue("o_fechaentrega"));
		if (!$this->tcomprel->FldIsDetailKey) {
			$this->tcomprel->setFormValue($objForm->GetValue("x_tcomprel"));
		}
		$this->tcomprel->setOldValue($objForm->GetValue("o_tcomprel"));
		if (!$this->serierel->FldIsDetailKey) {
			$this->serierel->setFormValue($objForm->GetValue("x_serierel"));
		}
		$this->serierel->setOldValue($objForm->GetValue("o_serierel"));
		if (!$this->ncomprel->FldIsDetailKey) {
			$this->ncomprel->setFormValue($objForm->GetValue("x_ncomprel"));
		}
		$this->ncomprel->setOldValue($objForm->GetValue("o_ncomprel"));
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
		$this->estado->setOldValue($objForm->GetValue("o_estado"));
		if (!$this->moneda->FldIsDetailKey) {
			$this->moneda->setFormValue($objForm->GetValue("x_moneda"));
		}
		$this->moneda->setOldValue($objForm->GetValue("o_moneda"));
		if (!$this->fechahora->FldIsDetailKey) {
			$this->fechahora->setFormValue($objForm->GetValue("x_fechahora"));
			$this->fechahora->CurrentValue = ew_UnFormatDateTime($this->fechahora->CurrentValue, 7);
		}
		$this->fechahora->setOldValue($objForm->GetValue("o_fechahora"));
		if (!$this->usuario->FldIsDetailKey) {
			$this->usuario->setFormValue($objForm->GetValue("x_usuario"));
		}
		$this->usuario->setOldValue($objForm->GetValue("o_usuario"));
		if (!$this->tcompsal->FldIsDetailKey) {
			$this->tcompsal->setFormValue($objForm->GetValue("x_tcompsal"));
		}
		$this->tcompsal->setOldValue($objForm->GetValue("o_tcompsal"));
		if (!$this->seriesal->FldIsDetailKey) {
			$this->seriesal->setFormValue($objForm->GetValue("x_seriesal"));
		}
		$this->seriesal->setOldValue($objForm->GetValue("o_seriesal"));
		if (!$this->ncompsal->FldIsDetailKey) {
			$this->ncompsal->setFormValue($objForm->GetValue("x_ncompsal"));
		}
		$this->ncompsal->setOldValue($objForm->GetValue("o_ncompsal"));
		if (!$this->codrem->FldIsDetailKey) {
			$this->codrem->setFormValue($objForm->GetValue("x_codrem"));
		}
		$this->codrem->setOldValue($objForm->GetValue("o_codrem"));
		if (!$this->cotiz->FldIsDetailKey) {
			$this->cotiz->setFormValue($objForm->GetValue("x_cotiz"));
		}
		$this->cotiz->setOldValue($objForm->GetValue("o_cotiz"));
		if (!$this->usurel->FldIsDetailKey) {
			$this->usurel->setFormValue($objForm->GetValue("x_usurel"));
		}
		$this->usurel->setOldValue($objForm->GetValue("o_usurel"));
		if (!$this->fecharel->FldIsDetailKey) {
			$this->fecharel->setFormValue($objForm->GetValue("x_fecharel"));
			$this->fecharel->CurrentValue = ew_UnFormatDateTime($this->fecharel->CurrentValue, 7);
		}
		$this->fecharel->setOldValue($objForm->GetValue("o_fecharel"));
		if (!$this->ususal->FldIsDetailKey) {
			$this->ususal->setFormValue($objForm->GetValue("x_ususal"));
		}
		$this->ususal->setOldValue($objForm->GetValue("o_ususal"));
		if (!$this->fechasal->FldIsDetailKey) {
			$this->fechasal->setFormValue($objForm->GetValue("x_fechasal"));
			$this->fechasal->CurrentValue = ew_UnFormatDateTime($this->fechasal->CurrentValue, 7);
		}
		$this->fechasal->setOldValue($objForm->GetValue("o_fechasal"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->codnum->CurrentValue = $this->codnum->FormValue;
		$this->tcomp->CurrentValue = $this->tcomp->FormValue;
		$this->serie->CurrentValue = $this->serie->FormValue;
		$this->ncomp->CurrentValue = $this->ncomp->FormValue;
		$this->codban->CurrentValue = $this->codban->FormValue;
		$this->codsuc->CurrentValue = $this->codsuc->FormValue;
		$this->codcta->CurrentValue = $this->codcta->FormValue;
		$this->tipcta->CurrentValue = $this->tipcta->FormValue;
		$this->codchq->CurrentValue = $this->codchq->FormValue;
		$this->codpais->CurrentValue = $this->codpais->FormValue;
		$this->importe->CurrentValue = $this->importe->FormValue;
		$this->fechaemis->CurrentValue = $this->fechaemis->FormValue;
		$this->fechaemis->CurrentValue = ew_UnFormatDateTime($this->fechaemis->CurrentValue, 7);
		$this->fechapago->CurrentValue = $this->fechapago->FormValue;
		$this->fechapago->CurrentValue = ew_UnFormatDateTime($this->fechapago->CurrentValue, 7);
		$this->entrego->CurrentValue = $this->entrego->FormValue;
		$this->recibio->CurrentValue = $this->recibio->FormValue;
		$this->fechaingr->CurrentValue = $this->fechaingr->FormValue;
		$this->fechaingr->CurrentValue = ew_UnFormatDateTime($this->fechaingr->CurrentValue, 7);
		$this->fechaentrega->CurrentValue = $this->fechaentrega->FormValue;
		$this->fechaentrega->CurrentValue = ew_UnFormatDateTime($this->fechaentrega->CurrentValue, 7);
		$this->tcomprel->CurrentValue = $this->tcomprel->FormValue;
		$this->serierel->CurrentValue = $this->serierel->FormValue;
		$this->ncomprel->CurrentValue = $this->ncomprel->FormValue;
		$this->estado->CurrentValue = $this->estado->FormValue;
		$this->moneda->CurrentValue = $this->moneda->FormValue;
		$this->fechahora->CurrentValue = $this->fechahora->FormValue;
		$this->fechahora->CurrentValue = ew_UnFormatDateTime($this->fechahora->CurrentValue, 7);
		$this->usuario->CurrentValue = $this->usuario->FormValue;
		$this->tcompsal->CurrentValue = $this->tcompsal->FormValue;
		$this->seriesal->CurrentValue = $this->seriesal->FormValue;
		$this->ncompsal->CurrentValue = $this->ncompsal->FormValue;
		$this->codrem->CurrentValue = $this->codrem->FormValue;
		$this->cotiz->CurrentValue = $this->cotiz->FormValue;
		$this->usurel->CurrentValue = $this->usurel->FormValue;
		$this->fecharel->CurrentValue = $this->fecharel->FormValue;
		$this->fecharel->CurrentValue = ew_UnFormatDateTime($this->fecharel->CurrentValue, 7);
		$this->ususal->CurrentValue = $this->ususal->FormValue;
		$this->fechasal->CurrentValue = $this->fechasal->FormValue;
		$this->fechasal->CurrentValue = ew_UnFormatDateTime($this->fechasal->CurrentValue, 7);
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
		$conn->raiseErrorFn = '';

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
		$this->codban->setDbValue($rs->fields('codban'));
		$this->codsuc->setDbValue($rs->fields('codsuc'));
		$this->codcta->setDbValue($rs->fields('codcta'));
		$this->tipcta->setDbValue($rs->fields('tipcta'));
		$this->codchq->setDbValue($rs->fields('codchq'));
		$this->codpais->setDbValue($rs->fields('codpais'));
		$this->importe->setDbValue($rs->fields('importe'));
		$this->fechaemis->setDbValue($rs->fields('fechaemis'));
		$this->fechapago->setDbValue($rs->fields('fechapago'));
		$this->entrego->setDbValue($rs->fields('entrego'));
		$this->recibio->setDbValue($rs->fields('recibio'));
		$this->fechaingr->setDbValue($rs->fields('fechaingr'));
		$this->fechaentrega->setDbValue($rs->fields('fechaentrega'));
		$this->tcomprel->setDbValue($rs->fields('tcomprel'));
		$this->serierel->setDbValue($rs->fields('serierel'));
		$this->ncomprel->setDbValue($rs->fields('ncomprel'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->moneda->setDbValue($rs->fields('moneda'));
		$this->fechahora->setDbValue($rs->fields('fechahora'));
		$this->usuario->setDbValue($rs->fields('usuario'));
		$this->tcompsal->setDbValue($rs->fields('tcompsal'));
		$this->seriesal->setDbValue($rs->fields('seriesal'));
		$this->ncompsal->setDbValue($rs->fields('ncompsal'));
		$this->codrem->setDbValue($rs->fields('codrem'));
		$this->cotiz->setDbValue($rs->fields('cotiz'));
		$this->usurel->setDbValue($rs->fields('usurel'));
		$this->fecharel->setDbValue($rs->fields('fecharel'));
		$this->ususal->setDbValue($rs->fields('ususal'));
		$this->fechasal->setDbValue($rs->fields('fechasal'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codnum->DbValue = $row['codnum'];
		$this->tcomp->DbValue = $row['tcomp'];
		$this->serie->DbValue = $row['serie'];
		$this->ncomp->DbValue = $row['ncomp'];
		$this->codban->DbValue = $row['codban'];
		$this->codsuc->DbValue = $row['codsuc'];
		$this->codcta->DbValue = $row['codcta'];
		$this->tipcta->DbValue = $row['tipcta'];
		$this->codchq->DbValue = $row['codchq'];
		$this->codpais->DbValue = $row['codpais'];
		$this->importe->DbValue = $row['importe'];
		$this->fechaemis->DbValue = $row['fechaemis'];
		$this->fechapago->DbValue = $row['fechapago'];
		$this->entrego->DbValue = $row['entrego'];
		$this->recibio->DbValue = $row['recibio'];
		$this->fechaingr->DbValue = $row['fechaingr'];
		$this->fechaentrega->DbValue = $row['fechaentrega'];
		$this->tcomprel->DbValue = $row['tcomprel'];
		$this->serierel->DbValue = $row['serierel'];
		$this->ncomprel->DbValue = $row['ncomprel'];
		$this->estado->DbValue = $row['estado'];
		$this->moneda->DbValue = $row['moneda'];
		$this->fechahora->DbValue = $row['fechahora'];
		$this->usuario->DbValue = $row['usuario'];
		$this->tcompsal->DbValue = $row['tcompsal'];
		$this->seriesal->DbValue = $row['seriesal'];
		$this->ncompsal->DbValue = $row['ncompsal'];
		$this->codrem->DbValue = $row['codrem'];
		$this->cotiz->DbValue = $row['cotiz'];
		$this->usurel->DbValue = $row['usurel'];
		$this->fecharel->DbValue = $row['fecharel'];
		$this->ususal->DbValue = $row['ususal'];
		$this->fechasal->DbValue = $row['fechasal'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		$arKeys[] = $this->RowOldKey;
		$cnt = count($arKeys);
		if ($cnt >= 1) {
			if (strval($arKeys[0]) <> "")
				$this->codnum->CurrentValue = strval($arKeys[0]); // codnum
			else
				$bValidKey = FALSE;
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

		if ($this->importe->FormValue == $this->importe->CurrentValue && is_numeric(ew_StrToFloat($this->importe->CurrentValue)))
			$this->importe->CurrentValue = ew_StrToFloat($this->importe->CurrentValue);

		// Convert decimal values if posted back
		if ($this->cotiz->FormValue == $this->cotiz->CurrentValue && is_numeric(ew_StrToFloat($this->cotiz->CurrentValue)))
			$this->cotiz->CurrentValue = ew_StrToFloat($this->cotiz->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// codnum
		// tcomp
		// serie
		// ncomp
		// codban
		// codsuc
		// codcta
		// tipcta
		// codchq
		// codpais
		// importe
		// fechaemis
		// fechapago
		// entrego
		// recibio
		// fechaingr
		// fechaentrega
		// tcomprel
		// serierel
		// ncomprel
		// estado
		// moneda
		// fechahora
		// usuario
		// tcompsal
		// seriesal
		// ncompsal
		// codrem
		// cotiz
		// usurel
		// fecharel
		// ususal
		// fechasal

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// codnum
			$this->codnum->ViewValue = $this->codnum->CurrentValue;
			$this->codnum->ViewCustomAttributes = "";

			// tcomp
			if (strval($this->tcomp->CurrentValue) <> "") {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->tcomp->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipcomp`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->tcomp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `descripcion` ASC";
				$rswrk = $conn-> executeUpdate($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->tcomp->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->tcomp->ViewValue = $this->tcomp->CurrentValue;
				}
			} else {
				$this->tcomp->ViewValue = NULL;
			}
			$this->tcomp->ViewCustomAttributes = "";

			// serie
			if (strval($this->serie->CurrentValue) <> "") {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->serie->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `series`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->serie, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `descripcion` ASC";
				$rswrk = $conn-> executeUpdate($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->serie->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->serie->ViewValue = $this->serie->CurrentValue;
				}
			} else {
				$this->serie->ViewValue = NULL;
			}
			$this->serie->ViewCustomAttributes = "";

			// ncomp
			$this->ncomp->ViewValue = $this->ncomp->CurrentValue;
			$this->ncomp->ViewCustomAttributes = "";

			// codban
			if (strval($this->codban->CurrentValue) <> "") {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->codban->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codnum`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bancos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codban, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
				$rswrk = $conn-> executeUpdate($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->codban->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->codban->ViewValue = $this->codban->CurrentValue;
				}
			} else {
				$this->codban->ViewValue = NULL;
			}
			$this->codban->ViewCustomAttributes = "";

			// codsuc
			if (strval($this->codsuc->CurrentValue) <> "") {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->codsuc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codnum`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sucbancos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codsuc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
				$rswrk = $conn-> executeUpdate($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->codsuc->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->codsuc->ViewValue = $this->codsuc->CurrentValue;
				}
			} else {
				$this->codsuc->ViewValue = NULL;
			}
			$this->codsuc->ViewCustomAttributes = "";

			// codcta
			$this->codcta->ViewValue = $this->codcta->CurrentValue;
			$this->codcta->ViewCustomAttributes = "";

			// tipcta
			$this->tipcta->ViewValue = $this->tipcta->CurrentValue;
			$this->tipcta->ViewCustomAttributes = "";

			// codchq
			$this->codchq->ViewValue = $this->codchq->CurrentValue;
			$this->codchq->ViewCustomAttributes = "";

			// codpais
			if (strval($this->codpais->CurrentValue) <> "") {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->codpais->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paises`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codpais, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `descripcion` DESC";
				$rswrk = $conn-> executeUpdate($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->codpais->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->codpais->ViewValue = $this->codpais->CurrentValue;
				}
			} else {
				$this->codpais->ViewValue = NULL;
			}
			$this->codpais->ViewCustomAttributes = "";

			// importe
			$this->importe->ViewValue = $this->importe->CurrentValue;
			$this->importe->ViewCustomAttributes = "";

			// fechaemis
			$this->fechaemis->ViewValue = $this->fechaemis->CurrentValue;
			$this->fechaemis->ViewValue = ew_FormatDateTime($this->fechaemis->ViewValue, 7);
			$this->fechaemis->ViewCustomAttributes = "";

			// fechapago
			$this->fechapago->ViewValue = $this->fechapago->CurrentValue;
			$this->fechapago->ViewValue = ew_FormatDateTime($this->fechapago->ViewValue, 7);
			$this->fechapago->ViewCustomAttributes = "";

			// entrego
			$this->entrego->ViewValue = $this->entrego->CurrentValue;
			$this->entrego->ViewCustomAttributes = "";

			// recibio
			$this->recibio->ViewValue = $this->recibio->CurrentValue;
			$this->recibio->ViewCustomAttributes = "";

			// fechaingr
			$this->fechaingr->ViewValue = $this->fechaingr->CurrentValue;
			$this->fechaingr->ViewValue = ew_FormatDateTime($this->fechaingr->ViewValue, 7);
			$this->fechaingr->ViewCustomAttributes = "";

			// fechaentrega
			$this->fechaentrega->ViewValue = $this->fechaentrega->CurrentValue;
			$this->fechaentrega->ViewValue = ew_FormatDateTime($this->fechaentrega->ViewValue, 7);
			$this->fechaentrega->ViewCustomAttributes = "";

			// tcomprel
			$this->tcomprel->ViewValue = $this->tcomprel->CurrentValue;
			$this->tcomprel->ViewCustomAttributes = "";

			// serierel
			$this->serierel->ViewValue = $this->serierel->CurrentValue;
			$this->serierel->ViewCustomAttributes = "";

			// ncomprel
			$this->ncomprel->ViewValue = $this->ncomprel->CurrentValue;
			$this->ncomprel->ViewCustomAttributes = "";

			// estado
			if (strval($this->estado->CurrentValue) <> "") {
				switch ($this->estado->CurrentValue) {
					case $this->estado->FldTagValue(1):
						$this->estado->ViewValue = $this->estado->FldTagCaption(1) <> "" ? $this->estado->FldTagCaption(1) : $this->estado->CurrentValue;
						break;
					case $this->estado->FldTagValue(2):
						$this->estado->ViewValue = $this->estado->FldTagCaption(2) <> "" ? $this->estado->FldTagCaption(2) : $this->estado->CurrentValue;
						break;
					default:
						$this->estado->ViewValue = $this->estado->CurrentValue;
				}
			} else {
				$this->estado->ViewValue = NULL;
			}
			$this->estado->ViewCustomAttributes = "";

			// moneda
			$this->moneda->ViewValue = $this->moneda->CurrentValue;
			$this->moneda->ViewCustomAttributes = "";

			// fechahora
			$this->fechahora->ViewValue = $this->fechahora->CurrentValue;
			$this->fechahora->ViewValue = ew_FormatDateTime($this->fechahora->ViewValue, 7);
			$this->fechahora->ViewCustomAttributes = "";

			// usuario
			$this->usuario->ViewValue = $this->usuario->CurrentValue;
			$this->usuario->ViewCustomAttributes = "";

			// tcompsal
			$this->tcompsal->ViewValue = $this->tcompsal->CurrentValue;
			$this->tcompsal->ViewCustomAttributes = "";

			// seriesal
			$this->seriesal->ViewValue = $this->seriesal->CurrentValue;
			$this->seriesal->ViewCustomAttributes = "";

			// ncompsal
			$this->ncompsal->ViewValue = $this->ncompsal->CurrentValue;
			$this->ncompsal->ViewCustomAttributes = "";

			// codrem
			if (strval($this->codrem->CurrentValue) <> "") {
				$sFilterWrk = "`ncomp`" . ew_SearchString("=", $this->codrem->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `ncomp`, `ncomp` AS `DispFld`, `direccion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `remates`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codrem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `ncomp` DESC";
				$rswrk = $conn-> executeUpdate($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->codrem->ViewValue = $rswrk->fields('DispFld');
					$this->codrem->ViewValue .= ew_ValueSeparator(1,$this->codrem) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->codrem->ViewValue = $this->codrem->CurrentValue;
				}
			} else {
				$this->codrem->ViewValue = NULL;
			}
			$this->codrem->ViewCustomAttributes = "";

			// cotiz
			$this->cotiz->ViewValue = $this->cotiz->CurrentValue;
			$this->cotiz->ViewCustomAttributes = "";

			// usurel
			$this->usurel->ViewValue = $this->usurel->CurrentValue;
			$this->usurel->ViewCustomAttributes = "";

			// fecharel
			$this->fecharel->ViewValue = $this->fecharel->CurrentValue;
			$this->fecharel->ViewValue = ew_FormatDateTime($this->fecharel->ViewValue, 7);
			$this->fecharel->ViewCustomAttributes = "";

			// ususal
			$this->ususal->ViewValue = $this->ususal->CurrentValue;
			$this->ususal->ViewCustomAttributes = "";

			// fechasal
			$this->fechasal->ViewValue = $this->fechasal->CurrentValue;
			$this->fechasal->ViewValue = ew_FormatDateTime($this->fechasal->ViewValue, 7);
			$this->fechasal->ViewCustomAttributes = "";

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

			// codban
			$this->codban->LinkCustomAttributes = "";
			$this->codban->HrefValue = "";
			$this->codban->TooltipValue = "";

			// codsuc
			$this->codsuc->LinkCustomAttributes = "";
			$this->codsuc->HrefValue = "";
			$this->codsuc->TooltipValue = "";

			// codcta
			$this->codcta->LinkCustomAttributes = "";
			$this->codcta->HrefValue = "";
			$this->codcta->TooltipValue = "";

			// tipcta
			$this->tipcta->LinkCustomAttributes = "";
			$this->tipcta->HrefValue = "";
			$this->tipcta->TooltipValue = "";

			// codchq
			$this->codchq->LinkCustomAttributes = "";
			$this->codchq->HrefValue = "";
			$this->codchq->TooltipValue = "";

			// codpais
			$this->codpais->LinkCustomAttributes = "";
			$this->codpais->HrefValue = "";
			$this->codpais->TooltipValue = "";

			// importe
			$this->importe->LinkCustomAttributes = "";
			$this->importe->HrefValue = "";
			$this->importe->TooltipValue = "";

			// fechaemis
			$this->fechaemis->LinkCustomAttributes = "";
			$this->fechaemis->HrefValue = "";
			$this->fechaemis->TooltipValue = "";

			// fechapago
			$this->fechapago->LinkCustomAttributes = "";
			$this->fechapago->HrefValue = "";
			$this->fechapago->TooltipValue = "";

			// entrego
			$this->entrego->LinkCustomAttributes = "";
			$this->entrego->HrefValue = "";
			$this->entrego->TooltipValue = "";

			// recibio
			$this->recibio->LinkCustomAttributes = "";
			$this->recibio->HrefValue = "";
			$this->recibio->TooltipValue = "";

			// fechaingr
			$this->fechaingr->LinkCustomAttributes = "";
			$this->fechaingr->HrefValue = "";
			$this->fechaingr->TooltipValue = "";

			// fechaentrega
			$this->fechaentrega->LinkCustomAttributes = "";
			$this->fechaentrega->HrefValue = "";
			$this->fechaentrega->TooltipValue = "";

			// tcomprel
			$this->tcomprel->LinkCustomAttributes = "";
			$this->tcomprel->HrefValue = "";
			$this->tcomprel->TooltipValue = "";

			// serierel
			$this->serierel->LinkCustomAttributes = "";
			$this->serierel->HrefValue = "";
			$this->serierel->TooltipValue = "";

			// ncomprel
			$this->ncomprel->LinkCustomAttributes = "";
			$this->ncomprel->HrefValue = "";
			$this->ncomprel->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";

			// moneda
			$this->moneda->LinkCustomAttributes = "";
			$this->moneda->HrefValue = "";
			$this->moneda->TooltipValue = "";

			// fechahora
			$this->fechahora->LinkCustomAttributes = "";
			$this->fechahora->HrefValue = "";
			$this->fechahora->TooltipValue = "";

			// usuario
			$this->usuario->LinkCustomAttributes = "";
			$this->usuario->HrefValue = "";
			$this->usuario->TooltipValue = "";

			// tcompsal
			$this->tcompsal->LinkCustomAttributes = "";
			$this->tcompsal->HrefValue = "";
			$this->tcompsal->TooltipValue = "";

			// seriesal
			$this->seriesal->LinkCustomAttributes = "";
			$this->seriesal->HrefValue = "";
			$this->seriesal->TooltipValue = "";

			// ncompsal
			$this->ncompsal->LinkCustomAttributes = "";
			$this->ncompsal->HrefValue = "";
			$this->ncompsal->TooltipValue = "";

			// codrem
			$this->codrem->LinkCustomAttributes = "";
			$this->codrem->HrefValue = "";
			$this->codrem->TooltipValue = "";

			// cotiz
			$this->cotiz->LinkCustomAttributes = "";
			$this->cotiz->HrefValue = "";
			$this->cotiz->TooltipValue = "";

			// usurel
			$this->usurel->LinkCustomAttributes = "";
			$this->usurel->HrefValue = "";
			$this->usurel->TooltipValue = "";

			// fecharel
			$this->fecharel->LinkCustomAttributes = "";
			$this->fecharel->HrefValue = "";
			$this->fecharel->TooltipValue = "";

			// ususal
			$this->ususal->LinkCustomAttributes = "";
			$this->ususal->HrefValue = "";
			$this->ususal->TooltipValue = "";

			// fechasal
			$this->fechasal->LinkCustomAttributes = "";
			$this->fechasal->HrefValue = "";
			$this->fechasal->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// codnum
			// tcomp

			$this->tcomp->EditAttrs["class"] = "form-control";
			$this->tcomp->EditCustomAttributes = "";
			if (trim(strval($this->tcomp->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->tcomp->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipcomp`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->tcomp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `descripcion` ASC";
			$rswrk = $conn-> executeUpdate($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->tcomp->EditValue = $arwrk;

			// serie
			$this->serie->EditAttrs["class"] = "form-control";
			$this->serie->EditCustomAttributes = "";
			if (trim(strval($this->serie->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->serie->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `series`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->serie, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `descripcion` ASC";
			$rswrk = $conn-> executeUpdate($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->serie->EditValue = $arwrk;

			// ncomp
			$this->ncomp->EditAttrs["class"] = "form-control";
			$this->ncomp->EditCustomAttributes = "";
			$this->ncomp->EditValue = ew_HtmlEncode($this->ncomp->CurrentValue);
			$this->ncomp->PlaceHolder = ew_RemoveHtml($this->ncomp->FldCaption());

			// codban
			$this->codban->EditAttrs["class"] = "form-control";
			$this->codban->EditCustomAttributes = "";
			if (trim(strval($this->codban->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->codban->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codnum`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `bancos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codban, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
			$rswrk = $conn-> executeUpdate($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->codban->EditValue = $arwrk;

			// codsuc
			$this->codsuc->EditAttrs["class"] = "form-control";
			$this->codsuc->EditCustomAttributes = "";
			if (trim(strval($this->codsuc->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->codsuc->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codnum`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `codbanco` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sucbancos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codsuc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
			$rswrk = $conn-> executeUpdate($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->codsuc->EditValue = $arwrk;

			// codcta
			$this->codcta->EditAttrs["class"] = "form-control";
			$this->codcta->EditCustomAttributes = "";
			$this->codcta->EditValue = ew_HtmlEncode($this->codcta->CurrentValue);
			$this->codcta->PlaceHolder = ew_RemoveHtml($this->codcta->FldCaption());

			// tipcta
			$this->tipcta->EditAttrs["class"] = "form-control";
			$this->tipcta->EditCustomAttributes = "";
			$this->tipcta->EditValue = ew_HtmlEncode($this->tipcta->CurrentValue);
			$this->tipcta->PlaceHolder = ew_RemoveHtml($this->tipcta->FldCaption());

			// codchq
			$this->codchq->EditAttrs["class"] = "form-control";
			$this->codchq->EditCustomAttributes = "";
			$this->codchq->EditValue = ew_HtmlEncode($this->codchq->CurrentValue);
			$this->codchq->PlaceHolder = ew_RemoveHtml($this->codchq->FldCaption());

			// codpais
			$this->codpais->EditAttrs["class"] = "form-control";
			$this->codpais->EditCustomAttributes = "";
			if (trim(strval($this->codpais->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->codpais->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `paises`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codpais, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `descripcion` DESC";
			$rswrk = $conn-> executeUpdate($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->codpais->EditValue = $arwrk;

			// importe
			$this->importe->EditAttrs["class"] = "form-control";
			$this->importe->EditCustomAttributes = "";
			$this->importe->EditValue = ew_HtmlEncode($this->importe->CurrentValue);
			$this->importe->PlaceHolder = ew_RemoveHtml($this->importe->FldCaption());
			if (strval($this->importe->EditValue) <> "" && is_numeric($this->importe->EditValue)) {
			$this->importe->EditValue = ew_FormatNumber($this->importe->EditValue, -2, -1, -2, 0);
			$this->importe->OldValue = $this->importe->EditValue;
			}

			// fechaemis
			$this->fechaemis->EditAttrs["class"] = "form-control";
			$this->fechaemis->EditCustomAttributes = "";
			$this->fechaemis->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechaemis->CurrentValue, 7));
			$this->fechaemis->PlaceHolder = ew_RemoveHtml($this->fechaemis->FldCaption());

			// fechapago
			$this->fechapago->EditAttrs["class"] = "form-control";
			$this->fechapago->EditCustomAttributes = "";
			$this->fechapago->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechapago->CurrentValue, 7));
			$this->fechapago->PlaceHolder = ew_RemoveHtml($this->fechapago->FldCaption());

			// entrego
			$this->entrego->EditAttrs["class"] = "form-control";
			$this->entrego->EditCustomAttributes = "";
			$this->entrego->EditValue = ew_HtmlEncode($this->entrego->CurrentValue);
			$this->entrego->PlaceHolder = ew_RemoveHtml($this->entrego->FldCaption());

			// recibio
			$this->recibio->EditAttrs["class"] = "form-control";
			$this->recibio->EditCustomAttributes = "";
			$this->recibio->EditValue = ew_HtmlEncode($this->recibio->CurrentValue);
			$this->recibio->PlaceHolder = ew_RemoveHtml($this->recibio->FldCaption());

			// fechaingr
			$this->fechaingr->EditAttrs["class"] = "form-control";
			$this->fechaingr->EditCustomAttributes = "";
			$this->fechaingr->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechaingr->CurrentValue, 7));
			$this->fechaingr->PlaceHolder = ew_RemoveHtml($this->fechaingr->FldCaption());

			// fechaentrega
			$this->fechaentrega->EditAttrs["class"] = "form-control";
			$this->fechaentrega->EditCustomAttributes = "";
			$this->fechaentrega->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechaentrega->CurrentValue, 7));
			$this->fechaentrega->PlaceHolder = ew_RemoveHtml($this->fechaentrega->FldCaption());

			// tcomprel
			$this->tcomprel->EditAttrs["class"] = "form-control";
			$this->tcomprel->EditCustomAttributes = "";
			if ($this->tcomprel->getSessionValue() <> "") {
				$this->tcomprel->CurrentValue = $this->tcomprel->getSessionValue();
				$this->tcomprel->OldValue = $this->tcomprel->CurrentValue;
			$this->tcomprel->ViewValue = $this->tcomprel->CurrentValue;
			$this->tcomprel->ViewCustomAttributes = "";
			} else {
			$this->tcomprel->EditValue = ew_HtmlEncode($this->tcomprel->CurrentValue);
			$this->tcomprel->PlaceHolder = ew_RemoveHtml($this->tcomprel->FldCaption());
			}

			// serierel
			$this->serierel->EditAttrs["class"] = "form-control";
			$this->serierel->EditCustomAttributes = "";
			if ($this->serierel->getSessionValue() <> "") {
				$this->serierel->CurrentValue = $this->serierel->getSessionValue();
				$this->serierel->OldValue = $this->serierel->CurrentValue;
			$this->serierel->ViewValue = $this->serierel->CurrentValue;
			$this->serierel->ViewCustomAttributes = "";
			} else {
			$this->serierel->EditValue = ew_HtmlEncode($this->serierel->CurrentValue);
			$this->serierel->PlaceHolder = ew_RemoveHtml($this->serierel->FldCaption());
			}

			// ncomprel
			$this->ncomprel->EditAttrs["class"] = "form-control";
			$this->ncomprel->EditCustomAttributes = "";
			if ($this->ncomprel->getSessionValue() <> "") {
				$this->ncomprel->CurrentValue = $this->ncomprel->getSessionValue();
				$this->ncomprel->OldValue = $this->ncomprel->CurrentValue;
			$this->ncomprel->ViewValue = $this->ncomprel->CurrentValue;
			$this->ncomprel->ViewCustomAttributes = "";
			} else {
			$this->ncomprel->EditValue = ew_HtmlEncode($this->ncomprel->CurrentValue);
			$this->ncomprel->PlaceHolder = ew_RemoveHtml($this->ncomprel->FldCaption());
			}

			// estado
			$this->estado->EditAttrs["class"] = "form-control";
			$this->estado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->estado->FldTagValue(1), $this->estado->FldTagCaption(1) <> "" ? $this->estado->FldTagCaption(1) : $this->estado->FldTagValue(1));
			$arwrk[] = array($this->estado->FldTagValue(2), $this->estado->FldTagCaption(2) <> "" ? $this->estado->FldTagCaption(2) : $this->estado->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->estado->EditValue = $arwrk;

			// moneda
			$this->moneda->EditAttrs["class"] = "form-control";
			$this->moneda->EditCustomAttributes = "";
			$this->moneda->EditValue = ew_HtmlEncode($this->moneda->CurrentValue);
			$this->moneda->PlaceHolder = ew_RemoveHtml($this->moneda->FldCaption());

			// fechahora
			$this->fechahora->EditAttrs["class"] = "form-control";
			$this->fechahora->EditCustomAttributes = "";
			$this->fechahora->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechahora->CurrentValue, 7));
			$this->fechahora->PlaceHolder = ew_RemoveHtml($this->fechahora->FldCaption());

			// usuario
			$this->usuario->EditAttrs["class"] = "form-control";
			$this->usuario->EditCustomAttributes = "";
			$this->usuario->EditValue = ew_HtmlEncode($this->usuario->CurrentValue);
			$this->usuario->PlaceHolder = ew_RemoveHtml($this->usuario->FldCaption());

			// tcompsal
			$this->tcompsal->EditAttrs["class"] = "form-control";
			$this->tcompsal->EditCustomAttributes = "";
			$this->tcompsal->EditValue = ew_HtmlEncode($this->tcompsal->CurrentValue);
			$this->tcompsal->PlaceHolder = ew_RemoveHtml($this->tcompsal->FldCaption());

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

			// codrem
			$this->codrem->EditAttrs["class"] = "form-control";
			$this->codrem->EditCustomAttributes = "";
			if (trim(strval($this->codrem->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ncomp`" . ew_SearchString("=", $this->codrem->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `ncomp`, `ncomp` AS `DispFld`, `direccion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `remates`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codrem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `ncomp` DESC";
			$rswrk = $conn-> executeUpdate($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->codrem->EditValue = $arwrk;

			// cotiz
			$this->cotiz->EditAttrs["class"] = "form-control";
			$this->cotiz->EditCustomAttributes = "";
			$this->cotiz->EditValue = ew_HtmlEncode($this->cotiz->CurrentValue);
			$this->cotiz->PlaceHolder = ew_RemoveHtml($this->cotiz->FldCaption());
			if (strval($this->cotiz->EditValue) <> "" && is_numeric($this->cotiz->EditValue)) {
			$this->cotiz->EditValue = ew_FormatNumber($this->cotiz->EditValue, -2, -1, -2, 0);
			$this->cotiz->OldValue = $this->cotiz->EditValue;
			}

			// usurel
			$this->usurel->EditAttrs["class"] = "form-control";
			$this->usurel->EditCustomAttributes = "";
			$this->usurel->EditValue = ew_HtmlEncode($this->usurel->CurrentValue);
			$this->usurel->PlaceHolder = ew_RemoveHtml($this->usurel->FldCaption());

			// fecharel
			$this->fecharel->EditAttrs["class"] = "form-control";
			$this->fecharel->EditCustomAttributes = "";
			$this->fecharel->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecharel->CurrentValue, 7));
			$this->fecharel->PlaceHolder = ew_RemoveHtml($this->fecharel->FldCaption());

			// ususal
			$this->ususal->EditAttrs["class"] = "form-control";
			$this->ususal->EditCustomAttributes = "";
			$this->ususal->EditValue = ew_HtmlEncode($this->ususal->CurrentValue);
			$this->ususal->PlaceHolder = ew_RemoveHtml($this->ususal->FldCaption());

			// fechasal
			$this->fechasal->EditAttrs["class"] = "form-control";
			$this->fechasal->EditCustomAttributes = "";
			$this->fechasal->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechasal->CurrentValue, 7));
			$this->fechasal->PlaceHolder = ew_RemoveHtml($this->fechasal->FldCaption());

			// Edit refer script
			// codnum

			$this->codnum->HrefValue = "";

			// tcomp
			$this->tcomp->HrefValue = "";

			// serie
			$this->serie->HrefValue = "";

			// ncomp
			$this->ncomp->HrefValue = "";

			// codban
			$this->codban->HrefValue = "";

			// codsuc
			$this->codsuc->HrefValue = "";

			// codcta
			$this->codcta->HrefValue = "";

			// tipcta
			$this->tipcta->HrefValue = "";

			// codchq
			$this->codchq->HrefValue = "";

			// codpais
			$this->codpais->HrefValue = "";

			// importe
			$this->importe->HrefValue = "";

			// fechaemis
			$this->fechaemis->HrefValue = "";

			// fechapago
			$this->fechapago->HrefValue = "";

			// entrego
			$this->entrego->HrefValue = "";

			// recibio
			$this->recibio->HrefValue = "";

			// fechaingr
			$this->fechaingr->HrefValue = "";

			// fechaentrega
			$this->fechaentrega->HrefValue = "";

			// tcomprel
			$this->tcomprel->HrefValue = "";

			// serierel
			$this->serierel->HrefValue = "";

			// ncomprel
			$this->ncomprel->HrefValue = "";

			// estado
			$this->estado->HrefValue = "";

			// moneda
			$this->moneda->HrefValue = "";

			// fechahora
			$this->fechahora->HrefValue = "";

			// usuario
			$this->usuario->HrefValue = "";

			// tcompsal
			$this->tcompsal->HrefValue = "";

			// seriesal
			$this->seriesal->HrefValue = "";

			// ncompsal
			$this->ncompsal->HrefValue = "";

			// codrem
			$this->codrem->HrefValue = "";

			// cotiz
			$this->cotiz->HrefValue = "";

			// usurel
			$this->usurel->HrefValue = "";

			// fecharel
			$this->fecharel->HrefValue = "";

			// ususal
			$this->ususal->HrefValue = "";

			// fechasal
			$this->fechasal->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// codnum
			$this->codnum->EditAttrs["class"] = "form-control";
			$this->codnum->EditCustomAttributes = "";
			$this->codnum->EditValue = $this->codnum->CurrentValue;
			$this->codnum->ViewCustomAttributes = "";

			// tcomp
			$this->tcomp->EditAttrs["class"] = "form-control";
			$this->tcomp->EditCustomAttributes = "";
			if (trim(strval($this->tcomp->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->tcomp->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipcomp`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->tcomp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `descripcion` ASC";
			$rswrk = $conn-> executeUpdate($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->tcomp->EditValue = $arwrk;

			// serie
			$this->serie->EditAttrs["class"] = "form-control";
			$this->serie->EditCustomAttributes = "";
			if (trim(strval($this->serie->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->serie->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `series`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->serie, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `descripcion` ASC";
			$rswrk = $conn-> executeUpdate($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->serie->EditValue = $arwrk;

			// ncomp
			$this->ncomp->EditAttrs["class"] = "form-control";
			$this->ncomp->EditCustomAttributes = "";
			$this->ncomp->EditValue = ew_HtmlEncode($this->ncomp->CurrentValue);
			$this->ncomp->PlaceHolder = ew_RemoveHtml($this->ncomp->FldCaption());

			// codban
			$this->codban->EditAttrs["class"] = "form-control";
			$this->codban->EditCustomAttributes = "";
			if (trim(strval($this->codban->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->codban->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codnum`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `bancos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codban, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
			$rswrk = $conn-> executeUpdate($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->codban->EditValue = $arwrk;

			// codsuc
			$this->codsuc->EditAttrs["class"] = "form-control";
			$this->codsuc->EditCustomAttributes = "";
			if (trim(strval($this->codsuc->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->codsuc->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codnum`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `codbanco` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sucbancos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codsuc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre` ASC";
			$rswrk = $conn-> executeUpdate($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->codsuc->EditValue = $arwrk;

			// codcta
			$this->codcta->EditAttrs["class"] = "form-control";
			$this->codcta->EditCustomAttributes = "";
			$this->codcta->EditValue = ew_HtmlEncode($this->codcta->CurrentValue);
			$this->codcta->PlaceHolder = ew_RemoveHtml($this->codcta->FldCaption());

			// tipcta
			$this->tipcta->EditAttrs["class"] = "form-control";
			$this->tipcta->EditCustomAttributes = "";
			$this->tipcta->EditValue = ew_HtmlEncode($this->tipcta->CurrentValue);
			$this->tipcta->PlaceHolder = ew_RemoveHtml($this->tipcta->FldCaption());

			// codchq
			$this->codchq->EditAttrs["class"] = "form-control";
			$this->codchq->EditCustomAttributes = "";
			$this->codchq->EditValue = ew_HtmlEncode($this->codchq->CurrentValue);
			$this->codchq->PlaceHolder = ew_RemoveHtml($this->codchq->FldCaption());

			// codpais
			$this->codpais->EditAttrs["class"] = "form-control";
			$this->codpais->EditCustomAttributes = "";
			if (trim(strval($this->codpais->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`codnum`" . ew_SearchString("=", $this->codpais->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `codnum`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `paises`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codpais, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `descripcion` DESC";
			$rswrk = $conn-> executeUpdate($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->codpais->EditValue = $arwrk;

			// importe
			$this->importe->EditAttrs["class"] = "form-control";
			$this->importe->EditCustomAttributes = "";
			$this->importe->EditValue = ew_HtmlEncode($this->importe->CurrentValue);
			$this->importe->PlaceHolder = ew_RemoveHtml($this->importe->FldCaption());
			if (strval($this->importe->EditValue) <> "" && is_numeric($this->importe->EditValue)) {
			$this->importe->EditValue = ew_FormatNumber($this->importe->EditValue, -2, -1, -2, 0);
			$this->importe->OldValue = $this->importe->EditValue;
			}

			// fechaemis
			$this->fechaemis->EditAttrs["class"] = "form-control";
			$this->fechaemis->EditCustomAttributes = "";
			$this->fechaemis->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechaemis->CurrentValue, 7));
			$this->fechaemis->PlaceHolder = ew_RemoveHtml($this->fechaemis->FldCaption());

			// fechapago
			$this->fechapago->EditAttrs["class"] = "form-control";
			$this->fechapago->EditCustomAttributes = "";
			$this->fechapago->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechapago->CurrentValue, 7));
			$this->fechapago->PlaceHolder = ew_RemoveHtml($this->fechapago->FldCaption());

			// entrego
			$this->entrego->EditAttrs["class"] = "form-control";
			$this->entrego->EditCustomAttributes = "";
			$this->entrego->EditValue = ew_HtmlEncode($this->entrego->CurrentValue);
			$this->entrego->PlaceHolder = ew_RemoveHtml($this->entrego->FldCaption());

			// recibio
			$this->recibio->EditAttrs["class"] = "form-control";
			$this->recibio->EditCustomAttributes = "";
			$this->recibio->EditValue = ew_HtmlEncode($this->recibio->CurrentValue);
			$this->recibio->PlaceHolder = ew_RemoveHtml($this->recibio->FldCaption());

			// fechaingr
			$this->fechaingr->EditAttrs["class"] = "form-control";
			$this->fechaingr->EditCustomAttributes = "";
			$this->fechaingr->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechaingr->CurrentValue, 7));
			$this->fechaingr->PlaceHolder = ew_RemoveHtml($this->fechaingr->FldCaption());

			// fechaentrega
			$this->fechaentrega->EditAttrs["class"] = "form-control";
			$this->fechaentrega->EditCustomAttributes = "";
			$this->fechaentrega->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechaentrega->CurrentValue, 7));
			$this->fechaentrega->PlaceHolder = ew_RemoveHtml($this->fechaentrega->FldCaption());

			// tcomprel
			$this->tcomprel->EditAttrs["class"] = "form-control";
			$this->tcomprel->EditCustomAttributes = "";
			if ($this->tcomprel->getSessionValue() <> "") {
				$this->tcomprel->CurrentValue = $this->tcomprel->getSessionValue();
				$this->tcomprel->OldValue = $this->tcomprel->CurrentValue;
			$this->tcomprel->ViewValue = $this->tcomprel->CurrentValue;
			$this->tcomprel->ViewCustomAttributes = "";
			} else {
			$this->tcomprel->EditValue = ew_HtmlEncode($this->tcomprel->CurrentValue);
			$this->tcomprel->PlaceHolder = ew_RemoveHtml($this->tcomprel->FldCaption());
			}

			// serierel
			$this->serierel->EditAttrs["class"] = "form-control";
			$this->serierel->EditCustomAttributes = "";
			if ($this->serierel->getSessionValue() <> "") {
				$this->serierel->CurrentValue = $this->serierel->getSessionValue();
				$this->serierel->OldValue = $this->serierel->CurrentValue;
			$this->serierel->ViewValue = $this->serierel->CurrentValue;
			$this->serierel->ViewCustomAttributes = "";
			} else {
			$this->serierel->EditValue = ew_HtmlEncode($this->serierel->CurrentValue);
			$this->serierel->PlaceHolder = ew_RemoveHtml($this->serierel->FldCaption());
			}

			// ncomprel
			$this->ncomprel->EditAttrs["class"] = "form-control";
			$this->ncomprel->EditCustomAttributes = "";
			if ($this->ncomprel->getSessionValue() <> "") {
				$this->ncomprel->CurrentValue = $this->ncomprel->getSessionValue();
				$this->ncomprel->OldValue = $this->ncomprel->CurrentValue;
			$this->ncomprel->ViewValue = $this->ncomprel->CurrentValue;
			$this->ncomprel->ViewCustomAttributes = "";
			} else {
			$this->ncomprel->EditValue = ew_HtmlEncode($this->ncomprel->CurrentValue);
			$this->ncomprel->PlaceHolder = ew_RemoveHtml($this->ncomprel->FldCaption());
			}

			// estado
			$this->estado->EditAttrs["class"] = "form-control";
			$this->estado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->estado->FldTagValue(1), $this->estado->FldTagCaption(1) <> "" ? $this->estado->FldTagCaption(1) : $this->estado->FldTagValue(1));
			$arwrk[] = array($this->estado->FldTagValue(2), $this->estado->FldTagCaption(2) <> "" ? $this->estado->FldTagCaption(2) : $this->estado->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->estado->EditValue = $arwrk;

			// moneda
			$this->moneda->EditAttrs["class"] = "form-control";
			$this->moneda->EditCustomAttributes = "";
			$this->moneda->EditValue = ew_HtmlEncode($this->moneda->CurrentValue);
			$this->moneda->PlaceHolder = ew_RemoveHtml($this->moneda->FldCaption());

			// fechahora
			$this->fechahora->EditAttrs["class"] = "form-control";
			$this->fechahora->EditCustomAttributes = "";
			$this->fechahora->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechahora->CurrentValue, 7));
			$this->fechahora->PlaceHolder = ew_RemoveHtml($this->fechahora->FldCaption());

			// usuario
			$this->usuario->EditAttrs["class"] = "form-control";
			$this->usuario->EditCustomAttributes = "";
			$this->usuario->EditValue = ew_HtmlEncode($this->usuario->CurrentValue);
			$this->usuario->PlaceHolder = ew_RemoveHtml($this->usuario->FldCaption());

			// tcompsal
			$this->tcompsal->EditAttrs["class"] = "form-control";
			$this->tcompsal->EditCustomAttributes = "";
			$this->tcompsal->EditValue = ew_HtmlEncode($this->tcompsal->CurrentValue);
			$this->tcompsal->PlaceHolder = ew_RemoveHtml($this->tcompsal->FldCaption());

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

			// codrem
			$this->codrem->EditAttrs["class"] = "form-control";
			$this->codrem->EditCustomAttributes = "";
			if (trim(strval($this->codrem->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`ncomp`" . ew_SearchString("=", $this->codrem->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `ncomp`, `ncomp` AS `DispFld`, `direccion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `remates`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codrem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `ncomp` DESC";
			$rswrk = $conn-> executeUpdate($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->codrem->EditValue = $arwrk;

			// cotiz
			$this->cotiz->EditAttrs["class"] = "form-control";
			$this->cotiz->EditCustomAttributes = "";
			$this->cotiz->EditValue = ew_HtmlEncode($this->cotiz->CurrentValue);
			$this->cotiz->PlaceHolder = ew_RemoveHtml($this->cotiz->FldCaption());
			if (strval($this->cotiz->EditValue) <> "" && is_numeric($this->cotiz->EditValue)) {
			$this->cotiz->EditValue = ew_FormatNumber($this->cotiz->EditValue, -2, -1, -2, 0);
			$this->cotiz->OldValue = $this->cotiz->EditValue;
			}

			// usurel
			$this->usurel->EditAttrs["class"] = "form-control";
			$this->usurel->EditCustomAttributes = "";
			$this->usurel->EditValue = ew_HtmlEncode($this->usurel->CurrentValue);
			$this->usurel->PlaceHolder = ew_RemoveHtml($this->usurel->FldCaption());

			// fecharel
			$this->fecharel->EditAttrs["class"] = "form-control";
			$this->fecharel->EditCustomAttributes = "";
			$this->fecharel->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecharel->CurrentValue, 7));
			$this->fecharel->PlaceHolder = ew_RemoveHtml($this->fecharel->FldCaption());

			// ususal
			$this->ususal->EditAttrs["class"] = "form-control";
			$this->ususal->EditCustomAttributes = "";
			$this->ususal->EditValue = ew_HtmlEncode($this->ususal->CurrentValue);
			$this->ususal->PlaceHolder = ew_RemoveHtml($this->ususal->FldCaption());

			// fechasal
			$this->fechasal->EditAttrs["class"] = "form-control";
			$this->fechasal->EditCustomAttributes = "";
			$this->fechasal->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechasal->CurrentValue, 7));
			$this->fechasal->PlaceHolder = ew_RemoveHtml($this->fechasal->FldCaption());

			// Edit refer script
			// codnum

			$this->codnum->HrefValue = "";

			// tcomp
			$this->tcomp->HrefValue = "";

			// serie
			$this->serie->HrefValue = "";

			// ncomp
			$this->ncomp->HrefValue = "";

			// codban
			$this->codban->HrefValue = "";

			// codsuc
			$this->codsuc->HrefValue = "";

			// codcta
			$this->codcta->HrefValue = "";

			// tipcta
			$this->tipcta->HrefValue = "";

			// codchq
			$this->codchq->HrefValue = "";

			// codpais
			$this->codpais->HrefValue = "";

			// importe
			$this->importe->HrefValue = "";

			// fechaemis
			$this->fechaemis->HrefValue = "";

			// fechapago
			$this->fechapago->HrefValue = "";

			// entrego
			$this->entrego->HrefValue = "";

			// recibio
			$this->recibio->HrefValue = "";

			// fechaingr
			$this->fechaingr->HrefValue = "";

			// fechaentrega
			$this->fechaentrega->HrefValue = "";

			// tcomprel
			$this->tcomprel->HrefValue = "";

			// serierel
			$this->serierel->HrefValue = "";

			// ncomprel
			$this->ncomprel->HrefValue = "";

			// estado
			$this->estado->HrefValue = "";

			// moneda
			$this->moneda->HrefValue = "";

			// fechahora
			$this->fechahora->HrefValue = "";

			// usuario
			$this->usuario->HrefValue = "";

			// tcompsal
			$this->tcompsal->HrefValue = "";

			// seriesal
			$this->seriesal->HrefValue = "";

			// ncompsal
			$this->ncompsal->HrefValue = "";

			// codrem
			$this->codrem->HrefValue = "";

			// cotiz
			$this->cotiz->HrefValue = "";

			// usurel
			$this->usurel->HrefValue = "";

			// fecharel
			$this->fecharel->HrefValue = "";

			// ususal
			$this->ususal->HrefValue = "";

			// fechasal
			$this->fechasal->HrefValue = "";
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
		if (!$this->tcomp->FldIsDetailKey && !is_null($this->tcomp->FormValue) && $this->tcomp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tcomp->FldCaption(), $this->tcomp->ReqErrMsg));
		}
		if (!$this->serie->FldIsDetailKey && !is_null($this->serie->FormValue) && $this->serie->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->serie->FldCaption(), $this->serie->ReqErrMsg));
		}
		if (!$this->ncomp->FldIsDetailKey && !is_null($this->ncomp->FormValue) && $this->ncomp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ncomp->FldCaption(), $this->ncomp->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->ncomp->FormValue)) {
			ew_AddMessage($gsFormError, $this->ncomp->FldErrMsg());
		}
		if (!$this->importe->FldIsDetailKey && !is_null($this->importe->FormValue) && $this->importe->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->importe->FldCaption(), $this->importe->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->importe->FormValue)) {
			ew_AddMessage($gsFormError, $this->importe->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fechaemis->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechaemis->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fechapago->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechapago->FldErrMsg());
		}
		if (!ew_CheckInteger($this->entrego->FormValue)) {
			ew_AddMessage($gsFormError, $this->entrego->FldErrMsg());
		}
		if (!ew_CheckInteger($this->recibio->FormValue)) {
			ew_AddMessage($gsFormError, $this->recibio->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fechaingr->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechaingr->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fechaentrega->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechaentrega->FldErrMsg());
		}
		if (!ew_CheckInteger($this->tcomprel->FormValue)) {
			ew_AddMessage($gsFormError, $this->tcomprel->FldErrMsg());
		}
		if (!ew_CheckInteger($this->serierel->FormValue)) {
			ew_AddMessage($gsFormError, $this->serierel->FldErrMsg());
		}
		if (!ew_CheckInteger($this->ncomprel->FormValue)) {
			ew_AddMessage($gsFormError, $this->ncomprel->FldErrMsg());
		}
		if (!$this->estado->FldIsDetailKey && !is_null($this->estado->FormValue) && $this->estado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estado->FldCaption(), $this->estado->ReqErrMsg));
		}
		if (!$this->moneda->FldIsDetailKey && !is_null($this->moneda->FormValue) && $this->moneda->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->moneda->FldCaption(), $this->moneda->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->moneda->FormValue)) {
			ew_AddMessage($gsFormError, $this->moneda->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fechahora->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechahora->FldErrMsg());
		}
		if (!ew_CheckInteger($this->usuario->FormValue)) {
			ew_AddMessage($gsFormError, $this->usuario->FldErrMsg());
		}
		if (!ew_CheckInteger($this->tcompsal->FormValue)) {
			ew_AddMessage($gsFormError, $this->tcompsal->FldErrMsg());
		}
		if (!ew_CheckInteger($this->seriesal->FormValue)) {
			ew_AddMessage($gsFormError, $this->seriesal->FldErrMsg());
		}
		if (!ew_CheckInteger($this->ncompsal->FormValue)) {
			ew_AddMessage($gsFormError, $this->ncompsal->FldErrMsg());
		}
		if (!ew_CheckNumber($this->cotiz->FormValue)) {
			ew_AddMessage($gsFormError, $this->cotiz->FldErrMsg());
		}
		if (!ew_CheckInteger($this->usurel->FormValue)) {
			ew_AddMessage($gsFormError, $this->usurel->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fecharel->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecharel->FldErrMsg());
		}
		if (!ew_CheckInteger($this->ususal->FormValue)) {
			ew_AddMessage($gsFormError, $this->ususal->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fechasal->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechasal->FldErrMsg());
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
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
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
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['codnum'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
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
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
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

			// tcomp
			$this->tcomp->SetDbValueDef($rsnew, $this->tcomp->CurrentValue, 0, $this->tcomp->ReadOnly);

			// serie
			$this->serie->SetDbValueDef($rsnew, $this->serie->CurrentValue, 0, $this->serie->ReadOnly);

			// ncomp
			$this->ncomp->SetDbValueDef($rsnew, $this->ncomp->CurrentValue, 0, $this->ncomp->ReadOnly);

			// codban
			$this->codban->SetDbValueDef($rsnew, $this->codban->CurrentValue, NULL, $this->codban->ReadOnly);

			// codsuc
			$this->codsuc->SetDbValueDef($rsnew, $this->codsuc->CurrentValue, NULL, $this->codsuc->ReadOnly);

			// codcta
			$this->codcta->SetDbValueDef($rsnew, $this->codcta->CurrentValue, NULL, $this->codcta->ReadOnly);

			// tipcta
			$this->tipcta->SetDbValueDef($rsnew, $this->tipcta->CurrentValue, NULL, $this->tipcta->ReadOnly);

			// codchq
			$this->codchq->SetDbValueDef($rsnew, $this->codchq->CurrentValue, NULL, $this->codchq->ReadOnly);

			// codpais
			$this->codpais->SetDbValueDef($rsnew, $this->codpais->CurrentValue, NULL, $this->codpais->ReadOnly);

			// importe
			$this->importe->SetDbValueDef($rsnew, $this->importe->CurrentValue, 0, $this->importe->ReadOnly);

			// fechaemis
			$this->fechaemis->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaemis->CurrentValue, 7), NULL, $this->fechaemis->ReadOnly);

			// fechapago
			$this->fechapago->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechapago->CurrentValue, 7), NULL, $this->fechapago->ReadOnly);

			// entrego
			$this->entrego->SetDbValueDef($rsnew, $this->entrego->CurrentValue, NULL, $this->entrego->ReadOnly);

			// recibio
			$this->recibio->SetDbValueDef($rsnew, $this->recibio->CurrentValue, NULL, $this->recibio->ReadOnly);

			// fechaingr
			$this->fechaingr->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaingr->CurrentValue, 7), NULL, $this->fechaingr->ReadOnly);

			// fechaentrega
			$this->fechaentrega->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaentrega->CurrentValue, 7), NULL, $this->fechaentrega->ReadOnly);

			// tcomprel
			$this->tcomprel->SetDbValueDef($rsnew, $this->tcomprel->CurrentValue, NULL, $this->tcomprel->ReadOnly);

			// serierel
			$this->serierel->SetDbValueDef($rsnew, $this->serierel->CurrentValue, NULL, $this->serierel->ReadOnly);

			// ncomprel
			$this->ncomprel->SetDbValueDef($rsnew, $this->ncomprel->CurrentValue, NULL, $this->ncomprel->ReadOnly);

			// estado
			$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, "", $this->estado->ReadOnly);

			// moneda
			$this->moneda->SetDbValueDef($rsnew, $this->moneda->CurrentValue, 0, $this->moneda->ReadOnly);

			// fechahora
			$this->fechahora->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechahora->CurrentValue, 7), NULL, $this->fechahora->ReadOnly);

			// usuario
			$this->usuario->SetDbValueDef($rsnew, $this->usuario->CurrentValue, NULL, $this->usuario->ReadOnly);

			// tcompsal
			$this->tcompsal->SetDbValueDef($rsnew, $this->tcompsal->CurrentValue, NULL, $this->tcompsal->ReadOnly);

			// seriesal
			$this->seriesal->SetDbValueDef($rsnew, $this->seriesal->CurrentValue, NULL, $this->seriesal->ReadOnly);

			// ncompsal
			$this->ncompsal->SetDbValueDef($rsnew, $this->ncompsal->CurrentValue, NULL, $this->ncompsal->ReadOnly);

			// codrem
			$this->codrem->SetDbValueDef($rsnew, $this->codrem->CurrentValue, NULL, $this->codrem->ReadOnly);

			// cotiz
			$this->cotiz->SetDbValueDef($rsnew, $this->cotiz->CurrentValue, NULL, $this->cotiz->ReadOnly);

			// usurel
			$this->usurel->SetDbValueDef($rsnew, $this->usurel->CurrentValue, NULL, $this->usurel->ReadOnly);

			// fecharel
			$this->fecharel->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecharel->CurrentValue, 7), NULL, $this->fecharel->ReadOnly);

			// ususal
			$this->ususal->SetDbValueDef($rsnew, $this->ususal->CurrentValue, NULL, $this->ususal->ReadOnly);

			// fechasal
			$this->fechasal->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechasal->CurrentValue, 7), NULL, $this->fechasal->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
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
			if ($this->getCurrentMasterTable() == "cabfac") {
				$this->tcomprel->CurrentValue = $this->tcomprel->getSessionValue();
				$this->serierel->CurrentValue = $this->serierel->getSessionValue();
				$this->ncomprel->CurrentValue = $this->ncomprel->getSessionValue();
			}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// tcomp
		$this->tcomp->SetDbValueDef($rsnew, $this->tcomp->CurrentValue, 0, FALSE);

		// serie
		$this->serie->SetDbValueDef($rsnew, $this->serie->CurrentValue, 0, FALSE);

		// ncomp
		$this->ncomp->SetDbValueDef($rsnew, $this->ncomp->CurrentValue, 0, FALSE);

		// codban
		$this->codban->SetDbValueDef($rsnew, $this->codban->CurrentValue, NULL, FALSE);

		// codsuc
		$this->codsuc->SetDbValueDef($rsnew, $this->codsuc->CurrentValue, NULL, FALSE);

		// codcta
		$this->codcta->SetDbValueDef($rsnew, $this->codcta->CurrentValue, NULL, FALSE);

		// tipcta
		$this->tipcta->SetDbValueDef($rsnew, $this->tipcta->CurrentValue, NULL, FALSE);

		// codchq
		$this->codchq->SetDbValueDef($rsnew, $this->codchq->CurrentValue, NULL, FALSE);

		// codpais
		$this->codpais->SetDbValueDef($rsnew, $this->codpais->CurrentValue, NULL, FALSE);

		// importe
		$this->importe->SetDbValueDef($rsnew, $this->importe->CurrentValue, 0, strval($this->importe->CurrentValue) == "");

		// fechaemis
		$this->fechaemis->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaemis->CurrentValue, 7), NULL, FALSE);

		// fechapago
		$this->fechapago->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechapago->CurrentValue, 7), NULL, FALSE);

		// entrego
		$this->entrego->SetDbValueDef($rsnew, $this->entrego->CurrentValue, NULL, FALSE);

		// recibio
		$this->recibio->SetDbValueDef($rsnew, $this->recibio->CurrentValue, NULL, FALSE);

		// fechaingr
		$this->fechaingr->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaingr->CurrentValue, 7), NULL, FALSE);

		// fechaentrega
		$this->fechaentrega->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaentrega->CurrentValue, 7), NULL, FALSE);

		// tcomprel
		$this->tcomprel->SetDbValueDef($rsnew, $this->tcomprel->CurrentValue, NULL, FALSE);

		// serierel
		$this->serierel->SetDbValueDef($rsnew, $this->serierel->CurrentValue, NULL, FALSE);

		// ncomprel
		$this->ncomprel->SetDbValueDef($rsnew, $this->ncomprel->CurrentValue, NULL, FALSE);

		// estado
		$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, "", FALSE);

		// moneda
		$this->moneda->SetDbValueDef($rsnew, $this->moneda->CurrentValue, 0, strval($this->moneda->CurrentValue) == "");

		// fechahora
		$this->fechahora->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechahora->CurrentValue, 7), NULL, FALSE);

		// usuario
		$this->usuario->SetDbValueDef($rsnew, $this->usuario->CurrentValue, NULL, FALSE);

		// tcompsal
		$this->tcompsal->SetDbValueDef($rsnew, $this->tcompsal->CurrentValue, NULL, FALSE);

		// seriesal
		$this->seriesal->SetDbValueDef($rsnew, $this->seriesal->CurrentValue, NULL, FALSE);

		// ncompsal
		$this->ncompsal->SetDbValueDef($rsnew, $this->ncompsal->CurrentValue, NULL, FALSE);

		// codrem
		$this->codrem->SetDbValueDef($rsnew, $this->codrem->CurrentValue, NULL, FALSE);

		// cotiz
		$this->cotiz->SetDbValueDef($rsnew, $this->cotiz->CurrentValue, NULL, FALSE);

		// usurel
		$this->usurel->SetDbValueDef($rsnew, $this->usurel->CurrentValue, NULL, FALSE);

		// fecharel
		$this->fecharel->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecharel->CurrentValue, 7), NULL, FALSE);

		// ususal
		$this->ususal->SetDbValueDef($rsnew, $this->ususal->CurrentValue, NULL, FALSE);

		// fechasal
		$this->fechasal->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechasal->CurrentValue, 7), NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
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
			$this->codnum->setDbValue($conn->Insert_ID());
			$rsnew['codnum'] = $this->codnum->DbValue;
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
		if ($sMasterTblVar == "cabfac") {
			$this->tcomprel->Visible = FALSE;
			if ($GLOBALS["cabfac"]->EventCancelled) $this->EventCancelled = TRUE;
			$this->serierel->Visible = FALSE;
			if ($GLOBALS["cabfac"]->EventCancelled) $this->EventCancelled = TRUE;
			$this->ncomprel->Visible = FALSE;
			if ($GLOBALS["cabfac"]->EventCancelled) $this->EventCancelled = TRUE;
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
