<?php

// Global variable for table object
$ss_index = NULL;

//
// Table class for ss_index
//
class css_index extends cTable {
	var $id_ssindex;
	var $id_idioma;
	var $slideshow1_url;
	var $slideshow2_url;
	var $slideshow3_url;
	var $slideshow4_url;
	var $slideshow5_url;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'ss_index';
		$this->TableName = 'ss_index';
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

		// id_ssindex
		$this->id_ssindex = new cField('ss_index', 'ss_index', 'x_id_ssindex', 'id_ssindex', '`id_ssindex`', '`id_ssindex`', 18, -1, FALSE, '`id_ssindex`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_ssindex->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_ssindex'] = &$this->id_ssindex;

		// id_idioma
		$this->id_idioma = new cField('ss_index', 'ss_index', 'x_id_idioma', 'id_idioma', '`id_idioma`', '`id_idioma`', 18, -1, FALSE, '`id_idioma`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_idioma->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_idioma'] = &$this->id_idioma;

		// slideshow1_url
		$this->slideshow1_url = new cField('ss_index', 'ss_index', 'x_slideshow1_url', 'slideshow1_url', '`slideshow1_url`', '`slideshow1_url`', 201, -1, TRUE, '`slideshow1_url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['slideshow1_url'] = &$this->slideshow1_url;

		// slideshow2_url
		$this->slideshow2_url = new cField('ss_index', 'ss_index', 'x_slideshow2_url', 'slideshow2_url', '`slideshow2_url`', '`slideshow2_url`', 201, -1, TRUE, '`slideshow2_url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['slideshow2_url'] = &$this->slideshow2_url;

		// slideshow3_url
		$this->slideshow3_url = new cField('ss_index', 'ss_index', 'x_slideshow3_url', 'slideshow3_url', '`slideshow3_url`', '`slideshow3_url`', 201, -1, TRUE, '`slideshow3_url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['slideshow3_url'] = &$this->slideshow3_url;

		// slideshow4_url
		$this->slideshow4_url = new cField('ss_index', 'ss_index', 'x_slideshow4_url', 'slideshow4_url', '`slideshow4_url`', '`slideshow4_url`', 201, -1, TRUE, '`slideshow4_url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['slideshow4_url'] = &$this->slideshow4_url;

		// slideshow5_url
		$this->slideshow5_url = new cField('ss_index', 'ss_index', 'x_slideshow5_url', 'slideshow5_url', '`slideshow5_url`', '`slideshow5_url`', 201, -1, TRUE, '`slideshow5_url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['slideshow5_url'] = &$this->slideshow5_url;
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
		return "`ss_index`";
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
	var $UpdateTable = "`ss_index`";

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
			if (array_key_exists('id_ssindex', $rs))
				ew_AddFilter($where, ew_QuotedName('id_ssindex') . '=' . ew_QuotedValue($rs['id_ssindex'], $this->id_ssindex->FldDataType));
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
		return "`id_ssindex` = @id_ssindex@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_ssindex->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_ssindex@", ew_AdjustSql($this->id_ssindex->CurrentValue), $sKeyFilter); // Replace key value
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
			return "ss_indexlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "ss_indexlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("ss_indexview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("ss_indexview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "ss_indexadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("ss_indexedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("ss_indexadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("ss_indexdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_ssindex->CurrentValue)) {
			$sUrl .= "id_ssindex=" . urlencode($this->id_ssindex->CurrentValue);
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
			$arKeys[] = @$_GET["id_ssindex"]; // id_ssindex

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
			$this->id_ssindex->CurrentValue = $key;
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
		$this->id_ssindex->setDbValue($rs->fields('id_ssindex'));
		$this->id_idioma->setDbValue($rs->fields('id_idioma'));
		$this->slideshow1_url->Upload->DbValue = $rs->fields('slideshow1_url');
		$this->slideshow2_url->Upload->DbValue = $rs->fields('slideshow2_url');
		$this->slideshow3_url->Upload->DbValue = $rs->fields('slideshow3_url');
		$this->slideshow4_url->Upload->DbValue = $rs->fields('slideshow4_url');
		$this->slideshow5_url->Upload->DbValue = $rs->fields('slideshow5_url');
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_ssindex
		// id_idioma
		// slideshow1_url
		// slideshow2_url
		// slideshow3_url
		// slideshow4_url
		// slideshow5_url
		// id_ssindex

		$this->id_ssindex->ViewValue = $this->id_ssindex->CurrentValue;
		$this->id_ssindex->ViewCustomAttributes = "";

		// id_idioma
		$this->id_idioma->ViewValue = $this->id_idioma->CurrentValue;
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

		// id_ssindex
		$this->id_ssindex->LinkCustomAttributes = "";
		$this->id_ssindex->HrefValue = "";
		$this->id_ssindex->TooltipValue = "";

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
				if ($this->id_ssindex->Exportable) $Doc->ExportCaption($this->id_ssindex);
				if ($this->id_idioma->Exportable) $Doc->ExportCaption($this->id_idioma);
				if ($this->slideshow1_url->Exportable) $Doc->ExportCaption($this->slideshow1_url);
				if ($this->slideshow2_url->Exportable) $Doc->ExportCaption($this->slideshow2_url);
				if ($this->slideshow3_url->Exportable) $Doc->ExportCaption($this->slideshow3_url);
				if ($this->slideshow4_url->Exportable) $Doc->ExportCaption($this->slideshow4_url);
				if ($this->slideshow5_url->Exportable) $Doc->ExportCaption($this->slideshow5_url);
			} else {
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
					if ($this->id_ssindex->Exportable) $Doc->ExportField($this->id_ssindex);
					if ($this->id_idioma->Exportable) $Doc->ExportField($this->id_idioma);
					if ($this->slideshow1_url->Exportable) $Doc->ExportField($this->slideshow1_url);
					if ($this->slideshow2_url->Exportable) $Doc->ExportField($this->slideshow2_url);
					if ($this->slideshow3_url->Exportable) $Doc->ExportField($this->slideshow3_url);
					if ($this->slideshow4_url->Exportable) $Doc->ExportField($this->slideshow4_url);
					if ($this->slideshow5_url->Exportable) $Doc->ExportField($this->slideshow5_url);
				} else {
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
