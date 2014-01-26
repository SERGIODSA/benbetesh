<?php

// Global variable for table object
$marcas = NULL;

//
// Table class for marcas
//
class cmarcas extends cTable {
	var $id_marca;
	var $id_idioma;
	var $nombre;
	var $amigable;
	var $logo_url;
	var $slideshow1_url;
	var $slideshow2_url;
	var $slideshow3_url;
	var $slideshow4_url;
	var $slideshow5_url;
	var $tienda1_url;
	var $tienda2_url;
	var $tienda3_url;
	var $titulo1;
	var $descripcion1;
	var $titulo2;
	var $descripcion2;
	var $titulo3;
	var $descripcion3;
	var $tiendas_pie;
	var $marcas_pie;
	var $descripcion_form;
	var $telefono;
	var $imagen_url;
	var $url_facebook;
	var $url_twitter;
	var $url_youtube;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'marcas';
		$this->TableName = 'marcas';
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

		// id_marca
		$this->id_marca = new cField('marcas', 'marcas', 'x_id_marca', 'id_marca', '`id_marca`', '`id_marca`', 18, -1, FALSE, '`id_marca`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_marca->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_marca'] = &$this->id_marca;

		// id_idioma
		$this->id_idioma = new cField('marcas', 'marcas', 'x_id_idioma', 'id_idioma', '`id_idioma`', '`id_idioma`', 18, -1, FALSE, '`id_idioma`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_idioma->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_idioma'] = &$this->id_idioma;

		// nombre
		$this->nombre = new cField('marcas', 'marcas', 'x_nombre', 'nombre', '`nombre`', '`nombre`', 201, -1, FALSE, '`nombre`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nombre'] = &$this->nombre;

		// amigable
		$this->amigable = new cField('marcas', 'marcas', 'x_amigable', 'amigable', '`amigable`', '`amigable`', 201, -1, FALSE, '`amigable`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['amigable'] = &$this->amigable;

		// logo_url
		$this->logo_url = new cField('marcas', 'marcas', 'x_logo_url', 'logo_url', '`logo_url`', '`logo_url`', 201, -1, TRUE, '`logo_url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['logo_url'] = &$this->logo_url;

		// slideshow1_url
		$this->slideshow1_url = new cField('marcas', 'marcas', 'x_slideshow1_url', 'slideshow1_url', '`slideshow1_url`', '`slideshow1_url`', 201, -1, TRUE, '`slideshow1_url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['slideshow1_url'] = &$this->slideshow1_url;

		// slideshow2_url
		$this->slideshow2_url = new cField('marcas', 'marcas', 'x_slideshow2_url', 'slideshow2_url', '`slideshow2_url`', '`slideshow2_url`', 201, -1, TRUE, '`slideshow2_url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['slideshow2_url'] = &$this->slideshow2_url;

		// slideshow3_url
		$this->slideshow3_url = new cField('marcas', 'marcas', 'x_slideshow3_url', 'slideshow3_url', '`slideshow3_url`', '`slideshow3_url`', 201, -1, TRUE, '`slideshow3_url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['slideshow3_url'] = &$this->slideshow3_url;

		// slideshow4_url
		$this->slideshow4_url = new cField('marcas', 'marcas', 'x_slideshow4_url', 'slideshow4_url', '`slideshow4_url`', '`slideshow4_url`', 201, -1, TRUE, '`slideshow4_url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['slideshow4_url'] = &$this->slideshow4_url;

		// slideshow5_url
		$this->slideshow5_url = new cField('marcas', 'marcas', 'x_slideshow5_url', 'slideshow5_url', '`slideshow5_url`', '`slideshow5_url`', 201, -1, TRUE, '`slideshow5_url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['slideshow5_url'] = &$this->slideshow5_url;

		// tienda1_url
		$this->tienda1_url = new cField('marcas', 'marcas', 'x_tienda1_url', 'tienda1_url', '`tienda1_url`', '`tienda1_url`', 201, -1, TRUE, '`tienda1_url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['tienda1_url'] = &$this->tienda1_url;

		// tienda2_url
		$this->tienda2_url = new cField('marcas', 'marcas', 'x_tienda2_url', 'tienda2_url', '`tienda2_url`', '`tienda2_url`', 201, -1, TRUE, '`tienda2_url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['tienda2_url'] = &$this->tienda2_url;

		// tienda3_url
		$this->tienda3_url = new cField('marcas', 'marcas', 'x_tienda3_url', 'tienda3_url', '`tienda3_url`', '`tienda3_url`', 201, -1, TRUE, '`tienda3_url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['tienda3_url'] = &$this->tienda3_url;

		// titulo1
		$this->titulo1 = new cField('marcas', 'marcas', 'x_titulo1', 'titulo1', '`titulo1`', '`titulo1`', 200, -1, FALSE, '`titulo1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['titulo1'] = &$this->titulo1;

		// descripcion1
		$this->descripcion1 = new cField('marcas', 'marcas', 'x_descripcion1', 'descripcion1', '`descripcion1`', '`descripcion1`', 201, -1, FALSE, '`descripcion1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['descripcion1'] = &$this->descripcion1;

		// titulo2
		$this->titulo2 = new cField('marcas', 'marcas', 'x_titulo2', 'titulo2', '`titulo2`', '`titulo2`', 200, -1, FALSE, '`titulo2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['titulo2'] = &$this->titulo2;

		// descripcion2
		$this->descripcion2 = new cField('marcas', 'marcas', 'x_descripcion2', 'descripcion2', '`descripcion2`', '`descripcion2`', 201, -1, FALSE, '`descripcion2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['descripcion2'] = &$this->descripcion2;

		// titulo3
		$this->titulo3 = new cField('marcas', 'marcas', 'x_titulo3', 'titulo3', '`titulo3`', '`titulo3`', 200, -1, FALSE, '`titulo3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['titulo3'] = &$this->titulo3;

		// descripcion3
		$this->descripcion3 = new cField('marcas', 'marcas', 'x_descripcion3', 'descripcion3', '`descripcion3`', '`descripcion3`', 201, -1, FALSE, '`descripcion3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['descripcion3'] = &$this->descripcion3;

		// tiendas_pie
		$this->tiendas_pie = new cField('marcas', 'marcas', 'x_tiendas_pie', 'tiendas_pie', '`tiendas_pie`', '`tiendas_pie`', 16, -1, FALSE, '`tiendas_pie`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tiendas_pie->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tiendas_pie'] = &$this->tiendas_pie;

		// marcas_pie
		$this->marcas_pie = new cField('marcas', 'marcas', 'x_marcas_pie', 'marcas_pie', '`marcas_pie`', '`marcas_pie`', 16, -1, FALSE, '`marcas_pie`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->marcas_pie->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['marcas_pie'] = &$this->marcas_pie;

		// descripcion_form
		$this->descripcion_form = new cField('marcas', 'marcas', 'x_descripcion_form', 'descripcion_form', '`descripcion_form`', '`descripcion_form`', 200, -1, FALSE, '`descripcion_form`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['descripcion_form'] = &$this->descripcion_form;

		// telefono
		$this->telefono = new cField('marcas', 'marcas', 'x_telefono', 'telefono', '`telefono`', '`telefono`', 200, -1, FALSE, '`telefono`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['telefono'] = &$this->telefono;

		// imagen_url
		$this->imagen_url = new cField('marcas', 'marcas', 'x_imagen_url', 'imagen_url', '`imagen_url`', '`imagen_url`', 201, -1, TRUE, '`imagen_url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['imagen_url'] = &$this->imagen_url;

		// url_facebook
		$this->url_facebook = new cField('marcas', 'marcas', 'x_url_facebook', 'url_facebook', '`url_facebook`', '`url_facebook`', 201, -1, FALSE, '`url_facebook`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['url_facebook'] = &$this->url_facebook;

		// url_twitter
		$this->url_twitter = new cField('marcas', 'marcas', 'x_url_twitter', 'url_twitter', '`url_twitter`', '`url_twitter`', 201, -1, FALSE, '`url_twitter`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['url_twitter'] = &$this->url_twitter;

		// url_youtube
		$this->url_youtube = new cField('marcas', 'marcas', 'x_url_youtube', 'url_youtube', '`url_youtube`', '`url_youtube`', 201, -1, FALSE, '`url_youtube`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['url_youtube'] = &$this->url_youtube;
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
		return "`marcas`";
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
	var $UpdateTable = "`marcas`";

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
			if (array_key_exists('id_marca', $rs))
				ew_AddFilter($where, ew_QuotedName('id_marca') . '=' . ew_QuotedValue($rs['id_marca'], $this->id_marca->FldDataType));
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
		return "`id_marca` = @id_marca@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_marca->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_marca@", ew_AdjustSql($this->id_marca->CurrentValue), $sKeyFilter); // Replace key value
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
			return "marcaslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "marcaslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("marcasview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("marcasview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "marcasadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("marcasedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("marcasadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("marcasdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_marca->CurrentValue)) {
			$sUrl .= "id_marca=" . urlencode($this->id_marca->CurrentValue);
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
			$arKeys[] = @$_GET["id_marca"]; // id_marca

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
			$this->id_marca->CurrentValue = $key;
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
		$this->id_marca->setDbValue($rs->fields('id_marca'));
		$this->id_idioma->setDbValue($rs->fields('id_idioma'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->amigable->setDbValue($rs->fields('amigable'));
		$this->logo_url->Upload->DbValue = $rs->fields('logo_url');
		$this->slideshow1_url->Upload->DbValue = $rs->fields('slideshow1_url');
		$this->slideshow2_url->Upload->DbValue = $rs->fields('slideshow2_url');
		$this->slideshow3_url->Upload->DbValue = $rs->fields('slideshow3_url');
		$this->slideshow4_url->Upload->DbValue = $rs->fields('slideshow4_url');
		$this->slideshow5_url->Upload->DbValue = $rs->fields('slideshow5_url');
		$this->tienda1_url->Upload->DbValue = $rs->fields('tienda1_url');
		$this->tienda2_url->Upload->DbValue = $rs->fields('tienda2_url');
		$this->tienda3_url->Upload->DbValue = $rs->fields('tienda3_url');
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
		$this->url_facebook->setDbValue($rs->fields('url_facebook'));
		$this->url_twitter->setDbValue($rs->fields('url_twitter'));
		$this->url_youtube->setDbValue($rs->fields('url_youtube'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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
		// id_marca

		$this->id_marca->ViewValue = $this->id_marca->CurrentValue;
		$this->id_marca->ViewCustomAttributes = "";

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

		// id_marca
		$this->id_marca->LinkCustomAttributes = "";
		$this->id_marca->HrefValue = "";
		$this->id_marca->TooltipValue = "";

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
				if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
				if ($this->amigable->Exportable) $Doc->ExportCaption($this->amigable);
				if ($this->logo_url->Exportable) $Doc->ExportCaption($this->logo_url);
				if ($this->slideshow1_url->Exportable) $Doc->ExportCaption($this->slideshow1_url);
				if ($this->slideshow2_url->Exportable) $Doc->ExportCaption($this->slideshow2_url);
				if ($this->slideshow3_url->Exportable) $Doc->ExportCaption($this->slideshow3_url);
				if ($this->slideshow4_url->Exportable) $Doc->ExportCaption($this->slideshow4_url);
				if ($this->slideshow5_url->Exportable) $Doc->ExportCaption($this->slideshow5_url);
				if ($this->tienda1_url->Exportable) $Doc->ExportCaption($this->tienda1_url);
				if ($this->tienda2_url->Exportable) $Doc->ExportCaption($this->tienda2_url);
				if ($this->tienda3_url->Exportable) $Doc->ExportCaption($this->tienda3_url);
				if ($this->titulo1->Exportable) $Doc->ExportCaption($this->titulo1);
				if ($this->descripcion1->Exportable) $Doc->ExportCaption($this->descripcion1);
				if ($this->titulo2->Exportable) $Doc->ExportCaption($this->titulo2);
				if ($this->descripcion2->Exportable) $Doc->ExportCaption($this->descripcion2);
				if ($this->titulo3->Exportable) $Doc->ExportCaption($this->titulo3);
				if ($this->descripcion3->Exportable) $Doc->ExportCaption($this->descripcion3);
				if ($this->tiendas_pie->Exportable) $Doc->ExportCaption($this->tiendas_pie);
				if ($this->marcas_pie->Exportable) $Doc->ExportCaption($this->marcas_pie);
				if ($this->descripcion_form->Exportable) $Doc->ExportCaption($this->descripcion_form);
				if ($this->telefono->Exportable) $Doc->ExportCaption($this->telefono);
				if ($this->imagen_url->Exportable) $Doc->ExportCaption($this->imagen_url);
				if ($this->url_facebook->Exportable) $Doc->ExportCaption($this->url_facebook);
				if ($this->url_twitter->Exportable) $Doc->ExportCaption($this->url_twitter);
				if ($this->url_youtube->Exportable) $Doc->ExportCaption($this->url_youtube);
			} else {
				if ($this->titulo1->Exportable) $Doc->ExportCaption($this->titulo1);
				if ($this->titulo2->Exportable) $Doc->ExportCaption($this->titulo2);
				if ($this->titulo3->Exportable) $Doc->ExportCaption($this->titulo3);
				if ($this->tiendas_pie->Exportable) $Doc->ExportCaption($this->tiendas_pie);
				if ($this->marcas_pie->Exportable) $Doc->ExportCaption($this->marcas_pie);
				if ($this->descripcion_form->Exportable) $Doc->ExportCaption($this->descripcion_form);
				if ($this->telefono->Exportable) $Doc->ExportCaption($this->telefono);
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
					if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
					if ($this->amigable->Exportable) $Doc->ExportField($this->amigable);
					if ($this->logo_url->Exportable) $Doc->ExportField($this->logo_url);
					if ($this->slideshow1_url->Exportable) $Doc->ExportField($this->slideshow1_url);
					if ($this->slideshow2_url->Exportable) $Doc->ExportField($this->slideshow2_url);
					if ($this->slideshow3_url->Exportable) $Doc->ExportField($this->slideshow3_url);
					if ($this->slideshow4_url->Exportable) $Doc->ExportField($this->slideshow4_url);
					if ($this->slideshow5_url->Exportable) $Doc->ExportField($this->slideshow5_url);
					if ($this->tienda1_url->Exportable) $Doc->ExportField($this->tienda1_url);
					if ($this->tienda2_url->Exportable) $Doc->ExportField($this->tienda2_url);
					if ($this->tienda3_url->Exportable) $Doc->ExportField($this->tienda3_url);
					if ($this->titulo1->Exportable) $Doc->ExportField($this->titulo1);
					if ($this->descripcion1->Exportable) $Doc->ExportField($this->descripcion1);
					if ($this->titulo2->Exportable) $Doc->ExportField($this->titulo2);
					if ($this->descripcion2->Exportable) $Doc->ExportField($this->descripcion2);
					if ($this->titulo3->Exportable) $Doc->ExportField($this->titulo3);
					if ($this->descripcion3->Exportable) $Doc->ExportField($this->descripcion3);
					if ($this->tiendas_pie->Exportable) $Doc->ExportField($this->tiendas_pie);
					if ($this->marcas_pie->Exportable) $Doc->ExportField($this->marcas_pie);
					if ($this->descripcion_form->Exportable) $Doc->ExportField($this->descripcion_form);
					if ($this->telefono->Exportable) $Doc->ExportField($this->telefono);
					if ($this->imagen_url->Exportable) $Doc->ExportField($this->imagen_url);
					if ($this->url_facebook->Exportable) $Doc->ExportField($this->url_facebook);
					if ($this->url_twitter->Exportable) $Doc->ExportField($this->url_twitter);
					if ($this->url_youtube->Exportable) $Doc->ExportField($this->url_youtube);
				} else {
					if ($this->titulo1->Exportable) $Doc->ExportField($this->titulo1);
					if ($this->titulo2->Exportable) $Doc->ExportField($this->titulo2);
					if ($this->titulo3->Exportable) $Doc->ExportField($this->titulo3);
					if ($this->tiendas_pie->Exportable) $Doc->ExportField($this->tiendas_pie);
					if ($this->marcas_pie->Exportable) $Doc->ExportField($this->marcas_pie);
					if ($this->descripcion_form->Exportable) $Doc->ExportField($this->descripcion_form);
					if ($this->telefono->Exportable) $Doc->ExportField($this->telefono);
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
