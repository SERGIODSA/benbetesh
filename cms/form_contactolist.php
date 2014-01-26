<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "form_contactoinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$form_contacto_list = NULL; // Initialize page object first

class cform_contacto_list extends cform_contacto {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'form_contacto';

	// Page object name
	var $PageObjName = 'form_contacto_list';

	// Grid form hidden field names
	var $FormName = 'fform_contactolist';
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

		// Table object (form_contacto)
		if (!isset($GLOBALS["form_contacto"]) || get_class($GLOBALS["form_contacto"]) == "cform_contacto") {
			$GLOBALS["form_contacto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["form_contacto"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "form_contactoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "form_contactodelete.php";
		$this->MultiUpdateUrl = "form_contactoupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'form_contacto', TRUE);

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
			$this->id_contacto->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_contacto->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->nombre, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->empresa, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->_email, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->telefono, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->pais, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->mensaje, $Keyword);
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
			$this->UpdateSort($this->nombre, $bCtrl); // nombre
			$this->UpdateSort($this->empresa, $bCtrl); // empresa
			$this->UpdateSort($this->_email, $bCtrl); // email
			$this->UpdateSort($this->telefono, $bCtrl); // telefono
			$this->UpdateSort($this->pais, $bCtrl); // pais
			$this->UpdateSort($this->mensaje, $bCtrl); // mensaje
			$this->UpdateSort($this->fecha_creacion, $bCtrl); // fecha_creacion
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
				$this->nombre->setSort("");
				$this->empresa->setSort("");
				$this->_email->setSort("");
				$this->telefono->setSort("");
				$this->pais->setSort("");
				$this->mensaje->setSort("");
				$this->fecha_creacion->setSort("");
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

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . " onclick=\"ew_ClickDelete(this);return ew_ConfirmDelete(ewLanguage.Phrase('DeleteConfirmMsg'), this);\"" . " data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_contacto->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fform_contactolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->id_contacto->setDbValue($rs->fields('id_contacto'));
		$this->id_idioma->setDbValue($rs->fields('id_idioma'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->empresa->setDbValue($rs->fields('empresa'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->telefono->setDbValue($rs->fields('telefono'));
		$this->pais->setDbValue($rs->fields('pais'));
		$this->mensaje->setDbValue($rs->fields('mensaje'));
		$this->fecha_creacion->setDbValue($rs->fields('fecha_creacion'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_contacto->DbValue = $row['id_contacto'];
		$this->id_idioma->DbValue = $row['id_idioma'];
		$this->nombre->DbValue = $row['nombre'];
		$this->empresa->DbValue = $row['empresa'];
		$this->_email->DbValue = $row['email'];
		$this->telefono->DbValue = $row['telefono'];
		$this->pais->DbValue = $row['pais'];
		$this->mensaje->DbValue = $row['mensaje'];
		$this->fecha_creacion->DbValue = $row['fecha_creacion'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_contacto")) <> "")
			$this->id_contacto->CurrentValue = $this->getKey("id_contacto"); // id_contacto
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
		// id_contacto

		$this->id_contacto->CellCssStyle = "white-space: nowrap;";

		// id_idioma
		$this->id_idioma->CellCssStyle = "white-space: nowrap;";

		// nombre
		// empresa
		// email
		// telefono
		// pais
		// mensaje
		// fecha_creacion

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// empresa
			$this->empresa->ViewValue = $this->empresa->CurrentValue;
			$this->empresa->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewCustomAttributes = "";

			// telefono
			$this->telefono->ViewValue = $this->telefono->CurrentValue;
			$this->telefono->ViewCustomAttributes = "";

			// pais
			$this->pais->ViewValue = $this->pais->CurrentValue;
			$this->pais->ViewCustomAttributes = "";

			// mensaje
			$this->mensaje->ViewValue = $this->mensaje->CurrentValue;
			$this->mensaje->ViewCustomAttributes = "";

			// fecha_creacion
			$this->fecha_creacion->ViewValue = $this->fecha_creacion->CurrentValue;
			$this->fecha_creacion->ViewValue = ew_FormatDateTime($this->fecha_creacion->ViewValue, 7);
			$this->fecha_creacion->ViewCustomAttributes = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// empresa
			$this->empresa->LinkCustomAttributes = "";
			$this->empresa->HrefValue = "";
			$this->empresa->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";
			$this->telefono->TooltipValue = "";

			// pais
			$this->pais->LinkCustomAttributes = "";
			$this->pais->HrefValue = "";
			$this->pais->TooltipValue = "";

			// mensaje
			$this->mensaje->LinkCustomAttributes = "";
			$this->mensaje->HrefValue = "";
			$this->mensaje->TooltipValue = "";

			// fecha_creacion
			$this->fecha_creacion->LinkCustomAttributes = "";
			$this->fecha_creacion->HrefValue = "";
			$this->fecha_creacion->TooltipValue = "";
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
if (!isset($form_contacto_list)) $form_contacto_list = new cform_contacto_list();

// Page init
$form_contacto_list->Page_Init();

// Page main
$form_contacto_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$form_contacto_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var form_contacto_list = new ew_Page("form_contacto_list");
form_contacto_list.PageID = "list"; // Page ID
var EW_PAGE_ID = form_contacto_list.PageID; // For backward compatibility

// Form object
var fform_contactolist = new ew_Form("fform_contactolist");
fform_contactolist.FormKeyCountName = '<?php echo $form_contacto_list->FormKeyCountName ?>';

// Form_CustomValidate event
fform_contactolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fform_contactolist.ValidateRequired = true;
<?php } else { ?>
fform_contactolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fform_contactolistsrch = new ew_Form("fform_contactolistsrch");
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
<?php if ($form_contacto_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $form_contacto_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$form_contacto_list->TotalRecs = $form_contacto->SelectRecordCount();
	} else {
		if ($form_contacto_list->Recordset = $form_contacto_list->LoadRecordset())
			$form_contacto_list->TotalRecs = $form_contacto_list->Recordset->RecordCount();
	}
	$form_contacto_list->StartRec = 1;
	if ($form_contacto_list->DisplayRecs <= 0 || ($form_contacto->Export <> "" && $form_contacto->ExportAll)) // Display all records
		$form_contacto_list->DisplayRecs = $form_contacto_list->TotalRecs;
	if (!($form_contacto->Export <> "" && $form_contacto->ExportAll))
		$form_contacto_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$form_contacto_list->Recordset = $form_contacto_list->LoadRecordset($form_contacto_list->StartRec-1, $form_contacto_list->DisplayRecs);
$form_contacto_list->RenderOtherOptions();
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($form_contacto->Export == "" && $form_contacto->CurrentAction == "") { ?>
<form name="fform_contactolistsrch" id="fform_contactolistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<div class="accordion ewDisplayTable ewSearchTable" id="fform_contactolistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fform_contactolistsrch_SearchGroup" href="#fform_contactolistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fform_contactolistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fform_contactolistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="form_contacto">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($form_contacto_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $form_contacto_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
	</div>
</div>
<div id="xsr_2" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($form_contacto_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($form_contacto_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($form_contacto_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $form_contacto_list->ShowPageHeader(); ?>
<?php
$form_contacto_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<form name="fform_contactolist" id="fform_contactolist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="form_contacto">
<div id="gmp_form_contacto" class="ewGridMiddlePanel">
<?php if ($form_contacto_list->TotalRecs > 0) { ?>
<table id="tbl_form_contactolist" class="ewTable ewTableSeparate">
<?php echo $form_contacto->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$form_contacto_list->RenderListOptions();

// Render list options (header, left)
$form_contacto_list->ListOptions->Render("header", "left");
?>
<?php if ($form_contacto->nombre->Visible) { // nombre ?>
	<?php if ($form_contacto->SortUrl($form_contacto->nombre) == "") { ?>
		<td><div id="elh_form_contacto_nombre" class="form_contacto_nombre"><div class="ewTableHeaderCaption"><?php echo $form_contacto->nombre->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $form_contacto->SortUrl($form_contacto->nombre) ?>',2);"><div id="elh_form_contacto_nombre" class="form_contacto_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $form_contacto->nombre->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($form_contacto->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($form_contacto->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($form_contacto->empresa->Visible) { // empresa ?>
	<?php if ($form_contacto->SortUrl($form_contacto->empresa) == "") { ?>
		<td><div id="elh_form_contacto_empresa" class="form_contacto_empresa"><div class="ewTableHeaderCaption"><?php echo $form_contacto->empresa->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $form_contacto->SortUrl($form_contacto->empresa) ?>',2);"><div id="elh_form_contacto_empresa" class="form_contacto_empresa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $form_contacto->empresa->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($form_contacto->empresa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($form_contacto->empresa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($form_contacto->_email->Visible) { // email ?>
	<?php if ($form_contacto->SortUrl($form_contacto->_email) == "") { ?>
		<td><div id="elh_form_contacto__email" class="form_contacto__email"><div class="ewTableHeaderCaption"><?php echo $form_contacto->_email->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $form_contacto->SortUrl($form_contacto->_email) ?>',2);"><div id="elh_form_contacto__email" class="form_contacto__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $form_contacto->_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($form_contacto->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($form_contacto->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($form_contacto->telefono->Visible) { // telefono ?>
	<?php if ($form_contacto->SortUrl($form_contacto->telefono) == "") { ?>
		<td><div id="elh_form_contacto_telefono" class="form_contacto_telefono"><div class="ewTableHeaderCaption"><?php echo $form_contacto->telefono->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $form_contacto->SortUrl($form_contacto->telefono) ?>',2);"><div id="elh_form_contacto_telefono" class="form_contacto_telefono">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $form_contacto->telefono->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($form_contacto->telefono->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($form_contacto->telefono->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($form_contacto->pais->Visible) { // pais ?>
	<?php if ($form_contacto->SortUrl($form_contacto->pais) == "") { ?>
		<td><div id="elh_form_contacto_pais" class="form_contacto_pais"><div class="ewTableHeaderCaption"><?php echo $form_contacto->pais->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $form_contacto->SortUrl($form_contacto->pais) ?>',2);"><div id="elh_form_contacto_pais" class="form_contacto_pais">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $form_contacto->pais->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($form_contacto->pais->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($form_contacto->pais->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($form_contacto->mensaje->Visible) { // mensaje ?>
	<?php if ($form_contacto->SortUrl($form_contacto->mensaje) == "") { ?>
		<td><div id="elh_form_contacto_mensaje" class="form_contacto_mensaje"><div class="ewTableHeaderCaption"><?php echo $form_contacto->mensaje->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $form_contacto->SortUrl($form_contacto->mensaje) ?>',2);"><div id="elh_form_contacto_mensaje" class="form_contacto_mensaje">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $form_contacto->mensaje->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($form_contacto->mensaje->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($form_contacto->mensaje->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($form_contacto->fecha_creacion->Visible) { // fecha_creacion ?>
	<?php if ($form_contacto->SortUrl($form_contacto->fecha_creacion) == "") { ?>
		<td><div id="elh_form_contacto_fecha_creacion" class="form_contacto_fecha_creacion"><div class="ewTableHeaderCaption"><?php echo $form_contacto->fecha_creacion->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $form_contacto->SortUrl($form_contacto->fecha_creacion) ?>',2);"><div id="elh_form_contacto_fecha_creacion" class="form_contacto_fecha_creacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $form_contacto->fecha_creacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($form_contacto->fecha_creacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($form_contacto->fecha_creacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$form_contacto_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($form_contacto->ExportAll && $form_contacto->Export <> "") {
	$form_contacto_list->StopRec = $form_contacto_list->TotalRecs;
} else {

	// Set the last record to display
	if ($form_contacto_list->TotalRecs > $form_contacto_list->StartRec + $form_contacto_list->DisplayRecs - 1)
		$form_contacto_list->StopRec = $form_contacto_list->StartRec + $form_contacto_list->DisplayRecs - 1;
	else
		$form_contacto_list->StopRec = $form_contacto_list->TotalRecs;
}
$form_contacto_list->RecCnt = $form_contacto_list->StartRec - 1;
if ($form_contacto_list->Recordset && !$form_contacto_list->Recordset->EOF) {
	$form_contacto_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $form_contacto_list->StartRec > 1)
		$form_contacto_list->Recordset->Move($form_contacto_list->StartRec - 1);
} elseif (!$form_contacto->AllowAddDeleteRow && $form_contacto_list->StopRec == 0) {
	$form_contacto_list->StopRec = $form_contacto->GridAddRowCount;
}

// Initialize aggregate
$form_contacto->RowType = EW_ROWTYPE_AGGREGATEINIT;
$form_contacto->ResetAttrs();
$form_contacto_list->RenderRow();
while ($form_contacto_list->RecCnt < $form_contacto_list->StopRec) {
	$form_contacto_list->RecCnt++;
	if (intval($form_contacto_list->RecCnt) >= intval($form_contacto_list->StartRec)) {
		$form_contacto_list->RowCnt++;

		// Set up key count
		$form_contacto_list->KeyCount = $form_contacto_list->RowIndex;

		// Init row class and style
		$form_contacto->ResetAttrs();
		$form_contacto->CssClass = "";
		if ($form_contacto->CurrentAction == "gridadd") {
		} else {
			$form_contacto_list->LoadRowValues($form_contacto_list->Recordset); // Load row values
		}
		$form_contacto->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$form_contacto->RowAttrs = array_merge($form_contacto->RowAttrs, array('data-rowindex'=>$form_contacto_list->RowCnt, 'id'=>'r' . $form_contacto_list->RowCnt . '_form_contacto', 'data-rowtype'=>$form_contacto->RowType));

		// Render row
		$form_contacto_list->RenderRow();

		// Render list options
		$form_contacto_list->RenderListOptions();
?>
	<tr<?php echo $form_contacto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$form_contacto_list->ListOptions->Render("body", "left", $form_contacto_list->RowCnt);
?>
	<?php if ($form_contacto->nombre->Visible) { // nombre ?>
		<td<?php echo $form_contacto->nombre->CellAttributes() ?>>
<span<?php echo $form_contacto->nombre->ViewAttributes() ?>>
<?php echo $form_contacto->nombre->ListViewValue() ?></span>
<a id="<?php echo $form_contacto_list->PageObjName . "_row_" . $form_contacto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($form_contacto->empresa->Visible) { // empresa ?>
		<td<?php echo $form_contacto->empresa->CellAttributes() ?>>
<span<?php echo $form_contacto->empresa->ViewAttributes() ?>>
<?php echo $form_contacto->empresa->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($form_contacto->_email->Visible) { // email ?>
		<td<?php echo $form_contacto->_email->CellAttributes() ?>>
<span<?php echo $form_contacto->_email->ViewAttributes() ?>>
<?php echo $form_contacto->_email->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($form_contacto->telefono->Visible) { // telefono ?>
		<td<?php echo $form_contacto->telefono->CellAttributes() ?>>
<span<?php echo $form_contacto->telefono->ViewAttributes() ?>>
<?php echo $form_contacto->telefono->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($form_contacto->pais->Visible) { // pais ?>
		<td<?php echo $form_contacto->pais->CellAttributes() ?>>
<span<?php echo $form_contacto->pais->ViewAttributes() ?>>
<?php echo $form_contacto->pais->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($form_contacto->mensaje->Visible) { // mensaje ?>
		<td<?php echo $form_contacto->mensaje->CellAttributes() ?>>
<span<?php echo $form_contacto->mensaje->ViewAttributes() ?>>
<?php echo $form_contacto->mensaje->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($form_contacto->fecha_creacion->Visible) { // fecha_creacion ?>
		<td<?php echo $form_contacto->fecha_creacion->CellAttributes() ?>>
<span<?php echo $form_contacto->fecha_creacion->ViewAttributes() ?>>
<?php echo $form_contacto->fecha_creacion->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$form_contacto_list->ListOptions->Render("body", "right", $form_contacto_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($form_contacto->CurrentAction <> "gridadd")
		$form_contacto_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($form_contacto->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($form_contacto_list->Recordset)
	$form_contacto_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($form_contacto->CurrentAction <> "gridadd" && $form_contacto->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($form_contacto_list->Pager)) $form_contacto_list->Pager = new cPrevNextPager($form_contacto_list->StartRec, $form_contacto_list->DisplayRecs, $form_contacto_list->TotalRecs) ?>
<?php if ($form_contacto_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($form_contacto_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $form_contacto_list->PageUrl() ?>start=<?php echo $form_contacto_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($form_contacto_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $form_contacto_list->PageUrl() ?>start=<?php echo $form_contacto_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $form_contacto_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($form_contacto_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $form_contacto_list->PageUrl() ?>start=<?php echo $form_contacto_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($form_contacto_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $form_contacto_list->PageUrl() ?>start=<?php echo $form_contacto_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $form_contacto_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $form_contacto_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $form_contacto_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $form_contacto_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($form_contacto_list->SearchWhere == "0=101") { ?>
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
	foreach ($form_contacto_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
</td></tr></table>
<script type="text/javascript">
fform_contactolistsrch.Init();
fform_contactolist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$form_contacto_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$form_contacto_list->Page_Terminate();
?>
