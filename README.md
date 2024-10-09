# IPTC metadata

    Using Exiftool. Using IPTC specification.

This lightweight package based on 
[machine-readable specification](https://iptc.org/std/photometadata/specification/) 
of IPTC provided by 
[iptc.org](https://iptc.org/std/photometadata/documentation/techreference/).

As specification describes how exiftool exports/imports metadata, describes 
every attribute and every structure — this package uses json-version 
of specification as a framework.

This package provides object-oriented programmatic interface to work 
with iptc attributes as objects.

This package provides [some helpers](laravel.md) for Laravel.

## Known issues

IPTC specification describes `locationCreated` as single element, but 
`Exiftool` counts it as a bag and requires array of `Location` structures.

IPTC specification describes `ProductWGtin.identifiers` as multiple element 
(array), but `Exiftool` requires single element.

`Exiftool` doesnt import `GPSAltitudeRef` as `GPSAltitude` may be positive 
or negative — this is enough.

This library overrides IPTC specification, making `locationCreated` multiple 
and `ProductWGtin.identifiers` single. And ignores `GPSAltitudeRef`.

## Configuration

Construct `Exiftool` with path to `exiftool` binary and, optionally, with 
path to specification file.

```php
use Codewiser\Exiftool\Exiftool;

// With latest specification
$exiftool = new Exiftool('/bin/exiftool');

// Or with another
$exiftool = new Exiftool('/bin/exiftool', '/path/to/specification.json');
```

Set up `AltLang` with locale information.

```php
use Codewiser\Exiftool\Attributes\AltLangAttribute;

AltLangAttribute::useLocale('en');
```

## Read metadata

```php
use Codewiser\Exiftool\Iptc;

public function read(string $filename): Iptc
{
    return $this->exiftool()->read($filenme);
}
```

## Embed metadata

```php
use Codewiser\Exiftool\Iptc;
use Symfony\Component\Process\Process;

public function write(string $filename, Iptc $data): Process
{
    return $this->exiftool()->write($filenme, $data);
}
```

## Clear metadata

```php
use Symfony\Component\Process\Process;

public function write(string $filename): Process
{
    return $this->exiftool()->clear($filenme, $data);
}
```

## Fake or empty metadata

You may create empty metadata collection and fill it with fake values:

```php
// empty collection
$empty = $exiftool->newMetadata();

// requires `fakerphp/faker`
$faked = $exiftool->newMetadata()->fake();
```

## Properties

Whole collection and every attribute are `JsonSerializable`: this 
method exports metadata to json format.

```php
$json = $data->jsonSerialize();
```

Whole collection and every single attribute may be filled with json data:

```php
$data->fromJson(['captionWriter' => 'me']);
```

### Plain

`Plain` attribute is `Stringable` and has `toString` method, that is used 
to get attribute scalar value.

```php
(string) $data->captionWriter;
// the same as
$data->captionWriter?->toString();

$data->captionWriter = 'me';
```

### DateTime

`DateTime` attribute is `Stringable` and has `toDateTime` method, that is used
to get `DateTimeInterface` value.

```php
(string)$data->dateCreated;
// the same as
$data->dateCreated?->toDateTime()->format('c');

$data->dateCreated = time();
$data->dateCreated = new \DateTime();
```

### AltLang

`AltLang` attribute keeps array of strings, each for different locale.

When importing data, `AltLang` stores default value to current locale. 
When exporting, `AltLang` current locale value will be embedded as default.

    If exiftool export is:
    
    [
        "AltTextAccessibility" => "Value 1",
        "AltTextAccessibility-es" => "Value 2",
        "AltTextAccessibility-de" => "Value 3",
    ]

    and current locale is `en` — the result will be:

    [
        "en" => "Value 1",
        "es" => "Value 2",
        "de" => "Value 3",
    ]

`AltLang` attribute is `Stringable` and has `toString` method, that is used
to get attribute value in current locale.

```php
(string) $data->description;
// the same as
$data->description?->toString();

$data->description = 'about';
```

It has `toArray` method te get all localized values.

```php
$data->description?->toArray();
```

It implements `ArrayAccess`, so you can access values using locale as key. 

```php
isset($data->description['cn']);

$data->description['en'] = 'about';
```

When your backend-end responds with metadata to a front-end, the `AltLang` 
attribute responds as array. Sometimes you may want to collapse `AltLang` 
values to a string with current locale (or best matched) value. To do that 
instruct attribute to collapse values before respond.

```php
use Codewiser\Exiftool\Attributes\AltLangAttribute;

AltLangAttribute::collapse();

return json_encode($data->jsonSerialized());
```

### Structure

`Structure` is nested collection of attributes. All structures well 
documented with all their attributes. Every nested attribute may be any of 
types.

```php
$data->creatorContactInfo?->address?->toString();

$data->locationCreated?->name['en']
```

To add structure fill it with a json:

```php
$data->locationCreated = ['city' => 'London', 'country' => 'UK'];
```

Or use factory to create empty object:

```php
$data->locationCreated = $exiftool->newStructure()->location();
$data->locationCreated->city = 'London';
```

### Multiple

`Multiple` attribute is array of other attributes of any type. It is 
`Iterator`, `ArrayAccess` and `Countable`, so you may handle it as true array.

```php
foreach ($data->keywords as $keyword) {
    //
}

count($data->keywords);

$data->keywords[0]->toArray();
```

```php
$data->locationsShown = [
    ['city' => 'London', 'country' => 'UK'],
    ['city' => 'Paris', 'country' => 'France'],
];
```

## Print Conv

Read https://exiftool.org/under.html

Without enabling `$exiftool->printConv()` all values is human-readable. For 
example, `GPSLatitude` may has value `45 deg 20' 11.00"`.

If you call `$exiftool->printConv()` before importing/exporting IPTC 
metadata, you should use _dirty_ values. For example, `GPSLatitude` may has 
value `45.3363888888889`.

This is very important in context of `enum` attributes — that must use 
values from limited list. Exiftool will reject value if it is not allowed.

Read more below.

## Enum values

Some attributes, such as `dataMining`, `modelReleaseStatus`, `rbShape` and 
some others, require their values from a limited list.

For example, `Exiftool` internally keeps `dataMining` values as 
`DMI-UNSPECIFIED`, `DMI-ALLOWED` etc., 
but exports it as `Unspecified - no prohibition defined`, `Allowed` etc. 
Conversely, you should import this attribute with values 
`Unspecified - no prohibition defined`, `Allowed` etc. 

However, with enabled `$exiftool->printConv()` we will export/import it with 
keys (`DMI-UNSPECIFIED`, `DMI-ALLOWED` etc.) instead of values.

You may inspect attribute specification for it enum values:

```php
use Codewiser\Exiftool\Exiftool;

$exiftool = new Exiftool($bin);

$values = $exiftool->specification()->topLevel()
    ->getAttributeByJsonName('dataMining')->enum();
```

For example, this is allowed values for `modelReleaseStatus` attribute:

```php
[
    'MR-NON' => 'None',
    'MR-NAP' => 'Not Applicable',
    'MR-UMR' => 'Unlimited Model Releases',
    'MR-LMR' => 'Limited or Incomplete Model Releases',
]
```

If you call `enum()` on this attribute without enabling `printConv`, you will 
gat values of this array. If you call `enum()` on this attribute with enabled 
`printConv`, you will gat keys of this array.

## Controlled Vocabularies

Some attributes, such as `sceneCodes`, `subjectCodes` and some others, 
should use values from external controlled vocabularies, called 
[NewsCodes](https://iptc.org/standards/newscodes/).

[Read more](laravel.md#controlled-vocabularies)