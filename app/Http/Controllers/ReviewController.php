<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Policies\ReviewPolicy;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class ReviewController extends Controller
{
    protected $policy;

    public function __construct()
    {
        $this->policy = new ReviewPolicy();
    }
    
    public function index()
    {
        $reviews = Review::with(['user'])
            ->when(request('user'), function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%'.request('user').'%');
                });
            })
            ->when(request('comment'), function($query) {
                $query->where('comment', 'like', '%'.request('comment').'%');
            })
            ->when(request('rating'), function($query) {
                $query->where('rating', request('rating'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(request('per_page', 10));

        return view('reviews.index', compact('reviews'));
    }

    public function create()
    {
        return view('reviews.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $validated['user_id'] = auth()->id();

        Review::create($validated);

        return redirect()->route('reviews.index')
            ->with('success', 'Komentar berhasil ditambahkan');
    }

    public function edit(Review $review)
    {
        $response = $this->policy->update(auth()->user(), $review);
        
        if ($response->denied()) {
            return redirect()->route('reviews.index')->with('error', $response->message());
        }else{
            return view('reviews.edit', compact('review'));
        }
        
    }

    public function update(Request $request, Review $review)
    {
        $response = $this->policy->update(auth()->user(), $review);
        
        if ($response->denied()) {
            return redirect()->route('reviews.index')->with('error', $response->message());
        }else{
            $validated = $request->validate([
                'comment' => 'required|string|max:1000',
                'rating' => 'required|integer|min:1|max:5'
            ]);

            $review->update($validated);

            return redirect()->route('reviews.index')
                ->with('success', 'Komentar berhasil diperbarui');
        }

       
    }

    public function destroy(Review $review)
    {
        $response = $this->policy->delete(auth()->user(), $review);
        
        if ($response->denied()) {
            return redirect()->route('reviews.index')->with('error', $response->message());
        }else{
            $review->delete();
    
            return redirect()->route('reviews.index')
                ->with('success', 'Komentar berhasil dihapus');
        }

    }
}