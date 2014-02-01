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

use \Symfony\Component\DomCrawler\Crawler;

/**
 * Finder interface
 *
 * @package Rych\ThumbnailFinder
 * @author Ryan Chouinard <rchouinard@gmail.com>
 * @copyright Copyright (c) 2014, Ryan Chouinard
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */
interface FinderInterface
{

    /**
     * Find thumbnail image from DomCrawler instance
     *
     * @param \Symfony\Component\DomCrawler\Crawler $crawler Instance of
     *     Symfony's DomCrawler instantiated with the HTML document to search.
     * @return string|null Returns the URL of the thumbnail image. If image
     *     cannot be found, returns NULL.
     */
    public function findThumbnail(Crawler $crawler);

}
