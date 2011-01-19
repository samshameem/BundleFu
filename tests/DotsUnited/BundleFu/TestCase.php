<?php
/**
 * DotsUnited\BundleFu
 *
 * LICENSE
 *
 * This source file is subject to the BSD license that is available
 * through the world-wide-web at this URL:
 * https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE
 *
 * @category   Du
 * @package    Dubundle
 * @subpackage UnitTests
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE New BSD License
 */

namespace DotsUnited\BundleFu;

/**
 * @category   Du
 * @package    Dubundle
 * @subpackage UnitTests
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE New BSD License
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Bundle
     */
    protected $bundle;

    public function setUp()
    {
        $this->bundle = new Bundle();
        $this->bundle->setDocRoot(__DIR__ . '/_files');
    }

    public function tearDown()
    {
        $this->purgeCache();
        $this->bundle = null;
    }

    /**************************************************************************/

    public function assertFileMatch($filename, $needle, $message = null)
    {
        if (null === $message) {
            $message = "expected {$filename} to match {$needle}, but doesn't.";
        }

        $this->assertFileExists($filename);
        $this->assertRegExp('/' . preg_quote($needle, '/') . '/', file_get_contents($filename), $message);
    }

    public function assertFileNotMatch($filename, $needle, $message = null)
    {
        if (null === $message) {
            $message = "expected {$filename} to not match {$needle}, but does.";
        }

        $this->assertFileExists($filename);
        $this->assertNotRegExp('/' . preg_quote($needle, '/') . '/', file_get_contents($filename), $message);
    }

    protected function appendToFile($filename, $content)
    {
        $this->assertFileExists($filename);
        file_put_contents($filename, $content, FILE_APPEND);
    }

    protected function purgeCache()
    {
        $paths = array(
            __DIR__ . '/_files/css/cache',
            __DIR__ . '/_files/js/cache'
        );

        foreach ($paths as $path) {
            if (!file_exists($path)) {
                continue;
            }

            foreach (glob($path . '/*') as $file) {
                if ($file[0] == '.') {
                    continue;
                }
                unlink($file);
            }

            rmdir($path);
        }
    }

    protected function includeSome()
    {
        return '<script src="/js/js_1.js?1000" type="text/javascript"></script>' . "\n" .
               '<script src="/js/js_2.js?1000" type="text/javascript"></script>' . "\n" .
               '<link href="/css/css_1.css?1000" media="screen" rel="stylesheet" type="text/css">' . "\n" .
               '<link href="/css/css_2.css?1000" media="screen" rel="stylesheet" type="text/css">';
    }

    protected function includeAll()
    {
        return $this->includeSome() . "\n" .
               '<script src="/js/js_3.js?1000" type="text/javascript"></script>' . "\n" .
               '<link href="/css/css_3.css?1000" media="screen" rel="stylesheet" type="text/css" />';
    }
}
