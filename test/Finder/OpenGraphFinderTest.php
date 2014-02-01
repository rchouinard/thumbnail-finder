<?php

namespace Rych\ThumbnailFinder\Finder;

use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\DomCrawler\Crawler;

class HeadLinkFinderTest extends TestCase
{

    /**
     * @var \Rych\ThumbnailFinder\Finder\HeadLinkFinder
     */
    protected $finder;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->finder = new HeadLinkFinder();
        parent::setUp();
    }

    /**
     * @test
     * @return void
     */
    public function finderFindsDefaultLinkRel()
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="image_src" href="http://example.com/assets/thumbnail.jpg">
  <title>Hello, World!</title>
</head>
<body>
  <h1>Hello, World!</h1>
</body>
</html>
HTML;

        $crawler = new Crawler($html);
        $this->assertEquals('http://example.com/assets/thumbnail.jpg', $this->finder->findThumbnail($crawler));
    }

    /**
     * @test
     * @return void
     */
    public function finderFindsCustomLinkRel()
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="my_image" href="http://example.com/assets/thumbnail.jpg">
  <title>Hello, World!</title>
</head>
<body>
  <h1>Hello, World!</h1>
</body>
</html>
HTML;

        $crawler = new Crawler($html);
        $this->finder->setLinkRel('my_image');
        $this->assertEquals('http://example.com/assets/thumbnail.jpg', $this->finder->findThumbnail($crawler));
    }

    /**
     * @test
     * @return void
     */
    public function finderReturnsNullWhenNoLinkRelImage()
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
</body>
</html>
HTML;

        $crawler = new Crawler($html);
        $this->assertNull($this->finder->findThumbnail($crawler));
    }

}
