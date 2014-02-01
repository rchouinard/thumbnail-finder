Thumbnail Finder
================

Quick project I threw together as part of a larger project. While writing a
content aggregator I found I needed a way to discover a thumbnail for the
linked content.

This library only discovers the "best" image URL to use. Actually downloading
and cropping/resizing/otherwise processing the image is out of scope.


How does it work?
-----------------

The library simply scans an HTML document for an image suitable for use as a
thumbnail and return the URL to that image. Suitable images are discovered
via OpenGraph metadata, the older Facebook image_src microformat, and by
crawling the document for img tags and using a simple algorithm for finding the
"most interesting" image.

When the library has to resort to crawling the linked images, it downloads only
as much of each image as is required to extract the image width and height. The
library discards very small images and images which are much wider than tall or
taller than wide. The remaining images are compared based on area, and the
largest image is returned.

This library currently uses Symfony's Dom Crawler and CSS Selector components to
work its magic. The Imagick extension for PHP is also required for extracting
width and height from partial image downloads.


Usage
-----

Very simple:

```php
<?php

$tf = new \Rych\ThumbnailFinder\ThumbnailFinder();
$thumbUrl = $tf->findThumbnail('http://www.imdb.com/title/tt0117500/');

// Outputs the image URL or NULL
//   (though in this case you'll get a link to a movie poster for "The Rock").
var_dump($thumbUrl);
```


Installation
------------

Composer:

```bash
composer require rych/thumbnail-finder:0.1.*
```


To-do
-----

This works for my purposes but there's always room to improve.

* To start, better error detection and logging would be nice.
* The HTML and image downloading could probably use some work, but I didn't want
  to have a large HTTP client library dependency. Right now I just use `fopen()`
  and `file_get_contents()`. I'm such a horrible person.
