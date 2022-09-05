<?php

namespace src\methods;

use ReflectionMethod;

interface HttpMethod {
    public function getData(ReflectionMethod $reflection);
}