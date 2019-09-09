<?php

namespace Structural\Bridge;

abstract class FormElement
{
    protected $name;
    protected $title;
    protected $data;

    public function __construct(string $name, string $title)
    {
        $this->name = $name;
        $this->title = $title;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setData($data): void
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    abstract public function render(): string;
}

class Input extends FormElemnt
{
    private $type;

    public function __construct(string $name, string $title, string $type)
    {
        parent::__construct($name, $title);
        $this->type = $type;
    }

    public function render(): string
    {
        return "<label for=\"{$this->name}\">{$this->title}</label>\n" . "<input name=\"{$this->name}\" type=\"{$this->type}\" value=\"{$this->data}\"><br>";
    }
}

abstract class FieldComposite extends FormElement
{
    protected $fields = [];

    public function add(FormElement $field): void
    {
        $name = $field->getName();
        $this->fields[$name] = $field;
    }

    public function remove(FormElement $component): void
    {
        $this->fields = array_filter($this->fields, function($child) use ($component) {
            return $child != $component;
        });
    }
}