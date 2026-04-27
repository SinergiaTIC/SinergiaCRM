<?php
namespace Tests;

use DOMDocument;
use DOMElement;
use DOMException;
use UXML\UXML;
use PHPUnit\Framework\TestCase;

final class UXMLTest extends TestCase {
    /**
     * Element list to text
     * @param  UXML[] $elements Array of elements
     * @return string           Text content of elements separated by commas
     */
    private function listToText(array $elements): string {
        return implode(',', array_map(function(UXML $elem) {
            return $elem->asText();
        }, $elements));
    }

    public function testCanCreateElements(): void {
        $xml = UXML::newInstance('RootTagName');
        $this->assertEquals('<RootTagName/>', $xml);

        $xml = UXML::fromElement(new DOMElement('TagName'));
        $this->assertEquals('<TagName/>', $xml);
    }

    public function testCanHandleSpecialCharacters(): void {
        $xml = UXML::newInstance('Test', 'A&a Co. > B&b Ltd.');
        $this->assertEquals('<Test>A&amp;a Co. &gt; B&amp;b Ltd.</Test>', $xml);

        $this->expectException(DOMException::class);
        UXML::newInstance('Test&Fail', 'Not a valid tag name');
    }

    public function testCanExportXml(): void {
        $xml = UXML::newInstance('Root');
        $this->assertEquals('<Root/>', (string) $xml);
        $this->assertEquals('<Root/>', $xml->asXML(null));
        $this->assertEquals("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<Root/>\n", $xml->asXML());
        $this->assertEquals("<?xml version=\"1.1\" encoding=\"ISO-8859-1\"?>\n<Root/>\n", $xml->asXML('1.1', 'ISO-8859-1'));

        $xml = UXML::fromString('<a><b>1</b><b>2</b></a>');
        $this->assertEquals("<a>\n  <b>1</b>\n  <b>2</b>\n</a>", $xml->asXML(null));
        $this->assertEquals('<a><b>1</b><b>2</b></a>', (string) $xml);
        $this->assertEquals('<a><b>1</b><b>2</b></a>', $xml->asXML(null, 'UTF-8', false));
    }

    public function testCanLoadXml(): void {
        $source  = "<fruits>";
        $source .= "<fruit>Banana</fruit>";
        $source .= "<fruit>Apple</fruit>";
        $source .= "<fruit>Orange</fruit>";
        $source .= "<optional>";
        $source .= "<fruit>Tomato</fruit>";
        $source .= "</optional>";
        $source .= "</fruits>";
        $xml = UXML::fromString($source);
        $this->assertEquals($source, $xml);
    }

    public function testCanAddElements(): void {
        $xml = UXML::newInstance('Parent');
        $childA = $xml->add('ChildA', 'Name');
        $childB = $xml->add('Wrapper')->add('ChildB');
        $this->assertEquals('<ChildA>Name</ChildA>', $childA);
        $this->assertEquals('<ChildB/>', $childB);
        $this->assertEquals('<Parent><ChildA>Name</ChildA><Wrapper><ChildB/></Wrapper></Parent>', $xml);
    }

    public function testCanHandleAttributes(): void {
        $feed = UXML::newInstance('feed', null, [
            'xmlns' => 'urn:atom',
            'xmlns:a' => 'urn:testns'
        ]);
        $feed->add('link', 'Wow!', ['a:href' => 'urn']);
        $this->assertEquals('<feed xmlns="urn:atom" xmlns:a="urn:testns"><link a:href="urn">Wow!</link></feed>', $feed);
    }

    public function testCanGetSingleElement(): void {
        $source = <<<XML
<movie>
    <name lang="en-US">Inception</name>
    <year>2010</year>
    <director>
        <name>Christopher</name>
        <surname>Nolan</surname>
        <year>1970</year>
    </director>
</movie>
XML;
        $xml = UXML::fromString($source);

        $this->assertEquals('<year>1970</year>', $xml->get('director/year'));
        $this->assertEquals('<year>1970</year>', $xml->get('director')->get('year'));
        $this->assertEquals('<year>2010</year>', $xml->get('year'));
        $this->assertEquals('<year>2010</year>', $xml->get('director')->get('//year'));
        $this->assertEquals('<name lang="en-US">Inception</name>', $xml->get('*[@lang]'));
        $this->assertNull($xml->get('genre'));
    }

    public function testCanGetAllElements(): void {
        $source = <<<XML
<root>
    <a>
        <b>1</b>
        <b>2</b>
        <c>-1</c>
        <b>3</b>
        <c>-2</c>
        <d>Inf</d>
    </a>
    <b>4</b>
    <c>-3</c>
</root>
XML;
        $xml = UXML::fromString($source);

        $this->assertEquals('1,2,3',    $this->listToText($xml->getAll('a/b')));
        $this->assertEquals('1,2',      $this->listToText($xml->getAll('a/b', 2)));
        $this->assertEquals('-3',       $this->listToText($xml->getAll('c')));
        $this->assertEquals('-1,-2,-3', $this->listToText($xml->getAll('//c')));
        $this->assertEmpty($xml->getAll('d'));
    }

    public function testCanHandleClarkNotation(): void {
        $xml = UXML::fromString('<a xmlns:ns="urn:abc"><ns:b /><ns:c /></a>');
        $this->assertEquals('<ns:b xmlns:ns="urn:abc"/>', $xml->get('{urn:abc}b'));
        $this->assertSame($xml->get('{urn:abc}b'), $xml->get('ns:b'));
    }

    public function testCanHandleNamespaces(): void {
        $xml = UXML::fromString('<root xmlns="urn:root" xmlns:ns="urn:child"><ns:child /><ns:child /></root>');
        $this->assertEquals(2, count($xml->getAll('ns:child')));
        $xml->add('ns:child', 'Another child');
        $this->assertEquals(3, count($xml->getAll('ns:child')));

        $xml = UXML::newInstance('root', null, [
            'xmlns:ns' => 'urn:child'
        ]);
        $xml->add('ns:child', 'A1')->add('child', 'A2', ['xmlns' => 'urn:child']);
        $xml->add('ns:child', 'B1')->add('ns:child', 'B2');
        $this->assertEquals(2, count($xml->getAll('ns:child')));
        $this->assertEquals(4, count($xml->getAll('//ns:child')));
    }

    public function testCanGetParent(): void {
        $root = UXML::newInstance('Root');
        $level1 = $root->add('Level1');
        $level2 = $level1->add('Level2');
        $level3 = $level2->add('Level3');
        $this->assertSame($level2, $level3->parent());
        $this->assertSame($level1, $level2->parent());
        $this->assertSame($root, $level1->parent());
        $this->assertSame($root, $root->parent());
        $this->assertSame($root, $level3->parent()->parent()->parent()->parent());
    }

    public function testCanRemoveElements(): void {
        $source = <<<XML
<root>
    <a>1</a>
    <a>2</a>
    <a>3</a>
    <b>4</b>
    <b>5</b>
    <b>6</b>
    <a>7</a>
    <a>8</a>
    <b>9</b>
    <a>10</a>
</root>
XML;
        $xml = UXML::fromString($source);
        foreach ($xml->getAll('a') as $item) {
            $item->remove();
        }
        $this->assertEmpty($xml->getAll('a'));
        $this->assertEquals('<root><b>4</b><b>5</b><b>6</b><b>9</b></root>', $xml);
    }

    public function testCanHandleExistingInstances(): void {
        $doc = new DOMDocument();

        $rootNode = $doc->createElement('root');
        $rootA = UXML::fromElement($rootNode);
        $rootB = UXML::fromElement($rootNode);
        $this->assertSame($rootA, $rootB);
        $this->assertSame($rootA->element(), $rootNode);

        $child = UXML::newInstance('Child', null, [], $doc);
        $childNode = $child->element();
        $rootNode->appendChild($childNode);
        $this->assertSame($rootA->get('Child'), $child);
        $this->assertSame($child->parent(), $rootA);
        $this->assertNotSame($child, $rootA);
    }
}
