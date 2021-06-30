<?php

namespace App\Tests\Unit\Entity;

use App\Entity\NegativeSearchTerm;
use PHPUnit\Framework\TestCase;

class NegativeSearchTermTest extends TestCase
{
    private const SEARCH_TERM = 'php';
    private const TAIL_WORD = 'sucks';
    private $positiveSearchTerm;

    protected function setUp(): void
    {
        parent::setUp();

        $this->positiveSearchTerm = new NegativeSearchTerm(self::SEARCH_TERM);
    }

    /** @test */
    public function it_creates_search_phrase()
    {
        $this->assertEquals(self::SEARCH_TERM.self::TAIL_WORD, $this->positiveSearchTerm->getSearchPhrase());
    }

    /** @test */
    public function it_gets_search_term()
    {
        $this->assertEquals(self::SEARCH_TERM, $this->positiveSearchTerm->getSearchTerm());
    }
}
