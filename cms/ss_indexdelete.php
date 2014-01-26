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

$ss_index_delete = NULL; // Initialize page object first

class css_index_delete extends css_index {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'ss_index';

	// Page object name
	var $PageObjName = 'ss_index_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("ss_indexlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in ss_index class, ss_indexinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "D"; // Delete record directly
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
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

			// id_idioma
			$this->id_idioma->ViewValue = $this->id_idioma->CurrentValue;
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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id_ssindex'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "ss_indexlist.php", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, ew_CurrentUrl());
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
if (!isset($ss_index_delete)) $ss_index_delete = new css_index_delete();

// Page init
$ss_index_delete->Page_Init();

// Page main
$ss_index_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ss_index_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var ss_index_delete = new ew_Page("ss_index_delete");
ss_index_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = ss_index_delete.PageID; // For backward compatibility

// Form object
var fss_indexdelete = new ew_Form("fss_indexdelete");

// Form_CustomValidate event
fss_indexdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fss_indexdelete.ValidateRequired = true;
<?php } else { ?>
fss_indexdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($ss_index_delete->Recordset = $ss_index_delete->LoadRecordset())
	$ss_index_deleteTotalRecs = $ss_index_delete->Recordset->RecordCount(); // Get record count
if ($ss_index_deleteTotalRecs <= 0) { // No record found, exit
	if ($ss_index_delete->Recordset)
		$ss_index_delete->Recordset->Close();
	$ss_index_delete->Page_Terminate("ss_indexlist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $ss_index_delete->ShowPageHeader(); ?>
<?php
$ss_index_delete->ShowMessage();
?>
<form name="fss_indexdelete" id="fss_indexdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="ss_index">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($ss_index_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_ss_indexdelete" class="ewTable ewTableSeparate">
<?php echo $ss_index->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($ss_index->id_idioma->Visible) { // id_idioma ?>
		<td><span id="elh_ss_index_id_idioma" class="ss_index_id_idioma"><?php echo $ss_index->id_idioma->FldCaption() ?></span></td>
<?php } ?>
<?php if ($ss_index->slideshow1_url->Visible) { // slideshow1_url ?>
		<td><span id="elh_ss_index_slideshow1_url" class="ss_index_slideshow1_url"><?php echo $ss_index->slideshow1_url->FldCaption() ?></span></td>
<?php } ?>
<?php if ($ss_index->slideshow2_url->Visible) { // slideshow2_url ?>
		<td><span id="elh_ss_index_slideshow2_url" class="ss_index_slideshow2_url"><?php echo $ss_index->slideshow2_url->FldCaption() ?></span></td>
<?php } ?>
<?php if ($ss_index->slideshow3_url->Visible) { // slideshow3_url ?>
		<td><span id="elh_ss_index_slideshow3_url" class="ss_index_slideshow3_url"><?php echo $ss_index->slideshow3_url->FldCaption() ?></span></td>
<?php } ?>
<?php if ($ss_index->slideshow4_url->Visible) { // slideshow4_url ?>
		<td><span id="elh_ss_index_slideshow4_url" class="ss_index_slideshow4_url"><?php echo $ss_index->slideshow4_url->FldCaption() ?></span></td>
<?php } ?>
<?php if ($ss_index->slideshow5_url->Visible) { // slideshow5_url ?>
		<td><span id="elh_ss_index_slideshow5_url" class="ss_index_slideshow5_url"><?php echo $ss_index->slideshow5_url->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$ss_index_delete->RecCnt = 0;
$i = 0;
while (!$ss_index_delete->Recordset->EOF) {
	$ss_index_delete->RecCnt++;
	$ss_index_delete->RowCnt++;

	// Set row properties
	$ss_index->ResetAttrs();
	$ss_index->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$ss_index_delete->LoadRowValues($ss_index_delete->Recordset);

	// Render row
	$ss_index_delete->RenderRow();
?>
	<tr<?php echo $ss_index->RowAttributes() ?>>
<?php if ($ss_index->id_idioma->Visible) { // id_idioma ?>
		<td<?php echo $ss_index->id_idioma->CellAttributes() ?>>
<span id="el<?php echo $ss_index_delete->RowCnt ?>_ss_index_id_idioma" class="control-group ss_index_id_idioma">
<span<?php echo $ss_index->id_idioma->ViewAttributes() ?>>
<?php echo $ss_index->id_idioma->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($ss_index->slideshow1_url->Visible) { // slideshow1_url ?>
		<td<?php echo $ss_index->slideshow1_url->CellAttributes() ?>>
<span id="el<?php echo $ss_index_delete->RowCnt ?>_ss_index_slideshow1_url" class="control-group ss_index_slideshow1_url">
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
</span>
</td>
<?php } ?>
<?php if ($ss_index->slideshow2_url->Visible) { // slideshow2_url ?>
		<td<?php echo $ss_index->slideshow2_url->CellAttributes() ?>>
<span id="el<?php echo $ss_index_delete->RowCnt ?>_ss_index_slideshow2_url" class="control-group ss_index_slideshow2_url">
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
</span>
</td>
<?php } ?>
<?php if ($ss_index->slideshow3_url->Visible) { // slideshow3_url ?>
		<td<?php echo $ss_index->slideshow3_url->CellAttributes() ?>>
<span id="el<?php echo $ss_index_delete->RowCnt ?>_ss_index_slideshow3_url" class="control-group ss_index_slideshow3_url">
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
</span>
</td>
<?php } ?>
<?php if ($ss_index->slideshow4_url->Visible) { // slideshow4_url ?>
		<td<?php echo $ss_index->slideshow4_url->CellAttributes() ?>>
<span id="el<?php echo $ss_index_delete->RowCnt ?>_ss_index_slideshow4_url" class="control-group ss_index_slideshow4_url">
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
</span>
</td>
<?php } ?>
<?php if ($ss_index->slideshow5_url->Visible) { // slideshow5_url ?>
		<td<?php echo $ss_index->slideshow5_url->CellAttributes() ?>>
<span id="el<?php echo $ss_index_delete->RowCnt ?>_ss_index_slideshow5_url" class="control-group ss_index_slideshow5_url">
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
</span>
</td>
<?php } ?>
	</tr>
<?php
	$ss_index_delete->Recordset->MoveNext();
}
$ss_index_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fss_indexdelete.Init();
</script>
<?php
$ss_index_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ss_index_delete->Page_Terminate();
?>
