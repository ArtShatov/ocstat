<?php

/** @property Loader load */
/** @property Document document */
/** @property Language language */
/** @property Url url */
/** @property Request request */
/** @property Response response */
/** @property \Cart\Currency currency */
/** @property Config config */
class ControllerExtensionDashboardOrderKpi extends Controller {

	public function index() {
		$this->load->language('extension/dashboard/order_kpi');

        	$this->document->addScript('https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js');


		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dashboard_order_kpi', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_select_all'] = $this->language->get('text_select_all');
        $data['text_unselect_all'] = $this->language->get('text_unselect_all');




		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_statuses'] = $this->language->get('entry_statuses');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/dashboard/order_kpi', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/dashboard/order_kpi', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true);

		if (isset($this->request->post['dashboard_order_kpi_width'])) {
			$data['dashboard_order_kpi_width'] = $this->request->post['dashboard_order_kpi_width'];
		} else {
			$data['dashboard_order_kpi_width'] = $this->config->get('dashboard_order_kpi_width');
		}

		$data['columns'] = array();

		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}

		if (isset($this->request->post['dashboard_order_kpi_status'])) {
			$data['dashboard_order_kpi_status'] = $this->request->post['dashboard_order_kpi_status'];
		} else {
			$data['dashboard_order_kpi_status'] = $this->config->get('dashboard_order_kpi_status');
		}

		if (isset($this->request->post['dashboard_order_kpi_sort_order'])) {
			$data['dashboard_order_kpi_sort_order'] = $this->request->post['dashboard_order_kpi_sort_order'];
		} else {
			$data['dashboard_order_kpi_sort_order'] = $this->config->get('dashboard_order_kpi_sort_order');
		}



		if (isset($this->request->post['dashboard_order_kpi_order_statuses'])) {
		    $data['dashboard_order_kpi_order_statuses'] = $this->request->post['dashboard_order_kpi_order_statuses'];
		} else {
		    $data['dashboard_order_kpi_order_statuses'] = $this->config->get('dashboard_order_kpi_order_statuses');
		}

		if(!$data['dashboard_order_kpi_order_statuses'] || !is_array($data['dashboard_order_kpi_order_statuses'])) {
			    $data['dashboard_order_kpi_order_statuses'] = array();
		}

		$this->load->model('localisation/order_status');
		$filter_data = array();
		$statuses = $this->model_localisation_order_status->getOrderStatuses($filter_data);


		$data['order_statuses'] = array();
		foreach($statuses as $status) {
		    $key = $status['order_status_id'];
		    if(!isset($data['dashboard_order_kpi_order_statuses'][$key])) {
		        $data['dashboard_order_kpi_order_statuses'][$key] = 1;
		    }
		}




		foreach($data['dashboard_order_kpi_order_statuses'] as $order_status_id => $is_active) {
		    $order_status = false;
		    foreach($statuses as $status) {
		        if($status['order_status_id'] == $order_status_id) {
		            $order_status = $status;
		            break;
		        }
		    }

		    if(!$order_status) {
		        continue;
		    }

		    $data['order_statuses'][] = array(
		        'order_status_id' => $order_status_id,
		        'active' => $is_active,
		        'name' => $order_status['name']
		    );
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/dashboard/order_kpi_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/analytics/google_analytics')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	private function convertData($data) {

		$head = array();
		$rows = array();
		$totals = array();


		foreach ($data as &$year) {
			if (!isset($head[$year['year']])) {
				$head[$year['year']] = array();
				$head[$year['year']]['month'] = array();

			}

			$total_key = $year['year'] . ':'  . $year['month'];
			if (!isset($totals[$total_key])) {
				$totals[$total_key] = array(
					'count' => 0,
					'sum' => 0
				);
			}

			$totals[$total_key]['count'] += $year['count'];
			$totals[$total_key]['sum']   += $year['sum'];

			$head[$year['year']]['year'] = $year['year'];
			$head[$year['year']]['month'][$year['month']] = $year['month'];

			$row_key = $year['order_status_id'];
			if(!isset($rows[$row_key])) {
				$rows[$row_key] = array();
				$rows[$row_key]['total'] = 0;
			}
			$rows[$row_key]['order_status_id'] = $year['order_status_id'];
			$rows[$row_key]['total'] += $year['count'];
            		$rows[$row_key]['href'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] .  '&filter_order_status='. $year['order_status_id'], true);

			if(!isset($rows[$row_key][$year['year']])) {
				$rows[$row_key][$year['year']] = array();
			}
			$rows[$row_key][$year['year']][$year['month']] = array(
				'count' => $year['count'],
				'sum' =>   round($year['sum'])
			);
		}
		unset($year);

		ksort($head);
		foreach ($head as &$year) {
			ksort($year['month']);
		}
		unset($year);

		return array(
			'head' => $head,
			'rows' => $rows,
			'totals' => $totals
		);
	}

	public function dashboard() {
		$this->load->language('extension/dashboard/order_kpi');
		/** @var array(
		 *  'order_statuses' => array()
		 *  'rows' => array(),
		 *  'total' => array(),
		 *  'head' => array()
		 * ) $data */
		$data = array();


		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_order_status'] = $this->language->get('text_order_status');
		$data['text_total'] = $this->language->get('text_total');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_legend'] = $this->language->get('text_legend');
		$data['text_absolute'] = $this->language->get('text_absolute');
		$data['text_sum'] = $this->language->get('text_sum');

		$this->load->model('extension/dashboard/order_kpi');
		/** @var ModelExtensionDashboardOrderKpi $model */
		$model = $this->model_extension_dashboard_order_kpi;

		$default_currency = $this->config->get('config_currency');
		$start_date = date("Y-m-01 00:00:00" , strtotime("-5 month"));

		$order_statuses = array();
		$order_statuses_from_settings = $this->config->get('dashboard_order_kpi_order_statuses');
		if($order_statuses_from_settings && is_array($order_statuses_from_settings)) {
		    foreach($order_statuses_from_settings as $order_status_id => $value) {
		        if($value) {
		            $order_statuses[] = $order_status_id;
		        }
		    }
		}
		

		$rows = $model->getOrdersByStatuses($start_date, $default_currency, $order_statuses);
		$data = array_merge($data , $this->convertData($rows));

		$data['order_statuses'] = $model->getStatuses();


		return $this->load->view('extension/dashboard/order_kpi_info', $data);
	}
}
