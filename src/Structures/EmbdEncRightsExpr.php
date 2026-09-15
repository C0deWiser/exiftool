<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|Plain $encRightsExpr Rights expression.
 * @property null|Plain $rightsExprEncType Encoding type.
 * @property null|Plain $rightsExprLangId Rights expression language ID.
 */
interface EmbdEncRightsExpr extends Structure
{

}
