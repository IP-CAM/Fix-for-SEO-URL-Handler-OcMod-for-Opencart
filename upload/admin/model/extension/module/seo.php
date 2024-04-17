<?php
class ModelExtensionModuleSeo extends Model {
	public function install() {
		// box tables
		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "seo_alias` (
				`seo_alias_id` INT(11) NOT NULL AUTO_INCREMENT,
				`keyword` varchar(255) NOT NULL,
				`alias` varchar(255) NOT NULL,
				`store_id` int(11) NOT NULL,
				`language_id` int(11) NOT NULL,
				PRIMARY KEY (`seo_alias_id`),
				KEY `keyword` (`keyword`),
				KEY `alias` (`alias`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "seo_alias`");
	}
	
	public function getKeyword($alias, $store_id = 0, $language_id = 0) {
		$query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "seo_alias WHERE alias = '" . $this->db->escape($alias) . "' and store_id = '".(int)$store_id . "'");

		return $query->row['keyword'];
	}
	
	public function getAliases($keyword = '', $store_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_alias where store_id = '" . (int)$store_id . "'" . ($keyword?(" and keyword = '".$this->db->escape($keyword)."'"):'').' order by keyword asc');

		return $query->rows;
	}
	
	public function saveAlias($keyword, $alias, $store_id = 0, $language_id = 0) {
		if (strlen($alias) && strlen($keyword)) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_alias WHERE alias = '" . $this->db->escape($alias) . "' and store_id = '".(int)$store_id . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "seo_alias SET alias = '" . $this->db->escape($alias) . "', keyword = '" . $this->db->escape($keyword) . "', store_id = '".(int)$store_id."', language_id = '". (int)$language_id. "'");
		}
	}
	
	public function checkAlias($alias, $store_id, $language_id) {
		$query = $this->db->query("select count(*) as found from " . DB_PREFIX . "seo_url where keyword = '" . $this->db->escape($alias) . "' and store_id = '" . (int)$store_id . "'");
		return (int)$query->row['found'];
	}
	
	public function deleteAlias($id) {
		if($id) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_alias WHERE seo_alias_id = '" . (int)$id . "'");
		}
	}
}