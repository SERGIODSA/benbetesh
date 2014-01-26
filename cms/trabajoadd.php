<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "trabajoinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$trabajo_add = NULL; // Initialize page object first

class ctrabajo_add extends ctrabajo {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'trabajo';

	// Page object name
	var $PageObjName = 'trabajo_add';

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

		// Table object (trabajo)
		if (!isset($GLOBALS["trabajo"]) || get_class($GLOBALS["trabajo"]) == "ctrabajo") {
			$GLOBALS["trabajo"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["trabajo"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'trabajo', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id_trabajo"] != "") {
				$this->id_trabajo->setQueryStringValue($_GET["id_trabajo"]);
				$this->setKey("id_trabajo", $this->id_trabajo->CurrentValue); // Set up key
			} else {
				$this->setKey("id_trabajo", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("trabajolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "trabajoview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id_idioma->CurrentValue = NULL;
		$this->id_idioma->OldValue = $this->id_idioma->CurrentValue;
		$this->titulo1->CurrentValue = NULL;
		$this->titulo1->OldValue = $this->titulo1->CurrentValue;
		$this->descripcion1->CurrentValue = NULL;
		$this->descripcion1->OldValue = $this->descripcion1->CurrentValue;
		$this->titulo2->CurrentValue = NULL;
		$this->titulo2->OldValue = $this->titulo2->CurrentValue;
		$this->descripcion2->CurrentValue = NULL;
		$this->descripcion2->OldValue = $this->descripcion2->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_idioma->FldIsDetailKey) {
			$this->id_idioma->setFormValue($objForm->GetValue("x_id_idioma"));
		}
		if (!$this->titulo1->FldIsDetailKey) {
			$this->titulo1->setFormValue($objForm->GetValue("x_titulo1"));
		}
		if (!$this->descripcion1->FldIsDetailKey) {
			$this->descripcion1->setFormValue($objForm->GetValue("x_descripcion1"));
		}
		if (!$this->titulo2->FldIsDetailKey) {
			$this->titulo2->setFormValue($objForm->GetValue("x_titulo2"));
		}
		if (!$this->descripcion2->FldIsDetailKey) {
			$this->descripcion2->setFormValue($objForm->GetValue("x_descripcion2"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id_idioma->CurrentValue = $this->id_idioma->FormValue;
		$this->titulo1->CurrentValue = $this->titulo1->FormValue;
		$this->descripcion1->CurrentValue = $this->descripcion1->FormValue;
		$this->titulo2->CurrentValue = $this->titulo2->FormValue;
		$this->descripcion2->CurrentValue = $this->descripcion2->FormValue;
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
		$this->id_trabajo->setDbValue($rs->fields('id_trabajo'));
		$this->id_idioma->setDbValue($rs->fields('id_idioma'));
		$this->titulo1->setDbValue($rs->fields('titulo1'));
		$this->descripcion1->setDbValue($rs->fields('descripcion1'));
		$this->titulo2->setDbValue($rs->fields('titulo2'));
		$this->descripcion2->setDbValue($rs->fields('descripcion2'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_trabajo->DbValue = $row['id_trabajo'];
		$this->id_idioma->DbValue = $row['id_idioma'];
		$this->titulo1->DbValue = $row['titulo1'];
		$this->descripcion1->DbValue = $row['descripcion1'];
		$this->titulo2->DbValue = $row['titulo2'];
		$this->descripcion2->DbValue = $row['descripcion2'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_trabajo")) <> "")
			$this->id_trabajo->CurrentValue = $this->getKey("id_trabajo"); // id_trabajo
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_trabajo
		// id_idioma
		// titulo1
		// descripcion1
		// titulo2
		// descripcion2

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

			// titulo1
			$this->titulo1->ViewValue = $this->titulo1->CurrentValue;
			$this->titulo1->ViewCustomAttributes = "";

			// descripcion1
			$this->descripcion1->ViewValue = $this->descripcion1->CurrentValue;
			$this->descripcion1->ViewCustomAttributes = "";

			// titulo2
			$this->titulo2->ViewValue = $this->titulo2->CurrentValue;
			$this->titulo2->ViewCustomAttributes = "";

			// descripcion2
			$this->descripcion2->ViewValue = $this->descripcion2->CurrentValue;
			$this->descripcion2->ViewCustomAttributes = "";

			// id_idioma
			$this->id_idioma->LinkCustomAttributes = "";
			$this->id_idioma->HrefValue = "";
			$this->id_idioma->TooltipValue = "";

			// titulo1
			$this->titulo1->LinkCustomAttributes = "";
			$this->titulo1->HrefValue = "";
			$this->titulo1->TooltipValue = "";

			// descripcion1
			$this->descripcion1->LinkCustomAttributes = "";
			$this->descripcion1->HrefValue = "";
			$this->descripcion1->TooltipValue = "";

			// titulo2
			$this->titulo2->LinkCustomAttributes = "";
			$this->titulo2->HrefValue = "";
			$this->titulo2->TooltipValue = "";

			// descripcion2
			$this->descripcion2->LinkCustomAttributes = "";
			$this->descripcion2->HrefValue = "";
			$this->descripcion2->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_idioma
			$this->id_idioma->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `id_idioma`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `idioma`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_idioma, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_idioma->EditValue = $arwrk;

			// titulo1
			$this->titulo1->EditCustomAttributes = "";
			$this->titulo1->EditValue = ew_HtmlEncode($this->titulo1->CurrentValue);
			$this->titulo1->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->titulo1->FldCaption()));

			// descripcion1
			$this->descripcion1->EditCustomAttributes = "";
			$this->descripcion1->EditValue = $this->descripcion1->CurrentValue;
			$this->descripcion1->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->descripcion1->FldCaption()));

			// titulo2
			$this->titulo2->EditCustomAttributes = "";
			$this->titulo2->EditValue = ew_HtmlEncode($this->titulo2->CurrentValue);
			$this->titulo2->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->titulo2->FldCaption()));

			// descripcion2
			$this->descripcion2->EditCustomAttributes = "";
			$this->descripcion2->EditValue = $this->descripcion2->CurrentValue;
			$this->descripcion2->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->descripcion2->FldCaption()));

			// Edit refer script
			// id_idioma

			$this->id_idioma->HrefValue = "";

			// titulo1
			$this->titulo1->HrefValue = "";

			// descripcion1
			$this->descripcion1->HrefValue = "";

			// titulo2
			$this->titulo2->HrefValue = "";

			// descripcion2
			$this->descripcion2->HrefValue = "";
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

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->id_idioma->FldIsDetailKey && !is_null($this->id_idioma->FormValue) && $this->id_idioma->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id_idioma->FldCaption());
		}
		if (!$this->titulo1->FldIsDetailKey && !is_null($this->titulo1->FormValue) && $this->titulo1->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->titulo1->FldCaption());
		}
		if (!$this->descripcion1->FldIsDetailKey && !is_null($this->descripcion1->FormValue) && $this->descripcion1->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->descripcion1->FldCaption());
		}
		if (!$this->titulo2->FldIsDetailKey && !is_null($this->titulo2->FormValue) && $this->titulo2->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->titulo2->FldCaption());
		}
		if (!$this->descripcion2->FldIsDetailKey && !is_null($this->descripcion2->FormValue) && $this->descripcion2->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->descripcion2->FldCaption());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// id_idioma
		$this->id_idioma->SetDbValueDef($rsnew, $this->id_idioma->CurrentValue, 0, FALSE);

		// titulo1
		$this->titulo1->SetDbValueDef($rsnew, $this->titulo1->CurrentValue, "", FALSE);

		// descripcion1
		$this->descripcion1->SetDbValueDef($rsnew, $this->descripcion1->CurrentValue, "", FALSE);

		// titulo2
		$this->titulo2->SetDbValueDef($rsnew, $this->titulo2->CurrentValue, "", FALSE);

		// descripcion2
		$this->descripcion2->SetDbValueDef($rsnew, $this->descripcion2->CurrentValue, "", FALSE);

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
			$this->id_trabajo->setDbValue($conn->Insert_ID());
			$rsnew['id_trabajo'] = $this->id_trabajo->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "trabajolist.php", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, ew_CurrentUrl());
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
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($trabajo_add)) $trabajo_add = new ctrabajo_add();

// Page init
$trabajo_add->Page_Init();

// Page main
$trabajo_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$trabajo_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var trabajo_add = new ew_Page("trabajo_add");
trabajo_add.PageID = "add"; // Page ID
var EW_PAGE_ID = trabajo_add.PageID; // For backward compatibility

// Form object
var ftrabajoadd = new ew_Form("ftrabajoadd");

// Validate form
ftrabajoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_idioma");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($trabajo->id_idioma->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_titulo1");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($trabajo->titulo1->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_descripcion1");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($trabajo->descripcion1->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_titulo2");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($trabajo->titulo2->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_descripcion2");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($trabajo->descripcion2->FldCaption()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
ftrabajoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftrabajoadd.ValidateRequired = true;
<?php } else { ?>
ftrabajoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftrabajoadd.Lists["x_id_idioma"] = {"LinkField":"x_id_idioma","Ajax":null,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $trabajo_add->ShowPageHeader(); ?>
<?php
$trabajo_add->ShowMessage();
?>
<form name="ftrabajoadd" id="ftrabajoadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="trabajo">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewGrid"><tr><td>
<table id="tbl_trabajoadd" class="table table-bordered table-striped">
<?php if ($trabajo->id_idioma->Visible) { // id_idioma ?>
	<tr id="r_id_idioma">
		<td><span id="elh_trabajo_id_idioma"><?php echo $trabajo->id_idioma->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $trabajo->id_idioma->CellAttributes() ?>>
<span id="el_trabajo_id_idioma" class="control-group">
<select data-field="x_id_idioma" id="x_id_idioma" name="x_id_idioma"<?php echo $trabajo->id_idioma->EditAttributes() ?>>
<?php
if (is_array($trabajo->id_idioma->EditValue)) {
	$arwrk = $trabajo->id_idioma->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($trabajo->id_idioma->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
ftrabajoadd.Lists["x_id_idioma"].Options = <?php echo (is_array($trabajo->id_idioma->EditValue)) ? ew_ArrayToJson($trabajo->id_idioma->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $trabajo->id_idioma->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajo->titulo1->Visible) { // titulo1 ?>
	<tr id="r_titulo1">
		<td><span id="elh_trabajo_titulo1"><?php echo $trabajo->titulo1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $trabajo->titulo1->CellAttributes() ?>>
<span id="el_trabajo_titulo1" class="control-group">
<input type="text" data-field="x_titulo1" name="x_titulo1" id="x_titulo1" size="30" maxlength="40" placeholder="<?php echo $trabajo->titulo1->PlaceHolder ?>" value="<?php echo $trabajo->titulo1->EditValue ?>"<?php echo $trabajo->titulo1->EditAttributes() ?>>
</span>
<?php echo $trabajo->titulo1->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajo->descripcion1->Visible) { // descripcion1 ?>
	<tr id="r_descripcion1">
		<td><span id="elh_trabajo_descripcion1"><?php echo $trabajo->descripcion1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $trabajo->descripcion1->CellAttributes() ?>>
<span id="el_trabajo_descripcion1" class="control-group">
<textarea data-field="x_descripcion1" class="editor" name="x_descripcion1" id="x_descripcion1" cols="35" rows="4" placeholder="<?php echo $trabajo->descripcion1->PlaceHolder ?>"<?php echo $trabajo->descripcion1->EditAttributes() ?>><?php echo $trabajo->descripcion1->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("ftrabajoadd", "x_descripcion1", 35, 4, <?php echo ($trabajo->descripcion1->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $trabajo->descripcion1->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajo->titulo2->Visible) { // titulo2 ?>
	<tr id="r_titulo2">
		<td><span id="elh_trabajo_titulo2"><?php echo $trabajo->titulo2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $trabajo->titulo2->CellAttributes() ?>>
<span id="el_trabajo_titulo2" class="control-group">
<input type="text" data-field="x_titulo2" name="x_titulo2" id="x_titulo2" size="30" maxlength="40" placeholder="<?php echo $trabajo->titulo2->PlaceHolder ?>" value="<?php echo $trabajo->titulo2->EditValue ?>"<?php echo $trabajo->titulo2->EditAttributes() ?>>
</span>
<?php echo $trabajo->titulo2->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($trabajo->descripcion2->Visible) { // descripcion2 ?>
	<tr id="r_descripcion2">
		<td><span id="elh_trabajo_descripcion2"><?php echo $trabajo->descripcion2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $trabajo->descripcion2->CellAttributes() ?>>
<span id="el_trabajo_descripcion2" class="control-group">
<textarea data-field="x_descripcion2" class="editor" name="x_descripcion2" id="x_descripcion2" cols="35" rows="4" placeholder="<?php echo $trabajo->descripcion2->PlaceHolder ?>"<?php echo $trabajo->descripcion2->EditAttributes() ?>><?php echo $trabajo->descripcion2->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("ftrabajoadd", "x_descripcion2", 35, 4, <?php echo ($trabajo->descripcion2->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $trabajo->descripcion2->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ftrabajoadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$trabajo_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$trabajo_add->Page_Terminate();
?>
