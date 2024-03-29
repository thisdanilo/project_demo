<?php

namespace Modules\Client\Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use Modules\Client\Entities\Client;

class ClientControllerTest extends TestCase
{
    protected $user;

    protected function setup(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_route_index()
    {
        $response = $this->actingAs($this->user)->get(route('client.index'));

        $response->assertSuccessful();

        $response->assertSee('Clientes');
    }

    public function test_route_create()
    {
        $response = $this->actingAs($this->user)->get(route('client.create'));

        $response->assertSuccessful();

        $response->assertSee('Clientes');
    }

    public function test_route_store()
    {
        $data = [
            'name' => 'Danilo Oliveira',
            'date_birthday' => '2021-10-21',
            'genre' => Client::MALE,
            'active' => true,
            'price' => '100.00'
        ];

        $response = $this->actingAs($this->user)->post(route('client.store'), $data);

        $response->assertRedirect(route('client.index'));

        $response->assertSessionHas('message', 'Cadastro realizado com sucesso.');

        $this->assertDatabaseCount('clients', 1);

        $this->assertDatabaseHas('clients', [
            'name' => 'Danilo Oliveira'
        ]);
    }

    public function test_route_show()
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user)->get(route('client.show', [
            'id' => $client->id
        ]));

        $response->assertSuccessful();

        $response->assertSee($client->name);
    }

    public function test_route_edit()
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user)->get(route('client.edit', [
            'id' => $client->id
        ]));

        $response->assertSuccessful();

        $response->assertSee($client->name);
    }

    public function test_route_update()
    {
        $client = Client::factory()->create();

        $data = [
            'name' => 'Danilo Oliveira',
            'date_birthday' => '2021-10-21',
            'genre' => Client::MALE,
            'active' => true,
            'price' => '100.00'
        ];

        $response = $this->actingAs($this->user)->put(route('client.update', $client->id), $data);

        $response->assertRedirect(route('client.edit', $client->id));

        $response->assertSessionHas('message', 'Atualização realizada com sucesso.');

        $this->assertDatabaseHas('clients', [
            'name' => 'Danilo Oliveira'
        ]);
    }

    public function test_route_confirm_delete()
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user)->get(route('client.confirm_delete', [
            'id' => $client->id
        ]));

        $response->assertSuccessful();

        $response->assertSee($client->name);
    }

    public function test_route_delete()
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('client.delete', [
            'id' =>  $client->id
        ]));

        $response->assertRedirect(route('client.index'));

        $response->assertSessionHas('message', 'Exclusão realizada com sucesso.');

        $this->assertDeleted('clients', $client->toArray());

        $this->assertSoftDeleted($client);

        $this->assertDatabaseCount('clients', 1);
    }
}
