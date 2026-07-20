# Uncomplicated XML
[![Build Status](https://github.com/josemmo/uxml/workflows/CI/badge.svg)](https://github.com/josemmo/uxml/actions)
[![Latest Version](https://img.shields.io/packagist/v/josemmo/uxml)](https://packagist.org/packages/josemmo/uxml)
[![Minimum PHP Version](https://img.shields.io/packagist/php-v/josemmo/uxml)](#installation)
[![License](https://img.shields.io/github/license/josemmo/uxml)](LICENSE)

UXML is an *extremely* simple PHP library for manipulating XML documents with ease while keeping overhead to a bare minimum.

It consist of just a single class which uses the PHP built-in `DOMElement` and `DOMDocument` classes under the hood.

## Installation

### Using Composer
```
composer install josemmo/uxml
```

### Without Composer
Download source files from the GitHub repository:
```
git clone https://github.com/josemmo/uxml.git
```

Use the UXML class in your app:
```php
use UXML\UXML;
require_once __DIR__ . "/uxml/src/UXML.php";
```

## FAQ
**Why use this instead of [sabre/xml](https://github.com/sabre-io/xml) or [FluidXML](https://github.com/servo-php/fluidxml)?**\
Both those options are great and if they fit your project you should definetely use them! However, in my case I needed something more lightweight to put on top of [LibXML's DOM](https://www.php.net/manual/en/book.dom.php) to provide an alternative syntax.

**Is UXML compatible with `DOMElement`?**\
Yes, indeed! You can get the original `DOMElement` instance from an `UXML` object and vice versa.

**I want UXML to do "X". Can you implement it?**\
My main goal with this project is not to implement all possible behaviors in the XML specification, you can use the DOM or SimpleXML libraries for that.

## Usage

### Create a new document
UXML does not distinguish between XML documents (`DOMDocument`) and elements (`DOMElement`). Instead, you can create a new document like so:
```php
$xml = \UXML\UXML::newInstance('RootTagName');
```

You can also wrap an already existing `DOMElement`:
```php
$domElement = new DOMElement('TagName');
$xml = \UXML\UXML::fromElement($domElement);
```

### Load an XML document from source
By loading an XML string, UXML will return the root element of the document tree:
```php
$source = <<<XML
<fruits>
    <fruit>Banana</fruit>
    <fruit>Apple</fruit>
    <fruit>Tomato</fruit>
</fruits>
XML;
$xml = \UXML\UXML::fromString($source);
```

### Add elements to a node
When adding an element, UXML will return a reference to the newly created element:
```php
$xml = \UXML\UXML::newInstance('Parent');
$child = $xml->add('Child');
echo $child; // <Child />
echo $xml;   // <Parent><Child /></Parent>
```

You can also define a value:
```php
$child = $xml->add('Child', 'Hello World!');
echo $child; // <Child>Hello World!</Child>
```

And even attributes or namespaces:
```php
$feed = \UXML\UXML::newInstance('feed', null, [
    'xmlns' => 'http://www.w3.org/2005/Atom'
]);
echo $feed; // <feed xmlns="http://www.w3.org/2005/Atom" />

$link = $feed->add('link', 'Wow!', [
    'href' => 'https://www.example.com'
]);
echo $link; // <link href="https://www.example.com">Wow!</link>
```

### Element chaining
Because with every element insertion a reference to the new element is returned, you can chain multiple of these calls to create a tree:
```php
$xml = \UXML\UXML::newInstance('people');
$xml->add('person')->add('name', 'Jane Doe');
echo $xml; // <people>
           //     <person>
           //         <name>Jane Doe</name>
           //     </person>
           // </people>
```

### Export XML source
Besides casting `UXML` objects to a `string`, there is a method for exporting the XML source of an element and its children:
```php
$xml->asXML();
```

By default, exported strings include an XML declaration (except when casting `UXML` instances to a `string`).

### Find XML elements
UXML allows you to use XPath 1.0 queries to get a particular element from a document:
```php
$xml = \UXML\UXML::newInstance('person');
$xml->add('name', 'Jane');
$xml->add('surname', 'Doe');
$xml->add('color', 'green', ['hex' => '#0f0']);

echo $xml->get('*[@hex]'); // <color hex="#0f0">green</color>
var_dump($xml->get('birthday')); // NULL
```

Or even multiple elements:
```php
$xml = \UXML\UXML::fromString('<a><b>1</b><b>2</b><b>3</b></a>');
foreach ($xml->getAll('b') as $elem) {
    echo "Element says: " . $elem->asText() . "\n";
}
```

Note all XPath queries are **relative to current element**:
```php
$source = <<<XML
<movie>
    <name>Inception</name>
    <year>2010</year>
    <director>
        <name>Christopher</name>
        <surname>Nolan</surname>
        <year>1970</year>
    </director>
</movie>
XML;
$xml = \UXML\UXML::fromString($source);

echo $xml->get('director/year'); // <year>1970</year>
echo $xml->get('director')->get('year'); // <year>1970</year>
echo $xml->get('year'); // <year>2010</year>
echo $xml->get('director')->get('//year'); // <year>2010</year>
```

### Remove XML elements
Elements can be removed from the XML tree by calling the `remove()` method on them.
After an element is removed, it becomes unusable:
```php
$source = <<<XML
<project>
    <public>
        <name>Alpha</name>
    </public>
    <confidential>
        <budget>1,000,000 USD</budget>
    </confidential>
</project>
XML;
$xml = \UXML\UXML::fromString($source);
$xml->get('confidential')->remove();
echo $xml; // <project><public><name>Alpha</name></public></project>
```

### Namespaces
Namespaces are assigned in the same way as other attributes:
```php
$xml->add('TagName', null, [
    'xmlns' => 'https://example.com',
    'xmlns:abc' => 'urn:abc',
    'attribute' => 'value'
])->add('abc:Child', 'Name');
echo $xml; // <TagName xmlns="https://example.com"
           //  xmlns:abc="urn:abc"
           //  attribute="value">
           //     <abc:Child>Name</abc:Child>
           // </TagName>
```

However, when querying elements, the prefix defined in the document may not be the one you are expecting:
```php
$xml = \UXML\UXML::fromString('<a xmlns:ns="urn:abc"><ns:b /></a>');
echo $xml->get('ns:b'); // <ns:b />
echo $xml->get('abc:b'); // Is NULL as the prefix does not exist
```

To fix this, you can make use of [clark notation](https://sabre.io/xml/clark-notation/) inside the XPath query:
```php
echo $xml->get('{urn:abc}b'); // <ns:b />
```

### Advanced XML manipulation
For any other document manipulation outside the scope of this library, you can always interact with the `DOMElement` instance:
```php
$xml = \UXML\UXML::newInstance('Test');
$xml->element(); // Returns a [DOMElement] object
```
