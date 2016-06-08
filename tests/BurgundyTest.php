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

use Http\Mock\Client as HttpClient;
use Http\Message\MessageFactory\GuzzleMessageFactory as MessageFactory;

class BurgundyTest extends \PHPUnit_Framework_TestCase
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
     * Test Burgundy class instantiation to PASS
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
     * Test Burgundy + HTTP client instantiation to PASS
     *
     * @access  public
     * @return  Burgundy
     */
    public function testCreateBurgundyHttpClientPass()
    {
        $message = new MessageFactory;
        $client = new HttpClient($message);

        // testReadAtom10UrlPass
        $client->addResponse(\GuzzleHttp\Psr7\parse_response(file_get_contents(__DIR__.'/responses/atom10')));

        // testReadRSS090UrlPass
        $client->addResponse(\GuzzleHttp\Psr7\parse_response(file_get_contents(__DIR__.'/responses/rss090')));

        // testReadRSS10UrlPass
        $client->addResponse(\GuzzleHttp\Psr7\parse_response(file_get_contents(__DIR__.'/responses/rss10')));

        // testReadRSS20UrlPass
        $client->addResponse(\GuzzleHttp\Psr7\parse_response(file_get_contents(__DIR__.'/responses/rss20')));

        $burgundy = Burgundy::create($client, $message);

        $this->assertInstanceOf(Burgundy::class, $burgundy);

        return $burgundy;
    }

    /**
     * Test read Atom 1.0 feed from file to PASS
     *
     * @depends testInputFilesPass
     * @depends testCreateBurgundyPass
     *
     * @access  public
     * @param   array    $files
     * @param   Burgundy $burgundy
     * @return  void
     */
    public function testReadAtom10FilePass(array $files, Burgundy $burgundy)
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
     * Test read RSS 0.90 feed from file to PASS
     *
     * @depends testInputFilesPass
     * @depends testCreateBurgundyPass
     *
     * @access  public
     * @param   array    $files
     * @param   Burgundy $burgundy
     * @return  void
     */
    public function testReadRSS090FilePass(array $files, Burgundy $burgundy)
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
     * Test read RSS 1.0 feed from file to PASS
     *
     * @depends testInputFilesPass
     * @depends testCreateBurgundyPass
     *
     * @access  public
     * @param   array    $files
     * @param   Burgundy $burgundy
     * @return  void
     */
    public function testReadRSS10FilePass(array $files, Burgundy $burgundy)
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
     * Test read RSS 2.0 feed from file to PASS
     *
     * @depends testInputFilesPass
     * @depends testCreateBurgundyPass
     *
     * @access  public
     * @param   array    $files
     * @param   Burgundy $burgundy
     * @return  void
     */
    public function testReadRSS20FilePass(array $files, Burgundy $burgundy)
    {
        $input = new SplFileInfo($files['rss20']);

        $stories = $burgundy->read($input);

        $this->assertInternalType('array', $stories);
        $this->assertCount(5, $stories);

        foreach ($stories as $story) {
            $this->assertEquals('RSS', $story->getSpec());
        }
    }

    /**
     * Test read Atom 1.0 feed from URL to PASS
     *
     * @depends testCreateBurgundyHttpClientPass
     *
     * @access  public
     * @param   Burgundy $burgundy
     * @return  void
     */
    public function testReadAtom10UrlPass(Burgundy $burgundy)
    {
        $stories = $burgundy->read('http://foo.bar/atom10.xml');

        $this->assertInternalType('array', $stories);
        $this->assertCount(15, $stories);

        foreach ($stories as $story) {
            $this->assertEquals('Atom', $story->getSpec());
        }
    }

    /**
     * Test read RSS 0.90 feed from URL to PASS
     *
     * @depends testCreateBurgundyHttpClientPass
     *
     * @access  public
     * @param   Burgundy $burgundy
     * @return  void
     */
    public function testReadRSS090UrlPass(Burgundy $burgundy)
    {
        $stories = $burgundy->read('http://foo.bar/rss090.xml');

        $this->assertInternalType('array', $stories);
        $this->assertCount(15, $stories);

        foreach ($stories as $story) {
            $this->assertEquals('RDF', $story->getSpec());
        }
    }

    /**
     * Test read RSS 1.0 feed from URL to PASS
     *
     * @depends testCreateBurgundyHttpClientPass
     *
     * @access  public
     * @param   Burgundy $burgundy
     * @return  void
     */
    public function testReadRSS10UrlPass(Burgundy $burgundy)
    {
        $stories = $burgundy->read('http://foo.bar/rss10.xml');

        $this->assertInternalType('array', $stories);
        $this->assertCount(15, $stories);

        foreach ($stories as $story) {
            $this->assertEquals('RDF', $story->getSpec());
        }
    }

    /**
     * Test read RSS 2.0 feed from URL to PASS
     *
     * @depends testCreateBurgundyHttpClientPass
     *
     * @access  public
     * @param   Burgundy $burgundy
     * @return  void
     */
    public function testReadRSS20UrlPass(Burgundy $burgundy)
    {
        $stories = $burgundy->read('http://foo.bar/rss20.xml');

        $this->assertInternalType('array', $stories);
        $this->assertCount(5, $stories);

        foreach ($stories as $story) {
            $this->assertEquals('RSS', $story->getSpec());
        }
    }

    /**
     * Test read from URL to FAIL (HttpClient + MessageFactory not set)
     *
     * @depends                   testCreateBurgundyPass
     * @expectedException         \Impensavel\Ron\RonException
     * @expectedExceptionMessage  HttpClient and MessageFactory required to fetch data from a URL
     *
     * @access  public
     * @param   Burgundy $burgundy
     * @return  void
     */
    public function testReadUrlFail(Burgundy $burgundy)
    {
        $burgundy->read('http://foo.bar/feed.xml');
    }
}
