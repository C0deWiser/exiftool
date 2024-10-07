<?php

namespace Codewiser\Exiftool\Attributes;

use Codewiser\Exiftool\Contracts;
use Codewiser\Exiftool\Spec\Concerns\AttributeSpec;

class AttributeFactory
{
    public static function for(AttributeSpec $spec): Contracts\Attribute
    {
        if ($spec->dataType() == 'struct') {
            if ($spec->dataFormat() == 'AltLang') {
                $factory = fn() => new AltLangAttribute();
            } else {
                $factory = fn() => new StructureAttribute($spec->struct());
            }
        } else {
            if ($spec->dataFormat() == 'date-time') {
                $factory = fn() => new DateTimeAttribute();
            } else {
                $mergeValues = true;
                $factory = fn() => new PlainAttribute();
            }
        }

        if ($spec->isMultiple()) {
            return new ArrayAttribute($factory, isset($mergeValues));
        } else {
            return call_user_func($factory);
        }
    }
}
