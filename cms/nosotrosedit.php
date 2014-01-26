<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "nosotrosinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$nosotros_edit = NULL; // Initialize page object first

class cnosotros_edit extends cnosotros {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'nosotros';

	// Page object name
	var $PageObjName = 'nosotros_edit';

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

		// Table object (nosotros)
		if (!isset($GLOBALS["nosotros"]) || get_class($GLOBALS["nosotros"]) == "cnosotros") {
			$GLOBALS["nosotros"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["nosotros"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'nosotros', TRUE);

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
		$this->id_nosotros->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (@$_GET["id_nosotros"] <> "") {
			$this->id_nosotros->setQueryStringValue($_GET["id_nosotros"]);
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
		if ($this->id_nosotros->CurrentValue == "")
			$this->Page_Terminate("nosotroslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("nosotroslist.php"); // No matching record, return to list
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
		$this->imagen1_url->Upload->Index = $objForm->Index;
		if ($this->imagen1_url->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->imagen1_url->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->imagen1_url->CurrentValue = $this->imagen1_url->Upload->FileName;
		$this->imagen2_url->Upload->Index = $objForm->Index;
		if ($this->imagen2_url->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->imagen2_url->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->imagen2_url->CurrentValue = $this->imagen2_url->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->id_nosotros->FldIsDetailKey)
			$this->id_nosotros->setFormValue($objForm->GetValue("x_id_nosotros"));
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
		if (!$this->titulo3->FldIsDetailKey) {
			$this->titulo3->setFormValue($objForm->GetValue("x_titulo3"));
		}
		if (!$this->descripcion3->FldIsDetailKey) {
			$this->descripcion3->setFormValue($objForm->GetValue("x_descripcion3"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_nosotros->CurrentValue = $this->id_nosotros->FormValue;
		$this->id_idioma->CurrentValue = $this->id_idioma->FormValue;
		$this->titulo1->CurrentValue = $this->titulo1->FormValue;
		$this->descripcion1->CurrentValue = $this->descripcion1->FormValue;
		$this->titulo2->CurrentValue = $this->titulo2->FormValue;
		$this->descripcion2->CurrentValue = $this->descripcion2->FormValue;
		$this->titulo3->CurrentValue = $this->titulo3->FormValue;
		$this->descripcion3->CurrentValue = $this->descripcion3->FormValue;
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
		$this->id_nosotros->setDbValue($rs->fields('id_nosotros'));
		$this->id_idioma->setDbValue($rs->fields('id_idioma'));
		$this->imagen1_url->Upload->DbValue = $rs->fields('imagen1_url');
		$this->imagen1_url->CurrentValue = $this->imagen1_url->Upload->DbValue;
		$this->imagen2_url->Upload->DbValue = $rs->fields('imagen2_url');
		$this->imagen2_url->CurrentValue = $this->imagen2_url->Upload->DbValue;
		$this->titulo1->setDbValue($rs->fields('titulo1'));
		$this->descripcion1->setDbValue($rs->fields('descripcion1'));
		$this->titulo2->setDbValue($rs->fields('titulo2'));
		$this->descripcion2->setDbValue($rs->fields('descripcion2'));
		$this->titulo3->setDbValue($rs->fields('titulo3'));
		$this->descripcion3->setDbValue($rs->fields('descripcion3'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_nosotros->DbValue = $row['id_nosotros'];
		$this->id_idioma->DbValue = $row['id_idioma'];
		$this->imagen1_url->Upload->DbValue = $row['imagen1_url'];
		$this->imagen2_url->Upload->DbValue = $row['imagen2_url'];
		$this->titulo1->DbValue = $row['titulo1'];
		$this->descripcion1->DbValue = $row['descripcion1'];
		$this->titulo2->DbValue = $row['titulo2'];
		$this->descripcion2->DbValue = $row['descripcion2'];
		$this->titulo3->DbValue = $row['titulo3'];
		$this->descripcion3->DbValue = $row['descripcion3'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_nosotros
		// id_idioma
		// imagen1_url
		// imagen2_url
		// titulo1
		// descripcion1
		// titulo2
		// descripcion2
		// titulo3
		// descripcion3

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_nosotros
			$this->id_nosotros->ViewValue = $this->id_nosotros->CurrentValue;
			$this->id_nosotros->ViewCustomAttributes = "";

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

			// imagen1_url
			if (!ew_Empty($this->imagen1_url->Upload->DbValue)) {
				$this->imagen1_url->ViewValue = $this->imagen1_url->Upload->DbValue;
			} else {
				$this->imagen1_url->ViewValue = "";
			}
			$this->imagen1_url->ViewCustomAttributes = "";

			// imagen2_url
			if (!ew_Empty($this->imagen2_url->Upload->DbValue)) {
				$this->imagen2_url->ViewValue = $this->imagen2_url->Upload->DbValue;
			} else {
				$this->imagen2_url->ViewValue = "";
			}
			$this->imagen2_url->ViewCustomAttributes = "";

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

			// titulo3
			$this->titulo3->ViewValue = $this->titulo3->CurrentValue;
			$this->titulo3->ViewCustomAttributes = "";

			// descripcion3
			$this->descripcion3->ViewValue = $this->descripcion3->CurrentValue;
			$this->descripcion3->ViewCustomAttributes = "";

			// id_nosotros
			$this->id_nosotros->LinkCustomAttributes = "";
			$this->id_nosotros->HrefValue = "";
			$this->id_nosotros->TooltipValue = "";

			// id_idioma
			$this->id_idioma->LinkCustomAttributes = "";
			$this->id_idioma->HrefValue = "";
			$this->id_idioma->TooltipValue = "";

			// imagen1_url
			$this->imagen1_url->LinkCustomAttributes = "";
			$this->imagen1_url->HrefValue = "";
			$this->imagen1_url->HrefValue2 = $this->imagen1_url->UploadPath . $this->imagen1_url->Upload->DbValue;
			$this->imagen1_url->TooltipValue = "";

			// imagen2_url
			$this->imagen2_url->LinkCustomAttributes = "";
			$this->imagen2_url->HrefValue = "";
			$this->imagen2_url->HrefValue2 = $this->imagen2_url->UploadPath . $this->imagen2_url->Upload->DbValue;
			$this->imagen2_url->TooltipValue = "";

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

			// titulo3
			$this->titulo3->LinkCustomAttributes = "";
			$this->titulo3->HrefValue = "";
			$this->titulo3->TooltipValue = "";

			// descripcion3
			$this->descripcion3->LinkCustomAttributes = "";
			$this->descripcion3->HrefValue = "";
			$this->descripcion3->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_nosotros
			$this->id_nosotros->EditCustomAttributes = "";
			$this->id_nosotros->EditValue = $this->id_nosotros->CurrentValue;
			$this->id_nosotros->ViewCustomAttributes = "";

			// id_idioma
			$this->id_idioma->EditCustomAttributes = "";
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
					$this->id_idioma->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_idioma->EditValue = $this->id_idioma->CurrentValue;
				}
			} else {
				$this->id_idioma->EditValue = NULL;
			}
			$this->id_idioma->ViewCustomAttributes = "";

			// imagen1_url
			$this->imagen1_url->EditCustomAttributes = "";
			if (!ew_Empty($this->imagen1_url->Upload->DbValue)) {
				$this->imagen1_url->EditValue = $this->imagen1_url->Upload->DbValue;
			} else {
				$this->imagen1_url->EditValue = "";
			}
			if (!ew_Empty($this->imagen1_url->CurrentValue))
				$this->imagen1_url->Upload->FileName = $this->imagen1_url->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->imagen1_url);

			// imagen2_url
			$this->imagen2_url->EditCustomAttributes = "";
			if (!ew_Empty($this->imagen2_url->Upload->DbValue)) {
				$this->imagen2_url->EditValue = $this->imagen2_url->Upload->DbValue;
			} else {
				$this->imagen2_url->EditValue = "";
			}
			if (!ew_Empty($this->imagen2_url->CurrentValue))
				$this->imagen2_url->Upload->FileName = $this->imagen2_url->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->imagen2_url);

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

			// titulo3
			$this->titulo3->EditCustomAttributes = "";
			$this->titulo3->EditValue = ew_HtmlEncode($this->titulo3->CurrentValue);
			$this->titulo3->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->titulo3->FldCaption()));

			// descripcion3
			$this->descripcion3->EditCustomAttributes = "";
			$this->descripcion3->EditValue = $this->descripcion3->CurrentValue;
			$this->descripcion3->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->descripcion3->FldCaption()));

			// Edit refer script
			// id_nosotros

			$this->id_nosotros->HrefValue = "";

			// id_idioma
			$this->id_idioma->HrefValue = "";

			// imagen1_url
			$this->imagen1_url->HrefValue = "";
			$this->imagen1_url->HrefValue2 = $this->imagen1_url->UploadPath . $this->imagen1_url->Upload->DbValue;

			// imagen2_url
			$this->imagen2_url->HrefValue = "";
			$this->imagen2_url->HrefValue2 = $this->imagen2_url->UploadPath . $this->imagen2_url->Upload->DbValue;

			// titulo1
			$this->titulo1->HrefValue = "";

			// descripcion1
			$this->descripcion1->HrefValue = "";

			// titulo2
			$this->titulo2->HrefValue = "";

			// descripcion2
			$this->descripcion2->HrefValue = "";

			// titulo3
			$this->titulo3->HrefValue = "";

			// descripcion3
			$this->descripcion3->HrefValue = "";
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
		if (is_null($this->imagen1_url->Upload->Value)) {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->imagen1_url->FldCaption());
		}
		if (is_null($this->imagen2_url->Upload->Value)) {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->imagen2_url->FldCaption());
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
		if (!$this->titulo3->FldIsDetailKey && !is_null($this->titulo3->FormValue) && $this->titulo3->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->titulo3->FldCaption());
		}
		if (!$this->descripcion3->FldIsDetailKey && !is_null($this->descripcion3->FormValue) && $this->descripcion3->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->descripcion3->FldCaption());
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
			$rsnew = array();

			// imagen1_url
			if (!($this->imagen1_url->ReadOnly) && !$this->imagen1_url->Upload->KeepFile) {
				$this->imagen1_url->Upload->DbValue = $rs->fields('imagen1_url'); // Get original value
				if ($this->imagen1_url->Upload->FileName == "") {
					$rsnew['imagen1_url'] = NULL;
				} else {
					$rsnew['imagen1_url'] = $this->imagen1_url->Upload->FileName;
				}
			}

			// imagen2_url
			if (!($this->imagen2_url->ReadOnly) && !$this->imagen2_url->Upload->KeepFile) {
				$this->imagen2_url->Upload->DbValue = $rs->fields('imagen2_url'); // Get original value
				if ($this->imagen2_url->Upload->FileName == "") {
					$rsnew['imagen2_url'] = NULL;
				} else {
					$rsnew['imagen2_url'] = $this->imagen2_url->Upload->FileName;
				}
			}

			// titulo1
			$this->titulo1->SetDbValueDef($rsnew, $this->titulo1->CurrentValue, "", $this->titulo1->ReadOnly);

			// descripcion1
			$this->descripcion1->SetDbValueDef($rsnew, $this->descripcion1->CurrentValue, "", $this->descripcion1->ReadOnly);

			// titulo2
			$this->titulo2->SetDbValueDef($rsnew, $this->titulo2->CurrentValue, "", $this->titulo2->ReadOnly);

			// descripcion2
			$this->descripcion2->SetDbValueDef($rsnew, $this->descripcion2->CurrentValue, "", $this->descripcion2->ReadOnly);

			// titulo3
			$this->titulo3->SetDbValueDef($rsnew, $this->titulo3->CurrentValue, "", $this->titulo3->ReadOnly);

			// descripcion3
			$this->descripcion3->SetDbValueDef($rsnew, $this->descripcion3->CurrentValue, "", $this->descripcion3->ReadOnly);
			if (!$this->imagen1_url->Upload->KeepFile) {
				if (!ew_Empty($this->imagen1_url->Upload->Value)) {
					$rsnew['imagen1_url'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->imagen1_url->UploadPath), $rsnew['imagen1_url']); // Get new file name
				}
			}
			if (!$this->imagen2_url->Upload->KeepFile) {
				if (!ew_Empty($this->imagen2_url->Upload->Value)) {
					$rsnew['imagen2_url'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->imagen2_url->UploadPath), $rsnew['imagen2_url']); // Get new file name
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
					if (!$this->imagen1_url->Upload->KeepFile) {
						if (!ew_Empty($this->imagen1_url->Upload->Value)) {
							$this->imagen1_url->Upload->SaveToFile($this->imagen1_url->UploadPath, $rsnew['imagen1_url'], TRUE);
						}
					}
					if (!$this->imagen2_url->Upload->KeepFile) {
						if (!ew_Empty($this->imagen2_url->Upload->Value)) {
							$this->imagen2_url->Upload->SaveToFile($this->imagen2_url->UploadPath, $rsnew['imagen2_url'], TRUE);
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

		// imagen1_url
		ew_CleanUploadTempPath($this->imagen1_url, $this->imagen1_url->Upload->Index);

		// imagen2_url
		ew_CleanUploadTempPath($this->imagen2_url, $this->imagen2_url->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "nosotroslist.php", $this->TableVar, TRUE);
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
if (!isset($nosotros_edit)) $nosotros_edit = new cnosotros_edit();

// Page init
$nosotros_edit->Page_Init();

// Page main
$nosotros_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$nosotros_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var nosotros_edit = new ew_Page("nosotros_edit");
nosotros_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = nosotros_edit.PageID; // For backward compatibility

// Form object
var fnosotrosedit = new ew_Form("fnosotrosedit");

// Validate form
fnosotrosedit.Validate = function() {
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
			felm = this.GetElements("x" + infix + "_imagen1_url");
			elm = this.GetElements("fn_x" + infix + "_imagen1_url");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($nosotros->imagen1_url->FldCaption()) ?>");
			felm = this.GetElements("x" + infix + "_imagen2_url");
			elm = this.GetElements("fn_x" + infix + "_imagen2_url");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($nosotros->imagen2_url->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_titulo1");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($nosotros->titulo1->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_descripcion1");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($nosotros->descripcion1->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_titulo2");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($nosotros->titulo2->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_descripcion2");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($nosotros->descripcion2->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_titulo3");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($nosotros->titulo3->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_descripcion3");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($nosotros->descripcion3->FldCaption()) ?>");

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
fnosotrosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnosotrosedit.ValidateRequired = true;
<?php } else { ?>
fnosotrosedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fnosotrosedit.Lists["x_id_idioma"] = {"LinkField":"x_id_idioma","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $nosotros_edit->ShowPageHeader(); ?>
<?php
$nosotros_edit->ShowMessage();
?>
<form name="fnosotrosedit" id="fnosotrosedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="nosotros">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewGrid"><tr><td>
<table id="tbl_nosotrosedit" class="table table-bordered table-striped">
<?php if ($nosotros->id_nosotros->Visible) { // id_nosotros ?>
	<tr id="r_id_nosotros">
		<td><span id="elh_nosotros_id_nosotros"><?php echo $nosotros->id_nosotros->FldCaption() ?></span></td>
		<td<?php echo $nosotros->id_nosotros->CellAttributes() ?>>
<span id="el_nosotros_id_nosotros" class="control-group">
<span<?php echo $nosotros->id_nosotros->ViewAttributes() ?>>
<?php echo $nosotros->id_nosotros->EditValue ?></span>
</span>
<input type="hidden" data-field="x_id_nosotros" name="x_id_nosotros" id="x_id_nosotros" value="<?php echo ew_HtmlEncode($nosotros->id_nosotros->CurrentValue) ?>">
<?php echo $nosotros->id_nosotros->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($nosotros->id_idioma->Visible) { // id_idioma ?>
	<tr id="r_id_idioma">
		<td><span id="elh_nosotros_id_idioma"><?php echo $nosotros->id_idioma->FldCaption() ?></span></td>
		<td<?php echo $nosotros->id_idioma->CellAttributes() ?>>
<span id="el_nosotros_id_idioma" class="control-group">
<span<?php echo $nosotros->id_idioma->ViewAttributes() ?>>
<?php echo $nosotros->id_idioma->EditValue ?></span>
</span>
<input type="hidden" data-field="x_id_idioma" name="x_id_idioma" id="x_id_idioma" value="<?php echo ew_HtmlEncode($nosotros->id_idioma->CurrentValue) ?>">
<?php echo $nosotros->id_idioma->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($nosotros->imagen1_url->Visible) { // imagen1_url ?>
	<tr id="r_imagen1_url">
		<td><span id="elh_nosotros_imagen1_url"><?php echo $nosotros->imagen1_url->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $nosotros->imagen1_url->CellAttributes() ?>>
<div id="el_nosotros_imagen1_url" class="control-group">
<span id="fd_x_imagen1_url">
<span class="btn btn-small fileinput-button"<?php if ($nosotros->imagen1_url->ReadOnly || $nosotros->imagen1_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_imagen1_url" name="x_imagen1_url" id="x_imagen1_url">
</span>
<input type="hidden" name="fn_x_imagen1_url" id= "fn_x_imagen1_url" value="<?php echo $nosotros->imagen1_url->Upload->FileName ?>">
<?php if (@$_POST["fa_x_imagen1_url"] == "0") { ?>
<input type="hidden" name="fa_x_imagen1_url" id= "fa_x_imagen1_url" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_imagen1_url" id= "fa_x_imagen1_url" value="1">
<?php } ?>
<input type="hidden" name="fs_x_imagen1_url" id= "fs_x_imagen1_url" value="256">
</span>
<table id="ft_x_imagen1_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $nosotros->imagen1_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($nosotros->imagen2_url->Visible) { // imagen2_url ?>
	<tr id="r_imagen2_url">
		<td><span id="elh_nosotros_imagen2_url"><?php echo $nosotros->imagen2_url->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $nosotros->imagen2_url->CellAttributes() ?>>
<div id="el_nosotros_imagen2_url" class="control-group">
<span id="fd_x_imagen2_url">
<span class="btn btn-small fileinput-button"<?php if ($nosotros->imagen2_url->ReadOnly || $nosotros->imagen2_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_imagen2_url" name="x_imagen2_url" id="x_imagen2_url">
</span>
<input type="hidden" name="fn_x_imagen2_url" id= "fn_x_imagen2_url" value="<?php echo $nosotros->imagen2_url->Upload->FileName ?>">
<?php if (@$_POST["fa_x_imagen2_url"] == "0") { ?>
<input type="hidden" name="fa_x_imagen2_url" id= "fa_x_imagen2_url" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_imagen2_url" id= "fa_x_imagen2_url" value="1">
<?php } ?>
<input type="hidden" name="fs_x_imagen2_url" id= "fs_x_imagen2_url" value="256">
</span>
<table id="ft_x_imagen2_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $nosotros->imagen2_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($nosotros->titulo1->Visible) { // titulo1 ?>
	<tr id="r_titulo1">
		<td><span id="elh_nosotros_titulo1"><?php echo $nosotros->titulo1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $nosotros->titulo1->CellAttributes() ?>>
<span id="el_nosotros_titulo1" class="control-group">
<input type="text" data-field="x_titulo1" name="x_titulo1" id="x_titulo1" size="30" maxlength="40" placeholder="<?php echo $nosotros->titulo1->PlaceHolder ?>" value="<?php echo $nosotros->titulo1->EditValue ?>"<?php echo $nosotros->titulo1->EditAttributes() ?>>
</span>
<?php echo $nosotros->titulo1->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($nosotros->descripcion1->Visible) { // descripcion1 ?>
	<tr id="r_descripcion1">
		<td><span id="elh_nosotros_descripcion1"><?php echo $nosotros->descripcion1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $nosotros->descripcion1->CellAttributes() ?>>
<span id="el_nosotros_descripcion1" class="control-group">
<textarea data-field="x_descripcion1" class="editor" name="x_descripcion1" id="x_descripcion1" cols="35" rows="4" placeholder="<?php echo $nosotros->descripcion1->PlaceHolder ?>"<?php echo $nosotros->descripcion1->EditAttributes() ?>><?php echo $nosotros->descripcion1->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fnosotrosedit", "x_descripcion1", 35, 4, <?php echo ($nosotros->descripcion1->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $nosotros->descripcion1->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($nosotros->titulo2->Visible) { // titulo2 ?>
	<tr id="r_titulo2">
		<td><span id="elh_nosotros_titulo2"><?php echo $nosotros->titulo2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $nosotros->titulo2->CellAttributes() ?>>
<span id="el_nosotros_titulo2" class="control-group">
<input type="text" data-field="x_titulo2" name="x_titulo2" id="x_titulo2" size="30" maxlength="15" placeholder="<?php echo $nosotros->titulo2->PlaceHolder ?>" value="<?php echo $nosotros->titulo2->EditValue ?>"<?php echo $nosotros->titulo2->EditAttributes() ?>>
</span>
<?php echo $nosotros->titulo2->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($nosotros->descripcion2->Visible) { // descripcion2 ?>
	<tr id="r_descripcion2">
		<td><span id="elh_nosotros_descripcion2"><?php echo $nosotros->descripcion2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $nosotros->descripcion2->CellAttributes() ?>>
<span id="el_nosotros_descripcion2" class="control-group">
<textarea data-field="x_descripcion2" class="editor" name="x_descripcion2" id="x_descripcion2" cols="35" rows="4" placeholder="<?php echo $nosotros->descripcion2->PlaceHolder ?>"<?php echo $nosotros->descripcion2->EditAttributes() ?>><?php echo $nosotros->descripcion2->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fnosotrosedit", "x_descripcion2", 35, 4, <?php echo ($nosotros->descripcion2->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $nosotros->descripcion2->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($nosotros->titulo3->Visible) { // titulo3 ?>
	<tr id="r_titulo3">
		<td><span id="elh_nosotros_titulo3"><?php echo $nosotros->titulo3->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $nosotros->titulo3->CellAttributes() ?>>
<span id="el_nosotros_titulo3" class="control-group">
<input type="text" data-field="x_titulo3" name="x_titulo3" id="x_titulo3" size="30" maxlength="15" placeholder="<?php echo $nosotros->titulo3->PlaceHolder ?>" value="<?php echo $nosotros->titulo3->EditValue ?>"<?php echo $nosotros->titulo3->EditAttributes() ?>>
</span>
<?php echo $nosotros->titulo3->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($nosotros->descripcion3->Visible) { // descripcion3 ?>
	<tr id="r_descripcion3">
		<td><span id="elh_nosotros_descripcion3"><?php echo $nosotros->descripcion3->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $nosotros->descripcion3->CellAttributes() ?>>
<span id="el_nosotros_descripcion3" class="control-group">
<textarea data-field="x_descripcion3" class="editor" name="x_descripcion3" id="x_descripcion3" cols="35" rows="4" placeholder="<?php echo $nosotros->descripcion3->PlaceHolder ?>"<?php echo $nosotros->descripcion3->EditAttributes() ?>><?php echo $nosotros->descripcion3->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fnosotrosedit", "x_descripcion3", 35, 4, <?php echo ($nosotros->descripcion3->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $nosotros->descripcion3->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fnosotrosedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$nosotros_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$nosotros_edit->Page_Terminate();
?>
