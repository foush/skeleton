<?php
namespace Application\Service;


class PasswordServiceTest extends \PHPUnit_Framework_TestCase {

    const CONFIG_MESSAGE = 'boop';

	/**
	 * Unit under test
	 * @var \Application\Entity\Base
	 */
	protected $uut;

	public function setUp()
	{
		$stub           = new Password();
		$this->uut      = $stub;
        $serviceManager = \Bootstrap::getServiceManager();
        $entityManager  = $serviceManager->get('em');
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