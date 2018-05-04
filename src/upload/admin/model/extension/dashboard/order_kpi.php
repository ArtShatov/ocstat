<?php
/**
 * Created by PhpStorm.
 * User: a
 * Date: 14.03.18
 * Time: 15:09
 */

/** @property DB db */
class ModelExtensionDashboardOrderKpi extends Model {
	public function getOrdersByStatuses($start_date, $currency_code) {
		if (!isset($start_date)) {
			throw new Exception('start_date not set');
		}

		if (!isset($currency_code)) {
			throw new Exception('Currency not set');
		}
		
		$sql = 'SELECT `value` FROM oc_currency WHERE `code` = "' . $this->db->escape($currency_code) . '"';
		$res = $this->db->query($sql);
		if ($res->num_rows == 0) {
			throw new Exception('Currency with code "' . $currency_code . '" not exists');
		}
		$currency_value = $res->row['value'];

		$sql = '
			SELECT 
				o.order_status_id, 
				year(o.date_added) as `year`,
				month(o.date_added) as `month`,
				count(*) as `count`,
				sum(o.total * ' . $currency_value . '/ currency_value) as `sum`
			FROM 
				' . DB_PREFIX . 'order o
			WHERE
				o.order_status_id > 0
				and o.date_added > \'' . $this->db->escape($start_date) . '\'
			GROUP BY 
				o.order_status_id, `year`, `month`';
		$result = $this->db->query($sql);

		return $result->rows;
	}

	public function getStatuses() {
		$sql = 'SELECT order_status_id, name FROM ' . DB_PREFIX . 'order_status';
		$rows = $this->db->query($sql);
		return array_column($rows->rows , 'name' , 'order_status_id');

	}
}