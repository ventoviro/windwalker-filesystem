<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2008 - 2014 Asikart.com. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Windwalker\Filesystem\Test;

use Windwalker\Filesystem\Path;
use Windwalker\Filesystem\Path\PathLocator;
use Windwalker\Filesystem\Path\PathCollection;

/**
 * Tests for the PathCollection class.
 *
 * @since  {DEPLOY_VERSION}
 */
class PathCollectionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Property collection.
	 *
	 * @var PathCollection
	 */
	public $collection;
	
	/**
	 * setUp description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  setUpReturn
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	public function setUp()
	{
		$this->collection = new PathCollection();
	}
	
	/**
	 * Data provider for testClean() method.
	 *
	 * @return  array
	 *
	 * @since   {DEPLOY_VERSION}
	 */
	public function getPathData()
	{
		return array(
			// Input Path, Directory Separator, Expected Output
			'one path' => array(
				'/var/www/foo/bar',
				array(new PathLocator('/var/www/foo/bar'))
			),
			
			'paths with on key' => array(
				array(
					'/',
					'/var/www/foo/bar',
					'/var/www/joomla/bar/foo'
				),
				array(
					new PathLocator('/'),
					new PathLocator('/var/www/foo/bar'),
					new PathLocator('/var/www/joomla/bar/foo')
				)
			),
			
			'paths with key' => array(
				array(
					'root' => '/',
					'foo' => '/var/www/foo'
				),
				array(
					'root' => new PathLocator('/'),
					'foo' => new PathLocator('/var/www/foo')
				)
			)
		);
	}
	
	/**
	 * Data provider for testClean() method.
	 *
	 * @return  array
	 *
	 * @since   {DEPLOY_VERSION}
	 */
	public function getIteratorData()
	{
		return array(
			'no rescurive' => array(
				array(
					__DIR__ . '/files/folder1',
					__DIR__ . '/files/folder2'
				),
				
				array(
					Path::clean(__DIR__ . '/files/folder1'),
					Path::clean(__DIR__ . '/files/folder2')
				),
				
				false
			),
			/*
			'rescurive' => array(
				array(
					__DIR__ . 'files'
				),
				
				array(
					Path::clean(__DIR__ . 'files/folder1'),
					Path::clean(__DIR__ . 'files/folder1/file1'),
					Path::clean(__DIR__ . 'files/folder2/file2.html'),
					Path::clean(__DIR__ . 'files/file2.txt')
				),
				
				true
			)
			*/
		);
	}
	
	/**
	 * name description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  nameReturn
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	public function getIteratorRecursiveData()
	{
		return array(
			'no rescurive' => array(
				array(
					__DIR__ . '/files/folder1',
					__DIR__ . '/files/folder2',
					__DIR__ . '/files/file2.txt',
				),
				
				array(
					Path::clean(__DIR__ . '/files/folder1'),
					Path::clean(__DIR__ . '/files/folder2')
				),
				
				false
			),
			
			'rescurive' => array(
				array(
					__DIR__ . '/files'
				),
				
				array(
					Path::clean(__DIR__ . '/files/folder1'),
					Path::clean(__DIR__ . '/files/folder1/path1'),
					Path::clean(__DIR__ . '/files/folder2/file2.html'),
					Path::clean(__DIR__ . '/files/file2.txt')
				),
				
				true
			)
		);
	}
	
	/**
	 * test__construct description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  test__constructReturn
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	public function test__construct()
	{
		$collections = new PathCollection('/var/www/foo/bar');
		
		$paths = $collections->getPaths();
		
		$this->assertEquals(array(new PathLocator('/var/www/foo/bar')), $paths);
	}
	
	/**
	 * testAddPaths description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  testAddPathsReturn
	 *
	 * @dataProvider  getPathData
	 * 
	 * @since  {DEPLOY_VERSION}
	 */
	public function testAddPaths($paths, $expects)
	{
		$this->collection->addPaths($paths);
		
		$paths = $this->collection->getPaths();
		
		$this->assertEquals($paths, $expects);
	}
	
	/**
	 * addPath description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  addPathReturn
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	public function testAddPath()
	{
		$path = new PathLocator('/var/foo/bar');
		
		$this->collection->addPath($path, 'bar');
		
		$this->assertEquals($path, $this->collection->getPath('bar'));
	}
	
	/**
	 * removePath description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  removePathReturn
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	public function testRemovePath()
	{
		$path = new PathLocator('/var/foo/bar');
		
		$this->collection->addPath($path, 'bar');
		
		$this->collection->removePath('bar');
		
		$path = $this->collection->getPath('bar');
		
		$this->assertNull($path);
	}
	
	/**
	 * getPaths description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  getPathsReturn
	 *
	 * @dataProvider  getPathData
	 * 
	 * @since  {DEPLOY_VERSION}
	 */
	public function testGetPaths($paths, $expects)
	{
		$this->setUp();
		
		$this->collection->addPaths($paths);
		
		$paths = $this->collection->getPaths();
		
		$this->assertEquals($paths, $expects);
	}
	
	/**
	 * getPath description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  getPathReturn
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	public function testGetPath()
	{
		$path = new PathLocator('/var/foo/bar2');
		
		$this->collection->addPath($path, 'bar2');
		
		$this->assertEquals($path, $this->collection->getPath('bar2'));
		
		$this->assertEquals(new PathLocator('/'), $this->collection->getPath('bar3', '/'));
	}
	
	/**
	 * getIterator description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  getIteratorReturn
	 *
	 * @dataProvider  getIteratorData
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	public function testGetIterator($paths, $expects, $rescursive)
	{
		$this->setUp();
		
		$this->collection->addPaths($paths);
		
		$iterator = $this->collection;
		
		$compare = array();
		
		foreach($iterator as $file)
		{
			$compare[] = (string) $file;
		}
		
		$this->assertEquals($compare, $expects);
	}
	
	/**
	 * testDiresctoryIterator description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  testDiresctoryIteratorReturn
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	public function testGetDiresctoryIterator()
	{
		
	}
	
	/**
	 * setPrefix description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  setPrefixReturn
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	public function testSetPrefix()
	{
		$this->setUp();
		
		$this->collection->addPath('joomla/dir/foo/bar', 'foo');
		$this->collection->addPath('joomla/dir/yoo/hoo', 'yoo');

		$this->collection->setPrefix('/var/www');

		$expects = array(
			Path::clean('/var/www/joomla/dir/foo/bar'),
			Path::clean('/var/www/joomla/dir/yoo/hoo'),
		);

		$paths = array(
			(string) $this->collection->getPath('foo'),
			(string) $this->collection->getPath('yoo')
		);
		
		$this->assertEquals($paths, $expects);
	}
	
	/**
	 * find description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  findReturn
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	public function find()
	{
		
	}
	
	/**
	 * findAll description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  findAllReturn
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	public function findAll()
	{
		
	}
	
	/**
	 * toArray description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  toArrayReturn
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	public function toArray()
	{
		
	}
	
	/**
	 * getFiles description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  getFilesReturn
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	public function getFiles($rescursive = false)
	{
		
	}
	
	/**
	 * getFolders description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  getFoldersReturn
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	public function getFolders($rescursive)
	{
		
	}
	
	/**
	 * appendAll description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  appendAllReturn
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	public function appendAll()
	{
		
	}
	
	/**
	 * prependAll description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  prependAllReturn
	 *
	 * @since  {DEPLOY_VERSION}
	 */
	public function prependAll()
	{
		
	}
}
