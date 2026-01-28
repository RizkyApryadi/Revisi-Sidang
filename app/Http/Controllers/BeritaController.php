<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BeritaController extends Controller
{
	/**
	 * Display the admin berita index view.
	 */
	public function index()
	{
		// return the view only; data retrieval can be added later in controller if needed
		return view('pages.admin.MasterData.berita.index');
	}
}

