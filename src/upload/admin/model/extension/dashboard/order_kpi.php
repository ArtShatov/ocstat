<?php
/**
 * Created by PhpStorm.
 * User: a
 * Date: 14.03.18
 * Time: 15:09
 */

/** @property DB db */
class ModelExtensionDashboardOrderKpi extends Model {
	public function getOrdersByStatuses($start_date, $currency) {

		if (!isset($start_date)) {
			throw new Exception('start_date not set');
		}

		if (!isset($currency)) {
			throw new Exception('Currency not set');
		}

		$sql = '
			SELECT 
				o.order_status_id, 
				year(o.date_added) as `year`,
				month(o.date_added) as `month`,
				count(*) as `count`
				
			FROM 
				' . DB_PREFIX . 'order o
			WHERE
				o.order_status_id > 0
				and o.date_added > \'' . $start_date . '\'
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