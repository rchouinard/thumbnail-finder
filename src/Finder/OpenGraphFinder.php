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
 * OpenGraph finder
 *
 * @package Rych\ThumbnailFinder
 * @author Ryan Chouinard <rchouinard@gmail.com>
 * @copyright Copyright (c) 2014, Ryan Chouinard
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */
class OpenGraphFinder implements FinderInterface
{

    /**
     * Find thumbnail image from DomCrawler instance
     * 
     * @param Symfony\Component\DomCrawler\Crawler $crawler Instance of
     *     Symfony's DomCrawler instantiated with the HTML document to search.
     * @return string|null Returns the URL of the thumbnail image. If image
     *     cannot be found, returns NULL.
     */
    public function findThumbnail(Crawler $crawler)
    {
        // <meta property="og:image" ...> or <meta name="og:image" ...>
        $ogCrawler = $crawler->filter('meta[property="og:image"]');
        if ($ogCrawler->count() == 0) {
            $ogCrawler = $crawler->filter('meta[name="og:image"]');
        }

        $url = null;
        if ($ogCrawler->count() > 0) {
            $url = $ogCrawler->first()->attr('content');
        }

        return $url;
    }

}
