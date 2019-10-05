<?php

namespace Structural\Bridge;

/**
 * Bridget Design Pattern
 * let you split large class or set of closely related classes into two separate hierarchies-abstraction and implementaion which can be developed independently of each other
 */

interface Renderer
{
    public function renderTitle(string $title): string;
    public function renderTextBlock(string $text): string;
    public function renderImage(string $url): string;
    public function renderLink(string $url, string $title): string;
    public function renderHeader(): string;
    public function renderFooter(): string;
    public function renderParts(array $parts): string;
}

class HTMLRenderer implements Renderer
{
    public function renderTitle(string $title): string
    {
        return "<h1>$title</h1>";
    }
    public function renderTextBlock(string $text): string
    {
        return "<div class='text'>$text</div>";
    }
    public function renderImage(string $url): string
    {
        return "<img src='$url'>";
    }
    public function renderLink(string $url, string $title): string
    {
        return "<a href='$url'>$title</a>";
    }
    public function renderHeader(): string
    {
        return "<html><body>";
    }
    public function renderFooter(): string
    {
        return "</body></html>";
    }
    public function renderParts(array $parts): string
    {
        return implode("\n", $parts);
    }
}

class JsonRenderer implements Renderer
{
    public function renderTitle(string $title): string
    {
        return '"title":"' . $title . '"';
    }
    public function renderTextBlock(string $text): string
    {
        return '"text":"' . $text . '"';
    }
    public function renderImage(string $url): string
    {
        return '"img":"' . $url . '"';
    }
    public function renderLink(string $url, string $title): string
    {
        return '"link": {"href": "' . $url . '", "title": "' . $title . '""}';
    }
    public function renderHeader(): string
    {
        return '';
    }
    public function renderFooter(): string
    {
        return '';
    }
    public function renderParts(array $parts): string
    {
        return "{\n" . implode(",\n", array_filter($parts)) . "\n";
    }
}

abstract class Page
{
    protected $renderer;

    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function changeRenderer(Renderer $renderer): void
    {
        $this->renderer = $renderer;
    }

    abstract public function view(): string;
}

class SimplePage extends Page
{
    protected $title;
    protected $content;

    public function __construct(Renderer $renderer, string $title, string $content)
    {
        parent::__construct($renderer);
        $this->title = $title;
        $this->content = $content;
    }

    public function view(): string
    {
        return $this->renderer->renderParts([
            $this->renderer->renderHeader(),
            $this->renderer->rendertitle($this->title),
            $this->renderer->renderTextBlock($this->content),
            $this->renderer->renderFooter(),
        ]);
    }
}

class Product
{
    private $id, $title, $description, $image, $price;

    public function __construct(
        string $id,
        string $title,
        string $description,
        string $image,
        float $price)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->price = $price;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImage(): string
    {
        return $this->iamge;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}

class ProductPage extends Page
{
    protected $product;

    public function __construct(Renderer $renderer, Product $product)
    {
        parent::__construct($renderer);
        $this->product = $product;
    }

    public function view(): string
    {
        return $this->renderer->renderParts([
            $this->renderer->renderHeader(),
            $this->renderer->rendertitle($this->product->getTitle),
            $this->renderer->renderTextBlock($this->product->getDescription),
            $this->renderer->renderImage($this->product->getImage),
            $this->renderer->renderLink("/cart/add/",$this->product->getId, "Add to cart"),
            $this->renderer->renderFooter(),
        ]);
    }
}

function clientCode(Page $page)
{
    echo $page->view();
}

$HTMLRenderer = new HTMLRenderer;
$JSONRenderer = new JsonRenderer;

$page = new SimplePage($HTMLRenderer, "Home", "Welcome to our website");
echo "HTML view of simple content page";
clientCode($page);

$page->changeRenderer($JSONRenderer);
echo "JSON view of simple content page, render with the same client code";
clientCode($page);

$product = new Product("123", "star wars, episode 1", "a long time agor in a galaxy far away...", "/imagews/star-wars.jpeg", 38.35);

$page = new ProductPage($HTMLRenderer, $product);

echo "HTML view of simple content page";
clientCode($page);

$page->changeRenderer($JSONRenderer);
echo "JSON view of simple content page, render with the same client code";
clientCode($page);


