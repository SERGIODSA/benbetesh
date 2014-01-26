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

$ss_index_edit = NULL; // Initialize page object first

class css_index_edit extends css_index {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'ss_index';

	// Page object name
	var $PageObjName = 'ss_index_edit';

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

		// Table object (ss_index)
		if (!isset($GLOBALS["ss_index"]) || get_class($GLOBALS["ss_index"]) == "css_index") {
			$GLOBALS["ss_index"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ss_index"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ss_index', TRUE);

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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id_ssindex"] <> "") {
			$this->id_ssindex->setQueryStringValue($_GET["id_ssindex"]);
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
		if ($this->id_ssindex->CurrentValue == "")
			$this->Page_Terminate("ss_indexlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("ss_indexlist.php"); // No matching record, return to list
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
		$this->slideshow1_url->Upload->Index = $objForm->Index;
		if ($this->slideshow1_url->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->slideshow1_url->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->slideshow1_url->CurrentValue = $this->slideshow1_url->Upload->FileName;
		$this->slideshow2_url->Upload->Index = $objForm->Index;
		if ($this->slideshow2_url->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->slideshow2_url->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->slideshow2_url->CurrentValue = $this->slideshow2_url->Upload->FileName;
		$this->slideshow3_url->Upload->Index = $objForm->Index;
		if ($this->slideshow3_url->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->slideshow3_url->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->slideshow3_url->CurrentValue = $this->slideshow3_url->Upload->FileName;
		$this->slideshow4_url->Upload->Index = $objForm->Index;
		if ($this->slideshow4_url->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->slideshow4_url->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->slideshow4_url->CurrentValue = $this->slideshow4_url->Upload->FileName;
		$this->slideshow5_url->Upload->Index = $objForm->Index;
		if ($this->slideshow5_url->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->slideshow5_url->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->slideshow5_url->CurrentValue = $this->slideshow5_url->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->id_ssindex->FldIsDetailKey)
			$this->id_ssindex->setFormValue($objForm->GetValue("x_id_ssindex"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_ssindex->CurrentValue = $this->id_ssindex->FormValue;
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

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// slideshow1_url
			$this->slideshow1_url->EditCustomAttributes = "";
			if (!ew_Empty($this->slideshow1_url->Upload->DbValue)) {
				$this->slideshow1_url->EditValue = $this->slideshow1_url->Upload->DbValue;
			} else {
				$this->slideshow1_url->EditValue = "";
			}
			if (!ew_Empty($this->slideshow1_url->CurrentValue))
				$this->slideshow1_url->Upload->FileName = $this->slideshow1_url->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->slideshow1_url);

			// slideshow2_url
			$this->slideshow2_url->EditCustomAttributes = "";
			if (!ew_Empty($this->slideshow2_url->Upload->DbValue)) {
				$this->slideshow2_url->EditValue = $this->slideshow2_url->Upload->DbValue;
			} else {
				$this->slideshow2_url->EditValue = "";
			}
			if (!ew_Empty($this->slideshow2_url->CurrentValue))
				$this->slideshow2_url->Upload->FileName = $this->slideshow2_url->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->slideshow2_url);

			// slideshow3_url
			$this->slideshow3_url->EditCustomAttributes = "";
			if (!ew_Empty($this->slideshow3_url->Upload->DbValue)) {
				$this->slideshow3_url->EditValue = $this->slideshow3_url->Upload->DbValue;
			} else {
				$this->slideshow3_url->EditValue = "";
			}
			if (!ew_Empty($this->slideshow3_url->CurrentValue))
				$this->slideshow3_url->Upload->FileName = $this->slideshow3_url->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->slideshow3_url);

			// slideshow4_url
			$this->slideshow4_url->EditCustomAttributes = "";
			if (!ew_Empty($this->slideshow4_url->Upload->DbValue)) {
				$this->slideshow4_url->EditValue = $this->slideshow4_url->Upload->DbValue;
			} else {
				$this->slideshow4_url->EditValue = "";
			}
			if (!ew_Empty($this->slideshow4_url->CurrentValue))
				$this->slideshow4_url->Upload->FileName = $this->slideshow4_url->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->slideshow4_url);

			// slideshow5_url
			$this->slideshow5_url->EditCustomAttributes = "";
			if (!ew_Empty($this->slideshow5_url->Upload->DbValue)) {
				$this->slideshow5_url->EditValue = $this->slideshow5_url->Upload->DbValue;
			} else {
				$this->slideshow5_url->EditValue = "";
			}
			if (!ew_Empty($this->slideshow5_url->CurrentValue))
				$this->slideshow5_url->Upload->FileName = $this->slideshow5_url->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->slideshow5_url);

			// Edit refer script
			// slideshow1_url

			$this->slideshow1_url->HrefValue = "";
			$this->slideshow1_url->HrefValue2 = $this->slideshow1_url->UploadPath . $this->slideshow1_url->Upload->DbValue;

			// slideshow2_url
			$this->slideshow2_url->HrefValue = "";
			$this->slideshow2_url->HrefValue2 = $this->slideshow2_url->UploadPath . $this->slideshow2_url->Upload->DbValue;

			// slideshow3_url
			$this->slideshow3_url->HrefValue = "";
			$this->slideshow3_url->HrefValue2 = $this->slideshow3_url->UploadPath . $this->slideshow3_url->Upload->DbValue;

			// slideshow4_url
			$this->slideshow4_url->HrefValue = "";
			$this->slideshow4_url->HrefValue2 = $this->slideshow4_url->UploadPath . $this->slideshow4_url->Upload->DbValue;

			// slideshow5_url
			$this->slideshow5_url->HrefValue = "";
			$this->slideshow5_url->HrefValue2 = $this->slideshow5_url->UploadPath . $this->slideshow5_url->Upload->DbValue;
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

			// slideshow1_url
			if (!($this->slideshow1_url->ReadOnly) && !$this->slideshow1_url->Upload->KeepFile) {
				$this->slideshow1_url->Upload->DbValue = $rs->fields('slideshow1_url'); // Get original value
				if ($this->slideshow1_url->Upload->FileName == "") {
					$rsnew['slideshow1_url'] = NULL;
				} else {
					$rsnew['slideshow1_url'] = $this->slideshow1_url->Upload->FileName;
				}
				$this->slideshow1_url->ImageWidth = 1000; // Resize width
				$this->slideshow1_url->ImageHeight = 611; // Resize height
			}

			// slideshow2_url
			if (!($this->slideshow2_url->ReadOnly) && !$this->slideshow2_url->Upload->KeepFile) {
				$this->slideshow2_url->Upload->DbValue = $rs->fields('slideshow2_url'); // Get original value
				if ($this->slideshow2_url->Upload->FileName == "") {
					$rsnew['slideshow2_url'] = NULL;
				} else {
					$rsnew['slideshow2_url'] = $this->slideshow2_url->Upload->FileName;
				}
				$this->slideshow2_url->ImageWidth = 1000; // Resize width
				$this->slideshow2_url->ImageHeight = 611; // Resize height
			}

			// slideshow3_url
			if (!($this->slideshow3_url->ReadOnly) && !$this->slideshow3_url->Upload->KeepFile) {
				$this->slideshow3_url->Upload->DbValue = $rs->fields('slideshow3_url'); // Get original value
				if ($this->slideshow3_url->Upload->FileName == "") {
					$rsnew['slideshow3_url'] = NULL;
				} else {
					$rsnew['slideshow3_url'] = $this->slideshow3_url->Upload->FileName;
				}
				$this->slideshow3_url->ImageWidth = 1000; // Resize width
				$this->slideshow3_url->ImageHeight = 611; // Resize height
			}

			// slideshow4_url
			if (!($this->slideshow4_url->ReadOnly) && !$this->slideshow4_url->Upload->KeepFile) {
				$this->slideshow4_url->Upload->DbValue = $rs->fields('slideshow4_url'); // Get original value
				if ($this->slideshow4_url->Upload->FileName == "") {
					$rsnew['slideshow4_url'] = NULL;
				} else {
					$rsnew['slideshow4_url'] = $this->slideshow4_url->Upload->FileName;
				}
				$this->slideshow4_url->ImageWidth = 1000; // Resize width
				$this->slideshow4_url->ImageHeight = 611; // Resize height
			}

			// slideshow5_url
			if (!($this->slideshow5_url->ReadOnly) && !$this->slideshow5_url->Upload->KeepFile) {
				$this->slideshow5_url->Upload->DbValue = $rs->fields('slideshow5_url'); // Get original value
				if ($this->slideshow5_url->Upload->FileName == "") {
					$rsnew['slideshow5_url'] = NULL;
				} else {
					$rsnew['slideshow5_url'] = $this->slideshow5_url->Upload->FileName;
				}
				$this->slideshow5_url->ImageWidth = 1000; // Resize width
				$this->slideshow5_url->ImageHeight = 611; // Resize height
			}
			if (!$this->slideshow1_url->Upload->KeepFile) {
				if (!ew_Empty($this->slideshow1_url->Upload->Value)) {
					$rsnew['slideshow1_url'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->slideshow1_url->UploadPath), $rsnew['slideshow1_url']); // Get new file name
				}
			}
			if (!$this->slideshow2_url->Upload->KeepFile) {
				if (!ew_Empty($this->slideshow2_url->Upload->Value)) {
					$rsnew['slideshow2_url'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->slideshow2_url->UploadPath), $rsnew['slideshow2_url']); // Get new file name
				}
			}
			if (!$this->slideshow3_url->Upload->KeepFile) {
				if (!ew_Empty($this->slideshow3_url->Upload->Value)) {
					$rsnew['slideshow3_url'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->slideshow3_url->UploadPath), $rsnew['slideshow3_url']); // Get new file name
				}
			}
			if (!$this->slideshow4_url->Upload->KeepFile) {
				if (!ew_Empty($this->slideshow4_url->Upload->Value)) {
					$rsnew['slideshow4_url'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->slideshow4_url->UploadPath), $rsnew['slideshow4_url']); // Get new file name
				}
			}
			if (!$this->slideshow5_url->Upload->KeepFile) {
				if (!ew_Empty($this->slideshow5_url->Upload->Value)) {
					$rsnew['slideshow5_url'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->slideshow5_url->UploadPath), $rsnew['slideshow5_url']); // Get new file name
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
					if (!$this->slideshow1_url->Upload->KeepFile) {
						if (!ew_Empty($this->slideshow1_url->Upload->Value)) {
							$this->slideshow1_url->Upload->Resize($this->slideshow1_url->ImageWidth, $this->slideshow1_url->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY);
							$this->slideshow1_url->Upload->SaveToFile($this->slideshow1_url->UploadPath, $rsnew['slideshow1_url'], TRUE);
						}
					}
					if (!$this->slideshow2_url->Upload->KeepFile) {
						if (!ew_Empty($this->slideshow2_url->Upload->Value)) {
							$this->slideshow2_url->Upload->Resize($this->slideshow2_url->ImageWidth, $this->slideshow2_url->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY);
							$this->slideshow2_url->Upload->SaveToFile($this->slideshow2_url->UploadPath, $rsnew['slideshow2_url'], TRUE);
						}
					}
					if (!$this->slideshow3_url->Upload->KeepFile) {
						if (!ew_Empty($this->slideshow3_url->Upload->Value)) {
							$this->slideshow3_url->Upload->Resize($this->slideshow3_url->ImageWidth, $this->slideshow3_url->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY);
							$this->slideshow3_url->Upload->SaveToFile($this->slideshow3_url->UploadPath, $rsnew['slideshow3_url'], TRUE);
						}
					}
					if (!$this->slideshow4_url->Upload->KeepFile) {
						if (!ew_Empty($this->slideshow4_url->Upload->Value)) {
							$this->slideshow4_url->Upload->Resize($this->slideshow4_url->ImageWidth, $this->slideshow4_url->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY);
							$this->slideshow4_url->Upload->SaveToFile($this->slideshow4_url->UploadPath, $rsnew['slideshow4_url'], TRUE);
						}
					}
					if (!$this->slideshow5_url->Upload->KeepFile) {
						if (!ew_Empty($this->slideshow5_url->Upload->Value)) {
							$this->slideshow5_url->Upload->Resize($this->slideshow5_url->ImageWidth, $this->slideshow5_url->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY);
							$this->slideshow5_url->Upload->SaveToFile($this->slideshow5_url->UploadPath, $rsnew['slideshow5_url'], TRUE);
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

		// slideshow1_url
		ew_CleanUploadTempPath($this->slideshow1_url, $this->slideshow1_url->Upload->Index);

		// slideshow2_url
		ew_CleanUploadTempPath($this->slideshow2_url, $this->slideshow2_url->Upload->Index);

		// slideshow3_url
		ew_CleanUploadTempPath($this->slideshow3_url, $this->slideshow3_url->Upload->Index);

		// slideshow4_url
		ew_CleanUploadTempPath($this->slideshow4_url, $this->slideshow4_url->Upload->Index);

		// slideshow5_url
		ew_CleanUploadTempPath($this->slideshow5_url, $this->slideshow5_url->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "ss_indexlist.php", $this->TableVar, TRUE);
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
if (!isset($ss_index_edit)) $ss_index_edit = new css_index_edit();

// Page init
$ss_index_edit->Page_Init();

// Page main
$ss_index_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ss_index_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var ss_index_edit = new ew_Page("ss_index_edit");
ss_index_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = ss_index_edit.PageID; // For backward compatibility

// Form object
var fss_indexedit = new ew_Form("fss_indexedit");

// Validate form
fss_indexedit.Validate = function() {
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
fss_indexedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fss_indexedit.ValidateRequired = true;
<?php } else { ?>
fss_indexedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $ss_index_edit->ShowPageHeader(); ?>
<?php
$ss_index_edit->ShowMessage();
?>
<form name="fss_indexedit" id="fss_indexedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="ss_index">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewGrid"><tr><td>
<table id="tbl_ss_indexedit" class="table table-bordered table-striped">
<?php if ($ss_index->slideshow1_url->Visible) { // slideshow1_url ?>
	<tr id="r_slideshow1_url">
		<td><span id="elh_ss_index_slideshow1_url"><?php echo $ss_index->slideshow1_url->FldCaption() ?></span></td>
		<td<?php echo $ss_index->slideshow1_url->CellAttributes() ?>>
<div id="el_ss_index_slideshow1_url" class="control-group">
<span id="fd_x_slideshow1_url">
<span class="btn btn-small fileinput-button"<?php if ($ss_index->slideshow1_url->ReadOnly || $ss_index->slideshow1_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_slideshow1_url" name="x_slideshow1_url" id="x_slideshow1_url">
</span>
<input type="hidden" name="fn_x_slideshow1_url" id= "fn_x_slideshow1_url" value="<?php echo $ss_index->slideshow1_url->Upload->FileName ?>">
<?php if (@$_POST["fa_x_slideshow1_url"] == "0") { ?>
<input type="hidden" name="fa_x_slideshow1_url" id= "fa_x_slideshow1_url" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_slideshow1_url" id= "fa_x_slideshow1_url" value="1">
<?php } ?>
<input type="hidden" name="fs_x_slideshow1_url" id= "fs_x_slideshow1_url" value="256">
</span>
<table id="ft_x_slideshow1_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $ss_index->slideshow1_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ss_index->slideshow2_url->Visible) { // slideshow2_url ?>
	<tr id="r_slideshow2_url">
		<td><span id="elh_ss_index_slideshow2_url"><?php echo $ss_index->slideshow2_url->FldCaption() ?></span></td>
		<td<?php echo $ss_index->slideshow2_url->CellAttributes() ?>>
<div id="el_ss_index_slideshow2_url" class="control-group">
<span id="fd_x_slideshow2_url">
<span class="btn btn-small fileinput-button"<?php if ($ss_index->slideshow2_url->ReadOnly || $ss_index->slideshow2_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_slideshow2_url" name="x_slideshow2_url" id="x_slideshow2_url">
</span>
<input type="hidden" name="fn_x_slideshow2_url" id= "fn_x_slideshow2_url" value="<?php echo $ss_index->slideshow2_url->Upload->FileName ?>">
<?php if (@$_POST["fa_x_slideshow2_url"] == "0") { ?>
<input type="hidden" name="fa_x_slideshow2_url" id= "fa_x_slideshow2_url" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_slideshow2_url" id= "fa_x_slideshow2_url" value="1">
<?php } ?>
<input type="hidden" name="fs_x_slideshow2_url" id= "fs_x_slideshow2_url" value="256">
</span>
<table id="ft_x_slideshow2_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $ss_index->slideshow2_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ss_index->slideshow3_url->Visible) { // slideshow3_url ?>
	<tr id="r_slideshow3_url">
		<td><span id="elh_ss_index_slideshow3_url"><?php echo $ss_index->slideshow3_url->FldCaption() ?></span></td>
		<td<?php echo $ss_index->slideshow3_url->CellAttributes() ?>>
<div id="el_ss_index_slideshow3_url" class="control-group">
<span id="fd_x_slideshow3_url">
<span class="btn btn-small fileinput-button"<?php if ($ss_index->slideshow3_url->ReadOnly || $ss_index->slideshow3_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_slideshow3_url" name="x_slideshow3_url" id="x_slideshow3_url">
</span>
<input type="hidden" name="fn_x_slideshow3_url" id= "fn_x_slideshow3_url" value="<?php echo $ss_index->slideshow3_url->Upload->FileName ?>">
<?php if (@$_POST["fa_x_slideshow3_url"] == "0") { ?>
<input type="hidden" name="fa_x_slideshow3_url" id= "fa_x_slideshow3_url" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_slideshow3_url" id= "fa_x_slideshow3_url" value="1">
<?php } ?>
<input type="hidden" name="fs_x_slideshow3_url" id= "fs_x_slideshow3_url" value="256">
</span>
<table id="ft_x_slideshow3_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $ss_index->slideshow3_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ss_index->slideshow4_url->Visible) { // slideshow4_url ?>
	<tr id="r_slideshow4_url">
		<td><span id="elh_ss_index_slideshow4_url"><?php echo $ss_index->slideshow4_url->FldCaption() ?></span></td>
		<td<?php echo $ss_index->slideshow4_url->CellAttributes() ?>>
<div id="el_ss_index_slideshow4_url" class="control-group">
<span id="fd_x_slideshow4_url">
<span class="btn btn-small fileinput-button"<?php if ($ss_index->slideshow4_url->ReadOnly || $ss_index->slideshow4_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_slideshow4_url" name="x_slideshow4_url" id="x_slideshow4_url">
</span>
<input type="hidden" name="fn_x_slideshow4_url" id= "fn_x_slideshow4_url" value="<?php echo $ss_index->slideshow4_url->Upload->FileName ?>">
<?php if (@$_POST["fa_x_slideshow4_url"] == "0") { ?>
<input type="hidden" name="fa_x_slideshow4_url" id= "fa_x_slideshow4_url" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_slideshow4_url" id= "fa_x_slideshow4_url" value="1">
<?php } ?>
<input type="hidden" name="fs_x_slideshow4_url" id= "fs_x_slideshow4_url" value="256">
</span>
<table id="ft_x_slideshow4_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $ss_index->slideshow4_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ss_index->slideshow5_url->Visible) { // slideshow5_url ?>
	<tr id="r_slideshow5_url">
		<td><span id="elh_ss_index_slideshow5_url"><?php echo $ss_index->slideshow5_url->FldCaption() ?></span></td>
		<td<?php echo $ss_index->slideshow5_url->CellAttributes() ?>>
<div id="el_ss_index_slideshow5_url" class="control-group">
<span id="fd_x_slideshow5_url">
<span class="btn btn-small fileinput-button"<?php if ($ss_index->slideshow5_url->ReadOnly || $ss_index->slideshow5_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_slideshow5_url" name="x_slideshow5_url" id="x_slideshow5_url">
</span>
<input type="hidden" name="fn_x_slideshow5_url" id= "fn_x_slideshow5_url" value="<?php echo $ss_index->slideshow5_url->Upload->FileName ?>">
<?php if (@$_POST["fa_x_slideshow5_url"] == "0") { ?>
<input type="hidden" name="fa_x_slideshow5_url" id= "fa_x_slideshow5_url" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_slideshow5_url" id= "fa_x_slideshow5_url" value="1">
<?php } ?>
<input type="hidden" name="fs_x_slideshow5_url" id= "fs_x_slideshow5_url" value="256">
</span>
<table id="ft_x_slideshow5_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $ss_index->slideshow5_url->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_id_ssindex" name="x_id_ssindex" id="x_id_ssindex" value="<?php echo ew_HtmlEncode($ss_index->id_ssindex->CurrentValue) ?>">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fss_indexedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$ss_index_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ss_index_edit->Page_Terminate();
?>
