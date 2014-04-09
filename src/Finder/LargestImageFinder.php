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

use Imagick;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Largest image finder
 *
 * @package Rych\ThumbnailFinder
 * @author Ryan Chouinard <rchouinard@gmail.com>
 * @copyright Copyright (c) 2014, Ryan Chouinard
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */
class LargestImageFinder implements FinderInterface
{

    /**
     * @var string
     */
    protected $useragent;

    /**
     * @var string
     */
    protected $referer;

    /**
     * @param string $useragent
     * @return void
     */
    public function __construct($useragent = 'Rych-ThumbnailFinder/1.0.0')
    {
        $this->setUserAgent($useragent);
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
        $maxSize = 0;
        $maxUrl = null;

        $urls = $this->getImgSrcList($crawler);
        foreach ($urls as $url) {
            if (!$geometry = $this->getRemoteImageGeometry($url)) {
                continue;
            }

            $area = $geometry['width'] * $geometry['height'];
            if ($area < 5000 || max($geometry) / min($geometry) > 1.5) {
                continue;
            }

            if (stripos($url, 'sprite') !== false) {
                $area /= 10;
            }

            if ($area > $maxSize) {
                $maxSize = $area;
                $maxUrl = $url;
            }
        }

        return $maxUrl;
    }

    /**
     * @param string $useragent
     * @return void
     */
    public function setUserAgent($useragent)
    {
        $this->useragent = (string) $useragent;
    }

    /**
     * @param string $referer
     * @return void
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;
    }

    /**
     * @param \Symfony\Component\DomCrawler\Crawler $crawler
     * @return array
     */
    protected function getImgSrcList(Crawler $crawler)
    {
        $imgCrawler = $crawler->filter('img[src]');

        $urls = array ();
        foreach ($imgCrawler as $node) {
            $url = $node->getAttribute('src');

            if (substr($url, 0, 2) == '//') {
                $url = 'http:' . $url;
            }

            if (!filter_var($url. FILTER_VALIDATE_URL) || stripos($url, 'http') !== 0) {
                continue;
            }

            $urls[] = $url;
        }

        return $urls;
    }

    /**
     * @param string $url
     * @return array|false
     */
    protected function getRemoteImageGeometry($url)
    {
        $geometry = false;

        $ctx = stream_context_create(array (
            'http' => array (
                'user_agent' => $this->useragent,
                'header' => sprintf('Referer: %s', $this->referer),
            ),
        ));

        if (!$fp = fopen($url, 'rb', false, $ctx)) {
            return false;
        }

        while ($data = fread($fp, 1024)) {
            if (!$imagick = $this->loadPartialImage($data)) {
                continue;
            }

            if (($geometry = $this->getImageGeometry($imagick))) {
                break;
            }
        }
        fclose($fp);

        return $geometry;
    }

    /**
     * @param Imagick $imagick
     * @return type
     */
    protected function getImageGeometry(Imagick $imagick)
    {
        if (($geometry = $imagick->getImageGeometry())) {
            $geometry = array (
                'width' => $geometry['width'],
                'height' => $geometry['height'],
            );
        }

        return $geometry;
    }

    /**
     * @param string $data
     * @return \Imagick|false
     */
    protected function loadPartialImage($data)
    {
        try {
            $imagick = new Imagick();
            $imagick->readImageBlob($data);
        } catch (\ImagickException $e) {
            $imagick = false;
        }

        return $imagick;
    }

}
