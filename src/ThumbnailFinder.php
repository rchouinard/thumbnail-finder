<?php
/**
 * Ryan's Thumbnail Finder
 *
 * @package Rych\ThumbnailFinder
 * @author Ryan Chouinard <rchouinard@gmail.com>
 * @copyright Copyright (c) 2014, Ryan Chouinard
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */

namespace Rych\ThumbnailFinder;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Ryan's Thumbnail Finder
 *
 * @package Rych\ThumbnailFinder
 * @author Ryan Chouinard <rchouinard@gmail.com>
 * @copyright Copyright (c) 2014, Ryan Chouinard
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */
class ThumbnailFinder
{

    /**
     * @var string
     */
    protected $useragent;

    /**
     * @var array
     */
    protected $finders = array ();

    /**
     * Class constructor
     *
     * @param string $useragent The useragent string to send when fetching
     *     remote data.
     * @return void
     */
    public function __construct($useragent = 'Rych-ThumbnailFinder/1.0.0')
    {
        $this->loadDefaultFinders();
        $this->setUserAgent($useragent);
    }

    /**
     * Find thumbnail image from HTML page at given URL
     *
     * @param string $url The URL to search.
     * @return string|null Returns the URL of the thumbnail image. If image
     *     cannot be found, returns NULL.
     */
    public function findThumbnail($url)
    {
        $ctx = stream_context_create(array (
            'http' => array (
                'user_agent' => $this->useragent,
            ),
        ));

        $html = file_get_contents($url, false, $ctx);

        return $this->findThumbnailFromHtml($html, $url);
    }

    /**
     * Find thumbnail image from HTML document
     *
     * @param string $html The HTML document to search.
     * @param string $referer The document URL. This is used as the referer
     *     value when fetching remote image data.
     * @return string|null Returns the URL of the thumbnail image. If image
     *     cannot be found, returns NULL.
     */
    public function findThumbnailFromHtml($html, $referer = null)
    {
        array_walk($this->finders, function ($finder) use ($referer) {
            if ($finder instanceof Finder\LargestImageFinder) {
                $finder->setReferer((string) $referer);
            }
        });

        $crawler = new Crawler($html);
        foreach ($this->finders as $finder) {
            $thumbnail = $finder->findThumbnail($crawler);
            if ($thumbnail !== null) {
                break;
            }
        }

        return $thumbnail;
    }

    /**
     * Set the useragent string
     *
     * @param string $useragent The useragent string to send when fetching
     *     remote data.
     * @return void
     */
    public function setUserAgent($useragent)
    {
        $this->useragent = (string) $useragent;
        array_walk($this->finders, function ($finder) use ($useragent) {
            if ($finder instanceof Finder\LargestImageFinder) {
                $finder->setUserAgent((string) $useragent);
            }
        });
    }

    /**
     * @return void
     */
    protected function loadDefaultFinders()
    {
        $finder = new Finder\OpenGraphFinder();
        $this->finders[] = $finder;

        $finder = new Finder\HeadLinkFinder('image_src');
        $this->finders[] = $finder;

        $finder = new Finder\LargestImageFinder($this->useragent);
        $this->finders[] = $finder;
    }

}
