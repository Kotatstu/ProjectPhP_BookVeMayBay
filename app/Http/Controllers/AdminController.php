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
use Carbon\Carbon;

class AdminController extends BaseController
{
    public function __construct()
    {
        // Bắt buộc phải đăng nhập mới được vào
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Kiểm tra xem user có trong bảng adminRole không
        $isAdmin = adminRole::where('U_ID', $user->id)->exists();
        if (!$isAdmin) {
            return redirect('/home')->with('error', 'Bạn không có quyền truy cập trang admin.');
        }

        return view('admin.index');
    }

    public function usersList()
    {
        $user = Auth::user();
        // Lấy tất cả người dùng từ bảng 'users'
        $users = DB::table('users')->get();

        // Trả về view với danh sách người dùng
        return view('admin.users', compact('users'));
    }

    // Phương thức sửa thông tin người dùng
    public function editUser($id)
    {
        // Lấy thông tin người dùng dựa trên ID
        $user = User::findOrFail($id);
        $isAdmin = adminRole::where('U_ID', $user->id)->exists();

        return view('admin.editUser', compact('user', 'isAdmin'));
    }

    public function updateUser(Request $request, $id)
    {
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
        // Xóa người dùng từ bảng 'users'
        DB::table('users')->where('id', $id)->delete();

        // Redirect về trang danh sách người dùng với thông báo
        return redirect()->route('admin.users')->with('success', 'Người dùng đã được xóa.');
    }

    public function flightsList()
    {
        $flights = Flight::orderBy('DepartureTime', 'asc')->get();

        return view('admin.flights', compact('flights'));
    }

    public function flightDetail($id)
    {
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
        $airlines = \App\Models\Airline::all();
        $aircrafts = \App\Models\Aircraft::all();
        $airports = \App\Models\Airport::all();

        return view('admin.createFlight', compact('airlines', 'aircrafts', 'airports'));
    }

    public function storeFlight(Request $request)
    {
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
        $flight = Flight::findOrFail($id);
        $airlines = Airline::all();
        $aircrafts = Aircraft::all();
        $airports = Airport::all();

        return view('admin.editFlight', compact('flight', 'airlines', 'aircrafts', 'airports'));
    }

    public function updateFlight(Request $request, $id)
    {
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
        $flight = Flight::findOrFail($id);
        $flight->delete();

        return redirect()->route('admin.flights')->with('success', 'Đã xóa chuyến bay!');
    }

    private function authorizeAdmin()
    {
        $user = Auth::user();
        if (!AdminRole::where('U_ID', $user->id)->exists()) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }
    }

    public function listAircraft() {
        $aircrafts = Aircraft::all();
        return view('admin.aircrafts', compact('aircrafts'));
    }

    public function createAircraft() {
        return view('admin.createAircraft');
    }

    public function storeAircraft(Request $request) {
        $request->validate([
            'AircraftCode' => 'required|unique:Aircrafts',
            'AircraftType' => 'required',
        ]);

        Aircraft::create($request->all());
        return redirect()->route('admin.aircrafts.index')->with('success', 'Thêm máy bay thành công!');
    }

    public function editAircraft($id) {
        $aircraft = Aircraft::findOrFail($id);
        return view('admin.editAircraft', compact('aircraft'));
    }

    public function updateAircraft(Request $request, $id) {
        $aircraft = Aircraft::findOrFail($id);
        $request->validate(['AircraftType' => 'required']);
        $aircraft->update($request->all());
        return redirect()->route('admin.aircrafts.index')->with('success', 'Cập nhật máy bay thành công!');
    }

    public function deleteAircraft($id) {
        Aircraft::findOrFail($id)->delete();
        return redirect()->route('admin.aircrafts.index')->with('success', 'Xóa máy bay thành công!');
    }

    public function listAirline() {
        $airlines = Airline::all();
        return view('admin.airlines', compact('airlines'));
    }

    public function createAirline() {
        return view('admin.createAirline');
    }

    public function storeAirline(Request $request) {
        $request->validate([
            'AirlineID' => 'required|unique:Airlines',
            'AirlineName' => 'required',
            'Country' => 'required',
            'LogoURL' => 'nullable',
        ]);

        Airline::create($request->all());
        return redirect()->route('admin.airlines.index')->with('success', 'Thêm hãng hàng không thành công!');
    }

    public function editAirline($id) {
        $airline = Airline::findOrFail($id);
        return view('admin.editAirline', compact('airline'));
    }

    public function updateAirline(Request $request, $id) {
        $airline = Airline::findOrFail($id);
        $request->validate([
            'AirlineName' => 'required',
            'Country' => 'required',
            'LogoURL' => 'nullable',
        ]);

        $airline->update($request->all());
        return redirect()->route('admin.airlines.index')->with('success', 'Cập nhật hãng hàng không thành công!');
    }

    public function deleteAirline($id) {
        Airline::findOrFail($id)->delete();
        return redirect()->route('admin.airlines.index')->with('success', 'Xóa hãng hàng không thành công!');
    }


    public function listAirport() {
        $airports = Airport::all();
        return view('admin.airports', compact('airports'));
    }

    public function createAirport() {
        return view('admin.createAirport');
    }

    public function storeAirport(Request $request) {
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

    public function editAirport($id) {
        $airport = Airport::findOrFail($id);
        return view('admin.editAirport', compact('airport'));
    }

    public function updateAirport(Request $request, $id) {
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

    public function deleteAirport($id) {
        Airport::findOrFail($id)->delete();
        return redirect()->route('admin.airports.index')->with('success', 'Xóa sân bay thành công!');
    }


}
