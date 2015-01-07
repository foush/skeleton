<?php
namespace Application\Service\Authentication\Event;


class MaxAttemptedLoginsCountServiceTest extends \PHPUnit_Framework_TestCase {

    const CONFIG_MAX_NUMBER_ATTEMPTS = 9000;

	/**
	 * Unit under test
	 * @var \Application\Entity\Base
	 */
	protected $uut;

	public function setUp()
	{
		$stub           = new MaxAttemptedLoginsCountService();
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
		$this->assertEquals(self::CONFIG_MAX_NUMBER_ATTEMPTS, $this->uut->getValue());
	}
}