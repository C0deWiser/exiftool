<?php

namespace Codewiser\Exiftool;

use Codewiser\Exiftool\Attributes\AltLangAttribute;
use Codewiser\Exiftool\Spec\Concerns\AttributeSpec;
use Codewiser\Exiftool\Spec\Concerns\AttributeBag;
use Codewiser\Exiftool\Spec\Specification;

/**
 * IPTC Specification extension.
 */
class IptcExt
{
    public function __construct(protected Specification $specification)
    {
        //
    }

    /**
     * Get Ug Topics descriptions.
     */
    public function getUgTopics(): array
    {
        return [
            'admin'     => __('Administrative Details'),
            'gimgcont'  => __('General Image Content'),
            'imgreg'    => __('Image Region'),
            'licensing' => __('Licensing Use'),
            'location'  => __('Location'),
            'othings'   => __('Other Things Shown'),
            'person'    => __('Persons Shown'),
            'rights'    => __('Rights Information'),
        ];
    }

    /**
     * Get Controlled Vocabularies urls associated to attributes.
     */
    public function getNewsCodes(): array
    {
        return [
            # Enter only the 6-digit codes from the IPTC Scene NewsCodes Controlled Vocabulary
            'sceneCodes.*'                         => 'http://cv.iptc.org/newscodes/scene',

            # Enter only the 8-digit codes from the IPTC Subject NewsCode Controlled Vocabulary
            'subjectCodes.*'                       => 'http://cv.iptc.org/newscodes/mediatopic',

            # Indicates a narrower attribute-like context for a Subject Code
            # NOTE: this vocabulary should be used with “Subject Code”
            // http://cv.iptc.org/newscodes/subjectqualifier

            # Unclear
            'contributors.*.role.*'                => 'http://cv.iptc.org/newscodes/contentprodpartyrole',
            'digitalSourceType'                    => 'http://cv.iptc.org/newscodes/digitalsourcetype',
            'imageRegion.*.rRole.*.identifiers.*'  => 'http://cv.iptc.org/newscodes/imageregionrole',
            'imageRegion.*.rCtype.*.identifiers.*' => 'http://cv.iptc.org/newscodes/imageregiontype',

            # cvId — Enter the globally unique identifier of the Controlled Vocabulary which the term is from
            # cvTermId — Enter the globally unique identifier of the term from a Controlled Vocabulary
            // 'aboutCvTerms.*.cvTermId'            => 'http://cv.iptc.org/newscodes/mediatopic',
            // 'genres.*.cvTermId'                  => 'http://cv.iptc.org/newscodes/genre',

            # Indicates product genres for media objects
            // http://cv.iptc.org/newscodes/productgenre

            # Indicates a region of the world
            // http://cv.iptc.org/newscodes/worldregion
        ];
    }

    /**
     * Get specification with keys as full-path json-name.
     */
    public function asDotArray(): array
    {
        $attributes = $this->iterateAttributes($this->specification->topLevel());

        ksort($attributes);

        return $attributes;
    }

    protected function iterateAttributes(AttributeBag $spec): array
    {
        $attributes = [];

        foreach ($spec->getAttributes() as $attribute) {
            $attributes = array_merge($attributes, $this->collectAttribute($attribute));
        }

        return $attributes;
    }

    protected function collectAttribute(AttributeSpec $attribute): array
    {
        $properties = [];

        $name = $attribute->jsonName();
        $properties[$name] = $attribute->toArray();
        if ($enum = $attribute->enum()) {
            $properties[$name]['enum'] = $enum;
        }

        if ($struct = $attribute->struct()) {
            $prefix = $attribute->isMultiple() ? '*.' : '';
            foreach ($this->iterateAttributes($struct) as $key => $value) {
                $properties["$name.$prefix$key"] = $value;
            }
        }

        return array_filter($properties);
    }

    /**
     * Get Laravel validation rules for all IPTC attributes.
     */
    public function getValidationRules(array $config = []): array
    {
        $rules = $this->iterateRules($this->specification->topLevel(), $config);

        ksort($rules);

        return $rules;
    }

    protected function iterateRules(AttributeBag $spec, array $config = []): array
    {
        $attributes = [];

        foreach ($spec->getAttributes() as $attribute) {
            $attributes = array_merge($attributes, $this->collectRules($attribute, $config));
        }

        return $attributes;
    }

    protected function collectRules(AttributeSpec $attribute, array $config = []): array
    {
        $rules = [];

        $name = $attribute->jsonName();
        $multi = $attribute->isMultiple();
        $max = ($max = $attribute->maxBytes()) && ($config['maxbytes'] ?? false) ? "|max:$max" : '';
        $numeric = ($config['number'] ?? false) ? 'numeric' : 'string';
        $datetime = ($config['date-time'] ?? false) ? 'date' : 'string';

        if ($struct = $attribute->struct()) {
            $rule = 'array:'.implode(',', $struct->getAttributesJsonNames());
        } elseif ($attribute->dataFormat() == 'AltLang') {
            $rule = "string$max";
            $multi = !AltLangAttribute::$collapsed;
        } elseif ($attribute->dataType() == 'number') {
            $rule = "$numeric$max";
        } else {
            $rule = match ($attribute->dataFormat()) {
                'date-time' => $datetime,
                default     => "string$max"
            };
        }

        $requirement = $attribute->isRequired() ? 'filled' : 'nullable';

        if ($multi) {
            $rules[$name] = "$requirement|array";
            $rules["$name.*"] = "filled|$rule";
        } else {
            $rules[$name] = "$requirement|$rule";
        }

        if ($struct = $attribute->struct()) {
            $prefix = $multi ? '*.' : '';
            foreach ($this->iterateRules($struct, $config) as $key => $value) {
                $rules["$name.$prefix$key"] = $value;
            }
        }

        return $rules;
    }
}
