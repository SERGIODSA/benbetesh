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

$benbetesh_delete = NULL; // Initialize page object first

class cbenbetesh_delete extends cbenbetesh {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'benbetesh';

	// Page object name
	var $PageObjName = 'benbetesh_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
			$this->Page_Terminate("benbeteshlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in benbetesh class, benbeteshinfo.php

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
				$sThisKey .= $row['id_tienda'];
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
		$Breadcrumb->Add("list", $this->TableVar, "benbeteshlist.php", $this->TableVar, TRUE);
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
if (!isset($benbetesh_delete)) $benbetesh_delete = new cbenbetesh_delete();

// Page init
$benbetesh_delete->Page_Init();

// Page main
$benbetesh_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$benbetesh_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var benbetesh_delete = new ew_Page("benbetesh_delete");
benbetesh_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = benbetesh_delete.PageID; // For backward compatibility

// Form object
var fbenbeteshdelete = new ew_Form("fbenbeteshdelete");

// Form_CustomValidate event
fbenbeteshdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbenbeteshdelete.ValidateRequired = true;
<?php } else { ?>
fbenbeteshdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fbenbeteshdelete.Lists["x_id_idioma"] = {"LinkField":"x_id_idioma","Ajax":null,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($benbetesh_delete->Recordset = $benbetesh_delete->LoadRecordset())
	$benbetesh_deleteTotalRecs = $benbetesh_delete->Recordset->RecordCount(); // Get record count
if ($benbetesh_deleteTotalRecs <= 0) { // No record found, exit
	if ($benbetesh_delete->Recordset)
		$benbetesh_delete->Recordset->Close();
	$benbetesh_delete->Page_Terminate("benbeteshlist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $benbetesh_delete->ShowPageHeader(); ?>
<?php
$benbetesh_delete->ShowMessage();
?>
<form name="fbenbeteshdelete" id="fbenbeteshdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="benbetesh">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($benbetesh_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_benbeteshdelete" class="ewTable ewTableSeparate">
<?php echo $benbetesh->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($benbetesh->id_idioma->Visible) { // id_idioma ?>
		<td><span id="elh_benbetesh_id_idioma" class="benbetesh_id_idioma"><?php echo $benbetesh->id_idioma->FldCaption() ?></span></td>
<?php } ?>
<?php if ($benbetesh->imagen_url->Visible) { // imagen_url ?>
		<td><span id="elh_benbetesh_imagen_url" class="benbetesh_imagen_url"><?php echo $benbetesh->imagen_url->FldCaption() ?></span></td>
<?php } ?>
<?php if ($benbetesh->titulo->Visible) { // titulo ?>
		<td><span id="elh_benbetesh_titulo" class="benbetesh_titulo"><?php echo $benbetesh->titulo->FldCaption() ?></span></td>
<?php } ?>
<?php if ($benbetesh->horario->Visible) { // horario ?>
		<td><span id="elh_benbetesh_horario" class="benbetesh_horario"><?php echo $benbetesh->horario->FldCaption() ?></span></td>
<?php } ?>
<?php if ($benbetesh->telefono->Visible) { // telefono ?>
		<td><span id="elh_benbetesh_telefono" class="benbetesh_telefono"><?php echo $benbetesh->telefono->FldCaption() ?></span></td>
<?php } ?>
<?php if ($benbetesh->dias->Visible) { // dias ?>
		<td><span id="elh_benbetesh_dias" class="benbetesh_dias"><?php echo $benbetesh->dias->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$benbetesh_delete->RecCnt = 0;
$i = 0;
while (!$benbetesh_delete->Recordset->EOF) {
	$benbetesh_delete->RecCnt++;
	$benbetesh_delete->RowCnt++;

	// Set row properties
	$benbetesh->ResetAttrs();
	$benbetesh->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$benbetesh_delete->LoadRowValues($benbetesh_delete->Recordset);

	// Render row
	$benbetesh_delete->RenderRow();
?>
	<tr<?php echo $benbetesh->RowAttributes() ?>>
<?php if ($benbetesh->id_idioma->Visible) { // id_idioma ?>
		<td<?php echo $benbetesh->id_idioma->CellAttributes() ?>>
<span id="el<?php echo $benbetesh_delete->RowCnt ?>_benbetesh_id_idioma" class="control-group benbetesh_id_idioma">
<span<?php echo $benbetesh->id_idioma->ViewAttributes() ?>>
<?php echo $benbetesh->id_idioma->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($benbetesh->imagen_url->Visible) { // imagen_url ?>
		<td<?php echo $benbetesh->imagen_url->CellAttributes() ?>>
<span id="el<?php echo $benbetesh_delete->RowCnt ?>_benbetesh_imagen_url" class="control-group benbetesh_imagen_url">
<span<?php echo $benbetesh->imagen_url->ViewAttributes() ?>>
<?php if ($benbetesh->imagen_url->LinkAttributes() <> "") { ?>
<?php if (!empty($benbetesh->imagen_url->Upload->DbValue)) { ?>
<?php echo $benbetesh->imagen_url->ListViewValue() ?>
<?php } elseif (!in_array($benbetesh->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($benbetesh->imagen_url->Upload->DbValue)) { ?>
<?php echo $benbetesh->imagen_url->ListViewValue() ?>
<?php } elseif (!in_array($benbetesh->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($benbetesh->titulo->Visible) { // titulo ?>
		<td<?php echo $benbetesh->titulo->CellAttributes() ?>>
<span id="el<?php echo $benbetesh_delete->RowCnt ?>_benbetesh_titulo" class="control-group benbetesh_titulo">
<span<?php echo $benbetesh->titulo->ViewAttributes() ?>>
<?php echo $benbetesh->titulo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($benbetesh->horario->Visible) { // horario ?>
		<td<?php echo $benbetesh->horario->CellAttributes() ?>>
<span id="el<?php echo $benbetesh_delete->RowCnt ?>_benbetesh_horario" class="control-group benbetesh_horario">
<span<?php echo $benbetesh->horario->ViewAttributes() ?>>
<?php echo $benbetesh->horario->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($benbetesh->telefono->Visible) { // telefono ?>
		<td<?php echo $benbetesh->telefono->CellAttributes() ?>>
<span id="el<?php echo $benbetesh_delete->RowCnt ?>_benbetesh_telefono" class="control-group benbetesh_telefono">
<span<?php echo $benbetesh->telefono->ViewAttributes() ?>>
<?php echo $benbetesh->telefono->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($benbetesh->dias->Visible) { // dias ?>
		<td<?php echo $benbetesh->dias->CellAttributes() ?>>
<span id="el<?php echo $benbetesh_delete->RowCnt ?>_benbetesh_dias" class="control-group benbetesh_dias">
<span<?php echo $benbetesh->dias->ViewAttributes() ?>>
<?php echo $benbetesh->dias->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$benbetesh_delete->Recordset->MoveNext();
}
$benbetesh_delete->Recordset->Close();
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
fbenbeteshdelete.Init();
</script>
<?php
$benbetesh_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$benbetesh_delete->Page_Terminate();
?>
