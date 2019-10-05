<?php

namespace Structural\Bridge;

/**
 * Facade Design Pattern
 * provide unified interface to set of interfaces in subsystem. Facade defines higher-level interface that makes the subsystem easier to use
 */

class YouTube
{
    public function fetchVideo(): string
    {
        return "";
    }

    public function saveAs(string $path): void
    {
        /** */
    }
}

class FFMpeg
{
    public function create(): FFMpeg
    {
        return new FFMpeg();
    }

    public function open(string $video): void
    {
        /** */
    }
}

Class FFMpegVideo
{
    public function filters(): self
    {
        return FFMpegVideo();
    }
    public function resize(): self
    {
        return FFMpegVideo();
    }
    public function synchronize(): self
    {
        return FFMpegVideo();
    }
    public function frame(): self
    {
        return FFMpegVideo();
    }
    public function save(string $path): self
    {
        return FFMpegVideo();
    }
}

class YouTubeDownloader
{
    protected $youtube;
    protected $ffmpeg;

    public function __construct(string $youtubeApiKey)
    {
        $this->youtube = new YouTube($youtubeApiKey);
        $this->ffmpeg = new FFMpeg;
    }

    public function downloadVideo(string $url): void
    {
        echo "Fetching video metadata from youtbe";
        $title = $this->youtube->fetchVideo($url)->getTitle();
        echo "Saving video file to a temporary file";
        $this->youtube->saveAs($url, "video.mpg");
        echo "Processing source video";
        $video = $this->ffmpeg->open("video.mpg");
        echo "Normalizing and resizing the video to smaller dimensions";
        $video->filters()->resize(new FFMpeg\Coordinate\Dimension(320,240))->synchronize();
        echo "Capturing preview image";
        $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(10))->save($title . 'frame.jpg');
        echo "Saving video in target formats";
        $video->save(new FFMpeg\Format\Video\X264, $title . '.mp4');
        $video->save(new FFMpeg\Format\Video\WMV, $title . '.wmv');
        $video->save(new FFMpeg\Format\Video\WebM, $title . '.webm');
        echo "Done";
    }
}

function clientCode(YouTubeDownloader $facade)
{
    $facade->downloadVideo("http://www.youtube.com/watch?v=");
}

$facade = new YouTubeDownloader("APIKEY-XXXXXXXX");
clientCode($facade);

/*
Fetching video metadata from youtube...
Saving video file to a temporary file...
Processing source video...
Normalizing and resizing the video to smaller dimensions...
Capturing preview image...
Saving video in target formats...
Done!
*?