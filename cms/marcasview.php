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

$marcas_view = NULL; // Initialize page object first

class cmarcas_view extends cmarcas {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'marcas';

	// Page object name
	var $PageObjName = 'marcas_view';

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

		// Table object (marcas)
		if (!isset($GLOBALS["marcas"]) || get_class($GLOBALS["marcas"]) == "cmarcas") {
			$GLOBALS["marcas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["marcas"];
		}
		$KeyUrl = "";
		if (@$_GET["id_marca"] <> "") {
			$this->RecKey["id_marca"] = $_GET["id_marca"];
			$KeyUrl .= "&amp;id_marca=" . urlencode($this->RecKey["id_marca"]);
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
			define("EW_TABLE_NAME", 'marcas', TRUE);

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
			if (@$_GET["id_marca"] <> "") {
				$this->id_marca->setQueryStringValue($_GET["id_marca"]);
				$this->RecKey["id_marca"] = $this->id_marca->QueryStringValue;
			} else {
				$sReturnUrl = "marcaslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "marcaslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "marcaslist.php"; // Not page request, return to list
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

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->IsLoggedIn());

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->IsLoggedIn());

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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id_marca
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

			// tienda1_url
			if (!ew_Empty($this->tienda1_url->Upload->DbValue)) {
				$this->tienda1_url->ViewValue = $this->tienda1_url->Upload->DbValue;
			} else {
				$this->tienda1_url->ViewValue = "";
			}
			$this->tienda1_url->ViewCustomAttributes = "";

			// tienda2_url
			if (!ew_Empty($this->tienda2_url->Upload->DbValue)) {
				$this->tienda2_url->ViewValue = $this->tienda2_url->Upload->DbValue;
			} else {
				$this->tienda2_url->ViewValue = "";
			}
			$this->tienda2_url->ViewCustomAttributes = "";

			// tienda3_url
			if (!ew_Empty($this->tienda3_url->Upload->DbValue)) {
				$this->tienda3_url->ViewValue = $this->tienda3_url->Upload->DbValue;
			} else {
				$this->tienda3_url->ViewValue = "";
			}
			$this->tienda3_url->ViewCustomAttributes = "";

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

			// imagen_url
			if (!ew_Empty($this->imagen_url->Upload->DbValue)) {
				$this->imagen_url->ViewValue = $this->imagen_url->Upload->DbValue;
			} else {
				$this->imagen_url->ViewValue = "";
			}
			$this->imagen_url->ViewCustomAttributes = "";

			// url_facebook
			$this->url_facebook->ViewValue = $this->url_facebook->CurrentValue;
			$this->url_facebook->ViewCustomAttributes = "";

			// url_twitter
			$this->url_twitter->ViewValue = $this->url_twitter->CurrentValue;
			$this->url_twitter->ViewCustomAttributes = "";

			// url_youtube
			$this->url_youtube->ViewValue = $this->url_youtube->CurrentValue;
			$this->url_youtube->ViewCustomAttributes = "";

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

			// tienda1_url
			$this->tienda1_url->LinkCustomAttributes = "";
			$this->tienda1_url->HrefValue = "";
			$this->tienda1_url->HrefValue2 = $this->tienda1_url->UploadPath . $this->tienda1_url->Upload->DbValue;
			$this->tienda1_url->TooltipValue = "";

			// tienda2_url
			$this->tienda2_url->LinkCustomAttributes = "";
			$this->tienda2_url->HrefValue = "";
			$this->tienda2_url->HrefValue2 = $this->tienda2_url->UploadPath . $this->tienda2_url->Upload->DbValue;
			$this->tienda2_url->TooltipValue = "";

			// tienda3_url
			$this->tienda3_url->LinkCustomAttributes = "";
			$this->tienda3_url->HrefValue = "";
			$this->tienda3_url->HrefValue2 = $this->tienda3_url->UploadPath . $this->tienda3_url->Upload->DbValue;
			$this->tienda3_url->TooltipValue = "";

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

			// imagen_url
			$this->imagen_url->LinkCustomAttributes = "";
			$this->imagen_url->HrefValue = "";
			$this->imagen_url->HrefValue2 = $this->imagen_url->UploadPath . $this->imagen_url->Upload->DbValue;
			$this->imagen_url->TooltipValue = "";

			// url_facebook
			$this->url_facebook->LinkCustomAttributes = "";
			$this->url_facebook->HrefValue = "";
			$this->url_facebook->TooltipValue = "";

			// url_twitter
			$this->url_twitter->LinkCustomAttributes = "";
			$this->url_twitter->HrefValue = "";
			$this->url_twitter->TooltipValue = "";

			// url_youtube
			$this->url_youtube->LinkCustomAttributes = "";
			$this->url_youtube->HrefValue = "";
			$this->url_youtube->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "marcaslist.php", $this->TableVar, TRUE);
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
if (!isset($marcas_view)) $marcas_view = new cmarcas_view();

// Page init
$marcas_view->Page_Init();

// Page main
$marcas_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$marcas_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var marcas_view = new ew_Page("marcas_view");
marcas_view.PageID = "view"; // Page ID
var EW_PAGE_ID = marcas_view.PageID; // For backward compatibility

// Form object
var fmarcasview = new ew_Form("fmarcasview");

// Form_CustomValidate event
fmarcasview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmarcasview.ValidateRequired = true;
<?php } else { ?>
fmarcasview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmarcasview.Lists["x_id_idioma"] = {"LinkField":"x_id_idioma","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<div class="ewViewExportOptions">
<?php $marcas_view->ExportOptions->Render("body") ?>
<?php if (!$marcas_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($marcas_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php $marcas_view->ShowPageHeader(); ?>
<?php
$marcas_view->ShowMessage();
?>
<form name="fmarcasview" id="fmarcasview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="marcas">
<table class="ewGrid"><tr><td>
<table id="tbl_marcasview" class="table table-bordered table-striped">
<?php if ($marcas->id_idioma->Visible) { // id_idioma ?>
	<tr id="r_id_idioma">
		<td><span id="elh_marcas_id_idioma"><?php echo $marcas->id_idioma->FldCaption() ?></span></td>
		<td<?php echo $marcas->id_idioma->CellAttributes() ?>>
<span id="el_marcas_id_idioma" class="control-group">
<span<?php echo $marcas->id_idioma->ViewAttributes() ?>>
<?php echo $marcas->id_idioma->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->nombre->Visible) { // nombre ?>
	<tr id="r_nombre">
		<td><span id="elh_marcas_nombre"><?php echo $marcas->nombre->FldCaption() ?></span></td>
		<td<?php echo $marcas->nombre->CellAttributes() ?>>
<span id="el_marcas_nombre" class="control-group">
<span<?php echo $marcas->nombre->ViewAttributes() ?>>
<?php echo $marcas->nombre->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->amigable->Visible) { // amigable ?>
	<tr id="r_amigable">
		<td><span id="elh_marcas_amigable"><?php echo $marcas->amigable->FldCaption() ?></span></td>
		<td<?php echo $marcas->amigable->CellAttributes() ?>>
<span id="el_marcas_amigable" class="control-group">
<span<?php echo $marcas->amigable->ViewAttributes() ?>>
<?php echo $marcas->amigable->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->logo_url->Visible) { // logo_url ?>
	<tr id="r_logo_url">
		<td><span id="elh_marcas_logo_url"><?php echo $marcas->logo_url->FldCaption() ?></span></td>
		<td<?php echo $marcas->logo_url->CellAttributes() ?>>
<span id="el_marcas_logo_url" class="control-group">
<span<?php echo $marcas->logo_url->ViewAttributes() ?>>
<?php if ($marcas->logo_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->logo_url->Upload->DbValue)) { ?>
<?php echo $marcas->logo_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->logo_url->Upload->DbValue)) { ?>
<?php echo $marcas->logo_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->slideshow1_url->Visible) { // slideshow1_url ?>
	<tr id="r_slideshow1_url">
		<td><span id="elh_marcas_slideshow1_url"><?php echo $marcas->slideshow1_url->FldCaption() ?></span></td>
		<td<?php echo $marcas->slideshow1_url->CellAttributes() ?>>
<span id="el_marcas_slideshow1_url" class="control-group">
<span<?php echo $marcas->slideshow1_url->ViewAttributes() ?>>
<?php if ($marcas->slideshow1_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->slideshow1_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow1_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->slideshow1_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow1_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->slideshow2_url->Visible) { // slideshow2_url ?>
	<tr id="r_slideshow2_url">
		<td><span id="elh_marcas_slideshow2_url"><?php echo $marcas->slideshow2_url->FldCaption() ?></span></td>
		<td<?php echo $marcas->slideshow2_url->CellAttributes() ?>>
<span id="el_marcas_slideshow2_url" class="control-group">
<span<?php echo $marcas->slideshow2_url->ViewAttributes() ?>>
<?php if ($marcas->slideshow2_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->slideshow2_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow2_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->slideshow2_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow2_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->slideshow3_url->Visible) { // slideshow3_url ?>
	<tr id="r_slideshow3_url">
		<td><span id="elh_marcas_slideshow3_url"><?php echo $marcas->slideshow3_url->FldCaption() ?></span></td>
		<td<?php echo $marcas->slideshow3_url->CellAttributes() ?>>
<span id="el_marcas_slideshow3_url" class="control-group">
<span<?php echo $marcas->slideshow3_url->ViewAttributes() ?>>
<?php if ($marcas->slideshow3_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->slideshow3_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow3_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->slideshow3_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow3_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->slideshow4_url->Visible) { // slideshow4_url ?>
	<tr id="r_slideshow4_url">
		<td><span id="elh_marcas_slideshow4_url"><?php echo $marcas->slideshow4_url->FldCaption() ?></span></td>
		<td<?php echo $marcas->slideshow4_url->CellAttributes() ?>>
<span id="el_marcas_slideshow4_url" class="control-group">
<span<?php echo $marcas->slideshow4_url->ViewAttributes() ?>>
<?php if ($marcas->slideshow4_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->slideshow4_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow4_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->slideshow4_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow4_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->slideshow5_url->Visible) { // slideshow5_url ?>
	<tr id="r_slideshow5_url">
		<td><span id="elh_marcas_slideshow5_url"><?php echo $marcas->slideshow5_url->FldCaption() ?></span></td>
		<td<?php echo $marcas->slideshow5_url->CellAttributes() ?>>
<span id="el_marcas_slideshow5_url" class="control-group">
<span<?php echo $marcas->slideshow5_url->ViewAttributes() ?>>
<?php if ($marcas->slideshow5_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->slideshow5_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow5_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->slideshow5_url->Upload->DbValue)) { ?>
<?php echo $marcas->slideshow5_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->tienda1_url->Visible) { // tienda1_url ?>
	<tr id="r_tienda1_url">
		<td><span id="elh_marcas_tienda1_url"><?php echo $marcas->tienda1_url->FldCaption() ?></span></td>
		<td<?php echo $marcas->tienda1_url->CellAttributes() ?>>
<span id="el_marcas_tienda1_url" class="control-group">
<span<?php echo $marcas->tienda1_url->ViewAttributes() ?>>
<?php if ($marcas->tienda1_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->tienda1_url->Upload->DbValue)) { ?>
<?php echo $marcas->tienda1_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->tienda1_url->Upload->DbValue)) { ?>
<?php echo $marcas->tienda1_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->tienda2_url->Visible) { // tienda2_url ?>
	<tr id="r_tienda2_url">
		<td><span id="elh_marcas_tienda2_url"><?php echo $marcas->tienda2_url->FldCaption() ?></span></td>
		<td<?php echo $marcas->tienda2_url->CellAttributes() ?>>
<span id="el_marcas_tienda2_url" class="control-group">
<span<?php echo $marcas->tienda2_url->ViewAttributes() ?>>
<?php if ($marcas->tienda2_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->tienda2_url->Upload->DbValue)) { ?>
<?php echo $marcas->tienda2_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->tienda2_url->Upload->DbValue)) { ?>
<?php echo $marcas->tienda2_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->tienda3_url->Visible) { // tienda3_url ?>
	<tr id="r_tienda3_url">
		<td><span id="elh_marcas_tienda3_url"><?php echo $marcas->tienda3_url->FldCaption() ?></span></td>
		<td<?php echo $marcas->tienda3_url->CellAttributes() ?>>
<span id="el_marcas_tienda3_url" class="control-group">
<span<?php echo $marcas->tienda3_url->ViewAttributes() ?>>
<?php if ($marcas->tienda3_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->tienda3_url->Upload->DbValue)) { ?>
<?php echo $marcas->tienda3_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->tienda3_url->Upload->DbValue)) { ?>
<?php echo $marcas->tienda3_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->titulo1->Visible) { // titulo1 ?>
	<tr id="r_titulo1">
		<td><span id="elh_marcas_titulo1"><?php echo $marcas->titulo1->FldCaption() ?></span></td>
		<td<?php echo $marcas->titulo1->CellAttributes() ?>>
<span id="el_marcas_titulo1" class="control-group">
<span<?php echo $marcas->titulo1->ViewAttributes() ?>>
<?php echo $marcas->titulo1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->descripcion1->Visible) { // descripcion1 ?>
	<tr id="r_descripcion1">
		<td><span id="elh_marcas_descripcion1"><?php echo $marcas->descripcion1->FldCaption() ?></span></td>
		<td<?php echo $marcas->descripcion1->CellAttributes() ?>>
<span id="el_marcas_descripcion1" class="control-group">
<span<?php echo $marcas->descripcion1->ViewAttributes() ?>>
<?php echo $marcas->descripcion1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->titulo2->Visible) { // titulo2 ?>
	<tr id="r_titulo2">
		<td><span id="elh_marcas_titulo2"><?php echo $marcas->titulo2->FldCaption() ?></span></td>
		<td<?php echo $marcas->titulo2->CellAttributes() ?>>
<span id="el_marcas_titulo2" class="control-group">
<span<?php echo $marcas->titulo2->ViewAttributes() ?>>
<?php echo $marcas->titulo2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->descripcion2->Visible) { // descripcion2 ?>
	<tr id="r_descripcion2">
		<td><span id="elh_marcas_descripcion2"><?php echo $marcas->descripcion2->FldCaption() ?></span></td>
		<td<?php echo $marcas->descripcion2->CellAttributes() ?>>
<span id="el_marcas_descripcion2" class="control-group">
<span<?php echo $marcas->descripcion2->ViewAttributes() ?>>
<?php echo $marcas->descripcion2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->titulo3->Visible) { // titulo3 ?>
	<tr id="r_titulo3">
		<td><span id="elh_marcas_titulo3"><?php echo $marcas->titulo3->FldCaption() ?></span></td>
		<td<?php echo $marcas->titulo3->CellAttributes() ?>>
<span id="el_marcas_titulo3" class="control-group">
<span<?php echo $marcas->titulo3->ViewAttributes() ?>>
<?php echo $marcas->titulo3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->descripcion3->Visible) { // descripcion3 ?>
	<tr id="r_descripcion3">
		<td><span id="elh_marcas_descripcion3"><?php echo $marcas->descripcion3->FldCaption() ?></span></td>
		<td<?php echo $marcas->descripcion3->CellAttributes() ?>>
<span id="el_marcas_descripcion3" class="control-group">
<span<?php echo $marcas->descripcion3->ViewAttributes() ?>>
<?php echo $marcas->descripcion3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->tiendas_pie->Visible) { // tiendas_pie ?>
	<tr id="r_tiendas_pie">
		<td><span id="elh_marcas_tiendas_pie"><?php echo $marcas->tiendas_pie->FldCaption() ?></span></td>
		<td<?php echo $marcas->tiendas_pie->CellAttributes() ?>>
<span id="el_marcas_tiendas_pie" class="control-group">
<span<?php echo $marcas->tiendas_pie->ViewAttributes() ?>>
<?php echo $marcas->tiendas_pie->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->marcas_pie->Visible) { // marcas_pie ?>
	<tr id="r_marcas_pie">
		<td><span id="elh_marcas_marcas_pie"><?php echo $marcas->marcas_pie->FldCaption() ?></span></td>
		<td<?php echo $marcas->marcas_pie->CellAttributes() ?>>
<span id="el_marcas_marcas_pie" class="control-group">
<span<?php echo $marcas->marcas_pie->ViewAttributes() ?>>
<?php echo $marcas->marcas_pie->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->descripcion_form->Visible) { // descripcion_form ?>
	<tr id="r_descripcion_form">
		<td><span id="elh_marcas_descripcion_form"><?php echo $marcas->descripcion_form->FldCaption() ?></span></td>
		<td<?php echo $marcas->descripcion_form->CellAttributes() ?>>
<span id="el_marcas_descripcion_form" class="control-group">
<span<?php echo $marcas->descripcion_form->ViewAttributes() ?>>
<?php echo $marcas->descripcion_form->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->telefono->Visible) { // telefono ?>
	<tr id="r_telefono">
		<td><span id="elh_marcas_telefono"><?php echo $marcas->telefono->FldCaption() ?></span></td>
		<td<?php echo $marcas->telefono->CellAttributes() ?>>
<span id="el_marcas_telefono" class="control-group">
<span<?php echo $marcas->telefono->ViewAttributes() ?>>
<?php echo $marcas->telefono->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->imagen_url->Visible) { // imagen_url ?>
	<tr id="r_imagen_url">
		<td><span id="elh_marcas_imagen_url"><?php echo $marcas->imagen_url->FldCaption() ?></span></td>
		<td<?php echo $marcas->imagen_url->CellAttributes() ?>>
<span id="el_marcas_imagen_url" class="control-group">
<span<?php echo $marcas->imagen_url->ViewAttributes() ?>>
<?php if ($marcas->imagen_url->LinkAttributes() <> "") { ?>
<?php if (!empty($marcas->imagen_url->Upload->DbValue)) { ?>
<?php echo $marcas->imagen_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($marcas->imagen_url->Upload->DbValue)) { ?>
<?php echo $marcas->imagen_url->ViewValue ?>
<?php } elseif (!in_array($marcas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->url_facebook->Visible) { // url_facebook ?>
	<tr id="r_url_facebook">
		<td><span id="elh_marcas_url_facebook"><?php echo $marcas->url_facebook->FldCaption() ?></span></td>
		<td<?php echo $marcas->url_facebook->CellAttributes() ?>>
<span id="el_marcas_url_facebook" class="control-group">
<span<?php echo $marcas->url_facebook->ViewAttributes() ?>>
<?php echo $marcas->url_facebook->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->url_twitter->Visible) { // url_twitter ?>
	<tr id="r_url_twitter">
		<td><span id="elh_marcas_url_twitter"><?php echo $marcas->url_twitter->FldCaption() ?></span></td>
		<td<?php echo $marcas->url_twitter->CellAttributes() ?>>
<span id="el_marcas_url_twitter" class="control-group">
<span<?php echo $marcas->url_twitter->ViewAttributes() ?>>
<?php echo $marcas->url_twitter->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($marcas->url_youtube->Visible) { // url_youtube ?>
	<tr id="r_url_youtube">
		<td><span id="elh_marcas_url_youtube"><?php echo $marcas->url_youtube->FldCaption() ?></span></td>
		<td<?php echo $marcas->url_youtube->CellAttributes() ?>>
<span id="el_marcas_url_youtube" class="control-group">
<span<?php echo $marcas->url_youtube->ViewAttributes() ?>>
<?php echo $marcas->url_youtube->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
fmarcasview.Init();
</script>
<?php
$marcas_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$marcas_view->Page_Terminate();
?>
