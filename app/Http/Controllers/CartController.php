<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flight;

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
        $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'fares.cabinClass'])->findOrFail($flightId);

        $minFare = $flight->fares->min('Price');

        $cartItem = [
            'id' => $flight->FlightID,
            'airline_name' => $flight->airline->Name ?? '',
            'airline_logo' => asset('storage/airlines/' . ($flight->airline->Logo ?? 'default.png')),
            'from' => $flight->departureAirport->City . ' (' . $flight->departureAirport->Code . ')',
            'to' => $flight->arrivalAirport->City . ' (' . $flight->arrivalAirport->Code . ')',
            'departure_date' => date('d/m/Y', strtotime($flight->DepartureTime)),
            'departure_time' => date('H:i', strtotime($flight->DepartureTime)),
            'arrival_time' => date('H:i', strtotime($flight->ArrivalTime)),
            'fare' => $minFare ?? 0,
        ];

        $cart = session()->get('cart', []);

        // Nếu đã có trong giỏ hàng, không thêm nữa
        if(!isset($cart[$flightId])){
            $cart[$flightId] = $cartItem;
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Đã thêm chuyến bay vào giỏ hàng!');
    }

    // Xóa khỏi giỏ hàng
    public function remove(Request $request)
    {
        $flightId = $request->flight_id;
        $cart = session()->get('cart', []);
        if(isset($cart[$flightId])){
            unset($cart[$flightId]);
            session()->put('cart', $cart);
        }
        return redirect()->route('cart.index')->with('success', 'Đã xóa chuyến bay khỏi giỏ hàng!');
    }
}
?>