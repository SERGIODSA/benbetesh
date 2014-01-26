<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "ss_indexinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$ss_index_list = NULL; // Initialize page object first

class css_index_list extends css_index {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'ss_index';

	// Page object name
	var $PageObjName = 'ss_index_list';

	// Grid form hidden field names
	var $FormName = 'fss_indexlist';
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

		// Table object (ss_index)
		if (!isset($GLOBALS["ss_index"]) || get_class($GLOBALS["ss_index"]) == "css_index") {
			$GLOBALS["ss_index"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ss_index"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "ss_indexadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "ss_indexdelete.php";
		$this->MultiUpdateUrl = "ss_indexupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ss_index', TRUE);

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
			$this->id_ssindex->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_ssindex->FormValue))
				return FALSE;
		}
		return TRUE;
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
			$this->UpdateSort($this->slideshow1_url, $bCtrl); // slideshow1_url
			$this->UpdateSort($this->slideshow2_url, $bCtrl); // slideshow2_url
			$this->UpdateSort($this->slideshow3_url, $bCtrl); // slideshow3_url
			$this->UpdateSort($this->slideshow4_url, $bCtrl); // slideshow4_url
			$this->UpdateSort($this->slideshow5_url, $bCtrl); // slideshow5_url
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

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id_idioma->setSort("");
				$this->slideshow1_url->setSort("");
				$this->slideshow2_url->setSort("");
				$this->slideshow3_url->setSort("");
				$this->slideshow4_url->setSort("");
				$this->slideshow5_url->setSort("");
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

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_ssindex->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fss_indexlist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->id_ssindex->setDbValue($rs->fields('id_ssindex'));
		$this->id_idioma->setDbValue($rs->fields('id_idioma'));
		$this->slideshow1_url->Upload->DbValue = $rs->fields('slideshow1_url');
		$this->slideshow1_url->CurrentValue = $this->slideshow1_url->Upload->DbValue;
		$this->slideshow2_url->Upload->DbValue = $rs->fields('slideshow2_url');
		$this->slideshow2_url->CurrentValue = $this->slideshow2_url->Upload->DbValue;
		$this->slideshow3_url->Upload->DbValue = $rs->fields('slideshow3_url');
		$this->slideshow3_url->CurrentValue = $this->slideshow3_url->Upload->DbValue;
		$this->slideshow4_url->Upload->DbValue = $rs->fields('slideshow4_url');
		$this->slideshow4_url->CurrentValue = $this->slideshow4_url->Upload->DbValue;
		$this->slideshow5_url->Upload->DbValue = $rs->fields('slideshow5_url');
		$this->slideshow5_url->CurrentValue = $this->slideshow5_url->Upload->DbValue;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_ssindex->DbValue = $row['id_ssindex'];
		$this->id_idioma->DbValue = $row['id_idioma'];
		$this->slideshow1_url->Upload->DbValue = $row['slideshow1_url'];
		$this->slideshow2_url->Upload->DbValue = $row['slideshow2_url'];
		$this->slideshow3_url->Upload->DbValue = $row['slideshow3_url'];
		$this->slideshow4_url->Upload->DbValue = $row['slideshow4_url'];
		$this->slideshow5_url->Upload->DbValue = $row['slideshow5_url'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_ssindex")) <> "")
			$this->id_ssindex->CurrentValue = $this->getKey("id_ssindex"); // id_ssindex
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
		// id_ssindex
		// id_idioma
		// slideshow1_url
		// slideshow2_url
		// slideshow3_url
		// slideshow4_url
		// slideshow5_url

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_idioma
			$this->id_idioma->ViewValue = $this->id_idioma->CurrentValue;
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

			// slideshow1_url
			if (!ew_Empty($this->slideshow1_url->Upload->DbValue)) {
				$this->slideshow1_url->ViewValue = $this->slideshow1_url->Upload->DbValue;
			} else {
				$this->slideshow1_url->ViewValue = "";
			}
			$this->slideshow1_url->ViewCustomAttributes = "";

			// slideshow2_url
			if (!ew_Empty($this->slideshow2_url->Upload->DbValue)) {
				$this->slideshow2_url->ViewValue = $this->slideshow2_url->Upload->DbValue;
			} else {
				$this->slideshow2_url->ViewValue = "";
			}
			$this->slideshow2_url->ViewCustomAttributes = "";

			// slideshow3_url
			if (!ew_Empty($this->slideshow3_url->Upload->DbValue)) {
				$this->slideshow3_url->ViewValue = $this->slideshow3_url->Upload->DbValue;
			} else {
				$this->slideshow3_url->ViewValue = "";
			}
			$this->slideshow3_url->ViewCustomAttributes = "";

			// slideshow4_url
			if (!ew_Empty($this->slideshow4_url->Upload->DbValue)) {
				$this->slideshow4_url->ViewValue = $this->slideshow4_url->Upload->DbValue;
			} else {
				$this->slideshow4_url->ViewValue = "";
			}
			$this->slideshow4_url->ViewCustomAttributes = "";

			// slideshow5_url
			if (!ew_Empty($this->slideshow5_url->Upload->DbValue)) {
				$this->slideshow5_url->ViewValue = $this->slideshow5_url->Upload->DbValue;
			} else {
				$this->slideshow5_url->ViewValue = "";
			}
			$this->slideshow5_url->ViewCustomAttributes = "";

			// id_idioma
			$this->id_idioma->LinkCustomAttributes = "";
			$this->id_idioma->HrefValue = "";
			$this->id_idioma->TooltipValue = "";

			// slideshow1_url
			$this->slideshow1_url->LinkCustomAttributes = "";
			$this->slideshow1_url->HrefValue = "";
			$this->slideshow1_url->HrefValue2 = $this->slideshow1_url->UploadPath . $this->slideshow1_url->Upload->DbValue;
			$this->slideshow1_url->TooltipValue = "";

			// slideshow2_url
			$this->slideshow2_url->LinkCustomAttributes = "";
			$this->slideshow2_url->HrefValue = "";
			$this->slideshow2_url->HrefValue2 = $this->slideshow2_url->UploadPath . $this->slideshow2_url->Upload->DbValue;
			$this->slideshow2_url->TooltipValue = "";

			// slideshow3_url
			$this->slideshow3_url->LinkCustomAttributes = "";
			$this->slideshow3_url->HrefValue = "";
			$this->slideshow3_url->HrefValue2 = $this->slideshow3_url->UploadPath . $this->slideshow3_url->Upload->DbValue;
			$this->slideshow3_url->TooltipValue = "";

			// slideshow4_url
			$this->slideshow4_url->LinkCustomAttributes = "";
			$this->slideshow4_url->HrefValue = "";
			$this->slideshow4_url->HrefValue2 = $this->slideshow4_url->UploadPath . $this->slideshow4_url->Upload->DbValue;
			$this->slideshow4_url->TooltipValue = "";

			// slideshow5_url
			$this->slideshow5_url->LinkCustomAttributes = "";
			$this->slideshow5_url->HrefValue = "";
			$this->slideshow5_url->HrefValue2 = $this->slideshow5_url->UploadPath . $this->slideshow5_url->Upload->DbValue;
			$this->slideshow5_url->TooltipValue = "";
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
if (!isset($ss_index_list)) $ss_index_list = new css_index_list();

// Page init
$ss_index_list->Page_Init();

// Page main
$ss_index_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ss_index_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var ss_index_list = new ew_Page("ss_index_list");
ss_index_list.PageID = "list"; // Page ID
var EW_PAGE_ID = ss_index_list.PageID; // For backward compatibility

// Form object
var fss_indexlist = new ew_Form("fss_indexlist");
fss_indexlist.FormKeyCountName = '<?php echo $ss_index_list->FormKeyCountName ?>';

// Form_CustomValidate event
fss_indexlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fss_indexlist.ValidateRequired = true;
<?php } else { ?>
fss_indexlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fss_indexlist.Lists["x_id_idioma"] = {"LinkField":"x_id_idioma","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
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
<?php if ($ss_index_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $ss_index_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$ss_index_list->TotalRecs = $ss_index->SelectRecordCount();
	} else {
		if ($ss_index_list->Recordset = $ss_index_list->LoadRecordset())
			$ss_index_list->TotalRecs = $ss_index_list->Recordset->RecordCount();
	}
	$ss_index_list->StartRec = 1;
	if ($ss_index_list->DisplayRecs <= 0 || ($ss_index->Export <> "" && $ss_index->ExportAll)) // Display all records
		$ss_index_list->DisplayRecs = $ss_index_list->TotalRecs;
	if (!($ss_index->Export <> "" && $ss_index->ExportAll))
		$ss_index_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$ss_index_list->Recordset = $ss_index_list->LoadRecordset($ss_index_list->StartRec-1, $ss_index_list->DisplayRecs);
$ss_index_list->RenderOtherOptions();
?>
<?php $ss_index_list->ShowPageHeader(); ?>
<?php
$ss_index_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<form name="fss_indexlist" id="fss_indexlist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="ss_index">
<div id="gmp_ss_index" class="ewGridMiddlePanel">
<?php if ($ss_index_list->TotalRecs > 0) { ?>
<table id="tbl_ss_indexlist" class="ewTable ewTableSeparate">
<?php echo $ss_index->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$ss_index_list->RenderListOptions();

// Render list options (header, left)
$ss_index_list->ListOptions->Render("header", "left");
?>
<?php if ($ss_index->id_idioma->Visible) { // id_idioma ?>
	<?php if ($ss_index->SortUrl($ss_index->id_idioma) == "") { ?>
		<td><div id="elh_ss_index_id_idioma" class="ss_index_id_idioma"><div class="ewTableHeaderCaption"><?php echo $ss_index->id_idioma->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $ss_index->SortUrl($ss_index->id_idioma) ?>',2);"><div id="elh_ss_index_id_idioma" class="ss_index_id_idioma">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ss_index->id_idioma->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ss_index->id_idioma->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ss_index->id_idioma->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ss_index->slideshow1_url->Visible) { // slideshow1_url ?>
	<?php if ($ss_index->SortUrl($ss_index->slideshow1_url) == "") { ?>
		<td><div id="elh_ss_index_slideshow1_url" class="ss_index_slideshow1_url"><div class="ewTableHeaderCaption"><?php echo $ss_index->slideshow1_url->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $ss_index->SortUrl($ss_index->slideshow1_url) ?>',2);"><div id="elh_ss_index_slideshow1_url" class="ss_index_slideshow1_url">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ss_index->slideshow1_url->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ss_index->slideshow1_url->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ss_index->slideshow1_url->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ss_index->slideshow2_url->Visible) { // slideshow2_url ?>
	<?php if ($ss_index->SortUrl($ss_index->slideshow2_url) == "") { ?>
		<td><div id="elh_ss_index_slideshow2_url" class="ss_index_slideshow2_url"><div class="ewTableHeaderCaption"><?php echo $ss_index->slideshow2_url->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $ss_index->SortUrl($ss_index->slideshow2_url) ?>',2);"><div id="elh_ss_index_slideshow2_url" class="ss_index_slideshow2_url">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ss_index->slideshow2_url->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ss_index->slideshow2_url->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ss_index->slideshow2_url->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ss_index->slideshow3_url->Visible) { // slideshow3_url ?>
	<?php if ($ss_index->SortUrl($ss_index->slideshow3_url) == "") { ?>
		<td><div id="elh_ss_index_slideshow3_url" class="ss_index_slideshow3_url"><div class="ewTableHeaderCaption"><?php echo $ss_index->slideshow3_url->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $ss_index->SortUrl($ss_index->slideshow3_url) ?>',2);"><div id="elh_ss_index_slideshow3_url" class="ss_index_slideshow3_url">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ss_index->slideshow3_url->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ss_index->slideshow3_url->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ss_index->slideshow3_url->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ss_index->slideshow4_url->Visible) { // slideshow4_url ?>
	<?php if ($ss_index->SortUrl($ss_index->slideshow4_url) == "") { ?>
		<td><div id="elh_ss_index_slideshow4_url" class="ss_index_slideshow4_url"><div class="ewTableHeaderCaption"><?php echo $ss_index->slideshow4_url->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $ss_index->SortUrl($ss_index->slideshow4_url) ?>',2);"><div id="elh_ss_index_slideshow4_url" class="ss_index_slideshow4_url">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ss_index->slideshow4_url->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ss_index->slideshow4_url->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ss_index->slideshow4_url->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ss_index->slideshow5_url->Visible) { // slideshow5_url ?>
	<?php if ($ss_index->SortUrl($ss_index->slideshow5_url) == "") { ?>
		<td><div id="elh_ss_index_slideshow5_url" class="ss_index_slideshow5_url"><div class="ewTableHeaderCaption"><?php echo $ss_index->slideshow5_url->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $ss_index->SortUrl($ss_index->slideshow5_url) ?>',2);"><div id="elh_ss_index_slideshow5_url" class="ss_index_slideshow5_url">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ss_index->slideshow5_url->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ss_index->slideshow5_url->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ss_index->slideshow5_url->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$ss_index_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($ss_index->ExportAll && $ss_index->Export <> "") {
	$ss_index_list->StopRec = $ss_index_list->TotalRecs;
} else {

	// Set the last record to display
	if ($ss_index_list->TotalRecs > $ss_index_list->StartRec + $ss_index_list->DisplayRecs - 1)
		$ss_index_list->StopRec = $ss_index_list->StartRec + $ss_index_list->DisplayRecs - 1;
	else
		$ss_index_list->StopRec = $ss_index_list->TotalRecs;
}
$ss_index_list->RecCnt = $ss_index_list->StartRec - 1;
if ($ss_index_list->Recordset && !$ss_index_list->Recordset->EOF) {
	$ss_index_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $ss_index_list->StartRec > 1)
		$ss_index_list->Recordset->Move($ss_index_list->StartRec - 1);
} elseif (!$ss_index->AllowAddDeleteRow && $ss_index_list->StopRec == 0) {
	$ss_index_list->StopRec = $ss_index->GridAddRowCount;
}

// Initialize aggregate
$ss_index->RowType = EW_ROWTYPE_AGGREGATEINIT;
$ss_index->ResetAttrs();
$ss_index_list->RenderRow();
while ($ss_index_list->RecCnt < $ss_index_list->StopRec) {
	$ss_index_list->RecCnt++;
	if (intval($ss_index_list->RecCnt) >= intval($ss_index_list->StartRec)) {
		$ss_index_list->RowCnt++;

		// Set up key count
		$ss_index_list->KeyCount = $ss_index_list->RowIndex;

		// Init row class and style
		$ss_index->ResetAttrs();
		$ss_index->CssClass = "";
		if ($ss_index->CurrentAction == "gridadd") {
		} else {
			$ss_index_list->LoadRowValues($ss_index_list->Recordset); // Load row values
		}
		$ss_index->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$ss_index->RowAttrs = array_merge($ss_index->RowAttrs, array('data-rowindex'=>$ss_index_list->RowCnt, 'id'=>'r' . $ss_index_list->RowCnt . '_ss_index', 'data-rowtype'=>$ss_index->RowType));

		// Render row
		$ss_index_list->RenderRow();

		// Render list options
		$ss_index_list->RenderListOptions();
?>
	<tr<?php echo $ss_index->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ss_index_list->ListOptions->Render("body", "left", $ss_index_list->RowCnt);
?>
	<?php if ($ss_index->id_idioma->Visible) { // id_idioma ?>
		<td<?php echo $ss_index->id_idioma->CellAttributes() ?>>
<span<?php echo $ss_index->id_idioma->ViewAttributes() ?>>
<?php echo $ss_index->id_idioma->ListViewValue() ?></span>
<a id="<?php echo $ss_index_list->PageObjName . "_row_" . $ss_index_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ss_index->slideshow1_url->Visible) { // slideshow1_url ?>
		<td<?php echo $ss_index->slideshow1_url->CellAttributes() ?>>
<span<?php echo $ss_index->slideshow1_url->ViewAttributes() ?>>
<?php if ($ss_index->slideshow1_url->LinkAttributes() <> "") { ?>
<?php if (!empty($ss_index->slideshow1_url->Upload->DbValue)) { ?>
<?php echo $ss_index->slideshow1_url->ListViewValue() ?>
<?php } elseif (!in_array($ss_index->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($ss_index->slideshow1_url->Upload->DbValue)) { ?>
<?php echo $ss_index->slideshow1_url->ListViewValue() ?>
<?php } elseif (!in_array($ss_index->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</td>
	<?php } ?>
	<?php if ($ss_index->slideshow2_url->Visible) { // slideshow2_url ?>
		<td<?php echo $ss_index->slideshow2_url->CellAttributes() ?>>
<span<?php echo $ss_index->slideshow2_url->ViewAttributes() ?>>
<?php if ($ss_index->slideshow2_url->LinkAttributes() <> "") { ?>
<?php if (!empty($ss_index->slideshow2_url->Upload->DbValue)) { ?>
<?php echo $ss_index->slideshow2_url->ListViewValue() ?>
<?php } elseif (!in_array($ss_index->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($ss_index->slideshow2_url->Upload->DbValue)) { ?>
<?php echo $ss_index->slideshow2_url->ListViewValue() ?>
<?php } elseif (!in_array($ss_index->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</td>
	<?php } ?>
	<?php if ($ss_index->slideshow3_url->Visible) { // slideshow3_url ?>
		<td<?php echo $ss_index->slideshow3_url->CellAttributes() ?>>
<span<?php echo $ss_index->slideshow3_url->ViewAttributes() ?>>
<?php if ($ss_index->slideshow3_url->LinkAttributes() <> "") { ?>
<?php if (!empty($ss_index->slideshow3_url->Upload->DbValue)) { ?>
<?php echo $ss_index->slideshow3_url->ListViewValue() ?>
<?php } elseif (!in_array($ss_index->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($ss_index->slideshow3_url->Upload->DbValue)) { ?>
<?php echo $ss_index->slideshow3_url->ListViewValue() ?>
<?php } elseif (!in_array($ss_index->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</td>
	<?php } ?>
	<?php if ($ss_index->slideshow4_url->Visible) { // slideshow4_url ?>
		<td<?php echo $ss_index->slideshow4_url->CellAttributes() ?>>
<span<?php echo $ss_index->slideshow4_url->ViewAttributes() ?>>
<?php if ($ss_index->slideshow4_url->LinkAttributes() <> "") { ?>
<?php if (!empty($ss_index->slideshow4_url->Upload->DbValue)) { ?>
<?php echo $ss_index->slideshow4_url->ListViewValue() ?>
<?php } elseif (!in_array($ss_index->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($ss_index->slideshow4_url->Upload->DbValue)) { ?>
<?php echo $ss_index->slideshow4_url->ListViewValue() ?>
<?php } elseif (!in_array($ss_index->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</td>
	<?php } ?>
	<?php if ($ss_index->slideshow5_url->Visible) { // slideshow5_url ?>
		<td<?php echo $ss_index->slideshow5_url->CellAttributes() ?>>
<span<?php echo $ss_index->slideshow5_url->ViewAttributes() ?>>
<?php if ($ss_index->slideshow5_url->LinkAttributes() <> "") { ?>
<?php if (!empty($ss_index->slideshow5_url->Upload->DbValue)) { ?>
<?php echo $ss_index->slideshow5_url->ListViewValue() ?>
<?php } elseif (!in_array($ss_index->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($ss_index->slideshow5_url->Upload->DbValue)) { ?>
<?php echo $ss_index->slideshow5_url->ListViewValue() ?>
<?php } elseif (!in_array($ss_index->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$ss_index_list->ListOptions->Render("body", "right", $ss_index_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($ss_index->CurrentAction <> "gridadd")
		$ss_index_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($ss_index->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($ss_index_list->Recordset)
	$ss_index_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($ss_index->CurrentAction <> "gridadd" && $ss_index->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($ss_index_list->Pager)) $ss_index_list->Pager = new cPrevNextPager($ss_index_list->StartRec, $ss_index_list->DisplayRecs, $ss_index_list->TotalRecs) ?>
<?php if ($ss_index_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($ss_index_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $ss_index_list->PageUrl() ?>start=<?php echo $ss_index_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($ss_index_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $ss_index_list->PageUrl() ?>start=<?php echo $ss_index_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $ss_index_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($ss_index_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $ss_index_list->PageUrl() ?>start=<?php echo $ss_index_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($ss_index_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $ss_index_list->PageUrl() ?>start=<?php echo $ss_index_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $ss_index_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $ss_index_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $ss_index_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $ss_index_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($ss_index_list->SearchWhere == "0=101") { ?>
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
	foreach ($ss_index_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
</td></tr></table>
<script type="text/javascript">
fss_indexlist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$ss_index_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ss_index_list->Page_Terminate();
?>
