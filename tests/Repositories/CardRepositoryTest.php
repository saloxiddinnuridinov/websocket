<?php

namespace Tests\Repositories;

use App\Models\Card;
use App\Repositories\CardRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CardRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected CardRepository $cardRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cardRepo = app(CardRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_card()
    {
        $card = Card::factory()->make()->toArray();

        $createdCard = $this->cardRepo->create($card);

        $createdCard = $createdCard->toArray();
        $this->assertArrayHasKey('id', $createdCard);
        $this->assertNotNull($createdCard['id'], 'Created Card must have id specified');
        $this->assertNotNull(Card::find($createdCard['id']), 'Card with given id must be in DB');
        $this->assertModelData($card, $createdCard);
    }

    /**
     * @test read
     */
    public function test_read_card()
    {
        $card = Card::factory()->create();

        $dbCard = $this->cardRepo->find($card->id);

        $dbCard = $dbCard->toArray();
        $this->assertModelData($card->toArray(), $dbCard);
    }

    /**
     * @test update
     */
    public function test_update_card()
    {
        $card = Card::factory()->create();
        $fakeCard = Card::factory()->make()->toArray();

        $updatedCard = $this->cardRepo->update($fakeCard, $card->id);

        $this->assertModelData($fakeCard, $updatedCard->toArray());
        $dbCard = $this->cardRepo->find($card->id);
        $this->assertModelData($fakeCard, $dbCard->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_card()
    {
        $card = Card::factory()->create();

        $resp = $this->cardRepo->delete($card->id);

        $this->assertTrue($resp);
        $this->assertNull(Card::find($card->id), 'Card should not exist in DB');
    }
}
