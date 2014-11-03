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

	public function testMain() {
		$logger = new Logger();
		$this->assertFalse($logger->hasLogs(), 'has logs is false');

		$logger->add('Start log');
		$this->assertContains('Start log', $logger->getLast(), 'getLast default log');
		$this->assertCount(1, $logger->getList(), 'getList count is one');
		$this->assertTrue($logger->hasLogs(), 'has logs is true');

		$logger->add('executing', Logger::NAMESPACE_DB);
		$this->assertArrayHasKey(Logger::NAMESPACE_DB, $logger->getListWithNamespaces(), 'db record added');

		$this->assertEquals('', $logger->getLogsPath(),'Log folder is empty');

		$logsPath = __DIR__ . '/tmp/';
		$logger->setLogsPath($logsPath);
		$this->assertEquals($logsPath, $logger->getLogsPath(),'Log folder set');

		$logger->save();
		$this->assertFileExists($logsPath . Logger::NAMESPACE_APPLICATION . '.log', 'application.txt created');
	}

	public static function tearDownAfterClass() {
		$filesToDelete = GLOB(__DIR__ . '/tmp/*.log');
		foreach($filesToDelete as $filePath) {
			unlink($filePath);
		}
		rmdir(__DIR__ . '/tmp/');
	}


}
 