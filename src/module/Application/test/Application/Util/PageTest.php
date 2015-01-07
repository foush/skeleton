<?php
namespace Application\Util;

class PageTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Unit under test
	 * @var \Application\Util\Page
	 */
	protected $uut;

	public function setUp()
	{
		$this->uut = new Page();
	}

	/**
	 * @test
	 */
	public function offset_defaults_to_zero()
	{
		$this->assertEquals(Page::offset(Param::create()), 0);
	}

	/**
	 * @test
	 */
	public function offset_parsed()
	{
		$offset = 4;
		$this->assertEquals(Page::offset(Param::create(array('offset' => $offset))), $offset);
	}

	/**
	 * @test
	 */
	public function offset_not_allowed_below_zero()
	{
		$default = 0;
		$this->assertEquals(Page::offset(Param::create(array('offset' => -1), $default)), $default);
	}

	/**
	 * @test
	 */
	public function offset_uses_default_if_param_invalid()
	{
		$default = 5;
		$this->assertEquals(Page::offset(Param::create(array('offset' => 'invalid value')), $default), $default);
	}

	/**
	 * @test
	 */
	public function limit_defaults_to_ten()
	{
		$this->assertEquals(Page::limit(Param::create()), 10);
	}

	/**
	 * @test
	 */
	public function limit_not_allowed_below_zero()
	{
		$default = 10;
		$this->assertEquals(Page::limit(Param::create(array('limit' => -1), $default)), $default);
	}

	/**
	 * @test
	 */
	public function limit_parsed()
	{
		$limit = 4;
		$this->assertEquals(Page::limit(Param::create(array('limit' => $limit))), $limit);
	}


	/**
	 * @test
	 */
	public function limit_uses_default_if_param_invalid()
	{
		$default = 5;
		$this->assertEquals(Page::limit(Param::create(array('limit' => 'invalid value')), $default), $default);
	}

}