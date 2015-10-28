<?
$doc = new DOMDocument();
$doc->load('test.dom.2.xml');
$destinations = $doc->getElementsByTagName("mm_displaystr");
foreach ($destinations as $destination) {
    foreach($destination->childNodes as $child) {
        if ($child->nodeType == XML_CDATA_SECTION_NODE) {
            echo htmlentities($child->textContent)."<br/>";
        }
    }
}
?>