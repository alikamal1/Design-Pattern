<?php 

namespace Creational\Prototype;

use function Creational\Factory_Method\clientCode as CreationalClientCode;

/**
 * Prototype Design Pattern
 * let you copy existing objects without making your code dependent on their classes
 */

class Author
{
    private $name;
    private $pages = [];

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function addToPage(Page $page): void
    {
        $this->pages[] = $page;
    }
}

class Page 
{
    private $title;
    private $body;
    private $author;
    private $comments = [];
    private $date;
    
    public function __construct(string $title, string $body, Author $author) {
        $this->title = $title;
        $this->body = $body;
        $this->author = $author;
        $this->author->addToPage($this);
        $this->date = new \DateTime;
    }

    public function addComment(string $comment): void
    {
        $this->comments[] = $comment;
    }

    public function __clone()
    {
        $this->title = "Copy of " . $this->title;
        $this->author->addToPage($this);
        $this->comments = [];
        $this->date = new \DateTime;
    }
}

function clientCode()
{
    $author = new Author("Ali Kamal");
    $page = new Page("Tip of the day", "Keep calm and carry on", $author);
    $page->addComment("Nice tip, thanks");

    $draft = clone $page;
    print_r($draft);
}

clientCode();

/*
Dump of the clone. Note that the author is now referencing two objects.

RefactoringGuru\Prototype\RealWorld\Page Object
(
    [title:RefactoringGuru\Prototype\RealWorld\Page:private] => Copy of Tip of the day
    [body:RefactoringGuru\Prototype\RealWorld\Page:private] => Keep calm and carry on.
    [author:RefactoringGuru\Prototype\RealWorld\Page:private] => RefactoringGuru\Prototype\RealWorld\Author Object
        (
            [name:RefactoringGuru\Prototype\RealWorld\Author:private] => John Smith
            [pages:RefactoringGuru\Prototype\RealWorld\Author:private] => Array
                (
                    [0] => RefactoringGuru\Prototype\RealWorld\Page Object
                        (
                            [title:RefactoringGuru\Prototype\RealWorld\Page:private] => Tip of the day
                            [body:RefactoringGuru\Prototype\RealWorld\Page:private] => Keep calm and carry on.
                            [author:RefactoringGuru\Prototype\RealWorld\Page:private] => RefactoringGuru\Prototype\RealWorld\Author Object
 *RECURSION*
                            [comments:RefactoringGuru\Prototype\RealWorld\Page:private] => Array
                                (
                                    [0] => Nice tip, thanks!
                                )

                            [date:RefactoringGuru\Prototype\RealWorld\Page:private] => DateTime Object
                                (
                                    [date] => 2018-06-04 14:50:39.306237
                                    [timezone_type] => 3
                                    [timezone] => UTC
                                )

                        )

                    [1] => RefactoringGuru\Prototype\RealWorld\Page Object
 *RECURSION*
                )

        )

    [comments:RefactoringGuru\Prototype\RealWorld\Page:private] => Array
        (
        )

    [date:RefactoringGuru\Prototype\RealWorld\Page:private] => DateTime Object
        (
            [date] => 2018-06-04 14:50:39.306272
            [timezone_type] => 3
            [timezone] => UTC
        )

)
*/