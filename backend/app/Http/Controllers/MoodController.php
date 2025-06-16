<?php

namespace App\Http\Controllers;

use App\Models\Mood;
use Illuminate\Http\Request;

class MoodController extends Controller
{
    public function index(Request $request)
    {
        // Lista nastrojów zalogowanego użytkownika
        return Mood::where('user_id', $request->user()->id)
                   ->orderBy('recorded_at', 'desc')
                   ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mood_value' => 'required|integer|min:1|max:10',
            'note'       => 'nullable|string',
            'recorded_at'=> 'required|date',
        ]);

        $mood = $request->user()->moods()->create($data);
        return response()->json($mood, 201);
    }

    public function show(Request $request, Mood $mood)
    {
        $this->authorize('view', $mood);
        return $mood;
    }

    public function update(Request $request, Mood $mood)
    {
        $this->authorize('update', $mood);
        $data = $request->validate([
            'mood_value' => 'integer|min:1|max:10',
            'note'       => 'nullable|string',
            'recorded_at'=> 'date',
        ]);
        $mood->update($data);
        return $mood;
    }

    public function destroy(Request $request, Mood $mood)
    {
        $this->authorize('delete', $mood);
        $mood->delete();
        return response()->json(null, 204);
    }
}
