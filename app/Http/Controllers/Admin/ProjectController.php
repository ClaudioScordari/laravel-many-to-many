<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Request
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

// Models
use App\Models\Project;
use App\Models\Technology;
use App\Models\Type;

// Facades
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();

        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Type::all();

        $technologies = Technology::all();

        return view('admin.projects.create', compact('types', 'technologies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $validDatas = $request->validated();

        // Comunque setto il percorso a null, così il percorso rimane null se non ce niente
        $imgPath = null;

        // Se l'input del file è pieno, mi riempio il percorso
        if (isset($validDatas['dataFile'])) {
            // Intercettare il file che mi arriva dal create
            $imgPath = Storage::disk('public')->put('img', $validDatas['dataFile']);
        }

        $project = Project::create([
            'name' => $validDatas['name'],
            'description' => $validDatas['description'],
            'type_id' => $validDatas['type_id'],
            'image_src' => $imgPath
        ]);


        // Se l'array dei tag passati è pieno...
        if (isset($validDatas['technologies'])) {

            // Scorro l'array delle checkbox e creo associazione con la nuova istanza di $project
            foreach ($validDatas['technologies'] as $oneTechId) {
                $project->technologies()->attach($oneTechId);
            }
        }

        return redirect()->route('admin.projects.show', compact('project'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $types = Type::all();

        $technologies = Technology::all();

        return view('admin.projects.edit', compact('project', 'types', 'technologies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validDatas = $request->validated();

        $project->update($validDatas); 

        /*
            Cose da fare per i file:
            1) Aggiungere img se prima non c'era;
            2) rimuovere img corrente;
            3) sostituzione dell'img con una nuova;
            4) non fare niente.
        */

        if (isset($validDatas['technologies'])) {
            $project->technologies()->sync($validDatas['technologies']);
        } 
        else {
            $project->technologies()->detach();
        }

        return redirect()->route('admin.projects.show', compact('project'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index');
    }
}
