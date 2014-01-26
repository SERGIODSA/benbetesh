<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "marcasinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$marcas_list = NULL; // Initialize page object first

class cmarcas_list extends cmarcas {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'marcas';

	// Page object name
	var $PageObjName = 'marcas_list';

	// Grid form hidden field names
	var $FormName = 'fmarcaslist';
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

		// Table object (marcas)
		if (!isset($GLOBALS["marcas"]) || get_class($GLOBALS["marcas"]) == "cmarcas") {
			$GLOBALS["marcas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["marcas"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "marcasadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "marcasdelete.php";
		$this->MultiUpdateUrl = "marcasupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'marcas', TRUE);

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
			$this->id_marca->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_marca->FormValue))
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
			$this->UpdateSort($this->nombre, $bCtrl); // nombre
			$this->UpdateSort($this->amigable, $bCtrl); // amigable
			$this->UpdateSort($this->logo_url, $bCtrl); // logo_url
			$this->UpdateSort($this->slideshow1_url, $bCtrl); // slideshow1_url
			$this->UpdateSort($this->slideshow2_url, $bCtrl); // slideshow2_url
			$this->UpdateSort($this->slideshow3_url, $bCtrl); // slideshow3_url
			$this->UpdateSort($this->slideshow4_url, $bCtrl); // slideshow4_url
			$this->UpdateSort($this->slideshow5_url, $bCtrl); // slideshow5_url
			$this->UpdateSort($this->titulo1, $bCtrl); // titulo1
			$this->UpdateSort($this->titulo2, $bCtrl); // titulo2
			$this->UpdateSort($this->titulo3, $bCtrl); // titulo3
			$this->UpdateSort($this->tiendas_pie, $bCtrl); // tiendas_pie
			$this->UpdateSort($this->marcas_pie, $bCtrl); // marcas_pie
			$this->UpdateSort($this->descripcion_form, $bCtrl); // descripcion_form
			$this->UpdateSort($this->telefono, $bCtrl); // telefono
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
				$this->nombre->setSort("");
				$this->amigable->setSort("");
				$this->logo_url->setSort("");
				$this->slideshow1_url->setSort("");
				$this->slideshow2_url->setSort("");
				$this->slideshow3_url->setSort("");
				$this->slideshow4_url->setSort("");
				$this->slideshow5_url->setSort("");
				$this->titulo1->setSort("");
				$this->titulo2->setSort("");
				$this->titulo3->setSort("");
				$this->tiendas_pie->setSort("");
				$this->marcas_pie->setSort("");
				$this->descripcion_form->setSort("");
				$this->telefono->setSort("");
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
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_marca->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fmarcaslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->id_marca->setDbValue($rs->fields('id_marca'));
		$this->id_idioma->setDbValue($rs->fields('id_idioma'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->amigable->setDbValue($rs->fields('amigable'));
		$this->logo_url->Upload->DbValue = $rs->fields('logo_url');
		$this->logo_url->CurrentValue = $this->logo_url->Upload->DbValue;
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
		$this->tienda1_url->Upload->DbValue = $rs->fields('tienda1_url');
		$this->tienda1_url->CurrentValue = $this->tienda1_url->Upload->DbValue;
		$this->tienda2_url->Upload->DbValue = $rs->fields('tienda2_url');
		$this->tienda2_url->CurrentValue = $this->tienda2_url->Upload->DbValue;
		$this->tienda3_url->Upload->DbValue = $rs->fields('tienda3_url');
		$this->tienda3_url->CurrentValue = $this->tienda3_url->Upload->DbValue;
		$this->titulo1->setDbValue($rs->fields('titulo1'));
		$this->descripcion1->setDbValue($rs->fields('descripcion1'));
		$this->titulo2->setDbValue($rs->fields('titulo2'));
		$this->descripcion2->setDbValue($rs->fields('descripcion2'));
		$this->titulo3->setDbValue($rs->fields('titulo3'));
		$this->descripcion3->setDbValue($rs->fields('descripcion3'));
		$this->tiendas_pie->setDbValue($rs->fields('tiendas_pie'));
		$this->marcas_pie->setDbValue($rs->fields('marcas_pie'));
		$this->descripcion_form->setDbValue($rs->fields('descripcion_form'));
		$this->telefono->setDbValue($rs->fields('telefono'));
		$this->imagen_url->Upload->DbValue = $rs->fields('imagen_url');
		$this->imagen_url->CurrentValue = $this->imagen_url->Upload->DbValue;
		$this->url_facebook->setDbValue($rs->fields('url_facebook'));
		$this->url_twitter->setDbValue($rs->fields('url_twitter'));
		$this->url_youtube->setDbValue($rs->fields('url_youtube'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_marca->DbValue = $row['id_marca'];
		$this->id_idioma->DbValue = $row['id_idioma'];
		$this->nombre->DbValue = $row['nombre'];
		$this->amigable->DbValue = $row['amigable'];
		$this->logo_url->Upload->DbValue = $row['logo_url'];
		$this->slideshow1_url->Upload->DbValue = $row['slideshow1_url'];
		$this->slideshow2_url->Upload->DbValue = $row['slideshow2_url'];
		$this->slideshow3_url->Upload->DbValue = $row['slideshow3_url'];
		$this->slideshow4_url->Upload->DbValue = $row['slideshow4_url'];
		$this->slideshow5_url->Upload->DbValue = $row['slideshow5_url'];
		$this->tienda1_url->Upload->DbValue = $row['tienda1_url'];
		$this->tienda2_url->Upload->DbValue = $row['tienda2_url'];
		$this->tienda3_url->Upload->DbValue = $row['tienda3_url'];
		$this->titulo1->DbValue = $row['titulo1'];
		$this->descripcion1->DbValue = $row['descripcion1'];
		$this->titulo2->DbValue = $row['titulo2'];
		$this->descripcion2->DbValue = $row['descripcion2'];
		$this->titulo3->DbValue = $row['titulo3'];
		$this->descripcion3->DbValue = $row['descripcion3'];
		$this->tiendas_pie->DbValue = $row['tiendas_pie'];
		$this->marcas_pie->DbValue = $row['marcas_pie'];
		$this->descripcion_form->DbValue = $row['descripcion_form'];
		$this->telefono->DbValue = $row['telefono'];
		$this->imagen_url->Upload->DbValue = $row['imagen_url'];
		$this->url_facebook->DbValue = $row['url_facebook'];
		$this->url_twitter->DbValue = $row['url_twitter'];
		$this->url_youtube->DbValue = $row['url_youtube'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_marca")) <> "")
			$this->id_marca->CurrentValue = $this->getKey("id_marca"); // id_marca
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
		// id_marca

		$this->id_marca->CellCssStyle = "white-space: nowrap;";

		// id_idioma
		// nombre
		// amigable
		// logo_url
		// slideshow1_url
		// slideshow2_url
		// slideshow3_url
		// slideshow4_url
		// slideshow5_url
		// tienda1_url
		// tienda2_url
		// tienda3_url
		// titulo1
		// descripcion1
		// titulo2
		// descripcion2
		// titulo3
		// descripcion3
		// tiendas_pie
		// marcas_pie
		// descripcion_form
		// telefono
		// imagen_url
		// url_facebook
		// url_twitter
		// url_youtube

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// amigable
			$this->amigable->ViewValue = $this->amigable->CurrentValue;
			$this->amigable->ViewCustomAttributes = "";

			// logo_url
			if (!ew_Empty($this->logo_url->Upload->DbValue)) {
				$this->logo_url->ViewValue = $this->logo_url->Upload->DbValue;
			} else {
				$this->logo_url->ViewValue = "";
			}
			$this->logo_url->ViewCustomAttributes = "";

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

			// titulo1
			$this->titulo1->ViewValue = $this->titulo1->CurrentValue;
			$this->titulo1->ViewCustomAttributes = "";

			// titulo2
			$this->titulo2->ViewValue = $this->titulo2->CurrentValue;
			$this->titulo2->ViewCustomAttributes = "";

			// titulo3
			$this->titulo3->ViewValue = $this->titulo3->CurrentValue;
			$this->titulo3->ViewCustomAttributes = "";

			// tiendas_pie
			if (strval($this->tiendas_pie->CurrentValue) <> "") {
				switch ($this->tiendas_pie->CurrentValue) {
					case $this->tiendas_pie->FldTagValue(1):
						$this->tiendas_pie->ViewValue = $this->tiendas_pie->FldTagCaption(1) <> "" ? $this->tiendas_pie->FldTagCaption(1) : $this->tiendas_pie->CurrentValue;
						break;
					case $this->tiendas_pie->FldTagValue(2):
						$this->tiendas_pie->ViewValue = $this->tiendas_pie->FldTagCaption(2) <> "" ? $this->tiendas_pie->FldTagCaption(2) : $this->tiendas_pie->CurrentValue;
						break;
					default:
						$this->tiendas_pie->ViewValue = $this->tiendas_pie->CurrentValue;
				}
			} else {
				$this->tiendas_pie->ViewValue = NULL;
			}
			$this->tiendas_pie->ViewCustomAttributes = "";

			// marcas_pie
			if (strval($this->marcas_pie->CurrentValue) <> "") {
				switch ($this->marcas_pie->CurrentValue) {
					case $this->marcas_pie->FldTagValue(1):
						$this->marcas_pie->ViewValue = $this->marcas_pie->FldTagCaption(1) <> "" ? $this->marcas_pie->FldTagCaption(1) : $this->marcas_pie->CurrentValue;
						break;
					case $this->marcas_pie->FldTagValue(2):
						$this->marcas_pie->ViewValue = $this->marcas_pie->FldTagCaption(2) <> "" ? $this->marcas_pie->FldTagCaption(2) : $this->marcas_pie->CurrentValue;
						break;
					default:
						$this->marcas_pie->ViewValue = $this->marcas_pie->CurrentValue;
				}
			} else {
				$this->marcas_pie->ViewValue = NULL;
			}
			$this->marcas_pie->ViewCustomAttributes = "";

			// descripcion_form
			$this->descripcion_form->ViewValue = $this->descripcion_form->CurrentValue;
			$this->descripcion_form->ViewCustomAttributes = "";

			// telefono
			$this->telefono->ViewValue = $this->telefono->CurrentValue;
			$this->telefono->ViewCustomAttributes = "";

			// id_idioma
			$this->id_idioma->LinkCustomAttributes = "";
			$this->id_idioma->HrefValue = "";
			$this->id_idioma->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// amigable
			$this->amigable->LinkCustomAttributes = "";
			$this->amigable->HrefValue = "";
			$this->amigable->TooltipValue = "";

			// logo_url
			$this->logo_url->LinkCustomAttributes = "";
			$this->logo_url->HrefValue = "";
			$this->logo_url->HrefValue2 = $this->logo_url->UploadPath . $this->logo_url->Upload->DbValue;
			$this->logo_url->TooltipValue = "";

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

			// titulo1
			$this->titulo1->LinkCustomAttributes = "";
			$this->titulo1->HrefValue = "";
			$this->titulo1->TooltipValue = "";

			// titulo2
			$this->titulo2->LinkCustomAttributes = "";
			$this->titulo2->HrefValue = "";
			$this->titulo2->TooltipValue = "";

			// titulo3
			$this->titulo3->LinkCustomAttributes = "";
			$this->titulo3->HrefValue = "";
			$this->titulo3->TooltipValue = "";

			// tiendas_pie
			$this->tiendas_pie->LinkCustomAttributes = "";
			$this->tiendas_pie->HrefValue = "";
			$this->tiendas_pie->TooltipValue = "";

			// marcas_pie
			$this->marcas_pie->LinkCustomAttributes = "";
			$this->marcas_pie->HrefValue = "";
			$this->marcas_pie->TooltipValue = "";

			// descripcion_form
			$this->descripcion_form->LinkCustomAttributes = "";
			$this->descripcion_form->HrefValue = "";
			$this->descripcion_form->TooltipValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";
			$this->telefono->TooltipValue = "";
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
if (!isset($marcas_list)) $marcas_list = new cmarcas_list();

// Page init
$marcas_list->Page_Init();

// Page main
$marcas_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$marcas_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var marcas_list = new ew_Page("marcas_list");
marcas_list.PageID = "list"; // Page ID
var EW_PAGE_ID = marcas_list.PageID; // For backward compatibility

// Form object
var fmarcaslist = new ew_Form("fmarcaslist");
fmarcaslist.FormKeyCountName = '<?php echo $marcas_list->FormKeyCountName ?>';

// Form_CustomValidate event
fmarcaslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmarcaslist.ValidateRequired = true;
<?php } else { ?>
fmarcaslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmarcaslist.Lists["x_id_idioma"] = {"LinkField":"x_id_idioma","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php if ($marcas_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $marcas_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$marcas_list->TotalRecs = $marcas->SelectRecordCount();
	} else {
		if ($marcas_list->Recordset = $marcas_list->LoadRecordset())
			$marcas_list->TotalRecs = $marcas_list->Recordset->RecordCount();
	}
	$marcas_list->StartRec = 1;
	if ($marcas_list->DisplayRecs <= 0 || ($marcas->Export <> "" && $marcas->ExportAll)) // Display all records
		$marcas_list->DisplayRecs = $marcas_list->TotalRecs;
	if (!($marcas->Export <> "" && $marcas->ExportAll))
		$marcas_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$marcas_list->Recordset = $marcas_list->LoadRecordset($marcas_list->StartRec-1, $marcas_list->DisplayRecs);
$marcas_list->RenderOtherOptions();
?>
<?php $marcas_list->ShowPageHeader(); ?>
<?php
$marcas_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<form name="fmarcaslist" id="fmarcaslist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="marcas">
<div id="gmp_marcas" class="ewGridMiddlePanel">
<?php if ($marcas_list->TotalRecs > 0) { ?>
<table id="tbl_marcaslist" class="ewTable ewTableSeparate">
<?php echo $marcas->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$marcas_list->RenderListOptions();

// Render list options (header, left)
$marcas_list->ListOptions->Render("header", "left");
?>
<?php if ($marcas->id_idioma->Visible) { // id_idioma ?>
	<?php if ($marcas->SortUrl($marcas->id_idioma) == "") { ?>
		<td><div id="elh_marcas_id_idioma" class="marcas_id_idioma"><div class="ewTableHeaderCaption"><?php echo $marcas->id_idioma->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $marcas->SortUrl($marcas->id_idioma) ?>',2);"><div id="elh_marcas_id_idioma" class="marcas_id_idioma">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $marcas->id_idioma->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($marcas->id_idioma->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($marcas->id_idioma->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($marcas->nombre->Visible) { // nombre ?>
	<?php if ($marcas->SortUrl($marcas->nombre) == "") { ?>
		<td><div id="elh_marcas_nombre" class="marcas_nombre"><div class="ewTableHeaderCaption"><?php echo $marcas->nombre->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $marcas->SortUrl($marcas->nombre) ?>',2);"><div id="elh_marcas_nombre" class="marcas_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $marcas->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($marcas->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($marcas->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($marcas->amigable->Visible) { // amigable ?>
	<?php if ($marcas->SortUrl($marcas->amigable) == "") { ?>
		<td><div id="elh_marcas_amigable" class="marcas_amigable"><div class="ewTableHeaderCaption"><?php echo $marcas->amigable->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $marcas->SortUrl($marcas->amigable) ?>',2);"><div id="elh_marcas_amigable" class="marcas_amigable">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $marcas->amigable->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($marcas->amigable->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($marcas->amigable->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($marcas->logo_url->Visible) { // logo_url ?>
	<?php if ($marcas->SortUrl($marcas->logo_url) == "") { ?>
		<td><div id="elh_marcas_logo_url" class="marcas_logo_url"><div class="ewTableHeaderCaption"><?php echo $marcas->logo_url->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $marcas->SortUrl($marcas->logo_url) ?>',2);"><div id="elh_marcas_logo_url" class="marcas_logo_url">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $marcas->logo_url->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($marcas->logo_url->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($marcas->logo_url->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($marcas->slideshow1_url->Visible) { // slideshow1_url ?>
	<?php if ($marcas->SortUrl($marcas->slideshow1_url) == "") { ?>
		<td><div id="elh_marcas_slideshow1_url" class="marcas_slideshow1_url"><div class="ewTableHeaderCaption"><?php echo $marcas->slideshow1_url->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $marcas->SortUrl($marcas->slideshow1_url) ?>',2);"><div id="elh_marcas_slideshow1_url" class="marcas_slideshow1_url">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $marcas->slideshow1_url->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($marcas->slideshow1_url->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($marcas->slideshow1_url->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($marcas->slideshow2_url->Visible) { // slideshow2_url ?>
	<?php if ($marcas->SortUrl($marcas->slideshow2_url) == "") { ?>
		<td><div id="elh_marcas_slideshow2_url" class="marcas_slideshow2_url"><div class="ewTableHeaderCaption"><?php echo $marcas->slideshow2_url->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $marcas->SortUrl($marcas->slideshow2_url) ?>',2);"><div id="elh_marcas_slideshow2_url" class="marcas_slideshow2_url">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $marcas->slideshow2_url->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($marcas->slideshow2_url->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($marcas->slideshow2_url->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($marcas->slideshow3_url->Visible) { // slideshow3_url ?>
	<?php if ($marcas->SortUrl($marcas->slideshow3_url) == "") { ?>
		<td><div id="elh_marcas_slideshow3_url" class="marcas_slideshow3_url"><div class="ewTableHeaderCaption"><?php echo $marcas->slideshow3_url->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $marcas->SortUrl($marcas->slideshow3_url) ?>',2);"><div id="elh_marcas_slideshow3_url" class="marcas_slideshow3_url">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $marcas->slideshow3_url->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($marcas->slideshow3_url->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($marcas->slideshow3_url->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($marcas->slideshow4_url->Visible) { // slideshow4_url ?>
	<?php if ($marcas->SortUrl($marcas->slideshow4_url) == "") { ?>
		<td><div id="elh_marcas_slideshow4_url" class="marcas_slideshow4_url"><div class="ewTableHeaderCaption"><?php echo $marcas->slideshow4_url->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $marcas->SortUrl($marcas->slideshow4_url) ?>',2);"><div id="elh_marcas_slideshow4_url" class="marcas_slideshow4_url">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $marcas->slideshow4_url->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($marcas->slideshow4_url->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($marcas->slideshow4_url->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($marcas->slideshow5_url->Visible) { // slideshow5_url ?>
	<?php if ($marcas->SortUrl($marcas->slideshow5_url) == "") { ?>
		<td><div id="elh_marcas_slideshow5_url" class="marcas_slideshow5_url"><div class="ewTableHeaderCaption"><?php echo $marcas->slideshow5_url->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $marcas->SortUrl($marcas->slideshow5_url) ?>',2);"><div id="elh_marcas_slideshow5_url" class="marcas_slideshow5_url">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $marcas->slideshow5_url->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($marcas->slideshow5_url->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($marcas->slideshow5_url->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($marcas->titulo1->Visible) { // titulo1 ?>
	<?php if ($marcas->SortUrl($marcas->titulo1) == "") { ?>
		<td><div id="elh_marcas_titulo1" class="marcas_titulo1"><div class="ewTableHeaderCaption"><?php echo $marcas->titulo1->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $marcas->SortUrl($marcas->titulo1) ?>',2);"><div id="elh_marcas_titulo1" class="marcas_titulo1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $marcas->titulo1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($marcas->titulo1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($marcas->titulo1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($marcas->titulo2->Visible) { // titulo2 ?>
	<?php if ($marcas->SortUrl($marcas->titulo2) == "") { ?>
		<td><div id="elh_marcas_titulo2" class="marcas_titulo2"><div class="ewTableHeaderCaption"><?php echo $marcas->titulo2->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $marcas->SortUrl($marcas->titulo2) ?>',2);"><div id="elh_marcas_titulo2" class="marcas_titulo2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $marcas->titulo2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($marcas->titulo2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($marcas->titulo2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($marcas->titulo3->Visible) { // titulo3 ?>
	<?php if ($marcas->SortUrl($marcas->titulo3) == "") { ?>
		<td><div id="elh_marcas_titulo3" class="marcas_titulo3"><div class="ewTableHeaderCaption"><?php echo $marcas->titulo3->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $marcas->SortUrl($marcas->titulo3) ?>',2);"><div id="elh_marcas_titulo3" class="marcas_titulo3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $marcas->titulo3->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($marcas->titulo3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($marcas->titulo3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($marcas->tiendas_pie->Visible) { // tiendas_pie ?>
	<?php if ($marcas->SortUrl($marcas->tiendas_pie) == "") { ?>
		<td><div id="elh_marcas_tiendas_pie" class="marcas_tiendas_pie"><div class="ewTableHeaderCaption"><?php echo $marcas->tiendas_pie->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $marcas->SortUrl($marcas->tiendas_pie) ?>',2);"><div id="elh_marcas_tiendas_pie" class="marcas_tiendas_pie">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $marcas->tiendas_pie->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($marcas->tiendas_pie->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($marcas->tiendas_pie->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($marcas->marcas_pie->Visible) { // marcas_pie ?>
	<?php if ($marcas->SortUrl($marcas->marcas_pie) == "") { ?>
		<td><div id="elh_marcas_marcas_pie" class="marcas_marcas_pie"><div class="ewTableHeaderCaption"><?php echo $marcas->marcas_pie->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $marcas->SortUrl($marcas->marcas_pie) ?>',2);"><div id="elh_marcas_marcas_pie" class="marcas_marcas_pie">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $marcas->marcas_pie->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($marcas->marcas_pie->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($marcas->marcas_pie->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($marcas->descripcion_form->Visible) { // descripcion_form ?>
	<?php if ($marcas->SortUrl($marcas->descripcion_form) == "") { ?>
		<td><div id="elh_marcas_descripcion_form" class="marcas_descripcion_form"><div class="ewTableHeaderCaption"><?php echo $marcas->descripcion_form->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $marcas->SortUrl($marcas->descripcion_form) ?>',2);"><div id="elh_marcas_descripcion_form" class="marcas_descripcion_form">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $marcas->descripcion_form->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($marcas->descripcion_form->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($marcas->descripcion_form->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($marcas->telefono->Visible) { // telefono ?>
	<?php if ($marcas->SortUrl($marcas->telefono) == "") { ?>
		<td><div id="elh_marcas_telefono" class="marcas_telefono"><div class="ewTableHeaderCaption"><?php echo $marcas->telefono->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $marcas->SortUrl($marcas->telefono) ?>',2);"><div id="elh_marcas_telefono" class="marcas_telefono">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $marcas->telefono->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($marcas->telefono->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($marcas->telefono->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$marcas_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($marcas->ExportAll && $marcas->Export <> "") {
	$marcas_list->StopRec = $marcas_list->TotalRecs;
} else {

	// Set the last record to display
	if ($marcas_list->TotalRecs > $marcas_list->StartRec + $marcas_list->DisplayRecs - 1)
		$marcas_list->StopRec = $marcas_list->StartRec + $marcas_list->DisplayRecs - 1;
	else
		$marcas_list->StopRec = $marcas_list->TotalRecs;
}
$marcas_list->RecCnt = $marcas_list->StartRec - 1;
if ($marcas_list->Recordset && !$marcas_list->Recordset->EOF) {
	$marcas_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $marcas_list->StartRec > 1)
		$marcas_list->Recordset->Move($marcas_list->StartRec - 1);
} elseif (!$marcas->AllowAddDeleteRow && $marcas_list->StopRec == 0) {
	$marcas_list->StopRec = $marcas->GridAddRowCount;
}

// Initialize aggregate
$marcas->RowType = EW_ROWTYPE_AGGREGATEINIT;
$marcas->ResetAttrs();
$marcas_list->RenderRow();
while ($marcas_list->RecCnt < $marcas_list->StopRec) {
	$marcas_list->RecCnt++;
	if (intval($marcas_list->RecCnt) >= intval($marcas_list->StartRec)) {
		$marcas_list->RowCnt++;

		// Set up key count
		$marcas_list->KeyCount = $marcas_list->RowIndex;

		// Init row class and style
		$marcas->ResetAttrs();
		$marcas->CssClass = "";
		if ($marcas->CurrentAction == "gridadd") {
		} else {
			$marcas_list->LoadRowValues($marcas_list->Recordset); // Load row values
		}
		$marcas->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$marcas->RowAttrs = array_merge($marcas->RowAttrs, array('data-rowindex'=>$marcas_list->RowCnt, 'id'=>'r' . $marcas_list->RowCnt . '_marcas', 'data-rowtype'=>$marcas->RowType));

		// Render row
		$marcas_list->RenderRow();

		// Render list options
		$marcas_list->RenderListOptions();
?>
	<tr<?php echo $marcas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$marcas_list->ListOptions->Render("body", "left", $marcas_list->RowCnt);
?>
	<?php if ($marcas->id_idioma->Visible) { // id_idioma ?>
		<td<?php echo $marcas->id_idioma->CellAttributes() ?>>
<span<?php echo $marcas->id_idioma->ViewAttributes() ?>>
<?php echo $marcas->id_idioma->ListViewValue() ?></span>
<a id="<?php echo $marcas_list->PageObjName . "_row_" . $marcas_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($marcas->nombre->Visible) { // nombre ?>
		<td<?php echo $marcas->nombre->CellAttributes() ?>>
<span<?php echo $marcas->nombre->ViewAttributes() ?>>
<?php echo $marcas->nombre->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($marcas->amigable->Visible) { // amigable ?>
		<td<?php echo $marcas->amigable->CellAttributes() ?>>
<span<?php echo $marcas->amigable->ViewAttributes() ?>>
<?php echo $marcas->amigable->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($marcas->logo_url->Visible) { // logo_url ?>
		<td<?php echo $marcas->logo_url->CellAttributes() ?>>
<span<?php echo $marcas->logo_url->ViewAttributes() ?>>
<?php if ($marcas->logo_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->logo_url->Upload->DbValue)) { ?>
<?php echo $marcas->logo_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->logo_url->Upload->DbValue)) { ?>
<?php echo $marcas->logo_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</td>
	<?php } ?>
	<?php if ($marcas->slideshow1_url->Visible) { // slideshow1_url ?>
		<td<?php echo $marcas->slideshow1_url->CellAttributes() ?>>
<span<?php echo $marcas->slideshow1_url->ViewAttributes() ?>>
<?php if ($marcas->slideshow1_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->slideshow1_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow1_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->slideshow1_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow1_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</td>
	<?php } ?>
	<?php if ($marcas->slideshow2_url->Visible) { // slideshow2_url ?>
		<td<?php echo $marcas->slideshow2_url->CellAttributes() ?>>
<span<?php echo $marcas->slideshow2_url->ViewAttributes() ?>>
<?php if ($marcas->slideshow2_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->slideshow2_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow2_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->slideshow2_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow2_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</td>
	<?php } ?>
	<?php if ($marcas->slideshow3_url->Visible) { // slideshow3_url ?>
		<td<?php echo $marcas->slideshow3_url->CellAttributes() ?>>
<span<?php echo $marcas->slideshow3_url->ViewAttributes() ?>>
<?php if ($marcas->slideshow3_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->slideshow3_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow3_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->slideshow3_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow3_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</td>
	<?php } ?>
	<?php if ($marcas->slideshow4_url->Visible) { // slideshow4_url ?>
		<td<?php echo $marcas->slideshow4_url->CellAttributes() ?>>
<span<?php echo $marcas->slideshow4_url->ViewAttributes() ?>>
<?php if ($marcas->slideshow4_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->slideshow4_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow4_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->slideshow4_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow4_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</td>
	<?php } ?>
	<?php if ($marcas->slideshow5_url->Visible) { // slideshow5_url ?>
		<td<?php echo $marcas->slideshow5_url->CellAttributes() ?>>
<span<?php echo $marcas->slideshow5_url->ViewAttributes() ?>>
<?php if ($marcas->slideshow5_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->slideshow5_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow5_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->slideshow5_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow5_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</td>
	<?php } ?>
	<?php if ($marcas->titulo1->Visible) { // titulo1 ?>
		<td<?php echo $marcas->titulo1->CellAttributes() ?>>
<span<?php echo $marcas->titulo1->ViewAttributes() ?>>
<?php echo $marcas->titulo1->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($marcas->titulo2->Visible) { // titulo2 ?>
		<td<?php echo $marcas->titulo2->CellAttributes() ?>>
<span<?php echo $marcas->titulo2->ViewAttributes() ?>>
<?php echo $marcas->titulo2->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($marcas->titulo3->Visible) { // titulo3 ?>
		<td<?php echo $marcas->titulo3->CellAttributes() ?>>
<span<?php echo $marcas->titulo3->ViewAttributes() ?>>
<?php echo $marcas->titulo3->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($marcas->tiendas_pie->Visible) { // tiendas_pie ?>
		<td<?php echo $marcas->tiendas_pie->CellAttributes() ?>>
<span<?php echo $marcas->tiendas_pie->ViewAttributes() ?>>
<?php echo $marcas->tiendas_pie->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($marcas->marcas_pie->Visible) { // marcas_pie ?>
		<td<?php echo $marcas->marcas_pie->CellAttributes() ?>>
<span<?php echo $marcas->marcas_pie->ViewAttributes() ?>>
<?php echo $marcas->marcas_pie->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($marcas->descripcion_form->Visible) { // descripcion_form ?>
		<td<?php echo $marcas->descripcion_form->CellAttributes() ?>>
<span<?php echo $marcas->descripcion_form->ViewAttributes() ?>>
<?php echo $marcas->descripcion_form->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($marcas->telefono->Visible) { // telefono ?>
		<td<?php echo $marcas->telefono->CellAttributes() ?>>
<span<?php echo $marcas->telefono->ViewAttributes() ?>>
<?php echo $marcas->telefono->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$marcas_list->ListOptions->Render("body", "right", $marcas_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($marcas->CurrentAction <> "gridadd")
		$marcas_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($marcas->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($marcas_list->Recordset)
	$marcas_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($marcas->CurrentAction <> "gridadd" && $marcas->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($marcas_list->Pager)) $marcas_list->Pager = new cPrevNextPager($marcas_list->StartRec, $marcas_list->DisplayRecs, $marcas_list->TotalRecs) ?>
<?php if ($marcas_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($marcas_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $marcas_list->PageUrl() ?>start=<?php echo $marcas_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($marcas_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $marcas_list->PageUrl() ?>start=<?php echo $marcas_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $marcas_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($marcas_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $marcas_list->PageUrl() ?>start=<?php echo $marcas_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($marcas_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $marcas_list->PageUrl() ?>start=<?php echo $marcas_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $marcas_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $marcas_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $marcas_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $marcas_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($marcas_list->SearchWhere == "0=101") { ?>
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
	foreach ($marcas_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
</td></tr></table>
<script type="text/javascript">
fmarcaslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$marcas_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$marcas_list->Page_Terminate();
?>
