<?php namespace Trial\DB;

use Trial\Injection\Container;

class ConnectionManager {
	
	private $connections = [];
	
	private $container;
	private $config;
	
	public function __construct (Container $container) {
		$this->container = $container;
		$this->config = $container->get('config.db');
		
		if ($this->config->get('autoload')) {
			$this->connect();
		}
	}
	
	public function get ($group) {
		$group = $group ?: 'default';
		
		if (!isset($this->connections[$group])) {
			$this->connect($group);
		}
		
		return $this->connections[$group];
	}
	
	public function connect ($group = 'default') {
		$config = $this->config->get($group);
		
		if (!$config) {
			return false;
		}
		
		$connection = new Connection($config);
		$connection->connect();
		
		$this->connections[$group] = $connection;
	}
	
}