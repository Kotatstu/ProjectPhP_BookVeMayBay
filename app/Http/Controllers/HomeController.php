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
        /* ======================================================
        * 1. Lấy thông tin chuyến bay + airline + fares + airports
        * ====================================================== */
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

        // Đóng gói dữ liệu chuyến bay
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

        /* ======================================================
        * 2. Lấy danh sách pixel vị trí sân bay
        * ====================================================== */
        $airportPixels = [
            'BKK' => ['x' => 1050, 'y' => 650],
            'BMV' => ['x' => 400,  'y' => 500],
            'CAH' => ['x' => 420,  'y' => 520],
            'CDG' => ['x' => 1350, 'y' => 300],
            'CGK' => ['x' => 1080, 'y' => 700],
            'CXR' => ['x' => 980,  'y' => 580],
            'DAD' => ['x' => 1000, 'y' => 600],
            'DIN' => ['x' => 1020, 'y' => 610],
            'DLI' => ['x' => 1010, 'y' => 620],
            'DMK' => ['x' => 1040, 'y' => 645],
            'DOH' => ['x' => 900,  'y' => 350],
            'DXB' => ['x' => 920,  'y' => 340],
            'FRA' => ['x' => 1300, 'y' => 280],
            'GMP' => ['x' => 1180, 'y' => 410],
            'HAN' => ['x' => 911,  'y' => 240],
            'HKG' => ['x' => 1220, 'y' => 450],
            'HND' => ['x' => 1360, 'y' => 420],
            'HPH' => ['x' => 925,  'y' => 245],
            'HUI' => ['x' => 990,  'y' => 610],
            'ICN' => ['x' => 1200, 'y' => 420],
            'JFK' => ['x' => 200,  'y' => 300],
            'KIX' => ['x' => 1380, 'y' => 440],
            'KUL' => ['x' => 1060, 'y' => 760],
            'LAX' => ['x' => 150,  'y' => 320],
            'LHR' => ['x' => 1320, 'y' => 290],
            'LPQ' => ['x' => 950,  'y' => 500],
            'MEL' => ['x' => 1400, 'y' => 800],
            'MNL' => ['x' => 1100, 'y' => 750],
            'NRT' => ['x' => 1350, 'y' => 430],
            'PEK' => ['x' => 1250, 'y' => 360],
            'PNH' => ['x' => 970,  'y' => 620],
            'PQC' => ['x' => 1080, 'y' => 770],
            'PVG' => ['x' => 1260, 'y' => 370],
            'PXU' => ['x' => 1015, 'y' => 635],
            'REP' => ['x' => 985,  'y' => 625],
            'SFO' => ['x' => 180,  'y' => 310],
            'SGN' => ['x' => 265,  'y' => 157],
            'SIN' => ['x' => 1070, 'y' => 780],
            'SYD' => ['x' => 1450, 'y' => 820],
            'TBB' => ['x' => 1060, 'y' => 655],
            'THD' => ['x' => 1025, 'y' => 605],
            'TPE' => ['x' => 1300, 'y' => 410],
            'UIH' => ['x' => 995,  'y' => 615],
            'VCA' => ['x' => 1020, 'y' => 640],
            'VCL' => ['x' => 1010, 'y' => 630],
            'VDO' => ['x' => 1030, 'y' => 650],
            'VII' => ['x' => 1015, 'y' => 645],
            'VTE' => ['x' => 950,  'y' => 505],
            'YVR' => ['x' => 100,  'y' => 280],
            'YYZ' => ['x' => 220,  'y' => 310],
        ];

        $fromCode = $flight->departureAirport->AirportCode;
        $toCode   = $flight->arrivalAirport->AirportCode;

        if (!isset($airportPixels[$fromCode]) || !isset($airportPixels[$toCode])) {
            abort(404, "Pixel map for airport not found.");
        }

        $pixelStart = $airportPixels[$fromCode];
        $pixelEnd   = $airportPixels[$toCode];

        /* ======================================================
        * 3. Lấy danh sách ghế + trạng thái + cabin
        * ====================================================== */
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

        // Cabin class name
        $cabinClassMap = DB::table('CabinClasses')->pluck('ClassName', 'CabinClassID')->toArray();
        $availableSeats->transform(
            fn($seat) => (object) array_merge((array)$seat, [
                'CabinClassName' => $cabinClassMap[$seat->CabinClassID] ?? 'Không xác định'
            ])
        );

        $seatsByCabin = $availableSeats->groupBy('CabinClassName');

        $availableCabinClassIds = $flight->fares->pluck('CabinClassID')->toArray();

        /* ======================================================
        * 4. Lấy phương thức thanh toán
        * ====================================================== */
        $paymentMethods = collect();
        $customerId = null;

        if ($user = Auth::user()) {
            $customerId = DB::table('Customers')->where('UserID', $user->id)->value('CustomerID');
            if ($customerId) {
                $paymentMethods = DB::table('PaymentMethods')->where('CustomerID', $customerId)->get();
            }
        }

        /* ======================================================
        * 5. Trả toàn bộ dữ liệu sang view detail + map
        * ====================================================== */
        return view('users.detail', [
            'flight' => $flight,
            'flightDetail' => $flightDetail,

            // seats
            'availableSeats' => $availableSeats,
            'seatsByCabin' => $seatsByCabin,
            'availableCabinClassIds' => $availableCabinClassIds,

            // payment
            'paymentMethods' => $paymentMethods,
            'customerId' => $customerId,

            // map pixels
            'fromCode' => $fromCode,
            'toCode' => $toCode,
            'startX' => $pixelStart['x'],
            'startY' => $pixelStart['y'],
            'endX' => $pixelEnd['x'],
            'endY' => $pixelEnd['y'],
        ]);
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
