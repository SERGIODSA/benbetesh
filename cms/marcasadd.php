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

$marcas_add = NULL; // Initialize page object first

class cmarcas_add extends cmarcas {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{56E3A665-834A-4135-82FE-063C92D89B0C}";

	// Table name
	var $TableName = 'marcas';

	// Page object name
	var $PageObjName = 'marcas_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id_marca"] != "") {
				$this->id_marca->setQueryStringValue($_GET["id_marca"]);
				$this->setKey("id_marca", $this->id_marca->CurrentValue); // Set up key
			} else {
				$this->setKey("id_marca", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("marcaslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "marcasview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
		$this->logo_url->Upload->Index = $objForm->Index;
		if ($this->logo_url->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->logo_url->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->logo_url->CurrentValue = $this->logo_url->Upload->FileName;
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
		$this->tienda1_url->Upload->Index = $objForm->Index;
		if ($this->tienda1_url->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->tienda1_url->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->tienda1_url->CurrentValue = $this->tienda1_url->Upload->FileName;
		$this->tienda2_url->Upload->Index = $objForm->Index;
		if ($this->tienda2_url->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->tienda2_url->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->tienda2_url->CurrentValue = $this->tienda2_url->Upload->FileName;
		$this->tienda3_url->Upload->Index = $objForm->Index;
		if ($this->tienda3_url->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->tienda3_url->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->tienda3_url->CurrentValue = $this->tienda3_url->Upload->FileName;
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

	// Load default values
	function LoadDefaultValues() {
		$this->id_idioma->CurrentValue = NULL;
		$this->id_idioma->OldValue = $this->id_idioma->CurrentValue;
		$this->nombre->CurrentValue = NULL;
		$this->nombre->OldValue = $this->nombre->CurrentValue;
		$this->amigable->CurrentValue = NULL;
		$this->amigable->OldValue = $this->amigable->CurrentValue;
		$this->logo_url->Upload->DbValue = NULL;
		$this->logo_url->OldValue = $this->logo_url->Upload->DbValue;
		$this->logo_url->CurrentValue = NULL; // Clear file related field
		$this->slideshow1_url->Upload->DbValue = NULL;
		$this->slideshow1_url->OldValue = $this->slideshow1_url->Upload->DbValue;
		$this->slideshow1_url->CurrentValue = NULL; // Clear file related field
		$this->slideshow2_url->Upload->DbValue = NULL;
		$this->slideshow2_url->OldValue = $this->slideshow2_url->Upload->DbValue;
		$this->slideshow2_url->CurrentValue = NULL; // Clear file related field
		$this->slideshow3_url->Upload->DbValue = NULL;
		$this->slideshow3_url->OldValue = $this->slideshow3_url->Upload->DbValue;
		$this->slideshow3_url->CurrentValue = NULL; // Clear file related field
		$this->slideshow4_url->Upload->DbValue = NULL;
		$this->slideshow4_url->OldValue = $this->slideshow4_url->Upload->DbValue;
		$this->slideshow4_url->CurrentValue = NULL; // Clear file related field
		$this->slideshow5_url->Upload->DbValue = NULL;
		$this->slideshow5_url->OldValue = $this->slideshow5_url->Upload->DbValue;
		$this->slideshow5_url->CurrentValue = NULL; // Clear file related field
		$this->tienda1_url->Upload->DbValue = NULL;
		$this->tienda1_url->OldValue = $this->tienda1_url->Upload->DbValue;
		$this->tienda1_url->CurrentValue = NULL; // Clear file related field
		$this->tienda2_url->Upload->DbValue = NULL;
		$this->tienda2_url->OldValue = $this->tienda2_url->Upload->DbValue;
		$this->tienda2_url->CurrentValue = NULL; // Clear file related field
		$this->tienda3_url->Upload->DbValue = NULL;
		$this->tienda3_url->OldValue = $this->tienda3_url->Upload->DbValue;
		$this->tienda3_url->CurrentValue = NULL; // Clear file related field
		$this->titulo1->CurrentValue = NULL;
		$this->titulo1->OldValue = $this->titulo1->CurrentValue;
		$this->descripcion1->CurrentValue = NULL;
		$this->descripcion1->OldValue = $this->descripcion1->CurrentValue;
		$this->titulo2->CurrentValue = NULL;
		$this->titulo2->OldValue = $this->titulo2->CurrentValue;
		$this->descripcion2->CurrentValue = NULL;
		$this->descripcion2->OldValue = $this->descripcion2->CurrentValue;
		$this->titulo3->CurrentValue = NULL;
		$this->titulo3->OldValue = $this->titulo3->CurrentValue;
		$this->descripcion3->CurrentValue = NULL;
		$this->descripcion3->OldValue = $this->descripcion3->CurrentValue;
		$this->tiendas_pie->CurrentValue = "0";
		$this->marcas_pie->CurrentValue = "0";
		$this->descripcion_form->CurrentValue = NULL;
		$this->descripcion_form->OldValue = $this->descripcion_form->CurrentValue;
		$this->telefono->CurrentValue = NULL;
		$this->telefono->OldValue = $this->telefono->CurrentValue;
		$this->imagen_url->Upload->DbValue = NULL;
		$this->imagen_url->OldValue = $this->imagen_url->Upload->DbValue;
		$this->imagen_url->CurrentValue = NULL; // Clear file related field
		$this->url_facebook->CurrentValue = NULL;
		$this->url_facebook->OldValue = $this->url_facebook->CurrentValue;
		$this->url_twitter->CurrentValue = NULL;
		$this->url_twitter->OldValue = $this->url_twitter->CurrentValue;
		$this->url_youtube->CurrentValue = NULL;
		$this->url_youtube->OldValue = $this->url_youtube->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->id_idioma->FldIsDetailKey) {
			$this->id_idioma->setFormValue($objForm->GetValue("x_id_idioma"));
		}
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->amigable->FldIsDetailKey) {
			$this->amigable->setFormValue($objForm->GetValue("x_amigable"));
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
		if (!$this->tiendas_pie->FldIsDetailKey) {
			$this->tiendas_pie->setFormValue($objForm->GetValue("x_tiendas_pie"));
		}
		if (!$this->marcas_pie->FldIsDetailKey) {
			$this->marcas_pie->setFormValue($objForm->GetValue("x_marcas_pie"));
		}
		if (!$this->descripcion_form->FldIsDetailKey) {
			$this->descripcion_form->setFormValue($objForm->GetValue("x_descripcion_form"));
		}
		if (!$this->telefono->FldIsDetailKey) {
			$this->telefono->setFormValue($objForm->GetValue("x_telefono"));
		}
		if (!$this->url_facebook->FldIsDetailKey) {
			$this->url_facebook->setFormValue($objForm->GetValue("x_url_facebook"));
		}
		if (!$this->url_twitter->FldIsDetailKey) {
			$this->url_twitter->setFormValue($objForm->GetValue("x_url_twitter"));
		}
		if (!$this->url_youtube->FldIsDetailKey) {
			$this->url_youtube->setFormValue($objForm->GetValue("x_url_youtube"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id_idioma->CurrentValue = $this->id_idioma->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->amigable->CurrentValue = $this->amigable->FormValue;
		$this->titulo1->CurrentValue = $this->titulo1->FormValue;
		$this->descripcion1->CurrentValue = $this->descripcion1->FormValue;
		$this->titulo2->CurrentValue = $this->titulo2->FormValue;
		$this->descripcion2->CurrentValue = $this->descripcion2->FormValue;
		$this->titulo3->CurrentValue = $this->titulo3->FormValue;
		$this->descripcion3->CurrentValue = $this->descripcion3->FormValue;
		$this->tiendas_pie->CurrentValue = $this->tiendas_pie->FormValue;
		$this->marcas_pie->CurrentValue = $this->marcas_pie->FormValue;
		$this->descripcion_form->CurrentValue = $this->descripcion_form->FormValue;
		$this->telefono->CurrentValue = $this->telefono->FormValue;
		$this->url_facebook->CurrentValue = $this->url_facebook->FormValue;
		$this->url_twitter->CurrentValue = $this->url_twitter->FormValue;
		$this->url_youtube->CurrentValue = $this->url_youtube->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_marca")) <> "")
			$this->id_marca->CurrentValue = $this->getKey("id_marca"); // id_marca
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_idioma
			$this->id_idioma->EditCustomAttributes = "";
			if (trim(strval($this->id_idioma->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_idioma`" . ew_SearchString("=", $this->id_idioma->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `id_idioma`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `idioma`";
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

			// nombre
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);
			$this->nombre->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nombre->FldCaption()));

			// amigable
			$this->amigable->EditCustomAttributes = "";
			$this->amigable->EditValue = ew_HtmlEncode($this->amigable->CurrentValue);
			$this->amigable->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->amigable->FldCaption()));

			// logo_url
			$this->logo_url->EditCustomAttributes = "";
			if (!ew_Empty($this->logo_url->Upload->DbValue)) {
				$this->logo_url->EditValue = $this->logo_url->Upload->DbValue;
			} else {
				$this->logo_url->EditValue = "";
			}
			if (!ew_Empty($this->logo_url->CurrentValue))
				$this->logo_url->Upload->FileName = $this->logo_url->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->logo_url);

			// slideshow1_url
			$this->slideshow1_url->EditCustomAttributes = "";
			if (!ew_Empty($this->slideshow1_url->Upload->DbValue)) {
				$this->slideshow1_url->EditValue = $this->slideshow1_url->Upload->DbValue;
			} else {
				$this->slideshow1_url->EditValue = "";
			}
			if (!ew_Empty($this->slideshow1_url->CurrentValue))
				$this->slideshow1_url->Upload->FileName = $this->slideshow1_url->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->slideshow1_url);

			// slideshow2_url
			$this->slideshow2_url->EditCustomAttributes = "";
			if (!ew_Empty($this->slideshow2_url->Upload->DbValue)) {
				$this->slideshow2_url->EditValue = $this->slideshow2_url->Upload->DbValue;
			} else {
				$this->slideshow2_url->EditValue = "";
			}
			if (!ew_Empty($this->slideshow2_url->CurrentValue))
				$this->slideshow2_url->Upload->FileName = $this->slideshow2_url->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->slideshow2_url);

			// slideshow3_url
			$this->slideshow3_url->EditCustomAttributes = "";
			if (!ew_Empty($this->slideshow3_url->Upload->DbValue)) {
				$this->slideshow3_url->EditValue = $this->slideshow3_url->Upload->DbValue;
			} else {
				$this->slideshow3_url->EditValue = "";
			}
			if (!ew_Empty($this->slideshow3_url->CurrentValue))
				$this->slideshow3_url->Upload->FileName = $this->slideshow3_url->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->slideshow3_url);

			// slideshow4_url
			$this->slideshow4_url->EditCustomAttributes = "";
			if (!ew_Empty($this->slideshow4_url->Upload->DbValue)) {
				$this->slideshow4_url->EditValue = $this->slideshow4_url->Upload->DbValue;
			} else {
				$this->slideshow4_url->EditValue = "";
			}
			if (!ew_Empty($this->slideshow4_url->CurrentValue))
				$this->slideshow4_url->Upload->FileName = $this->slideshow4_url->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->slideshow4_url);

			// slideshow5_url
			$this->slideshow5_url->EditCustomAttributes = "";
			if (!ew_Empty($this->slideshow5_url->Upload->DbValue)) {
				$this->slideshow5_url->EditValue = $this->slideshow5_url->Upload->DbValue;
			} else {
				$this->slideshow5_url->EditValue = "";
			}
			if (!ew_Empty($this->slideshow5_url->CurrentValue))
				$this->slideshow5_url->Upload->FileName = $this->slideshow5_url->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->slideshow5_url);

			// tienda1_url
			$this->tienda1_url->EditCustomAttributes = "";
			if (!ew_Empty($this->tienda1_url->Upload->DbValue)) {
				$this->tienda1_url->EditValue = $this->tienda1_url->Upload->DbValue;
			} else {
				$this->tienda1_url->EditValue = "";
			}
			if (!ew_Empty($this->tienda1_url->CurrentValue))
				$this->tienda1_url->Upload->FileName = $this->tienda1_url->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->tienda1_url);

			// tienda2_url
			$this->tienda2_url->EditCustomAttributes = "";
			if (!ew_Empty($this->tienda2_url->Upload->DbValue)) {
				$this->tienda2_url->EditValue = $this->tienda2_url->Upload->DbValue;
			} else {
				$this->tienda2_url->EditValue = "";
			}
			if (!ew_Empty($this->tienda2_url->CurrentValue))
				$this->tienda2_url->Upload->FileName = $this->tienda2_url->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->tienda2_url);

			// tienda3_url
			$this->tienda3_url->EditCustomAttributes = "";
			if (!ew_Empty($this->tienda3_url->Upload->DbValue)) {
				$this->tienda3_url->EditValue = $this->tienda3_url->Upload->DbValue;
			} else {
				$this->tienda3_url->EditValue = "";
			}
			if (!ew_Empty($this->tienda3_url->CurrentValue))
				$this->tienda3_url->Upload->FileName = $this->tienda3_url->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->tienda3_url);

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

			// tiendas_pie
			$this->tiendas_pie->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->tiendas_pie->FldTagValue(1), $this->tiendas_pie->FldTagCaption(1) <> "" ? $this->tiendas_pie->FldTagCaption(1) : $this->tiendas_pie->FldTagValue(1));
			$arwrk[] = array($this->tiendas_pie->FldTagValue(2), $this->tiendas_pie->FldTagCaption(2) <> "" ? $this->tiendas_pie->FldTagCaption(2) : $this->tiendas_pie->FldTagValue(2));
			$this->tiendas_pie->EditValue = $arwrk;

			// marcas_pie
			$this->marcas_pie->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->marcas_pie->FldTagValue(1), $this->marcas_pie->FldTagCaption(1) <> "" ? $this->marcas_pie->FldTagCaption(1) : $this->marcas_pie->FldTagValue(1));
			$arwrk[] = array($this->marcas_pie->FldTagValue(2), $this->marcas_pie->FldTagCaption(2) <> "" ? $this->marcas_pie->FldTagCaption(2) : $this->marcas_pie->FldTagValue(2));
			$this->marcas_pie->EditValue = $arwrk;

			// descripcion_form
			$this->descripcion_form->EditCustomAttributes = "";
			$this->descripcion_form->EditValue = ew_HtmlEncode($this->descripcion_form->CurrentValue);
			$this->descripcion_form->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->descripcion_form->FldCaption()));

			// telefono
			$this->telefono->EditCustomAttributes = "";
			$this->telefono->EditValue = ew_HtmlEncode($this->telefono->CurrentValue);
			$this->telefono->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->telefono->FldCaption()));

			// imagen_url
			$this->imagen_url->EditCustomAttributes = "";
			if (!ew_Empty($this->imagen_url->Upload->DbValue)) {
				$this->imagen_url->EditValue = $this->imagen_url->Upload->DbValue;
			} else {
				$this->imagen_url->EditValue = "";
			}
			if (!ew_Empty($this->imagen_url->CurrentValue))
				$this->imagen_url->Upload->FileName = $this->imagen_url->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->imagen_url);

			// url_facebook
			$this->url_facebook->EditCustomAttributes = "";
			$this->url_facebook->EditValue = $this->url_facebook->CurrentValue;
			$this->url_facebook->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->url_facebook->FldCaption()));

			// url_twitter
			$this->url_twitter->EditCustomAttributes = "";
			$this->url_twitter->EditValue = $this->url_twitter->CurrentValue;
			$this->url_twitter->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->url_twitter->FldCaption()));

			// url_youtube
			$this->url_youtube->EditCustomAttributes = "";
			$this->url_youtube->EditValue = $this->url_youtube->CurrentValue;
			$this->url_youtube->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->url_youtube->FldCaption()));

			// Edit refer script
			// id_idioma

			$this->id_idioma->HrefValue = "";

			// nombre
			$this->nombre->HrefValue = "";

			// amigable
			$this->amigable->HrefValue = "";

			// logo_url
			$this->logo_url->HrefValue = "";
			$this->logo_url->HrefValue2 = $this->logo_url->UploadPath . $this->logo_url->Upload->DbValue;

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

			// tienda1_url
			$this->tienda1_url->HrefValue = "";
			$this->tienda1_url->HrefValue2 = $this->tienda1_url->UploadPath . $this->tienda1_url->Upload->DbValue;

			// tienda2_url
			$this->tienda2_url->HrefValue = "";
			$this->tienda2_url->HrefValue2 = $this->tienda2_url->UploadPath . $this->tienda2_url->Upload->DbValue;

			// tienda3_url
			$this->tienda3_url->HrefValue = "";
			$this->tienda3_url->HrefValue2 = $this->tienda3_url->UploadPath . $this->tienda3_url->Upload->DbValue;

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

			// tiendas_pie
			$this->tiendas_pie->HrefValue = "";

			// marcas_pie
			$this->marcas_pie->HrefValue = "";

			// descripcion_form
			$this->descripcion_form->HrefValue = "";

			// telefono
			$this->telefono->HrefValue = "";

			// imagen_url
			$this->imagen_url->HrefValue = "";
			$this->imagen_url->HrefValue2 = $this->imagen_url->UploadPath . $this->imagen_url->Upload->DbValue;

			// url_facebook
			$this->url_facebook->HrefValue = "";

			// url_twitter
			$this->url_twitter->HrefValue = "";

			// url_youtube
			$this->url_youtube->HrefValue = "";
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
		if (!$this->nombre->FldIsDetailKey && !is_null($this->nombre->FormValue) && $this->nombre->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nombre->FldCaption());
		}
		if (!$this->amigable->FldIsDetailKey && !is_null($this->amigable->FormValue) && $this->amigable->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->amigable->FldCaption());
		}
		if (is_null($this->logo_url->Upload->Value)) {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->logo_url->FldCaption());
		}
		if (is_null($this->tienda1_url->Upload->Value)) {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->tienda1_url->FldCaption());
		}
		if (is_null($this->tienda2_url->Upload->Value)) {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->tienda2_url->FldCaption());
		}
		if (is_null($this->tienda3_url->Upload->Value)) {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->tienda3_url->FldCaption());
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
		if (!$this->descripcion_form->FldIsDetailKey && !is_null($this->descripcion_form->FormValue) && $this->descripcion_form->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->descripcion_form->FldCaption());
		}
		if (!$this->telefono->FldIsDetailKey && !is_null($this->telefono->FormValue) && $this->telefono->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->telefono->FldCaption());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// id_idioma
		$this->id_idioma->SetDbValueDef($rsnew, $this->id_idioma->CurrentValue, 0, FALSE);

		// nombre
		$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, NULL, FALSE);

		// amigable
		$this->amigable->SetDbValueDef($rsnew, $this->amigable->CurrentValue, NULL, FALSE);

		// logo_url
		if (!$this->logo_url->Upload->KeepFile) {
			if ($this->logo_url->Upload->FileName == "") {
				$rsnew['logo_url'] = NULL;
			} else {
				$rsnew['logo_url'] = $this->logo_url->Upload->FileName;
			}
			$this->logo_url->ImageWidth = 373; // Resize width
			$this->logo_url->ImageHeight = 175; // Resize height
		}

		// slideshow1_url
		if (!$this->slideshow1_url->Upload->KeepFile) {
			if ($this->slideshow1_url->Upload->FileName == "") {
				$rsnew['slideshow1_url'] = NULL;
			} else {
				$rsnew['slideshow1_url'] = $this->slideshow1_url->Upload->FileName;
			}
			$this->slideshow1_url->ImageWidth = 1000; // Resize width
			$this->slideshow1_url->ImageHeight = 611; // Resize height
		}

		// slideshow2_url
		if (!$this->slideshow2_url->Upload->KeepFile) {
			if ($this->slideshow2_url->Upload->FileName == "") {
				$rsnew['slideshow2_url'] = NULL;
			} else {
				$rsnew['slideshow2_url'] = $this->slideshow2_url->Upload->FileName;
			}
			$this->slideshow2_url->ImageWidth = 1000; // Resize width
			$this->slideshow2_url->ImageHeight = 611; // Resize height
		}

		// slideshow3_url
		if (!$this->slideshow3_url->Upload->KeepFile) {
			if ($this->slideshow3_url->Upload->FileName == "") {
				$rsnew['slideshow3_url'] = NULL;
			} else {
				$rsnew['slideshow3_url'] = $this->slideshow3_url->Upload->FileName;
			}
			$this->slideshow3_url->ImageWidth = 1000; // Resize width
			$this->slideshow3_url->ImageHeight = 611; // Resize height
		}

		// slideshow4_url
		if (!$this->slideshow4_url->Upload->KeepFile) {
			if ($this->slideshow4_url->Upload->FileName == "") {
				$rsnew['slideshow4_url'] = NULL;
			} else {
				$rsnew['slideshow4_url'] = $this->slideshow4_url->Upload->FileName;
			}
			$this->slideshow4_url->ImageWidth = 1000; // Resize width
			$this->slideshow4_url->ImageHeight = 611; // Resize height
		}

		// slideshow5_url
		if (!$this->slideshow5_url->Upload->KeepFile) {
			if ($this->slideshow5_url->Upload->FileName == "") {
				$rsnew['slideshow5_url'] = NULL;
			} else {
				$rsnew['slideshow5_url'] = $this->slideshow5_url->Upload->FileName;
			}
			$this->slideshow5_url->ImageWidth = 1000; // Resize width
			$this->slideshow5_url->ImageHeight = 611; // Resize height
		}

		// tienda1_url
		if (!$this->tienda1_url->Upload->KeepFile) {
			if ($this->tienda1_url->Upload->FileName == "") {
				$rsnew['tienda1_url'] = NULL;
			} else {
				$rsnew['tienda1_url'] = $this->tienda1_url->Upload->FileName;
			}
			$this->tienda1_url->ImageWidth = 209; // Resize width
			$this->tienda1_url->ImageHeight = 175; // Resize height
		}

		// tienda2_url
		if (!$this->tienda2_url->Upload->KeepFile) {
			if ($this->tienda2_url->Upload->FileName == "") {
				$rsnew['tienda2_url'] = NULL;
			} else {
				$rsnew['tienda2_url'] = $this->tienda2_url->Upload->FileName;
			}
			$this->tienda2_url->ImageWidth = 209; // Resize width
			$this->tienda2_url->ImageHeight = 175; // Resize height
		}

		// tienda3_url
		if (!$this->tienda3_url->Upload->KeepFile) {
			if ($this->tienda3_url->Upload->FileName == "") {
				$rsnew['tienda3_url'] = NULL;
			} else {
				$rsnew['tienda3_url'] = $this->tienda3_url->Upload->FileName;
			}
			$this->tienda3_url->ImageWidth = 209; // Resize width
			$this->tienda3_url->ImageHeight = 175; // Resize height
		}

		// titulo1
		$this->titulo1->SetDbValueDef($rsnew, $this->titulo1->CurrentValue, NULL, FALSE);

		// descripcion1
		$this->descripcion1->SetDbValueDef($rsnew, $this->descripcion1->CurrentValue, NULL, FALSE);

		// titulo2
		$this->titulo2->SetDbValueDef($rsnew, $this->titulo2->CurrentValue, NULL, FALSE);

		// descripcion2
		$this->descripcion2->SetDbValueDef($rsnew, $this->descripcion2->CurrentValue, NULL, FALSE);

		// titulo3
		$this->titulo3->SetDbValueDef($rsnew, $this->titulo3->CurrentValue, NULL, FALSE);

		// descripcion3
		$this->descripcion3->SetDbValueDef($rsnew, $this->descripcion3->CurrentValue, NULL, FALSE);

		// tiendas_pie
		$this->tiendas_pie->SetDbValueDef($rsnew, $this->tiendas_pie->CurrentValue, NULL, FALSE);

		// marcas_pie
		$this->marcas_pie->SetDbValueDef($rsnew, $this->marcas_pie->CurrentValue, NULL, FALSE);

		// descripcion_form
		$this->descripcion_form->SetDbValueDef($rsnew, $this->descripcion_form->CurrentValue, NULL, FALSE);

		// telefono
		$this->telefono->SetDbValueDef($rsnew, $this->telefono->CurrentValue, NULL, FALSE);

		// imagen_url
		if (!$this->imagen_url->Upload->KeepFile) {
			if ($this->imagen_url->Upload->FileName == "") {
				$rsnew['imagen_url'] = NULL;
			} else {
				$rsnew['imagen_url'] = $this->imagen_url->Upload->FileName;
			}
		}

		// url_facebook
		$this->url_facebook->SetDbValueDef($rsnew, $this->url_facebook->CurrentValue, NULL, FALSE);

		// url_twitter
		$this->url_twitter->SetDbValueDef($rsnew, $this->url_twitter->CurrentValue, NULL, FALSE);

		// url_youtube
		$this->url_youtube->SetDbValueDef($rsnew, $this->url_youtube->CurrentValue, NULL, FALSE);
		if (!$this->logo_url->Upload->KeepFile) {
			if (!ew_Empty($this->logo_url->Upload->Value)) {
				$rsnew['logo_url'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->logo_url->UploadPath), $rsnew['logo_url']); // Get new file name
			}
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
		if (!$this->tienda1_url->Upload->KeepFile) {
			if (!ew_Empty($this->tienda1_url->Upload->Value)) {
				$rsnew['tienda1_url'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->tienda1_url->UploadPath), $rsnew['tienda1_url']); // Get new file name
			}
		}
		if (!$this->tienda2_url->Upload->KeepFile) {
			if (!ew_Empty($this->tienda2_url->Upload->Value)) {
				$rsnew['tienda2_url'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->tienda2_url->UploadPath), $rsnew['tienda2_url']); // Get new file name
			}
		}
		if (!$this->tienda3_url->Upload->KeepFile) {
			if (!ew_Empty($this->tienda3_url->Upload->Value)) {
				$rsnew['tienda3_url'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->tienda3_url->UploadPath), $rsnew['tienda3_url']); // Get new file name
			}
		}
		if (!$this->imagen_url->Upload->KeepFile) {
			if (!ew_Empty($this->imagen_url->Upload->Value)) {
				$rsnew['imagen_url'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->imagen_url->UploadPath), $rsnew['imagen_url']); // Get new file name
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if (!$this->logo_url->Upload->KeepFile) {
					if (!ew_Empty($this->logo_url->Upload->Value)) {
						$this->logo_url->Upload->Resize($this->logo_url->ImageWidth, $this->logo_url->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY);
						$this->logo_url->Upload->SaveToFile($this->logo_url->UploadPath, $rsnew['logo_url'], TRUE);
					}
				}
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
				if (!$this->tienda1_url->Upload->KeepFile) {
					if (!ew_Empty($this->tienda1_url->Upload->Value)) {
						$this->tienda1_url->Upload->Resize($this->tienda1_url->ImageWidth, $this->tienda1_url->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY);
						$this->tienda1_url->Upload->SaveToFile($this->tienda1_url->UploadPath, $rsnew['tienda1_url'], TRUE);
					}
				}
				if (!$this->tienda2_url->Upload->KeepFile) {
					if (!ew_Empty($this->tienda2_url->Upload->Value)) {
						$this->tienda2_url->Upload->Resize($this->tienda2_url->ImageWidth, $this->tienda2_url->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY);
						$this->tienda2_url->Upload->SaveToFile($this->tienda2_url->UploadPath, $rsnew['tienda2_url'], TRUE);
					}
				}
				if (!$this->tienda3_url->Upload->KeepFile) {
					if (!ew_Empty($this->tienda3_url->Upload->Value)) {
						$this->tienda3_url->Upload->Resize($this->tienda3_url->ImageWidth, $this->tienda3_url->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY);
						$this->tienda3_url->Upload->SaveToFile($this->tienda3_url->UploadPath, $rsnew['tienda3_url'], TRUE);
					}
				}
				if (!$this->imagen_url->Upload->KeepFile) {
					if (!ew_Empty($this->imagen_url->Upload->Value)) {
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
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->id_marca->setDbValue($conn->Insert_ID());
			$rsnew['id_marca'] = $this->id_marca->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// logo_url
		ew_CleanUploadTempPath($this->logo_url, $this->logo_url->Upload->Index);

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

		// tienda1_url
		ew_CleanUploadTempPath($this->tienda1_url, $this->tienda1_url->Upload->Index);

		// tienda2_url
		ew_CleanUploadTempPath($this->tienda2_url, $this->tienda2_url->Upload->Index);

		// tienda3_url
		ew_CleanUploadTempPath($this->tienda3_url, $this->tienda3_url->Upload->Index);

		// imagen_url
		ew_CleanUploadTempPath($this->imagen_url, $this->imagen_url->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "marcaslist.php", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, ew_CurrentUrl());
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
if (!isset($marcas_add)) $marcas_add = new cmarcas_add();

// Page init
$marcas_add->Page_Init();

// Page main
$marcas_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$marcas_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var marcas_add = new ew_Page("marcas_add");
marcas_add.PageID = "add"; // Page ID
var EW_PAGE_ID = marcas_add.PageID; // For backward compatibility

// Form object
var fmarcasadd = new ew_Form("fmarcasadd");

// Validate form
fmarcasadd.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($marcas->id_idioma->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($marcas->nombre->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_amigable");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($marcas->amigable->FldCaption()) ?>");
			felm = this.GetElements("x" + infix + "_logo_url");
			elm = this.GetElements("fn_x" + infix + "_logo_url");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($marcas->logo_url->FldCaption()) ?>");
			felm = this.GetElements("x" + infix + "_tienda1_url");
			elm = this.GetElements("fn_x" + infix + "_tienda1_url");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($marcas->tienda1_url->FldCaption()) ?>");
			felm = this.GetElements("x" + infix + "_tienda2_url");
			elm = this.GetElements("fn_x" + infix + "_tienda2_url");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($marcas->tienda2_url->FldCaption()) ?>");
			felm = this.GetElements("x" + infix + "_tienda3_url");
			elm = this.GetElements("fn_x" + infix + "_tienda3_url");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($marcas->tienda3_url->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_titulo1");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($marcas->titulo1->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_descripcion1");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($marcas->descripcion1->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_titulo2");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($marcas->titulo2->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_descripcion2");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($marcas->descripcion2->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_titulo3");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($marcas->titulo3->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_descripcion3");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($marcas->descripcion3->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_descripcion_form");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($marcas->descripcion_form->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_telefono");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($marcas->telefono->FldCaption()) ?>");

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
fmarcasadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmarcasadd.ValidateRequired = true;
<?php } else { ?>
fmarcasadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmarcasadd.Lists["x_id_idioma"] = {"LinkField":"x_id_idioma","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">
$( document ).ready(function() {    
	jQuery("#x_amigable").attr("disabled","disable");
	jQuery("#x_nombre").change(function(){
		var x=jQuery(this).val();

		//console.log(x);   
		x=x.toLowerCase(); 

		//console.log(x);  
		x=x.replace(/(["·~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, '-');
		x=x.replace(/^(-)+|(-)+$/g,'');

		//console.log(x);  
		jQuery("#x_amigable").val(x);   
	});     
	jQuery("#btnAction").click(function(){
		 jQuery("#x_amigable").removeAttr("disabled");        
	});  
});                      
</script>
<?php $Breadcrumb->Render(); ?>
<?php $marcas_add->ShowPageHeader(); ?>
<?php
$marcas_add->ShowMessage();
?>
<form name="fmarcasadd" id="fmarcasadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="marcas">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewGrid"><tr><td>
<table id="tbl_marcasadd" class="table table-bordered table-striped">
<?php if ($marcas->id_idioma->Visible) { // id_idioma ?>
	<tr id="r_id_idioma">
		<td><span id="elh_marcas_id_idioma"><?php echo $marcas->id_idioma->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $marcas->id_idioma->CellAttributes() ?>>
<span id="el_marcas_id_idioma" class="control-group">
<select data-field="x_id_idioma" id="x_id_idioma" name="x_id_idioma"<?php echo $marcas->id_idioma->EditAttributes() ?>>
<?php
if (is_array($marcas->id_idioma->EditValue)) {
	$arwrk = $marcas->id_idioma->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($marcas->id_idioma->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT `id_idioma`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `idioma`";
$sWhereWrk = "";

// Call Lookup selecting
$marcas->Lookup_Selecting($marcas->id_idioma, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_id_idioma" id="s_x_id_idioma" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id_idioma` = {filter_value}"); ?>&amp;t0=18">
</span>
<?php echo $marcas->id_idioma->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->nombre->Visible) { // nombre ?>
	<tr id="r_nombre">
		<td><span id="elh_marcas_nombre"><?php echo $marcas->nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $marcas->nombre->CellAttributes() ?>>
<span id="el_marcas_nombre" class="control-group">
<input type="text" data-field="x_nombre" name="x_nombre" id="x_nombre" placeholder="<?php echo $marcas->nombre->PlaceHolder ?>" value="<?php echo $marcas->nombre->EditValue ?>"<?php echo $marcas->nombre->EditAttributes() ?>>
</span>
<?php echo $marcas->nombre->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->amigable->Visible) { // amigable ?>
	<tr id="r_amigable">
		<td><span id="elh_marcas_amigable"><?php echo $marcas->amigable->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $marcas->amigable->CellAttributes() ?>>
<span id="el_marcas_amigable" class="control-group">
<input type="text" data-field="x_amigable" name="x_amigable" id="x_amigable" placeholder="<?php echo $marcas->amigable->PlaceHolder ?>" value="<?php echo $marcas->amigable->EditValue ?>"<?php echo $marcas->amigable->EditAttributes() ?>>
</span>
<?php echo $marcas->amigable->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->logo_url->Visible) { // logo_url ?>
	<tr id="r_logo_url">
		<td><span id="elh_marcas_logo_url"><?php echo $marcas->logo_url->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $marcas->logo_url->CellAttributes() ?>>
<div id="el_marcas_logo_url" class="control-group">
<span id="fd_x_logo_url">
<span class="btn btn-small fileinput-button"<?php if ($marcas->logo_url->ReadOnly || $marcas->logo_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_logo_url" name="x_logo_url" id="x_logo_url">
</span>
<input type="hidden" name="fn_x_logo_url" id= "fn_x_logo_url" value="<?php echo $marcas->logo_url->Upload->FileName ?>">
<input type="hidden" name="fa_x_logo_url" id= "fa_x_logo_url" value="0">
<input type="hidden" name="fs_x_logo_url" id= "fs_x_logo_url" value="256">
</span>
<table id="ft_x_logo_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $marcas->logo_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->slideshow1_url->Visible) { // slideshow1_url ?>
	<tr id="r_slideshow1_url">
		<td><span id="elh_marcas_slideshow1_url"><?php echo $marcas->slideshow1_url->FldCaption() ?></span></td>
		<td<?php echo $marcas->slideshow1_url->CellAttributes() ?>>
<div id="el_marcas_slideshow1_url" class="control-group">
<span id="fd_x_slideshow1_url">
<span class="btn btn-small fileinput-button"<?php if ($marcas->slideshow1_url->ReadOnly || $marcas->slideshow1_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_slideshow1_url" name="x_slideshow1_url" id="x_slideshow1_url">
</span>
<input type="hidden" name="fn_x_slideshow1_url" id= "fn_x_slideshow1_url" value="<?php echo $marcas->slideshow1_url->Upload->FileName ?>">
<input type="hidden" name="fa_x_slideshow1_url" id= "fa_x_slideshow1_url" value="0">
<input type="hidden" name="fs_x_slideshow1_url" id= "fs_x_slideshow1_url" value="256">
</span>
<table id="ft_x_slideshow1_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $marcas->slideshow1_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->slideshow2_url->Visible) { // slideshow2_url ?>
	<tr id="r_slideshow2_url">
		<td><span id="elh_marcas_slideshow2_url"><?php echo $marcas->slideshow2_url->FldCaption() ?></span></td>
		<td<?php echo $marcas->slideshow2_url->CellAttributes() ?>>
<div id="el_marcas_slideshow2_url" class="control-group">
<span id="fd_x_slideshow2_url">
<span class="btn btn-small fileinput-button"<?php if ($marcas->slideshow2_url->ReadOnly || $marcas->slideshow2_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_slideshow2_url" name="x_slideshow2_url" id="x_slideshow2_url">
</span>
<input type="hidden" name="fn_x_slideshow2_url" id= "fn_x_slideshow2_url" value="<?php echo $marcas->slideshow2_url->Upload->FileName ?>">
<input type="hidden" name="fa_x_slideshow2_url" id= "fa_x_slideshow2_url" value="0">
<input type="hidden" name="fs_x_slideshow2_url" id= "fs_x_slideshow2_url" value="256">
</span>
<table id="ft_x_slideshow2_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $marcas->slideshow2_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->slideshow3_url->Visible) { // slideshow3_url ?>
	<tr id="r_slideshow3_url">
		<td><span id="elh_marcas_slideshow3_url"><?php echo $marcas->slideshow3_url->FldCaption() ?></span></td>
		<td<?php echo $marcas->slideshow3_url->CellAttributes() ?>>
<div id="el_marcas_slideshow3_url" class="control-group">
<span id="fd_x_slideshow3_url">
<span class="btn btn-small fileinput-button"<?php if ($marcas->slideshow3_url->ReadOnly || $marcas->slideshow3_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_slideshow3_url" name="x_slideshow3_url" id="x_slideshow3_url">
</span>
<input type="hidden" name="fn_x_slideshow3_url" id= "fn_x_slideshow3_url" value="<?php echo $marcas->slideshow3_url->Upload->FileName ?>">
<input type="hidden" name="fa_x_slideshow3_url" id= "fa_x_slideshow3_url" value="0">
<input type="hidden" name="fs_x_slideshow3_url" id= "fs_x_slideshow3_url" value="256">
</span>
<table id="ft_x_slideshow3_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $marcas->slideshow3_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->slideshow4_url->Visible) { // slideshow4_url ?>
	<tr id="r_slideshow4_url">
		<td><span id="elh_marcas_slideshow4_url"><?php echo $marcas->slideshow4_url->FldCaption() ?></span></td>
		<td<?php echo $marcas->slideshow4_url->CellAttributes() ?>>
<div id="el_marcas_slideshow4_url" class="control-group">
<span id="fd_x_slideshow4_url">
<span class="btn btn-small fileinput-button"<?php if ($marcas->slideshow4_url->ReadOnly || $marcas->slideshow4_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_slideshow4_url" name="x_slideshow4_url" id="x_slideshow4_url">
</span>
<input type="hidden" name="fn_x_slideshow4_url" id= "fn_x_slideshow4_url" value="<?php echo $marcas->slideshow4_url->Upload->FileName ?>">
<input type="hidden" name="fa_x_slideshow4_url" id= "fa_x_slideshow4_url" value="0">
<input type="hidden" name="fs_x_slideshow4_url" id= "fs_x_slideshow4_url" value="256">
</span>
<table id="ft_x_slideshow4_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $marcas->slideshow4_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->slideshow5_url->Visible) { // slideshow5_url ?>
	<tr id="r_slideshow5_url">
		<td><span id="elh_marcas_slideshow5_url"><?php echo $marcas->slideshow5_url->FldCaption() ?></span></td>
		<td<?php echo $marcas->slideshow5_url->CellAttributes() ?>>
<div id="el_marcas_slideshow5_url" class="control-group">
<span id="fd_x_slideshow5_url">
<span class="btn btn-small fileinput-button"<?php if ($marcas->slideshow5_url->ReadOnly || $marcas->slideshow5_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_slideshow5_url" name="x_slideshow5_url" id="x_slideshow5_url">
</span>
<input type="hidden" name="fn_x_slideshow5_url" id= "fn_x_slideshow5_url" value="<?php echo $marcas->slideshow5_url->Upload->FileName ?>">
<input type="hidden" name="fa_x_slideshow5_url" id= "fa_x_slideshow5_url" value="0">
<input type="hidden" name="fs_x_slideshow5_url" id= "fs_x_slideshow5_url" value="256">
</span>
<table id="ft_x_slideshow5_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $marcas->slideshow5_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->tienda1_url->Visible) { // tienda1_url ?>
	<tr id="r_tienda1_url">
		<td><span id="elh_marcas_tienda1_url"><?php echo $marcas->tienda1_url->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $marcas->tienda1_url->CellAttributes() ?>>
<div id="el_marcas_tienda1_url" class="control-group">
<span id="fd_x_tienda1_url">
<span class="btn btn-small fileinput-button"<?php if ($marcas->tienda1_url->ReadOnly || $marcas->tienda1_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_tienda1_url" name="x_tienda1_url" id="x_tienda1_url">
</span>
<input type="hidden" name="fn_x_tienda1_url" id= "fn_x_tienda1_url" value="<?php echo $marcas->tienda1_url->Upload->FileName ?>">
<input type="hidden" name="fa_x_tienda1_url" id= "fa_x_tienda1_url" value="0">
<input type="hidden" name="fs_x_tienda1_url" id= "fs_x_tienda1_url" value="256">
</span>
<table id="ft_x_tienda1_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $marcas->tienda1_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->tienda2_url->Visible) { // tienda2_url ?>
	<tr id="r_tienda2_url">
		<td><span id="elh_marcas_tienda2_url"><?php echo $marcas->tienda2_url->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $marcas->tienda2_url->CellAttributes() ?>>
<div id="el_marcas_tienda2_url" class="control-group">
<span id="fd_x_tienda2_url">
<span class="btn btn-small fileinput-button"<?php if ($marcas->tienda2_url->ReadOnly || $marcas->tienda2_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_tienda2_url" name="x_tienda2_url" id="x_tienda2_url">
</span>
<input type="hidden" name="fn_x_tienda2_url" id= "fn_x_tienda2_url" value="<?php echo $marcas->tienda2_url->Upload->FileName ?>">
<input type="hidden" name="fa_x_tienda2_url" id= "fa_x_tienda2_url" value="0">
<input type="hidden" name="fs_x_tienda2_url" id= "fs_x_tienda2_url" value="256">
</span>
<table id="ft_x_tienda2_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $marcas->tienda2_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->tienda3_url->Visible) { // tienda3_url ?>
	<tr id="r_tienda3_url">
		<td><span id="elh_marcas_tienda3_url"><?php echo $marcas->tienda3_url->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $marcas->tienda3_url->CellAttributes() ?>>
<div id="el_marcas_tienda3_url" class="control-group">
<span id="fd_x_tienda3_url">
<span class="btn btn-small fileinput-button"<?php if ($marcas->tienda3_url->ReadOnly || $marcas->tienda3_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_tienda3_url" name="x_tienda3_url" id="x_tienda3_url">
</span>
<input type="hidden" name="fn_x_tienda3_url" id= "fn_x_tienda3_url" value="<?php echo $marcas->tienda3_url->Upload->FileName ?>">
<input type="hidden" name="fa_x_tienda3_url" id= "fa_x_tienda3_url" value="0">
<input type="hidden" name="fs_x_tienda3_url" id= "fs_x_tienda3_url" value="256">
</span>
<table id="ft_x_tienda3_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $marcas->tienda3_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->titulo1->Visible) { // titulo1 ?>
	<tr id="r_titulo1">
		<td><span id="elh_marcas_titulo1"><?php echo $marcas->titulo1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $marcas->titulo1->CellAttributes() ?>>
<span id="el_marcas_titulo1" class="control-group">
<input type="text" data-field="x_titulo1" name="x_titulo1" id="x_titulo1" size="30" maxlength="15" placeholder="<?php echo $marcas->titulo1->PlaceHolder ?>" value="<?php echo $marcas->titulo1->EditValue ?>"<?php echo $marcas->titulo1->EditAttributes() ?>>
</span>
<?php echo $marcas->titulo1->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->descripcion1->Visible) { // descripcion1 ?>
	<tr id="r_descripcion1">
		<td><span id="elh_marcas_descripcion1"><?php echo $marcas->descripcion1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $marcas->descripcion1->CellAttributes() ?>>
<span id="el_marcas_descripcion1" class="control-group">
<textarea data-field="x_descripcion1" class="editor" name="x_descripcion1" id="x_descripcion1" cols="35" rows="4" placeholder="<?php echo $marcas->descripcion1->PlaceHolder ?>"<?php echo $marcas->descripcion1->EditAttributes() ?>><?php echo $marcas->descripcion1->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fmarcasadd", "x_descripcion1", 35, 4, <?php echo ($marcas->descripcion1->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $marcas->descripcion1->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->titulo2->Visible) { // titulo2 ?>
	<tr id="r_titulo2">
		<td><span id="elh_marcas_titulo2"><?php echo $marcas->titulo2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $marcas->titulo2->CellAttributes() ?>>
<span id="el_marcas_titulo2" class="control-group">
<input type="text" data-field="x_titulo2" name="x_titulo2" id="x_titulo2" size="30" maxlength="15" placeholder="<?php echo $marcas->titulo2->PlaceHolder ?>" value="<?php echo $marcas->titulo2->EditValue ?>"<?php echo $marcas->titulo2->EditAttributes() ?>>
</span>
<?php echo $marcas->titulo2->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->descripcion2->Visible) { // descripcion2 ?>
	<tr id="r_descripcion2">
		<td><span id="elh_marcas_descripcion2"><?php echo $marcas->descripcion2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $marcas->descripcion2->CellAttributes() ?>>
<span id="el_marcas_descripcion2" class="control-group">
<textarea data-field="x_descripcion2" class="editor" name="x_descripcion2" id="x_descripcion2" cols="35" rows="4" placeholder="<?php echo $marcas->descripcion2->PlaceHolder ?>"<?php echo $marcas->descripcion2->EditAttributes() ?>><?php echo $marcas->descripcion2->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fmarcasadd", "x_descripcion2", 35, 4, <?php echo ($marcas->descripcion2->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $marcas->descripcion2->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->titulo3->Visible) { // titulo3 ?>
	<tr id="r_titulo3">
		<td><span id="elh_marcas_titulo3"><?php echo $marcas->titulo3->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $marcas->titulo3->CellAttributes() ?>>
<span id="el_marcas_titulo3" class="control-group">
<input type="text" data-field="x_titulo3" name="x_titulo3" id="x_titulo3" size="30" maxlength="15" placeholder="<?php echo $marcas->titulo3->PlaceHolder ?>" value="<?php echo $marcas->titulo3->EditValue ?>"<?php echo $marcas->titulo3->EditAttributes() ?>>
</span>
<?php echo $marcas->titulo3->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->descripcion3->Visible) { // descripcion3 ?>
	<tr id="r_descripcion3">
		<td><span id="elh_marcas_descripcion3"><?php echo $marcas->descripcion3->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $marcas->descripcion3->CellAttributes() ?>>
<span id="el_marcas_descripcion3" class="control-group">
<textarea data-field="x_descripcion3" class="editor" name="x_descripcion3" id="x_descripcion3" cols="35" rows="4" placeholder="<?php echo $marcas->descripcion3->PlaceHolder ?>"<?php echo $marcas->descripcion3->EditAttributes() ?>><?php echo $marcas->descripcion3->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fmarcasadd", "x_descripcion3", 35, 4, <?php echo ($marcas->descripcion3->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $marcas->descripcion3->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->tiendas_pie->Visible) { // tiendas_pie ?>
	<tr id="r_tiendas_pie">
		<td><span id="elh_marcas_tiendas_pie"><?php echo $marcas->tiendas_pie->FldCaption() ?></span></td>
		<td<?php echo $marcas->tiendas_pie->CellAttributes() ?>>
<span id="el_marcas_tiendas_pie" class="control-group">
<div id="tp_x_tiendas_pie" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_tiendas_pie" id="x_tiendas_pie" value="{value}"<?php echo $marcas->tiendas_pie->EditAttributes() ?>></div>
<div id="dsl_x_tiendas_pie" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $marcas->tiendas_pie->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($marcas->tiendas_pie->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_tiendas_pie" name="x_tiendas_pie" id="x_tiendas_pie_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $marcas->tiendas_pie->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $marcas->tiendas_pie->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->marcas_pie->Visible) { // marcas_pie ?>
	<tr id="r_marcas_pie">
		<td><span id="elh_marcas_marcas_pie"><?php echo $marcas->marcas_pie->FldCaption() ?></span></td>
		<td<?php echo $marcas->marcas_pie->CellAttributes() ?>>
<span id="el_marcas_marcas_pie" class="control-group">
<div id="tp_x_marcas_pie" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_marcas_pie" id="x_marcas_pie" value="{value}"<?php echo $marcas->marcas_pie->EditAttributes() ?>></div>
<div id="dsl_x_marcas_pie" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $marcas->marcas_pie->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($marcas->marcas_pie->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_marcas_pie" name="x_marcas_pie" id="x_marcas_pie_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $marcas->marcas_pie->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $marcas->marcas_pie->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->descripcion_form->Visible) { // descripcion_form ?>
	<tr id="r_descripcion_form">
		<td><span id="elh_marcas_descripcion_form"><?php echo $marcas->descripcion_form->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $marcas->descripcion_form->CellAttributes() ?>>
<span id="el_marcas_descripcion_form" class="control-group">
<input type="text" data-field="x_descripcion_form" name="x_descripcion_form" id="x_descripcion_form" size="30" maxlength="60" placeholder="<?php echo $marcas->descripcion_form->PlaceHolder ?>" value="<?php echo $marcas->descripcion_form->EditValue ?>"<?php echo $marcas->descripcion_form->EditAttributes() ?>>
</span>
<?php echo $marcas->descripcion_form->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->telefono->Visible) { // telefono ?>
	<tr id="r_telefono">
		<td><span id="elh_marcas_telefono"><?php echo $marcas->telefono->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $marcas->telefono->CellAttributes() ?>>
<span id="el_marcas_telefono" class="control-group">
<input type="text" data-field="x_telefono" name="x_telefono" id="x_telefono" size="30" maxlength="30" placeholder="<?php echo $marcas->telefono->PlaceHolder ?>" value="<?php echo $marcas->telefono->EditValue ?>"<?php echo $marcas->telefono->EditAttributes() ?>>
</span>
<?php echo $marcas->telefono->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->imagen_url->Visible) { // imagen_url ?>
	<tr id="r_imagen_url">
		<td><span id="elh_marcas_imagen_url"><?php echo $marcas->imagen_url->FldCaption() ?></span></td>
		<td<?php echo $marcas->imagen_url->CellAttributes() ?>>
<div id="el_marcas_imagen_url" class="control-group">
<span id="fd_x_imagen_url">
<span class="btn btn-small fileinput-button"<?php if ($marcas->imagen_url->ReadOnly || $marcas->imagen_url->Disabled) echo " style=\"display: none;\""; ?>>
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_imagen_url" name="x_imagen_url" id="x_imagen_url">
</span>
<input type="hidden" name="fn_x_imagen_url" id= "fn_x_imagen_url" value="<?php echo $marcas->imagen_url->Upload->FileName ?>">
<input type="hidden" name="fa_x_imagen_url" id= "fa_x_imagen_url" value="0">
<input type="hidden" name="fs_x_imagen_url" id= "fs_x_imagen_url" value="256">
</span>
<table id="ft_x_imagen_url" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
<?php echo $marcas->imagen_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->url_facebook->Visible) { // url_facebook ?>
	<tr id="r_url_facebook">
		<td><span id="elh_marcas_url_facebook"><?php echo $marcas->url_facebook->FldCaption() ?></span></td>
		<td<?php echo $marcas->url_facebook->CellAttributes() ?>>
<span id="el_marcas_url_facebook" class="control-group">
<textarea data-field="x_url_facebook" name="x_url_facebook" id="x_url_facebook" cols="35" rows="4" placeholder="<?php echo $marcas->url_facebook->PlaceHolder ?>"<?php echo $marcas->url_facebook->EditAttributes() ?>><?php echo $marcas->url_facebook->EditValue ?></textarea>
</span>
<?php echo $marcas->url_facebook->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->url_twitter->Visible) { // url_twitter ?>
	<tr id="r_url_twitter">
		<td><span id="elh_marcas_url_twitter"><?php echo $marcas->url_twitter->FldCaption() ?></span></td>
		<td<?php echo $marcas->url_twitter->CellAttributes() ?>>
<span id="el_marcas_url_twitter" class="control-group">
<textarea data-field="x_url_twitter" name="x_url_twitter" id="x_url_twitter" cols="35" rows="4" placeholder="<?php echo $marcas->url_twitter->PlaceHolder ?>"<?php echo $marcas->url_twitter->EditAttributes() ?>><?php echo $marcas->url_twitter->EditValue ?></textarea>
</span>
<?php echo $marcas->url_twitter->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($marcas->url_youtube->Visible) { // url_youtube ?>
	<tr id="r_url_youtube">
		<td><span id="elh_marcas_url_youtube"><?php echo $marcas->url_youtube->FldCaption() ?></span></td>
		<td<?php echo $marcas->url_youtube->CellAttributes() ?>>
<span id="el_marcas_url_youtube" class="control-group">
<textarea data-field="x_url_youtube" name="x_url_youtube" id="x_url_youtube" cols="35" rows="4" placeholder="<?php echo $marcas->url_youtube->PlaceHolder ?>"<?php echo $marcas->url_youtube->EditAttributes() ?>><?php echo $marcas->url_youtube->EditValue ?></textarea>
</span>
<?php echo $marcas->url_youtube->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fmarcasadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$marcas_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$marcas_add->Page_Terminate();
?>
