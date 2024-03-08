@extends('layouts.app')

@section('page-title', $project->name)

@section('main-content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h1 class="my-2">
                        {{ $project->name }}
                    </h1>

                    {{-- Tipo del progetto --}}
                    <h2 class="my-3">
                        Type: {{ $project->type->name }}
                    </h2>

                    <p>
                        {{ $project->description }}
                    </p>

                    {{-- Progetti --}}
                    <div>
                        <a class="btn btn-primary" href="{{ route('admin.projects.index') }}">
                            Torna ai progetti
                        </a>
                    </div>

                    <br>

                    La dashboard è una pagina privata (protetta dal middleware)
                </div>
            </div>
        </div>
    </div>
@endsection