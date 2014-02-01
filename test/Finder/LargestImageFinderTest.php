<?php

namespace Rych\ThumbnailFinder\Finder;

use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\DomCrawler\Crawler;

class LargestImageFinderTest extends TestCase
{

    /**
     * @var \Rych\ThumbnailFinder\Finder\LargestImageFinder
     */
    protected $finder;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->finder = new LargestImageFinder();
        parent::setUp();
    }

    /**
     * @test
     * @return void
     */
    public function finderFindsLargestImage()
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Hello, World!</title>
</head>
<body>
  <h1>Hello, World!</h1>
  <ul>
    <li><img src="http://placehold.it/125x125"></li>
    <li><img src="http://placehold.it/250x250"></li>
    <li><img src="http://placehold.it/100x100"></li>
  </ul>
</body>
</html>
HTML;

        $crawler = new Crawler($html);
        $this->assertEquals('http://placehold.it/250x250', $this->finder->findThumbnail($crawler));
    }

    /**
     * @test
     * @return void
     */
    public function finderReturnsNullWhenNoLargeImage()
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Hello, World!</title>
</head>
<body>
  <h1>Hello, World!</h1>
  <ul>
    <!-- Should be ignored due to size -->
    <li><img src="http://placehold.it/25x25"></li>
  </ul>
</body>
</html>
HTML;

        $crawler = new Crawler($html);
        $this->assertNull($this->finder->findThumbnail($crawler));
    }

}
