<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|Plain $registryIdentifier Organisation Identifier.
 * @property null|Plain $assetIdentifier Item Identifier.
 * @property null|Plain $role Role.
 */
interface RegistryEntry extends Structure
{

}
