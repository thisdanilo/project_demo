<?php

namespace Modules\Suit\Tests\Feature\Http\Controllers;

use Tests\TestCase;
use Modules\Suit\Entities\Suit;
use Modules\Client\Entities\Client;
use Modules\Product\Entities\Product;
use Modules\Suit\Entities\SuitProduct;
use Modules\Category\Entities\Category;
use Modules\Purveyor\Entities\Purveyor;

class SuitControllerTest extends TestCase
{
    public function test_route_index()
    {
        $response = $this->get(route('suit.index'));

        $response->assertSuccessful();

        $response->assertSee('Pedidos');
    }

    public function test_route_create()
    {
        $response = $this->get(route('suit.create'));

        $response->assertSuccessful();

        $response->assertSee('Pedidos');
    }

    public function test_route_store()
    {
        $client = Client::factory()->create();

        $purveyor = Purveyor::factory()->hasAttached(
            Category::factory()->hasAttached(
                Product::factory()->count(2)
            )
        )
            ->create();

        $purveyor->load('categories.products');

        $data = [
            'date' => now()->format('Y-m-d'),
            'status' => Suit::FINISHED,
            'description' => 'Teste',
            'client_id' => $client->id,
            'products' => [
                0 => [
                    'amount' => 10,
                    'price' => '200.00',
                    'purveyor_id' => $purveyor->id,
                    'category_id' => $purveyor->categories->first()->id,
                    'product_id' =>  $purveyor->categories->first()->products->first()->id
                ],
                1 => [
                    'amount' => 10,
                    'price' => '200.00',
                    'purveyor_id' => $purveyor->id,
                    'category_id' =>  $purveyor->categories->first()->id,
                    'product_id' =>  $purveyor->categories->first()->products->last()->id
                ],
            ]

        ];

        $response = $this->post(route('suit.store'), $data);

        $response->assertRedirect(route('suit.index'));

        $response->assertSessionHas('message', 'Cadastro realizado com sucesso.');

        $this->assertDatabaseCount('suits', 1);

        $this->assertDatabaseHas('suits', [
            'description' => 'Teste'
        ]);
    }

    public function test_route_show()
    {
        $suit = Suit::factory()->hasClient()->hasSuitProducts()->create();

        $response = $this->get(route('suit.show', [
            'id' => $suit->id
        ]));

        $response->assertSuccessful();

        $response->assertSee($suit->formatted_suit_date);
    }

    public function test_route_edit()
    {
        $suit = Suit::factory()->hasClient()->hasSuitProducts()->create();

        $response = $this->get(route('suit.edit', [
            'id' => $suit->id
        ]));

        $response->assertSuccessful();

        $response->assertSee($suit->formatted_suit_date);
    }

    public function test_route_update()
    {
        $suit =  Suit::factory()->create();

        $client = Client::factory()->create();

        $purveyor = Purveyor::factory()->hasAttached(
            Category::factory()->hasAttached(
                Product::factory()->count(2)
            )
        )
            ->create();

        $purveyor->load('categories.products');

        $data = [
            'date' => now()->format('Y-m-d'),
            'status' => Suit::FINISHED,
            'description' => 'Teste',
            'client_id' => $client->id,
            'products' => [
                0 => [
                    'amount' => 10,
                    'price' => '200.00',
                    'purveyor_id' => $purveyor->id,
                    'category_id' => $purveyor->categories->first()->id,
                    'product_id' =>  $purveyor->categories->first()->products->first()->id
                ],
                1 => [
                    'amount' => 10,
                    'price' => '200.00',
                    'purveyor_id' => $purveyor->id,
                    'category_id' =>  $purveyor->categories->first()->id,
                    'product_id' =>  $purveyor->categories->first()->products->last()->id
                ],
            ]

        ];

        $response = $this->put(route('suit.update', $suit->id), $data);

        $response->assertRedirect(route('suit.edit', $suit->id));

        $response->assertSessionHas('message', 'Atualização realizada com sucesso.');

        $this->assertDatabaseHas('suits', [
            'description' => 'Teste'
        ]);
    }

    public function test_route_confirm_delete()
    {
        $suit = Suit::factory()->hasClient()->create();

        $response = $this->get(route('suit.confirm_delete', [
            'id' => $suit->id
        ]));

        $response->assertSuccessful();

        $response->assertSee($suit->formatted_date);
    }

    public function test_route_delete()
    {
        $suit = Suit::factory()->create();

        $response = $this->delete(route('suit.delete', [
            'id' =>  $suit->id
        ]));

        $response->assertRedirect(route('suit.index'));

        $response->assertSessionHas('message', 'Exclusão realizada com sucesso.');

        $this->assertDeleted('suits', $suit->toArray());

        $this->assertSoftDeleted($suit);

        $this->assertDatabaseCount('suits', 1);
    }
}
