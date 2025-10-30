<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flight;
use App\Models\Fare;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Xem giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('users.cart', compact('cart'));
    }

    // Thêm vào giỏ hàng
    public function add(Request $request)
    {
        $flightId = $request->flight_id;
        $fareId = $request->fare_id;

        // Lấy thông tin chuyến bay và vé
        $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])->findOrFail($flightId);
        $fare = Fare::with('cabinClass')->findOrFail($fareId);

        //$totalPrice = $fare->BasePrice + $fare->Tax;
         $totalPrice = ($fare->BasePrice ?? 0) + ($fare->Tax ?? 0);

        // Dữ liệu hiển thị trong giỏ
        $cartItem = [
            'flight_id' => $flight->FlightID,
            'fare_id' => $fare->FareID,
            'airline_name' => $flight->airline->AirlineName ?? '',
            'airline_logo' => asset('storage/airline/' . ($flight->airline->LogoURL ?? 'temp.png')),
            'from' => $flight->departureAirport->City . ' (' . $flight->departureAirport->AirportCode . ')',
            'to' => $flight->arrivalAirport->City . ' (' . $flight->arrivalAirport->AirportCode . ')',
            'departure_date' => date('d/m/Y', strtotime($flight->DepartureTime)),
            'departure_time' => date('H:i', strtotime($flight->DepartureTime)),
            'arrival_time' => date('H:i', strtotime($flight->ArrivalTime)),
            'cabin_class' => $fare->cabinClass->ClassName ?? 'Không xác định',
            'fare' => $totalPrice,
        ];

        // Lưu vào session
        $cart = session()->get('cart', []);
        $key = $flightId . '-' . $fareId; 

        $cart[$key] = $cartItem;
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Đã thêm vé vào giỏ hàng!');
    }

    // Xóa khỏi giỏ hàng
    public function remove(Request $request)
    {
        $key = $request->key;
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Đã xóa vé khỏi giỏ hàng!');
    }

    public function checkout(Request $request)
    {
        $key = $request->key;
        $cart = session()->get('cart', []);

        if (!isset($cart[$key])) {
            return redirect()->route('cart.index')->with('error', 'Vé không tồn tại trong giỏ!');
        }

        $item = $cart[$key];
        //$customerId = Auth::user()->CustomerID ?? null;
        $customerId = 1; //test nhanh thanh toán

        if (!$customerId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán!');
        }

        // Lưu vé vào bảng Tickets
        Ticket::create([
            'CustomerID' => $customerId,
            'FlightID' => $item['flight_id'],
            'FareID' => $item['fare_id'],
            'PaymentMethodID' => 2, // Tạm cố định, sau này chọn lại
            'TotalAmount' => $item['fare'],
            'Status' => 'Đã thanh toán',
        ]);

        // Xóa khỏi giỏ hàng
        unset($cart[$key]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Thanh toán thành công 1 vé!');
    }

    public function checkoutAll()
    {
        $cart = session()->get('cart', []);
        //$customerId = Auth::user()->CustomerID ?? null;
        $customerId = 1; //test nhanh thanh toán

        if (!$customerId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán!');
        }

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        foreach ($cart as $item) {
            Ticket::create([
                'CustomerID' => $customerId,
                'FlightID' => $item['flight_id'],
                'FareID' => $item['fare_id'],
                'PaymentMethodID' => 2, // Tạm cố định, sau này chọn lại
                'TotalAmount' => $item['fare'],
                'Status' => 'Đã thanh toán',
            ]);
        }

        // Xóa giỏ hàng
        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Đã thanh toán toàn bộ giỏ hàng!');
    }

}
