<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyteamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user=Auth::user();
        $team=$user->team;

        if(!$team){
            abort('404',__('You do not have a team'));
        }

        $team->load(['leader.media','students.user.media','students.user.student','leader.student']);

        return TeamResource::make($team)
        ->additional([
            'message'=>ResponseMessages::RETRIEVED->message()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
