@extends('provider::layouts.master')

@section('title', 'Fornecedores')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-10">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item">Fornecedores</li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')

    {{-- Respostas --}}
    @include('dashboard.partials.errors')
    @include('dashboard.partials.success')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('provider.update', $provider->id) }}" method="post">

                    {{-- Elementos Ocultos --}}
                    @csrf
                    @method('PUT')

                    <div class="card card-outline card-secondary">
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Dados da Fornecedores
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">


                                <div class="row">

                                    {{-- CNPJ --}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>CNPJ:<span class="text-danger">*</span></label>
                                            <input type="text" name="cnpj" class="form-control mask-cnpj" required value="{{ $provider->cnpj }}">
                                        </div>
                                    </div>


                                    {{-- Razão social --}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Razão social:<span class="text-danger">*</span></label>
                                            <input type="text" name="corporate_name" class="form-control" required value="{{ $provider->corporate_name }}">
                                        </div>
                                    </div>


                                    {{-- Nome Fantasia --}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Nome Fantasia:<span class="text-danger">*</span></label>
                                            <input type="text" name="fantasy_name" class="form-control" required value="{{ $provider->fantasy_name }}">
                                        </div>
                                    </div>

                                    {{-- Ativo --}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Ativo:<span class="text-danger">*</span></label>
                                            <select name="active" class="form-control select2" style="width: 100%;" required>
                                                <option value="1" @if ($provider->active) selected @endif>Sim</option>
                                                <option value="0" @if (!$provider->active)  selected @endif>Não</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer"></div>

                        </div>
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Observação
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">

                                    {{-- Observação --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Observação:</label>
                                            <textarea name="note" id="summernote" cols="50" rows="5" class="form-control">{!! $provider->note !!}</textarea>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="card-footer"></div>

                        </div>

                        {{-- Botão que salva os dados --}}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-plus fa-fw"></i> Salvar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('footer-extras')
    <script src="{{ mix('js/provider.js') }}"></script>
@endsection
