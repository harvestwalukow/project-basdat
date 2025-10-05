<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OwnerReservationController extends Controller
{
    public function index($tab = 'semua')
    {
        $validTabs = ['semua', 'today', 'upcoming', 'selesai'];
        if (!in_array($tab, $validTabs)) {
            $tab = 'semua';
        }

        return view('owner.reservations.index', compact('tab'));
    }
}