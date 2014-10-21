<?php
 /*
 * This file is a part of Solve framework.
 *
 * @author Sergey Evtyshenko <s.evtyshenko@gmail.com>
 * @copyright 2009-2014, Sergey Evtyshenko
 * created: 10/20/14 12:46
 */


namespace Solve\Debug\Logger;


require_once __DIR__.'/../Logger.php';

class LoggerTest extends \PHPUnit_Framework_TestCase {

	public function testMain(){
		$this->assertTrue(true, 'Start test');

		date_default_timezone_set( 'Europe/Kiev');

		$logger = new Logger();
		$logger->add('Start log');
		$logger->get();
		$logger->getAll();

		$this->assertEquals('', $logger->getLogFolder(),'Log folder is empty');
		$logger->setLogFolder('asserts/');
		$this->assertEquals('asserts/', $logger->getLogFolder(),'Log folder set');

		$logger->saveToFile();
	}

}
 