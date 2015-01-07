<?php
namespace Application\Util;

use Doctrine\Common\Collections\ArrayCollection;

class ParamTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Unit under test
	 * @var \Application\Util\Param
	 */
	protected $uut;

	public function setUp()
	{
		$this->uut = new Param();
	}

	/**
	 * @return array
	 */
	public function extractionProvider()
	{
		$arr = array("one", 2, "thr33");
		$assocArr = array('key1' => 'one', 'key2' => 2, 3 => 'three');
		return array(
			array($arr, $arr),
			array($assocArr, $assocArr),
			array(new ArrayCollection($arr), $arr),
			array(new ArrayCollection($assocArr), $assocArr),
		);
	}

	/**
	 * @test
	 * @dataProvider extractionProvider
	 */
	public function extract_returns_array($paramsCollection, $expectedExtraction)
	{
		$this->assertEquals($expectedExtraction, Param::extractParamsFromCollection($paramsCollection));
	}

	/**
	 * @return array
	 */
	public function factoryProvider()
	{
		$arr = array("key1" => 'val1', 'key2' => 3);
		return array(
			array(null, array()),
			array($arr, $arr),
			array(new ArrayCollection($arr), $arr),
		);
	}

	/**
	 * @test
	 * @dataProvider factoryProvider
	 */
	public function create_factory_works($params, $expected)
	{
		$this->assertEquals($expected, Param::create($params)->get());
	}
	/**
	 * @return array
	 */
	public function paramFactoryProvider()
	{
		return array(
			array(
				array(),// expected
				array(),// query
				array(),// post
				array(),// route
				array(),// files
			),
			// all entries are merged
			array(
				array('key1' => 1, 'key2' => 2, 'key3' => 3, 'key4' => 4),// expected
				array('key1' => 1),// query
				array('key2' => 2),// post
				array('key3' => 3),// route
				array('key4' => 4),// files
			),

			// post overwrites get when in conflict
			array(
				array('key1' => 1, 'key2' => 3),// expected
				array('key1' => 2, 'key2' => 3),// query
				array('key1' => 1),// post
				array(),// route
				array(),// files
			),
			// route overwrites get when in conflict
			array(
				array('key1' => 1, 'key2' => 3),// expected
				array('key1' => 2, 'key2' => 3),// query
				array(),// post
				array('key1' => 1),// route
				array(),// files
			),
			// files overwrites get when in conflict
			array(
				array('key1' => 1, 'key2' => 3),// expected
				array('key1' => 2, 'key2' => 3),// query
				array(),// post
				array(),// route
				array('key1' => 1),// files
			),
			// route overwrites post when in conflict
			array(
				array('key1' => 1, 'key2' => 3),// expected
				array(),// query
				array('key1' => 2, 'key2' => 3),// post
				array('key1' => 1),// route
				array(),// files
			),
			// files overwrites post when in conflict
			array(
				array('key1' => 1, 'key2' => 3),// expected
				array(),// query
				array('key1' => 2, 'key2' => 3),// post
				array(),// route
				array('key1' => 1),// files
			),
			// files overwrites route when in conflict
			array(
				array('key1' => 1, 'key2' => 3),// expected
				array(),// query
				array(),// post
				array('key1' => 2, 'key2' => 3),// route
				array('key1' => 1),// files
			),

		);
	}

	/**
	 * @test
	 * @dataProvider paramFactoryProvider
	 */
	public function create_factory_from_params_works($expected, $query, $post, $route, $files)
	{
		$mock = $this->getMock('\Zend\Mvc\Controller\Plugin\Params', array('fromQuery', 'fromPost', 'fromRoute', 'fromFiles'));
		$mock->expects($this->once())->method('fromQuery')->willReturn($query);
		$mock->expects($this->once())->method('fromPost')->willReturn($post);
		$mock->expects($this->once())->method('fromRoute')->willReturn($route);
		$mock->expects($this->once())->method('fromFiles')->willReturn($files);
		$this->assertEquals($expected, Param::create($mock)->get());
	}

}