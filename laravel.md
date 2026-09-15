# Laravel

It is expected that you register `Exiftool` in `ApplicationServiceProvider`.

## Cast

Add `json` column to a database and apply `AsIptc` cast to an attribute.

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

## Request validation rules

We may use the IPTC specification to automatically build request validation rules. 

The specification marks some attributes as numeric, as uri, as url or as date-time.
Actually, most url and uri attributes accept comma-separated values, so 
we shouldn't apply the `url` rule to them.

Sometimes we work with legacy data, where values meet `max` limitations.

That's why `getValidationRules()` without configuration will return 
rules that require all attributes to be just a string. But you may enable 
strict behaviour as shown below:

```php
use Codewiser\Exiftool\Exiftool;
use Codewiser\Exiftool\IptcExt;

$ext = new IptcExt(
    app(Exiftool::class)->specification()
);

$rules = $ext->getValidationRules([
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

Finally, you may apply collected rules to a `FormRequest`.

## Flatten array of attributes

The flattened array of attributes may be useful for automated building of a 
user interface. It is a key-value array, where the key is the full-qualified 
attribute name (as in the `rules` array) and the value is the raw attribute specification. 

Some attributes may have an `enum` property. [Read more](README.md#enum-values).

```php
use Codewiser\Exiftool\Exiftool;
use Codewiser\Exiftool\IptcExt;

$ext = new IptcExt(
    app(Exiftool::class)->specification()
);

$ext->asDotArray();
```

## Controlled Vocabularies

You may get a list of controlled vocabulary urls associated with attributes.

The list is a key-value array, where the key is the full-qualified
attribute name (as in the `rules` array) and the value is the controlled vocabulary url.

```php
use Codewiser\Exiftool\Exiftool;
use Codewiser\Exiftool\IptcExt;

$ext = new IptcExt(
    app(Exiftool::class)->specification()
);

$ext->getNewsCodes();
```