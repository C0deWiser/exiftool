<?php

namespace Tests\KnowBugs;

use Codewiser\Exiftool\Attributes\AltLangAttribute;
use Codewiser\Exiftool\Spec\Specification;
use Tests\TestCase;

/**
 * ExifTool bug: ProductInImage.ProductId is multi, but exiftool counts it a single string.
 *
 * So. ExifTool keeps a string, we will keep an array.
 *
 * If we have a few identifiers, we will embed to ExifTool the first one.
 * If we get from ExifTool a single identifier, we will keep it in array.
 */
class ProductWGtinTest extends TestCase
{
    /**
     * ExifTool provides a string, we provide as array.
     */
    public function testReadTransformation()
    {
        $raw = $this->exiftool->readRaw(__DIR__.'/../Spec2023/IPTC-PhotometadataRef-Std2023.2.jpg');

        $productInImage = $raw[0]['ProductInImage'][0];

        $this->assertTrue(is_string($productInImage['ProductId']));

        $iptc = $this->exiftool->newMetadata()->fromExiftool($raw[0]);

        $productInImage = $iptc->jsonSerialize()['productsShown'][0];

        $this->assertTrue(is_array($productInImage['identifiers']));
    }

    /**
     * If we have an array of identifiers, we embed just one.
     */
    public function testWriteTransformation()
    {
        $template = __DIR__.'/../IPTC-Empty.jpg';
        $filename = __DIR__.'/ProductWGtinTest.jpg';

        if (file_exists($filename)) {
            unlink($filename);
        }

        copy($template, $filename);

        try {
            $iptc = $this->exiftool->newMetadata();
            $iptc->fromJson([
                'productsShown' => [[
                    'gtin'        => '12345678901234',
                    'identifiers' => ['http://wikidata.org/item/Q1', 'http://wikidata.org/item/Q2'],
                    'name'        => ['ru' => 'Айфон', 'en' => 'IPhone'],
                    'description' => ['ru' => 'Описание', 'en' => 'Description'],
                ]]
            ]);

            $this->exiftool->write($filename, $iptc);

            $embedded = $this->exiftool->read($filename);

            $this->assertEquals(['productsShown' => [[
                'gtin'        => '12345678901234',
                'identifiers' => ['http://wikidata.org/item/Q1'],
                'name'        => ['ru' => 'Айфон', 'en' => 'IPhone'],
                'description' => ['ru' => 'Описание', 'en' => 'Description'],
            ]]], $embedded->jsonSerialize());
        } finally {
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
    }
}
