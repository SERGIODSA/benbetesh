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

$benbetesh_add = NULL; // Initialize page object first

class cbenbetesh_add extends cbenbetesh {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'benbetesh';

	// Page object name
	var $PageObjName = 'benbetesh_add';

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

		// Table object (benbetesh)
		if (!isset($GLOBALS["benbetesh"]) || get_class($GLOBALS["benbetesh"]) == "cbenbetesh") {
			$GLOBALS["benbetesh"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["benbetesh"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'benbetesh', TRUE);

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
			if (@$_GET["id_tienda"] != "") {
				$this->id_tienda->setQueryStringValue($_GET["id_tienda"]);
				$this->setKey("id_tienda", $this->id_tienda->CurrentValue); // Set up key
			} else {
				$this->setKey("id_tienda", ""); // Clear key
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
					$this->Page_Terminate("benbeteshlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "benbeteshview.php")
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
		$this->imagen_url->Upload->Index = $objForm->Index;
		if ($this->imagen_url->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->imagen_url->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->imagen_url->CurrentValue = $this->imagen_url->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id_idioma->CurrentValue = NULL;
		$this->id_idioma->OldValue = $this->id_idioma->CurrentValue;
		$this->imagen_url->Upload->DbValue = NULL;
		$this->imagen_url->OldValue = $this->imagen_url->Upload->DbValue;
		$this->imagen_url->CurrentValue = NULL; // Clear file related field
		$this->titulo->CurrentValue = NULL;
		$this->titulo->OldValue = $this->titulo->CurrentValue;
		$this->horario->CurrentValue = NULL;
		$this->horario->OldValue = $this->horario->CurrentValue;
		$this->telefono->CurrentValue = NULL;
		$this->telefono->OldValue = $this->telefono->CurrentValue;
		$this->dias->CurrentValue = NULL;
		$this->dias->OldValue = $this->dias->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->id_idioma->FldIsDetailKey) {
			$this->id_idioma->setFormValue($objForm->GetValue("x_id_idioma"));
		}
		if (!$this->titulo->FldIsDetailKey) {
			$this->titulo->setFormValue($objForm->GetValue("x_titulo"));
		}
		if (!$this->horario->FldIsDetailKey) {
			$this->horario->setFormValue($objForm->GetValue("x_horario"));
		}
		if (!$this->telefono->FldIsDetailKey) {
			$this->telefono->setFormValue($objForm->GetValue("x_telefono"));
		}
		if (!$this->dias->FldIsDetailKey) {
			$this->dias->setFormValue($objForm->GetValue("x_dias"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id_idioma->CurrentValue = $this->id_idioma->FormValue;
		$this->titulo->CurrentValue = $this->titulo->FormValue;
		$this->horario->CurrentValue = $this->horario->FormValue;
		$this->telefono->CurrentValue = $this->telefono->FormValue;
		$this->dias->CurrentValue = $this->dias->FormValue;
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

			// imagen_url
			$this->imagen_url->EditCustomAttributes = "";
			if (!ew_Empty($this->imagen_url->Upload->DbValue)) {
				$this->imagen_url->EditValue = $this->imagen_url->Upload->DbValue;
			} else {
				$this->imagen_url->EditValue = "";
			}
			if (!ew_Empty($this->imagen_url->CurrentValue))
				$this->imagen_url->Upload->FileName = $this->imagen_url->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->imagen_url);

			// titulo
			$this->titulo->EditCustomAttributes = "";
			$this->titulo->EditValue = ew_HtmlEncode($this->titulo->CurrentValue);
			$this->titulo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->titulo->FldCaption()));

			// horario
			$this->horario->EditCustomAttributes = "";
			$this->horario->EditValue = ew_HtmlEncode($this->horario->CurrentValue);
			$this->horario->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->horario->FldCaption()));

			// telefono
			$this->telefono->EditCustomAttributes = "";
			$this->telefono->EditValue = ew_HtmlEncode($this->telefono->CurrentValue);
			$this->telefono->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->telefono->FldCaption()));

			// dias
			$this->dias->EditCustomAttributes = "";
			$this->dias->EditValue = ew_HtmlEncode($this->dias->CurrentValue);
			$this->dias->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dias->FldCaption()));

			// Edit refer script
			// id_idioma

			$this->id_idioma->HrefValue = "";

			// imagen_url
			$this->imagen_url->HrefValue = "";
			$this->imagen_url->HrefValue2 = $this->imagen_url->UploadPath . $this->imagen_url->Upload->DbValue;

			// titulo
			$this->titulo->HrefValue = "";

			// horario
			$this->horario->HrefValue = "";

			// telefono
			$this->telefono->HrefValue = "";

			// dias
			$this->dias->HrefValue = "";
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
		if (is_null($this->imagen_url->Upload->Value)) {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->imagen_url->FldCaption());
		}
		if (!$this->titulo->FldIsDetailKey && !is_null($this->titulo->FormValue) && $this->titulo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->titulo->FldCaption());
		}
		if (!$this->horario->FldIsDetailKey && !is_null($this->horario->FormValue) && $this->horario->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->horario->FldCaption());
		}
		if (!$this->telefono->FldIsDetailKey && !is_null($this->telefono->FormValue) && $this->telefono->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->telefono->FldCaption());
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

		// imagen_url
		if (!$this->imagen_url->Upload->KeepFile) {
			if ($this->imagen_url->Upload->FileName == "") {
				$rsnew['imagen_url'] = NULL;
			} else {
				$rsnew['imagen_url'] = $this->imagen_url->Upload->FileName;
			}
		}

		// titulo
		$this->titulo->SetDbValueDef($rsnew, $this->titulo->CurrentValue, "", FALSE);

		// horario
		$this->horario->SetDbValueDef($rsnew, $this->horario->CurrentValue, "", FALSE);

		// telefono
		$this->telefono->SetDbValueDef($rsnew, $this->telefono->CurrentValue, "", FALSE);

		// dias
		$this->dias->SetDbValueDef($rsnew, $this->dias->CurrentValue, NULL, FALSE);
		if (!$this->imagen_url->Upload->KeepFile) {
			if (!ew_Empty($this->imagen_url->Upload->Value)) {
				$rsnew['imagen_url'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->imagen_url->UploadPath), $rsnew['imagen_url']); // Get new file name
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if (!$this->imagen_url->Upload->KeepFile) {
					if (!ew_Empty($this->imagen_url->Upload->Value)) {
						$this->imagen_url->Upload->SaveToFile($this->imagen_url->UploadPath, $rsnew['imagen_url'], TRUE);
					}
				}
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
			$this->id_tienda->setDbValue($conn->Insert_ID());
			$rsnew['id_tienda'] = $this->id_tienda->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// imagen_url
		ew_CleanUploadTempPath($this->imagen_url, $this->imagen_url->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "benbeteshlist.php", $this->TableVar, TRUE);
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
if (!isset($benbetesh_add)) $benbetesh_add = new cbenbetesh_add();

// Page init
$benbetesh_add->Page_Init();

// Page main
$benbetesh_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$benbetesh_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var benbetesh_add = new ew_Page("benbetesh_add");
benbetesh_add.PageID = "add"; // Page ID
var EW_PAGE_ID = benbetesh_add.PageID; // For backward compatibility

// Form object
var fbenbeteshadd = new ew_Form("fbenbeteshadd");

// Validate form
fbenbeteshadd.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($benbetesh->id_idioma->FldCaption()) ?>");
			felm = this.GetElements("x" + infix + "_imagen_url");
			elm = this.GetElements("fn_x" + infix + "_imagen_url");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($benbetesh->imagen_url->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_titulo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($benbetesh->titulo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_horario");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($benbetesh->horario->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_telefono");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($benbetesh->telefono->FldCaption()) ?>");

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
fbenbeteshadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbenbeteshadd.ValidateRequired = true;
<?php } else { ?>
fbenbeteshadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fbenbeteshadd.Lists["x_id_idioma"] = {"LinkField":"x_id_idioma","Ajax":null,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $benbetesh_add->ShowPageHeader(); ?>
<?php
$benbetesh_add->ShowMessage();
?>
<form name="fbenbeteshadd" id="fbenbeteshadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="benbetesh">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewGrid"><tr><td>
<table id="tbl_benbeteshadd" class="table table-bordered table-striped">
<?php if ($benbetesh->id_idioma->Visible) { // id_idioma ?>
	<tr id="r_id_idioma">
		<td><span id="elh_benbetesh_id_idioma"><?php echo $benbetesh->id_idioma->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $benbetesh->id_idioma->CellAttributes() ?>>
<span id="el_benbetesh_id_idioma" class="control-group">
<select data-field="x_id_idioma" id="x_id_idioma" name="x_id_idioma"<?php echo $benbetesh->id_idioma->EditAttributes() ?>>
<?php
if (is_array($benbetesh->id_idioma->EditValue)) {
	$arwrk = $benbetesh->id_idioma->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($benbetesh->id_idioma->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fbenbeteshadd.Lists["x_id_idioma"].Options = <?php echo (is_array($benbetesh->id_idioma->EditValue)) ? ew_ArrayToJson($benbetesh->id_idioma->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $benbetesh->id_idioma->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($benbetesh->imagen_url->Visible) { // imagen_url ?>
	<tr id="r_imagen_url">
		<td><span id="elh_benbetesh_imagen_url"><?php echo $benbetesh->imagen_url->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $benbetesh->imagen_url->CellAttributes() ?>>
<div id="el_benbetesh_imagen_url" class="control-group">
<span id="fd_x_imagen_url">
<span class="btn btn-small fileinput-button"<?php if ($benbetesh->imagen_url->ReadOnly || $benbetesh->imagen_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_imagen_url" name="x_imagen_url" id="x_imagen_url">
</span>
<input type="hidden" name="fn_x_imagen_url" id= "fn_x_imagen_url" value="<?php echo $benbetesh->imagen_url->Upload->FileName ?>">
<input type="hidden" name="fa_x_imagen_url" id= "fa_x_imagen_url" value="0">
<input type="hidden" name="fs_x_imagen_url" id= "fs_x_imagen_url" value="256">
</span>
<table id="ft_x_imagen_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $benbetesh->imagen_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($benbetesh->titulo->Visible) { // titulo ?>
	<tr id="r_titulo">
		<td><span id="elh_benbetesh_titulo"><?php echo $benbetesh->titulo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $benbetesh->titulo->CellAttributes() ?>>
<span id="el_benbetesh_titulo" class="control-group">
<input type="text" data-field="x_titulo" name="x_titulo" id="x_titulo" size="150" maxlength="100" placeholder="<?php echo $benbetesh->titulo->PlaceHolder ?>" value="<?php echo $benbetesh->titulo->EditValue ?>"<?php echo $benbetesh->titulo->EditAttributes() ?>>
</span>
<?php echo $benbetesh->titulo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($benbetesh->horario->Visible) { // horario ?>
	<tr id="r_horario">
		<td><span id="elh_benbetesh_horario"><?php echo $benbetesh->horario->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $benbetesh->horario->CellAttributes() ?>>
<span id="el_benbetesh_horario" class="control-group">
<input type="text" data-field="x_horario" name="x_horario" id="x_horario" size="150" maxlength="150" placeholder="<?php echo $benbetesh->horario->PlaceHolder ?>" value="<?php echo $benbetesh->horario->EditValue ?>"<?php echo $benbetesh->horario->EditAttributes() ?>>
</span>
<?php echo $benbetesh->horario->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($benbetesh->telefono->Visible) { // telefono ?>
	<tr id="r_telefono">
		<td><span id="elh_benbetesh_telefono"><?php echo $benbetesh->telefono->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $benbetesh->telefono->CellAttributes() ?>>
<span id="el_benbetesh_telefono" class="control-group">
<input type="text" data-field="x_telefono" name="x_telefono" id="x_telefono" size="50" maxlength="50" placeholder="<?php echo $benbetesh->telefono->PlaceHolder ?>" value="<?php echo $benbetesh->telefono->EditValue ?>"<?php echo $benbetesh->telefono->EditAttributes() ?>>
</span>
<?php echo $benbetesh->telefono->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($benbetesh->dias->Visible) { // dias ?>
	<tr id="r_dias">
		<td><span id="elh_benbetesh_dias"><?php echo $benbetesh->dias->FldCaption() ?></span></td>
		<td<?php echo $benbetesh->dias->CellAttributes() ?>>
<span id="el_benbetesh_dias" class="control-group">
<input type="text" data-field="x_dias" name="x_dias" id="x_dias" size="50" maxlength="50" placeholder="<?php echo $benbetesh->dias->PlaceHolder ?>" value="<?php echo $benbetesh->dias->EditValue ?>"<?php echo $benbetesh->dias->EditAttributes() ?>>
</span>
<?php echo $benbetesh->dias->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fbenbeteshadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$benbetesh_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$benbetesh_add->Page_Terminate();
?>
