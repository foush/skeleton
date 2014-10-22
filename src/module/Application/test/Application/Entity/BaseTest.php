<?php
namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class BaseTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Unit under test
	 * @var \Application\Entity\Base
	 */
	protected $uut;

	public function setUp()
	{
		$stub = $this->getMockForAbstractClass('Application\Entity\Base');
		$this->uut = $stub;
	}

	/**
	 * @test
	 */
	public function implements_base_interface()
	{
		$this->assertInstanceOf('Application\Entity\BaseInterface', $this->uut, "Base entity must be instance of BaseInterface");
	}

	/**
	 * @test
	 */
	public function has_id()
	{
		$this->assertObjectHasAttribute('id', $this->uut, "Base entity must have an ID property");
	}

	/**
	 * @test
	 */
	public function flatten_returns_array()
	{
		$this->assertInternalType('array', $this->uut->flatten(), "Flatten must return an array!");
	}

	/**
	 * @test
	 */
	public function is_null_returns_false()
	{
		$this->assertFalse($this->uut->isNull(), "Base entity must report itself as not null!");
	}

	/**
	 * @test
	 */
	public function flat_collection_handles_non_base_entities() {
		$collection = array(
			1,2,"three"
		);
		$this->assertEquals($this->uut->flatCollection($collection), $collection);
	}

	/**
	 * @test
	 */
	public function as_doctrine_property_returns_self()
	{
		$this->assertEquals($this->uut, $this->uut->asDoctrineProperty(), "asDoctrineProperty must return object");
	}

	/**
	 * @test
	 */
	public function add_self_to()
	{
		$collection = new ArrayCollection();
		$this->uut->addSelfTo($collection);
		$this->assertTrue($collection->contains($this->uut), "addSelfTo should append object into collection parameter");
	}

	/**
	 * @test
	 */
	public function null_get_returns_entity_if_not_null()
	{
		$this->assertEquals($this->uut, $this->uut->nullGet('Application\Entity\Base', $this->uut));
	}

	/**
	 * @test
	 */
	public function ts_set_returns_datetime()
	{
		$this->assertInstanceOf('\DateTime', $this->uut->tsSet('now'));
		$this->assertInstanceOf('\DateTime', $this->uut->tsSet(new \DateTime()));
	}

	/**
	 * @test
	 * @expectedException \Exception
	 */
	public function ts_set_throws_exception_if_timestamp_is_nonsense()
	{
		$this->uut->tsSet("Eleventy Three Nonses");
	}

	/**
	 * @test
	 * @expectedException \InvalidArgumentException
	 */
	public function ts_set_throws_invalid_argument_exception_if_not_passed_datetime_or_string()
	{
		$this->uut->tsSet(90);
	}

	/**
	 * @test
	 */
	public function ts_get_returns_datetime()
	{
		$this->assertInstanceOf('\DateTime', $this->uut->tsGet(null));
		$this->assertInstanceOf('\DateTime', $this->uut->tsGet(new \DateTime()));
	}

	/**
	 * @test
	 */
	public function to_string_json_encodes_flatten_result()
	{
		$flattenReturnValue = array('id' => 1);
		// set a mock for the flatten method so we can track that it was invoked
		$base = $this->getMockForAbstractClass('Application\Entity\Base', array(), '', true, true, true, array('flatten'));
		$base->expects($this->once())
			->method('flatten')
			->will($this->returnValue($flattenReturnValue));
		$result = (string)$base;
		$this->assertEquals(json_encode($flattenReturnValue), $result);
	}



}