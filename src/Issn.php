<?php declare(strict_types=1);

namespace App;

class Issn
{
    public static function generate(): string
    {
        //TODO: put real ISSN algorithm
        return uniqid();
    }
}
