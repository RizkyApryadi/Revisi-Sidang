<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IbadahController extends Controller
{
	/**
	 * Display the admin ibadah index view.
	 */
	public function index()
	{
		return view('pages.admin.MasterData.ibadah.index');
	}
}
