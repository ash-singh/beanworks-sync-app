<?php

namespace App\Document\AuthToken;

use App\Document\Login;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class LastLogin extends Login
{
}
