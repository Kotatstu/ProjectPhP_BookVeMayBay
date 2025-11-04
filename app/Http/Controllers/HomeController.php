<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $flights = DB::table('Flights')
            ->join('Airlines', 'Flights.AirlineID', '=', 'Airlines.AirlineID')
            ->join('Airports as dep', 'Flights.DepartureAirport', '=', 'dep.AirportCode')
            ->join('Airports as arr', 'Flights.ArrivalAirport', '=', 'arr.AirportCode')
            ->select(
                'Flights.FlightID as id',
                'Airlines.AirlineName as airline_name',
                'Airlines.LogoURL as airline_logo',
                'dep.City as from_city',
                'arr.City as to_city',
                'Flights.DepartureTime as departure_time'
            )
            ->orderBy('Flights.DepartureTime', 'asc')
            ->paginate(10); // phân trang 10 bản ghi mỗi trang

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

    public function search(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $date = $request->input('date');

        $flights = DB::table('Flights')
            ->join('Airlines', 'Flights.AirlineID', '=', 'Airlines.AirlineID')
            ->join('Airports as dep', 'Flights.DepartureAirport', '=', 'dep.AirportCode')
            ->join('Airports as arr', 'Flights.ArrivalAirport', '=', 'arr.AirportCode')
            ->select(
                'Flights.FlightID as id',
                'Airlines.AirlineName as airline_name',
                'Airlines.LogoURL as airline_logo',
                'dep.City as from_city',
                'arr.City as to_city',
                'Flights.DepartureTime as departure_time'
            )
            ->when($from, function ($query, $from) {
                $query->where(function ($q) use ($from) {
                    $q->where('dep.City', 'LIKE', "%$from%")
                    ->orWhere('dep.AirportCode', 'LIKE', "%$from%");
                });
            })
            ->when($to, function ($query, $to) {
                $query->where(function ($q) use ($to) {
                    $q->where('arr.City', 'LIKE', "%$to%")
                    ->orWhere('arr.AirportCode', 'LIKE', "%$to%");
                });
            })
            ->when($date, function ($query, $date) {
                $query->whereDate('Flights.DepartureTime', $date);
            })
            ->orderBy('Flights.DepartureTime', 'asc')
            ->paginate(10)
            ->appends([
                'from' => $from,
                'to' => $to,
                'date' => $date
            ]);

        return view('home', compact('flights', 'from', 'to', 'date'));
    }


}
