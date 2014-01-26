<?php

// Global variable for table object
$nosotros = NULL;

//
// Table class for nosotros
//
class cnosotros extends cTable {
	var $id_nosotros;
	var $id_idioma;
	var $imagen1_url;
	var $imagen2_url;
	var $titulo1;
	var $descripcion1;
	var $titulo2;
	var $descripcion2;
	var $titulo3;
	var $descripcion3;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'nosotros';
		$this->TableName = 'nosotros';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id_nosotros
		$this->id_nosotros = new cField('nosotros', 'nosotros', 'x_id_nosotros', 'id_nosotros', '`id_nosotros`', '`id_nosotros`', 18, -1, FALSE, '`id_nosotros`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_nosotros->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_nosotros'] = &$this->id_nosotros;

		// id_idioma
		$this->id_idioma = new cField('nosotros', 'nosotros', 'x_id_idioma', 'id_idioma', '`id_idioma`', '`id_idioma`', 18, -1, FALSE, '`id_idioma`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_idioma->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_idioma'] = &$this->id_idioma;

		// imagen1_url
		$this->imagen1_url = new cField('nosotros', 'nosotros', 'x_imagen1_url', 'imagen1_url', '`imagen1_url`', '`imagen1_url`', 201, -1, TRUE, '`imagen1_url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['imagen1_url'] = &$this->imagen1_url;

		// imagen2_url
		$this->imagen2_url = new cField('nosotros', 'nosotros', 'x_imagen2_url', 'imagen2_url', '`imagen2_url`', '`imagen2_url`', 201, -1, TRUE, '`imagen2_url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['imagen2_url'] = &$this->imagen2_url;

		// titulo1
		$this->titulo1 = new cField('nosotros', 'nosotros', 'x_titulo1', 'titulo1', '`titulo1`', '`titulo1`', 200, -1, FALSE, '`titulo1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['titulo1'] = &$this->titulo1;

		// descripcion1
		$this->descripcion1 = new cField('nosotros', 'nosotros', 'x_descripcion1', 'descripcion1', '`descripcion1`', '`descripcion1`', 201, -1, FALSE, '`descripcion1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['descripcion1'] = &$this->descripcion1;

		// titulo2
		$this->titulo2 = new cField('nosotros', 'nosotros', 'x_titulo2', 'titulo2', '`titulo2`', '`titulo2`', 200, -1, FALSE, '`titulo2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['titulo2'] = &$this->titulo2;

		// descripcion2
		$this->descripcion2 = new cField('nosotros', 'nosotros', 'x_descripcion2', 'descripcion2', '`descripcion2`', '`descripcion2`', 201, -1, FALSE, '`descripcion2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['descripcion2'] = &$this->descripcion2;

		// titulo3
		$this->titulo3 = new cField('nosotros', 'nosotros', 'x_titulo3', 'titulo3', '`titulo3`', '`titulo3`', 200, -1, FALSE, '`titulo3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['titulo3'] = &$this->titulo3;

		// descripcion3
		$this->descripcion3 = new cField('nosotros', 'nosotros', 'x_descripcion3', 'descripcion3', '`descripcion3`', '`descripcion3`', 201, -1, FALSE, '`descripcion3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['descripcion3'] = &$this->descripcion3;
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`nosotros`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . substr($sSql, 13);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`nosotros`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('id_nosotros', $rs))
				ew_AddFilter($where, ew_QuotedName('id_nosotros') . '=' . ew_QuotedValue($rs['id_nosotros'], $this->id_nosotros->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id_nosotros` = @id_nosotros@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_nosotros->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_nosotros@", ew_AdjustSql($this->id_nosotros->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "nosotroslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "nosotroslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("nosotrosview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("nosotrosview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "nosotrosadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("nosotrosedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("nosotrosadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("nosotrosdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_nosotros->CurrentValue)) {
			$sUrl .= "id_nosotros=" . urlencode($this->id_nosotros->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["id_nosotros"]; // id_nosotros

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id_nosotros->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id_nosotros->setDbValue($rs->fields('id_nosotros'));
		$this->id_idioma->setDbValue($rs->fields('id_idioma'));
		$this->imagen1_url->Upload->DbValue = $rs->fields('imagen1_url');
		$this->imagen2_url->Upload->DbValue = $rs->fields('imagen2_url');
		$this->titulo1->setDbValue($rs->fields('titulo1'));
		$this->descripcion1->setDbValue($rs->fields('descripcion1'));
		$this->titulo2->setDbValue($rs->fields('titulo2'));
		$this->descripcion2->setDbValue($rs->fields('descripcion2'));
		$this->titulo3->setDbValue($rs->fields('titulo3'));
		$this->descripcion3->setDbValue($rs->fields('descripcion3'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_nosotros

		$this->id_nosotros->CellCssStyle = "white-space: nowrap;";

		// id_idioma
		// imagen1_url
		// imagen2_url
		// titulo1
		// descripcion1
		// titulo2
		// descripcion2
		// titulo3
		// descripcion3
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				if ($this->id_idioma->Exportable) $Doc->ExportCaption($this->id_idioma);
				if ($this->imagen1_url->Exportable) $Doc->ExportCaption($this->imagen1_url);
				if ($this->imagen2_url->Exportable) $Doc->ExportCaption($this->imagen2_url);
				if ($this->titulo1->Exportable) $Doc->ExportCaption($this->titulo1);
				if ($this->descripcion1->Exportable) $Doc->ExportCaption($this->descripcion1);
				if ($this->titulo2->Exportable) $Doc->ExportCaption($this->titulo2);
				if ($this->descripcion2->Exportable) $Doc->ExportCaption($this->descripcion2);
				if ($this->titulo3->Exportable) $Doc->ExportCaption($this->titulo3);
				if ($this->descripcion3->Exportable) $Doc->ExportCaption($this->descripcion3);
			} else {
				if ($this->id_idioma->Exportable) $Doc->ExportCaption($this->id_idioma);
				if ($this->titulo1->Exportable) $Doc->ExportCaption($this->titulo1);
				if ($this->titulo2->Exportable) $Doc->ExportCaption($this->titulo2);
				if ($this->titulo3->Exportable) $Doc->ExportCaption($this->titulo3);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->id_idioma->Exportable) $Doc->ExportField($this->id_idioma);
					if ($this->imagen1_url->Exportable) $Doc->ExportField($this->imagen1_url);
					if ($this->imagen2_url->Exportable) $Doc->ExportField($this->imagen2_url);
					if ($this->titulo1->Exportable) $Doc->ExportField($this->titulo1);
					if ($this->descripcion1->Exportable) $Doc->ExportField($this->descripcion1);
					if ($this->titulo2->Exportable) $Doc->ExportField($this->titulo2);
					if ($this->descripcion2->Exportable) $Doc->ExportField($this->descripcion2);
					if ($this->titulo3->Exportable) $Doc->ExportField($this->titulo3);
					if ($this->descripcion3->Exportable) $Doc->ExportField($this->descripcion3);
				} else {
					if ($this->id_idioma->Exportable) $Doc->ExportField($this->id_idioma);
					if ($this->titulo1->Exportable) $Doc->ExportField($this->titulo1);
					if ($this->titulo2->Exportable) $Doc->ExportField($this->titulo2);
					if ($this->titulo3->Exportable) $Doc->ExportField($this->titulo3);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
