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
 * Table class for concafactven
 */
class Concafactven extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $DbErrorMessage = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Ajax / Modal
    public $UseAjaxActions = false;
    public $ModalSearch = false;
    public $ModalView = false;
    public $ModalAdd = false;
    public $ModalEdit = false;
    public $ModalUpdate = false;
    public $InlineDelete = false;
    public $ModalGridAdd = false;
    public $ModalGridEdit = false;
    public $ModalMultiEdit = false;

    // Fields
    public $codnum;
    public $nroconc;
    public $descrip;
    public $porcentaje;
    public $importe;
    public $usuario;
    public $fechahora;
    public $activo;
    public $tipoiva;
    public $impuesto;
    public $tieneresol;
    public $ctacbleBAS;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "concafactven";
        $this->TableName = 'concafactven';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "concafactven";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)

        // PDF
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)

        // PhpSpreadsheet
        $this->ExportExcelPageOrientation = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_DEFAULT; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4; // Page size (PhpSpreadsheet only)

        // PHPWord
        $this->ExportWordPageOrientation = ""; // Page orientation (PHPWord only)
        $this->ExportWordPageSize = ""; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = false; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = false; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UseAjaxActions = $this->UseAjaxActions || Config("USE_AJAX_ACTIONS");
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this);

        // codnum
        $this->codnum = new DbField(
            $this, // Table
            'x_codnum', // Variable name
            'codnum', // Name
            '`codnum`', // Expression
            '`codnum`', // Basic search expression
            3, // Type
            9, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`codnum`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'NO' // Edit Tag
        );
        $this->codnum->InputTextType = "text";
        $this->codnum->Raw = true;
        $this->codnum->IsAutoIncrement = true; // Autoincrement field
        $this->codnum->IsPrimaryKey = true; // Primary key field
        $this->codnum->Nullable = false; // NOT NULL field
        $this->codnum->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->codnum->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['codnum'] = &$this->codnum;

        // nroconc
        $this->nroconc = new DbField(
            $this, // Table
            'x_nroconc', // Variable name
            'nroconc', // Name
            '`nroconc`', // Expression
            '`nroconc`', // Basic search expression
            3, // Type
            10, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`nroconc`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->nroconc->InputTextType = "text";
        $this->nroconc->Raw = true;
        $this->nroconc->Nullable = false; // NOT NULL field
        $this->nroconc->Required = true; // Required field
        $this->nroconc->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->nroconc->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['nroconc'] = &$this->nroconc;

        // descrip
        $this->descrip = new DbField(
            $this, // Table
            'x_descrip', // Variable name
            'descrip', // Name
            '`descrip`', // Expression
            '`descrip`', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`descrip`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->descrip->InputTextType = "text";
        $this->descrip->Nullable = false; // NOT NULL field
        $this->descrip->Required = true; // Required field
        $this->descrip->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['descrip'] = &$this->descrip;

        // porcentaje
        $this->porcentaje = new DbField(
            $this, // Table
            'x_porcentaje', // Variable name
            'porcentaje', // Name
            '`porcentaje`', // Expression
            '`porcentaje`', // Basic search expression
            4, // Type
            12, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`porcentaje`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->porcentaje->InputTextType = "text";
        $this->porcentaje->Raw = true;
        $this->porcentaje->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->porcentaje->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['porcentaje'] = &$this->porcentaje;

        // importe
        $this->importe = new DbField(
            $this, // Table
            'x_importe', // Variable name
            'importe', // Name
            '`importe`', // Expression
            '`importe`', // Basic search expression
            4, // Type
            12, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`importe`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->importe->InputTextType = "text";
        $this->importe->Raw = true;
        $this->importe->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->importe->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['importe'] = &$this->importe;

        // usuario
        $this->usuario = new DbField(
            $this, // Table
            'x_usuario', // Variable name
            'usuario', // Name
            '`usuario`', // Expression
            '`usuario`', // Basic search expression
            3, // Type
            3, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`usuario`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->usuario->addMethod("getAutoUpdateValue", fn() => CurrentUserID());
        $this->usuario->InputTextType = "text";
        $this->usuario->Raw = true;
        $this->usuario->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->usuario->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['usuario'] = &$this->usuario;

        // fechahora
        $this->fechahora = new DbField(
            $this, // Table
            'x_fechahora', // Variable name
            'fechahora', // Name
            '`fechahora`', // Expression
            CastDateFieldForLike("`fechahora`", 7, "DB"), // Basic search expression
            135, // Type
            19, // Size
            7, // Date/Time format
            false, // Is upload field
            '`fechahora`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fechahora->InputTextType = "text";
        $this->fechahora->Raw = true;
        $this->fechahora->Nullable = false; // NOT NULL field
        $this->fechahora->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->fechahora->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['fechahora'] = &$this->fechahora;

        // activo
        $this->activo = new DbField(
            $this, // Table
            'x_activo', // Variable name
            'activo', // Name
            '`activo`', // Expression
            '`activo`', // Basic search expression
            16, // Type
            1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`activo`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->activo->addMethod("getDefault", fn() => 1);
        $this->activo->InputTextType = "text";
        $this->activo->Raw = true;
        $this->activo->Nullable = false; // NOT NULL field
        $this->activo->Required = true; // Required field
        $this->activo->setSelectMultiple(false); // Select one
        $this->activo->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->activo->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        global $CurrentLanguage;
        switch ($CurrentLanguage) {
            case "en-US":
                $this->activo->Lookup = new Lookup($this->activo, 'concafactven', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
                break;
            default:
                $this->activo->Lookup = new Lookup($this->activo, 'concafactven', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
                break;
        }
        $this->activo->OptionCount = 2;
        $this->activo->DefaultErrorMessage = $Language->phrase("IncorrectField");
        $this->activo->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['activo'] = &$this->activo;

        // tipoiva
        $this->tipoiva = new DbField(
            $this, // Table
            'x_tipoiva', // Variable name
            'tipoiva', // Name
            '`tipoiva`', // Expression
            '`tipoiva`', // Basic search expression
            3, // Type
            3, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`tipoiva`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->tipoiva->InputTextType = "text";
        $this->tipoiva->Raw = true;
        global $CurrentLanguage;
        switch ($CurrentLanguage) {
            case "en-US":
                $this->tipoiva->Lookup = new Lookup($this->tipoiva, 'tipoiva', false, 'codnum', ["descrip","","",""], '', '', [], [], [], [], [], [], false, '', '', "`descrip`");
                break;
            default:
                $this->tipoiva->Lookup = new Lookup($this->tipoiva, 'tipoiva', false, 'codnum', ["descrip","","",""], '', '', [], [], [], [], [], [], false, '', '', "`descrip`");
                break;
        }
        $this->tipoiva->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->tipoiva->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['tipoiva'] = &$this->tipoiva;

        // impuesto
        $this->impuesto = new DbField(
            $this, // Table
            'x_impuesto', // Variable name
            'impuesto', // Name
            '`impuesto`', // Expression
            '`impuesto`', // Basic search expression
            3, // Type
            3, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`impuesto`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->impuesto->InputTextType = "text";
        $this->impuesto->Raw = true;
        global $CurrentLanguage;
        switch ($CurrentLanguage) {
            case "en-US":
                $this->impuesto->Lookup = new Lookup($this->impuesto, 'impuestos', false, 'codnum', ["porcen","","",""], '', '', [], [], [], [], [], [], false, '', '', "`porcen`");
                break;
            default:
                $this->impuesto->Lookup = new Lookup($this->impuesto, 'impuestos', false, 'codnum', ["porcen","","",""], '', '', [], [], [], [], [], [], false, '', '', "`porcen`");
                break;
        }
        $this->impuesto->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->impuesto->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['impuesto'] = &$this->impuesto;

        // tieneresol
        $this->tieneresol = new DbField(
            $this, // Table
            'x_tieneresol', // Variable name
            'tieneresol', // Name
            '`tieneresol`', // Expression
            '`tieneresol`', // Basic search expression
            16, // Type
            1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`tieneresol`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->tieneresol->addMethod("getDefault", fn() => 0);
        $this->tieneresol->InputTextType = "text";
        $this->tieneresol->Raw = true;
        $this->tieneresol->Nullable = false; // NOT NULL field
        $this->tieneresol->Required = true; // Required field
        $this->tieneresol->setSelectMultiple(false); // Select one
        $this->tieneresol->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tieneresol->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        global $CurrentLanguage;
        switch ($CurrentLanguage) {
            case "en-US":
                $this->tieneresol->Lookup = new Lookup($this->tieneresol, 'concafactven', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
                break;
            default:
                $this->tieneresol->Lookup = new Lookup($this->tieneresol, 'concafactven', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
                break;
        }
        $this->tieneresol->OptionCount = 2;
        $this->tieneresol->DefaultErrorMessage = $Language->phrase("IncorrectField");
        $this->tieneresol->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['tieneresol'] = &$this->tieneresol;

        // ctacbleBAS
        $this->ctacbleBAS = new DbField(
            $this, // Table
            'x_ctacbleBAS', // Variable name
            'ctacbleBAS', // Name
            '`ctacbleBAS`', // Expression
            '`ctacbleBAS`', // Basic search expression
            200, // Type
            12, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ctacbleBAS`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ctacbleBAS->InputTextType = "text";
        $this->ctacbleBAS->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['ctacbleBAS'] = &$this->ctacbleBAS;

        // Add Doctrine Cache
        $this->Cache = new \Symfony\Component\Cache\Adapter\ArrayAdapter();
        $this->CacheProfile = new \Doctrine\DBAL\Cache\QueryCacheProfile(0, $this->TableVar);

        // Call Table Load event
        $this->tableLoad();
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Single column sort
    public function updateSort(&$fld)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            $this->setSessionOrderBy($orderBy); // Save to Session
        }
    }

    // Update field sort
    public function updateFieldSort()
    {
        $orderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
        $flds = GetSortFields($orderBy);
        foreach ($this->Fields as $field) {
            $fldSort = "";
            foreach ($flds as $fld) {
                if ($fld[0] == $field->Expression || $fld[0] == $field->VirtualExpression) {
                    $fldSort = $fld[1];
                }
            }
            $field->setSort($fldSort);
        }
    }

    // Render X Axis for chart
    public function renderChartXAxis($chartVar, $chartRow)
    {
        return $chartRow;
    }

    // Get FROM clause
    public function getSqlFrom()
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "concafactven";
    }

    // Get FROM clause (for backward compatibility)
    public function sqlFrom()
    {
        return $this->getSqlFrom();
    }

    // Set FROM clause
    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    // Get SELECT clause
    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select($this->sqlSelectFields());
    }

    // Get list of fields
    private function sqlSelectFields()
    {
        $useFieldNames = false;
        $fieldNames = [];
        $platform = $this->getConnection()->getDatabasePlatform();
        foreach ($this->Fields as $field) {
            $expr = $field->Expression;
            $customExpr = $field->CustomDataType?->convertToPHPValueSQL($expr, $platform) ?? $expr;
            if ($customExpr != $expr) {
                $fieldNames[] = $customExpr . " AS " . QuotedName($field->Name, $this->Dbid);
                $useFieldNames = true;
            } else {
                $fieldNames[] = $expr;
            }
        }
        return $useFieldNames ? implode(", ", $fieldNames) : "*";
    }

    // Get SELECT clause (for backward compatibility)
    public function sqlSelect()
    {
        return $this->getSqlSelect();
    }

    // Set SELECT clause
    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    // Get WHERE clause
    public function getSqlWhere()
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    // Get WHERE clause (for backward compatibility)
    public function sqlWhere()
    {
        return $this->getSqlWhere();
    }

    // Set WHERE clause
    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    // Get GROUP BY clause
    public function getSqlGroupBy()
    {
        return $this->SqlGroupBy != "" ? $this->SqlGroupBy : "";
    }

    // Get GROUP BY clause (for backward compatibility)
    public function sqlGroupBy()
    {
        return $this->getSqlGroupBy();
    }

    // set GROUP BY clause
    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    // Get HAVING clause
    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    // Get HAVING clause (for backward compatibility)
    public function sqlHaving()
    {
        return $this->getSqlHaving();
    }

    // Set HAVING clause
    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    // Get ORDER BY clause
    public function getSqlOrderBy()
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : "";
    }

    // Get ORDER BY clause (for backward compatibility)
    public function sqlOrderBy()
    {
        return $this->getSqlOrderBy();
    }

    // set ORDER BY clause
    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter, $id = "")
    {
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return ($allow & Allow::ADD) == Allow::ADD;
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return ($allow & Allow::EDIT) == Allow::EDIT;
            case "delete":
                return ($allow & Allow::DELETE) == Allow::DELETE;
            case "view":
                return ($allow & Allow::VIEW) == Allow::VIEW;
            case "search":
                return ($allow & Allow::SEARCH) == Allow::SEARCH;
            case "lookup":
                return ($allow & Allow::LOOKUP) == Allow::LOOKUP;
            default:
                return ($allow & Allow::LIST) == Allow::LIST;
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $sqlwrk = $sql instanceof QueryBuilder // Query builder
            ? (clone $sql)->resetQueryPart("orderBy")->getSQL()
            : $sql;
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            in_array($this->TableType, ["TABLE", "VIEW", "LINKTABLE"]) &&
            preg_match($pattern, $sqlwrk) &&
            !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sqlwrk) &&
            !preg_match('/^\s*SELECT\s+DISTINCT\s+/i', $sqlwrk) &&
            !preg_match('/\s+ORDER\s+BY\s+/i', $sqlwrk)
        ) {
            $sqlcnt = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sqlwrk);
        } else {
            $sqlcnt = "SELECT COUNT(*) FROM (" . $sqlwrk . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $cnt = $conn->fetchOne($sqlcnt);
        if ($cnt !== false) {
            return (int)$cnt;
        }
        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        $result = $conn->executeQuery($sqlwrk);
        $cnt = $result->rowCount();
        if ($cnt == 0) { // Unable to get record count, count directly
            while ($result->fetch()) {
                $cnt++;
            }
        }
        return $cnt;
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->getSqlAsQueryBuilder($where, $orderBy)->getSQL();
    }

    // Get QueryBuilder
    public function getSqlAsQueryBuilder($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        );
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->getSqlSelect();
        $from = $this->getSqlFrom();
        $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $isCustomView = $this->TableType == "CUSTOMVIEW";
        $select = $isCustomView ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $isCustomView ? $this->getSqlGroupBy() : "";
        $having = $isCustomView ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $isCustomView = $this->TableType == "CUSTOMVIEW";
        $select = $isCustomView ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $isCustomView ? $this->getSqlGroupBy() : "";
        $having = $isCustomView ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    public function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        $platform = $this->getConnection()->getDatabasePlatform();
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            $field = $this->Fields[$name];
            $parm = $queryBuilder->createPositionalParameter($value, $field->getParameterType());
            $parm = $field->CustomDataType?->convertToDatabaseValueSQL($parm, $platform) ?? $parm; // Convert database SQL
            $queryBuilder->setValue($field->Expression, $parm);
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        try {
            $queryBuilder = $this->insertSql($rs);
            $result = $queryBuilder->executeStatement();
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $result = false;
            $this->DbErrorMessage = $e->getMessage();
        }
        if ($result) {
            $this->codnum->setDbValue($conn->lastInsertId());
            $rs['codnum'] = $this->codnum->DbValue;
        }
        return $result;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        $platform = $this->getConnection()->getDatabasePlatform();
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            $field = $this->Fields[$name];
            $parm = $queryBuilder->createPositionalParameter($value, $field->getParameterType());
            $parm = $field->CustomDataType?->convertToDatabaseValueSQL($parm, $platform) ?? $parm; // Convert database SQL
            $queryBuilder->set($field->Expression, $parm);
        }
        $filter = $curfilter ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // If no field is updated, execute may return 0. Treat as success
        try {
            $success = $this->updateSql($rs, $where, $curfilter)->executeStatement();
            $success = $success > 0 ? $success : true;
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $success = false;
            $this->DbErrorMessage = $e->getMessage();
        }

        // Return auto increment field
        if ($success) {
            if (!isset($rs['codnum']) && !EmptyValue($this->codnum->CurrentValue)) {
                $rs['codnum'] = $this->codnum->CurrentValue;
            }
        }
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
            if (array_key_exists('codnum', $rs)) {
                AddFilter($where, QuotedName('codnum', $this->Dbid) . '=' . QuotedValue($rs['codnum'], $this->codnum->DataType, $this->Dbid));
            }
        }
        $filter = $curfilter ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;
        if ($success) {
            try {
                $success = $this->deleteSql($rs, $where, $curfilter)->executeStatement();
                $this->DbErrorMessage = "";
            } catch (\Exception $e) {
                $success = false;
                $this->DbErrorMessage = $e->getMessage();
            }
        }
        return $success;
    }

    // Load DbValue from result set or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->codnum->DbValue = $row['codnum'];
        $this->nroconc->DbValue = $row['nroconc'];
        $this->descrip->DbValue = $row['descrip'];
        $this->porcentaje->DbValue = $row['porcentaje'];
        $this->importe->DbValue = $row['importe'];
        $this->usuario->DbValue = $row['usuario'];
        $this->fechahora->DbValue = $row['fechahora'];
        $this->activo->DbValue = $row['activo'];
        $this->tipoiva->DbValue = $row['tipoiva'];
        $this->impuesto->DbValue = $row['impuesto'];
        $this->tieneresol->DbValue = $row['tieneresol'];
        $this->ctacbleBAS->DbValue = $row['ctacbleBAS'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`codnum` = @codnum@";
    }

    // Get Key
    public function getKey($current = false, $keySeparator = null)
    {
        $keys = [];
        $val = $current ? $this->codnum->CurrentValue : $this->codnum->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        $keySeparator ??= Config("COMPOSITE_KEY_SEPARATOR");
        return implode($keySeparator, $keys);
    }

    // Set Key
    public function setKey($key, $current = false, $keySeparator = null)
    {
        $keySeparator ??= Config("COMPOSITE_KEY_SEPARATOR");
        $this->OldKey = strval($key);
        $keys = explode($keySeparator, $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->codnum->CurrentValue = $keys[0];
            } else {
                $this->codnum->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('codnum', $row) ? $row['codnum'] : null;
        } else {
            $val = !EmptyValue($this->codnum->OldValue) && !$current ? $this->codnum->OldValue : $this->codnum->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@codnum@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("ConcafactvenList");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        return match ($pageName) {
            "ConcafactvenView" => $Language->phrase("View"),
            "ConcafactvenEdit" => $Language->phrase("Edit"),
            "ConcafactvenAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "ConcafactvenList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "ConcafactvenView",
            Config("API_ADD_ACTION") => "ConcafactvenAdd",
            Config("API_EDIT_ACTION") => "ConcafactvenEdit",
            Config("API_DELETE_ACTION") => "ConcafactvenDelete",
            Config("API_LIST_ACTION") => "ConcafactvenList",
            default => ""
        };
    }

    // Current URL
    public function getCurrentUrl($parm = "")
    {
        $url = CurrentPageUrl(false);
        if ($parm != "") {
            $url = $this->keyUrl($url, $parm);
        } else {
            $url = $this->keyUrl($url, Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // List URL
    public function getListUrl()
    {
        return "ConcafactvenList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ConcafactvenView", $parm);
        } else {
            $url = $this->keyUrl("ConcafactvenView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ConcafactvenAdd?" . $parm;
        } else {
            $url = "ConcafactvenAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("ConcafactvenEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("ConcafactvenList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("ConcafactvenAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("ConcafactvenList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("ConcafactvenDelete", $parm);
        }
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"codnum\":" . VarToJson($this->codnum->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->codnum->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->codnum->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderFieldHeader($fld)
    {
        global $Security, $Language;
        $sortUrl = "";
        $attrs = "";
        if ($this->PageID != "grid" && $fld->Sortable) {
            $sortUrl = $this->sortUrl($fld);
            $attrs = ' role="button" data-ew-action="sort" data-ajax="' . ($this->UseAjaxActions ? "true" : "false") . '" data-sort-url="' . $sortUrl . '" data-sort-type="1"';
            if ($this->ContextClass) { // Add context
                $attrs .= ' data-context="' . HtmlEncode($this->ContextClass) . '"';
            }
        }
        $html = '<div class="ew-table-header-caption"' . $attrs . '>' . $fld->caption() . '</div>';
        if ($sortUrl) {
            $html .= '<div class="ew-table-header-sort">' . $fld->getSortIcon() . '</div>';
        }
        if ($this->PageID != "grid" && !$this->isExport() && $fld->UseFilter && $Security->canSearch()) {
            $html .= '<div class="ew-filter-dropdown-btn" data-ew-action="filter" data-table="' . $fld->TableVar . '" data-field="' . $fld->FieldVar .
                '"><div class="ew-table-header-filter" role="button" aria-haspopup="true">' . $Language->phrase("Filter") .
                (is_array($fld->EditValue) ? str_replace("%c", count($fld->EditValue), $Language->phrase("FilterCount")) : '') .
                '</div></div>';
        }
        $html = '<div class="ew-table-header-btn">' . $html . '</div>';
        if ($this->UseCustomTemplate) {
            $scriptId = str_replace("{id}", $fld->TableVar . "_" . $fld->Param, "tpc_{id}");
            $html = '<template id="' . $scriptId . '">' . $html . '</template>';
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        global $DashboardReport;
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = "order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort();
            if ($DashboardReport) {
                $urlParm .= "&amp;" . Config("PAGE_DASHBOARD") . "=" . $DashboardReport;
            }
            return $this->addMasterUrl($this->CurrentPageName . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            $isApi = IsApi();
            $keyValues = $isApi
                ? (Route(0) == "export"
                    ? array_map(fn ($i) => Route($i + 3), range(0, 0))  // Export API
                    : array_map(fn ($i) => Route($i + 2), range(0, 0))) // Other API
                : []; // Non-API
            if (($keyValue = Param("codnum") ?? Route("codnum")) !== null) {
                $arKeys[] = $keyValue;
            } elseif ($isApi && (($keyValue = Key(0) ?? $keyValues[0] ?? null) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                if (!is_numeric($key)) {
                    continue;
                }
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from records
    public function getFilterFromRecords($rows)
    {
        $keyFilter = "";
        foreach ($rows as $row) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            $keyFilter .= "(" . $this->getRecordFilter($row) . ")";
        }
        return $keyFilter;
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            if ($setCurrent) {
                $this->codnum->CurrentValue = $key;
            } else {
                $this->codnum->OldValue = $key;
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load result set based on filter/sort
    public function loadRs($filter, $sort = "")
    {
        $sql = $this->getSql($filter, $sort); // Set up filter (WHERE Clause) / sort (ORDER BY Clause)
        $conn = $this->getConnection();
        return $conn->executeQuery($sql);
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            return;
        }
        $this->codnum->setDbValue($row['codnum']);
        $this->nroconc->setDbValue($row['nroconc']);
        $this->descrip->setDbValue($row['descrip']);
        $this->porcentaje->setDbValue($row['porcentaje']);
        $this->importe->setDbValue($row['importe']);
        $this->usuario->setDbValue($row['usuario']);
        $this->fechahora->setDbValue($row['fechahora']);
        $this->activo->setDbValue($row['activo']);
        $this->tipoiva->setDbValue($row['tipoiva']);
        $this->impuesto->setDbValue($row['impuesto']);
        $this->tieneresol->setDbValue($row['tieneresol']);
        $this->ctacbleBAS->setDbValue($row['ctacbleBAS']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "ConcafactvenList";
        $listClass = PROJECT_NAMESPACE . $listPage;
        $page = new $listClass();
        $page->loadRecordsetFromFilter($filter);
        $view = Container("app.view");
        $template = $listPage . ".php"; // View
        $GLOBALS["Title"] ??= $page->Title; // Title
        try {
            $Response = $view->render($Response, $template, $GLOBALS);
        } finally {
            $page->terminate(); // Terminate page and clean up
        }
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // codnum

        // nroconc

        // descrip

        // porcentaje

        // importe

        // usuario

        // fechahora

        // activo

        // tipoiva

        // impuesto

        // tieneresol

        // ctacbleBAS

        // codnum
        $this->codnum->ViewValue = $this->codnum->CurrentValue;

        // nroconc
        $this->nroconc->ViewValue = $this->nroconc->CurrentValue;
        $this->nroconc->ViewValue = FormatNumber($this->nroconc->ViewValue, $this->nroconc->formatPattern());

        // descrip
        $this->descrip->ViewValue = $this->descrip->CurrentValue;

        // porcentaje
        $this->porcentaje->ViewValue = $this->porcentaje->CurrentValue;
        $this->porcentaje->ViewValue = FormatNumber($this->porcentaje->ViewValue, $this->porcentaje->formatPattern());

        // importe
        $this->importe->ViewValue = $this->importe->CurrentValue;
        $this->importe->ViewValue = FormatNumber($this->importe->ViewValue, $this->importe->formatPattern());

        // usuario
        $this->usuario->ViewValue = $this->usuario->CurrentValue;
        $this->usuario->ViewValue = FormatNumber($this->usuario->ViewValue, $this->usuario->formatPattern());

        // fechahora
        $this->fechahora->ViewValue = $this->fechahora->CurrentValue;
        $this->fechahora->ViewValue = FormatDateTime($this->fechahora->ViewValue, $this->fechahora->formatPattern());

        // activo
        if (strval($this->activo->CurrentValue) != "") {
            $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
        } else {
            $this->activo->ViewValue = null;
        }

        // tipoiva
        $this->tipoiva->ViewValue = $this->tipoiva->CurrentValue;
        $curVal = strval($this->tipoiva->CurrentValue);
        if ($curVal != "") {
            $this->tipoiva->ViewValue = $this->tipoiva->lookupCacheOption($curVal);
            if ($this->tipoiva->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->tipoiva->Lookup->getTable()->Fields["codnum"]->searchExpression(), "=", $curVal, $this->tipoiva->Lookup->getTable()->Fields["codnum"]->searchDataType(), "");
                $sqlWrk = $this->tipoiva->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->tipoiva->Lookup->renderViewRow($rswrk[0]);
                    $this->tipoiva->ViewValue = $this->tipoiva->displayValue($arwrk);
                } else {
                    $this->tipoiva->ViewValue = FormatNumber($this->tipoiva->CurrentValue, $this->tipoiva->formatPattern());
                }
            }
        } else {
            $this->tipoiva->ViewValue = null;
        }

        // impuesto
        $this->impuesto->ViewValue = $this->impuesto->CurrentValue;
        $curVal = strval($this->impuesto->CurrentValue);
        if ($curVal != "") {
            $this->impuesto->ViewValue = $this->impuesto->lookupCacheOption($curVal);
            if ($this->impuesto->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->impuesto->Lookup->getTable()->Fields["codnum"]->searchExpression(), "=", $curVal, $this->impuesto->Lookup->getTable()->Fields["codnum"]->searchDataType(), "");
                $sqlWrk = $this->impuesto->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->impuesto->Lookup->renderViewRow($rswrk[0]);
                    $this->impuesto->ViewValue = $this->impuesto->displayValue($arwrk);
                } else {
                    $this->impuesto->ViewValue = FormatNumber($this->impuesto->CurrentValue, $this->impuesto->formatPattern());
                }
            }
        } else {
            $this->impuesto->ViewValue = null;
        }

        // tieneresol
        if (strval($this->tieneresol->CurrentValue) != "") {
            $this->tieneresol->ViewValue = $this->tieneresol->optionCaption($this->tieneresol->CurrentValue);
        } else {
            $this->tieneresol->ViewValue = null;
        }

        // ctacbleBAS
        $this->ctacbleBAS->ViewValue = $this->ctacbleBAS->CurrentValue;

        // codnum
        $this->codnum->HrefValue = "";
        $this->codnum->TooltipValue = "";

        // nroconc
        $this->nroconc->HrefValue = "";
        $this->nroconc->TooltipValue = "";

        // descrip
        $this->descrip->HrefValue = "";
        $this->descrip->TooltipValue = "";

        // porcentaje
        $this->porcentaje->HrefValue = "";
        $this->porcentaje->TooltipValue = "";

        // importe
        $this->importe->HrefValue = "";
        $this->importe->TooltipValue = "";

        // usuario
        $this->usuario->HrefValue = "";
        $this->usuario->TooltipValue = "";

        // fechahora
        $this->fechahora->HrefValue = "";
        $this->fechahora->TooltipValue = "";

        // activo
        $this->activo->HrefValue = "";
        $this->activo->TooltipValue = "";

        // tipoiva
        $this->tipoiva->HrefValue = "";
        $this->tipoiva->TooltipValue = "";

        // impuesto
        $this->impuesto->HrefValue = "";
        $this->impuesto->TooltipValue = "";

        // tieneresol
        $this->tieneresol->HrefValue = "";
        $this->tieneresol->TooltipValue = "";

        // ctacbleBAS
        $this->ctacbleBAS->HrefValue = "";
        $this->ctacbleBAS->TooltipValue = "";

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // codnum
        $this->codnum->setupEditAttributes();
        $this->codnum->EditValue = $this->codnum->CurrentValue;

        // nroconc
        $this->nroconc->setupEditAttributes();
        $this->nroconc->EditValue = $this->nroconc->CurrentValue;
        $this->nroconc->PlaceHolder = RemoveHtml($this->nroconc->caption());
        if (strval($this->nroconc->EditValue) != "" && is_numeric($this->nroconc->EditValue)) {
            $this->nroconc->EditValue = FormatNumber($this->nroconc->EditValue, null);
        }

        // descrip
        $this->descrip->setupEditAttributes();
        if (!$this->descrip->Raw) {
            $this->descrip->CurrentValue = HtmlDecode($this->descrip->CurrentValue);
        }
        $this->descrip->EditValue = $this->descrip->CurrentValue;
        $this->descrip->PlaceHolder = RemoveHtml($this->descrip->caption());

        // porcentaje
        $this->porcentaje->setupEditAttributes();
        $this->porcentaje->EditValue = $this->porcentaje->CurrentValue;
        $this->porcentaje->PlaceHolder = RemoveHtml($this->porcentaje->caption());
        if (strval($this->porcentaje->EditValue) != "" && is_numeric($this->porcentaje->EditValue)) {
            $this->porcentaje->EditValue = FormatNumber($this->porcentaje->EditValue, null);
        }

        // importe
        $this->importe->setupEditAttributes();
        $this->importe->EditValue = $this->importe->CurrentValue;
        $this->importe->PlaceHolder = RemoveHtml($this->importe->caption());
        if (strval($this->importe->EditValue) != "" && is_numeric($this->importe->EditValue)) {
            $this->importe->EditValue = FormatNumber($this->importe->EditValue, null);
        }

        // usuario

        // fechahora
        $this->fechahora->setupEditAttributes();
        $this->fechahora->EditValue = FormatDateTime($this->fechahora->CurrentValue, $this->fechahora->formatPattern());
        $this->fechahora->PlaceHolder = RemoveHtml($this->fechahora->caption());

        // activo
        $this->activo->setupEditAttributes();
        $this->activo->EditValue = $this->activo->options(true);
        $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

        // tipoiva
        $this->tipoiva->setupEditAttributes();
        $this->tipoiva->EditValue = $this->tipoiva->CurrentValue;
        $this->tipoiva->PlaceHolder = RemoveHtml($this->tipoiva->caption());

        // impuesto
        $this->impuesto->setupEditAttributes();
        $this->impuesto->EditValue = $this->impuesto->CurrentValue;
        $this->impuesto->PlaceHolder = RemoveHtml($this->impuesto->caption());

        // tieneresol
        $this->tieneresol->setupEditAttributes();
        $this->tieneresol->EditValue = $this->tieneresol->options(true);
        $this->tieneresol->PlaceHolder = RemoveHtml($this->tieneresol->caption());

        // ctacbleBAS
        $this->ctacbleBAS->setupEditAttributes();
        if (!$this->ctacbleBAS->Raw) {
            $this->ctacbleBAS->CurrentValue = HtmlDecode($this->ctacbleBAS->CurrentValue);
        }
        $this->ctacbleBAS->EditValue = $this->ctacbleBAS->CurrentValue;
        $this->ctacbleBAS->PlaceHolder = RemoveHtml($this->ctacbleBAS->caption());

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $result, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$result || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->codnum);
                    $doc->exportCaption($this->nroconc);
                    $doc->exportCaption($this->descrip);
                    $doc->exportCaption($this->porcentaje);
                    $doc->exportCaption($this->importe);
                    $doc->exportCaption($this->usuario);
                    $doc->exportCaption($this->fechahora);
                    $doc->exportCaption($this->activo);
                    $doc->exportCaption($this->tipoiva);
                    $doc->exportCaption($this->impuesto);
                    $doc->exportCaption($this->tieneresol);
                    $doc->exportCaption($this->ctacbleBAS);
                } else {
                    $doc->exportCaption($this->codnum);
                    $doc->exportCaption($this->nroconc);
                    $doc->exportCaption($this->descrip);
                    $doc->exportCaption($this->porcentaje);
                    $doc->exportCaption($this->importe);
                    $doc->exportCaption($this->usuario);
                    $doc->exportCaption($this->fechahora);
                    $doc->exportCaption($this->activo);
                    $doc->exportCaption($this->tipoiva);
                    $doc->exportCaption($this->impuesto);
                    $doc->exportCaption($this->tieneresol);
                    $doc->exportCaption($this->ctacbleBAS);
                }
                $doc->endExportRow();
            }
        }
        $recCnt = $startRec - 1;
        $stopRec = $stopRec > 0 ? $stopRec : PHP_INT_MAX;
        while (($row = $result->fetch()) && $recCnt < $stopRec) {
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);

                // Render row
                $this->RowType = RowType::VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->codnum);
                        $doc->exportField($this->nroconc);
                        $doc->exportField($this->descrip);
                        $doc->exportField($this->porcentaje);
                        $doc->exportField($this->importe);
                        $doc->exportField($this->usuario);
                        $doc->exportField($this->fechahora);
                        $doc->exportField($this->activo);
                        $doc->exportField($this->tipoiva);
                        $doc->exportField($this->impuesto);
                        $doc->exportField($this->tieneresol);
                        $doc->exportField($this->ctacbleBAS);
                    } else {
                        $doc->exportField($this->codnum);
                        $doc->exportField($this->nroconc);
                        $doc->exportField($this->descrip);
                        $doc->exportField($this->porcentaje);
                        $doc->exportField($this->importe);
                        $doc->exportField($this->usuario);
                        $doc->exportField($this->fechahora);
                        $doc->exportField($this->activo);
                        $doc->exportField($this->tipoiva);
                        $doc->exportField($this->impuesto);
                        $doc->exportField($this->tieneresol);
                        $doc->exportField($this->ctacbleBAS);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($doc, $row);
            }
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        global $DownloadFileName;

        // No binary fields
        return false;
    }

    // Table level events

    // Table Load event
    public function tableLoad()
    {
        // Enter your code here
    }

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected($rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, $rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, $rsnew)
    {
        //Log("Row Updated");
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted($rs)
    {
        //Log("Row Deleted");
    }

    // Email Sending event
    public function emailSending($email, $args)
    {
        //var_dump($email, $args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
