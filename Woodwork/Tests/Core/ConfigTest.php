<?php
use Woodwork\Core\Config;
use org\bovigo\vfs\vfsStream;

class ConfigTest extends \PHPUnit_Framework_TestCase
{

	public function setUp()
	{

		$root = vfsStream::setup('home');
		vfsStream::newFile('test_config.cfg')->at($root)->setContent("ADAPTER=mysql
HOSTNAME=127.0.0.1
DATABASE=woodwork
USERNAME=root
PASSWORD=root
PORT=
BASEURL=http://localhost/woodwork
PASSWORD_SALT=12345");

		$this->url = vfsStream::url('home/test_config.cfg');
	}

  	/**
     * @covers            \Woodwork\Core\Config::__construct
     * @uses              \Woodwork\Core\Config
     * @expectedException \Exception 
     */
	public function testExceptionIsRaisedForInvalidConstructorArgument()
	{
		new Config(null);
	}

	/**
     * @covers            \Woodwork\Core\Config::__construct
     * @uses              \Woodwork\Core\Config
     * @expectedException \Exception 
     */
	public function testExceptionIsRaisedForConfigFileDoesNotExist()
	{

		$filename = 'config_file_not_found.cfg';
		$this->assertFileNotExists( $filename );

		new Config($filename);
	}

  	/**
     * @covers            \Woodwork\Core\Config::readConfigFromFile
     * @uses              \Woodwork\Core\Config
     * @expectedException \Exception 
     */
	public function testExceptionIsRaisedForConfigFileMissingEquals()
	{

		$root = vfsStream::setup('home');
		vfsStream::newFile('invalid_test_config.cfg')->at($root)->setContent("ADAPTER=mysql
HOSTNAME127.0.0.1
DATABASE=woodwork
USERNAME=root
PASSWORD=root
PORT=
BASEURL=http://localhost/woodwork
PASSWORD_SALT=12345");

		$url = vfsStream::url('home/invalid_test_config.cfg');

		new Config($url);

	}

  	/**
     * @covers            \Woodwork\Core\Config::readConfigFromFile
     * @uses              \Woodwork\Core\Config
     * @expectedException \Exception 
     */
	public function testExceptionIsRaisedForConfigFileMissingNewLine()
	{

		$root = vfsStream::setup('home');
		vfsStream::newFile('invalid_test_config2.cfg')->at($root)->setContent("ADAPTER=mysql
HOSTNAME=127.0.0.1DATABASE=woodwork
USERNAME=root
PASSWORD=root
PORT=
BASEURL=http://localhost/woodwork
PASSWORD_SALT=12345");

		$url = vfsStream::url('home/invalid_test_config2.cfg');

		new Config($url);
	}

	/**
	 * @covers 			\Woodwork\Core\Config::__construct
	 * @uses   			\Woodwork\Core\Config
	 */
	public function testObjectIsConstructedForValidConstructorArguments()
	{

		$config = new Config( $this->url );
		$this->assertInstanceOf('\\Woodwork\\Core\\Config', $config);

	}

	/**
	 * @covers 			\Woodwork\Core\Config::__get
	 * @uses 			\Woodwork\Core\Config
	 * @dataProvider	configKeyProvider
	 */
	public function testGetConfigValue( $key, $expected )
	{
		$config = new Config( $this->url );
		$this->assertEquals( $config->$key, $expected);
	}

	/**
	 * @covers 			\Woodwork\Core\Config::getArray
	 * @uses 			\Woodwork\Core\Config
	 * @dataProvider	configKeyProvider
	 */
	public function testConfigValuesWithGetArray( $key, $value )
	{
		$config = new Config( $this->url );
		$array = $config->getArray();

		$this->assertTrue(is_array($array));
		$this->assertEquals($array[$key], $value);
	}

	public function configKeyProvider()
	{
		return array(
			array( 'hostname', '127.0.0.1' ),
			array( 'username', 'root' ),
			array( 'password', 'root' ),
			array( 'port', '' ),
			array( 'baseurl', 'http://localhost/woodwork' ),
			array( 'password_salt', '12345' )
		);
	}

}