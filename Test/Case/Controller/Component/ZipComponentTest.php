<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('ZipComponent', 'Controller/Component');

/**
 * ZipComponent Test Case
 *
 * @property ZipComponent Zip
 */
class ZipComponentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
	);
	private $tmp_filename;

	/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->resetZipComponent();
	}

	private function resetZipComponent()
	{
		$Collection = new ComponentCollection();
		$this->Zip = new ZipComponent($Collection);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Zip);
		parent::tearDown();
	}

/**
 * testAddFile method
 *
 * @return void
 */
	public function testAddFile() {

		$this->openTmpZip();

		$image = 'test.jpg';
		$image_filename = TESTS . 'Fixture' . DS . 'binary' . DS . $image;

		$this->assertEquals($this->Zip->addFile($image_filename, $image), true);
		$this->assertEquals($this->Zip->end(), true);

		$this->resetZipComponent();

		$this->assertEquals($this->Zip->begin($this->tmp_filename, false), true);
		$this->assertTrue($this->Zip->find($image) !== false);
		$this->assertEquals($this->Zip->end(), true);
	}

	private function openTmpZip()
	{
		$this->tmp_filename = tempnam(sys_get_temp_dir(), 'ziptest');
		$this->assertEquals($this->Zip->begin($this->tmp_filename), true);
	}

/**
 * testAddByContent method
 *
 * @return void
 */
	public function testAddByContent() {
		$this->openTmpZip();

		$filename = 'test.txt';
		$this->Zip->addByContent($filename, 'this is a test');
		$this->Zip->end();

		$this->resetZipComponent();

		$this->assertEquals($this->Zip->begin($this->tmp_filename, false), true);
		$extract_path = sys_get_temp_dir().DS.(microtime(true)*10000).'test'.DS;
		$this->Zip->extract($extract_path);
		$this->assertTrue(file_exists($extract_path.$filename));
		unlink($extract_path.$filename);
		$this->Zip->end();
	}

/**
 * testAddDirectory method
 *
 * @return void
 */
	public function testAddDirectory() {
		$this->openTmpZip();
		$dir_path = sys_get_temp_dir().DS.'test'.(microtime(true)*10000).DS;
		$filename = 'testadddir.txt';
		mkdir($dir_path);
		fwrite(fopen($dir_path.$filename, 'w'),'testtest');

		$this->Zip->addDir($dir_path, 'test');
		$this->Zip->end();
		$this->resetZipComponent();
		$this->assertEquals($this->Zip->begin($this->tmp_filename, false), true);
		$this->assertTrue($this->Zip->find('test'.DS.$filename) !== false);
		$this->assertEquals($this->Zip->end(), true);

	}

/**
 * testUndo method
 *
 * @return void
 */
	public function testUndo() {
		$this->openTmpZip();

		$image = 'test.jpg';
		$image_filename = TESTS . 'Fixture' . DS . 'binary' . DS . $image;

		$this->assertEquals($this->Zip->addFile($image_filename, $image), true);
		$this->Zip->undo('all');
		$this->assertEquals($this->Zip->end(), true);

		$this->resetZipComponent();

		$this->assertEquals($this->Zip->begin($this->tmp_filename, false), true);
		$this->assertTrue($this->Zip->find($image) == false);
		$this->assertEquals($this->Zip->end(), true);
	}

/**
 * testRename method
 *
 * @return void
 */
	public function testRename() {
		$this->openTmpZip();

		$image = 'test.jpg';
		$image_changed = 'test2.jpg';
		$image_filename = TESTS . 'Fixture' . DS . 'binary' . DS . $image;

		$this->assertEquals($this->Zip->addFile($image_filename, $image), true);
		$this->Zip->rename($image,$image_changed);
		$this->assertEquals($this->Zip->end(), true);

		$this->resetZipComponent();

		$this->assertEquals($this->Zip->begin($this->tmp_filename, false), true);
		$this->assertTrue($this->Zip->find($image_changed) !== false);
		$this->assertEquals($this->Zip->end(), true);
	}

/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {
		$this->openTmpZip();

		$image = 'test.jpg';
		$image_filename = TESTS . 'Fixture' . DS . 'binary' . DS . $image;

		$this->assertEquals($this->Zip->addFile($image_filename, $image), true);
		$this->Zip->delete($image);
		$this->assertEquals($this->Zip->end(), true);

		$this->resetZipComponent();

		$this->assertEquals($this->Zip->begin($this->tmp_filename, false), true);
		$this->assertTrue($this->Zip->find($image) == false);
		$this->assertEquals($this->Zip->end(), true);
	}

/**
 * testComment method
 *
 * @return void
 */
	public function testComment() {
		$this->openTmpZip();

		$image = 'test.jpg';
		$image_filename = TESTS . 'Fixture' . DS . 'binary' . DS . $image;

		$test_comment = 'test';

		$this->assertEquals($this->Zip->addFile($image_filename, $image), true);
		$this->assertEquals($this->Zip->setComment($image, $test_comment), true);
		$this->assertEquals($this->Zip->getComment($image), $test_comment);

	}

/**
 * testStats method
 *
 * @return void
 */
	public function testStats() {
		$this->openTmpZip();

		$image = 'test.jpg';
		$image_filename = TESTS . 'Fixture' . DS . 'binary' . DS . $image;

		$this->assertEquals($this->Zip->addFile($image_filename, $image), true);
		$stat = $this->Zip->stats($image);
		$this->assertEquals($stat['name'], $image);
		$this->assertEquals($stat['size'], filesize($image_filename));
	}

}
