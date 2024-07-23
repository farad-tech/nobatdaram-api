<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\RecentView;
use Illuminate\Http\Request;

class RecentViewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recents = RecentView::where('watcher_id', auth()->id())->select('watched_id')->take(20)->get()->toArray();

        $profiles = Profile::whereIn('user_id', $recents)->get();

        return response($profiles);
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

    public static function save($watched_id)
    {
        $watcher_id = auth()->id();

        if ($watched_id != $watcher_id) {

            $view = RecentView::where('watcher_id', $watcher_id)->where('watched_id', $watched_id)->first();

            switch ($view) {
                case null:
                    $count = 1;
                    RecentView::create(compact('watcher_id', 'watched_id', 'count'));
                    break;

                default:
                    $view->count ++;
                    $view->save();
                    break;
            }
        }

    }
}
