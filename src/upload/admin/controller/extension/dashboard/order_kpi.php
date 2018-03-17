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

		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

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

	public function dashboard() {
		$this->load->language('extension/dashboard/order_kpi');
		$data = array();
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_order_status'] = $this->language->get('text_order_status');
		$data['text_total'] = $this->language->get('text_total');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_legend'] = $this->language->get('text_legend');
		$data['text_absolute'] = $this->language->get('text_absolute');



		$this->load->model('extension/dashboard/order_kpi');
		/** @var ModelExtensionDashboardOrderKpi $model */
		$model = $this->model_extension_dashboard_order_kpi;

		$default_currency = $this->config->get('config_currency');
		$start_date = date("Y-m-01 00:00:00" , strtotime("-5 month"));

		$data['data'] = $model->getOrdersByStatuses($start_date, $default_currency);;
		$data['order_statuses'] = $model->getStatuses();
		return $this->load->view('extension/dashboard/order_kpi_info', $data);
	}
}
