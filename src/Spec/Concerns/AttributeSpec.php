<?php

namespace Codewiser\Exiftool\Spec\Concerns;

use Codewiser\Exiftool\Attributes\AltLangAttribute;
use Codewiser\Exiftool\Contracts\AltLang;
use Codewiser\Exiftool\Exiftool;
use Codewiser\Exiftool\Spec\Specification;
use Codewiser\Exiftool\Spec\StructureSpec;
use Codewiser\Exiftool\Spec\TopLevelAttributeSpec;
use Codewiser\Exiftool\Traits\HasAltLang;

abstract class AttributeSpec
{
    use HasAltLang;

    protected array $spec;

    /**
     * Get name of an attribute.
     */
    public function name(): string
    {
        return $this->spec['name'];
    }

    /**
     * Get human-readable label of an attribute.
     */
    public function label(): string
    {
        return $this->spec['label'];
    }

    /**
     * Get help text of an attribute.
     */
    public function helpText(): string
    {
        return $this->spec['helptext'];
    }

    /**
     * Get user notes of an attribute.
     */
    public function userNotes(): ?string
    {
        return $this->spec['usernotes'] ?: null;
    }

    /**
     * Get specification anchor.
     */
    public function specIdx(): string
    {
        return $this->spec['specidx'];
    }

    /**
     * Get an attribute sort order.
     */
    public function sortOrder(): string
    {
        return $this->spec['sortorder'];
    }

    /**
     * Get an attribute data type. Possible values are [string, number, struct].
     */
    public function dataType(): string
    {
        return $this->spec['datatype'];
    }

    /**
     * Get an attribute data format. Possible values are:
     * for string: [date-time, uri, url],
     * for number: [integer],
     * for struct: struct name.
     *
     * Always has a value for the `struct` data-type.
     */
    public function dataFormat(): ?string
    {
        return $this->spec['dataformat'] ?? null;
    }

    /**
     * Get attribute max size.
     */
    public function maxBytes(): ?int
    {
        return $this->spec['IIMmaxbytes'] ?? null;
    }

    /**
     * Is attribute has multiple values?
     */
    public function isMultiple(): bool
    {
        return $this->spec['propoccurrence'] == 'multi';
    }

    /**
     * Is attribute has single value?
     */
    public function isSingle(): bool
    {
        return $this->spec['propoccurrence'] == 'single';
    }

    /**
     * Is attribute required to be filled?
     */
    public function isRequired(): bool
    {
        return !!$this->spec['isrequired'];
    }

    /**
     * Get json-name of an attribute.
     */
    public function jsonName(): string
    {
        return $this->jsonName;
    }

    /**
     * Get structure specification of an attribute (for `struct` attributes only).
     */
    public function struct(): ?StructureSpec
    {
        if ($this->dataType() == 'struct') {
            $dataFormat = $this->dataFormat();
            if ($dataFormat !== 'AltLang') {
                return new StructureSpec($dataFormat);
            }
        }

        return null;
    }

    public function toArray(): array
    {
        return $this->spec;
    }

    /**
     * Gat array of attribute short-names (used for reading metadata).
     */
    abstract public function etNames(): array;

    /**
     * Gat array of attribute full-names (used for writing metadata).
     */
    abstract public function etNamesWithPrefix(): array;

    /**
     * @see https://exiftool.org/TagNames/PLUS.html
     */
    public function enum(): ?array
    {
        $values = match ($this->jsonName()) {
            # PLUS properties
            'dataMining'              => $this->enumForDataMining(),
            'minorModelAgeDisclosure' => $this->enumForMinorModelAgeDisclosure(),
            'modelReleaseStatus'      => $this->enumForModelReleaseStatus(),
            'propertyReleaseStatus'   => $this->enumForPropertyReleaseStatus(),

            # Enums
            'imageRating'             => $this->enumForImageRating(),
            'rbShape'                 => $this->enumForRbShape(),
            'rbUnit'                  => $this->enumForRbUnit(),
            'gpsAltitudeRef'          => $this->enumForGpsAltitudeRef(),
            'licensorTelephoneType1',
            'licensorTelephoneType2'  => $this->enumForLicensorTelephoneType(),
            default                   => null
        };

        if ($values) {
            return Exiftool::$printConv ? array_keys($values) : array_values($values);
        }

        return null;
    }

    protected function enumForImageRating(): array
    {
        return [
            -1 => 'Rejected',
            0  => 'Unrated',
            1  => 1,
            2  => 2,
            3  => 3,
            4  => 4,
            5  => 5,
        ];
    }

    protected function enumForRbShape(): array
    {
        return [
            'rectangle' => 'Rectangle',
            'circle'    => 'Circle',
            'polygon'   => 'Polygon',
        ];
    }

    protected function enumForLicensorTelephoneType(): array
    {
        return [
            'cell'  => 'Cell',
            'fax'   => 'FAX',
            'home'  => 'Home',
            'pager' => 'Pager',
            'work'  => 'Work',
        ];
    }

    protected function enumForRbUnit(): array
    {
        return [
            'pixel'    => 'Pixel',
            'relative' => 'Relative',
        ];
    }

    /**
     * Data mining prohibition or permission, optionally with constraints.
     *
     * @see http://ns.useplus.org/LDF/ldf-XMPSpecification#DataMining
     */
    protected function enumForDataMining(): array
    {
        return [
            'DMI-UNSPECIFIED'                           => 'Unspecified - no prohibition defined',
            'DMI-ALLOWED'                               => 'Allowed',
            'DMI-PROHIBITED-AIMLTRAINING'               => 'Prohibited for AI/ML training',
            'DMI-PROHIBITED-GENAIMLTRAINING'            => 'Prohibited for Generative AI/ML training',
            'DMI-PROHIBITED-EXCEPTSEARCHENGINEINDEXING' => 'Prohibited except for search engine indexing',
            'DMI-PROHIBITED-SEECONSTRAINT'              => 'Prohibited, see plus:OtherConstraints',
            'DMI-PROHIBITED-SEEEMBEDDEDRIGHTSEXPR'      => 'Prohibited, see iptcExt:EmbdEncRightsExpr',
            'DMI-PROHIBITED-SEELINKEDRIGHTSEXPR'        => 'Prohibited, see iptcExt:LinkedEncRightsExpr',
        ];
    }

    /**
     * Age of the youngest model pictured in the image, at the time that the image was made.
     *
     * @see http://ns.useplus.org/LDF/ldf-XMPSpecification#MinorModelAgeDisclosure
     */
    protected function enumForMinorModelAgeDisclosure(): array
    {
        return [
            'AG-UNK' => 'Age Unknown',
            'AG-A25' => 'Age 25 or Over',
            'AG-A24' => 'Age 24',
            'AG-A23' => 'Age 23',
            'AG-A22' => 'Age 22',
            'AG-A21' => 'Age 21',
            'AG-A20' => 'Age 20',
            'AG-A19' => 'Age 19',
            'AG-A18' => 'Age 18',
            'AG-A17' => 'Age 17',
            'AG-A16' => 'Age 16',
            'AG-A15' => 'Age 15',
            'AG-U14' => 'Age 14 or Under',
        ];
    }

    /**
     * Summarizes the availability and scope of model releases authorizing usage of the likenesses of persons appearing in the photograph.
     *
     * @see http://ns.useplus.org/LDF/ldf-XMPSpecification#ModelReleaseStatus
     */
    protected function enumForModelReleaseStatus(): array
    {
        return [
            'MR-NON' => 'None',
            'MR-NAP' => 'Not Applicable',
            'MR-UMR' => 'Unlimited Model Releases',
            'MR-LMR' => 'Limited or Incomplete Model Releases',
        ];
    }

    /**
     * Summarizes the availability and scope of property releases authorizing usage of the properties appearing in the photograph.
     *
     * @see http://ns.useplus.org/LDF/ldf-XMPSpecification#PropertyReleaseStatus
     */
    protected function enumForPropertyReleaseStatus(): array
    {
        return [
            'PR-NON' => 'None',
            'PR-NAP' => 'Not Applicable',
            'PR-UPR' => 'Unlimited Property Releases',
            'PR-LPR' => 'Limited or Incomplete Property Releases',
        ];
    }

    protected function enumForGpsAltitudeRef(): array
    {
        return [
            0 => 'Above Sea Level',
            1 => 'Below Sea Level'
        ];
    }
}
