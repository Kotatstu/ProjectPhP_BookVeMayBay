<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\adminRole;
use App\Models\Flight;
use App\Models\Aircraft;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\CabinClass;
use App\Models\Customer;
use App\Models\Fare;
use App\Models\PaymentMethod;
use App\Models\Ticket;
use Carbon\Carbon;

class AdminController extends BaseController
{
    public function __construct()
    {
        // Bắt buộc phải đăng nhập mới được vào
        $this->middleware('auth');
    }

    public function listMembers()
    {
        $members = [
            ['name' => 'Nguyễn Hoàng Long', 'mssv' => '2001222438', 'contribution' => '40%'],
            ['name' => 'Tô Minh Lợi', 'mssv' => '2001222485', 'contribution' => '30%'],
            ['name' => 'Lê Thái Toàn', 'mssv' => '2001224457', 'contribution' => '30%'],
        ];

        return view('admin.members', compact('members'));
    }

    private function authorizeAdmin()
    {
        $user = Auth::user();
        $isAdmin = adminRole::where('U_ID', $user->id)->exists();
        if (!$isAdmin) {
            return redirect('/home')->with('error', 'Bạn không có quyền truy cập trang admin.')->send();
            // send() để dừng thực hiện hàm hiện tại sau redirect
        }
    }


    public function index()
    {
        $this->authorizeAdmin();

        return view('admin.index');
    }

    public function usersList()
    {
        $this->authorizeAdmin();

        $user = Auth::user();
        // Lấy tất cả người dùng từ bảng 'users'
        $users = DB::table('users')->get();

        // Trả về view với danh sách người dùng
        return view('admin.users', compact('users'));
    }

    // Phương thức sửa thông tin người dùng
    public function editUser($id)
    {
        $this->authorizeAdmin();

        // Lấy thông tin người dùng dựa trên ID
        $user = User::findOrFail($id);
        $isAdmin = adminRole::where('U_ID', $user->id)->exists();

        return view('admin.editUser', compact('user', 'isAdmin'));
    }

    public function updateUser(Request $request, $id)
    {
        $this->authorizeAdmin();

        $user = User::findOrFail($id);
        
        $user->name = $request->name;
        $user->email = $request->email;

        if (!empty($request->password)) {
            $user->password = $request->password;
        }

        $user->save();

        if ($request->has('is_admin')) {
        // Nếu có tick => thêm vào bảng adminRole nếu chưa có
            adminRole::updateOrCreate(['U_ID' => $user->id]);
        } else {
            // Nếu bỏ tick => xóa quyền admin
            adminRole::where('U_ID', $user->id)->delete();
        }

        // Quay lại danh sách người dùng
        return redirect()->route('admin.users')->with('success', 'Cập nhật thông tin người dùng thành công.');
    }

    // Phương thức xóa người dùng
    public function deleteUser($id)
    {
        $this->authorizeAdmin();

        // Xóa người dùng từ bảng 'users'
        DB::table('users')->where('id', $id)->delete();

        // Redirect về trang danh sách người dùng với thông báo
        return redirect()->route('admin.users')->with('success', 'Người dùng đã được xóa.');
    }

    public function flightsList()
    {
        $this->authorizeAdmin();

        $flights = Flight::orderBy('DepartureTime', 'asc')->get();

        return view('admin.flights', compact('flights'));
    }

    public function flightDetail($id)
    {
        $this->authorizeAdmin();

        $flight = Flight::with([
            'airline',
            'aircraft',
            'departureAirport',
            'arrivalAirport',
            'fares.cabinClass'
        ])->findOrFail($id);

        // Lấy danh sách ghế đã được đặt từ SeatAvailability
        $bookedSeats = DB::table('SeatAvailability')
            ->where('FlightID', $flight->FlightID)
            ->where('IsBooked', 1)
            ->pluck('SeatID') // chỉ cần SeatID, giả sử SeatID từ 1 đến 116
            ->toArray();

        return view('admin.flightDetail', compact('flight', 'bookedSeats'));
    }

    public function createFlight()
    {
        $this->authorizeAdmin();

        $airlines = \App\Models\Airline::all();
        $aircrafts = \App\Models\Aircraft::all();
        $airports = \App\Models\Airport::all();

        return view('admin.createFlight', compact('airlines', 'aircrafts', 'airports'));
    }

    public function storeFlight(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'AirlineID' => 'required|exists:Airlines,AirlineID',
            'FlightNumber' => 'required|string|max:10|unique:Flights,FlightNumber',
            'AircraftID' => 'required|exists:Aircrafts,AircraftID',
            'DepartureAirport' => 'required|exists:Airports,AirportCode|different:ArrivalAirport',
            'ArrivalAirport' => 'required|exists:Airports,AirportCode|different:DepartureAirport',
            'DepartureTime' => 'required|date|before:ArrivalTime',
            'ArrivalTime' => 'required|date|after:DepartureTime',
            'Status' => 'required|string',
        ]);

        // Chuyển đổi datetime-local (2025-10-26T14:00) thành định dạng SQL hợp lệ
        $validated['DepartureTime'] = Carbon::parse($validated['DepartureTime'])->format('Y-m-d H:i:s');
        $validated['ArrivalTime']   = Carbon::parse($validated['ArrivalTime'])->format('Y-m-d H:i:s');

        Flight::create($validated);

        return redirect()->route('admin.flights')->with('success', 'Thêm chuyến bay mới thành công!');
    }

    public function editFlight($id)
    {
        $this->authorizeAdmin();

        $flight = Flight::findOrFail($id);
        $airlines = Airline::all();
        $aircrafts = Aircraft::all();
        $airports = Airport::all();

        return view('admin.editFlight', compact('flight', 'airlines', 'aircrafts', 'airports'));
    }

    public function updateFlight(Request $request, $id)
    {
        $this->authorizeAdmin();

        $flight = Flight::findOrFail($id);

        $validated = $request->validate([
            'AirlineID' => 'required|exists:Airlines,AirlineID',
            'FlightNumber' => 'required|string|max:10|unique:Flights,FlightNumber,' . $id . ',FlightID',
            'AircraftID' => 'required|exists:Aircrafts,AircraftID',
            'DepartureAirport' => 'required|exists:Airports,AirportCode|different:ArrivalAirport',
            'ArrivalAirport' => 'required|exists:Airports,AirportCode|different:DepartureAirport',
            'DepartureTime' => 'required|date|before:ArrivalTime',
            'ArrivalTime' => 'required|date|after:DepartureTime',
            'Status' => 'required|string',
        ]);

        //Chuyển đổi định dạng datetime-local => SQL datetime
        $validated['DepartureTime'] = Carbon::parse($validated['DepartureTime'])->format('Y-m-d H:i:s');
        $validated['ArrivalTime']   = Carbon::parse($validated['ArrivalTime'])->format('Y-m-d H:i:s');

        $flight->update($validated);

        return redirect()->route('admin.flights')->with('success', 'Cập nhật chuyến bay thành công!');
    }

    public function deleteFlight($id)
    {
        $this->authorizeAdmin();

        $flight = Flight::findOrFail($id);
        $flight->delete();

        return redirect()->route('admin.flights')->with('success', 'Đã xóa chuyến bay!');
    }

    public function listAircraft() 
    {
        $this->authorizeAdmin();

        $aircrafts = Aircraft::all();
        return view('admin.aircrafts', compact('aircrafts'));
    }

    public function createAircraft() 
    {
        $this->authorizeAdmin();

        return view('admin.createAircraft');
    }

    public function storeAircraft(Request $request) 
    {
        $this->authorizeAdmin();

        $request->validate([
            'AircraftCode' => 'required|unique:Aircrafts',
            'AircraftType' => 'required',
        ]);

        Aircraft::create($request->all());
        return redirect()->route('admin.aircrafts.index')->with('success', 'Thêm máy bay thành công!');
    }

    public function editAircraft($id)
    {
        $this->authorizeAdmin();

        $aircraft = Aircraft::findOrFail($id);
        return view('admin.editAircraft', compact('aircraft'));
    }

    public function updateAircraft(Request $request, $id)
    {
        $this->authorizeAdmin();

        $aircraft = Aircraft::findOrFail($id);
        $request->validate(['AircraftType' => 'required']);
        $aircraft->update($request->all());
        return redirect()->route('admin.aircrafts.index')->with('success', 'Cập nhật máy bay thành công!');
    }

    public function deleteAircraft($id) 
    {
        $this->authorizeAdmin();

        Aircraft::findOrFail($id)->delete();
        return redirect()->route('admin.aircrafts.index')->with('success', 'Xóa máy bay thành công!');
    }

    public function listAirline() 
    {
        $this->authorizeAdmin();

        $airlines = Airline::all();
        return view('admin.airlines', compact('airlines'));
    }

    public function createAirline() 
    {
        $this->authorizeAdmin();

        return view('admin.createAirline');
    }

    public function storeAirline(Request $request) 
    {
        $this->authorizeAdmin();

        $request->validate([
            'AirlineID' => 'required|unique:Airlines',
            'AirlineName' => 'required',
            'Country' => 'required',
            'LogoURL' => 'nullable',
        ]);

        Airline::create($request->all());
        return redirect()->route('admin.airlines.index')->with('success', 'Thêm hãng hàng không thành công!');
    }

    public function editAirline($id) 
    {
        $this->authorizeAdmin();

        $airline = Airline::findOrFail($id);
        return view('admin.editAirline', compact('airline'));
    }

    public function updateAirline(Request $request, $id) 
    {
        $this->authorizeAdmin();

        $airline = Airline::findOrFail($id);
        $request->validate([
            'AirlineName' => 'required',
            'Country' => 'required',
            'LogoURL' => 'nullable',
        ]);

        $airline->update($request->all());
        return redirect()->route('admin.airlines.index')->with('success', 'Cập nhật hãng hàng không thành công!');
    }

    public function deleteAirline($id) 
    {
        $this->authorizeAdmin();

        Airline::findOrFail($id)->delete();
        return redirect()->route('admin.airlines.index')->with('success', 'Xóa hãng hàng không thành công!');
    }


    public function listAirport() 
    {
        $this->authorizeAdmin();

        $airports = Airport::all();
        return view('admin.airports', compact('airports'));
    }

    public function createAirport() 
    {
        $this->authorizeAdmin();

        return view('admin.createAirport');
    }

    public function storeAirport(Request $request) 
    {
        $this->authorizeAdmin();

        $request->validate([
            'AirportCode' => 'required|unique:Airports',
            'AirportName' => 'required',
            'City' => 'required',
            'Country' => 'required',
            'TimeZone' => 'required',
        ]);

        Airport::create($request->all());
        return redirect()->route('admin.airports.index')->with('success', 'Thêm sân bay thành công!');
    }

    public function editAirport($id)
    {
        $this->authorizeAdmin();

        $airport = Airport::findOrFail($id);
        return view('admin.editAirport', compact('airport'));
    }

    public function updateAirport(Request $request, $id) 
    {
        $this->authorizeAdmin();

        $airport = Airport::findOrFail($id);
        $request->validate([
            'AirportName' => 'required',
            'City' => 'required',
            'Country' => 'required',
            'TimeZone' => 'required',
        ]);

        $airport->update($request->all());
        return redirect()->route('admin.airports.index')->with('success', 'Cập nhật sân bay thành công!');
    }

    public function deleteAirport($id) 
    {
        $this->authorizeAdmin();

        Airport::findOrFail($id)->delete();
        return redirect()->route('admin.airports.index')->with('success', 'Xóa sân bay thành công!');
    }

    public function listTickets()
    {
        $this->authorizeAdmin();

        $tickets = Ticket::with([
            'customer.user',
            'fare.cabinClass',
            'paymentMethod'
        ])->get();

        return view('admin.tickets', compact('tickets'));
    }

    public function editTicket($id)
    {
        $this->authorizeAdmin();

        $ticket = Ticket::findOrFail($id);
        $customers = Customer::with('user')->get();
        $fares = Fare::with('cabinClass')->get();
        $methods = PaymentMethod::all();

        return view('admin.editTicket', compact('ticket', 'customers', 'fares', 'methods'));
    }

    public function updateTicket(Request $request, $id)
    {
        $this->authorizeAdmin();

        $ticket = Ticket::findOrFail($id);

        $validated = $request->validate([
            'CustomerID' => 'required|exists:Customers,CustomerID',
            'FareID' => 'required|exists:Fares,FareID',
            'PaymentMethodID' => 'required|integer',
            'TotalAmount' => 'required|numeric|min:0',
            'Status' => 'required|string|max:50',
        ]);

        $ticket->update($validated);

        return redirect()->route('admin.tickets.index')->with('success', 'Cập nhật vé thành công!');
    }
    

    public function deleteTicket($id)
    {
        $this->authorizeAdmin();

        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('admin.tickets.index')->with('success', 'Đã xóa vé thành công!');
    }

    public function listFare()
    {
        $this->authorizeAdmin();

        $fares = Fare::with(['flight.departureAirport', 'flight.arrivalAirport', 'cabinClass'])->get();

        return view('admin.Fares', compact('fares'));
    }

    public function editFare($id)
    {
        $this->authorizeAdmin();

        $fare = Fare::findOrFail($id);
        $flights = Flight::all();
        $classes = CabinClass::all();

        return view('admin.editFare', compact('fare', 'flights', 'classes'));
    }

    public function updateFare(Request $request, $id)
    {
        $this->authorizeAdmin();

        $fare = Fare::findOrFail($id);
        $fare->update($request->all());

        return redirect()->route('admin.fares.index')->with('success', 'Cập nhật giá vé thành công!');
    }

    public function destroyFare($id)
    {
        $this->authorizeAdmin();

        $fare = Fare::findOrFail($id);
        $fare->delete();

        return redirect()->route('admin.fares.index')->with('success', 'Xóa giá vé thành công!');
    }

    public function createFare()
    {
        $this->authorizeAdmin();

        $flights = Flight::with(['departureAirport', 'arrivalAirport'])->get();
        $classes = CabinClass::all();

        return view('admin.createFare', compact('flights', 'classes'));
    }

    public function storeFare(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'FlightID' => 'required|exists:Flights,FlightID',
            'CabinClassID' => 'required|exists:CabinClasses,CabinClassID',
            'BasePrice' => 'required|numeric|min:0',
            'Tax' => 'required|numeric|min:0',
            'Currency' => 'required|string|max:10',
        ]);

        Fare::create($request->all());
        return redirect()->route('admin.fares.index')->with('success', 'Thêm giá vé mới thành công!');
    }
    public function revenue()
    {
        $this->authorizeAdmin();

        // Doanh thu theo tháng
        $revenueByMonth = Ticket::select(
                DB::raw('MONTH(BookingDate) as month'),
                DB::raw('YEAR(BookingDate) as year'),
                DB::raw('SUM(TotalAmount) as total_revenue')
            )
            ->groupBy(DB::raw('YEAR(BookingDate), MONTH(BookingDate)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Doanh thu theo hãng hàng không
        $revenueByAirline = Ticket::join('Fares', 'Tickets.FareID', '=', 'Fares.FareID')
            ->join('Flights', 'Fares.FlightID', '=', 'Flights.FlightID')
            ->join('Airlines', 'Flights.AirlineID', '=', 'Airlines.AirlineID')
            ->select(
                'Airlines.AirlineName',
                DB::raw('SUM(Tickets.TotalAmount) as total_revenue')
            )
            ->groupBy('Airlines.AirlineName')
            ->orderByDesc('total_revenue')
            ->get();

        // Doanh thu theo hạng vé
        $revenueByCabin = Ticket::join('Fares', 'Tickets.FareID', '=', 'Fares.FareID')
            ->join('CabinClasses', 'Fares.CabinClassID', '=', 'CabinClasses.CabinClassID')
            ->select(
                'CabinClasses.ClassName',
                DB::raw('SUM(Tickets.TotalAmount) as total_revenue')
            )
            ->groupBy('CabinClasses.ClassName')
            ->orderByDesc('total_revenue')
            ->get();

        return view('admin.revenue', compact('revenueByMonth', 'revenueByAirline', 'revenueByCabin'));
    }
}
