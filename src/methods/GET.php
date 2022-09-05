<?php

namespace src\methods;

use ReflectionMethod;
use src\responses\ErrorResponse;

class GET implements HttpMethod {
    public function getData(ReflectionMethod $reflection): array {
        $args = [];
        foreach($reflection->getParameters() as $arg) {
            $args[$arg->name] = $_REQUEST[$arg->name] ?? null;
            if (is_null($args[$arg->name]) && !$arg->isOptional()) {
                (new ErrorResponse("Необходимо указать параметр '$arg->name'"))->endSession();
            }
        }

        return $args;
    }
}