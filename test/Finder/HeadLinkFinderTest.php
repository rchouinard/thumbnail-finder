<?php

namespace Rych\ThumbnailFinder\Finder;

use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\DomCrawler\Crawler;

class OpenGraphFinderTest extends TestCase
{

    /**
     * @var \Rych\ThumbnailFinder\Finder\OpenGraphFinder
     */
    protected $finder;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->finder = new OpenGraphFinder();
        parent::setUp();
    }

    /**
     * @test
     * @return void
     */
    public function finderFindsStandardOpenGraphImage()
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html xmlns:og="http://ogp.me/ns#">
<head>
  <meta charset="utf-8">
  <meta property="og:image" content="http://example.com/assets/thumbnail.jpg">
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
    public function finderFindsAlternateOpenGraphImage()
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html xmlns:og="http://ogp.me/ns#">
<head>
  <meta charset="utf-8">
  <meta name="og:image" content="http://example.com/assets/thumbnail.jpg">
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
    public function finderReturnsNullWhenNoOpenGraphImage()
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
