<?php

namespace App\Attribute;
use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)] // только к параметрам метода
class RequestBody {}