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

$oficinas_delete = NULL; // Initialize page object first

class coficinas_delete extends coficinas {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'oficinas';

	// Page object name
	var $PageObjName = 'oficinas_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
			$this->Page_Terminate("oficinaslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in oficinas class, oficinasinfo.php

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

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_oficina

		$this->id_oficina->CellCssStyle = "white-space: nowrap;";

		// id_idioma
		$this->id_idioma->CellCssStyle = "white-space: nowrap;";

		// direccion
		$this->direccion->CellCssStyle = "white-space: nowrap;";

		// lugar_titulo
		$this->lugar_titulo->CellCssStyle = "white-space: nowrap;";

		// telefono
		$this->telefono->CellCssStyle = "white-space: nowrap;";

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
				$sThisKey .= $row['id_oficina'];
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
		$Breadcrumb->Add("list", $this->TableVar, "oficinaslist.php", $this->TableVar, TRUE);
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
if (!isset($oficinas_delete)) $oficinas_delete = new coficinas_delete();

// Page init
$oficinas_delete->Page_Init();

// Page main
$oficinas_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$oficinas_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var oficinas_delete = new ew_Page("oficinas_delete");
oficinas_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = oficinas_delete.PageID; // For backward compatibility

// Form object
var foficinasdelete = new ew_Form("foficinasdelete");

// Form_CustomValidate event
foficinasdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
foficinasdelete.ValidateRequired = true;
<?php } else { ?>
foficinasdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
foficinasdelete.Lists["x_id_idioma"] = {"LinkField":"x_id_idioma","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($oficinas_delete->Recordset = $oficinas_delete->LoadRecordset())
	$oficinas_deleteTotalRecs = $oficinas_delete->Recordset->RecordCount(); // Get record count
if ($oficinas_deleteTotalRecs <= 0) { // No record found, exit
	if ($oficinas_delete->Recordset)
		$oficinas_delete->Recordset->Close();
	$oficinas_delete->Page_Terminate("oficinaslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $oficinas_delete->ShowPageHeader(); ?>
<?php
$oficinas_delete->ShowMessage();
?>
<form name="foficinasdelete" id="foficinasdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="oficinas">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($oficinas_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_oficinasdelete" class="ewTable ewTableSeparate">
<?php echo $oficinas->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($oficinas->id_idioma->Visible) { // id_idioma ?>
		<td><span id="elh_oficinas_id_idioma" class="oficinas_id_idioma"><?php echo $oficinas->id_idioma->FldCaption() ?></span></td>
<?php } ?>
<?php if ($oficinas->direccion->Visible) { // direccion ?>
		<td><span id="elh_oficinas_direccion" class="oficinas_direccion"><?php echo $oficinas->direccion->FldCaption() ?></span></td>
<?php } ?>
<?php if ($oficinas->lugar_titulo->Visible) { // lugar_titulo ?>
		<td><span id="elh_oficinas_lugar_titulo" class="oficinas_lugar_titulo"><?php echo $oficinas->lugar_titulo->FldCaption() ?></span></td>
<?php } ?>
<?php if ($oficinas->telefono->Visible) { // telefono ?>
		<td><span id="elh_oficinas_telefono" class="oficinas_telefono"><?php echo $oficinas->telefono->FldCaption() ?></span></td>
<?php } ?>
<?php if ($oficinas->correo->Visible) { // correo ?>
		<td><span id="elh_oficinas_correo" class="oficinas_correo"><?php echo $oficinas->correo->FldCaption() ?></span></td>
<?php } ?>
<?php if ($oficinas->tienda->Visible) { // tienda ?>
		<td><span id="elh_oficinas_tienda" class="oficinas_tienda"><?php echo $oficinas->tienda->FldCaption() ?></span></td>
<?php } ?>
<?php if ($oficinas->fax->Visible) { // fax ?>
		<td><span id="elh_oficinas_fax" class="oficinas_fax"><?php echo $oficinas->fax->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$oficinas_delete->RecCnt = 0;
$i = 0;
while (!$oficinas_delete->Recordset->EOF) {
	$oficinas_delete->RecCnt++;
	$oficinas_delete->RowCnt++;

	// Set row properties
	$oficinas->ResetAttrs();
	$oficinas->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$oficinas_delete->LoadRowValues($oficinas_delete->Recordset);

	// Render row
	$oficinas_delete->RenderRow();
?>
	<tr<?php echo $oficinas->RowAttributes() ?>>
<?php if ($oficinas->id_idioma->Visible) { // id_idioma ?>
		<td<?php echo $oficinas->id_idioma->CellAttributes() ?>>
<span id="el<?php echo $oficinas_delete->RowCnt ?>_oficinas_id_idioma" class="control-group oficinas_id_idioma">
<span<?php echo $oficinas->id_idioma->ViewAttributes() ?>>
<?php echo $oficinas->id_idioma->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($oficinas->direccion->Visible) { // direccion ?>
		<td<?php echo $oficinas->direccion->CellAttributes() ?>>
<span id="el<?php echo $oficinas_delete->RowCnt ?>_oficinas_direccion" class="control-group oficinas_direccion">
<span<?php echo $oficinas->direccion->ViewAttributes() ?>>
<?php echo $oficinas->direccion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($oficinas->lugar_titulo->Visible) { // lugar_titulo ?>
		<td<?php echo $oficinas->lugar_titulo->CellAttributes() ?>>
<span id="el<?php echo $oficinas_delete->RowCnt ?>_oficinas_lugar_titulo" class="control-group oficinas_lugar_titulo">
<span<?php echo $oficinas->lugar_titulo->ViewAttributes() ?>>
<?php echo $oficinas->lugar_titulo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($oficinas->telefono->Visible) { // telefono ?>
		<td<?php echo $oficinas->telefono->CellAttributes() ?>>
<span id="el<?php echo $oficinas_delete->RowCnt ?>_oficinas_telefono" class="control-group oficinas_telefono">
<span<?php echo $oficinas->telefono->ViewAttributes() ?>>
<?php echo $oficinas->telefono->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($oficinas->correo->Visible) { // correo ?>
		<td<?php echo $oficinas->correo->CellAttributes() ?>>
<span id="el<?php echo $oficinas_delete->RowCnt ?>_oficinas_correo" class="control-group oficinas_correo">
<span<?php echo $oficinas->correo->ViewAttributes() ?>>
<?php echo $oficinas->correo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($oficinas->tienda->Visible) { // tienda ?>
		<td<?php echo $oficinas->tienda->CellAttributes() ?>>
<span id="el<?php echo $oficinas_delete->RowCnt ?>_oficinas_tienda" class="control-group oficinas_tienda">
<span<?php echo $oficinas->tienda->ViewAttributes() ?>>
<?php echo $oficinas->tienda->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($oficinas->fax->Visible) { // fax ?>
		<td<?php echo $oficinas->fax->CellAttributes() ?>>
<span id="el<?php echo $oficinas_delete->RowCnt ?>_oficinas_fax" class="control-group oficinas_fax">
<span<?php echo $oficinas->fax->ViewAttributes() ?>>
<?php echo $oficinas->fax->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$oficinas_delete->Recordset->MoveNext();
}
$oficinas_delete->Recordset->Close();
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
foficinasdelete.Init();
</script>
<?php
$oficinas_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$oficinas_delete->Page_Terminate();
?>
