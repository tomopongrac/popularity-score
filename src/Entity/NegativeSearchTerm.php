<?php

declare(strict_types=1);

namespace App\Entity;

class NegativeSearchTerm implements SearchTerm
{
    private const TAIL_WORD = 'sucks';

    private $searchTerm;
    private $searchPhrase;

    public function __construct(string $searchTerm)
    {
        $this->searchTerm = $searchTerm;
        $this->searchPhrase = $searchTerm.self::TAIL_WORD;
    }

    public function getSearchTerm(): string
    {
        return $this->searchTerm;
    }

    public function getSearchPhrase(): string
    {
        return $this->searchPhrase;
    }
}