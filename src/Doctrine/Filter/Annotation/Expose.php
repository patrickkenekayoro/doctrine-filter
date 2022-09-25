<?php

namespace Maldoinc\Doctrine\Filter\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Expose
{
    public function __construct(
        public ?string $serializedName = null,
    ) {
    }
}
