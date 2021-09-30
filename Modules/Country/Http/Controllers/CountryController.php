<?php

namespace Modules\Country\Http\Controllers;

use Yajra\DataTables\DataTables;
use Illuminate\Routing\Controller;
use Modules\Country\Http\Requests;
use Modules\Country\Entities\Country;
use Modules\Country\Http\Requests\CountryRequest;

class CountryController extends Controller
{
    protected $country;

    /**
     * Método Construtor
     *
     * @param Country $country
     * @return void
     */
    public function __construct(Country $country)
    {
        $this->country = $country;
    }

    /**
     * Exibe a tela inicial com a listagem de dados.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('country::index');
    }

    /**
     * Obtêm os dados para a tabela
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function dataTable()
    {
        $countries = $this->country->query();

        return DataTables::of($countries)
            ->addColumn("action", function ($country) {
                return '<div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
						data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-cog" aria-hidden="true"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="' . route('country.show', $country->id) . '">Ver</a>
                        <a class="dropdown-item" href="' . route('country.edit', $country->id) . '">Editar</a>
                        <a class="dropdown-item" href="' . route('country.confirm_delete', $country->id) . '">Excluir</a>
                        </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Exibe a tela de cadastro
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('country::create');
    }

    /**
     * Cadastra e retorna para a tela inicial
     *
     * @param Requests\CountryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CountryRequest $request)
    {
        $this->country->create($request->all());

        return redirect()
            ->route('country.index')
            ->with('message', 'Cadastro realizado com sucesso.');
    }

    /**
     * Exibe os dados
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $country = $this->country->findOrFail($id);

        return view('country::show', compact('country'));
    }

    /**
     * Exibe os dados para edição
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $country = $this->country->findOrFail($id);

        return view('country::edit', compact('country'));
    }

    /**
     * Atualiza e retorna para a tela de edição
     *
     * @param Requests\CountryRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CountryRequest $request, $id)
    {
        $country = $this->country->findOrFail($id);

        $country->update($request->all());

        return redirect()
            ->route('country.edit', $country->id)
            ->with('message', 'Atualização realizada com sucesso.');
    }

    /**
     * Exibe a tela para exclusão
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function confirmDelete($id)
    {
        $country = $this->country->findOrFail($id);

        return view('country::confirm-delete', compact('country'));
    }

    /**
     * Exclui e retorna para a tela inicial
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $country = $this->country->findOrFail($id);

        $country->delete();

        return redirect()
            ->route('country.index')
            ->with('message', 'Exclusão realizada com sucesso.');
    }
}
