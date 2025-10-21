<?php
namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $flights = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'fares.cabinClass'])
            ->take(5)
            ->get()
            ->map(function ($flight) {
                $minFare = $flight->fares->min('Price');

                return (object) [
                    'id' => $flight->FlightID,
                    'airline_name' => $flight->airline->Name ?? '',
                    'airline_logo' => asset('storage/airlines/' . ($flight->airline->Logo ?? 'default.png')),
                    'from' => $flight->departureAirport->City . ' (' . $flight->departureAirport->Code . ')',
                    'to' => $flight->arrivalAirport->City . ' (' . $flight->arrivalAirport->Code . ')',
                    'departure_date' => date('d/m/Y', strtotime($flight->DepartureTime)),
                    'fare' => $minFare ?? 0,
                    'departure_time' => date('H:i', strtotime($flight->DepartureTime)),
                    'arrival_time' => date('H:i', strtotime($flight->ArrivalTime)),
                ];
            });

        return view('home', compact('flights'));
    }

    public function show($id)
    {
        $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'fares.cabinClass'])
                        ->findOrFail($id);

        $minFare = $flight->fares->min('Price');

        $flightDetail = (object) [
            'id' => $flight->FlightID,
            'airline_name' => $flight->airline->Name ?? '',
            'airline_logo' => asset('storage/airlines/' . ($flight->airline->Logo ?? 'default.png')),
            'from' => $flight->departureAirport->City . ' (' . $flight->departureAirport->Code . ')',
            'to' => $flight->arrivalAirport->City . ' (' . $flight->arrivalAirport->Code . ')',
            'departure_time' => date('H:i', strtotime($flight->DepartureTime)),
            'arrival_time' => date('H:i', strtotime($flight->ArrivalTime)),
            'departure_date' => date('d/m/Y', strtotime($flight->DepartureTime)),
            'fare' => $minFare ?? 0,
        ];

        return view('users.detail', compact('flight', 'flightDetail'));
    }
}
?>