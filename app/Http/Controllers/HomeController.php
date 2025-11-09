<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function show(Request $request, $id)
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

        // Lấy hạng vé đã chọn (mặc định hạng vé đầu tiên)
        $selectedFareId = $request->input('fare_id');
        $selectedFare = $selectedFareId
            ? $flight->fares->where('FareID', $selectedFareId)->first()
            : $flight->fares->first();

        // Lấy ghế trống theo hạng vé
        $availableSeats = $selectedFare
            ? DB::table('Seats as s')
            ->leftJoin('SeatAvailability as sa', function ($join) use ($flight) {
                $join->on('s.SeatID', '=', 'sa.SeatID')
                    ->where('sa.FlightID', '=', $flight->FlightID);
            })
            ->where('s.AircraftID', $flight->AircraftID)
            ->where('s.CabinClassID', $selectedFare->CabinClassID)
            ->where(function ($query) {
                $query->where('sa.IsBooked', 0)
                    ->orWhereNull('sa.IsBooked');  // ghế chưa có record -> coi là trống
            })
            ->select('s.SeatID', 's.SeatNumber')
            ->get()
            : collect();
            
        $selectedSeat = $availableSeats->first();

        // Phương thức thanh toán của user (nếu đã đăng nhập)
        $user = Auth::user();
        $paymentMethods = collect();
        $customerId = null;
        if ($user) {
            // Lấy CustomerID từ bảng Customers
            $customerId = DB::table('Customers')->where('UserID', $user->id)->value('CustomerID');

            if ($customerId) {
                $paymentMethods = DB::table('PaymentMethods')
                    ->where('CustomerID', $customerId)
                    ->get();
            }
        }

        return view('users.detail', compact('flight', 'flightDetail', 'availableSeats', 'paymentMethods', 'selectedFare', 'customerId', 'selectedSeat'));
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
