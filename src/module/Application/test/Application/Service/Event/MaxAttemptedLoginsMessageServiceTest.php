<?php
namespace Application\Service\Authentication\Event;


class MaxAttemptedLoginsMessageServiceTest extends \PHPUnit_Framework_TestCase {

    const CONFIG_MESSAGE = 'boop';

	/**
	 * Unit under test
	 * @var \Application\Entity\Base
	 */
	protected $uut;

	public function setUp()
	{
		$stub           = new MaxAttemptedLoginsMessageService();
		$this->uut      = $stub;
        $serviceManager = \Bootstrap::getServiceManager();
        $config = $serviceManager->get('Config');
        $this->uut->setServiceLocator($serviceManager);
	}

	/**
	 * @test
	 */
	public function valueIsFoundFromConfiguration()
	{
		$this->assertEquals(self::CONFIG_MESSAGE, $this->uut->getValue());
	}
}