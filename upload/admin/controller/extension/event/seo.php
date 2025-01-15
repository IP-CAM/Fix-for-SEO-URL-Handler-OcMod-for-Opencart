<?php
class controllerExtensionEventSeo extends Controller {
	
	public function view(&$view, &$data, &$output) {// triggered before view form
        // check if this module is enabled
        if(!$this->config->get('module_seo_status')) {
            return;
        }
		$this->load->language('extension/module/seo_event');
		$data['text_alias'] = $this->language->get('text_alias');
		
		$this->load->model('extension/module/seo');
		// get SEO aliases
		if(isset($data['product_seo_url'])) {
			foreach($data['product_seo_url'] as $store => $value) {
				foreach($value as $language => $keyword) {
					$data['product_seo_alias'][$store][$language] = $this->model_extension_module_seo->getAliases($keyword,$store,$language);
				}
			}
		}
		if(isset($data['category_seo_url'])) {
			foreach($data['category_seo_url'] as $store => $value) {
				foreach($value as $language => $keyword) {
					$data['product_seo_alias'][$store][$language] = $this->model_extension_module_seo->getAliases($keyword,$store,$language);
				}
			}
		}
		if(isset($data['manufacturer_seo_url'])) {
			foreach($data['manufacturer_seo_url'] as $store => $value) {
				foreach($value as $language => $keyword) {
					$data['product_seo_alias'][$store][$language] = $this->model_extension_module_seo->getAliases($keyword,$store,$language);
				}
			}
		}
		if(isset($data['information_seo_url'])) {
			foreach($data['information_seo_url'] as $store => $value) {
				foreach($value as $language => $keyword) {
					$data['product_seo_alias'][$store][$language] = $this->model_extension_module_seo->getAliases($keyword,$store,$language);
				}
			}
		}
		if(isset($data['resource_seo_url'])) {
			foreach($data['resource_seo_url'] as $store => $value) {
				foreach($value as $language => $keyword) {
					$data['product_seo_alias'][$store][$language] = $this->model_extension_module_seo->getAliases($keyword,$store,$language);
				}
			}
		}
		if($this->config->get('module_seo_debug')) {
			$this->log->write($data);
		}
	}
	
	public function save(&$route, &$data, &$output = null) {// triggered after save form
        // check if this module is enabled
        if(!$this->config->get('module_seo_status')) {
            return;
        }
		if((int)$output) {
			$id = $output;
			$temp = $data[0];
		} else {
			$temp = $data[1];
			$id = $data[0];
		}
		$this->language->load('extension/module/seo_event');
		$this->load->model('extension/module/seo');
		$this->load->model('design/seo_url');
		//$this->log->write($temp);
		foreach($temp['product_seo_alias'] as $store_id => $value) {
			foreach($value as $language_id => $aliases) {
				foreach($aliases as $id => $alias) {
					$alias = trim($alias);
					switch(true) {
						case isset($temp['product_seo_url']):
							$keyword = trim($temp['product_seo_url'][$store_id][$language_id]);
							break;
						case isset($temp['manufacturer_seo_url']):
							$keyword = trim($temp['manufacturer_seo_url'][$store_id][$language_id]);
							break;
						case isset($temp['category_seo_url']):
							$keyword = trim($temp['category_seo_url'][$store_id][$language_id]);
							break;
						case isset($temp['information_seo_url']):
							$keyword = trim($temp['information_seo_url'][$store_id][$language_id]);
							break;
						case isset($temp['resource_seo_url']):
							$keyword = trim($temp['resource_seo_url'][$store_id][$language_id]);
							break;
						
					}
					if(!strlen($alias)) {
						$this->model_extension_module_seo->deleteAlias($id);
					} else {
						if(!strlen($keyword)) {
							$this->language->set('text_success', $this->language->get('error_keyword'));
							if($this->config->get('module_seo_debug')) {
								$this->log->write($this->language->get('error_keyword'));
							}
						} elseif($this->model_extension_module_seo->checkAlias($alias, $store_id, $language_id)) {
							$this->language->set('text_success', sprintf($this->language->get('error_duplicate'),$alias));
							if($this->config->get('module_seo_debug')) {
								$this->log->write(sprintf($this->language->get('error_duplicate'),$alias));
							}
						} else {
							$this->model_extension_module_seo->saveAlias($keyword, $alias, $store_id, $language_id);
						}
					}
				}
			}
		}
	}
	
}
