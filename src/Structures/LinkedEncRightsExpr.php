<?php

namespace Codewiser\Exiftool\Structures;

use Codewiser\Exiftool\Contracts\Plain;
use Codewiser\Exiftool\Contracts\Structure;

/**
 * @property null|Plain $linkedRightsExpr Link to Rights Expression.
 * @property null|Plain $rightsExprEncType Encoding type.
 * @property null|Plain $rightsExprLangId Rights Expression Language ID.
 */
interface LinkedEncRightsExpr extends Structure
{

}
