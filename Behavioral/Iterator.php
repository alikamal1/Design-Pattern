<?php

namespace Behavioral\Iterator;

/**
 * Iterator Design Pattern
 * provide a way to access the element of an aggregate object without exposing its underlying representation
 */

class CsvIterator implements \Iterator
{
    const ROW_SIZE = 4096;
    protected $filePointer = null;
    protected $currentElement = null;
    protected $rowCounter = null;
    protected $delimiter = null;

    public function __counstruct($file, $delimiter = ',')
    {
        try {
            $this->filePointer = fopen($file, 'rb');
            $this->delimiter = $delimiter;
        } catch (\Exception $e) {
            throw new \Exception('The file "' . $file . '" cannot be read');
        }
    }

    public function rewind(): void
    {
        $this->rowCounter = 0;
        rewind($this->filePointer);
    }

    public function current(): array
    {
        $this->currentElement = fgetcsv($this->filePointer, self::ROW_SIZE, $this->delimiter);
        $this->rowCounter++;
        return $this->currentElement;
    }

    public function next(): bool
    {
        if(is_resource($this->filePointer)) {
            return !feof($this->filePointer);
        }
        return false;
    }

    public function valid(): bool
    {
        if(!$this->next()) {
            if(is_resource($this->filePointer)) {
                fclose($this->filePointer);
            }
            return false;
        }
        return true;
    }
}

$csv = new CsvIterator(__DIR__, '/cats.csv');

foreach($csv as $key => $row) {
    print_r($row);
}

/*
Name,Age,Owner,Breed,Image,Color,Texture,Fur,Size
Steve,3,Alexander Shvets,Bengal,/cats/bengal.jpg,Brown,Stripes,Short,Medium
Siri,2,Alexander Shvets,Domestic short-haired,/cats/domestic-sh.jpg,Black,Solid,Medium,Medium
Fluffy,5,John Smith,Maine Coon,/cats/Maine-Coon.jpg,Gray,Stripes,Long,Large

Array
(
    [0] => Name
    [1] => Age
    [2] => Owner
    [3] => Breed
    [4] => Image
    [5] => Color
    [6] => Texture
    [7] => Fur
    [8] => Size
)
Array
(
    [0] => Steve
    [1] => 3
    [2] => Alexander Shvets
    [3] => Bengal
    [4] => /cats/bengal.jpg
    [5] => Brown
    [6] => Stripes
    [7] => Short
    [8] => Medium
)
Array
(
    [0] => Siri
    [1] => 2
    [2] => Alexander Shvets
    [3] => Domestic short-haired
    [4] => /cats/domestic-sh.jpg
    [5] => Black
    [6] => Solid
    [7] => Medium
    [8] => Medium
)
Array
(
    [0] => Fluffy
    [1] => 5
    [2] => John Smith
    [3] => Maine Coon
    [4] => /cats/Maine-Coon.jpg
    [5] => Gray
    [6] => Stripes
    [7] => Long
    [8] => Large
)
/*