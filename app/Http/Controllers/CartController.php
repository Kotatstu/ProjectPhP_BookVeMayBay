<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Seat;
use App\Models\Fare;
use App\Models\SeatAvailability;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    //Function xử lý một lần check login và lấy thông tin khách hàng
    private function getCustomerOrRedirect()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        $customer = Customer::where('UserID', $user->id)->first();
        if (!$customer) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin khách hàng.');
        }

        return $customer;
    }

    //Xem giỏ hàng
    public function index()
    {
        $customer = $this->getCustomerOrRedirect();
        if ($customer instanceof RedirectResponse) return $customer;

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

    //Thêm vé vào giỏ hàng
    public function store(Request $request)
    {
        $customer = $this->getCustomerOrRedirect();
        if ($customer instanceof RedirectResponse) return $customer;

        // Validate cơ bản
        $request->validate([
            'flight_id' => 'required|exists:Flights,FlightID',
            'selected_seats' => 'nullable|string',
            'payment_method' => 'nullable|exists:PaymentMethods,PaymentMethodID'
        ]);

        $flightId = $request->input('flight_id');
        $paymentMethodId = $request->input('payment_method');

        // Tách seatIds, loại bỏ chuỗi rỗng
        $seatIds = array_filter(explode(',', $request->input('selected_seats', '')));

        // Nếu chưa chọn ghế
        if (empty($seatIds)) {
            return back()->with('error', 'Bạn chưa chọn ghế nào.');
        }

        foreach ($seatIds as $seatId) {
            $seat = Seat::find($seatId);
            if (!$seat) continue;

            $fare = Fare::where('FlightID', $flightId)
                ->where('CabinClassID', $seat->CabinClassID)
                ->first();

            if (!$fare) continue;

            Ticket::create([
                'CustomerID' => $customer->CustomerID,
                'FlightID' => $flightId,
                'FareID' => $fare->FareID,
                'SeatID' => $seatId,
                'CabinClassID' => $fare->CabinClassID,
                'PaymentMethodID' => $paymentMethodId,
                'BookingDate' => now(),
                'TotalAmount' => $fare->BasePrice + $fare->Tax,
                'Status' => 'Chờ thanh toán',
            ]);

            // Đánh dấu ghế đã được chọn
            SeatAvailability::where('FlightID', $flightId)
                ->where('SeatID', $seatId)
                ->update(['IsBooked' => 1]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã lưu vé vào giỏ hàng.');
    }

    //Thanh toán ngay
    public function storeAndCheckout(Request $request)
    {
        // Lấy customer hoặc redirect nếu chưa login / không tồn tại
        $customer = $this->getCustomerOrRedirect();
        if ($customer instanceof RedirectResponse) return $customer;

        $request->validate([
            'flight_id' => 'required|exists:Flights,FlightID',
            'seat_id' => 'nullable|string',
            'selected_seats' => 'nullable|string',
            'payment_method' => 'nullable|exists:PaymentMethods,PaymentMethodID'
        ]);

        $flightId = $request->input('flight_id');

        // Lấy danh sách seat
        $seatIds = array_filter(explode(',', $request->input('seat_id') ?: $request->input('selected_seats', '')));
        if (empty($seatIds)) {
            return back()->with('error', 'Bạn chưa chọn ghế nào.');
        }

        // Lấy PaymentMethod, ưu tiên từ input, fallback lấy mặc định của customer
        $paymentMethodId = $request->input('payment_method')
            ?? PaymentMethod::where('CustomerID', $customer->CustomerID)->value('PaymentMethodID');

        if (!$paymentMethodId) {
            return back()->with('error', 'Bạn chưa có phương thức thanh toán nào.');
        }

        $ticketIds = [];

        foreach ($seatIds as $seatId) {
            $seat = Seat::find($seatId);
            if (!$seat) continue;

            $fare = Fare::where('FlightID', $flightId)
                ->where('CabinClassID', $seat->CabinClassID)
                ->first();

            if (!$fare) {
                return back()->with('error', 'Không tìm thấy thông tin giá vé.');
            }

            // Tạo ticket
            $ticket = Ticket::create([
                'CustomerID' => $customer->CustomerID,
                'FlightID' => $flightId,
                'FareID' => $fare->FareID,
                'SeatID' => $seat->SeatID,
                'CabinClassID' => $seat->CabinClassID,
                'PaymentMethodID' => $paymentMethodId,
                'BookingDate' => now(),
                'TotalAmount' => $fare->BasePrice + $fare->Tax,
                'Status' => 'Chờ thanh toán',
            ]);

            $ticketIds[] = $ticket->TicketID;

            // Đánh dấu ghế đã được chọn
            SeatAvailability::where('FlightID', $flightId)
                ->where('SeatID', $seat->SeatID)
                ->update(['IsBooked' => 1]);
        }

        if (empty($ticketIds)) {
            return back()->with('error', 'Không tạo được vé nào. Vui lòng thử lại.');
        }

        // Redirect tới checkout tương ứng: 1 vé hoặc nhiều vé
        return count($ticketIds) === 1
            ? redirect()->route('cart.checkoutForm', ['ticket' => $ticketIds[0]])
            : redirect()->route('cart.checkoutAllForm', ['ticket_ids' => implode(',', $ticketIds)]);
    }

    //Hiển thị form checkout cho 1 vé
    public function checkoutForm($ticketId)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        $customer = Customer::where('UserID', $user->id)->first();
        if (!$customer) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin khách hàng.');
        }

        $ticket = Ticket::with([
            'fare.cabinClass',
            'seat',
            'flight.airline',
            'flight.departureAirport',
            'flight.arrivalAirport'
        ])->findOrFail($ticketId);

        $paymentMethods = PaymentMethod::where('CustomerID', $customer->CustomerID)->get();

        return view('users.checkout', [
            'tickets' => collect([$ticket]), // giữ dạng collection để view dùng giống checkoutAll
            'user' => $user,
            'customer' => $customer,
            'paymentMethods' => $paymentMethods
        ]);
    }

    public function checkoutAllForm(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        $customer = Customer::where('UserID', $user->id)->first();
        if (!$customer) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin khách hàng.');
        }

        $ticketIds = explode(',', $request->input('ticket_ids', ''));
        $ticketIds = array_filter($ticketIds);

        $tickets = Ticket::with([
            'fare.cabinClass',
            'seat',
            'flight.airline',
            'flight.departureAirport',
            'flight.arrivalAirport'
        ])->whereIn('TicketID', $ticketIds)->get();

        $paymentMethods = PaymentMethod::where('CustomerID', $customer->CustomerID)->get();

        return view('users.checkout', compact('tickets', 'user', 'customer', 'paymentMethods'));
    }

    //Thanh toán 1 vé
    public function checkout(Request $request, $ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);

        $request->validate([
            'payment_method' => 'required|exists:PaymentMethods,PaymentMethodID'
        ]);

        $ticket->update([
            'PaymentMethodID' => $request->input('payment_method'),
            'Status' => 'Đã thanh toán',
        ]);

        SeatAvailability::where('FlightID', $ticket->FlightID)
            ->where('SeatID', $ticket->SeatID)
            ->update(['IsBooked' => 1]);

        return redirect()->route('cart.index')->with('success', 'Thanh toán thành công!');
    }

    //Thanh toán tất cả vé
    public function checkoutAll(Request $request)
    {
        $customer = $this->getCustomerOrRedirect();
        if ($customer instanceof RedirectResponse) return $customer;

        $request->validate([
            'ticket_ids' => 'required|string',
            'payment_method' => 'required|exists:PaymentMethods,PaymentMethodID'
        ]);

        $idsArray = array_filter(explode(',', $request->input('ticket_ids')));
        $tickets = Ticket::whereIn('TicketID', $idsArray)->get();

        foreach ($tickets as $ticket) {
            $ticket->update([
                'PaymentMethodID' => $request->input('payment_method'),
                'Status' => 'Đã thanh toán',
            ]);

            SeatAvailability::where('FlightID', $ticket->FlightID)
                ->where('SeatID', $ticket->SeatID)
                ->update(['IsBooked' => 1]);
        }

        return redirect()->route('cart.index')->with('success', 'Thanh toán tất cả vé thành công!');
    }

    //Xóa vé khỏi giỏ
    public function remove($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);

        SeatAvailability::where('FlightID', $ticket->FlightID)
            ->where('SeatID', $ticket->SeatID)
            ->update(['IsBooked' => 0]);

        $ticket->delete();

        return redirect()->route('cart.index')->with('success', 'Đã xóa vé khỏi giỏ hàng.');
    }

    //Huỷ vé
    public function cancel($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->update(['Status' => 'Đã huỷ']);

        return redirect()->route('cart.index')->with('success', 'Vé đã được huỷ.');
    }

    //Reset vé để chỉnh sửa (tên method rõ ràng hơn)
    public function resetTicketForEdit($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->delete();

        return redirect()->route('flights.detail', ['id' => $ticket->FlightID])
            ->with('info', 'Bạn có thể chỉnh sửa hạng vé, ghế và phương thức thanh toán.');
    }

    //Sửa vé (chuyển hướng tới trang chi tiết chuyến bay)
    public function edit($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->delete();

        return redirect()->route('flights.detail', ['id' => $ticket->FlightID])
            ->with('info', 'Bạn có thể chỉnh sửa hạng vé, ghế và phương thức thanh toán.');
    }
}
