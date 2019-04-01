<?php
/**
 * Created by PhpStorm.
 * User: a
 * Date: 14.03.18
 * Time: 15:09
 */

/** @property DB db */
class ModelExtensionDashboardOrderKpi extends Model {
	public function getOrdersByStatuses($start_date, $currency_code, $order_statuses = array()) {
		if (!isset($start_date)) {
			throw new Exception('start_date not set');
		}

		if (!isset($currency_code)) {
			throw new Exception('Currency not set');
		}

		$order_statuses = array_map('intval', $order_statuses);
		
		$sql = 'SELECT `value` FROM ' . DB_PREFIX . 'currency WHERE `code` = "' . $this->db->escape($currency_code) . '"';
		$res = $this->db->query($sql);
		if ($res->num_rows == 0) {
			throw new Exception('Currency with code "' . $currency_code . '" not exists');
		}
		$currency_value = $res->row['value'];

		$order_statuses_sql = $order_statuses ? " AND o.order_status_id IN (". implode(",", $order_statuses). ") " : '';
		$order_statuses_sort_order = $order_statuses ? " ORDER BY FIELD(o.order_status_id, ". implode(",", $order_statuses). " ) " : '';


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
				o.order_status_id > 0 '.$order_statuses_sql.'
				and o.date_added > \'' . $this->db->escape($start_date) . '\'
			GROUP BY 
				o.order_status_id, `year`, `month`' . $order_statuses_sort_order;

		$result = $this->db->query($sql);

		return $result->rows;
	}

	public function getStatuses() {
		$sql = 'SELECT order_status_id, name FROM ' . DB_PREFIX . 'order_status';
		$rows = $this->db->query($sql);
		return array_column($rows->rows , 'name' , 'order_status_id');

	}
}

if (! function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( !array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( !array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}