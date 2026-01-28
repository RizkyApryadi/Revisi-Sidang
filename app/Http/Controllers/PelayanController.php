<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PelayanController extends Controller
{
	/**
	 * Display the admin pelayanan ibadah index view.
	 */
	public function index()
	{
		return view('pages.admin.MasterData.pelayan.index');
	}
}
