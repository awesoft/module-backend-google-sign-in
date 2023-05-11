<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Api\Helper;

interface StateInterface
{
    public function create(): string;

    public function get(): string;

    public function validate(string $state): void;
}
