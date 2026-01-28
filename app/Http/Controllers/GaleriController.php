<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GaleriController extends Controller
{
	/**
	 * Display the admin galeri index view.
	 */
	public function index()
	{
		// return the gallery index view; data can be added here later if needed
		return view('pages.admin.MasterData.galeri.index');
	}
}

