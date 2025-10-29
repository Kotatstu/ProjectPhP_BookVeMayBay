<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $minFareSubquery = DB::table('Fares')
            ->select('FlightID', DB::raw('MIN(BasePrice) as fare'))
            ->groupBy('FlightID');

        $flights = DB::table('Flights')
            ->join('Airlines', 'Flights.AirlineID', '=', 'Airlines.AirlineID')
            ->join('Airports as dep', 'Flights.DepartureAirport', '=', 'dep.AirportCode')
            ->join('Airports as arr', 'Flights.ArrivalAirport', '=', 'arr.AirportCode')
            ->leftJoinSub($minFareSubquery, 'minfare', function ($join) {
                $join->on('Flights.FlightID', '=', 'minfare.FlightID');
            })
            ->select(
                'Flights.FlightID as id',
                'Airlines.AirlineName as airline_name',
                'Airlines.LogoURL as airline_logo',
                'dep.City as from_city',
                'arr.City as to_city',
                'Flights.DepartureTime as departure_time',
                DB::raw('ISNULL(minfare.fare, 0) as fare')
            )
            ->orderBy('Flights.DepartureTime', 'asc')
            ->get();

        return view('home', compact('flights'));
    }

    public function show($id)
    {
        $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'fares.cabinClass'])
            ->findOrFail($id);

        $minFare = $flight->fares->map(function ($fare) {
            return $fare->BasePrice + $fare->Tax;
        })->min();

        $flightDetail = (object) [
            'id' => $flight->FlightID,
            'airline_name' => $flight->airline->AirlineName ?? '',
            'airline_logo' => asset('storage/airline/' . ($flight->airline->LogoURL ?? 'temp.png')),
            'from' => $flight->departureAirport->City . ' (' . $flight->departureAirport->AirportCode . ')',
            'to' => $flight->arrivalAirport->City . ' (' . $flight->arrivalAirport->AirportCode . ')',
            'departure_time' => date('H:i', strtotime($flight->DepartureTime)),
            'arrival_time' => date('H:i', strtotime($flight->ArrivalTime)),
            'departure_date' => date('d/m/Y', strtotime($flight->DepartureTime)),
            'fare' => $minFare ?? 0,
        ];

        return view('users.detail', compact('flight', 'flightDetail'));
    }
}
