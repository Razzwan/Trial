<?php namespace Trial\View;

use Trial\Injection\Container;

use Trial\Routing\Http\Output,
	Trial\Routing\Http\Response;

use Trial\View\Plugins\Plugin;

class Template implements Output {
	
	private $app;
	private $container;
	private $data;
	private $view;
	
	private $plugins = [];
	
	private $mainView;
	private $views = [];
	
	public function __construct (Container $container, $view, array $data) {
		$this->app = $container->get('app');
		$this->container = $container;
		$this->data = $data;
		$this->view = $view;
	}
	
	public function render (Response $response = null) {
		$view = new View(
			$this, $this->view, $this->app->buildAppPath($name), $this->data
		);
		$this->mainView = $view;
		
		$view->render();
	}
	
	public function renderPartial ($name, array $data = []) {
		$view = new View(
			$this, $name, $this->app->buildAppPath($name), array_merge(
				$this->mainView->getVariables(), $data
			)
		);
		
		$this->views[$name] = $view;
		$view->render();
	}
	
	public function registerPlugin (Plugin $plugin) {
		$this->plugins[$plugin->getName()] = $plugin;
	}
	
	public function __call ($plugin, $params) {
		return $this->getPlugin($plugin)->execute($params);
	}
	
	public function getPlugin ($name) {
		if (!isset($this->plugins[$name])) {
			throw new Exception(
				"Plugin '$name' does not exists!"
			);
		}
		
		return $this->plugins[$name];
	}
	
}