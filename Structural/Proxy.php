<?php

namespace Structural\Proxy;

/**
 * Proxy Design Pattern
 * Provide a surrogate or placeholder for another object to contorl. access to the original object or add other responsibilities
 */

interface Downloader
{
    public function download(string $url): string;
}

class SimpleDownloader implements Downloader
{
    public function download(string $url): string
    {
        echo "Downloading file from the Internet";
        $result = file_get_contents($url);
        echo "Downloaded bytes: " . strlen($result);
        return $result;
    }
}

class CachingDowloader implements Downloader
{
    private $downloader;

    private $cache = [];

    public function __construct(SimpleDownloader $downloader)
    {
        $this->downloader = $downloader;
    }

    public function download(string $url): string
    {
        if(!isset($this->cache[$url])) {
            echo "CacheProxy MISS";
            $result = $this->downloader->download($url);
            $this->cache[$url] = $result;
        } else {
            echo "CacheProxy HIT. Retreiveing result from cache";
        }
        return $this->cache[$url];
    }
}

function clientCode(Downloader $subject)
{
    $result = $subject->download("http://example.com");
    $result = $subject->download("http://example.com");
}

echo "Excuting client code with real subject";
$realSubject = new SimpleDownloader;
clientCode($realSubject);

echo "Executing the same client code with a proxy";
$proxy = new CachingDowloader($realSubject);
clientCode($proxy);

/*
Executing client code with real subject:
Downloading a file from the Internet.
Downloaded bytes: 1270
Downloading a file from the Internet.
Downloaded bytes: 1270

Executing the same client code with a proxy:
CacheProxy MISS. Downloading a file from the Internet.
Downloaded bytes: 1270
CacheProxy HIT. Retrieving result from cache.
*/