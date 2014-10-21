<?php
/*
 * This file is a part of Solve framework.
 *
 * @author Sergey Evtyshenko <s.evtyshenko@gmail.com>
 * @copyright 2009-2014, Sergey Evtyshenko
 * created: 10/20/14 12:26
 */


namespace Solve\Debug\Logger;

/**
 * Class Logger
 *
 * @package Solve\Logger
 *
 * Class Logger is used to operate with logs
 * @version 1.1
 */

class Logger {

	const NAMESPACE_SYSTEM            = 'system';
	const NAMESPACE_PROJECT           = 'project';
	const NAMESPACE_DB                = 'db';

	private $_logs = array();
	private $_logFolder = '';

	public function add($message, $namespace = self::NAMESPACE_PROJECT) {
		if (empty($this->_logs[$namespace]))
			$this->_logs[$namespace] = array();

		$this->_logs[$namespace][] = array(
			'message'   => $message,
			'namespace' => $namespace,
			'datetime'  => date('d.m.Y H:i:s')
		);

		return $this;
	}

	public function get($namespace = self::NAMESPACE_PROJECT) {
		if (!isset($this->_logs[$namespace])) {
			new \Exception('Invalid namespace: ' . $namespace);
		}

		return $this->_logs[$namespace];
	}

	public function getAll() {
		return $this->_logs;
	}

	public function saveToFile($namespaces = null) {
		if ($namespaces && !is_array($namespaces)) {
			new \Exception('Namespaces [' . $namespaces . '] should be an array');
		} else {
			$namespaces = array_keys($this->_logs);
		}

		if (!is_dir($this->_logFolder)) {
			mkdir($this->_logFolder, 0777, true);
			chmod($this->_logFolder, 0777);
		}

		foreach ($namespaces as $namespace) {
			$h = fopen($this->_logFolder . $namespace . '.txt', 'a+');
			foreach ($this->_logs[$namespace] as $s) {
				fputs($h, $s['datetime'] . ' ' . $s['message'] . "\n");
			}
			$this->_logs[$namespace] = array();
			fclose($h);
		}
	}

	public function setLogFolder($folder) {
		$this->_logFolder = $folder;
	}

	public function getLogFolder() {
		return $this->_logFolder;
	}

	public function __destructor() {
		$this->saveToFile();
	}

} 