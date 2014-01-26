<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "noticiasinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$noticias_edit = NULL; // Initialize page object first

class cnoticias_edit extends cnoticias {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'noticias';

	// Page object name
	var $PageObjName = 'noticias_edit';

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

		// Table object (noticias)
		if (!isset($GLOBALS["noticias"]) || get_class($GLOBALS["noticias"]) == "cnoticias") {
			$GLOBALS["noticias"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["noticias"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'noticias', TRUE);

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
		$this->id_noticia->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id_noticia"] <> "") {
			$this->id_noticia->setQueryStringValue($_GET["id_noticia"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id_noticia->CurrentValue == "")
			$this->Page_Terminate("noticiaslist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("noticiaslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->id_noticia->FldIsDetailKey)
			$this->id_noticia->setFormValue($objForm->GetValue("x_id_noticia"));
		if (!$this->id_idioma->FldIsDetailKey) {
			$this->id_idioma->setFormValue($objForm->GetValue("x_id_idioma"));
		}
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		}
		if (!$this->titulo->FldIsDetailKey) {
			$this->titulo->setFormValue($objForm->GetValue("x_titulo"));
		}
		if (!$this->amigable->FldIsDetailKey) {
			$this->amigable->setFormValue($objForm->GetValue("x_amigable"));
		}
		if (!$this->descripcion->FldIsDetailKey) {
			$this->descripcion->setFormValue($objForm->GetValue("x_descripcion"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_noticia->CurrentValue = $this->id_noticia->FormValue;
		$this->id_idioma->CurrentValue = $this->id_idioma->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		$this->titulo->CurrentValue = $this->titulo->FormValue;
		$this->amigable->CurrentValue = $this->amigable->FormValue;
		$this->descripcion->CurrentValue = $this->descripcion->FormValue;
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
		$this->id_noticia->setDbValue($rs->fields('id_noticia'));
		$this->id_idioma->setDbValue($rs->fields('id_idioma'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->titulo->setDbValue($rs->fields('titulo'));
		$this->amigable->setDbValue($rs->fields('amigable'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->imagen_url->Upload->DbValue = $rs->fields('imagen_url');
		$this->imagen_url->CurrentValue = $this->imagen_url->Upload->DbValue;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_noticia->DbValue = $row['id_noticia'];
		$this->id_idioma->DbValue = $row['id_idioma'];
		$this->fecha->DbValue = $row['fecha'];
		$this->titulo->DbValue = $row['titulo'];
		$this->amigable->DbValue = $row['amigable'];
		$this->descripcion->DbValue = $row['descripcion'];
		$this->imagen_url->Upload->DbValue = $row['imagen_url'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_noticia
		// id_idioma
		// fecha
		// titulo
		// amigable
		// descripcion
		// imagen_url

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_noticia
			$this->id_noticia->ViewValue = $this->id_noticia->CurrentValue;
			$this->id_noticia->ViewCustomAttributes = "";

			// id_idioma
			if (strval($this->id_idioma->CurrentValue) <> "") {
				$sFilterWrk = "`id_idioma`" . ew_SearchString("=", $this->id_idioma->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT `id_idioma`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `idioma`";
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

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
			$this->fecha->ViewCustomAttributes = "";

			// titulo
			$this->titulo->ViewValue = $this->titulo->CurrentValue;
			$this->titulo->ViewCustomAttributes = "";

			// amigable
			$this->amigable->ViewValue = $this->amigable->CurrentValue;
			$this->amigable->ViewCustomAttributes = "";

			// descripcion
			$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
			$this->descripcion->ViewCustomAttributes = "";

			// imagen_url
			$this->imagen_url->UploadPath = "../subidas/";
			if (!ew_Empty($this->imagen_url->Upload->DbValue)) {
				$this->imagen_url->ViewValue = $this->imagen_url->Upload->DbValue;
			} else {
				$this->imagen_url->ViewValue = "";
			}
			$this->imagen_url->ViewCustomAttributes = "";

			// id_noticia
			$this->id_noticia->LinkCustomAttributes = "";
			$this->id_noticia->HrefValue = "";
			$this->id_noticia->TooltipValue = "";

			// id_idioma
			$this->id_idioma->LinkCustomAttributes = "";
			$this->id_idioma->HrefValue = "";
			$this->id_idioma->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// titulo
			$this->titulo->LinkCustomAttributes = "";
			$this->titulo->HrefValue = "";
			$this->titulo->TooltipValue = "";

			// amigable
			$this->amigable->LinkCustomAttributes = "";
			$this->amigable->HrefValue = "";
			$this->amigable->TooltipValue = "";

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";
			$this->descripcion->TooltipValue = "";

			// imagen_url
			$this->imagen_url->LinkCustomAttributes = "";
			$this->imagen_url->HrefValue = "";
			$this->imagen_url->HrefValue2 = $this->imagen_url->UploadPath . $this->imagen_url->Upload->DbValue;
			$this->imagen_url->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_noticia
			$this->id_noticia->EditCustomAttributes = "";
			$this->id_noticia->EditValue = $this->id_noticia->CurrentValue;
			$this->id_noticia->ViewCustomAttributes = "";

			// id_idioma
			$this->id_idioma->EditCustomAttributes = "";
			if (trim(strval($this->id_idioma->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_idioma`" . ew_SearchString("=", $this->id_idioma->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT DISTINCT `id_idioma`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `idioma`";
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

			// fecha
			// titulo

			$this->titulo->EditCustomAttributes = "";
			$this->titulo->EditValue = ew_HtmlEncode($this->titulo->CurrentValue);
			$this->titulo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->titulo->FldCaption()));

			// amigable
			$this->amigable->EditCustomAttributes = "";
			$this->amigable->EditValue = $this->amigable->CurrentValue;
			$this->amigable->ViewCustomAttributes = "";

			// descripcion
			$this->descripcion->EditCustomAttributes = "";
			$this->descripcion->EditValue = $this->descripcion->CurrentValue;
			$this->descripcion->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->descripcion->FldCaption()));

			// imagen_url
			$this->imagen_url->EditCustomAttributes = "";
			$this->imagen_url->UploadPath = "../subidas/";
			if (!ew_Empty($this->imagen_url->Upload->DbValue)) {
				$this->imagen_url->EditValue = $this->imagen_url->Upload->DbValue;
			} else {
				$this->imagen_url->EditValue = "";
			}
			if (!ew_Empty($this->imagen_url->CurrentValue))
				$this->imagen_url->Upload->FileName = $this->imagen_url->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->imagen_url);

			// Edit refer script
			// id_noticia

			$this->id_noticia->HrefValue = "";

			// id_idioma
			$this->id_idioma->HrefValue = "";

			// fecha
			$this->fecha->HrefValue = "";

			// titulo
			$this->titulo->HrefValue = "";

			// amigable
			$this->amigable->HrefValue = "";

			// descripcion
			$this->descripcion->HrefValue = "";

			// imagen_url
			$this->imagen_url->HrefValue = "";
			$this->imagen_url->HrefValue2 = $this->imagen_url->UploadPath . $this->imagen_url->Upload->DbValue;
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
		if (!$this->titulo->FldIsDetailKey && !is_null($this->titulo->FormValue) && $this->titulo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->titulo->FldCaption());
		}
		if (!$this->descripcion->FldIsDetailKey && !is_null($this->descripcion->FormValue) && $this->descripcion->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->descripcion->FldCaption());
		}
		if (is_null($this->imagen_url->Upload->Value)) {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->imagen_url->FldCaption());
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		if ($this->titulo->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`titulo` = '" . ew_AdjustSql($this->titulo->CurrentValue) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->titulo->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->titulo->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
		if ($this->amigable->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`amigable` = '" . ew_AdjustSql($this->amigable->CurrentValue) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->amigable->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->amigable->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$this->imagen_url->OldUploadPath = "../subidas/";
			$this->imagen_url->UploadPath = $this->imagen_url->OldUploadPath;
			$rsnew = array();

			// id_idioma
			$this->id_idioma->SetDbValueDef($rsnew, $this->id_idioma->CurrentValue, 0, $this->id_idioma->ReadOnly);

			// fecha
			$this->fecha->SetDbValueDef($rsnew, ew_CurrentDate(), ew_CurrentDate());
			$rsnew['fecha'] = &$this->fecha->DbValue;

			// titulo
			$this->titulo->SetDbValueDef($rsnew, $this->titulo->CurrentValue, "", $this->titulo->ReadOnly);

			// descripcion
			$this->descripcion->SetDbValueDef($rsnew, $this->descripcion->CurrentValue, "", $this->descripcion->ReadOnly);

			// imagen_url
			if (!($this->imagen_url->ReadOnly) && !$this->imagen_url->Upload->KeepFile) {
				$this->imagen_url->Upload->DbValue = $rs->fields('imagen_url'); // Get original value
				if ($this->imagen_url->Upload->FileName == "") {
					$rsnew['imagen_url'] = NULL;
				} else {
					$rsnew['imagen_url'] = $this->imagen_url->Upload->FileName;
				}
				$this->imagen_url->ImageWidth = 882; // Resize width
				$this->imagen_url->ImageHeight = 544; // Resize height
			}
			if (!$this->imagen_url->Upload->KeepFile) {
				$this->imagen_url->UploadPath = "../subidas/";
				if (!ew_Empty($this->imagen_url->Upload->Value)) {
					$rsnew['imagen_url'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->imagen_url->UploadPath), $rsnew['imagen_url']); // Get new file name
				}
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
					if (!$this->imagen_url->Upload->KeepFile) {
						if (!ew_Empty($this->imagen_url->Upload->Value)) {
							$this->imagen_url->Upload->Resize($this->imagen_url->ImageWidth, $this->imagen_url->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY);
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
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();

		// imagen_url
		ew_CleanUploadTempPath($this->imagen_url, $this->imagen_url->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "noticiaslist.php", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, ew_CurrentUrl());
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
if (!isset($noticias_edit)) $noticias_edit = new cnoticias_edit();

// Page init
$noticias_edit->Page_Init();

// Page main
$noticias_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$noticias_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var noticias_edit = new ew_Page("noticias_edit");
noticias_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = noticias_edit.PageID; // For backward compatibility

// Form object
var fnoticiasedit = new ew_Form("fnoticiasedit");

// Validate form
fnoticiasedit.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($noticias->id_idioma->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_titulo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($noticias->titulo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_descripcion");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($noticias->descripcion->FldCaption()) ?>");
			felm = this.GetElements("x" + infix + "_imagen_url");
			elm = this.GetElements("fn_x" + infix + "_imagen_url");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($noticias->imagen_url->FldCaption()) ?>");

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
fnoticiasedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnoticiasedit.ValidateRequired = true;
<?php } else { ?>
fnoticiasedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fnoticiasedit.Lists["x_id_idioma"] = {"LinkField":"x_id_idioma","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $noticias_edit->ShowPageHeader(); ?>
<?php
$noticias_edit->ShowMessage();
?>
<form name="fnoticiasedit" id="fnoticiasedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="noticias">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewGrid"><tr><td>
<table id="tbl_noticiasedit" class="table table-bordered table-striped">
<?php if ($noticias->id_noticia->Visible) { // id_noticia ?>
	<tr id="r_id_noticia">
		<td><span id="elh_noticias_id_noticia"><?php echo $noticias->id_noticia->FldCaption() ?></span></td>
		<td<?php echo $noticias->id_noticia->CellAttributes() ?>>
<span id="el_noticias_id_noticia" class="control-group">
<span<?php echo $noticias->id_noticia->ViewAttributes() ?>>
<?php echo $noticias->id_noticia->EditValue ?></span>
</span>
<input type="hidden" data-field="x_id_noticia" name="x_id_noticia" id="x_id_noticia" value="<?php echo ew_HtmlEncode($noticias->id_noticia->CurrentValue) ?>">
<?php echo $noticias->id_noticia->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($noticias->id_idioma->Visible) { // id_idioma ?>
	<tr id="r_id_idioma">
		<td><span id="elh_noticias_id_idioma"><?php echo $noticias->id_idioma->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $noticias->id_idioma->CellAttributes() ?>>
<span id="el_noticias_id_idioma" class="control-group">
<select data-field="x_id_idioma" id="x_id_idioma" name="x_id_idioma"<?php echo $noticias->id_idioma->EditAttributes() ?>>
<?php
if (is_array($noticias->id_idioma->EditValue)) {
	$arwrk = $noticias->id_idioma->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($noticias->id_idioma->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT DISTINCT `id_idioma`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `idioma`";
$sWhereWrk = "";

// Call Lookup selecting
$noticias->Lookup_Selecting($noticias->id_idioma, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_id_idioma" id="s_x_id_idioma" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id_idioma` = {filter_value}"); ?>&amp;t0=18">
</span>
<?php echo $noticias->id_idioma->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($noticias->titulo->Visible) { // titulo ?>
	<tr id="r_titulo">
		<td><span id="elh_noticias_titulo"><?php echo $noticias->titulo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $noticias->titulo->CellAttributes() ?>>
<span id="el_noticias_titulo" class="control-group">
<input type="text" data-field="x_titulo" name="x_titulo" id="x_titulo" size="30" maxlength="100" placeholder="<?php echo $noticias->titulo->PlaceHolder ?>" value="<?php echo $noticias->titulo->EditValue ?>"<?php echo $noticias->titulo->EditAttributes() ?>>
</span>
<?php echo $noticias->titulo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($noticias->amigable->Visible) { // amigable ?>
	<tr id="r_amigable">
		<td><span id="elh_noticias_amigable"><?php echo $noticias->amigable->FldCaption() ?></span></td>
		<td<?php echo $noticias->amigable->CellAttributes() ?>>
<span id="el_noticias_amigable" class="control-group">
<span<?php echo $noticias->amigable->ViewAttributes() ?>>
<?php echo $noticias->amigable->EditValue ?></span>
</span>
<input type="hidden" data-field="x_amigable" name="x_amigable" id="x_amigable" value="<?php echo ew_HtmlEncode($noticias->amigable->CurrentValue) ?>">
<?php echo $noticias->amigable->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($noticias->descripcion->Visible) { // descripcion ?>
	<tr id="r_descripcion">
		<td><span id="elh_noticias_descripcion"><?php echo $noticias->descripcion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $noticias->descripcion->CellAttributes() ?>>
<span id="el_noticias_descripcion" class="control-group">
<textarea data-field="x_descripcion" class="editor" name="x_descripcion" id="x_descripcion" cols="45" rows="5" placeholder="<?php echo $noticias->descripcion->PlaceHolder ?>"<?php echo $noticias->descripcion->EditAttributes() ?>><?php echo $noticias->descripcion->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fnoticiasedit", "x_descripcion", 45, 5, <?php echo ($noticias->descripcion->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $noticias->descripcion->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($noticias->imagen_url->Visible) { // imagen_url ?>
	<tr id="r_imagen_url">
		<td><span id="elh_noticias_imagen_url"><?php echo $noticias->imagen_url->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $noticias->imagen_url->CellAttributes() ?>>
<div id="el_noticias_imagen_url" class="control-group">
<span id="fd_x_imagen_url">
<span class="btn btn-small fileinput-button"<?php if ($noticias->imagen_url->ReadOnly || $noticias->imagen_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_imagen_url" name="x_imagen_url" id="x_imagen_url">
</span>
<input type="hidden" name="fn_x_imagen_url" id= "fn_x_imagen_url" value="<?php echo $noticias->imagen_url->Upload->FileName ?>">
<?php if (@$_POST["fa_x_imagen_url"] == "0") { ?>
<input type="hidden" name="fa_x_imagen_url" id= "fa_x_imagen_url" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_imagen_url" id= "fa_x_imagen_url" value="1">
<?php } ?>
<input type="hidden" name="fs_x_imagen_url" id= "fs_x_imagen_url" value="256">
</span>
<table id="ft_x_imagen_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $noticias->imagen_url->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fnoticiasedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$noticias_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$noticias_edit->Page_Terminate();
?>
