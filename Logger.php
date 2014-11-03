<?php
/*
 * This file is a part of Solve framework.
 *
 * @author Sergey Evtyshenko <s.evtyshenko@gmail.com>
 * @copyright 2009-2014, Sergey Evtyshenko
 * created: 10/20/14 12:26
 */


namespace Solve\Logger;

/**
 * Class Logger
 *
 * @package Solve\Logger
 *
 * Class Logger is used to operate with logs
 * @version 1.1
 */

class Logger {

    const NAMESPACE_SYSTEM      = 'system';
    const NAMESPACE_APPLICATION = 'application';
    const NAMESPACE_FRAMEWORK   = 'framework';
    const NAMESPACE_DB          = 'db';

    private $_logs     = array();
    private $_logsPath = null;

    public function add($message, $namespace = self::NAMESPACE_APPLICATION) {
        if (empty($this->_logs[$namespace])) {
            $this->_logs[$namespace] = array();
        }

        $this->_logs[$namespace][] = array(
            'message'   => $message,
            'namespace' => $namespace,
            'datetime'  => date('d.m.Y H:i:s') . substr((string)microtime(), 1, 5)
        );

        return $this;
    }

    public function getList($namespace = self::NAMESPACE_APPLICATION) {
        return empty($this->_logs[$namespace]) ? array() : $this->_logs[$namespace];
    }

    public function getLast($namespace = self::NAMESPACE_APPLICATION) {
        return empty($this->_logs[$namespace]) ? null : end($this->_logs[$namespace]);
    }

    public function hasLogs($namespace = self::NAMESPACE_APPLICATION) {
        return !empty($this->_logs[$namespace]);
    }

    public function getListWithNamespaces() {
        return $this->_logs;
    }

    public function save($namespaces = null) {
        if ($namespaces && !is_array($namespaces)) {
            new \Exception('Namespaces [' . $namespaces . '] should be an array');
        } else {
            $namespaces = array_keys($this->_logs);
        }
        if (empty($this->_logsPath)) {
            trigger_error('Logs path are not set', E_USER_WARNING);
            return true;
        }
        if (!is_dir($this->_logsPath)) {
            mkdir($this->_logsPath, 0777, true);
            chmod($this->_logsPath, 0777);
        }

        foreach ($namespaces as $namespace) {
            $h = fopen($this->_logsPath . $namespace . '.txt', 'a+');
            foreach ($this->_logs[$namespace] as $s) {
                fputs($h, $s['datetime'] . ' ' . $s['message'] . "\n");
            }
            $this->_logs[$namespace] = array();
            fclose($h);
        }
    }

    public function setLogsPath($folder) {
        if ($folder[strlen($folder)-1] !== DIRECTORY_SEPARATOR) $folder .= DIRECTORY_SEPARATOR;
        $this->_logsPath = $folder;
        return $this;
    }

    public function getLogsPath() {
        return $this->_logsPath;
    }

    public function __destruct() {
        $this->save();
    }

} 