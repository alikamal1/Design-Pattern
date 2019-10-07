<?php

namespace Behavioral\Command;

/**
 * Command Design Pattern
 * Turns request into stand-alone object that contain all information about the rquest. this transformation let you parameterize methods with different requests, delay or queue request's execution and support umdoable operations
 */

interface Command
{
    public function execute(): void;
    public function getId(): int;
    public function getStatus(): int;
}

abstract class WebScrapingCommand implements Command
{
    public $id;
    public $status = 0;
    public $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getStatus(): int
    {
        return $this->status;
    }
    public function getURL(): string
    {
        return $this->url;
    }

    public function execute(): void
    {
        $html = $this->download();
        $this->parse($html);
        $this->complete();
    }

    public function download(): string
    {
        $html = file_get_contents($this->getURL());
        echo "WebScrapingCommand: Downloaded {$this->url}";
        return $html;
    }

    abstract public function parse(string $html): void;

    public function complete(): void
    {
        $this->status = 1;
        Queue::get()->completeCommand($this);
    }
}

class IMDBGenresScrapingCommand extends WebScrapingCommand
{
    public function __construct()
    {
        $this->url = "http://www.imdb.com/feature/genre";
    }

    public function parse(string $html): void
    {
        preg_match_all("|href=\"(https://www.imdb.com/search/title\?genres=.*?)\"|", $html, $matches);
        echo "IMBGenresSCrapingCommand: Discovered " . count($matches[1]) . " genres.\n";

        foreach($matches[1] as $genre) {
            Queue::get()->add(new IMDBGenresScrapingCommand($genre));
        }
    }
}

class IMDBGenrePageScrapingCommand extends WebScrapingCommand
{
    private $page;
    public function __construct(string $url, int $page = 1)
    {
        parent::__construct($url);
        $this->page = $page;
    }

    public function getURL(): string
    {
        return $this->url . "?page=" . $this->page;
    }

    public function parse(string $html): void
    {
        preg_match_all("|href=\"(/title/.*?/)\?ref_=adv_li_tt\"|", $html, $matches);
        echo "IMDBGenrePageScrapingCommand: Discovered " . count($matches[1]) . " movies.\n";

        foreach($matches[1] as $moviePath) {
            $url = "http://www.imdb.com" . $moviePath;
            Queue::get()->add(new IMDBMovieScrapingCommand($url));
        }

        if(preg_match_all("|Next &#187;</a>|", $html)) {
            Queue::get()->add(new IMDBGenrePageScrapingCommand($this->url, $this->page + 1));
        }
    }
}

class IMDBMovieScrapingCommand extends WebScrapingCommand
{
    public function parse(string $html)
    {
        if(preg_match_all("|<h1 itemprop=\"name\" class=\"\">(.*?)</h1>|", $html, $matches))
        {
            $title = $matches[1];
        }
        echo "IMDBMovieScrapingCommand: parsed movie $title";
    }
}

class Queue
{
    private $db;

    public function __construct()
    {
        $this->db = new \SQLite3(__DIR__ . '/commands.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $this->db->queue('CREATE TABLE IF NOT EXISTS "command" ("id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, "command" TEXT, "status" INTEGER)');
    }
    public function isEmpty(): bool
    {
        $query = 'SELECT COUNT ("id") FROM "commands" WHERE status = 0';
        return $this->db->querySingle($query) === 0;
    }
    public function add(command $command): void
    {
        $query = 'INSERT INTO commands (command, status) VALUES (:command, :status)';
        $statement = $this->db->prepare($query);
        $statement->bindValue(":command", base64_encode(serialize($command)));
        $statement->bindValue(":status", $command->getStatus());
        $statement->execute();
    }
    public function getCommand(): command
    {
        $query = "SELECT * FROM 'commands' WHERE 'status' = 0 LIMIT 1";
        $record = $this->db->querySingle($query, true);
        $command = unserialize(base64_decode($record["command"]));
        $command->id = $record["id"];
        return $command;
    }
    public function completeCommand(Command $command): void
    {
        $query = "UPDATE commands SET status = :status WHERE id = :id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(":status", $command->getStatus());
        $statement->bindValue(":id", $command->getId());
        $statement->execute();
    }
    public function work(): void
    {
        while(!$this->isEmpty()) {
            $command = $this->getCommand();
            $command->execute();
        }
    }
    public function get(): Queue
    {
        static $instance;
        if(!$instance) {
            $instance = new Queue;
        }
        return $instance;
    }
}

$queue = Queue::get();
if($queue->isEmpty()) {
    $queue->add(new IMDBGenresScrapingCommand);
}
$queue->work();

/*
WebScrapingCommand: Downloaded https://www.imdb.com/feature/genre/
IMDBGenresScrapingCommand: Discovered 14 genres.
WebScrapingCommand: Downloaded https://www.imdb.com/search/title?genres=comedy
IMDBGenrePageScrapingCommand: Discovered 50 movies.
WebScrapingCommand: Downloaded https://www.imdb.com/search/title?genres=sci-fi
IMDBGenrePageScrapingCommand: Discovered 50 movies.
WebScrapingCommand: Downloaded https://www.imdb.com/search/title?genres=horror
IMDBGenrePageScrapingCommand: Discovered 50 movies.
WebScrapingCommand: Downloaded https://www.imdb.com/search/title?genres=romance
IMDBGenrePageScrapingCommand: Discovered 50 movies.
...
*/