<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "marcasinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$marcas_delete = NULL; // Initialize page object first

class cmarcas_delete extends cmarcas {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'marcas';

	// Page object name
	var $PageObjName = 'marcas_delete';

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

		// Table object (marcas)
		if (!isset($GLOBALS["marcas"]) || get_class($GLOBALS["marcas"]) == "cmarcas") {
			$GLOBALS["marcas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["marcas"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'marcas', TRUE);

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
			$this->Page_Terminate("marcaslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in marcas class, marcasinfo.php

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
		$this->id_marca->setDbValue($rs->fields('id_marca'));
		$this->id_idioma->setDbValue($rs->fields('id_idioma'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->amigable->setDbValue($rs->fields('amigable'));
		$this->logo_url->Upload->DbValue = $rs->fields('logo_url');
		$this->logo_url->CurrentValue = $this->logo_url->Upload->DbValue;
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
		$this->tienda1_url->Upload->DbValue = $rs->fields('tienda1_url');
		$this->tienda1_url->CurrentValue = $this->tienda1_url->Upload->DbValue;
		$this->tienda2_url->Upload->DbValue = $rs->fields('tienda2_url');
		$this->tienda2_url->CurrentValue = $this->tienda2_url->Upload->DbValue;
		$this->tienda3_url->Upload->DbValue = $rs->fields('tienda3_url');
		$this->tienda3_url->CurrentValue = $this->tienda3_url->Upload->DbValue;
		$this->titulo1->setDbValue($rs->fields('titulo1'));
		$this->descripcion1->setDbValue($rs->fields('descripcion1'));
		$this->titulo2->setDbValue($rs->fields('titulo2'));
		$this->descripcion2->setDbValue($rs->fields('descripcion2'));
		$this->titulo3->setDbValue($rs->fields('titulo3'));
		$this->descripcion3->setDbValue($rs->fields('descripcion3'));
		$this->tiendas_pie->setDbValue($rs->fields('tiendas_pie'));
		$this->marcas_pie->setDbValue($rs->fields('marcas_pie'));
		$this->descripcion_form->setDbValue($rs->fields('descripcion_form'));
		$this->telefono->setDbValue($rs->fields('telefono'));
		$this->imagen_url->Upload->DbValue = $rs->fields('imagen_url');
		$this->imagen_url->CurrentValue = $this->imagen_url->Upload->DbValue;
		$this->url_facebook->setDbValue($rs->fields('url_facebook'));
		$this->url_twitter->setDbValue($rs->fields('url_twitter'));
		$this->url_youtube->setDbValue($rs->fields('url_youtube'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_marca->DbValue = $row['id_marca'];
		$this->id_idioma->DbValue = $row['id_idioma'];
		$this->nombre->DbValue = $row['nombre'];
		$this->amigable->DbValue = $row['amigable'];
		$this->logo_url->Upload->DbValue = $row['logo_url'];
		$this->slideshow1_url->Upload->DbValue = $row['slideshow1_url'];
		$this->slideshow2_url->Upload->DbValue = $row['slideshow2_url'];
		$this->slideshow3_url->Upload->DbValue = $row['slideshow3_url'];
		$this->slideshow4_url->Upload->DbValue = $row['slideshow4_url'];
		$this->slideshow5_url->Upload->DbValue = $row['slideshow5_url'];
		$this->tienda1_url->Upload->DbValue = $row['tienda1_url'];
		$this->tienda2_url->Upload->DbValue = $row['tienda2_url'];
		$this->tienda3_url->Upload->DbValue = $row['tienda3_url'];
		$this->titulo1->DbValue = $row['titulo1'];
		$this->descripcion1->DbValue = $row['descripcion1'];
		$this->titulo2->DbValue = $row['titulo2'];
		$this->descripcion2->DbValue = $row['descripcion2'];
		$this->titulo3->DbValue = $row['titulo3'];
		$this->descripcion3->DbValue = $row['descripcion3'];
		$this->tiendas_pie->DbValue = $row['tiendas_pie'];
		$this->marcas_pie->DbValue = $row['marcas_pie'];
		$this->descripcion_form->DbValue = $row['descripcion_form'];
		$this->telefono->DbValue = $row['telefono'];
		$this->imagen_url->Upload->DbValue = $row['imagen_url'];
		$this->url_facebook->DbValue = $row['url_facebook'];
		$this->url_twitter->DbValue = $row['url_twitter'];
		$this->url_youtube->DbValue = $row['url_youtube'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_marca

		$this->id_marca->CellCssStyle = "white-space: nowrap;";

		// id_idioma
		// nombre
		// amigable
		// logo_url
		// slideshow1_url
		// slideshow2_url
		// slideshow3_url
		// slideshow4_url
		// slideshow5_url
		// tienda1_url
		// tienda2_url
		// tienda3_url
		// titulo1
		// descripcion1
		// titulo2
		// descripcion2
		// titulo3
		// descripcion3
		// tiendas_pie
		// marcas_pie
		// descripcion_form
		// telefono
		// imagen_url
		// url_facebook
		// url_twitter
		// url_youtube

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

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// amigable
			$this->amigable->ViewValue = $this->amigable->CurrentValue;
			$this->amigable->ViewCustomAttributes = "";

			// logo_url
			if (!ew_Empty($this->logo_url->Upload->DbValue)) {
				$this->logo_url->ViewValue = $this->logo_url->Upload->DbValue;
			} else {
				$this->logo_url->ViewValue = "";
			}
			$this->logo_url->ViewCustomAttributes = "";

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

			// titulo1
			$this->titulo1->ViewValue = $this->titulo1->CurrentValue;
			$this->titulo1->ViewCustomAttributes = "";

			// titulo2
			$this->titulo2->ViewValue = $this->titulo2->CurrentValue;
			$this->titulo2->ViewCustomAttributes = "";

			// titulo3
			$this->titulo3->ViewValue = $this->titulo3->CurrentValue;
			$this->titulo3->ViewCustomAttributes = "";

			// tiendas_pie
			if (strval($this->tiendas_pie->CurrentValue) <> "") {
				switch ($this->tiendas_pie->CurrentValue) {
					case $this->tiendas_pie->FldTagValue(1):
						$this->tiendas_pie->ViewValue = $this->tiendas_pie->FldTagCaption(1) <> "" ? $this->tiendas_pie->FldTagCaption(1) : $this->tiendas_pie->CurrentValue;
						break;
					case $this->tiendas_pie->FldTagValue(2):
						$this->tiendas_pie->ViewValue = $this->tiendas_pie->FldTagCaption(2) <> "" ? $this->tiendas_pie->FldTagCaption(2) : $this->tiendas_pie->CurrentValue;
						break;
					default:
						$this->tiendas_pie->ViewValue = $this->tiendas_pie->CurrentValue;
				}
			} else {
				$this->tiendas_pie->ViewValue = NULL;
			}
			$this->tiendas_pie->ViewCustomAttributes = "";

			// marcas_pie
			if (strval($this->marcas_pie->CurrentValue) <> "") {
				switch ($this->marcas_pie->CurrentValue) {
					case $this->marcas_pie->FldTagValue(1):
						$this->marcas_pie->ViewValue = $this->marcas_pie->FldTagCaption(1) <> "" ? $this->marcas_pie->FldTagCaption(1) : $this->marcas_pie->CurrentValue;
						break;
					case $this->marcas_pie->FldTagValue(2):
						$this->marcas_pie->ViewValue = $this->marcas_pie->FldTagCaption(2) <> "" ? $this->marcas_pie->FldTagCaption(2) : $this->marcas_pie->CurrentValue;
						break;
					default:
						$this->marcas_pie->ViewValue = $this->marcas_pie->CurrentValue;
				}
			} else {
				$this->marcas_pie->ViewValue = NULL;
			}
			$this->marcas_pie->ViewCustomAttributes = "";

			// descripcion_form
			$this->descripcion_form->ViewValue = $this->descripcion_form->CurrentValue;
			$this->descripcion_form->ViewCustomAttributes = "";

			// telefono
			$this->telefono->ViewValue = $this->telefono->CurrentValue;
			$this->telefono->ViewCustomAttributes = "";

			// id_idioma
			$this->id_idioma->LinkCustomAttributes = "";
			$this->id_idioma->HrefValue = "";
			$this->id_idioma->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// amigable
			$this->amigable->LinkCustomAttributes = "";
			$this->amigable->HrefValue = "";
			$this->amigable->TooltipValue = "";

			// logo_url
			$this->logo_url->LinkCustomAttributes = "";
			$this->logo_url->HrefValue = "";
			$this->logo_url->HrefValue2 = $this->logo_url->UploadPath . $this->logo_url->Upload->DbValue;
			$this->logo_url->TooltipValue = "";

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

			// titulo1
			$this->titulo1->LinkCustomAttributes = "";
			$this->titulo1->HrefValue = "";
			$this->titulo1->TooltipValue = "";

			// titulo2
			$this->titulo2->LinkCustomAttributes = "";
			$this->titulo2->HrefValue = "";
			$this->titulo2->TooltipValue = "";

			// titulo3
			$this->titulo3->LinkCustomAttributes = "";
			$this->titulo3->HrefValue = "";
			$this->titulo3->TooltipValue = "";

			// tiendas_pie
			$this->tiendas_pie->LinkCustomAttributes = "";
			$this->tiendas_pie->HrefValue = "";
			$this->tiendas_pie->TooltipValue = "";

			// marcas_pie
			$this->marcas_pie->LinkCustomAttributes = "";
			$this->marcas_pie->HrefValue = "";
			$this->marcas_pie->TooltipValue = "";

			// descripcion_form
			$this->descripcion_form->LinkCustomAttributes = "";
			$this->descripcion_form->HrefValue = "";
			$this->descripcion_form->TooltipValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";
			$this->telefono->TooltipValue = "";
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
				$sThisKey .= $row['id_marca'];
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
		$Breadcrumb->Add("list", $this->TableVar, "marcaslist.php", $this->TableVar, TRUE);
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
if (!isset($marcas_delete)) $marcas_delete = new cmarcas_delete();

// Page init
$marcas_delete->Page_Init();

// Page main
$marcas_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$marcas_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var marcas_delete = new ew_Page("marcas_delete");
marcas_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = marcas_delete.PageID; // For backward compatibility

// Form object
var fmarcasdelete = new ew_Form("fmarcasdelete");

// Form_CustomValidate event
fmarcasdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmarcasdelete.ValidateRequired = true;
<?php } else { ?>
fmarcasdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmarcasdelete.Lists["x_id_idioma"] = {"LinkField":"x_id_idioma","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($marcas_delete->Recordset = $marcas_delete->LoadRecordset())
	$marcas_deleteTotalRecs = $marcas_delete->Recordset->RecordCount(); // Get record count
if ($marcas_deleteTotalRecs <= 0) { // No record found, exit
	if ($marcas_delete->Recordset)
		$marcas_delete->Recordset->Close();
	$marcas_delete->Page_Terminate("marcaslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $marcas_delete->ShowPageHeader(); ?>
<?php
$marcas_delete->ShowMessage();
?>
<form name="fmarcasdelete" id="fmarcasdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="marcas">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($marcas_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_marcasdelete" class="ewTable ewTableSeparate">
<?php echo $marcas->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($marcas->id_idioma->Visible) { // id_idioma ?>
		<td><span id="elh_marcas_id_idioma" class="marcas_id_idioma"><?php echo $marcas->id_idioma->FldCaption() ?></span></td>
<?php } ?>
<?php if ($marcas->nombre->Visible) { // nombre ?>
		<td><span id="elh_marcas_nombre" class="marcas_nombre"><?php echo $marcas->nombre->FldCaption() ?></span></td>
<?php } ?>
<?php if ($marcas->amigable->Visible) { // amigable ?>
		<td><span id="elh_marcas_amigable" class="marcas_amigable"><?php echo $marcas->amigable->FldCaption() ?></span></td>
<?php } ?>
<?php if ($marcas->logo_url->Visible) { // logo_url ?>
		<td><span id="elh_marcas_logo_url" class="marcas_logo_url"><?php echo $marcas->logo_url->FldCaption() ?></span></td>
<?php } ?>
<?php if ($marcas->slideshow1_url->Visible) { // slideshow1_url ?>
		<td><span id="elh_marcas_slideshow1_url" class="marcas_slideshow1_url"><?php echo $marcas->slideshow1_url->FldCaption() ?></span></td>
<?php } ?>
<?php if ($marcas->slideshow2_url->Visible) { // slideshow2_url ?>
		<td><span id="elh_marcas_slideshow2_url" class="marcas_slideshow2_url"><?php echo $marcas->slideshow2_url->FldCaption() ?></span></td>
<?php } ?>
<?php if ($marcas->slideshow3_url->Visible) { // slideshow3_url ?>
		<td><span id="elh_marcas_slideshow3_url" class="marcas_slideshow3_url"><?php echo $marcas->slideshow3_url->FldCaption() ?></span></td>
<?php } ?>
<?php if ($marcas->slideshow4_url->Visible) { // slideshow4_url ?>
		<td><span id="elh_marcas_slideshow4_url" class="marcas_slideshow4_url"><?php echo $marcas->slideshow4_url->FldCaption() ?></span></td>
<?php } ?>
<?php if ($marcas->slideshow5_url->Visible) { // slideshow5_url ?>
		<td><span id="elh_marcas_slideshow5_url" class="marcas_slideshow5_url"><?php echo $marcas->slideshow5_url->FldCaption() ?></span></td>
<?php } ?>
<?php if ($marcas->titulo1->Visible) { // titulo1 ?>
		<td><span id="elh_marcas_titulo1" class="marcas_titulo1"><?php echo $marcas->titulo1->FldCaption() ?></span></td>
<?php } ?>
<?php if ($marcas->titulo2->Visible) { // titulo2 ?>
		<td><span id="elh_marcas_titulo2" class="marcas_titulo2"><?php echo $marcas->titulo2->FldCaption() ?></span></td>
<?php } ?>
<?php if ($marcas->titulo3->Visible) { // titulo3 ?>
		<td><span id="elh_marcas_titulo3" class="marcas_titulo3"><?php echo $marcas->titulo3->FldCaption() ?></span></td>
<?php } ?>
<?php if ($marcas->tiendas_pie->Visible) { // tiendas_pie ?>
		<td><span id="elh_marcas_tiendas_pie" class="marcas_tiendas_pie"><?php echo $marcas->tiendas_pie->FldCaption() ?></span></td>
<?php } ?>
<?php if ($marcas->marcas_pie->Visible) { // marcas_pie ?>
		<td><span id="elh_marcas_marcas_pie" class="marcas_marcas_pie"><?php echo $marcas->marcas_pie->FldCaption() ?></span></td>
<?php } ?>
<?php if ($marcas->descripcion_form->Visible) { // descripcion_form ?>
		<td><span id="elh_marcas_descripcion_form" class="marcas_descripcion_form"><?php echo $marcas->descripcion_form->FldCaption() ?></span></td>
<?php } ?>
<?php if ($marcas->telefono->Visible) { // telefono ?>
		<td><span id="elh_marcas_telefono" class="marcas_telefono"><?php echo $marcas->telefono->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$marcas_delete->RecCnt = 0;
$i = 0;
while (!$marcas_delete->Recordset->EOF) {
	$marcas_delete->RecCnt++;
	$marcas_delete->RowCnt++;

	// Set row properties
	$marcas->ResetAttrs();
	$marcas->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$marcas_delete->LoadRowValues($marcas_delete->Recordset);

	// Render row
	$marcas_delete->RenderRow();
?>
	<tr<?php echo $marcas->RowAttributes() ?>>
<?php if ($marcas->id_idioma->Visible) { // id_idioma ?>
		<td<?php echo $marcas->id_idioma->CellAttributes() ?>>
<span id="el<?php echo $marcas_delete->RowCnt ?>_marcas_id_idioma" class="control-group marcas_id_idioma">
<span<?php echo $marcas->id_idioma->ViewAttributes() ?>>
<?php echo $marcas->id_idioma->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($marcas->nombre->Visible) { // nombre ?>
		<td<?php echo $marcas->nombre->CellAttributes() ?>>
<span id="el<?php echo $marcas_delete->RowCnt ?>_marcas_nombre" class="control-group marcas_nombre">
<span<?php echo $marcas->nombre->ViewAttributes() ?>>
<?php echo $marcas->nombre->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($marcas->amigable->Visible) { // amigable ?>
		<td<?php echo $marcas->amigable->CellAttributes() ?>>
<span id="el<?php echo $marcas_delete->RowCnt ?>_marcas_amigable" class="control-group marcas_amigable">
<span<?php echo $marcas->amigable->ViewAttributes() ?>>
<?php echo $marcas->amigable->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($marcas->logo_url->Visible) { // logo_url ?>
		<td<?php echo $marcas->logo_url->CellAttributes() ?>>
<span id="el<?php echo $marcas_delete->RowCnt ?>_marcas_logo_url" class="control-group marcas_logo_url">
<span<?php echo $marcas->logo_url->ViewAttributes() ?>>
<?php if ($marcas->logo_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->logo_url->Upload->DbValue)) { ?>
<?php echo $marcas->logo_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->logo_url->Upload->DbValue)) { ?>
<?php echo $marcas->logo_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($marcas->slideshow1_url->Visible) { // slideshow1_url ?>
		<td<?php echo $marcas->slideshow1_url->CellAttributes() ?>>
<span id="el<?php echo $marcas_delete->RowCnt ?>_marcas_slideshow1_url" class="control-group marcas_slideshow1_url">
<span<?php echo $marcas->slideshow1_url->ViewAttributes() ?>>
<?php if ($marcas->slideshow1_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->slideshow1_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow1_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->slideshow1_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow1_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($marcas->slideshow2_url->Visible) { // slideshow2_url ?>
		<td<?php echo $marcas->slideshow2_url->CellAttributes() ?>>
<span id="el<?php echo $marcas_delete->RowCnt ?>_marcas_slideshow2_url" class="control-group marcas_slideshow2_url">
<span<?php echo $marcas->slideshow2_url->ViewAttributes() ?>>
<?php if ($marcas->slideshow2_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->slideshow2_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow2_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->slideshow2_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow2_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($marcas->slideshow3_url->Visible) { // slideshow3_url ?>
		<td<?php echo $marcas->slideshow3_url->CellAttributes() ?>>
<span id="el<?php echo $marcas_delete->RowCnt ?>_marcas_slideshow3_url" class="control-group marcas_slideshow3_url">
<span<?php echo $marcas->slideshow3_url->ViewAttributes() ?>>
<?php if ($marcas->slideshow3_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->slideshow3_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow3_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->slideshow3_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow3_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($marcas->slideshow4_url->Visible) { // slideshow4_url ?>
		<td<?php echo $marcas->slideshow4_url->CellAttributes() ?>>
<span id="el<?php echo $marcas_delete->RowCnt ?>_marcas_slideshow4_url" class="control-group marcas_slideshow4_url">
<span<?php echo $marcas->slideshow4_url->ViewAttributes() ?>>
<?php if ($marcas->slideshow4_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->slideshow4_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow4_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->slideshow4_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow4_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($marcas->slideshow5_url->Visible) { // slideshow5_url ?>
		<td<?php echo $marcas->slideshow5_url->CellAttributes() ?>>
<span id="el<?php echo $marcas_delete->RowCnt ?>_marcas_slideshow5_url" class="control-group marcas_slideshow5_url">
<span<?php echo $marcas->slideshow5_url->ViewAttributes() ?>>
<?php if ($marcas->slideshow5_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->slideshow5_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow5_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->slideshow5_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow5_url->ListViewValue() ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($marcas->titulo1->Visible) { // titulo1 ?>
		<td<?php echo $marcas->titulo1->CellAttributes() ?>>
<span id="el<?php echo $marcas_delete->RowCnt ?>_marcas_titulo1" class="control-group marcas_titulo1">
<span<?php echo $marcas->titulo1->ViewAttributes() ?>>
<?php echo $marcas->titulo1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($marcas->titulo2->Visible) { // titulo2 ?>
		<td<?php echo $marcas->titulo2->CellAttributes() ?>>
<span id="el<?php echo $marcas_delete->RowCnt ?>_marcas_titulo2" class="control-group marcas_titulo2">
<span<?php echo $marcas->titulo2->ViewAttributes() ?>>
<?php echo $marcas->titulo2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($marcas->titulo3->Visible) { // titulo3 ?>
		<td<?php echo $marcas->titulo3->CellAttributes() ?>>
<span id="el<?php echo $marcas_delete->RowCnt ?>_marcas_titulo3" class="control-group marcas_titulo3">
<span<?php echo $marcas->titulo3->ViewAttributes() ?>>
<?php echo $marcas->titulo3->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($marcas->tiendas_pie->Visible) { // tiendas_pie ?>
		<td<?php echo $marcas->tiendas_pie->CellAttributes() ?>>
<span id="el<?php echo $marcas_delete->RowCnt ?>_marcas_tiendas_pie" class="control-group marcas_tiendas_pie">
<span<?php echo $marcas->tiendas_pie->ViewAttributes() ?>>
<?php echo $marcas->tiendas_pie->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($marcas->marcas_pie->Visible) { // marcas_pie ?>
		<td<?php echo $marcas->marcas_pie->CellAttributes() ?>>
<span id="el<?php echo $marcas_delete->RowCnt ?>_marcas_marcas_pie" class="control-group marcas_marcas_pie">
<span<?php echo $marcas->marcas_pie->ViewAttributes() ?>>
<?php echo $marcas->marcas_pie->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($marcas->descripcion_form->Visible) { // descripcion_form ?>
		<td<?php echo $marcas->descripcion_form->CellAttributes() ?>>
<span id="el<?php echo $marcas_delete->RowCnt ?>_marcas_descripcion_form" class="control-group marcas_descripcion_form">
<span<?php echo $marcas->descripcion_form->ViewAttributes() ?>>
<?php echo $marcas->descripcion_form->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($marcas->telefono->Visible) { // telefono ?>
		<td<?php echo $marcas->telefono->CellAttributes() ?>>
<span id="el<?php echo $marcas_delete->RowCnt ?>_marcas_telefono" class="control-group marcas_telefono">
<span<?php echo $marcas->telefono->ViewAttributes() ?>>
<?php echo $marcas->telefono->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$marcas_delete->Recordset->MoveNext();
}
$marcas_delete->Recordset->Close();
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
fmarcasdelete.Init();
</script>
<?php
$marcas_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$marcas_delete->Page_Terminate();
?>
