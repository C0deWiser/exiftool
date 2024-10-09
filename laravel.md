# Laravel

It is expecting, that you register `Exiftool` in `ApplicationServiceProvider`.

## Cast

Add `json` column to a database and apply `AsIptc` cast to an attribute.

Cast requires `Exiftool` to be registered as a service in
`ApplicationServiceProvider`.

```php
use Codewiser\Exiftool\Iptc;
use Codewiser\Exiftool\Laravel\Casts\AsIptc;
use Illuminate\Database\Eloquent\Model;

/**
 * @property null|Iptc $iptc 
 */
class Media extends Model
{
    protected function casts(): array
    {
        return [
            'iptc' => AsIptc::class        
        ]   
    }
}

use Codewiser\Exiftool\Exiftool;

$media->iptc = app(Exiftool::class)->read('filename.jpg');
```

## Rules

Specification marks some attributes as numeric, as uri, as url or as date-time.
Actually, most of url and uri attributes accepts comma-separated values, so 
we shouldn't apply `url` rule to them.

Sometimes we work with legacy data, where values meets `max` limitations.

That's why, `getValidationRules()` without configuration will return 
rules, that requires all attributes to be just a string.  

```php
use Codewiser\Exiftool\Exiftool;
use Codewiser\Exiftool\IptcExt;

$exiftool = new Exiftool();

$ext = new IptcExt($exiftool->specification());

$ext->getValidationRules([
    // Require numbers conform to `numeric` rule
    'number'    => true,
    // Require dates conform to `date` rule
    'date-time' => true,
    // Require limited values conform to `max` rule
    'maxbytes'  => true,
    // Require enum attributes use values from limited list
    // N.B. this works only with `printconv` enabled
    'enum'      => true
]);
```

## Flatten array of attributes

Flatten array of attributes may be useful to automatically building of 
user-interface. It is a key-value array, there key is full-qualified 
attribute name (as in `rules` array) and value is raw attribute specification. 

Some attributes may have an `enum` property. [Read more](README.md#enum-values).

```php
use Codewiser\Exiftool\Exiftool;
use Codewiser\Exiftool\IptcExt;

$exiftool = new Exiftool();

$ext = new IptcExt($exiftool->specification());

$ext->asDotArray();
```

## Controlled Vocabularies

You may get list of controlled vocabularies urls, associated to attributes.

List is a key-value array, there key is full-qualified
attribute name (as in `rules` array) and value is controlled vocabularies url.

```php
use Codewiser\Exiftool\Exiftool;
use Codewiser\Exiftool\IptcExt;

$exiftool = new Exiftool();

$ext = new IptcExt($exiftool->specification());

$ext->getNewsCodes();
```