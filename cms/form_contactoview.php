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

$form_contacto_view = NULL; // Initialize page object first

class cform_contacto_view extends cform_contacto {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'form_contacto';

	// Page object name
	var $PageObjName = 'form_contacto_view';

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
		$KeyUrl = "";
		if (@$_GET["id_contacto"] <> "") {
			$this->RecKey["id_contacto"] = $_GET["id_contacto"];
			$KeyUrl .= "&amp;id_contacto=" . urlencode($this->RecKey["id_contacto"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'form_contacto', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		$this->id_contacto->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id_contacto"] <> "") {
				$this->id_contacto->setQueryStringValue($_GET["id_contacto"]);
				$this->RecKey["id_contacto"] = $this->id_contacto->QueryStringValue;
			} else {
				$sReturnUrl = "form_contactolist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "form_contactolist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "form_contactolist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a onclick=\"return ew_Confirm(ewLanguage.Phrase('DeleteConfirmMsg'));\" class=\"ewAction ewDelete\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->IsLoggedIn());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
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

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id_contacto
		// id_idioma
		// nombre
		// empresa
		// email
		// telefono
		// pais
		// mensaje
		// fecha_creacion

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_contacto
			$this->id_contacto->ViewValue = $this->id_contacto->CurrentValue;
			$this->id_contacto->ViewCustomAttributes = "";

			// id_idioma
			$this->id_idioma->ViewValue = $this->id_idioma->CurrentValue;
			$this->id_idioma->ViewCustomAttributes = "";

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

			// id_contacto
			$this->id_contacto->LinkCustomAttributes = "";
			$this->id_contacto->HrefValue = "";
			$this->id_contacto->TooltipValue = "";

			// id_idioma
			$this->id_idioma->LinkCustomAttributes = "";
			$this->id_idioma->HrefValue = "";
			$this->id_idioma->TooltipValue = "";

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
		$Breadcrumb->Add("list", $this->TableVar, "form_contactolist.php", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, ew_CurrentUrl());
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($form_contacto_view)) $form_contacto_view = new cform_contacto_view();

// Page init
$form_contacto_view->Page_Init();

// Page main
$form_contacto_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$form_contacto_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var form_contacto_view = new ew_Page("form_contacto_view");
form_contacto_view.PageID = "view"; // Page ID
var EW_PAGE_ID = form_contacto_view.PageID; // For backward compatibility

// Form object
var fform_contactoview = new ew_Form("fform_contactoview");

// Form_CustomValidate event
fform_contactoview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fform_contactoview.ValidateRequired = true;
<?php } else { ?>
fform_contactoview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<div class="ewViewExportOptions">
<?php $form_contacto_view->ExportOptions->Render("body") ?>
<?php if (!$form_contacto_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($form_contacto_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php $form_contacto_view->ShowPageHeader(); ?>
<?php
$form_contacto_view->ShowMessage();
?>
<form name="fform_contactoview" id="fform_contactoview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="form_contacto">
<table class="ewGrid"><tr><td>
<table id="tbl_form_contactoview" class="table table-bordered table-striped">
<?php if ($form_contacto->id_contacto->Visible) { // id_contacto ?>
	<tr id="r_id_contacto">
		<td><span id="elh_form_contacto_id_contacto"><?php echo $form_contacto->id_contacto->FldCaption() ?></span></td>
		<td<?php echo $form_contacto->id_contacto->CellAttributes() ?>>
<span id="el_form_contacto_id_contacto" class="control-group">
<span<?php echo $form_contacto->id_contacto->ViewAttributes() ?>>
<?php echo $form_contacto->id_contacto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($form_contacto->id_idioma->Visible) { // id_idioma ?>
	<tr id="r_id_idioma">
		<td><span id="elh_form_contacto_id_idioma"><?php echo $form_contacto->id_idioma->FldCaption() ?></span></td>
		<td<?php echo $form_contacto->id_idioma->CellAttributes() ?>>
<span id="el_form_contacto_id_idioma" class="control-group">
<span<?php echo $form_contacto->id_idioma->ViewAttributes() ?>>
<?php echo $form_contacto->id_idioma->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($form_contacto->nombre->Visible) { // nombre ?>
	<tr id="r_nombre">
		<td><span id="elh_form_contacto_nombre"><?php echo $form_contacto->nombre->FldCaption() ?></span></td>
		<td<?php echo $form_contacto->nombre->CellAttributes() ?>>
<span id="el_form_contacto_nombre" class="control-group">
<span<?php echo $form_contacto->nombre->ViewAttributes() ?>>
<?php echo $form_contacto->nombre->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($form_contacto->empresa->Visible) { // empresa ?>
	<tr id="r_empresa">
		<td><span id="elh_form_contacto_empresa"><?php echo $form_contacto->empresa->FldCaption() ?></span></td>
		<td<?php echo $form_contacto->empresa->CellAttributes() ?>>
<span id="el_form_contacto_empresa" class="control-group">
<span<?php echo $form_contacto->empresa->ViewAttributes() ?>>
<?php echo $form_contacto->empresa->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($form_contacto->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_form_contacto__email"><?php echo $form_contacto->_email->FldCaption() ?></span></td>
		<td<?php echo $form_contacto->_email->CellAttributes() ?>>
<span id="el_form_contacto__email" class="control-group">
<span<?php echo $form_contacto->_email->ViewAttributes() ?>>
<?php echo $form_contacto->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($form_contacto->telefono->Visible) { // telefono ?>
	<tr id="r_telefono">
		<td><span id="elh_form_contacto_telefono"><?php echo $form_contacto->telefono->FldCaption() ?></span></td>
		<td<?php echo $form_contacto->telefono->CellAttributes() ?>>
<span id="el_form_contacto_telefono" class="control-group">
<span<?php echo $form_contacto->telefono->ViewAttributes() ?>>
<?php echo $form_contacto->telefono->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($form_contacto->pais->Visible) { // pais ?>
	<tr id="r_pais">
		<td><span id="elh_form_contacto_pais"><?php echo $form_contacto->pais->FldCaption() ?></span></td>
		<td<?php echo $form_contacto->pais->CellAttributes() ?>>
<span id="el_form_contacto_pais" class="control-group">
<span<?php echo $form_contacto->pais->ViewAttributes() ?>>
<?php echo $form_contacto->pais->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($form_contacto->mensaje->Visible) { // mensaje ?>
	<tr id="r_mensaje">
		<td><span id="elh_form_contacto_mensaje"><?php echo $form_contacto->mensaje->FldCaption() ?></span></td>
		<td<?php echo $form_contacto->mensaje->CellAttributes() ?>>
<span id="el_form_contacto_mensaje" class="control-group">
<span<?php echo $form_contacto->mensaje->ViewAttributes() ?>>
<?php echo $form_contacto->mensaje->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($form_contacto->fecha_creacion->Visible) { // fecha_creacion ?>
	<tr id="r_fecha_creacion">
		<td><span id="elh_form_contacto_fecha_creacion"><?php echo $form_contacto->fecha_creacion->FldCaption() ?></span></td>
		<td<?php echo $form_contacto->fecha_creacion->CellAttributes() ?>>
<span id="el_form_contacto_fecha_creacion" class="control-group">
<span<?php echo $form_contacto->fecha_creacion->ViewAttributes() ?>>
<?php echo $form_contacto->fecha_creacion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
fform_contactoview.Init();
</script>
<?php
$form_contacto_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$form_contacto_view->Page_Terminate();
?>
