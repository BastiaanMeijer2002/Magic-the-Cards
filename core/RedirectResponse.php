<?php

namespace Core;

class RedirectResponse
{
    public function __construct(string $uri)
    {
        header("Location: $uri", true, 302);
    }

}