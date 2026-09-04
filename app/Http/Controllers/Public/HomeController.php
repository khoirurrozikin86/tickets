<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Public/Home');
    }

    public function checkout()
    {
        return Inertia::render('Public/TicketCheckout');
    }



    public function reservation()
    {
        return Inertia::render('Public/Reservation');
    }
}
