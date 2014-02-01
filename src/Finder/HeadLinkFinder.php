<?php
/**
 * Ryan's Thumbnail Finder
 *
 * @package Rych\ThumbnailFinder
 * @author Ryan Chouinard <rchouinard@gmail.com>
 * @copyright Copyright (c) 2014, Ryan Chouinard
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */

namespace Rych\ThumbnailFinder\Finder;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Head link finder
 *
 * @package Rych\ThumbnailFinder
 * @author Ryan Chouinard <rchouinard@gmail.com>
 * @copyright Copyright (c) 2014, Ryan Chouinard
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */
class HeadLinkFinder implements FinderInterface
{

    /**
     * @var string
     */
    protected $rel;

    /**
     * @param string $rel
     * @return void
     */
    public function __construct($rel = 'image_src')
    {
        $this->setLinkRel($rel);
    }

    /**
     * Find thumbnail image from DomCrawler instance
     * 
     * @param \Symfony\Component\DomCrawler\Crawler $crawler Instance of
     *     Symfony's DomCrawler instantiated with the HTML document to search.
     * @return string|null Returns the URL of the thumbnail image. If image
     *     cannot be found, returns NULL.
     */
    public function findThumbnail(Crawler $crawler)
    {
        // <link rel="image_src" ...>
        $isCrawler = $crawler->filter(sprintf('link[rel="%s"]', $this->rel));

        $url = null;
        if ($isCrawler->count() > 0) {
            $url = $isCrawler->first()->attr('href');
        }

        return $url;
    }

    /**
     * @param string $rel
     */
    public function setLinkRel($rel)
    {
        $this->rel = (string) $rel;
    }

}
