<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barangay;
use App\Models\Municipality;

class ProfileController extends Controller
{
    public function showMunicipality($citymunCode)
    {
        $city = Municipality::where('citymunCode', $citymunCode)->first();

        return view('profile.show', compact('city'));
    }
}
