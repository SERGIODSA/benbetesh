<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "form_empleoinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$form_empleo_delete = NULL; // Initialize page object first

class cform_empleo_delete extends cform_empleo {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'form_empleo';

	// Page object name
	var $PageObjName = 'form_empleo_delete';

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

		// Table object (form_empleo)
		if (!isset($GLOBALS["form_empleo"]) || get_class($GLOBALS["form_empleo"]) == "cform_empleo") {
			$GLOBALS["form_empleo"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["form_empleo"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'form_empleo', TRUE);

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
			$this->Page_Terminate("form_empleolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in form_empleo class, form_empleoinfo.php

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
		$this->id_empleo->setDbValue($rs->fields('id_empleo'));
		$this->id_idioma->setDbValue($rs->fields('id_idioma'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->telefono->setDbValue($rs->fields('telefono'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->archivo->setDbValue($rs->fields('archivo'));
		$this->mensaje->setDbValue($rs->fields('mensaje'));
		$this->fecha->setDbValue($rs->fields('fecha'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_empleo->DbValue = $row['id_empleo'];
		$this->id_idioma->DbValue = $row['id_idioma'];
		$this->nombre->DbValue = $row['nombre'];
		$this->telefono->DbValue = $row['telefono'];
		$this->_email->DbValue = $row['email'];
		$this->archivo->DbValue = $row['archivo'];
		$this->mensaje->DbValue = $row['mensaje'];
		$this->fecha->DbValue = $row['fecha'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_empleo

		$this->id_empleo->CellCssStyle = "white-space: nowrap;";

		// id_idioma
		$this->id_idioma->CellCssStyle = "white-space: nowrap;";

		// nombre
		// telefono
		// email
		// archivo
		// mensaje
		// fecha

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// telefono
			$this->telefono->ViewValue = $this->telefono->CurrentValue;
			$this->telefono->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewCustomAttributes = "";

			// archivo
			$this->archivo->ViewValue = $this->archivo->CurrentValue;
			$this->archivo->ViewCustomAttributes = "";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
			$this->fecha->ViewCustomAttributes = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";
			$this->telefono->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// archivo
			$this->archivo->LinkCustomAttributes = "";
			if (!ew_Empty($this->archivo->CurrentValue)) {
				$this->archivo->HrefValue = $this->archivo->CurrentValue; // Add prefix/suffix
				$this->archivo->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->archivo->HrefValue = ew_ConvertFullUrl($this->archivo->HrefValue);
			} else {
				$this->archivo->HrefValue = "";
			}
			$this->archivo->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";
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
				$sThisKey .= $row['id_empleo'];
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
		$Breadcrumb->Add("list", $this->TableVar, "form_empleolist.php", $this->TableVar, TRUE);
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
if (!isset($form_empleo_delete)) $form_empleo_delete = new cform_empleo_delete();

// Page init
$form_empleo_delete->Page_Init();

// Page main
$form_empleo_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$form_empleo_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var form_empleo_delete = new ew_Page("form_empleo_delete");
form_empleo_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = form_empleo_delete.PageID; // For backward compatibility

// Form object
var fform_empleodelete = new ew_Form("fform_empleodelete");

// Form_CustomValidate event
fform_empleodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fform_empleodelete.ValidateRequired = true;
<?php } else { ?>
fform_empleodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($form_empleo_delete->Recordset = $form_empleo_delete->LoadRecordset())
	$form_empleo_deleteTotalRecs = $form_empleo_delete->Recordset->RecordCount(); // Get record count
if ($form_empleo_deleteTotalRecs <= 0) { // No record found, exit
	if ($form_empleo_delete->Recordset)
		$form_empleo_delete->Recordset->Close();
	$form_empleo_delete->Page_Terminate("form_empleolist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $form_empleo_delete->ShowPageHeader(); ?>
<?php
$form_empleo_delete->ShowMessage();
?>
<form name="fform_empleodelete" id="fform_empleodelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="form_empleo">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($form_empleo_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_form_empleodelete" class="ewTable ewTableSeparate">
<?php echo $form_empleo->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($form_empleo->nombre->Visible) { // nombre ?>
		<td><span id="elh_form_empleo_nombre" class="form_empleo_nombre"><?php echo $form_empleo->nombre->FldCaption() ?></span></td>
<?php } ?>
<?php if ($form_empleo->telefono->Visible) { // telefono ?>
		<td><span id="elh_form_empleo_telefono" class="form_empleo_telefono"><?php echo $form_empleo->telefono->FldCaption() ?></span></td>
<?php } ?>
<?php if ($form_empleo->_email->Visible) { // email ?>
		<td><span id="elh_form_empleo__email" class="form_empleo__email"><?php echo $form_empleo->_email->FldCaption() ?></span></td>
<?php } ?>
<?php if ($form_empleo->archivo->Visible) { // archivo ?>
		<td><span id="elh_form_empleo_archivo" class="form_empleo_archivo"><?php echo $form_empleo->archivo->FldCaption() ?></span></td>
<?php } ?>
<?php if ($form_empleo->fecha->Visible) { // fecha ?>
		<td><span id="elh_form_empleo_fecha" class="form_empleo_fecha"><?php echo $form_empleo->fecha->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$form_empleo_delete->RecCnt = 0;
$i = 0;
while (!$form_empleo_delete->Recordset->EOF) {
	$form_empleo_delete->RecCnt++;
	$form_empleo_delete->RowCnt++;

	// Set row properties
	$form_empleo->ResetAttrs();
	$form_empleo->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$form_empleo_delete->LoadRowValues($form_empleo_delete->Recordset);

	// Render row
	$form_empleo_delete->RenderRow();
?>
	<tr<?php echo $form_empleo->RowAttributes() ?>>
<?php if ($form_empleo->nombre->Visible) { // nombre ?>
		<td<?php echo $form_empleo->nombre->CellAttributes() ?>>
<span id="el<?php echo $form_empleo_delete->RowCnt ?>_form_empleo_nombre" class="control-group form_empleo_nombre">
<span<?php echo $form_empleo->nombre->ViewAttributes() ?>>
<?php echo $form_empleo->nombre->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($form_empleo->telefono->Visible) { // telefono ?>
		<td<?php echo $form_empleo->telefono->CellAttributes() ?>>
<span id="el<?php echo $form_empleo_delete->RowCnt ?>_form_empleo_telefono" class="control-group form_empleo_telefono">
<span<?php echo $form_empleo->telefono->ViewAttributes() ?>>
<?php echo $form_empleo->telefono->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($form_empleo->_email->Visible) { // email ?>
		<td<?php echo $form_empleo->_email->CellAttributes() ?>>
<span id="el<?php echo $form_empleo_delete->RowCnt ?>_form_empleo__email" class="control-group form_empleo__email">
<span<?php echo $form_empleo->_email->ViewAttributes() ?>>
<?php echo $form_empleo->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($form_empleo->archivo->Visible) { // archivo ?>
		<td<?php echo $form_empleo->archivo->CellAttributes() ?>>
<span id="el<?php echo $form_empleo_delete->RowCnt ?>_form_empleo_archivo" class="control-group form_empleo_archivo">
<span<?php echo $form_empleo->archivo->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($form_empleo->archivo->ListViewValue()) && $form_empleo->archivo->LinkAttributes() <> "") { ?>
<a<?php echo $form_empleo->archivo->LinkAttributes() ?>><?php echo $form_empleo->archivo->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $form_empleo->archivo->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($form_empleo->fecha->Visible) { // fecha ?>
		<td<?php echo $form_empleo->fecha->CellAttributes() ?>>
<span id="el<?php echo $form_empleo_delete->RowCnt ?>_form_empleo_fecha" class="control-group form_empleo_fecha">
<span<?php echo $form_empleo->fecha->ViewAttributes() ?>>
<?php echo $form_empleo->fecha->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$form_empleo_delete->Recordset->MoveNext();
}
$form_empleo_delete->Recordset->Close();
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
fform_empleodelete.Init();
</script>
<?php
$form_empleo_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$form_empleo_delete->Page_Terminate();
?>
