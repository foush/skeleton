<?php
namespace Application\View\Helper;

class FlashMessagesTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Unit under test
	 * @var
	 */
	protected $uut;

	public function setUp()
	{
		/* @var $manager \Zend\View\HelperPluginManager */
		$manager = \Bootstrap::getServiceManager()->get('ViewHelperManager');
		$this->uut = $manager->get('flashMessages');
	}

	/**
	 * @test
	 */
	public function is_view_helper()
	{
		$this->assertInstanceOf('Zend\View\Helper\AbstractHelper', $this->uut, "Not a view helper");
	}

	/**
	 * @test
	 */
	public function invoke_returns_array() {
		$function = $this->uut;
		$result = $function();
		$this->assertInternalType('array', $result, "Invoke ought to return an array!");
	}
}