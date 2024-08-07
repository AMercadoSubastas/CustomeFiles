<?php

namespace PHPMaker2024\Subastas2024;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;
use Closure;

/**
 * Page class
 */
class CartvaloresEdit extends Cartvalores
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "CartvaloresEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "CartvaloresEdit";

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page layout
    public $UseLayout = true;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl($withArgs = true)
    {
        $route = GetRoute();
        $args = RemoveXss($route->getArguments());
        if (!$withArgs) {
            foreach ($args as $key => &$val) {
                $val = "";
            }
            unset($val);
        }
        return rtrim(UrlFor($route->getName(), $args), "/") . "?";
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<div id="ew-page-header">' . $header . '</div>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<div id="ew-page-footer">' . $footer . '</div>';
        }
    }

    // Set field visibility
    public function setVisibility()
    {
        $this->codnum->setVisibility();
        $this->tcomp->setVisibility();
        $this->serie->setVisibility();
        $this->ncomp->setVisibility();
        $this->codban->setVisibility();
        $this->codsuc->setVisibility();
        $this->codcta->setVisibility();
        $this->tipcta->setVisibility();
        $this->codchq->setVisibility();
        $this->codpais->setVisibility();
        $this->importe->setVisibility();
        $this->fechaemis->setVisibility();
        $this->fechapago->setVisibility();
        $this->entrego->setVisibility();
        $this->recibio->setVisibility();
        $this->fechaingr->setVisibility();
        $this->fechaentrega->setVisibility();
        $this->tcomprel->setVisibility();
        $this->serierel->setVisibility();
        $this->ncomprel->setVisibility();
        $this->estado->setVisibility();
        $this->moneda->setVisibility();
        $this->fechahora->setVisibility();
        $this->usuario->setVisibility();
        $this->tcompsal->setVisibility();
        $this->seriesal->setVisibility();
        $this->ncompsal->setVisibility();
        $this->codrem->setVisibility();
        $this->cotiz->setVisibility();
        $this->usurel->setVisibility();
        $this->fecharel->setVisibility();
        $this->ususal->setVisibility();
        $this->fechasal->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'cartvalores';
        $this->TableName = 'cartvalores';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (cartvalores)
        if (!isset($GLOBALS["cartvalores"]) || $GLOBALS["cartvalores"]::class == PROJECT_NAMESPACE . "cartvalores") {
            $GLOBALS["cartvalores"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'cartvalores');
        }

        // Start timer
        $DebugTimer = Container("debug.timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] ??= $this->getConnection();

        // User table object
        $UserTable = Container("usertable");
    }

    // Get content from stream
    public function getContents(): string
    {
        global $Response;
        return $Response?->getBody() ?? ob_get_clean();
    }

    // Is lookup
    public function isLookup()
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup()
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

        // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }
        DispatchEvent(new PageUnloadedEvent($this), PageUnloadedEvent::NAME);
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show response for API
                $ar = array_merge($this->getMessages(), $url ? ["url" => GetUrl($url)] : []);
                WriteJson($ar);
            }
            $this->clearMessages(); // Clear messages for API request
            return;
        } else { // Check if response is JSON
            if (WithJsonResponse()) { // With JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $pageName = GetPageName($url);
                $result = ["url" => GetUrl($url), "modal" => "1"];  // Assume return to modal for simplicity
                if (
                    SameString($pageName, GetPageName($this->getListUrl())) ||
                    SameString($pageName, GetPageName($this->getViewUrl())) ||
                    SameString($pageName, GetPageName(CurrentMasterTable()?->getViewUrl() ?? ""))
                ) { // List / View / Master View page
                    if (!SameString($pageName, GetPageName($this->getListUrl()))) { // Not List page
                        $result["caption"] = $this->getModalCaption($pageName);
                        $result["view"] = SameString($pageName, "CartvaloresView"); // If View page, no primary button
                    } else { // List page
                        $result["error"] = $this->getFailureMessage(); // List page should not be shown as modal => error
                        $this->clearFailureMessage();
                    }
                } else { // Other pages (add messages and then clear messages)
                    $result = array_merge($this->getMessages(), ["modal" => "1"]);
                    $this->clearMessages();
                }
                WriteJson($result);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }

    // Get records from result set
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Result set
            while ($row = $rs->fetch()) {
                $this->loadRowValues($row); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($row);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DataType::BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['codnum'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->codnum->Visible = false;
        }
    }

    // Lookup data
    public function lookup(array $req = [], bool $response = true)
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = $req["field"] ?? null;
        if (!$fieldName) {
            return [];
        }
        $fld = $this->Fields[$fieldName];
        $lookup = $fld->Lookup;
        $name = $req["name"] ?? "";
        if (ContainsString($name, "query_builder_rule")) {
            $lookup->FilterFields = []; // Skip parent fields if any
        }

        // Get lookup parameters
        $lookupType = $req["ajax"] ?? "unknown";
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal") || SameText($lookupType, "filter")) {
            $searchValue = $req["q"] ?? $req["sv"] ?? "";
            $pageSize = $req["n"] ?? $req["recperpage"] ?? 10;
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = $req["q"] ?? "";
            $pageSize = $req["n"] ?? -1;
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
        }
        $start = $req["start"] ?? -1;
        $start = is_numeric($start) ? (int)$start : -1;
        $page = $req["page"] ?? -1;
        $page = is_numeric($page) ? (int)$page : -1;
        $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        $userSelect = Decrypt($req["s"] ?? "");
        $userFilter = Decrypt($req["f"] ?? "");
        $userOrderBy = Decrypt($req["o"] ?? "");
        $keys = $req["keys"] ?? null;
        $lookup->LookupType = $lookupType; // Lookup type
        $lookup->FilterValues = []; // Clear filter values first
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = $req["v0"] ?? $req["lookupValue"] ?? "";
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = $req["v" . $i] ?? "";
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        return $lookup->toJson($this, $response); // Use settings from current page
    }

    // Properties
    public $FormClassName = "ew-form ew-edit-form overlay-wrapper";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $HashValue; // Hash Value
    public $DisplayRecords = 1;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecordCount;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $Language, $Security, $CurrentForm, $SkipHeaderFooter;

        // Is modal
        $this->IsModal = ConvertToBool(Param("modal"));
        $this->UseLayout = $this->UseLayout && !$this->IsModal;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Load user profile
        if (IsLoggedIn()) {
            Profile()->setUserName(CurrentUserName())->loadFromStorage();
        }

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->setVisibility();

        // Set lookup cache
        if (!in_array($this->PageID, Config("LOOKUP_CACHE_PAGE_IDS"))) {
            $this->setUseLookupCache(false);
        }

        // Global Page Loading event (in userfn*.php)
        DispatchEvent(new PageLoadingEvent($this), PageLoadingEvent::NAME);

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Hide fields for add/edit
        if (!$this->UseAjaxActions) {
            $this->hideFieldsForAddEdit();
        }
        // Use inline delete
        if ($this->UseAjaxActions) {
            $this->InlineDelete = true;
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->tcomp);
        $this->setupLookupOptions($this->codban);
        $this->setupLookupOptions($this->codsuc);
        $this->setupLookupOptions($this->codpais);
        $this->setupLookupOptions($this->estado);
        $this->setupLookupOptions($this->codrem);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("codnum") ?? Key(0) ?? Route(2)) !== null) {
                $this->codnum->setQueryStringValue($keyValue);
                $this->codnum->setOldValue($this->codnum->QueryStringValue);
            } elseif (Post("codnum") !== null) {
                $this->codnum->setFormValue(Post("codnum"));
                $this->codnum->setOldValue($this->codnum->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action", "") !== "") {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey(Post($this->OldKeyName), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("codnum") ?? Route("codnum")) !== null) {
                    $this->codnum->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->codnum->CurrentValue = null;
                }
            }

            // Load result set
            if ($this->isShow()) {
                    // Load current record
                    $loaded = $this->loadRow();
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                    if (!$loaded) { // Load record based on key
                        if ($this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("CartvaloresList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "CartvaloresList") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) { // Update record based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Update success
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "CartvaloresList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "CartvaloresList"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->IsModal && $this->UseAjaxActions) { // Return JSON error message
                    WriteJson(["success" => false, "validation" => $this->getValidationErrors(), "error" => $this->getFailureMessage()]);
                    $this->clearFailureMessage();
                    $this->terminate();
                    return;
                } elseif ($this->getFailureMessage() == $Language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = RowType::EDIT; // Render as Edit
        $this->resetAttributes();
        $this->renderRow();

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            DispatchEvent(new PageRenderingEvent($this), PageRenderingEvent::NAME);

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }

            // Render search option
            if (method_exists($this, "renderSearchOptions")) {
                $this->renderSearchOptions();
            }
        }
    }

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'codnum' first before field var 'x_codnum'
        $val = $CurrentForm->hasValue("codnum") ? $CurrentForm->getValue("codnum") : $CurrentForm->getValue("x_codnum");
        if (!$this->codnum->IsDetailKey) {
            $this->codnum->setFormValue($val);
        }

        // Check field name 'tcomp' first before field var 'x_tcomp'
        $val = $CurrentForm->hasValue("tcomp") ? $CurrentForm->getValue("tcomp") : $CurrentForm->getValue("x_tcomp");
        if (!$this->tcomp->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tcomp->Visible = false; // Disable update for API request
            } else {
                $this->tcomp->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'serie' first before field var 'x_serie'
        $val = $CurrentForm->hasValue("serie") ? $CurrentForm->getValue("serie") : $CurrentForm->getValue("x_serie");
        if (!$this->serie->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->serie->Visible = false; // Disable update for API request
            } else {
                $this->serie->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'ncomp' first before field var 'x_ncomp'
        $val = $CurrentForm->hasValue("ncomp") ? $CurrentForm->getValue("ncomp") : $CurrentForm->getValue("x_ncomp");
        if (!$this->ncomp->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ncomp->Visible = false; // Disable update for API request
            } else {
                $this->ncomp->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'codban' first before field var 'x_codban'
        $val = $CurrentForm->hasValue("codban") ? $CurrentForm->getValue("codban") : $CurrentForm->getValue("x_codban");
        if (!$this->codban->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->codban->Visible = false; // Disable update for API request
            } else {
                $this->codban->setFormValue($val);
            }
        }

        // Check field name 'codsuc' first before field var 'x_codsuc'
        $val = $CurrentForm->hasValue("codsuc") ? $CurrentForm->getValue("codsuc") : $CurrentForm->getValue("x_codsuc");
        if (!$this->codsuc->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->codsuc->Visible = false; // Disable update for API request
            } else {
                $this->codsuc->setFormValue($val);
            }
        }

        // Check field name 'codcta' first before field var 'x_codcta'
        $val = $CurrentForm->hasValue("codcta") ? $CurrentForm->getValue("codcta") : $CurrentForm->getValue("x_codcta");
        if (!$this->codcta->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->codcta->Visible = false; // Disable update for API request
            } else {
                $this->codcta->setFormValue($val);
            }
        }

        // Check field name 'tipcta' first before field var 'x_tipcta'
        $val = $CurrentForm->hasValue("tipcta") ? $CurrentForm->getValue("tipcta") : $CurrentForm->getValue("x_tipcta");
        if (!$this->tipcta->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tipcta->Visible = false; // Disable update for API request
            } else {
                $this->tipcta->setFormValue($val);
            }
        }

        // Check field name 'codchq' first before field var 'x_codchq'
        $val = $CurrentForm->hasValue("codchq") ? $CurrentForm->getValue("codchq") : $CurrentForm->getValue("x_codchq");
        if (!$this->codchq->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->codchq->Visible = false; // Disable update for API request
            } else {
                $this->codchq->setFormValue($val);
            }
        }

        // Check field name 'codpais' first before field var 'x_codpais'
        $val = $CurrentForm->hasValue("codpais") ? $CurrentForm->getValue("codpais") : $CurrentForm->getValue("x_codpais");
        if (!$this->codpais->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->codpais->Visible = false; // Disable update for API request
            } else {
                $this->codpais->setFormValue($val);
            }
        }

        // Check field name 'importe' first before field var 'x_importe'
        $val = $CurrentForm->hasValue("importe") ? $CurrentForm->getValue("importe") : $CurrentForm->getValue("x_importe");
        if (!$this->importe->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->importe->Visible = false; // Disable update for API request
            } else {
                $this->importe->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'fechaemis' first before field var 'x_fechaemis'
        $val = $CurrentForm->hasValue("fechaemis") ? $CurrentForm->getValue("fechaemis") : $CurrentForm->getValue("x_fechaemis");
        if (!$this->fechaemis->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fechaemis->Visible = false; // Disable update for API request
            } else {
                $this->fechaemis->setFormValue($val, true, $validate);
            }
            $this->fechaemis->CurrentValue = UnFormatDateTime($this->fechaemis->CurrentValue, $this->fechaemis->formatPattern());
        }

        // Check field name 'fechapago' first before field var 'x_fechapago'
        $val = $CurrentForm->hasValue("fechapago") ? $CurrentForm->getValue("fechapago") : $CurrentForm->getValue("x_fechapago");
        if (!$this->fechapago->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fechapago->Visible = false; // Disable update for API request
            } else {
                $this->fechapago->setFormValue($val, true, $validate);
            }
            $this->fechapago->CurrentValue = UnFormatDateTime($this->fechapago->CurrentValue, $this->fechapago->formatPattern());
        }

        // Check field name 'entrego' first before field var 'x_entrego'
        $val = $CurrentForm->hasValue("entrego") ? $CurrentForm->getValue("entrego") : $CurrentForm->getValue("x_entrego");
        if (!$this->entrego->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->entrego->Visible = false; // Disable update for API request
            } else {
                $this->entrego->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'recibio' first before field var 'x_recibio'
        $val = $CurrentForm->hasValue("recibio") ? $CurrentForm->getValue("recibio") : $CurrentForm->getValue("x_recibio");
        if (!$this->recibio->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->recibio->Visible = false; // Disable update for API request
            } else {
                $this->recibio->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'fechaingr' first before field var 'x_fechaingr'
        $val = $CurrentForm->hasValue("fechaingr") ? $CurrentForm->getValue("fechaingr") : $CurrentForm->getValue("x_fechaingr");
        if (!$this->fechaingr->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fechaingr->Visible = false; // Disable update for API request
            } else {
                $this->fechaingr->setFormValue($val, true, $validate);
            }
            $this->fechaingr->CurrentValue = UnFormatDateTime($this->fechaingr->CurrentValue, $this->fechaingr->formatPattern());
        }

        // Check field name 'fechaentrega' first before field var 'x_fechaentrega'
        $val = $CurrentForm->hasValue("fechaentrega") ? $CurrentForm->getValue("fechaentrega") : $CurrentForm->getValue("x_fechaentrega");
        if (!$this->fechaentrega->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fechaentrega->Visible = false; // Disable update for API request
            } else {
                $this->fechaentrega->setFormValue($val, true, $validate);
            }
            $this->fechaentrega->CurrentValue = UnFormatDateTime($this->fechaentrega->CurrentValue, $this->fechaentrega->formatPattern());
        }

        // Check field name 'tcomprel' first before field var 'x_tcomprel'
        $val = $CurrentForm->hasValue("tcomprel") ? $CurrentForm->getValue("tcomprel") : $CurrentForm->getValue("x_tcomprel");
        if (!$this->tcomprel->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tcomprel->Visible = false; // Disable update for API request
            } else {
                $this->tcomprel->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'serierel' first before field var 'x_serierel'
        $val = $CurrentForm->hasValue("serierel") ? $CurrentForm->getValue("serierel") : $CurrentForm->getValue("x_serierel");
        if (!$this->serierel->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->serierel->Visible = false; // Disable update for API request
            } else {
                $this->serierel->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'ncomprel' first before field var 'x_ncomprel'
        $val = $CurrentForm->hasValue("ncomprel") ? $CurrentForm->getValue("ncomprel") : $CurrentForm->getValue("x_ncomprel");
        if (!$this->ncomprel->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ncomprel->Visible = false; // Disable update for API request
            } else {
                $this->ncomprel->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'estado' first before field var 'x_estado'
        $val = $CurrentForm->hasValue("estado") ? $CurrentForm->getValue("estado") : $CurrentForm->getValue("x_estado");
        if (!$this->estado->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->estado->Visible = false; // Disable update for API request
            } else {
                $this->estado->setFormValue($val);
            }
        }

        // Check field name 'moneda' first before field var 'x_moneda'
        $val = $CurrentForm->hasValue("moneda") ? $CurrentForm->getValue("moneda") : $CurrentForm->getValue("x_moneda");
        if (!$this->moneda->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->moneda->Visible = false; // Disable update for API request
            } else {
                $this->moneda->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'fechahora' first before field var 'x_fechahora'
        $val = $CurrentForm->hasValue("fechahora") ? $CurrentForm->getValue("fechahora") : $CurrentForm->getValue("x_fechahora");
        if (!$this->fechahora->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fechahora->Visible = false; // Disable update for API request
            } else {
                $this->fechahora->setFormValue($val, true, $validate);
            }
            $this->fechahora->CurrentValue = UnFormatDateTime($this->fechahora->CurrentValue, $this->fechahora->formatPattern());
        }

        // Check field name 'usuario' first before field var 'x_usuario'
        $val = $CurrentForm->hasValue("usuario") ? $CurrentForm->getValue("usuario") : $CurrentForm->getValue("x_usuario");
        if (!$this->usuario->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->usuario->Visible = false; // Disable update for API request
            } else {
                $this->usuario->setFormValue($val);
            }
        }

        // Check field name 'tcompsal' first before field var 'x_tcompsal'
        $val = $CurrentForm->hasValue("tcompsal") ? $CurrentForm->getValue("tcompsal") : $CurrentForm->getValue("x_tcompsal");
        if (!$this->tcompsal->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tcompsal->Visible = false; // Disable update for API request
            } else {
                $this->tcompsal->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'seriesal' first before field var 'x_seriesal'
        $val = $CurrentForm->hasValue("seriesal") ? $CurrentForm->getValue("seriesal") : $CurrentForm->getValue("x_seriesal");
        if (!$this->seriesal->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->seriesal->Visible = false; // Disable update for API request
            } else {
                $this->seriesal->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'ncompsal' first before field var 'x_ncompsal'
        $val = $CurrentForm->hasValue("ncompsal") ? $CurrentForm->getValue("ncompsal") : $CurrentForm->getValue("x_ncompsal");
        if (!$this->ncompsal->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ncompsal->Visible = false; // Disable update for API request
            } else {
                $this->ncompsal->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'codrem' first before field var 'x_codrem'
        $val = $CurrentForm->hasValue("codrem") ? $CurrentForm->getValue("codrem") : $CurrentForm->getValue("x_codrem");
        if (!$this->codrem->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->codrem->Visible = false; // Disable update for API request
            } else {
                $this->codrem->setFormValue($val);
            }
        }

        // Check field name 'cotiz' first before field var 'x_cotiz'
        $val = $CurrentForm->hasValue("cotiz") ? $CurrentForm->getValue("cotiz") : $CurrentForm->getValue("x_cotiz");
        if (!$this->cotiz->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cotiz->Visible = false; // Disable update for API request
            } else {
                $this->cotiz->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'usurel' first before field var 'x_usurel'
        $val = $CurrentForm->hasValue("usurel") ? $CurrentForm->getValue("usurel") : $CurrentForm->getValue("x_usurel");
        if (!$this->usurel->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->usurel->Visible = false; // Disable update for API request
            } else {
                $this->usurel->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'fecharel' first before field var 'x_fecharel'
        $val = $CurrentForm->hasValue("fecharel") ? $CurrentForm->getValue("fecharel") : $CurrentForm->getValue("x_fecharel");
        if (!$this->fecharel->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fecharel->Visible = false; // Disable update for API request
            } else {
                $this->fecharel->setFormValue($val, true, $validate);
            }
            $this->fecharel->CurrentValue = UnFormatDateTime($this->fecharel->CurrentValue, $this->fecharel->formatPattern());
        }

        // Check field name 'ususal' first before field var 'x_ususal'
        $val = $CurrentForm->hasValue("ususal") ? $CurrentForm->getValue("ususal") : $CurrentForm->getValue("x_ususal");
        if (!$this->ususal->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ususal->Visible = false; // Disable update for API request
            } else {
                $this->ususal->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'fechasal' first before field var 'x_fechasal'
        $val = $CurrentForm->hasValue("fechasal") ? $CurrentForm->getValue("fechasal") : $CurrentForm->getValue("x_fechasal");
        if (!$this->fechasal->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fechasal->Visible = false; // Disable update for API request
            } else {
                $this->fechasal->setFormValue($val, true, $validate);
            }
            $this->fechasal->CurrentValue = UnFormatDateTime($this->fechasal->CurrentValue, $this->fechasal->formatPattern());
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
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
        $this->fechaemis->CurrentValue = UnFormatDateTime($this->fechaemis->CurrentValue, $this->fechaemis->formatPattern());
        $this->fechapago->CurrentValue = $this->fechapago->FormValue;
        $this->fechapago->CurrentValue = UnFormatDateTime($this->fechapago->CurrentValue, $this->fechapago->formatPattern());
        $this->entrego->CurrentValue = $this->entrego->FormValue;
        $this->recibio->CurrentValue = $this->recibio->FormValue;
        $this->fechaingr->CurrentValue = $this->fechaingr->FormValue;
        $this->fechaingr->CurrentValue = UnFormatDateTime($this->fechaingr->CurrentValue, $this->fechaingr->formatPattern());
        $this->fechaentrega->CurrentValue = $this->fechaentrega->FormValue;
        $this->fechaentrega->CurrentValue = UnFormatDateTime($this->fechaentrega->CurrentValue, $this->fechaentrega->formatPattern());
        $this->tcomprel->CurrentValue = $this->tcomprel->FormValue;
        $this->serierel->CurrentValue = $this->serierel->FormValue;
        $this->ncomprel->CurrentValue = $this->ncomprel->FormValue;
        $this->estado->CurrentValue = $this->estado->FormValue;
        $this->moneda->CurrentValue = $this->moneda->FormValue;
        $this->fechahora->CurrentValue = $this->fechahora->FormValue;
        $this->fechahora->CurrentValue = UnFormatDateTime($this->fechahora->CurrentValue, $this->fechahora->formatPattern());
        $this->usuario->CurrentValue = $this->usuario->FormValue;
        $this->tcompsal->CurrentValue = $this->tcompsal->FormValue;
        $this->seriesal->CurrentValue = $this->seriesal->FormValue;
        $this->ncompsal->CurrentValue = $this->ncompsal->FormValue;
        $this->codrem->CurrentValue = $this->codrem->FormValue;
        $this->cotiz->CurrentValue = $this->cotiz->FormValue;
        $this->usurel->CurrentValue = $this->usurel->FormValue;
        $this->fecharel->CurrentValue = $this->fecharel->FormValue;
        $this->fecharel->CurrentValue = UnFormatDateTime($this->fecharel->CurrentValue, $this->fecharel->formatPattern());
        $this->ususal->CurrentValue = $this->ususal->FormValue;
        $this->fechasal->CurrentValue = $this->fechasal->FormValue;
        $this->fechasal->CurrentValue = UnFormatDateTime($this->fechasal->CurrentValue, $this->fechasal->formatPattern());
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssociative($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from result set or record
     *
     * @param array $row Record
     * @return void
     */
    public function loadRowValues($row = null)
    {
        $row = is_array($row) ? $row : $this->newRow();

        // Call Row Selected event
        $this->rowSelected($row);
        $this->codnum->setDbValue($row['codnum']);
        $this->tcomp->setDbValue($row['tcomp']);
        $this->serie->setDbValue($row['serie']);
        $this->ncomp->setDbValue($row['ncomp']);
        $this->codban->setDbValue($row['codban']);
        $this->codsuc->setDbValue($row['codsuc']);
        $this->codcta->setDbValue($row['codcta']);
        $this->tipcta->setDbValue($row['tipcta']);
        $this->codchq->setDbValue($row['codchq']);
        $this->codpais->setDbValue($row['codpais']);
        $this->importe->setDbValue($row['importe']);
        $this->fechaemis->setDbValue($row['fechaemis']);
        $this->fechapago->setDbValue($row['fechapago']);
        $this->entrego->setDbValue($row['entrego']);
        $this->recibio->setDbValue($row['recibio']);
        $this->fechaingr->setDbValue($row['fechaingr']);
        $this->fechaentrega->setDbValue($row['fechaentrega']);
        $this->tcomprel->setDbValue($row['tcomprel']);
        $this->serierel->setDbValue($row['serierel']);
        $this->ncomprel->setDbValue($row['ncomprel']);
        $this->estado->setDbValue($row['estado']);
        $this->moneda->setDbValue($row['moneda']);
        $this->fechahora->setDbValue($row['fechahora']);
        $this->usuario->setDbValue($row['usuario']);
        $this->tcompsal->setDbValue($row['tcompsal']);
        $this->seriesal->setDbValue($row['seriesal']);
        $this->ncompsal->setDbValue($row['ncompsal']);
        $this->codrem->setDbValue($row['codrem']);
        $this->cotiz->setDbValue($row['cotiz']);
        $this->usurel->setDbValue($row['usurel']);
        $this->fecharel->setDbValue($row['fecharel']);
        $this->ususal->setDbValue($row['ususal']);
        $this->fechasal->setDbValue($row['fechasal']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['codnum'] = $this->codnum->DefaultValue;
        $row['tcomp'] = $this->tcomp->DefaultValue;
        $row['serie'] = $this->serie->DefaultValue;
        $row['ncomp'] = $this->ncomp->DefaultValue;
        $row['codban'] = $this->codban->DefaultValue;
        $row['codsuc'] = $this->codsuc->DefaultValue;
        $row['codcta'] = $this->codcta->DefaultValue;
        $row['tipcta'] = $this->tipcta->DefaultValue;
        $row['codchq'] = $this->codchq->DefaultValue;
        $row['codpais'] = $this->codpais->DefaultValue;
        $row['importe'] = $this->importe->DefaultValue;
        $row['fechaemis'] = $this->fechaemis->DefaultValue;
        $row['fechapago'] = $this->fechapago->DefaultValue;
        $row['entrego'] = $this->entrego->DefaultValue;
        $row['recibio'] = $this->recibio->DefaultValue;
        $row['fechaingr'] = $this->fechaingr->DefaultValue;
        $row['fechaentrega'] = $this->fechaentrega->DefaultValue;
        $row['tcomprel'] = $this->tcomprel->DefaultValue;
        $row['serierel'] = $this->serierel->DefaultValue;
        $row['ncomprel'] = $this->ncomprel->DefaultValue;
        $row['estado'] = $this->estado->DefaultValue;
        $row['moneda'] = $this->moneda->DefaultValue;
        $row['fechahora'] = $this->fechahora->DefaultValue;
        $row['usuario'] = $this->usuario->DefaultValue;
        $row['tcompsal'] = $this->tcompsal->DefaultValue;
        $row['seriesal'] = $this->seriesal->DefaultValue;
        $row['ncompsal'] = $this->ncompsal->DefaultValue;
        $row['codrem'] = $this->codrem->DefaultValue;
        $row['cotiz'] = $this->cotiz->DefaultValue;
        $row['usurel'] = $this->usurel->DefaultValue;
        $row['fecharel'] = $this->fecharel->DefaultValue;
        $row['ususal'] = $this->ususal->DefaultValue;
        $row['fechasal'] = $this->fechasal->DefaultValue;
        return $row;
    }

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        if ($this->OldKey != "") {
            $this->setKey($this->OldKey);
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $rs = ExecuteQuery($sql, $conn);
            if ($row = $rs->fetch()) {
                $this->loadRowValues($row); // Load row values
                return $row;
            }
        }
        $this->loadRowValues(); // Load default row values
        return null;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // codnum
        $this->codnum->RowCssClass = "row";

        // tcomp
        $this->tcomp->RowCssClass = "row";

        // serie
        $this->serie->RowCssClass = "row";

        // ncomp
        $this->ncomp->RowCssClass = "row";

        // codban
        $this->codban->RowCssClass = "row";

        // codsuc
        $this->codsuc->RowCssClass = "row";

        // codcta
        $this->codcta->RowCssClass = "row";

        // tipcta
        $this->tipcta->RowCssClass = "row";

        // codchq
        $this->codchq->RowCssClass = "row";

        // codpais
        $this->codpais->RowCssClass = "row";

        // importe
        $this->importe->RowCssClass = "row";

        // fechaemis
        $this->fechaemis->RowCssClass = "row";

        // fechapago
        $this->fechapago->RowCssClass = "row";

        // entrego
        $this->entrego->RowCssClass = "row";

        // recibio
        $this->recibio->RowCssClass = "row";

        // fechaingr
        $this->fechaingr->RowCssClass = "row";

        // fechaentrega
        $this->fechaentrega->RowCssClass = "row";

        // tcomprel
        $this->tcomprel->RowCssClass = "row";

        // serierel
        $this->serierel->RowCssClass = "row";

        // ncomprel
        $this->ncomprel->RowCssClass = "row";

        // estado
        $this->estado->RowCssClass = "row";

        // moneda
        $this->moneda->RowCssClass = "row";

        // fechahora
        $this->fechahora->RowCssClass = "row";

        // usuario
        $this->usuario->RowCssClass = "row";

        // tcompsal
        $this->tcompsal->RowCssClass = "row";

        // seriesal
        $this->seriesal->RowCssClass = "row";

        // ncompsal
        $this->ncompsal->RowCssClass = "row";

        // codrem
        $this->codrem->RowCssClass = "row";

        // cotiz
        $this->cotiz->RowCssClass = "row";

        // usurel
        $this->usurel->RowCssClass = "row";

        // fecharel
        $this->fecharel->RowCssClass = "row";

        // ususal
        $this->ususal->RowCssClass = "row";

        // fechasal
        $this->fechasal->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // codnum
            $this->codnum->ViewValue = $this->codnum->CurrentValue;

            // tcomp
            $this->tcomp->ViewValue = $this->tcomp->CurrentValue;
            $curVal = strval($this->tcomp->CurrentValue);
            if ($curVal != "") {
                $this->tcomp->ViewValue = $this->tcomp->lookupCacheOption($curVal);
                if ($this->tcomp->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->tcomp->Lookup->getTable()->Fields["codnum"]->searchExpression(), "=", $curVal, $this->tcomp->Lookup->getTable()->Fields["codnum"]->searchDataType(), "");
                    $sqlWrk = $this->tcomp->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tcomp->Lookup->renderViewRow($rswrk[0]);
                        $this->tcomp->ViewValue = $this->tcomp->displayValue($arwrk);
                    } else {
                        $this->tcomp->ViewValue = FormatNumber($this->tcomp->CurrentValue, $this->tcomp->formatPattern());
                    }
                }
            } else {
                $this->tcomp->ViewValue = null;
            }

            // serie
            $this->serie->ViewValue = $this->serie->CurrentValue;
            $this->serie->ViewValue = FormatNumber($this->serie->ViewValue, $this->serie->formatPattern());

            // ncomp
            $this->ncomp->ViewValue = $this->ncomp->CurrentValue;
            $this->ncomp->ViewValue = FormatNumber($this->ncomp->ViewValue, $this->ncomp->formatPattern());

            // codban
            $curVal = strval($this->codban->CurrentValue);
            if ($curVal != "") {
                $this->codban->ViewValue = $this->codban->lookupCacheOption($curVal);
                if ($this->codban->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->codban->Lookup->getTable()->Fields["codnum"]->searchExpression(), "=", $curVal, $this->codban->Lookup->getTable()->Fields["codnum"]->searchDataType(), "");
                    $sqlWrk = $this->codban->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->codban->Lookup->renderViewRow($rswrk[0]);
                        $this->codban->ViewValue = $this->codban->displayValue($arwrk);
                    } else {
                        $this->codban->ViewValue = FormatNumber($this->codban->CurrentValue, $this->codban->formatPattern());
                    }
                }
            } else {
                $this->codban->ViewValue = null;
            }

            // codsuc
            $curVal = strval($this->codsuc->CurrentValue);
            if ($curVal != "") {
                $this->codsuc->ViewValue = $this->codsuc->lookupCacheOption($curVal);
                if ($this->codsuc->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->codsuc->Lookup->getTable()->Fields["codnum"]->searchExpression(), "=", $curVal, $this->codsuc->Lookup->getTable()->Fields["codnum"]->searchDataType(), "");
                    $sqlWrk = $this->codsuc->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->codsuc->Lookup->renderViewRow($rswrk[0]);
                        $this->codsuc->ViewValue = $this->codsuc->displayValue($arwrk);
                    } else {
                        $this->codsuc->ViewValue = FormatNumber($this->codsuc->CurrentValue, $this->codsuc->formatPattern());
                    }
                }
            } else {
                $this->codsuc->ViewValue = null;
            }

            // codcta
            $this->codcta->ViewValue = $this->codcta->CurrentValue;

            // tipcta
            $this->tipcta->ViewValue = $this->tipcta->CurrentValue;

            // codchq
            $this->codchq->ViewValue = $this->codchq->CurrentValue;

            // codpais
            $curVal = strval($this->codpais->CurrentValue);
            if ($curVal != "") {
                $this->codpais->ViewValue = $this->codpais->lookupCacheOption($curVal);
                if ($this->codpais->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->codpais->Lookup->getTable()->Fields["codnum"]->searchExpression(), "=", $curVal, $this->codpais->Lookup->getTable()->Fields["codnum"]->searchDataType(), "");
                    $sqlWrk = $this->codpais->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->codpais->Lookup->renderViewRow($rswrk[0]);
                        $this->codpais->ViewValue = $this->codpais->displayValue($arwrk);
                    } else {
                        $this->codpais->ViewValue = FormatNumber($this->codpais->CurrentValue, $this->codpais->formatPattern());
                    }
                }
            } else {
                $this->codpais->ViewValue = null;
            }

            // importe
            $this->importe->ViewValue = $this->importe->CurrentValue;
            $this->importe->ViewValue = FormatNumber($this->importe->ViewValue, $this->importe->formatPattern());

            // fechaemis
            $this->fechaemis->ViewValue = $this->fechaemis->CurrentValue;
            $this->fechaemis->ViewValue = FormatDateTime($this->fechaemis->ViewValue, $this->fechaemis->formatPattern());

            // fechapago
            $this->fechapago->ViewValue = $this->fechapago->CurrentValue;
            $this->fechapago->ViewValue = FormatDateTime($this->fechapago->ViewValue, $this->fechapago->formatPattern());

            // entrego
            $this->entrego->ViewValue = $this->entrego->CurrentValue;
            $this->entrego->ViewValue = FormatNumber($this->entrego->ViewValue, $this->entrego->formatPattern());

            // recibio
            $this->recibio->ViewValue = $this->recibio->CurrentValue;
            $this->recibio->ViewValue = FormatNumber($this->recibio->ViewValue, $this->recibio->formatPattern());

            // fechaingr
            $this->fechaingr->ViewValue = $this->fechaingr->CurrentValue;
            $this->fechaingr->ViewValue = FormatDateTime($this->fechaingr->ViewValue, $this->fechaingr->formatPattern());

            // fechaentrega
            $this->fechaentrega->ViewValue = $this->fechaentrega->CurrentValue;
            $this->fechaentrega->ViewValue = FormatDateTime($this->fechaentrega->ViewValue, $this->fechaentrega->formatPattern());

            // tcomprel
            $this->tcomprel->ViewValue = $this->tcomprel->CurrentValue;
            $this->tcomprel->ViewValue = FormatNumber($this->tcomprel->ViewValue, $this->tcomprel->formatPattern());

            // serierel
            $this->serierel->ViewValue = $this->serierel->CurrentValue;
            $this->serierel->ViewValue = FormatNumber($this->serierel->ViewValue, $this->serierel->formatPattern());

            // ncomprel
            $this->ncomprel->ViewValue = $this->ncomprel->CurrentValue;
            $this->ncomprel->ViewValue = FormatNumber($this->ncomprel->ViewValue, $this->ncomprel->formatPattern());

            // estado
            if (strval($this->estado->CurrentValue) != "") {
                $this->estado->ViewValue = $this->estado->optionCaption($this->estado->CurrentValue);
            } else {
                $this->estado->ViewValue = null;
            }

            // moneda
            $this->moneda->ViewValue = $this->moneda->CurrentValue;
            $this->moneda->ViewValue = FormatNumber($this->moneda->ViewValue, $this->moneda->formatPattern());

            // fechahora
            $this->fechahora->ViewValue = $this->fechahora->CurrentValue;
            $this->fechahora->ViewValue = FormatDateTime($this->fechahora->ViewValue, $this->fechahora->formatPattern());

            // usuario
            $this->usuario->ViewValue = $this->usuario->CurrentValue;
            $this->usuario->ViewValue = FormatNumber($this->usuario->ViewValue, $this->usuario->formatPattern());

            // tcompsal
            $this->tcompsal->ViewValue = $this->tcompsal->CurrentValue;
            $this->tcompsal->ViewValue = FormatNumber($this->tcompsal->ViewValue, $this->tcompsal->formatPattern());

            // seriesal
            $this->seriesal->ViewValue = $this->seriesal->CurrentValue;
            $this->seriesal->ViewValue = FormatNumber($this->seriesal->ViewValue, $this->seriesal->formatPattern());

            // ncompsal
            $this->ncompsal->ViewValue = $this->ncompsal->CurrentValue;
            $this->ncompsal->ViewValue = FormatNumber($this->ncompsal->ViewValue, $this->ncompsal->formatPattern());

            // codrem
            $curVal = strval($this->codrem->CurrentValue);
            if ($curVal != "") {
                $this->codrem->ViewValue = $this->codrem->lookupCacheOption($curVal);
                if ($this->codrem->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->codrem->Lookup->getTable()->Fields["codnum"]->searchExpression(), "=", $curVal, $this->codrem->Lookup->getTable()->Fields["codnum"]->searchDataType(), "");
                    $sqlWrk = $this->codrem->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->codrem->Lookup->renderViewRow($rswrk[0]);
                        $this->codrem->ViewValue = $this->codrem->displayValue($arwrk);
                    } else {
                        $this->codrem->ViewValue = FormatNumber($this->codrem->CurrentValue, $this->codrem->formatPattern());
                    }
                }
            } else {
                $this->codrem->ViewValue = null;
            }

            // cotiz
            $this->cotiz->ViewValue = $this->cotiz->CurrentValue;
            $this->cotiz->ViewValue = FormatNumber($this->cotiz->ViewValue, $this->cotiz->formatPattern());

            // usurel
            $this->usurel->ViewValue = $this->usurel->CurrentValue;
            $this->usurel->ViewValue = FormatNumber($this->usurel->ViewValue, $this->usurel->formatPattern());

            // fecharel
            $this->fecharel->ViewValue = $this->fecharel->CurrentValue;
            $this->fecharel->ViewValue = FormatDateTime($this->fecharel->ViewValue, $this->fecharel->formatPattern());

            // ususal
            $this->ususal->ViewValue = $this->ususal->CurrentValue;
            $this->ususal->ViewValue = FormatNumber($this->ususal->ViewValue, $this->ususal->formatPattern());

            // fechasal
            $this->fechasal->ViewValue = $this->fechasal->CurrentValue;
            $this->fechasal->ViewValue = FormatDateTime($this->fechasal->ViewValue, $this->fechasal->formatPattern());

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
        } elseif ($this->RowType == RowType::EDIT) {
            // codnum
            $this->codnum->setupEditAttributes();
            $this->codnum->EditValue = $this->codnum->CurrentValue;

            // tcomp
            $this->tcomp->setupEditAttributes();
            $this->tcomp->EditValue = $this->tcomp->CurrentValue;
            $curVal = strval($this->tcomp->CurrentValue);
            if ($curVal != "") {
                $this->tcomp->EditValue = $this->tcomp->lookupCacheOption($curVal);
                if ($this->tcomp->EditValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->tcomp->Lookup->getTable()->Fields["codnum"]->searchExpression(), "=", $curVal, $this->tcomp->Lookup->getTable()->Fields["codnum"]->searchDataType(), "");
                    $sqlWrk = $this->tcomp->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tcomp->Lookup->renderViewRow($rswrk[0]);
                        $this->tcomp->EditValue = $this->tcomp->displayValue($arwrk);
                    } else {
                        $this->tcomp->EditValue = HtmlEncode(FormatNumber($this->tcomp->CurrentValue, $this->tcomp->formatPattern()));
                    }
                }
            } else {
                $this->tcomp->EditValue = null;
            }
            $this->tcomp->PlaceHolder = RemoveHtml($this->tcomp->caption());

            // serie
            $this->serie->setupEditAttributes();
            $this->serie->EditValue = $this->serie->CurrentValue;
            $this->serie->PlaceHolder = RemoveHtml($this->serie->caption());
            if (strval($this->serie->EditValue) != "" && is_numeric($this->serie->EditValue)) {
                $this->serie->EditValue = FormatNumber($this->serie->EditValue, null);
            }

            // ncomp
            $this->ncomp->setupEditAttributes();
            $this->ncomp->EditValue = $this->ncomp->CurrentValue;
            $this->ncomp->PlaceHolder = RemoveHtml($this->ncomp->caption());
            if (strval($this->ncomp->EditValue) != "" && is_numeric($this->ncomp->EditValue)) {
                $this->ncomp->EditValue = FormatNumber($this->ncomp->EditValue, null);
            }

            // codban
            $this->codban->setupEditAttributes();
            $curVal = trim(strval($this->codban->CurrentValue));
            if ($curVal != "") {
                $this->codban->ViewValue = $this->codban->lookupCacheOption($curVal);
            } else {
                $this->codban->ViewValue = $this->codban->Lookup !== null && is_array($this->codban->lookupOptions()) && count($this->codban->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->codban->ViewValue !== null) { // Load from cache
                $this->codban->EditValue = array_values($this->codban->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->codban->Lookup->getTable()->Fields["codnum"]->searchExpression(), "=", $this->codban->CurrentValue, $this->codban->Lookup->getTable()->Fields["codnum"]->searchDataType(), "");
                }
                $sqlWrk = $this->codban->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->codban->EditValue = $arwrk;
            }
            $this->codban->PlaceHolder = RemoveHtml($this->codban->caption());

            // codsuc
            $this->codsuc->setupEditAttributes();
            $curVal = trim(strval($this->codsuc->CurrentValue));
            if ($curVal != "") {
                $this->codsuc->ViewValue = $this->codsuc->lookupCacheOption($curVal);
            } else {
                $this->codsuc->ViewValue = $this->codsuc->Lookup !== null && is_array($this->codsuc->lookupOptions()) && count($this->codsuc->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->codsuc->ViewValue !== null) { // Load from cache
                $this->codsuc->EditValue = array_values($this->codsuc->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->codsuc->Lookup->getTable()->Fields["codnum"]->searchExpression(), "=", $this->codsuc->CurrentValue, $this->codsuc->Lookup->getTable()->Fields["codnum"]->searchDataType(), "");
                }
                $sqlWrk = $this->codsuc->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->codsuc->EditValue = $arwrk;
            }
            $this->codsuc->PlaceHolder = RemoveHtml($this->codsuc->caption());

            // codcta
            $this->codcta->setupEditAttributes();
            if (!$this->codcta->Raw) {
                $this->codcta->CurrentValue = HtmlDecode($this->codcta->CurrentValue);
            }
            $this->codcta->EditValue = HtmlEncode($this->codcta->CurrentValue);
            $this->codcta->PlaceHolder = RemoveHtml($this->codcta->caption());

            // tipcta
            $this->tipcta->setupEditAttributes();
            if (!$this->tipcta->Raw) {
                $this->tipcta->CurrentValue = HtmlDecode($this->tipcta->CurrentValue);
            }
            $this->tipcta->EditValue = HtmlEncode($this->tipcta->CurrentValue);
            $this->tipcta->PlaceHolder = RemoveHtml($this->tipcta->caption());

            // codchq
            $this->codchq->setupEditAttributes();
            if (!$this->codchq->Raw) {
                $this->codchq->CurrentValue = HtmlDecode($this->codchq->CurrentValue);
            }
            $this->codchq->EditValue = HtmlEncode($this->codchq->CurrentValue);
            $this->codchq->PlaceHolder = RemoveHtml($this->codchq->caption());

            // codpais
            $this->codpais->setupEditAttributes();
            $curVal = trim(strval($this->codpais->CurrentValue));
            if ($curVal != "") {
                $this->codpais->ViewValue = $this->codpais->lookupCacheOption($curVal);
            } else {
                $this->codpais->ViewValue = $this->codpais->Lookup !== null && is_array($this->codpais->lookupOptions()) && count($this->codpais->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->codpais->ViewValue !== null) { // Load from cache
                $this->codpais->EditValue = array_values($this->codpais->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->codpais->Lookup->getTable()->Fields["codnum"]->searchExpression(), "=", $this->codpais->CurrentValue, $this->codpais->Lookup->getTable()->Fields["codnum"]->searchDataType(), "");
                }
                $sqlWrk = $this->codpais->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->codpais->EditValue = $arwrk;
            }
            $this->codpais->PlaceHolder = RemoveHtml($this->codpais->caption());

            // importe
            $this->importe->setupEditAttributes();
            $this->importe->EditValue = $this->importe->CurrentValue;
            $this->importe->PlaceHolder = RemoveHtml($this->importe->caption());
            if (strval($this->importe->EditValue) != "" && is_numeric($this->importe->EditValue)) {
                $this->importe->EditValue = FormatNumber($this->importe->EditValue, null);
            }

            // fechaemis
            $this->fechaemis->setupEditAttributes();
            $this->fechaemis->EditValue = HtmlEncode(FormatDateTime($this->fechaemis->CurrentValue, $this->fechaemis->formatPattern()));
            $this->fechaemis->PlaceHolder = RemoveHtml($this->fechaemis->caption());

            // fechapago
            $this->fechapago->setupEditAttributes();
            $this->fechapago->EditValue = HtmlEncode(FormatDateTime($this->fechapago->CurrentValue, $this->fechapago->formatPattern()));
            $this->fechapago->PlaceHolder = RemoveHtml($this->fechapago->caption());

            // entrego
            $this->entrego->setupEditAttributes();
            $this->entrego->EditValue = $this->entrego->CurrentValue;
            $this->entrego->PlaceHolder = RemoveHtml($this->entrego->caption());
            if (strval($this->entrego->EditValue) != "" && is_numeric($this->entrego->EditValue)) {
                $this->entrego->EditValue = FormatNumber($this->entrego->EditValue, null);
            }

            // recibio
            $this->recibio->setupEditAttributes();
            $this->recibio->EditValue = $this->recibio->CurrentValue;
            $this->recibio->PlaceHolder = RemoveHtml($this->recibio->caption());
            if (strval($this->recibio->EditValue) != "" && is_numeric($this->recibio->EditValue)) {
                $this->recibio->EditValue = FormatNumber($this->recibio->EditValue, null);
            }

            // fechaingr
            $this->fechaingr->setupEditAttributes();
            $this->fechaingr->EditValue = HtmlEncode(FormatDateTime($this->fechaingr->CurrentValue, $this->fechaingr->formatPattern()));
            $this->fechaingr->PlaceHolder = RemoveHtml($this->fechaingr->caption());

            // fechaentrega
            $this->fechaentrega->setupEditAttributes();
            $this->fechaentrega->EditValue = HtmlEncode(FormatDateTime($this->fechaentrega->CurrentValue, $this->fechaentrega->formatPattern()));
            $this->fechaentrega->PlaceHolder = RemoveHtml($this->fechaentrega->caption());

            // tcomprel
            $this->tcomprel->setupEditAttributes();
            $this->tcomprel->EditValue = $this->tcomprel->CurrentValue;
            $this->tcomprel->PlaceHolder = RemoveHtml($this->tcomprel->caption());
            if (strval($this->tcomprel->EditValue) != "" && is_numeric($this->tcomprel->EditValue)) {
                $this->tcomprel->EditValue = FormatNumber($this->tcomprel->EditValue, null);
            }

            // serierel
            $this->serierel->setupEditAttributes();
            $this->serierel->EditValue = $this->serierel->CurrentValue;
            $this->serierel->PlaceHolder = RemoveHtml($this->serierel->caption());
            if (strval($this->serierel->EditValue) != "" && is_numeric($this->serierel->EditValue)) {
                $this->serierel->EditValue = FormatNumber($this->serierel->EditValue, null);
            }

            // ncomprel
            $this->ncomprel->setupEditAttributes();
            $this->ncomprel->EditValue = $this->ncomprel->CurrentValue;
            $this->ncomprel->PlaceHolder = RemoveHtml($this->ncomprel->caption());
            if (strval($this->ncomprel->EditValue) != "" && is_numeric($this->ncomprel->EditValue)) {
                $this->ncomprel->EditValue = FormatNumber($this->ncomprel->EditValue, null);
            }

            // estado
            $this->estado->setupEditAttributes();
            $this->estado->EditValue = $this->estado->options(true);
            $this->estado->PlaceHolder = RemoveHtml($this->estado->caption());

            // moneda
            $this->moneda->setupEditAttributes();
            $this->moneda->EditValue = $this->moneda->CurrentValue;
            $this->moneda->PlaceHolder = RemoveHtml($this->moneda->caption());
            if (strval($this->moneda->EditValue) != "" && is_numeric($this->moneda->EditValue)) {
                $this->moneda->EditValue = FormatNumber($this->moneda->EditValue, null);
            }

            // fechahora
            $this->fechahora->setupEditAttributes();
            $this->fechahora->EditValue = HtmlEncode(FormatDateTime($this->fechahora->CurrentValue, $this->fechahora->formatPattern()));
            $this->fechahora->PlaceHolder = RemoveHtml($this->fechahora->caption());

            // usuario

            // tcompsal
            $this->tcompsal->setupEditAttributes();
            $this->tcompsal->EditValue = $this->tcompsal->CurrentValue;
            $this->tcompsal->PlaceHolder = RemoveHtml($this->tcompsal->caption());
            if (strval($this->tcompsal->EditValue) != "" && is_numeric($this->tcompsal->EditValue)) {
                $this->tcompsal->EditValue = FormatNumber($this->tcompsal->EditValue, null);
            }

            // seriesal
            $this->seriesal->setupEditAttributes();
            $this->seriesal->EditValue = $this->seriesal->CurrentValue;
            $this->seriesal->PlaceHolder = RemoveHtml($this->seriesal->caption());
            if (strval($this->seriesal->EditValue) != "" && is_numeric($this->seriesal->EditValue)) {
                $this->seriesal->EditValue = FormatNumber($this->seriesal->EditValue, null);
            }

            // ncompsal
            $this->ncompsal->setupEditAttributes();
            $this->ncompsal->EditValue = $this->ncompsal->CurrentValue;
            $this->ncompsal->PlaceHolder = RemoveHtml($this->ncompsal->caption());
            if (strval($this->ncompsal->EditValue) != "" && is_numeric($this->ncompsal->EditValue)) {
                $this->ncompsal->EditValue = FormatNumber($this->ncompsal->EditValue, null);
            }

            // codrem
            $this->codrem->setupEditAttributes();
            $curVal = trim(strval($this->codrem->CurrentValue));
            if ($curVal != "") {
                $this->codrem->ViewValue = $this->codrem->lookupCacheOption($curVal);
            } else {
                $this->codrem->ViewValue = $this->codrem->Lookup !== null && is_array($this->codrem->lookupOptions()) && count($this->codrem->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->codrem->ViewValue !== null) { // Load from cache
                $this->codrem->EditValue = array_values($this->codrem->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->codrem->Lookup->getTable()->Fields["codnum"]->searchExpression(), "=", $this->codrem->CurrentValue, $this->codrem->Lookup->getTable()->Fields["codnum"]->searchDataType(), "");
                }
                $sqlWrk = $this->codrem->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                foreach ($arwrk as &$row) {
                    $row = $this->codrem->Lookup->renderViewRow($row);
                }
                $this->codrem->EditValue = $arwrk;
            }
            $this->codrem->PlaceHolder = RemoveHtml($this->codrem->caption());

            // cotiz
            $this->cotiz->setupEditAttributes();
            $this->cotiz->EditValue = $this->cotiz->CurrentValue;
            $this->cotiz->PlaceHolder = RemoveHtml($this->cotiz->caption());
            if (strval($this->cotiz->EditValue) != "" && is_numeric($this->cotiz->EditValue)) {
                $this->cotiz->EditValue = FormatNumber($this->cotiz->EditValue, null);
            }

            // usurel
            $this->usurel->setupEditAttributes();
            $this->usurel->EditValue = $this->usurel->CurrentValue;
            $this->usurel->PlaceHolder = RemoveHtml($this->usurel->caption());
            if (strval($this->usurel->EditValue) != "" && is_numeric($this->usurel->EditValue)) {
                $this->usurel->EditValue = FormatNumber($this->usurel->EditValue, null);
            }

            // fecharel
            $this->fecharel->setupEditAttributes();
            $this->fecharel->EditValue = HtmlEncode(FormatDateTime($this->fecharel->CurrentValue, $this->fecharel->formatPattern()));
            $this->fecharel->PlaceHolder = RemoveHtml($this->fecharel->caption());

            // ususal
            $this->ususal->setupEditAttributes();
            $this->ususal->EditValue = $this->ususal->CurrentValue;
            $this->ususal->PlaceHolder = RemoveHtml($this->ususal->caption());
            if (strval($this->ususal->EditValue) != "" && is_numeric($this->ususal->EditValue)) {
                $this->ususal->EditValue = FormatNumber($this->ususal->EditValue, null);
            }

            // fechasal
            $this->fechasal->setupEditAttributes();
            $this->fechasal->EditValue = HtmlEncode(FormatDateTime($this->fechasal->CurrentValue, $this->fechasal->formatPattern()));
            $this->fechasal->PlaceHolder = RemoveHtml($this->fechasal->caption());

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
        if ($this->RowType == RowType::ADD || $this->RowType == RowType::EDIT || $this->RowType == RowType::SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != RowType::AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language, $Security;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $validateForm = true;
            if ($this->codnum->Visible && $this->codnum->Required) {
                if (!$this->codnum->IsDetailKey && EmptyValue($this->codnum->FormValue)) {
                    $this->codnum->addErrorMessage(str_replace("%s", $this->codnum->caption(), $this->codnum->RequiredErrorMessage));
                }
            }
            if ($this->tcomp->Visible && $this->tcomp->Required) {
                if (!$this->tcomp->IsDetailKey && EmptyValue($this->tcomp->FormValue)) {
                    $this->tcomp->addErrorMessage(str_replace("%s", $this->tcomp->caption(), $this->tcomp->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->tcomp->FormValue)) {
                $this->tcomp->addErrorMessage($this->tcomp->getErrorMessage(false));
            }
            if ($this->serie->Visible && $this->serie->Required) {
                if (!$this->serie->IsDetailKey && EmptyValue($this->serie->FormValue)) {
                    $this->serie->addErrorMessage(str_replace("%s", $this->serie->caption(), $this->serie->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->serie->FormValue)) {
                $this->serie->addErrorMessage($this->serie->getErrorMessage(false));
            }
            if ($this->ncomp->Visible && $this->ncomp->Required) {
                if (!$this->ncomp->IsDetailKey && EmptyValue($this->ncomp->FormValue)) {
                    $this->ncomp->addErrorMessage(str_replace("%s", $this->ncomp->caption(), $this->ncomp->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->ncomp->FormValue)) {
                $this->ncomp->addErrorMessage($this->ncomp->getErrorMessage(false));
            }
            if ($this->codban->Visible && $this->codban->Required) {
                if (!$this->codban->IsDetailKey && EmptyValue($this->codban->FormValue)) {
                    $this->codban->addErrorMessage(str_replace("%s", $this->codban->caption(), $this->codban->RequiredErrorMessage));
                }
            }
            if ($this->codsuc->Visible && $this->codsuc->Required) {
                if (!$this->codsuc->IsDetailKey && EmptyValue($this->codsuc->FormValue)) {
                    $this->codsuc->addErrorMessage(str_replace("%s", $this->codsuc->caption(), $this->codsuc->RequiredErrorMessage));
                }
            }
            if ($this->codcta->Visible && $this->codcta->Required) {
                if (!$this->codcta->IsDetailKey && EmptyValue($this->codcta->FormValue)) {
                    $this->codcta->addErrorMessage(str_replace("%s", $this->codcta->caption(), $this->codcta->RequiredErrorMessage));
                }
            }
            if ($this->tipcta->Visible && $this->tipcta->Required) {
                if (!$this->tipcta->IsDetailKey && EmptyValue($this->tipcta->FormValue)) {
                    $this->tipcta->addErrorMessage(str_replace("%s", $this->tipcta->caption(), $this->tipcta->RequiredErrorMessage));
                }
            }
            if ($this->codchq->Visible && $this->codchq->Required) {
                if (!$this->codchq->IsDetailKey && EmptyValue($this->codchq->FormValue)) {
                    $this->codchq->addErrorMessage(str_replace("%s", $this->codchq->caption(), $this->codchq->RequiredErrorMessage));
                }
            }
            if ($this->codpais->Visible && $this->codpais->Required) {
                if (!$this->codpais->IsDetailKey && EmptyValue($this->codpais->FormValue)) {
                    $this->codpais->addErrorMessage(str_replace("%s", $this->codpais->caption(), $this->codpais->RequiredErrorMessage));
                }
            }
            if ($this->importe->Visible && $this->importe->Required) {
                if (!$this->importe->IsDetailKey && EmptyValue($this->importe->FormValue)) {
                    $this->importe->addErrorMessage(str_replace("%s", $this->importe->caption(), $this->importe->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->importe->FormValue)) {
                $this->importe->addErrorMessage($this->importe->getErrorMessage(false));
            }
            if ($this->fechaemis->Visible && $this->fechaemis->Required) {
                if (!$this->fechaemis->IsDetailKey && EmptyValue($this->fechaemis->FormValue)) {
                    $this->fechaemis->addErrorMessage(str_replace("%s", $this->fechaemis->caption(), $this->fechaemis->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->fechaemis->FormValue, $this->fechaemis->formatPattern())) {
                $this->fechaemis->addErrorMessage($this->fechaemis->getErrorMessage(false));
            }
            if ($this->fechapago->Visible && $this->fechapago->Required) {
                if (!$this->fechapago->IsDetailKey && EmptyValue($this->fechapago->FormValue)) {
                    $this->fechapago->addErrorMessage(str_replace("%s", $this->fechapago->caption(), $this->fechapago->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->fechapago->FormValue, $this->fechapago->formatPattern())) {
                $this->fechapago->addErrorMessage($this->fechapago->getErrorMessage(false));
            }
            if ($this->entrego->Visible && $this->entrego->Required) {
                if (!$this->entrego->IsDetailKey && EmptyValue($this->entrego->FormValue)) {
                    $this->entrego->addErrorMessage(str_replace("%s", $this->entrego->caption(), $this->entrego->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->entrego->FormValue)) {
                $this->entrego->addErrorMessage($this->entrego->getErrorMessage(false));
            }
            if ($this->recibio->Visible && $this->recibio->Required) {
                if (!$this->recibio->IsDetailKey && EmptyValue($this->recibio->FormValue)) {
                    $this->recibio->addErrorMessage(str_replace("%s", $this->recibio->caption(), $this->recibio->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->recibio->FormValue)) {
                $this->recibio->addErrorMessage($this->recibio->getErrorMessage(false));
            }
            if ($this->fechaingr->Visible && $this->fechaingr->Required) {
                if (!$this->fechaingr->IsDetailKey && EmptyValue($this->fechaingr->FormValue)) {
                    $this->fechaingr->addErrorMessage(str_replace("%s", $this->fechaingr->caption(), $this->fechaingr->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->fechaingr->FormValue, $this->fechaingr->formatPattern())) {
                $this->fechaingr->addErrorMessage($this->fechaingr->getErrorMessage(false));
            }
            if ($this->fechaentrega->Visible && $this->fechaentrega->Required) {
                if (!$this->fechaentrega->IsDetailKey && EmptyValue($this->fechaentrega->FormValue)) {
                    $this->fechaentrega->addErrorMessage(str_replace("%s", $this->fechaentrega->caption(), $this->fechaentrega->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->fechaentrega->FormValue, $this->fechaentrega->formatPattern())) {
                $this->fechaentrega->addErrorMessage($this->fechaentrega->getErrorMessage(false));
            }
            if ($this->tcomprel->Visible && $this->tcomprel->Required) {
                if (!$this->tcomprel->IsDetailKey && EmptyValue($this->tcomprel->FormValue)) {
                    $this->tcomprel->addErrorMessage(str_replace("%s", $this->tcomprel->caption(), $this->tcomprel->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->tcomprel->FormValue)) {
                $this->tcomprel->addErrorMessage($this->tcomprel->getErrorMessage(false));
            }
            if ($this->serierel->Visible && $this->serierel->Required) {
                if (!$this->serierel->IsDetailKey && EmptyValue($this->serierel->FormValue)) {
                    $this->serierel->addErrorMessage(str_replace("%s", $this->serierel->caption(), $this->serierel->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->serierel->FormValue)) {
                $this->serierel->addErrorMessage($this->serierel->getErrorMessage(false));
            }
            if ($this->ncomprel->Visible && $this->ncomprel->Required) {
                if (!$this->ncomprel->IsDetailKey && EmptyValue($this->ncomprel->FormValue)) {
                    $this->ncomprel->addErrorMessage(str_replace("%s", $this->ncomprel->caption(), $this->ncomprel->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->ncomprel->FormValue)) {
                $this->ncomprel->addErrorMessage($this->ncomprel->getErrorMessage(false));
            }
            if ($this->estado->Visible && $this->estado->Required) {
                if (!$this->estado->IsDetailKey && EmptyValue($this->estado->FormValue)) {
                    $this->estado->addErrorMessage(str_replace("%s", $this->estado->caption(), $this->estado->RequiredErrorMessage));
                }
            }
            if ($this->moneda->Visible && $this->moneda->Required) {
                if (!$this->moneda->IsDetailKey && EmptyValue($this->moneda->FormValue)) {
                    $this->moneda->addErrorMessage(str_replace("%s", $this->moneda->caption(), $this->moneda->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->moneda->FormValue)) {
                $this->moneda->addErrorMessage($this->moneda->getErrorMessage(false));
            }
            if ($this->fechahora->Visible && $this->fechahora->Required) {
                if (!$this->fechahora->IsDetailKey && EmptyValue($this->fechahora->FormValue)) {
                    $this->fechahora->addErrorMessage(str_replace("%s", $this->fechahora->caption(), $this->fechahora->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->fechahora->FormValue, $this->fechahora->formatPattern())) {
                $this->fechahora->addErrorMessage($this->fechahora->getErrorMessage(false));
            }
            if ($this->usuario->Visible && $this->usuario->Required) {
                if (!$this->usuario->IsDetailKey && EmptyValue($this->usuario->FormValue)) {
                    $this->usuario->addErrorMessage(str_replace("%s", $this->usuario->caption(), $this->usuario->RequiredErrorMessage));
                }
            }
            if ($this->tcompsal->Visible && $this->tcompsal->Required) {
                if (!$this->tcompsal->IsDetailKey && EmptyValue($this->tcompsal->FormValue)) {
                    $this->tcompsal->addErrorMessage(str_replace("%s", $this->tcompsal->caption(), $this->tcompsal->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->tcompsal->FormValue)) {
                $this->tcompsal->addErrorMessage($this->tcompsal->getErrorMessage(false));
            }
            if ($this->seriesal->Visible && $this->seriesal->Required) {
                if (!$this->seriesal->IsDetailKey && EmptyValue($this->seriesal->FormValue)) {
                    $this->seriesal->addErrorMessage(str_replace("%s", $this->seriesal->caption(), $this->seriesal->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->seriesal->FormValue)) {
                $this->seriesal->addErrorMessage($this->seriesal->getErrorMessage(false));
            }
            if ($this->ncompsal->Visible && $this->ncompsal->Required) {
                if (!$this->ncompsal->IsDetailKey && EmptyValue($this->ncompsal->FormValue)) {
                    $this->ncompsal->addErrorMessage(str_replace("%s", $this->ncompsal->caption(), $this->ncompsal->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->ncompsal->FormValue)) {
                $this->ncompsal->addErrorMessage($this->ncompsal->getErrorMessage(false));
            }
            if ($this->codrem->Visible && $this->codrem->Required) {
                if (!$this->codrem->IsDetailKey && EmptyValue($this->codrem->FormValue)) {
                    $this->codrem->addErrorMessage(str_replace("%s", $this->codrem->caption(), $this->codrem->RequiredErrorMessage));
                }
            }
            if ($this->cotiz->Visible && $this->cotiz->Required) {
                if (!$this->cotiz->IsDetailKey && EmptyValue($this->cotiz->FormValue)) {
                    $this->cotiz->addErrorMessage(str_replace("%s", $this->cotiz->caption(), $this->cotiz->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->cotiz->FormValue)) {
                $this->cotiz->addErrorMessage($this->cotiz->getErrorMessage(false));
            }
            if ($this->usurel->Visible && $this->usurel->Required) {
                if (!$this->usurel->IsDetailKey && EmptyValue($this->usurel->FormValue)) {
                    $this->usurel->addErrorMessage(str_replace("%s", $this->usurel->caption(), $this->usurel->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->usurel->FormValue)) {
                $this->usurel->addErrorMessage($this->usurel->getErrorMessage(false));
            }
            if ($this->fecharel->Visible && $this->fecharel->Required) {
                if (!$this->fecharel->IsDetailKey && EmptyValue($this->fecharel->FormValue)) {
                    $this->fecharel->addErrorMessage(str_replace("%s", $this->fecharel->caption(), $this->fecharel->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->fecharel->FormValue, $this->fecharel->formatPattern())) {
                $this->fecharel->addErrorMessage($this->fecharel->getErrorMessage(false));
            }
            if ($this->ususal->Visible && $this->ususal->Required) {
                if (!$this->ususal->IsDetailKey && EmptyValue($this->ususal->FormValue)) {
                    $this->ususal->addErrorMessage(str_replace("%s", $this->ususal->caption(), $this->ususal->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->ususal->FormValue)) {
                $this->ususal->addErrorMessage($this->ususal->getErrorMessage(false));
            }
            if ($this->fechasal->Visible && $this->fechasal->Required) {
                if (!$this->fechasal->IsDetailKey && EmptyValue($this->fechasal->FormValue)) {
                    $this->fechasal->addErrorMessage(str_replace("%s", $this->fechasal->caption(), $this->fechasal->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->fechasal->FormValue, $this->fechasal->formatPattern())) {
                $this->fechasal->addErrorMessage($this->fechasal->getErrorMessage(false));
            }

        // Return validate result
        $validateForm = $validateForm && !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();

        // Load old row
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssociative($sql);
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            return false; // Update Failed
        } else {
            // Load old values
            $this->loadDbValues($rsold);
        }

        // Get new row
        $rsnew = $this->getEditRow($rsold);

        // Update current values
        $this->setCurrentValues($rsnew);

        // Call Row Updating event
        $updateRow = $this->rowUpdating($rsold, $rsnew);
        if ($updateRow) {
            if (count($rsnew) > 0) {
                $this->CurrentFilter = $filter; // Set up current filter
                $editRow = $this->update($rsnew, "", $rsold);
                if (!$editRow && !EmptyValue($this->DbErrorMessage)) { // Show database error
                    $this->setFailureMessage($this->DbErrorMessage);
                }
            } else {
                $editRow = true; // No field to update
            }
            if ($editRow) {
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("UpdateCancelled"));
            }
            $editRow = false;
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }

        // Write JSON response
        if (IsJsonResponse() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            $table = $this->TableVar;
            WriteJson(["success" => true, "action" => Config("API_EDIT_ACTION"), $table => $row]);
        }
        return $editRow;
    }

    /**
     * Get edit row
     *
     * @return array
     */
    protected function getEditRow($rsold)
    {
        global $Security;
        $rsnew = [];

        // tcomp
        $this->tcomp->setDbValueDef($rsnew, $this->tcomp->CurrentValue, $this->tcomp->ReadOnly);

        // serie
        $this->serie->setDbValueDef($rsnew, $this->serie->CurrentValue, $this->serie->ReadOnly);

        // ncomp
        $this->ncomp->setDbValueDef($rsnew, $this->ncomp->CurrentValue, $this->ncomp->ReadOnly);

        // codban
        $this->codban->setDbValueDef($rsnew, $this->codban->CurrentValue, $this->codban->ReadOnly);

        // codsuc
        $this->codsuc->setDbValueDef($rsnew, $this->codsuc->CurrentValue, $this->codsuc->ReadOnly);

        // codcta
        $this->codcta->setDbValueDef($rsnew, $this->codcta->CurrentValue, $this->codcta->ReadOnly);

        // tipcta
        $this->tipcta->setDbValueDef($rsnew, $this->tipcta->CurrentValue, $this->tipcta->ReadOnly);

        // codchq
        $this->codchq->setDbValueDef($rsnew, $this->codchq->CurrentValue, $this->codchq->ReadOnly);

        // codpais
        $this->codpais->setDbValueDef($rsnew, $this->codpais->CurrentValue, $this->codpais->ReadOnly);

        // importe
        $this->importe->setDbValueDef($rsnew, $this->importe->CurrentValue, $this->importe->ReadOnly);

        // fechaemis
        $this->fechaemis->setDbValueDef($rsnew, UnFormatDateTime($this->fechaemis->CurrentValue, $this->fechaemis->formatPattern()), $this->fechaemis->ReadOnly);

        // fechapago
        $this->fechapago->setDbValueDef($rsnew, UnFormatDateTime($this->fechapago->CurrentValue, $this->fechapago->formatPattern()), $this->fechapago->ReadOnly);

        // entrego
        $this->entrego->setDbValueDef($rsnew, $this->entrego->CurrentValue, $this->entrego->ReadOnly);

        // recibio
        $this->recibio->setDbValueDef($rsnew, $this->recibio->CurrentValue, $this->recibio->ReadOnly);

        // fechaingr
        $this->fechaingr->setDbValueDef($rsnew, UnFormatDateTime($this->fechaingr->CurrentValue, $this->fechaingr->formatPattern()), $this->fechaingr->ReadOnly);

        // fechaentrega
        $this->fechaentrega->setDbValueDef($rsnew, UnFormatDateTime($this->fechaentrega->CurrentValue, $this->fechaentrega->formatPattern()), $this->fechaentrega->ReadOnly);

        // tcomprel
        $this->tcomprel->setDbValueDef($rsnew, $this->tcomprel->CurrentValue, $this->tcomprel->ReadOnly);

        // serierel
        $this->serierel->setDbValueDef($rsnew, $this->serierel->CurrentValue, $this->serierel->ReadOnly);

        // ncomprel
        $this->ncomprel->setDbValueDef($rsnew, $this->ncomprel->CurrentValue, $this->ncomprel->ReadOnly);

        // estado
        $this->estado->setDbValueDef($rsnew, $this->estado->CurrentValue, $this->estado->ReadOnly);

        // moneda
        $this->moneda->setDbValueDef($rsnew, $this->moneda->CurrentValue, $this->moneda->ReadOnly);

        // fechahora
        $this->fechahora->setDbValueDef($rsnew, UnFormatDateTime($this->fechahora->CurrentValue, $this->fechahora->formatPattern()), $this->fechahora->ReadOnly);

        // usuario
        $this->usuario->CurrentValue = $this->usuario->getAutoUpdateValue(); // PHP
        $this->usuario->setDbValueDef($rsnew, $this->usuario->CurrentValue, $this->usuario->ReadOnly);

        // tcompsal
        $this->tcompsal->setDbValueDef($rsnew, $this->tcompsal->CurrentValue, $this->tcompsal->ReadOnly);

        // seriesal
        $this->seriesal->setDbValueDef($rsnew, $this->seriesal->CurrentValue, $this->seriesal->ReadOnly);

        // ncompsal
        $this->ncompsal->setDbValueDef($rsnew, $this->ncompsal->CurrentValue, $this->ncompsal->ReadOnly);

        // codrem
        $this->codrem->setDbValueDef($rsnew, $this->codrem->CurrentValue, $this->codrem->ReadOnly);

        // cotiz
        $this->cotiz->setDbValueDef($rsnew, $this->cotiz->CurrentValue, $this->cotiz->ReadOnly);

        // usurel
        $this->usurel->setDbValueDef($rsnew, $this->usurel->CurrentValue, $this->usurel->ReadOnly);

        // fecharel
        $this->fecharel->setDbValueDef($rsnew, UnFormatDateTime($this->fecharel->CurrentValue, $this->fecharel->formatPattern()), $this->fecharel->ReadOnly);

        // ususal
        $this->ususal->setDbValueDef($rsnew, $this->ususal->CurrentValue, $this->ususal->ReadOnly);

        // fechasal
        $this->fechasal->setDbValueDef($rsnew, UnFormatDateTime($this->fechasal->CurrentValue, $this->fechasal->formatPattern()), $this->fechasal->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['tcomp'])) { // tcomp
            $this->tcomp->CurrentValue = $row['tcomp'];
        }
        if (isset($row['serie'])) { // serie
            $this->serie->CurrentValue = $row['serie'];
        }
        if (isset($row['ncomp'])) { // ncomp
            $this->ncomp->CurrentValue = $row['ncomp'];
        }
        if (isset($row['codban'])) { // codban
            $this->codban->CurrentValue = $row['codban'];
        }
        if (isset($row['codsuc'])) { // codsuc
            $this->codsuc->CurrentValue = $row['codsuc'];
        }
        if (isset($row['codcta'])) { // codcta
            $this->codcta->CurrentValue = $row['codcta'];
        }
        if (isset($row['tipcta'])) { // tipcta
            $this->tipcta->CurrentValue = $row['tipcta'];
        }
        if (isset($row['codchq'])) { // codchq
            $this->codchq->CurrentValue = $row['codchq'];
        }
        if (isset($row['codpais'])) { // codpais
            $this->codpais->CurrentValue = $row['codpais'];
        }
        if (isset($row['importe'])) { // importe
            $this->importe->CurrentValue = $row['importe'];
        }
        if (isset($row['fechaemis'])) { // fechaemis
            $this->fechaemis->CurrentValue = $row['fechaemis'];
        }
        if (isset($row['fechapago'])) { // fechapago
            $this->fechapago->CurrentValue = $row['fechapago'];
        }
        if (isset($row['entrego'])) { // entrego
            $this->entrego->CurrentValue = $row['entrego'];
        }
        if (isset($row['recibio'])) { // recibio
            $this->recibio->CurrentValue = $row['recibio'];
        }
        if (isset($row['fechaingr'])) { // fechaingr
            $this->fechaingr->CurrentValue = $row['fechaingr'];
        }
        if (isset($row['fechaentrega'])) { // fechaentrega
            $this->fechaentrega->CurrentValue = $row['fechaentrega'];
        }
        if (isset($row['tcomprel'])) { // tcomprel
            $this->tcomprel->CurrentValue = $row['tcomprel'];
        }
        if (isset($row['serierel'])) { // serierel
            $this->serierel->CurrentValue = $row['serierel'];
        }
        if (isset($row['ncomprel'])) { // ncomprel
            $this->ncomprel->CurrentValue = $row['ncomprel'];
        }
        if (isset($row['estado'])) { // estado
            $this->estado->CurrentValue = $row['estado'];
        }
        if (isset($row['moneda'])) { // moneda
            $this->moneda->CurrentValue = $row['moneda'];
        }
        if (isset($row['fechahora'])) { // fechahora
            $this->fechahora->CurrentValue = $row['fechahora'];
        }
        if (isset($row['usuario'])) { // usuario
            $this->usuario->CurrentValue = $row['usuario'];
        }
        if (isset($row['tcompsal'])) { // tcompsal
            $this->tcompsal->CurrentValue = $row['tcompsal'];
        }
        if (isset($row['seriesal'])) { // seriesal
            $this->seriesal->CurrentValue = $row['seriesal'];
        }
        if (isset($row['ncompsal'])) { // ncompsal
            $this->ncompsal->CurrentValue = $row['ncompsal'];
        }
        if (isset($row['codrem'])) { // codrem
            $this->codrem->CurrentValue = $row['codrem'];
        }
        if (isset($row['cotiz'])) { // cotiz
            $this->cotiz->CurrentValue = $row['cotiz'];
        }
        if (isset($row['usurel'])) { // usurel
            $this->usurel->CurrentValue = $row['usurel'];
        }
        if (isset($row['fecharel'])) { // fecharel
            $this->fecharel->CurrentValue = $row['fecharel'];
        }
        if (isset($row['ususal'])) { // ususal
            $this->ususal->CurrentValue = $row['ususal'];
        }
        if (isset($row['fechasal'])) { // fechasal
            $this->fechasal->CurrentValue = $row['fechasal'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("CartvaloresList"), "", $this->TableVar, true);
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x_tcomp":
                    break;
                case "x_codban":
                    break;
                case "x_codsuc":
                    break;
                case "x_codpais":
                    break;
                case "x_estado":
                    break;
                case "x_codrem":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if (!$fld->hasLookupOptions() && $fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0 && count($fld->Lookup->FilterFields) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll();
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row, Container($fld->Lookup->LinkTable));
                    $key = $row["lf"];
                    if (IsFloatType($fld->Type)) { // Handle float field
                        $key = (float)$key;
                    }
                    $ar[strval($key)] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        $pageNo = Get(Config("TABLE_PAGE_NUMBER"));
        $startRec = Get(Config("TABLE_START_REC"));
        $infiniteScroll = false;
        $recordNo = $pageNo ?? $startRec; // Record number = page number or start record
        if ($recordNo !== null && is_numeric($recordNo)) {
            $this->StartRecord = $recordNo;
        } else {
            $this->StartRecord = $this->getStartRecordNumber();
        }

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || intval($this->StartRecord) <= 0) { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
        }
        if (!$infiniteScroll) {
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Get page count
    public function pageCount() {
        return ceil($this->TotalRecords / $this->DisplayRecords);
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
        if ($type == "success") {
            //$msg = "your success message";
        } elseif ($type == "failure") {
            //$msg = "your failure message";
        } elseif ($type == "warning") {
            //$msg = "your warning message";
        } else {
            //$msg = "your message";
        }
    }

    // Page Render event
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }

    // Page Breaking event
    public function pageBreaking(&$break, &$content)
    {
        // Example:
        //$break = false; // Skip page break, or
        //$content = "<div style=\"break-after:page;\"></div>"; // Modify page break content
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }
}
