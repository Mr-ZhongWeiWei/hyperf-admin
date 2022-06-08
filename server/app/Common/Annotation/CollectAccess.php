<?php

namespace App\Common\Annotation;

use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * @Annotation
 * @Target("METHOD")
 * Class AccessAnnotation
 * @package App\Common\Annotation
 */
class CollectAccess extends AbstractAnnotation
{
    public $rule;

    public $logic   =   'or';
}
