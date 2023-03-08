<?php

namespace App\Http\Controllers;
use App\Models\Route;
use App\Models\Ticket;
use App\Models\Payment;
use Exception;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    
    public function checkAvailableSeats(Request $request)
    {
        // Dapatkan semua route yang menggunakan bus_class_id yang diminta
        $routes = Route::where('bus_class_id', $request['bus_class_id'])->get();
        
        // Looping setiap route dan cek apakah ada tiket yang tersedia
        foreach ($routes as $route) {
            $tickets = Ticket::where('route_id', $route->id)
                ->whereDate('departure_time', $request['departure_date'])
                ->where('status', '==', 'paid')
                ->get();

            $total_seats = $route->busClass->bus->total_seat;
            $occupied_seats = $tickets->count();
            $available_seats = $total_seats - $occupied_seats;

            if ($available_seats > 0) {
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'route_id' => $route->id,
                        'available_seats' => $available_seats,
                    ],
                ]);
            }
        }

        // Jika tidak ada tiket tersedia untuk semua route yang menggunakan bus_class_id yang diminta
        return response()->json([
            'status' => 'error',
            'message' => 'Maaf, tidak ada tiket tersedia untuk bus class ini pada tanggal yang diminta.',
        ], 404);

    }
    
    public function findRoute($departure_city, $arrival_city)
    {
        $buses = Bus::where('departure_city_id', $departure_city)
            ->where('arrival_city_id', $arrival_city)
            ->with(['busClasses' => function ($query) {
                $query->select('id', 'name', 'bus_id', 'price')
                    ->with(['bus' => function ($query) {
                        $query->select('id', 'name', 'departure_city_id', 'arrival_city_id');
                    }]);
            }])
            ->get();

        return response()->json(['data' => $buses]);
    }

    public function purchaseTicket(Request $request)
    {
        $route = Route::find($request->route_id);
        
        if(!$route){
            return response()->json(['error' => 'No route found'], 404);
        }
    
        $total_seats = $route->busClass->bus->total_seat;
        $occupied_seats = Ticket::where('route_id', $route->id)
                                ->where('status', 'booked')
                                ->count();
        $available_seats = $total_seats - $occupied_seats;
        
        if($available_seats < $request->total){
            return response()->json(['error' => 'Not enough available seats'], 400);
        }
    
        $total_price = $route->busClass->price * $request->total;
        $ticket_ids = [];
        for ($i = 1; $i <= $request->total; $i++) {
            $ticket = new Ticket();
            $ticket->route_id = $route->id;
            $ticket->status = 'paid';
            $ticket->passenger_name = $request->passenger_name;
            $ticket->passenger_email = $request->passenger_email;
            $ticket->passenger_phone = $request->passenger_phone;
            $ticket->seat_number = $available_seats;
            $ticket->agent_id = $request->agent_id;
            $ticket->save();
    
            $ticket_ids[] = $ticket->id;
        }
        $payment = new Payment();
        $payment->ticket_id = $ticket->id;
        $payment->amount = $total_price;
        $payment->confirmation = 'pending';
        $payment->method = $request->payment_method;
        $payment->save();
    
        $payment_id = $payment->id;
    
        
    
        return response()->json([
            'status' => 'success',
            'data' => [
                'ticket_ids' => $ticket_ids,
                'total_price' => $total_price,
                'payment_method' => $request->payment_method,
            ],
        ]);
    }
    

    public function showPaymentDetails($ticket_id)
    {
        
    
        // Cari tiket dengan id yang diberikan
        $ticket = Ticket::find($ticket_id);
    
        // Cari payment dengan foreign key ticket_id sesuai dengan id tiket yang diberikan
        $payment = Payment::where('ticket_id', $ticket_id)->first();
    
        // Hitung total harga tiket berdasarkan harga kelas bus pada tiket
        $totalPrice = $payment->amount;
    
        // Tampilkan informasi rincian pembayaran
        $data = [
            'ticket' => $ticket,
            'totalPrice' => $totalPrice,
            'paymentMethod' => $payment ? $payment->method : null,
        ];
    
        return response()->json($data);
    }
    
    public function processPayment(Request $request)
    {
        // Ambil informasi pembayaran dari request
        $ticketId = $request->input('ticket_id');
        $paymentAmount = $request->input('payment_amount');

        // Cari tiket dengan id yang diberikan
        $ticket = Ticket::find($ticketId);
        $payment = Payment::where('ticket_id', $ticketId)->first();
        // Validasi apakah payment ditemukan
        if (!$payment) {
            return response()->json(['error' => 'Tidak dapat menemukan pembayaran untuk tiket ini.']);
        }
        // Hitung total harga tiket
        $totalPrice = $payment->amount;

        // Validasi pembayaran
        if ($paymentAmount != $totalPrice) {
            return response()->json(['error' => 'Jumlah pembayaran tidak sesuai dengan harga tiket.']);
        }
        
        // Ubah status tiket menjadi "paid"
        $ticket->status = 'paid';
        $ticket->save();

        // Tampilkan halaman sukses pembayaran
        return response()->json(['success' => 'Pembayaran sukses.']);
    }

}
