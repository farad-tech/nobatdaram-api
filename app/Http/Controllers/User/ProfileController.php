<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = auth()->id();

        $profile = Profile::where('user_id', $user_id)->first();

        if ($profile == null) {

            $profile = Profile::create([
                'avatar' => '/storage/avatar.jpg',
                'name' => Str::random(4) . '-' . rand(100, 999),
                'user_id' => $user_id,
            ]);
        }

        return response($profile);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'avatar' => 'file|image',
            'name' => 'required',
        ]);

        $user_id = auth()->id();

        $data = [
            'name' => $request->name,
        ];

        if($request->avatar !== null) {
            $data['avatar'] = '/storage/'.Storage::disk('public')->put('/avatars', $request->file('avatar'));       
        }

        Profile::where('user_id', $user_id)->update($data);

        return response('Profile updated!');
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

    public function deleteAvatar()
    {

        $profile = Profile::where('user_id', auth()->id())->first();

        Storage::disk('public')->delete($profile->avatar);

        $profile->avatar = '/storage/avatar.jpg';

        $profile->save();

        return response('Avatar removed');

    }
}
