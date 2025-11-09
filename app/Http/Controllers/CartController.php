<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\PaymentMethod;

class CartController extends Controller
{
    // Xem giỏ hàng
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem giỏ vé');
        }

        $customer = DB::table('Customers')->where('UserID', $user->id)->first();
        if (!$customer) {
            return redirect()->route('home')->with('error', 'Bạn chưa có thông tin khách hàng.');
        }

        $tickets = Ticket::with([
            'fare.cabinClass',
            'seat',
            'flight.airline',
            'flight.departureAirport',
            'flight.arrivalAirport'
        ])
            ->where('CustomerID', $customer->CustomerID)
            ->orderBy('BookingDate', 'desc')
            ->get();

        return view('users.cart', compact('tickets'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $customer = DB::table('Customers')->where('UserID', $user->id)->first();
        if (!$customer) {
            return back()->with('error', 'Bạn chưa có thông tin khách hàng.');
        }
        $customerId = $customer->CustomerID;

        // Lấy thông tin từ form
        $flightId = $request->input('flight_id');
        $fareId = $request->input('fare_id');
        $seatId = $request->input('seat_id');
        $paymentMethodId = $request->input('payment_method');

        // Tính tổng tiền dựa trên Fare
        $fare = DB::table('Fares')->where('FareID', $fareId)->first();
        if (!$fare) {
            return back()->with('error', 'Hạng vé không hợp lệ');
        }
        $totalAmount = $fare->BasePrice + $fare->Tax;

        // Thêm vào bảng Tickets
        $ticket = Ticket::create([
            'CustomerID' => $customerId,
            'FlightID' => $flightId,
            'FareID' => $fareId,
            'SeatID' => $seatId,
            'CabinClassID' => $fare->CabinClassID,
            'PaymentMethodID' => $paymentMethodId,
            'BookingDate' => now(),
            'TotalAmount' => $totalAmount,
            'Status' => 'Chờ thanh toán',
        ]);

        // Cập nhật SeatAvailability đánh dấu ghế đã được chọn
        DB::table('SeatAvailability')
            ->where('FlightID', $flightId)
            ->where('SeatID', $seatId)
            ->update(['IsBooked' => 1]);

        return redirect()->route('cart.index')->with('success', 'Đã lưu vé vào giỏ hàng');
    }

    public function storeAndCheckout(Request $request)
    {
        $user = Auth::user();
        $customer = DB::table('Customers')->where('UserID', $user->id)->first();
        $customerId = $customer->CustomerID;

        // Lấy thông tin từ form
        $flightId = $request->input('flight_id');
        $fareId = $request->input('fare_id');
        $seatId = $request->input('seat_id');
        $paymentMethodId = $request->input('payment_method');

        $fare = DB::table('Fares')->where('FareID', $fareId)->first();
        $totalAmount = $fare->BasePrice + $fare->Tax;

        $paymentMethod = DB::table('PaymentMethods')->where('CustomerID', $customerId)->first();
        $paymentMethodId = $paymentMethod->PaymentMethodID ?? null;

        if (!$paymentMethodId) {
            return back()->with('error', 'Bạn chưa có phương thức thanh toán nào. Vui lòng thêm trước.');
        }

        // Thêm vé và lấy ID
        $ticket = Ticket::create([
            'CustomerID' => $customerId,
            'FlightID' => $flightId,
            'FareID' => $fareId,
            'SeatID' => $seatId,
            'CabinClassID' => $fare->CabinClassID,
            'PaymentMethodID' => $paymentMethodId,
            'BookingDate' => now(),
            'TotalAmount' => $totalAmount,
            'Status' => 'Chờ thanh toán',
        ]);

        // Đánh dấu ghế đã chọn
        DB::table('SeatAvailability')
            ->where('FlightID', $flightId)
            ->where('SeatID', $seatId)
            ->update(['IsBooked' => 1]);

        // Redirect sang trang checkout
        return redirect()->route('cart.checkoutForm', ['ticket' => $ticket->TicketID]);
    }

    // Hiển thị form thanh toán cho 1 vé
    public function checkoutForm($ticketId)
    {
        $ticket = Ticket::with([
            'fare.cabinClass',
            'seat',
            'flight.airline',
            'flight.departureAirport',
            'flight.arrivalAirport'
        ])->findOrFail($ticketId);
        $user = Auth::user();
        $customer = Customer::where('UserID', $user->id)->first();
        $paymentMethods = PaymentMethod::where('CustomerID', $customer->CustomerID)->get();

        return view('users.checkout', compact('ticket', 'user', 'customer', 'paymentMethods'));
    }

    public function checkout(Request $request, $ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);

        $paymentMethodId = $request->input('payment_method');
        if (!$paymentMethodId) {
            return back()->with('error', 'Vui lòng chọn phương thức thanh toán.');
        }
        // Cập nhật vé: paymentMethod + status
        $ticket->PaymentMethodID = $paymentMethodId;
        $ticket->Status = 'Đã thanh toán';
        $ticket->save();

        // Đánh dấu ghế là đã được đặt
        DB::table('SeatAvailability')
            ->where('FlightID', $ticket->FlightID)
            ->where('SeatID', $ticket->SeatID)
            ->update(['IsBooked' => 1]);

        return redirect()->route('cart.index')->with('success', 'Thanh toán thành công!');
    }

    public function remove($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);

        // Reset trạng thái ghế
        DB::table('SeatAvailability')
            ->where('FlightID', $ticket->FlightID)
            ->where('SeatID', $ticket->SeatID)
            ->update(['IsBooked' => 0]);

        $ticket->delete();

        return redirect()->route('cart.index')->with('success', 'Đã xóa vé khỏi giỏ hàng');
    }

    public function cancel($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->Status = 'Đã huỷ';
        $ticket->save();

        return redirect()->route('cart.index')->with('success', 'Vé đã được huỷ.');
    }

    public function edit($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->delete();

        return redirect()->route('flights.detail', ['id' => $ticket->FlightID])
            ->with('info', 'Bạn có thể chỉnh sửa hạng vé, ghế và phương thức thanh toán.');
    }
}
