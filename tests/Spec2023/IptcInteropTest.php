<?php

namespace Tests\Spec2023;

use Codewiser\Exiftool\Attributes\AltLangAttribute;
use Codewiser\Exiftool\Exiftool;
use Tests\TestCase;

/**
 * IPTC Photo Metadata Interoperability Test #2:
 * "Does the software read and write embedded metadata correctly?"
 *
 * A copy of the reference image is loaded, some fields are changed and saved.
 * Only the intentionally changed fields may differ from the original image.
 */
class IptcInteropTest extends TestCase
{
    protected string $spec = __DIR__.'/../../iptc-pmd-techreference_2023.2.json';
    protected string $reference = __DIR__.'/IPTC-PhotometadataRef-Std2023.2.jpg';
    protected string $work = __DIR__.'/IptcInteropTest-copy.jpg';

    /** @var array|string[] json-names of fields intentionally changed */
    protected array $edited = [
        'title',
        'description',
        'keywords',
        'creatorNames',
        'dateCreated',
        'headline',
        'usageTerms',
        'creatorContactInfo',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        if (is_file($this->work)) {
            unlink($this->work);
        }
        copy($this->reference, $this->work);

        $this->exiftool = new Exiftool(specification: $this->spec);

        AltLangAttribute::collapse(false);
    }

    protected function tearDown(): void
    {
        if (is_file($this->work)) {
            unlink($this->work);
        }

        parent::tearDown();
    }

    protected function applyInteropChanges(\Codewiser\Exiftool\Iptc $iptc): void
    {
        $iptc->title = 'The Title (interop edited)';
        $iptc->description = 'The description was edited by interoperability test #2';
        $iptc->keywords = ['Interop Keyword 1', 'Interop Keyword 2'];
        $iptc->creatorNames = ['Interop Creator'];
        $iptc->dateCreated = '2025-06-15T10:30:00+00:00';
        $iptc->headline = 'Interop Headline';
        $iptc->usageTerms = 'Interop usage terms';
        $iptc->creatorContactInfo = [
            'city' => 'Interop City',
            'country' => 'Interop Country',
        ];
    }

    protected function runInteropTest(): void
    {
        $baseline = $this->exiftool->read($this->reference);

        $changed = $this->exiftool->newMetadata();
        $this->applyInteropChanges($changed);

        $process = $this->exiftool->write($this->work, $changed);
        $this->assertTrue($process->isSuccessful(), $process->getErrorOutput());

        $result = $this->exiftool->read($this->work);

        foreach ($this->edited as $jsonName) {
            // Changed field must differ from the reference image.
            $this->assertNotEquals(
                $baseline->{$jsonName}->jsonSerialize(),
                $result->{$jsonName}->jsonSerialize(),
                "$jsonName was not changed"
            );

            // Changed field must be written and read back as-is.
            $this->assertEquals(
                $changed->{$jsonName}->jsonSerialize(),
                $result->{$jsonName}->jsonSerialize(),
                "$jsonName was not written as-is"
            );
        }

        $base = $baseline->jsonSerialize();
        $res = $result->jsonSerialize();

        // Not intentionally changed field must stay the same.
        foreach (array_unique(array_merge(array_keys($base), array_keys($res))) as $jsonName) {
            if (in_array($jsonName, $this->edited)) {
                continue;
            }

            $this->assertEquals(
                $base[$jsonName] ?? null,
                $res[$jsonName] ?? null,
                "Unexpected change of $jsonName"
            );
        }
    }

    public function testHumanReadHumanWrite()
    {
        $this->exiftool->useHumanValues();

        $this->runInteropTest();
    }

    public function testMachineReadMachineWrite()
    {
        $this->exiftool->useMachineValues();

        $this->runInteropTest();
    }
}