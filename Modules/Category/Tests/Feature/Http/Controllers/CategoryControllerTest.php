<?php

namespace Modules\Category\Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use Modules\Category\Entities\Category;

class CategoryControllerTest extends TestCase
{
    protected $user;

    protected function setup(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_route_index()
    {
        $response = $this->actingAs($this->user)->get(route('category.index'));

        $response->assertSuccessful();

        $response->assertSee('Categorias');
    }

    public function test_route_create()
    {
        $response = $this->actingAs($this->user)->get(route('category.create'));

        $response->assertSuccessful();

        $response->assertSee('Categorias');
    }

    public function test_route_store()
    {
        $data = [
            'name' => 'Cat. A',
            'active' => true
        ];

        $response = $this->actingAs($this->user)->post(route('category.store'), $data);

        $response->assertRedirect(route('category.index'));

        $response->assertSessionHas('message', 'Cadastro realizado com sucesso.');

        $this->assertDatabaseCount('categories', 1);

        $this->assertDatabaseHas('categories', [
            'name' => 'Cat. A'
        ]);
    }

    public function test_route_show()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->get(route('category.show', [
            'id' => $category->id
        ]));

        $response->assertSuccessful();

        $response->assertSee($category->name);
    }

    public function test_route_edit()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->get(route('category.edit', [
            'id' => $category->id
        ]));

        $response->assertSuccessful();

        $response->assertSee($category->name);
    }

    public function test_route_update()
    {
        $category = Category::factory()->create();

        $data = [
            'name' => 'Cat. A',
            'active' => true
        ];

        $response = $this->actingAs($this->user)->put(route('category.update', $category->id), $data);

        $response->assertRedirect(route('category.edit', $category->id));

        $response->assertSessionHas('message', 'Atualização realizada com sucesso.');

        $this->assertDatabaseHas('categories', [
            'name' => 'Cat. A'
        ]);
    }

    public function test_route_confirm_delete()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->get(route('category.confirm_delete', [
            'id' => $category->id
        ]));

        $response->assertSuccessful();

        $response->assertSee($category->name);
    }

    public function test_route_delete()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('category.delete', [
            'id' =>  $category->id
        ]));

        $response->assertRedirect(route('category.index'));

        $response->assertSessionHas('message', 'Exclusão realizada com sucesso.');

        $this->assertDeleted('categories', $category->toArray());

        $this->assertSoftDeleted($category);

        $this->assertDatabaseCount('categories', 1);
    }
}
