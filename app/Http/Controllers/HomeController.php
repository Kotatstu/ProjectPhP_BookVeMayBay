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
        // Lấy thông tin chuyến bay kèm airline, airport, fares
        $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'fares.cabinClass'])
            ->findOrFail($id);

        // Giá vé thấp nhất
        $minFare = $flight->fares->map(fn($fare) => $fare->BasePrice + $fare->Tax)->min() ?? 0;

        // Logo airline
        $logoFile = $flight->airline->LogoURL ?? null;
        $logoPath = public_path("images/airlines/{$logoFile}");
        $airlineLogo = ($logoFile && file_exists($logoPath))
            ? asset("images/airlines/{$logoFile}")
            : asset('images/default.jpg');

        $flightDetail = (object) [
            'id' => $flight->FlightID,
            'airline_name' => $flight->airline->AirlineName ?? '',
            'airline_logo' => $airlineLogo,
            'from' => $flight->departureAirport->City . ' (' . $flight->departureAirport->AirportCode . ')',
            'to' => $flight->arrivalAirport->City . ' (' . $flight->arrivalAirport->AirportCode . ')',
            'departure_time' => date('H:i', strtotime($flight->DepartureTime)),
            'arrival_time' => date('H:i', strtotime($flight->ArrivalTime)),
            'departure_date' => date('d/m/Y', strtotime($flight->DepartureTime)),
            'fare' => $minFare,
        ];

        // Lấy danh sách ghế kèm trạng thái
        $availableSeats = DB::table('Seats as s')
            ->leftJoin('SeatAvailability as sa', function ($join) use ($flight) {
                $join->on('s.SeatID', '=', 'sa.SeatID')
                    ->where('sa.FlightID', '=', $flight->FlightID);
            })
            ->leftJoin('Tickets as t', function ($join) use ($flight) {
                $join->on('s.SeatID', '=', 't.SeatID')
                    ->where('t.FlightID', '=', $flight->FlightID)
                    ->whereIn('t.Status', ['Chờ thanh toán', 'Đã thanh toán']);
            })
            ->where('s.AircraftID', $flight->AircraftID)
            ->select(
                's.SeatID',
                's.SeatNumber',
                's.CabinClassID',
                DB::raw('COALESCE(sa.IsBooked, 0) as IsBooked'),
                DB::raw('CASE WHEN t.TicketID IS NOT NULL THEN 1 ELSE 0 END as IsSold')
            )
            ->get()
            ->map(fn($seat) => (object) [
                'SeatID' => $seat->SeatID,
                'SeatNumber' => $seat->SeatNumber,
                'CabinClassID' => $seat->CabinClassID,
                'IsBooked' => (bool)$seat->IsBooked,
                'IsSold' => (bool)$seat->IsSold,
            ]);

        // Lấy tên CabinClass cho ghế
        $cabinClassMap = DB::table('CabinClasses')->pluck('ClassName', 'CabinClassID')->toArray();
        $availableSeats->transform(
            fn($seat) =>
            (object) array_merge((array)$seat, ['CabinClassName' => $cabinClassMap[$seat->CabinClassID] ?? 'Không xác định'])
        );

        // Nhóm ghế theo CabinClass
        $seatsByCabin = $availableSeats->groupBy('CabinClassName');

        // Lấy danh sách CabinClass có trong fares
        $availableCabinClassIds = $flight->fares->pluck('CabinClassID')->toArray();

        // Lấy phương thức thanh toán nếu user đã login
        $paymentMethods = collect();
        $customerId = null;
        if ($user = Auth::user()) {
            $customerId = DB::table('Customers')->where('UserID', $user->id)->value('CustomerID');
            if ($customerId) {
                $paymentMethods = DB::table('PaymentMethods')->where('CustomerID', $customerId)->get();
            }
        }

        return view('users.detail', compact(
            'flight',
            'flightDetail',
            'availableSeats',
            'seatsByCabin',
            'paymentMethods',
            'customerId',
            'availableCabinClassIds'
        ));
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
