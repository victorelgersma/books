
<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NoteQuoteLinkController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'note_id' => ['required', 'exists:notes,id'],
            'quote_id' => ['required', 'exists:quotes,id'],
        ]);

        $note = Note::findOrFail($validated['note_id']);
        $note->quotes()->syncWithoutDetaching([$validated['quote_id']]);

        return back();
    }

    public function destroy(Note $note, Quote $quote): RedirectResponse
    {
        $note->quotes()->detach($quote->id);

        return back();
    }
}
