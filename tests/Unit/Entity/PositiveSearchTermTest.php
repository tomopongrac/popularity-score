<?php

namespace App\Tests\Unit\Entity;

use App\Entity\PositiveSearchTerm;
use PHPUnit\Framework\TestCase;

class PositiveSearchTermTest extends TestCase
{
    private const SEARCH_TERM = 'php';
    private const TAIL_WORD = 'rocks';
    private $positiveSearchTerm;

    protected function setUp(): void
    {
        parent::setUp();

        $this->positiveSearchTerm = new PositiveSearchTerm(self::SEARCH_TERM);
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
