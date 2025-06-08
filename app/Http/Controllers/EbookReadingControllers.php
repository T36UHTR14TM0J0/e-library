<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\EbookReading;
use Illuminate\Http\Request;

class EbookReadingControllers extends Controller
{
    public function startReading(Request $request)
    {
        $request->validate([
            'ebook_id' => 'required|exists:ebooks,id'
        ]);

        $reading = EbookReading::create([
            'user_id' => auth()->id(),
            'ebook_id' => $request->ebook_id
        ]);

        return response()->json([
            'status' => 'success',
            'session_id' => $reading->id
        ]);
    }

    
}
