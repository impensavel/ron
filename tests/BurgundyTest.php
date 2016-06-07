<?php
/**
 * This file is part of the Ron library.
 *
 * @author     Quetzy Garcia <quetzyg@impensavel.com>
 * @copyright  2015-2016
 *
 * For the full copyright and license information,
 * please view the LICENSE.md file that was distributed
 * with this source code.
 */

namespace Impensavel\Ron;

use SplFileInfo;

use PHPUnit_Framework_TestCase;

class BurgundyTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test input file to PASS (readability)
     *
     * @access  public
     * @return  array
     */
    public function testInputFilesPass()
    {
        $files = [
            'atom10' => __DIR__ . '/input/atom10.xml',
            'rss10'  => __DIR__ . '/input/rss10.xml',
            'rss20'  => __DIR__ . '/input/rss20.xml',
            'rss090' => __DIR__ . '/input/rss090.xml',
        ];

        foreach ($files as $file) {
            $this->assertTrue(is_readable($file));
        }

        return $files;
    }

    /**
     * Test Burgundy class instantiation
     *
     * @access  public
     * @return  Burgundy
     */
    public function testCreateBurgundyPass()
    {
        $burgundy = Burgundy::create();

        $this->assertInstanceOf(Burgundy::class, $burgundy);

        return $burgundy;
    }

    /**
     * Test read Atom 1.0 to PASS
     *
     * @depends testInputFilesPass
     * @depends testCreateBurgundyPass
     *
     * @access  public
     * @param   array    $files
     * @param   Burgundy $burgundy
     * @return  void
     */
    public function testReadAtom10Pass(array $files, Burgundy $burgundy)
    {
        $input = new SplFileInfo($files['atom10']);

        $stories = $burgundy->read($input);

        $this->assertInternalType('array', $stories);
        $this->assertCount(15, $stories);

        foreach ($stories as $story) {
            $this->assertEquals('Atom', $story->getSpec());
        }
    }

    /**
     * Test read RSS 0.90 to PASS
     *
     * @depends testInputFilesPass
     * @depends testCreateBurgundyPass
     *
     * @access  public
     * @param   array    $files
     * @param   Burgundy $burgundy
     * @return  void
     */
    public function testReadRSS090Pass(array $files, Burgundy $burgundy)
    {
        $input = new SplFileInfo($files['rss090']);

        $stories = $burgundy->read($input);

        $this->assertInternalType('array', $stories);
        $this->assertCount(15, $stories);

        foreach ($stories as $story) {
            $this->assertEquals('RDF', $story->getSpec());
        }
    }

    /**
     * Test read RSS 1.0 to PASS
     *
     * @depends testInputFilesPass
     * @depends testCreateBurgundyPass
     *
     * @access  public
     * @param   array    $files
     * @param   Burgundy $burgundy
     * @return  void
     */
    public function testReadRSS10Pass(array $files, Burgundy $burgundy)
    {
        $input = new SplFileInfo($files['rss10']);

        $stories = $burgundy->read($input);

        $this->assertInternalType('array', $stories);
        $this->assertCount(15, $stories);

        foreach ($stories as $story) {
            $this->assertEquals('RDF', $story->getSpec());
        }
    }

    /**
     * Test read RSS 2.0 to PASS
     *
     * @depends testInputFilesPass
     * @depends testCreateBurgundyPass
     *
     * @access  public
     * @param   array    $files
     * @param   Burgundy $burgundy
     * @return  void
     */
    public function testReadRSS20Pass(array $files, Burgundy $burgundy)
    {
        $input = new SplFileInfo($files['rss20']);

        $stories = $burgundy->read($input);

        $this->assertInternalType('array', $stories);
        $this->assertCount(5, $stories);

        foreach ($stories as $story) {
            $this->assertEquals('RSS', $story->getSpec());
        }
    }
}
