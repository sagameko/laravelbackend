<?php

namespace App\Http\Controllers;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Application::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Application::create($request->all());
    }


    public function appli(Request $request)
    {
        $validatedData = $request->validate([
            'Name' => 'required|string',
            'Email' => 'required|email',
            'Phone' => 'required|string',
            'Code' => 'required|string',
            'Skills' => 'required|string',
            'Experience' => 'required|string',
            'Time' => 'required|string',
            'Status' => 'required|string',
        ]);

        $jobApplication = JobApplication::create($validatedData);

        return response()->json(['message' => 'Form submitted successfully']);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($Code)
    {
        return response()->json(Application::where('Code', $Code)-> get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $application = Application::find($id);
        $application->update($request-> all());
        return $application;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Application::destroy($id);
    }
}
