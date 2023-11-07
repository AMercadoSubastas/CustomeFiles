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
 * Table class for cabremi
 */
class Cabremi extends DbTable
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
    public $tcomp;
    public $serie;
    public $ncomp;
    public $cantrengs;
    public $comprador;
    public $fecharemi;
    public $observaciones;
    public $calle;
    public $numero;
    public $pisodto;
    public $codpais;
    public $codprov;
    public $codloc;
    public $codpost;
    public $patente;
    public $patremolque;
    public $cuit;
    public $fechahora;
    public $usuario;
    public $tcomprel;
    public $serierel;
    public $ncomprel;
    public $usuarioultmod;
    public $fechaultmod;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "cabremi";
        $this->TableName = 'cabremi';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "cabremi";
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

        // tcomp
        $this->tcomp = new DbField(
            $this, // Table
            'x_tcomp', // Variable name
            'tcomp', // Name
            '`tcomp`', // Expression
            '`tcomp`', // Basic search expression
            3, // Type
            2, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`tcomp`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->tcomp->InputTextType = "text";
        $this->tcomp->Raw = true;
        $this->tcomp->Nullable = false; // NOT NULL field
        $this->tcomp->Required = true; // Required field
        $this->tcomp->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->tcomp->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['tcomp'] = &$this->tcomp;

        // serie
        $this->serie = new DbField(
            $this, // Table
            'x_serie', // Variable name
            'serie', // Name
            '`serie`', // Expression
            '`serie`', // Basic search expression
            3, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`serie`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->serie->InputTextType = "text";
        $this->serie->Raw = true;
        $this->serie->Nullable = false; // NOT NULL field
        $this->serie->Required = true; // Required field
        $this->serie->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->serie->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['serie'] = &$this->serie;

        // ncomp
        $this->ncomp = new DbField(
            $this, // Table
            'x_ncomp', // Variable name
            'ncomp', // Name
            '`ncomp`', // Expression
            '`ncomp`', // Basic search expression
            3, // Type
            9, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ncomp`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ncomp->InputTextType = "text";
        $this->ncomp->Raw = true;
        $this->ncomp->Nullable = false; // NOT NULL field
        $this->ncomp->Required = true; // Required field
        $this->ncomp->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->ncomp->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['ncomp'] = &$this->ncomp;

        // cantrengs
        $this->cantrengs = new DbField(
            $this, // Table
            'x_cantrengs', // Variable name
            'cantrengs', // Name
            '`cantrengs`', // Expression
            '`cantrengs`', // Basic search expression
            3, // Type
            3, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cantrengs`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cantrengs->InputTextType = "text";
        $this->cantrengs->Raw = true;
        $this->cantrengs->Required = true; // Required field
        $this->cantrengs->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cantrengs->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['cantrengs'] = &$this->cantrengs;

        // comprador
        $this->comprador = new DbField(
            $this, // Table
            'x_comprador', // Variable name
            'comprador', // Name
            '`comprador`', // Expression
            '`comprador`', // Basic search expression
            3, // Type
            5, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`comprador`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->comprador->InputTextType = "text";
        $this->comprador->Raw = true;
        $this->comprador->Required = true; // Required field
        $this->comprador->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->comprador->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['comprador'] = &$this->comprador;

        // fecharemi
        $this->fecharemi = new DbField(
            $this, // Table
            'x_fecharemi', // Variable name
            'fecharemi', // Name
            '`fecharemi`', // Expression
            CastDateFieldForLike("`fecharemi`", 0, "DB"), // Basic search expression
            133, // Type
            10, // Size
            0, // Date/Time format
            false, // Is upload field
            '`fecharemi`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fecharemi->InputTextType = "text";
        $this->fecharemi->Raw = true;
        $this->fecharemi->Nullable = false; // NOT NULL field
        $this->fecharemi->Required = true; // Required field
        $this->fecharemi->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->fecharemi->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['fecharemi'] = &$this->fecharemi;

        // observaciones
        $this->observaciones = new DbField(
            $this, // Table
            'x_observaciones', // Variable name
            'observaciones', // Name
            '`observaciones`', // Expression
            '`observaciones`', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`observaciones`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->observaciones->InputTextType = "text";
        $this->observaciones->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['observaciones'] = &$this->observaciones;

        // calle
        $this->calle = new DbField(
            $this, // Table
            'x_calle', // Variable name
            'calle', // Name
            '`calle`', // Expression
            '`calle`', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`calle`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->calle->InputTextType = "text";
        $this->calle->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['calle'] = &$this->calle;

        // numero
        $this->numero = new DbField(
            $this, // Table
            'x_numero', // Variable name
            'numero', // Name
            '`numero`', // Expression
            '`numero`', // Basic search expression
            200, // Type
            10, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`numero`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->numero->InputTextType = "text";
        $this->numero->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['numero'] = &$this->numero;

        // pisodto
        $this->pisodto = new DbField(
            $this, // Table
            'x_pisodto', // Variable name
            'pisodto', // Name
            '`pisodto`', // Expression
            '`pisodto`', // Basic search expression
            200, // Type
            10, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`pisodto`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->pisodto->InputTextType = "text";
        $this->pisodto->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['pisodto'] = &$this->pisodto;

        // codpais
        $this->codpais = new DbField(
            $this, // Table
            'x_codpais', // Variable name
            'codpais', // Name
            '`codpais`', // Expression
            '`codpais`', // Basic search expression
            3, // Type
            3, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`codpais`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->codpais->InputTextType = "text";
        $this->codpais->Raw = true;
        $this->codpais->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->codpais->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['codpais'] = &$this->codpais;

        // codprov
        $this->codprov = new DbField(
            $this, // Table
            'x_codprov', // Variable name
            'codprov', // Name
            '`codprov`', // Expression
            '`codprov`', // Basic search expression
            3, // Type
            3, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`codprov`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->codprov->InputTextType = "text";
        $this->codprov->Raw = true;
        $this->codprov->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->codprov->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['codprov'] = &$this->codprov;

        // codloc
        $this->codloc = new DbField(
            $this, // Table
            'x_codloc', // Variable name
            'codloc', // Name
            '`codloc`', // Expression
            '`codloc`', // Basic search expression
            3, // Type
            3, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`codloc`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->codloc->InputTextType = "text";
        $this->codloc->Raw = true;
        $this->codloc->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->codloc->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['codloc'] = &$this->codloc;

        // codpost
        $this->codpost = new DbField(
            $this, // Table
            'x_codpost', // Variable name
            'codpost', // Name
            '`codpost`', // Expression
            '`codpost`', // Basic search expression
            200, // Type
            8, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`codpost`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->codpost->InputTextType = "text";
        $this->codpost->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['codpost'] = &$this->codpost;

        // patente
        $this->patente = new DbField(
            $this, // Table
            'x_patente', // Variable name
            'patente', // Name
            '`patente`', // Expression
            '`patente`', // Basic search expression
            200, // Type
            8, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`patente`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->patente->InputTextType = "text";
        $this->patente->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['patente'] = &$this->patente;

        // patremolque
        $this->patremolque = new DbField(
            $this, // Table
            'x_patremolque', // Variable name
            'patremolque', // Name
            '`patremolque`', // Expression
            '`patremolque`', // Basic search expression
            200, // Type
            8, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`patremolque`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->patremolque->InputTextType = "text";
        $this->patremolque->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['patremolque'] = &$this->patremolque;

        // cuit
        $this->cuit = new DbField(
            $this, // Table
            'x_cuit', // Variable name
            'cuit', // Name
            '`cuit`', // Expression
            '`cuit`', // Basic search expression
            200, // Type
            14, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cuit`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cuit->InputTextType = "text";
        $this->cuit->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['cuit'] = &$this->cuit;

        // fechahora
        $this->fechahora = new DbField(
            $this, // Table
            'x_fechahora', // Variable name
            'fechahora', // Name
            '`fechahora`', // Expression
            CastDateFieldForLike("`fechahora`", 0, "DB"), // Basic search expression
            135, // Type
            19, // Size
            0, // Date/Time format
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
        $this->fechahora->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->fechahora->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fechahora'] = &$this->fechahora;

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
        $this->usuario->InputTextType = "text";
        $this->usuario->Raw = true;
        $this->usuario->Required = true; // Required field
        $this->usuario->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->usuario->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['usuario'] = &$this->usuario;

        // tcomprel
        $this->tcomprel = new DbField(
            $this, // Table
            'x_tcomprel', // Variable name
            'tcomprel', // Name
            '`tcomprel`', // Expression
            '`tcomprel`', // Basic search expression
            3, // Type
            9, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`tcomprel`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->tcomprel->InputTextType = "text";
        $this->tcomprel->Raw = true;
        $this->tcomprel->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->tcomprel->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['tcomprel'] = &$this->tcomprel;

        // serierel
        $this->serierel = new DbField(
            $this, // Table
            'x_serierel', // Variable name
            'serierel', // Name
            '`serierel`', // Expression
            '`serierel`', // Basic search expression
            3, // Type
            5, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`serierel`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->serierel->InputTextType = "text";
        $this->serierel->Raw = true;
        $this->serierel->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->serierel->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['serierel'] = &$this->serierel;

        // ncomprel
        $this->ncomprel = new DbField(
            $this, // Table
            'x_ncomprel', // Variable name
            'ncomprel', // Name
            '`ncomprel`', // Expression
            '`ncomprel`', // Basic search expression
            3, // Type
            9, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ncomprel`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ncomprel->InputTextType = "text";
        $this->ncomprel->Raw = true;
        $this->ncomprel->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->ncomprel->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['ncomprel'] = &$this->ncomprel;

        // usuarioultmod
        $this->usuarioultmod = new DbField(
            $this, // Table
            'x_usuarioultmod', // Variable name
            'usuarioultmod', // Name
            '`usuarioultmod`', // Expression
            '`usuarioultmod`', // Basic search expression
            3, // Type
            3, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`usuarioultmod`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->usuarioultmod->InputTextType = "text";
        $this->usuarioultmod->Raw = true;
        $this->usuarioultmod->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->usuarioultmod->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['usuarioultmod'] = &$this->usuarioultmod;

        // fechaultmod
        $this->fechaultmod = new DbField(
            $this, // Table
            'x_fechaultmod', // Variable name
            'fechaultmod', // Name
            '`fechaultmod`', // Expression
            CastDateFieldForLike("`fechaultmod`", 0, "DB"), // Basic search expression
            135, // Type
            19, // Size
            0, // Date/Time format
            false, // Is upload field
            '`fechaultmod`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fechaultmod->InputTextType = "text";
        $this->fechaultmod->Raw = true;
        $this->fechaultmod->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->fechaultmod->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fechaultmod'] = &$this->fechaultmod;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "cabremi";
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
        $this->tcomp->DbValue = $row['tcomp'];
        $this->serie->DbValue = $row['serie'];
        $this->ncomp->DbValue = $row['ncomp'];
        $this->cantrengs->DbValue = $row['cantrengs'];
        $this->comprador->DbValue = $row['comprador'];
        $this->fecharemi->DbValue = $row['fecharemi'];
        $this->observaciones->DbValue = $row['observaciones'];
        $this->calle->DbValue = $row['calle'];
        $this->numero->DbValue = $row['numero'];
        $this->pisodto->DbValue = $row['pisodto'];
        $this->codpais->DbValue = $row['codpais'];
        $this->codprov->DbValue = $row['codprov'];
        $this->codloc->DbValue = $row['codloc'];
        $this->codpost->DbValue = $row['codpost'];
        $this->patente->DbValue = $row['patente'];
        $this->patremolque->DbValue = $row['patremolque'];
        $this->cuit->DbValue = $row['cuit'];
        $this->fechahora->DbValue = $row['fechahora'];
        $this->usuario->DbValue = $row['usuario'];
        $this->tcomprel->DbValue = $row['tcomprel'];
        $this->serierel->DbValue = $row['serierel'];
        $this->ncomprel->DbValue = $row['ncomprel'];
        $this->usuarioultmod->DbValue = $row['usuarioultmod'];
        $this->fechaultmod->DbValue = $row['fechaultmod'];
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
        return $_SESSION[$name] ?? GetUrl("CabremiList");
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
            "CabremiView" => $Language->phrase("View"),
            "CabremiEdit" => $Language->phrase("Edit"),
            "CabremiAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "CabremiList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "CabremiView",
            Config("API_ADD_ACTION") => "CabremiAdd",
            Config("API_EDIT_ACTION") => "CabremiEdit",
            Config("API_DELETE_ACTION") => "CabremiDelete",
            Config("API_LIST_ACTION") => "CabremiList",
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
        return "CabremiList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("CabremiView", $parm);
        } else {
            $url = $this->keyUrl("CabremiView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "CabremiAdd?" . $parm;
        } else {
            $url = "CabremiAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("CabremiEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("CabremiList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("CabremiAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("CabremiList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("CabremiDelete", $parm);
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
        $this->tcomp->setDbValue($row['tcomp']);
        $this->serie->setDbValue($row['serie']);
        $this->ncomp->setDbValue($row['ncomp']);
        $this->cantrengs->setDbValue($row['cantrengs']);
        $this->comprador->setDbValue($row['comprador']);
        $this->fecharemi->setDbValue($row['fecharemi']);
        $this->observaciones->setDbValue($row['observaciones']);
        $this->calle->setDbValue($row['calle']);
        $this->numero->setDbValue($row['numero']);
        $this->pisodto->setDbValue($row['pisodto']);
        $this->codpais->setDbValue($row['codpais']);
        $this->codprov->setDbValue($row['codprov']);
        $this->codloc->setDbValue($row['codloc']);
        $this->codpost->setDbValue($row['codpost']);
        $this->patente->setDbValue($row['patente']);
        $this->patremolque->setDbValue($row['patremolque']);
        $this->cuit->setDbValue($row['cuit']);
        $this->fechahora->setDbValue($row['fechahora']);
        $this->usuario->setDbValue($row['usuario']);
        $this->tcomprel->setDbValue($row['tcomprel']);
        $this->serierel->setDbValue($row['serierel']);
        $this->ncomprel->setDbValue($row['ncomprel']);
        $this->usuarioultmod->setDbValue($row['usuarioultmod']);
        $this->fechaultmod->setDbValue($row['fechaultmod']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "CabremiList";
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

        // tcomp

        // serie

        // ncomp

        // cantrengs

        // comprador

        // fecharemi

        // observaciones

        // calle

        // numero

        // pisodto

        // codpais

        // codprov

        // codloc

        // codpost

        // patente

        // patremolque

        // cuit

        // fechahora

        // usuario

        // tcomprel

        // serierel

        // ncomprel

        // usuarioultmod

        // fechaultmod

        // codnum
        $this->codnum->ViewValue = $this->codnum->CurrentValue;
        $this->codnum->ViewValue = FormatNumber($this->codnum->ViewValue, $this->codnum->formatPattern());

        // tcomp
        $this->tcomp->ViewValue = $this->tcomp->CurrentValue;
        $this->tcomp->ViewValue = FormatNumber($this->tcomp->ViewValue, $this->tcomp->formatPattern());

        // serie
        $this->serie->ViewValue = $this->serie->CurrentValue;
        $this->serie->ViewValue = FormatNumber($this->serie->ViewValue, $this->serie->formatPattern());

        // ncomp
        $this->ncomp->ViewValue = $this->ncomp->CurrentValue;
        $this->ncomp->ViewValue = FormatNumber($this->ncomp->ViewValue, $this->ncomp->formatPattern());

        // cantrengs
        $this->cantrengs->ViewValue = $this->cantrengs->CurrentValue;
        $this->cantrengs->ViewValue = FormatNumber($this->cantrengs->ViewValue, $this->cantrengs->formatPattern());

        // comprador
        $this->comprador->ViewValue = $this->comprador->CurrentValue;
        $this->comprador->ViewValue = FormatNumber($this->comprador->ViewValue, $this->comprador->formatPattern());

        // fecharemi
        $this->fecharemi->ViewValue = $this->fecharemi->CurrentValue;
        $this->fecharemi->ViewValue = FormatDateTime($this->fecharemi->ViewValue, $this->fecharemi->formatPattern());

        // observaciones
        $this->observaciones->ViewValue = $this->observaciones->CurrentValue;

        // calle
        $this->calle->ViewValue = $this->calle->CurrentValue;

        // numero
        $this->numero->ViewValue = $this->numero->CurrentValue;

        // pisodto
        $this->pisodto->ViewValue = $this->pisodto->CurrentValue;

        // codpais
        $this->codpais->ViewValue = $this->codpais->CurrentValue;
        $this->codpais->ViewValue = FormatNumber($this->codpais->ViewValue, $this->codpais->formatPattern());

        // codprov
        $this->codprov->ViewValue = $this->codprov->CurrentValue;
        $this->codprov->ViewValue = FormatNumber($this->codprov->ViewValue, $this->codprov->formatPattern());

        // codloc
        $this->codloc->ViewValue = $this->codloc->CurrentValue;
        $this->codloc->ViewValue = FormatNumber($this->codloc->ViewValue, $this->codloc->formatPattern());

        // codpost
        $this->codpost->ViewValue = $this->codpost->CurrentValue;

        // patente
        $this->patente->ViewValue = $this->patente->CurrentValue;

        // patremolque
        $this->patremolque->ViewValue = $this->patremolque->CurrentValue;

        // cuit
        $this->cuit->ViewValue = $this->cuit->CurrentValue;

        // fechahora
        $this->fechahora->ViewValue = $this->fechahora->CurrentValue;
        $this->fechahora->ViewValue = FormatDateTime($this->fechahora->ViewValue, $this->fechahora->formatPattern());

        // usuario
        $this->usuario->ViewValue = $this->usuario->CurrentValue;
        $this->usuario->ViewValue = FormatNumber($this->usuario->ViewValue, $this->usuario->formatPattern());

        // tcomprel
        $this->tcomprel->ViewValue = $this->tcomprel->CurrentValue;
        $this->tcomprel->ViewValue = FormatNumber($this->tcomprel->ViewValue, $this->tcomprel->formatPattern());

        // serierel
        $this->serierel->ViewValue = $this->serierel->CurrentValue;
        $this->serierel->ViewValue = FormatNumber($this->serierel->ViewValue, $this->serierel->formatPattern());

        // ncomprel
        $this->ncomprel->ViewValue = $this->ncomprel->CurrentValue;
        $this->ncomprel->ViewValue = FormatNumber($this->ncomprel->ViewValue, $this->ncomprel->formatPattern());

        // usuarioultmod
        $this->usuarioultmod->ViewValue = $this->usuarioultmod->CurrentValue;
        $this->usuarioultmod->ViewValue = FormatNumber($this->usuarioultmod->ViewValue, $this->usuarioultmod->formatPattern());

        // fechaultmod
        $this->fechaultmod->ViewValue = $this->fechaultmod->CurrentValue;
        $this->fechaultmod->ViewValue = FormatDateTime($this->fechaultmod->ViewValue, $this->fechaultmod->formatPattern());

        // codnum
        $this->codnum->HrefValue = "";
        $this->codnum->TooltipValue = "";

        // tcomp
        $this->tcomp->HrefValue = "";
        $this->tcomp->TooltipValue = "";

        // serie
        $this->serie->HrefValue = "";
        $this->serie->TooltipValue = "";

        // ncomp
        $this->ncomp->HrefValue = "";
        $this->ncomp->TooltipValue = "";

        // cantrengs
        $this->cantrengs->HrefValue = "";
        $this->cantrengs->TooltipValue = "";

        // comprador
        $this->comprador->HrefValue = "";
        $this->comprador->TooltipValue = "";

        // fecharemi
        $this->fecharemi->HrefValue = "";
        $this->fecharemi->TooltipValue = "";

        // observaciones
        $this->observaciones->HrefValue = "";
        $this->observaciones->TooltipValue = "";

        // calle
        $this->calle->HrefValue = "";
        $this->calle->TooltipValue = "";

        // numero
        $this->numero->HrefValue = "";
        $this->numero->TooltipValue = "";

        // pisodto
        $this->pisodto->HrefValue = "";
        $this->pisodto->TooltipValue = "";

        // codpais
        $this->codpais->HrefValue = "";
        $this->codpais->TooltipValue = "";

        // codprov
        $this->codprov->HrefValue = "";
        $this->codprov->TooltipValue = "";

        // codloc
        $this->codloc->HrefValue = "";
        $this->codloc->TooltipValue = "";

        // codpost
        $this->codpost->HrefValue = "";
        $this->codpost->TooltipValue = "";

        // patente
        $this->patente->HrefValue = "";
        $this->patente->TooltipValue = "";

        // patremolque
        $this->patremolque->HrefValue = "";
        $this->patremolque->TooltipValue = "";

        // cuit
        $this->cuit->HrefValue = "";
        $this->cuit->TooltipValue = "";

        // fechahora
        $this->fechahora->HrefValue = "";
        $this->fechahora->TooltipValue = "";

        // usuario
        $this->usuario->HrefValue = "";
        $this->usuario->TooltipValue = "";

        // tcomprel
        $this->tcomprel->HrefValue = "";
        $this->tcomprel->TooltipValue = "";

        // serierel
        $this->serierel->HrefValue = "";
        $this->serierel->TooltipValue = "";

        // ncomprel
        $this->ncomprel->HrefValue = "";
        $this->ncomprel->TooltipValue = "";

        // usuarioultmod
        $this->usuarioultmod->HrefValue = "";
        $this->usuarioultmod->TooltipValue = "";

        // fechaultmod
        $this->fechaultmod->HrefValue = "";
        $this->fechaultmod->TooltipValue = "";

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
        $this->codnum->EditValue = FormatNumber($this->codnum->EditValue, $this->codnum->formatPattern());

        // tcomp
        $this->tcomp->setupEditAttributes();
        $this->tcomp->EditValue = $this->tcomp->CurrentValue;
        $this->tcomp->PlaceHolder = RemoveHtml($this->tcomp->caption());
        if (strval($this->tcomp->EditValue) != "" && is_numeric($this->tcomp->EditValue)) {
            $this->tcomp->EditValue = FormatNumber($this->tcomp->EditValue, null);
        }

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

        // cantrengs
        $this->cantrengs->setupEditAttributes();
        $this->cantrengs->EditValue = $this->cantrengs->CurrentValue;
        $this->cantrengs->PlaceHolder = RemoveHtml($this->cantrengs->caption());
        if (strval($this->cantrengs->EditValue) != "" && is_numeric($this->cantrengs->EditValue)) {
            $this->cantrengs->EditValue = FormatNumber($this->cantrengs->EditValue, null);
        }

        // comprador
        $this->comprador->setupEditAttributes();
        $this->comprador->EditValue = $this->comprador->CurrentValue;
        $this->comprador->PlaceHolder = RemoveHtml($this->comprador->caption());
        if (strval($this->comprador->EditValue) != "" && is_numeric($this->comprador->EditValue)) {
            $this->comprador->EditValue = FormatNumber($this->comprador->EditValue, null);
        }

        // fecharemi
        $this->fecharemi->setupEditAttributes();
        $this->fecharemi->EditValue = FormatDateTime($this->fecharemi->CurrentValue, $this->fecharemi->formatPattern());
        $this->fecharemi->PlaceHolder = RemoveHtml($this->fecharemi->caption());

        // observaciones
        $this->observaciones->setupEditAttributes();
        if (!$this->observaciones->Raw) {
            $this->observaciones->CurrentValue = HtmlDecode($this->observaciones->CurrentValue);
        }
        $this->observaciones->EditValue = $this->observaciones->CurrentValue;
        $this->observaciones->PlaceHolder = RemoveHtml($this->observaciones->caption());

        // calle
        $this->calle->setupEditAttributes();
        if (!$this->calle->Raw) {
            $this->calle->CurrentValue = HtmlDecode($this->calle->CurrentValue);
        }
        $this->calle->EditValue = $this->calle->CurrentValue;
        $this->calle->PlaceHolder = RemoveHtml($this->calle->caption());

        // numero
        $this->numero->setupEditAttributes();
        if (!$this->numero->Raw) {
            $this->numero->CurrentValue = HtmlDecode($this->numero->CurrentValue);
        }
        $this->numero->EditValue = $this->numero->CurrentValue;
        $this->numero->PlaceHolder = RemoveHtml($this->numero->caption());

        // pisodto
        $this->pisodto->setupEditAttributes();
        if (!$this->pisodto->Raw) {
            $this->pisodto->CurrentValue = HtmlDecode($this->pisodto->CurrentValue);
        }
        $this->pisodto->EditValue = $this->pisodto->CurrentValue;
        $this->pisodto->PlaceHolder = RemoveHtml($this->pisodto->caption());

        // codpais
        $this->codpais->setupEditAttributes();
        $this->codpais->EditValue = $this->codpais->CurrentValue;
        $this->codpais->PlaceHolder = RemoveHtml($this->codpais->caption());
        if (strval($this->codpais->EditValue) != "" && is_numeric($this->codpais->EditValue)) {
            $this->codpais->EditValue = FormatNumber($this->codpais->EditValue, null);
        }

        // codprov
        $this->codprov->setupEditAttributes();
        $this->codprov->EditValue = $this->codprov->CurrentValue;
        $this->codprov->PlaceHolder = RemoveHtml($this->codprov->caption());
        if (strval($this->codprov->EditValue) != "" && is_numeric($this->codprov->EditValue)) {
            $this->codprov->EditValue = FormatNumber($this->codprov->EditValue, null);
        }

        // codloc
        $this->codloc->setupEditAttributes();
        $this->codloc->EditValue = $this->codloc->CurrentValue;
        $this->codloc->PlaceHolder = RemoveHtml($this->codloc->caption());
        if (strval($this->codloc->EditValue) != "" && is_numeric($this->codloc->EditValue)) {
            $this->codloc->EditValue = FormatNumber($this->codloc->EditValue, null);
        }

        // codpost
        $this->codpost->setupEditAttributes();
        if (!$this->codpost->Raw) {
            $this->codpost->CurrentValue = HtmlDecode($this->codpost->CurrentValue);
        }
        $this->codpost->EditValue = $this->codpost->CurrentValue;
        $this->codpost->PlaceHolder = RemoveHtml($this->codpost->caption());

        // patente
        $this->patente->setupEditAttributes();
        if (!$this->patente->Raw) {
            $this->patente->CurrentValue = HtmlDecode($this->patente->CurrentValue);
        }
        $this->patente->EditValue = $this->patente->CurrentValue;
        $this->patente->PlaceHolder = RemoveHtml($this->patente->caption());

        // patremolque
        $this->patremolque->setupEditAttributes();
        if (!$this->patremolque->Raw) {
            $this->patremolque->CurrentValue = HtmlDecode($this->patremolque->CurrentValue);
        }
        $this->patremolque->EditValue = $this->patremolque->CurrentValue;
        $this->patremolque->PlaceHolder = RemoveHtml($this->patremolque->caption());

        // cuit
        $this->cuit->setupEditAttributes();
        if (!$this->cuit->Raw) {
            $this->cuit->CurrentValue = HtmlDecode($this->cuit->CurrentValue);
        }
        $this->cuit->EditValue = $this->cuit->CurrentValue;
        $this->cuit->PlaceHolder = RemoveHtml($this->cuit->caption());

        // fechahora
        $this->fechahora->setupEditAttributes();
        $this->fechahora->EditValue = FormatDateTime($this->fechahora->CurrentValue, $this->fechahora->formatPattern());
        $this->fechahora->PlaceHolder = RemoveHtml($this->fechahora->caption());

        // usuario
        $this->usuario->setupEditAttributes();
        $this->usuario->EditValue = $this->usuario->CurrentValue;
        $this->usuario->PlaceHolder = RemoveHtml($this->usuario->caption());
        if (strval($this->usuario->EditValue) != "" && is_numeric($this->usuario->EditValue)) {
            $this->usuario->EditValue = FormatNumber($this->usuario->EditValue, null);
        }

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

        // usuarioultmod
        $this->usuarioultmod->setupEditAttributes();
        $this->usuarioultmod->EditValue = $this->usuarioultmod->CurrentValue;
        $this->usuarioultmod->PlaceHolder = RemoveHtml($this->usuarioultmod->caption());
        if (strval($this->usuarioultmod->EditValue) != "" && is_numeric($this->usuarioultmod->EditValue)) {
            $this->usuarioultmod->EditValue = FormatNumber($this->usuarioultmod->EditValue, null);
        }

        // fechaultmod
        $this->fechaultmod->setupEditAttributes();
        $this->fechaultmod->EditValue = FormatDateTime($this->fechaultmod->CurrentValue, $this->fechaultmod->formatPattern());
        $this->fechaultmod->PlaceHolder = RemoveHtml($this->fechaultmod->caption());

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
                    $doc->exportCaption($this->tcomp);
                    $doc->exportCaption($this->serie);
                    $doc->exportCaption($this->ncomp);
                    $doc->exportCaption($this->cantrengs);
                    $doc->exportCaption($this->comprador);
                    $doc->exportCaption($this->fecharemi);
                    $doc->exportCaption($this->observaciones);
                    $doc->exportCaption($this->calle);
                    $doc->exportCaption($this->numero);
                    $doc->exportCaption($this->pisodto);
                    $doc->exportCaption($this->codpais);
                    $doc->exportCaption($this->codprov);
                    $doc->exportCaption($this->codloc);
                    $doc->exportCaption($this->codpost);
                    $doc->exportCaption($this->patente);
                    $doc->exportCaption($this->patremolque);
                    $doc->exportCaption($this->cuit);
                    $doc->exportCaption($this->fechahora);
                    $doc->exportCaption($this->usuario);
                    $doc->exportCaption($this->tcomprel);
                    $doc->exportCaption($this->serierel);
                    $doc->exportCaption($this->ncomprel);
                    $doc->exportCaption($this->usuarioultmod);
                    $doc->exportCaption($this->fechaultmod);
                } else {
                    $doc->exportCaption($this->codnum);
                    $doc->exportCaption($this->tcomp);
                    $doc->exportCaption($this->serie);
                    $doc->exportCaption($this->ncomp);
                    $doc->exportCaption($this->cantrengs);
                    $doc->exportCaption($this->comprador);
                    $doc->exportCaption($this->fecharemi);
                    $doc->exportCaption($this->observaciones);
                    $doc->exportCaption($this->calle);
                    $doc->exportCaption($this->numero);
                    $doc->exportCaption($this->pisodto);
                    $doc->exportCaption($this->codpais);
                    $doc->exportCaption($this->codprov);
                    $doc->exportCaption($this->codloc);
                    $doc->exportCaption($this->codpost);
                    $doc->exportCaption($this->patente);
                    $doc->exportCaption($this->patremolque);
                    $doc->exportCaption($this->cuit);
                    $doc->exportCaption($this->fechahora);
                    $doc->exportCaption($this->usuario);
                    $doc->exportCaption($this->tcomprel);
                    $doc->exportCaption($this->serierel);
                    $doc->exportCaption($this->ncomprel);
                    $doc->exportCaption($this->usuarioultmod);
                    $doc->exportCaption($this->fechaultmod);
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
                        $doc->exportField($this->tcomp);
                        $doc->exportField($this->serie);
                        $doc->exportField($this->ncomp);
                        $doc->exportField($this->cantrengs);
                        $doc->exportField($this->comprador);
                        $doc->exportField($this->fecharemi);
                        $doc->exportField($this->observaciones);
                        $doc->exportField($this->calle);
                        $doc->exportField($this->numero);
                        $doc->exportField($this->pisodto);
                        $doc->exportField($this->codpais);
                        $doc->exportField($this->codprov);
                        $doc->exportField($this->codloc);
                        $doc->exportField($this->codpost);
                        $doc->exportField($this->patente);
                        $doc->exportField($this->patremolque);
                        $doc->exportField($this->cuit);
                        $doc->exportField($this->fechahora);
                        $doc->exportField($this->usuario);
                        $doc->exportField($this->tcomprel);
                        $doc->exportField($this->serierel);
                        $doc->exportField($this->ncomprel);
                        $doc->exportField($this->usuarioultmod);
                        $doc->exportField($this->fechaultmod);
                    } else {
                        $doc->exportField($this->codnum);
                        $doc->exportField($this->tcomp);
                        $doc->exportField($this->serie);
                        $doc->exportField($this->ncomp);
                        $doc->exportField($this->cantrengs);
                        $doc->exportField($this->comprador);
                        $doc->exportField($this->fecharemi);
                        $doc->exportField($this->observaciones);
                        $doc->exportField($this->calle);
                        $doc->exportField($this->numero);
                        $doc->exportField($this->pisodto);
                        $doc->exportField($this->codpais);
                        $doc->exportField($this->codprov);
                        $doc->exportField($this->codloc);
                        $doc->exportField($this->codpost);
                        $doc->exportField($this->patente);
                        $doc->exportField($this->patremolque);
                        $doc->exportField($this->cuit);
                        $doc->exportField($this->fechahora);
                        $doc->exportField($this->usuario);
                        $doc->exportField($this->tcomprel);
                        $doc->exportField($this->serierel);
                        $doc->exportField($this->ncomprel);
                        $doc->exportField($this->usuarioultmod);
                        $doc->exportField($this->fechaultmod);
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
