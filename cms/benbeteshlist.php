<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "benbeteshinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$benbetesh_list = NULL; // Initialize page object first

class cbenbetesh_list extends cbenbetesh {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'benbetesh';

	// Page object name
	var $PageObjName = 'benbetesh_list';

	// Grid form hidden field names
	var $FormName = 'fbenbeteshlist';
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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
			$html .= "<div class=\"alert alert-error ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<table class=\"ewStdTable\"><tr><td><div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div></td></tr></table>";
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

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (benbetesh)
		if (!isset($GLOBALS["benbetesh"]) || get_class($GLOBALS["benbetesh"]) == "cbenbetesh") {
			$GLOBALS["benbetesh"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["benbetesh"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "benbeteshadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "benbeteshdelete.php";
		$this->MultiUpdateUrl = "benbeteshupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'benbetesh', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Setup other options
		$this->SetupOtherOptions();

		// Set "checkbox" visible
		if (count($this->CustomActions) > 0)
			$this->ListOptions->Items["checkbox"]->Visible = TRUE;
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

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
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
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

			// Process custom action first
			$this->ProcessCustomAction();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			$this->SetupBreadcrumb();

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

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore search parms from Session if not searching / reset
			if ($this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue("k_key"));
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
			$sThisKey = strval($objForm->GetValue("k_key"));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id_tienda->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_tienda->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->imagen_url, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->titulo, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->horario, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->telefono, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->dias, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		if ($Keyword == EW_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NULL";
		} elseif ($Keyword == EW_NOT_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = $this->BasicSearch->Keyword;
		$sSearchType = $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id_idioma, $bCtrl); // id_idioma
			$this->UpdateSort($this->imagen_url, $bCtrl); // imagen_url
			$this->UpdateSort($this->titulo, $bCtrl); // titulo
			$this->UpdateSort($this->horario, $bCtrl); // horario
			$this->UpdateSort($this->telefono, $bCtrl); // telefono
			$this->UpdateSort($this->dias, $bCtrl); // dias
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

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id_idioma->setSort("");
				$this->imagen_url->setSort("");
				$this->titulo->setSort("");
				$this->horario->setSort("");
				$this->telefono->setSort("");
				$this->dias->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\"></label>";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		$this->ListOptions->ButtonClass = "btn-small"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . " onclick=\"ew_ClickDelete(this);return ew_ConfirmDelete(ewLanguage.Phrase('DeleteConfirmMsg'), this);\"" . " data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_tienda->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-small"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fbenbeteshlist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

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
		$this->id_tienda->setDbValue($rs->fields('id_tienda'));
		$this->id_idioma->setDbValue($rs->fields('id_idioma'));
		$this->imagen_url->Upload->DbValue = $rs->fields('imagen_url');
		$this->imagen_url->CurrentValue = $this->imagen_url->Upload->DbValue;
		$this->titulo->setDbValue($rs->fields('titulo'));
		$this->horario->setDbValue($rs->fields('horario'));
		$this->telefono->setDbValue($rs->fields('telefono'));
		$this->dias->setDbValue($rs->fields('dias'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_tienda->DbValue = $row['id_tienda'];
		$this->id_idioma->DbValue = $row['id_idioma'];
		$this->imagen_url->Upload->DbValue = $row['imagen_url'];
		$this->titulo->DbValue = $row['titulo'];
		$this->horario->DbValue = $row['horario'];
		$this->telefono->DbValue = $row['telefono'];
		$this->dias->DbValue = $row['dias'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_tienda")) <> "")
			$this->id_tienda->CurrentValue = $this->getKey("id_tienda"); // id_tienda
		else
			$bValidKey = FALSE;

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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id_tienda
		// id_idioma
		// imagen_url
		// titulo
		// horario
		// telefono
		// dias

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_tienda
			$this->id_tienda->ViewValue = $this->id_tienda->CurrentValue;
			$this->id_tienda->ViewCustomAttributes = "";

			// id_idioma
			if (strval($this->id_idioma->CurrentValue) <> "") {
				$sFilterWrk = "`id_idioma`" . ew_SearchString("=", $this->id_idioma->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_idioma`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `idioma`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_idioma, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_idioma->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_idioma->ViewValue = $this->id_idioma->CurrentValue;
				}
			} else {
				$this->id_idioma->ViewValue = NULL;
			}
			$this->id_idioma->ViewCustomAttributes = "";

			// imagen_url
			if (!ew_Empty($this->imagen_url->Upload->DbValue)) {
				$this->imagen_url->ViewValue = $this->imagen_url->Upload->DbValue;
			} else {
				$this->imagen_url->ViewValue = "";
			}
			$this->imagen_url->ViewCustomAttributes = "";

			// titulo
			$this->titulo->ViewValue = $this->titulo->CurrentValue;
			$this->titulo->ViewCustomAttributes = "";

			// horario
			$this->horario->ViewValue = $this->horario->CurrentValue;
			$this->horario->ViewCustomAttributes = "";

			// telefono
			$this->telefono->ViewValue = $this->telefono->CurrentValue;
			$this->telefono->ViewCustomAttributes = "";

			// dias
			$this->dias->ViewValue = $this->dias->CurrentValue;
			$this->dias->ViewCustomAttributes = "";

			// id_idioma
			$this->id_idioma->LinkCustomAttributes = "";
			$this->id_idioma->HrefValue = "";
			$this->id_idioma->TooltipValue = "";

			// imagen_url
			$this->imagen_url->LinkCustomAttributes = "";
			$this->imagen_url->HrefValue = "";
			$this->imagen_url->HrefValue2 = $this->imagen_url->UploadPath . $this->imagen_url->Upload->DbValue;
			$this->imagen_url->TooltipValue = "";

			// titulo
			$this->titulo->LinkCustomAttributes = "";
			$this->titulo->HrefValue = "";
			$this->titulo->TooltipValue = "";

			// horario
			$this->horario->LinkCustomAttributes = "";
			$this->horario->HrefValue = "";
			$this->horario->TooltipValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";
			$this->telefono->TooltipValue = "";

			// dias
			$this->dias->LinkCustomAttributes = "";
			$this->dias->HrefValue = "";
			$this->dias->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($benbetesh_list)) $benbetesh_list = new cbenbetesh_list();

// Page init
$benbetesh_list->Page_Init();

// Page main
$benbetesh_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$benbetesh_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var benbetesh_list = new ew_Page("benbetesh_list");
benbetesh_list.PageID = "list"; // Page ID
var EW_PAGE_ID = benbetesh_list.PageID; // For backward compatibility

// Form object
var fbenbeteshlist = new ew_Form("fbenbeteshlist");
fbenbeteshlist.FormKeyCountName = '<?php echo $benbetesh_list->FormKeyCountName ?>';

// Form_CustomValidate event
fbenbeteshlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbenbeteshlist.ValidateRequired = true;
<?php } else { ?>
fbenbeteshlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fbenbeteshlist.Lists["x_id_idioma"] = {"LinkField":"x_id_idioma","Ajax":null,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fbenbeteshlistsrch = new ew_Form("fbenbeteshlistsrch");
</script>
<style type="text/css">

/* main table preview row color */
.ewTablePreviewRow {
	background-color: #FFFFFF; /* preview row color */
}
.ewPreviewRowImage {
    min-width: 9px; /* for Chrome */
}
</style>
<div id="ewPreview" class="hide"><ul class="nav nav-tabs"></ul><div class="tab-content"><div class="tab-pane fade"></div></div></div>
<script type="text/javascript" src="phpjs/ewpreview.min.js"></script>
<script type="text/javascript">
var EW_PREVIEW_PLACEMENT = "left";
var EW_PREVIEW_SINGLE_ROW = false;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php if ($benbetesh_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $benbetesh_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$benbetesh_list->TotalRecs = $benbetesh->SelectRecordCount();
	} else {
		if ($benbetesh_list->Recordset = $benbetesh_list->LoadRecordset())
			$benbetesh_list->TotalRecs = $benbetesh_list->Recordset->RecordCount();
	}
	$benbetesh_list->StartRec = 1;
	if ($benbetesh_list->DisplayRecs <= 0 || ($benbetesh->Export <> "" && $benbetesh->ExportAll)) // Display all records
		$benbetesh_list->DisplayRecs = $benbetesh_list->TotalRecs;
	if (!($benbetesh->Export <> "" && $benbetesh->ExportAll))
		$benbetesh_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$benbetesh_list->Recordset = $benbetesh_list->LoadRecordset($benbetesh_list->StartRec-1, $benbetesh_list->DisplayRecs);
$benbetesh_list->RenderOtherOptions();
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($benbetesh->Export == "" && $benbetesh->CurrentAction == "") { ?>
<form name="fbenbeteshlistsrch" id="fbenbeteshlistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<div class="accordion ewDisplayTable ewSearchTable" id="fbenbeteshlistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fbenbeteshlistsrch_SearchGroup" href="#fbenbeteshlistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fbenbeteshlistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fbenbeteshlistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="benbetesh">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($benbetesh_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $benbetesh_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
	</div>
</div>
<div id="xsr_2" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($benbetesh_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($benbetesh_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($benbetesh_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
			</div>
		</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $benbetesh_list->ShowPageHeader(); ?>
<?php
$benbetesh_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<form name="fbenbeteshlist" id="fbenbeteshlist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="benbetesh">
<div id="gmp_benbetesh" class="ewGridMiddlePanel">
<?php if ($benbetesh_list->TotalRecs > 0) { ?>
<table id="tbl_benbeteshlist" class="ewTable ewTableSeparate">
<?php echo $benbetesh->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$benbetesh_list->RenderListOptions();

// Render list options (header, left)
$benbetesh_list->ListOptions->Render("header", "left");
?>
<?php if ($benbetesh->id_idioma->Visible) { // id_idioma ?>
	<?php if ($benbetesh->SortUrl($benbetesh->id_idioma) == "") { ?>
		<td><div id="elh_benbetesh_id_idioma" class="benbetesh_id_idioma"><div class="ewTableHeaderCaption"><?php echo $benbetesh->id_idioma->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $benbetesh->SortUrl($benbetesh->id_idioma) ?>',2);"><div id="elh_benbetesh_id_idioma" class="benbetesh_id_idioma">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $benbetesh->id_idioma->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($benbetesh->id_idioma->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($benbetesh->id_idioma->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($benbetesh->imagen_url->Visible) { // imagen_url ?>
	<?php if ($benbetesh->SortUrl($benbetesh->imagen_url) == "") { ?>
		<td><div id="elh_benbetesh_imagen_url" class="benbetesh_imagen_url"><div class="ewTableHeaderCaption"><?php echo $benbetesh->imagen_url->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $benbetesh->SortUrl($benbetesh->imagen_url) ?>',2);"><div id="elh_benbetesh_imagen_url" class="benbetesh_imagen_url">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $benbetesh->imagen_url->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($benbetesh->imagen_url->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($benbetesh->imagen_url->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($benbetesh->titulo->Visible) { // titulo ?>
	<?php if ($benbetesh->SortUrl($benbetesh->titulo) == "") { ?>
		<td><div id="elh_benbetesh_titulo" class="benbetesh_titulo"><div class="ewTableHeaderCaption"><?php echo $benbetesh->titulo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $benbetesh->SortUrl($benbetesh->titulo) ?>',2);"><div id="elh_benbetesh_titulo" class="benbetesh_titulo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $benbetesh->titulo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($benbetesh->titulo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($benbetesh->titulo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($benbetesh->horario->Visible) { // horario ?>
	<?php if ($benbetesh->SortUrl($benbetesh->horario) == "") { ?>
		<td><div id="elh_benbetesh_horario" class="benbetesh_horario"><div class="ewTableHeaderCaption"><?php echo $benbetesh->horario->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $benbetesh->SortUrl($benbetesh->horario) ?>',2);"><div id="elh_benbetesh_horario" class="benbetesh_horario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $benbetesh->horario->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($benbetesh->horario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($benbetesh->horario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($benbetesh->telefono->Visible) { // telefono ?>
	<?php if ($benbetesh->SortUrl($benbetesh->telefono) == "") { ?>
		<td><div id="elh_benbetesh_telefono" class="benbetesh_telefono"><div class="ewTableHeaderCaption"><?php echo $benbetesh->telefono->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $benbetesh->SortUrl($benbetesh->telefono) ?>',2);"><div id="elh_benbetesh_telefono" class="benbetesh_telefono">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $benbetesh->telefono->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($benbetesh->telefono->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($benbetesh->telefono->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($benbetesh->dias->Visible) { // dias ?>
	<?php if ($benbetesh->SortUrl($benbetesh->dias) == "") { ?>
		<td><div id="elh_benbetesh_dias" class="benbetesh_dias"><div class="ewTableHeaderCaption"><?php echo $benbetesh->dias->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $benbetesh->SortUrl($benbetesh->dias) ?>',2);"><div id="elh_benbetesh_dias" class="benbetesh_dias">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $benbetesh->dias->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($benbetesh->dias->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($benbetesh->dias->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$benbetesh_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($benbetesh->ExportAll && $benbetesh->Export <> "") {
	$benbetesh_list->StopRec = $benbetesh_list->TotalRecs;
} else {

	// Set the last record to display
	if ($benbetesh_list->TotalRecs > $benbetesh_list->StartRec + $benbetesh_list->DisplayRecs - 1)
		$benbetesh_list->StopRec = $benbetesh_list->StartRec + $benbetesh_list->DisplayRecs - 1;
	else
		$benbetesh_list->StopRec = $benbetesh_list->TotalRecs;
}
$benbetesh_list->RecCnt = $benbetesh_list->StartRec - 1;
if ($benbetesh_list->Recordset && !$benbetesh_list->Recordset->EOF) {
	$benbetesh_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $benbetesh_list->StartRec > 1)
		$benbetesh_list->Recordset->Move($benbetesh_list->StartRec - 1);
} elseif (!$benbetesh->AllowAddDeleteRow && $benbetesh_list->StopRec == 0) {
	$benbetesh_list->StopRec = $benbetesh->GridAddRowCount;
}

// Initialize aggregate
$benbetesh->RowType = EW_ROWTYPE_AGGREGATEINIT;
$benbetesh->ResetAttrs();
$benbetesh_list->RenderRow();
while ($benbetesh_list->RecCnt < $benbetesh_list->StopRec) {
	$benbetesh_list->RecCnt++;
	if (intval($benbetesh_list->RecCnt) >= intval($benbetesh_list->StartRec)) {
		$benbetesh_list->RowCnt++;

		// Set up key count
		$benbetesh_list->KeyCount = $benbetesh_list->RowIndex;

		// Init row class and style
		$benbetesh->ResetAttrs();
		$benbetesh->CssClass = "";
		if ($benbetesh->CurrentAction == "gridadd") {
		} else {
			$benbetesh_list->LoadRowValues($benbetesh_list->Recordset); // Load row values
		}
		$benbetesh->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$benbetesh->RowAttrs = array_merge($benbetesh->RowAttrs, array('data-rowindex'=>$benbetesh_list->RowCnt, 'id'=>'r' . $benbetesh_list->RowCnt . '_benbetesh', 'data-rowtype'=>$benbetesh->RowType));

		// Render row
		$benbetesh_list->RenderRow();

		// Render list options
		$benbetesh_list->RenderListOptions();
?>
	<tr<?php echo $benbetesh->RowAttributes() ?>>
<?php

// Render list options (body, left)
$benbetesh_list->ListOptions->Render("body", "left", $benbetesh_list->RowCnt);
?>
	<?php if ($benbetesh->id_idioma->Visible) { // id_idioma ?>
		<td<?php echo $benbetesh->id_idioma->CellAttributes() ?>>
<span<?php echo $benbetesh->id_idioma->ViewAttributes() ?>>
<?php echo $benbetesh->id_idioma->ListViewValue() ?></span>
<a id="<?php echo $benbetesh_list->PageObjName . "_row_" . $benbetesh_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($benbetesh->imagen_url->Visible) { // imagen_url ?>
		<td<?php echo $benbetesh->imagen_url->CellAttributes() ?>>
<span<?php echo $benbetesh->imagen_url->ViewAttributes() ?>>
<?php if ($benbetesh->imagen_url->LinkAttributes() <> "") { ?>
<?php if (!empty($benbetesh->imagen_url->Upload->DbValue)) { ?>
<?php echo $benbetesh->imagen_url->ListViewValue() ?>
<?php } elseif (!in_array($benbetesh->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($benbetesh->imagen_url->Upload->DbValue)) { ?>
<?php echo $benbetesh->imagen_url->ListViewValue() ?>
<?php } elseif (!in_array($benbetesh->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</td>
	<?php } ?>
	<?php if ($benbetesh->titulo->Visible) { // titulo ?>
		<td<?php echo $benbetesh->titulo->CellAttributes() ?>>
<span<?php echo $benbetesh->titulo->ViewAttributes() ?>>
<?php echo $benbetesh->titulo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($benbetesh->horario->Visible) { // horario ?>
		<td<?php echo $benbetesh->horario->CellAttributes() ?>>
<span<?php echo $benbetesh->horario->ViewAttributes() ?>>
<?php echo $benbetesh->horario->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($benbetesh->telefono->Visible) { // telefono ?>
		<td<?php echo $benbetesh->telefono->CellAttributes() ?>>
<span<?php echo $benbetesh->telefono->ViewAttributes() ?>>
<?php echo $benbetesh->telefono->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($benbetesh->dias->Visible) { // dias ?>
		<td<?php echo $benbetesh->dias->CellAttributes() ?>>
<span<?php echo $benbetesh->dias->ViewAttributes() ?>>
<?php echo $benbetesh->dias->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$benbetesh_list->ListOptions->Render("body", "right", $benbetesh_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($benbetesh->CurrentAction <> "gridadd")
		$benbetesh_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($benbetesh->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($benbetesh_list->Recordset)
	$benbetesh_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($benbetesh->CurrentAction <> "gridadd" && $benbetesh->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($benbetesh_list->Pager)) $benbetesh_list->Pager = new cPrevNextPager($benbetesh_list->StartRec, $benbetesh_list->DisplayRecs, $benbetesh_list->TotalRecs) ?>
<?php if ($benbetesh_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($benbetesh_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $benbetesh_list->PageUrl() ?>start=<?php echo $benbetesh_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($benbetesh_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $benbetesh_list->PageUrl() ?>start=<?php echo $benbetesh_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $benbetesh_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($benbetesh_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $benbetesh_list->PageUrl() ?>start=<?php echo $benbetesh_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($benbetesh_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $benbetesh_list->PageUrl() ?>start=<?php echo $benbetesh_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $benbetesh_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $benbetesh_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $benbetesh_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $benbetesh_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($benbetesh_list->SearchWhere == "0=101") { ?>
	<p><?php echo $Language->Phrase("EnterSearchCriteria") ?></p>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
	<?php } ?>
<?php } ?>
</td>
</tr></table>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($benbetesh_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
</td></tr></table>
<script type="text/javascript">
fbenbeteshlistsrch.Init();
fbenbeteshlist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$benbetesh_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$benbetesh_list->Page_Terminate();
?>
