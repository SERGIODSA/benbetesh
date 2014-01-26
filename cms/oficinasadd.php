<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "oficinasinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$oficinas_add = NULL; // Initialize page object first

class coficinas_add extends coficinas {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'oficinas';

	// Page object name
	var $PageObjName = 'oficinas_add';

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

		// Table object (oficinas)
		if (!isset($GLOBALS["oficinas"]) || get_class($GLOBALS["oficinas"]) == "coficinas") {
			$GLOBALS["oficinas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["oficinas"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'oficinas', TRUE);

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
			if (@$_GET["id_oficina"] != "") {
				$this->id_oficina->setQueryStringValue($_GET["id_oficina"]);
				$this->setKey("id_oficina", $this->id_oficina->CurrentValue); // Set up key
			} else {
				$this->setKey("id_oficina", ""); // Clear key
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
					$this->Page_Terminate("oficinaslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "oficinasview.php")
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
		$this->direccion->CurrentValue = NULL;
		$this->direccion->OldValue = $this->direccion->CurrentValue;
		$this->lugar_titulo->CurrentValue = NULL;
		$this->lugar_titulo->OldValue = $this->lugar_titulo->CurrentValue;
		$this->telefono->CurrentValue = NULL;
		$this->telefono->OldValue = $this->telefono->CurrentValue;
		$this->correo->CurrentValue = NULL;
		$this->correo->OldValue = $this->correo->CurrentValue;
		$this->tienda->CurrentValue = NULL;
		$this->tienda->OldValue = $this->tienda->CurrentValue;
		$this->fax->CurrentValue = NULL;
		$this->fax->OldValue = $this->fax->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_idioma->FldIsDetailKey) {
			$this->id_idioma->setFormValue($objForm->GetValue("x_id_idioma"));
		}
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue($objForm->GetValue("x_direccion"));
		}
		if (!$this->lugar_titulo->FldIsDetailKey) {
			$this->lugar_titulo->setFormValue($objForm->GetValue("x_lugar_titulo"));
		}
		if (!$this->telefono->FldIsDetailKey) {
			$this->telefono->setFormValue($objForm->GetValue("x_telefono"));
		}
		if (!$this->correo->FldIsDetailKey) {
			$this->correo->setFormValue($objForm->GetValue("x_correo"));
		}
		if (!$this->tienda->FldIsDetailKey) {
			$this->tienda->setFormValue($objForm->GetValue("x_tienda"));
		}
		if (!$this->fax->FldIsDetailKey) {
			$this->fax->setFormValue($objForm->GetValue("x_fax"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id_idioma->CurrentValue = $this->id_idioma->FormValue;
		$this->direccion->CurrentValue = $this->direccion->FormValue;
		$this->lugar_titulo->CurrentValue = $this->lugar_titulo->FormValue;
		$this->telefono->CurrentValue = $this->telefono->FormValue;
		$this->correo->CurrentValue = $this->correo->FormValue;
		$this->tienda->CurrentValue = $this->tienda->FormValue;
		$this->fax->CurrentValue = $this->fax->FormValue;
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
		$this->id_oficina->setDbValue($rs->fields('id_oficina'));
		$this->id_idioma->setDbValue($rs->fields('id_idioma'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->lugar_titulo->setDbValue($rs->fields('lugar_titulo'));
		$this->telefono->setDbValue($rs->fields('telefono'));
		$this->correo->setDbValue($rs->fields('correo'));
		$this->tienda->setDbValue($rs->fields('tienda'));
		$this->fax->setDbValue($rs->fields('fax'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_oficina->DbValue = $row['id_oficina'];
		$this->id_idioma->DbValue = $row['id_idioma'];
		$this->direccion->DbValue = $row['direccion'];
		$this->lugar_titulo->DbValue = $row['lugar_titulo'];
		$this->telefono->DbValue = $row['telefono'];
		$this->correo->DbValue = $row['correo'];
		$this->tienda->DbValue = $row['tienda'];
		$this->fax->DbValue = $row['fax'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_oficina")) <> "")
			$this->id_oficina->CurrentValue = $this->getKey("id_oficina"); // id_oficina
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
		// id_oficina
		// id_idioma
		// direccion
		// lugar_titulo
		// telefono
		// correo
		// tienda
		// fax

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

			// direccion
			$this->direccion->ViewValue = $this->direccion->CurrentValue;
			$this->direccion->ViewCustomAttributes = "";

			// lugar_titulo
			$this->lugar_titulo->ViewValue = $this->lugar_titulo->CurrentValue;
			$this->lugar_titulo->ViewCustomAttributes = "";

			// telefono
			$this->telefono->ViewValue = $this->telefono->CurrentValue;
			$this->telefono->ViewCustomAttributes = "";

			// correo
			$this->correo->ViewValue = $this->correo->CurrentValue;
			$this->correo->ViewCustomAttributes = "";

			// tienda
			$this->tienda->ViewValue = $this->tienda->CurrentValue;
			$this->tienda->ViewCustomAttributes = "";

			// fax
			$this->fax->ViewValue = $this->fax->CurrentValue;
			$this->fax->ViewCustomAttributes = "";

			// id_idioma
			$this->id_idioma->LinkCustomAttributes = "";
			$this->id_idioma->HrefValue = "";
			$this->id_idioma->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// lugar_titulo
			$this->lugar_titulo->LinkCustomAttributes = "";
			$this->lugar_titulo->HrefValue = "";
			$this->lugar_titulo->TooltipValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";
			$this->telefono->TooltipValue = "";

			// correo
			$this->correo->LinkCustomAttributes = "";
			$this->correo->HrefValue = "";
			$this->correo->TooltipValue = "";

			// tienda
			$this->tienda->LinkCustomAttributes = "";
			$this->tienda->HrefValue = "";
			$this->tienda->TooltipValue = "";

			// fax
			$this->fax->LinkCustomAttributes = "";
			$this->fax->HrefValue = "";
			$this->fax->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_idioma
			$this->id_idioma->EditCustomAttributes = "";
			if (trim(strval($this->id_idioma->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_idioma`" . ew_SearchString("=", $this->id_idioma->CurrentValue, EW_DATATYPE_NUMBER);
			}
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

			// direccion
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = $this->direccion->CurrentValue;
			$this->direccion->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->direccion->FldCaption()));

			// lugar_titulo
			$this->lugar_titulo->EditCustomAttributes = "";
			$this->lugar_titulo->EditValue = ew_HtmlEncode($this->lugar_titulo->CurrentValue);
			$this->lugar_titulo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->lugar_titulo->FldCaption()));

			// telefono
			$this->telefono->EditCustomAttributes = "";
			$this->telefono->EditValue = ew_HtmlEncode($this->telefono->CurrentValue);
			$this->telefono->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->telefono->FldCaption()));

			// correo
			$this->correo->EditCustomAttributes = "";
			$this->correo->EditValue = ew_HtmlEncode($this->correo->CurrentValue);
			$this->correo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->correo->FldCaption()));

			// tienda
			$this->tienda->EditCustomAttributes = "";
			$this->tienda->EditValue = ew_HtmlEncode($this->tienda->CurrentValue);
			$this->tienda->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->tienda->FldCaption()));

			// fax
			$this->fax->EditCustomAttributes = "";
			$this->fax->EditValue = ew_HtmlEncode($this->fax->CurrentValue);
			$this->fax->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->fax->FldCaption()));

			// Edit refer script
			// id_idioma

			$this->id_idioma->HrefValue = "";

			// direccion
			$this->direccion->HrefValue = "";

			// lugar_titulo
			$this->lugar_titulo->HrefValue = "";

			// telefono
			$this->telefono->HrefValue = "";

			// correo
			$this->correo->HrefValue = "";

			// tienda
			$this->tienda->HrefValue = "";

			// fax
			$this->fax->HrefValue = "";
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
		if (!$this->direccion->FldIsDetailKey && !is_null($this->direccion->FormValue) && $this->direccion->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->direccion->FldCaption());
		}
		if (!$this->lugar_titulo->FldIsDetailKey && !is_null($this->lugar_titulo->FormValue) && $this->lugar_titulo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->lugar_titulo->FldCaption());
		}
		if (!$this->telefono->FldIsDetailKey && !is_null($this->telefono->FormValue) && $this->telefono->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->telefono->FldCaption());
		}
		if (!ew_CheckEmail($this->correo->FormValue)) {
			ew_AddMessage($gsFormError, $this->correo->FldErrMsg());
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

		// direccion
		$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, "", FALSE);

		// lugar_titulo
		$this->lugar_titulo->SetDbValueDef($rsnew, $this->lugar_titulo->CurrentValue, "", FALSE);

		// telefono
		$this->telefono->SetDbValueDef($rsnew, $this->telefono->CurrentValue, "", FALSE);

		// correo
		$this->correo->SetDbValueDef($rsnew, $this->correo->CurrentValue, NULL, FALSE);

		// tienda
		$this->tienda->SetDbValueDef($rsnew, $this->tienda->CurrentValue, NULL, FALSE);

		// fax
		$this->fax->SetDbValueDef($rsnew, $this->fax->CurrentValue, NULL, FALSE);

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
			$this->id_oficina->setDbValue($conn->Insert_ID());
			$rsnew['id_oficina'] = $this->id_oficina->DbValue;
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
		$Breadcrumb->Add("list", $this->TableVar, "oficinaslist.php", $this->TableVar, TRUE);
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
if (!isset($oficinas_add)) $oficinas_add = new coficinas_add();

// Page init
$oficinas_add->Page_Init();

// Page main
$oficinas_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$oficinas_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var oficinas_add = new ew_Page("oficinas_add");
oficinas_add.PageID = "add"; // Page ID
var EW_PAGE_ID = oficinas_add.PageID; // For backward compatibility

// Form object
var foficinasadd = new ew_Form("foficinasadd");

// Validate form
foficinasadd.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($oficinas->id_idioma->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_direccion");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($oficinas->direccion->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_lugar_titulo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($oficinas->lugar_titulo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_telefono");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($oficinas->telefono->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_correo");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($oficinas->correo->FldErrMsg()) ?>");

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
foficinasadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
foficinasadd.ValidateRequired = true;
<?php } else { ?>
foficinasadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
foficinasadd.Lists["x_id_idioma"] = {"LinkField":"x_id_idioma","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $oficinas_add->ShowPageHeader(); ?>
<?php
$oficinas_add->ShowMessage();
?>
<form name="foficinasadd" id="foficinasadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="oficinas">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewGrid"><tr><td>
<table id="tbl_oficinasadd" class="table table-bordered table-striped">
<?php if ($oficinas->id_idioma->Visible) { // id_idioma ?>
	<tr id="r_id_idioma">
		<td><span id="elh_oficinas_id_idioma"><?php echo $oficinas->id_idioma->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $oficinas->id_idioma->CellAttributes() ?>>
<span id="el_oficinas_id_idioma" class="control-group">
<select data-field="x_id_idioma" id="x_id_idioma" name="x_id_idioma"<?php echo $oficinas->id_idioma->EditAttributes() ?>>
<?php
if (is_array($oficinas->id_idioma->EditValue)) {
	$arwrk = $oficinas->id_idioma->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($oficinas->id_idioma->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php
$sSqlWrk = "SELECT `id_idioma`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `idioma`";
$sWhereWrk = "";

// Call Lookup selecting
$oficinas->Lookup_Selecting($oficinas->id_idioma, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_id_idioma" id="s_x_id_idioma" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id_idioma` = {filter_value}"); ?>&amp;t0=18">
</span>
<?php echo $oficinas->id_idioma->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($oficinas->direccion->Visible) { // direccion ?>
	<tr id="r_direccion">
		<td><span id="elh_oficinas_direccion"><?php echo $oficinas->direccion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $oficinas->direccion->CellAttributes() ?>>
<span id="el_oficinas_direccion" class="control-group">
<textarea data-field="x_direccion" class="editor" name="x_direccion" id="x_direccion" cols="4" rows="35" placeholder="<?php echo $oficinas->direccion->PlaceHolder ?>"<?php echo $oficinas->direccion->EditAttributes() ?>><?php echo $oficinas->direccion->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("foficinasadd", "x_direccion", 0, 0, <?php echo ($oficinas->direccion->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $oficinas->direccion->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($oficinas->lugar_titulo->Visible) { // lugar_titulo ?>
	<tr id="r_lugar_titulo">
		<td><span id="elh_oficinas_lugar_titulo"><?php echo $oficinas->lugar_titulo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $oficinas->lugar_titulo->CellAttributes() ?>>
<span id="el_oficinas_lugar_titulo" class="control-group">
<input type="text" data-field="x_lugar_titulo" name="x_lugar_titulo" id="x_lugar_titulo" size="30" maxlength="50" placeholder="<?php echo $oficinas->lugar_titulo->PlaceHolder ?>" value="<?php echo $oficinas->lugar_titulo->EditValue ?>"<?php echo $oficinas->lugar_titulo->EditAttributes() ?>>
</span>
<?php echo $oficinas->lugar_titulo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($oficinas->telefono->Visible) { // telefono ?>
	<tr id="r_telefono">
		<td><span id="elh_oficinas_telefono"><?php echo $oficinas->telefono->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $oficinas->telefono->CellAttributes() ?>>
<span id="el_oficinas_telefono" class="control-group">
<input type="text" data-field="x_telefono" name="x_telefono" id="x_telefono" size="30" maxlength="30" placeholder="<?php echo $oficinas->telefono->PlaceHolder ?>" value="<?php echo $oficinas->telefono->EditValue ?>"<?php echo $oficinas->telefono->EditAttributes() ?>>
</span>
<?php echo $oficinas->telefono->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($oficinas->correo->Visible) { // correo ?>
	<tr id="r_correo">
		<td><span id="elh_oficinas_correo"><?php echo $oficinas->correo->FldCaption() ?></span></td>
		<td<?php echo $oficinas->correo->CellAttributes() ?>>
<span id="el_oficinas_correo" class="control-group">
<input type="text" data-field="x_correo" name="x_correo" id="x_correo" size="30" maxlength="30" placeholder="<?php echo $oficinas->correo->PlaceHolder ?>" value="<?php echo $oficinas->correo->EditValue ?>"<?php echo $oficinas->correo->EditAttributes() ?>>
</span>
<?php echo $oficinas->correo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($oficinas->tienda->Visible) { // tienda ?>
	<tr id="r_tienda">
		<td><span id="elh_oficinas_tienda"><?php echo $oficinas->tienda->FldCaption() ?></span></td>
		<td<?php echo $oficinas->tienda->CellAttributes() ?>>
<span id="el_oficinas_tienda" class="control-group">
<input type="text" data-field="x_tienda" name="x_tienda" id="x_tienda" size="30" maxlength="50" placeholder="<?php echo $oficinas->tienda->PlaceHolder ?>" value="<?php echo $oficinas->tienda->EditValue ?>"<?php echo $oficinas->tienda->EditAttributes() ?>>
</span>
<?php echo $oficinas->tienda->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($oficinas->fax->Visible) { // fax ?>
	<tr id="r_fax">
		<td><span id="elh_oficinas_fax"><?php echo $oficinas->fax->FldCaption() ?></span></td>
		<td<?php echo $oficinas->fax->CellAttributes() ?>>
<span id="el_oficinas_fax" class="control-group">
<input type="text" data-field="x_fax" name="x_fax" id="x_fax" size="30" maxlength="30" placeholder="<?php echo $oficinas->fax->PlaceHolder ?>" value="<?php echo $oficinas->fax->EditValue ?>"<?php echo $oficinas->fax->EditAttributes() ?>>
</span>
<?php echo $oficinas->fax->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
foficinasadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$oficinas_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$oficinas_add->Page_Terminate();
?>
