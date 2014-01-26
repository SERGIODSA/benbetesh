<?php

// Global variable for table object
$form_contacto = NULL;

//
// Table class for form_contacto
//
class cform_contacto extends cTable {
	var $id_contacto;
	var $id_idioma;
	var $nombre;
	var $empresa;
	var $_email;
	var $telefono;
	var $pais;
	var $mensaje;
	var $fecha_creacion;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'form_contacto';
		$this->TableName = 'form_contacto';
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

		// id_contacto
		$this->id_contacto = new cField('form_contacto', 'form_contacto', 'x_id_contacto', 'id_contacto', '`id_contacto`', '`id_contacto`', 19, -1, FALSE, '`id_contacto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_contacto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_contacto'] = &$this->id_contacto;

		// id_idioma
		$this->id_idioma = new cField('form_contacto', 'form_contacto', 'x_id_idioma', 'id_idioma', '`id_idioma`', '`id_idioma`', 18, -1, FALSE, '`id_idioma`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_idioma->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_idioma'] = &$this->id_idioma;

		// nombre
		$this->nombre = new cField('form_contacto', 'form_contacto', 'x_nombre', 'nombre', '`nombre`', '`nombre`', 200, -1, FALSE, '`nombre`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nombre'] = &$this->nombre;

		// empresa
		$this->empresa = new cField('form_contacto', 'form_contacto', 'x_empresa', 'empresa', '`empresa`', '`empresa`', 200, -1, FALSE, '`empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['empresa'] = &$this->empresa;

		// email
		$this->_email = new cField('form_contacto', 'form_contacto', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['email'] = &$this->_email;

		// telefono
		$this->telefono = new cField('form_contacto', 'form_contacto', 'x_telefono', 'telefono', '`telefono`', '`telefono`', 200, -1, FALSE, '`telefono`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['telefono'] = &$this->telefono;

		// pais
		$this->pais = new cField('form_contacto', 'form_contacto', 'x_pais', 'pais', '`pais`', '`pais`', 200, -1, FALSE, '`pais`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['pais'] = &$this->pais;

		// mensaje
		$this->mensaje = new cField('form_contacto', 'form_contacto', 'x_mensaje', 'mensaje', '`mensaje`', '`mensaje`', 201, -1, FALSE, '`mensaje`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['mensaje'] = &$this->mensaje;

		// fecha_creacion
		$this->fecha_creacion = new cField('form_contacto', 'form_contacto', 'x_fecha_creacion', 'fecha_creacion', '`fecha_creacion`', 'DATE_FORMAT(`fecha_creacion`, \'%d/%m/%Y\')', 135, 7, FALSE, '`fecha_creacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_creacion->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_creacion'] = &$this->fecha_creacion;
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
		return "`form_contacto`";
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
	var $UpdateTable = "`form_contacto`";

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
			if (array_key_exists('id_contacto', $rs))
				ew_AddFilter($where, ew_QuotedName('id_contacto') . '=' . ew_QuotedValue($rs['id_contacto'], $this->id_contacto->FldDataType));
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
		return "`id_contacto` = @id_contacto@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_contacto->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_contacto@", ew_AdjustSql($this->id_contacto->CurrentValue), $sKeyFilter); // Replace key value
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
			return "form_contactolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "form_contactolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("form_contactoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("form_contactoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "form_contactoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("form_contactoedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("form_contactoadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("form_contactodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_contacto->CurrentValue)) {
			$sUrl .= "id_contacto=" . urlencode($this->id_contacto->CurrentValue);
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
			$arKeys[] = @$_GET["id_contacto"]; // id_contacto

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
			$this->id_contacto->CurrentValue = $key;
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_contacto

		$this->id_contacto->CellCssStyle = "white-space: nowrap;";

		// id_idioma
		$this->id_idioma->CellCssStyle = "white-space: nowrap;";

		// nombre
		// empresa
		// email
		// telefono
		// pais
		// mensaje
		// fecha_creacion
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
				if ($this->id_contacto->Exportable) $Doc->ExportCaption($this->id_contacto);
				if ($this->id_idioma->Exportable) $Doc->ExportCaption($this->id_idioma);
				if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
				if ($this->empresa->Exportable) $Doc->ExportCaption($this->empresa);
				if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
				if ($this->telefono->Exportable) $Doc->ExportCaption($this->telefono);
				if ($this->pais->Exportable) $Doc->ExportCaption($this->pais);
				if ($this->mensaje->Exportable) $Doc->ExportCaption($this->mensaje);
				if ($this->fecha_creacion->Exportable) $Doc->ExportCaption($this->fecha_creacion);
			} else {
				if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
				if ($this->empresa->Exportable) $Doc->ExportCaption($this->empresa);
				if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
				if ($this->telefono->Exportable) $Doc->ExportCaption($this->telefono);
				if ($this->pais->Exportable) $Doc->ExportCaption($this->pais);
				if ($this->mensaje->Exportable) $Doc->ExportCaption($this->mensaje);
				if ($this->fecha_creacion->Exportable) $Doc->ExportCaption($this->fecha_creacion);
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
					if ($this->id_contacto->Exportable) $Doc->ExportField($this->id_contacto);
					if ($this->id_idioma->Exportable) $Doc->ExportField($this->id_idioma);
					if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
					if ($this->empresa->Exportable) $Doc->ExportField($this->empresa);
					if ($this->_email->Exportable) $Doc->ExportField($this->_email);
					if ($this->telefono->Exportable) $Doc->ExportField($this->telefono);
					if ($this->pais->Exportable) $Doc->ExportField($this->pais);
					if ($this->mensaje->Exportable) $Doc->ExportField($this->mensaje);
					if ($this->fecha_creacion->Exportable) $Doc->ExportField($this->fecha_creacion);
				} else {
					if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
					if ($this->empresa->Exportable) $Doc->ExportField($this->empresa);
					if ($this->_email->Exportable) $Doc->ExportField($this->_email);
					if ($this->telefono->Exportable) $Doc->ExportField($this->telefono);
					if ($this->pais->Exportable) $Doc->ExportField($this->pais);
					if ($this->mensaje->Exportable) $Doc->ExportField($this->mensaje);
					if ($this->fecha_creacion->Exportable) $Doc->ExportField($this->fecha_creacion);
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
