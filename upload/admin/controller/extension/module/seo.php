<?php
class ControllerExtensionModuleSeo extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/seo');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_seo', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/seo', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/seo', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_seo_status'])) {
			$data['module_seo_status'] = $this->request->post['module_seo_status'];
		} else {
			$data['module_seo_status'] = $this->config->get('module_seo_status');
		}

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/seo', $data));
	}
	
	public function install() {
		// add db table
		$this->load->model('extension/module/seo');
		$this->model_extension_module_seo->install();
		// set up event handlers
		$this->load->model('setting/event');
		/** Set up events for admin save **/
		$this->model_setting_event->addEvent('seo', 'admin/model/catalog/product/addProduct/after', 'extension/event/seo/save');
		$this->model_setting_event->addEvent('seo', 'admin/model/catalog/product/editProduct/after', 'extension/event/seo/save');
		
		$this->model_setting_event->addEvent('seo', 'admin/model/catalog/category/addCategory/after', 'extension/event/seo/save');
		$this->model_setting_event->addEvent('seo', 'admin/model/catalog/category/editCategory/after', 'extension/event/seo/save');
		
		$this->model_setting_event->addEvent('seo', 'admin/model/catalog/information/addInformation/after', 'extension/event/seo/save');
		$this->model_setting_event->addEvent('seo', 'admin/model/catalog/information/editInformation/after', 'extension/event/seo/save');
		
		$this->model_setting_event->addEvent('seo', 'admin/model/catalog/manufacturer/addManufacturer/after', 'extension/event/seo/save');
		$this->model_setting_event->addEvent('seo', 'admin/model/catalog/manufacturer/editManufacturer/after', 'extension/event/seo/save');
		
		$this->model_setting_event->addEvent('seo', 'admin/model/catalog/resource/addResource/after', 'extension/event/seo/save');
		$this->model_setting_event->addEvent('seo', 'admin/model/catalog/resource/editResource/after', 'extension/event/seo/save');
		/** Set up events for admin view **/
		$this->model_setting_event->addEvent('seo', 'admin/view/catalog/product_form/before', 'extension/event/seo/view');
		$this->model_setting_event->addEvent('seo', 'admin/view/catalog/category_form/before', 'extension/event/seo/view');
		$this->model_setting_event->addEvent('seo', 'admin/view/catalog/information_form/before', 'extension/event/seo/view');
		$this->model_setting_event->addEvent('seo', 'admin/view/catalog/resource_form/before', 'extension/event/seo/view');
	}
	
	public function uninstall() {
		// remove db table
		$this->load->model('extension/module/seo');
		$this->model_extension_module_seo->uninstall();
		// remove events
		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode('seo');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/seo')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		return !$this->error;
	}
}