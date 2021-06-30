<?php

namespace App\Entity;

interface SearchTerm
{
    public function getSearchTerm(): string;

    public function getSearchPhrase(): string;
}