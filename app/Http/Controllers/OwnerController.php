<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OwnerController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $lastMonth = Carbon::now()->subMonth()->month;
        $lastMonthYear = Carbon::now()->subMonth()->year;

        // Pendapatan Bulan Ini
        $currentMonthIncome = DB::table('pembayaran')
            ->whereMonth('tanggal_bayar', $currentMonth)
            ->whereYear('tanggal_bayar', $currentYear)
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah_bayar');

        $lastMonthIncome = DB::table('pembayaran')
            ->whereMonth('tanggal_bayar', $lastMonth)
            ->whereYear('tanggal_bayar', $lastMonthYear)
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah_bayar');

        $incomePercentage = $lastMonthIncome > 0 
            ? round((($currentMonthIncome - $lastMonthIncome) / $lastMonthIncome) * 100, 1) 
            : 0;

        // Hewan Aktif (sedang dititipkan)
        $activeAnimals = DB::table('penitipan')
            ->where('status', 'aktif')
            ->count();

        $lastWeek = Carbon::now()->subWeek();
        $newAnimalsThisWeek = DB::table('hewan')
            ->where('created_at', '>=', $lastWeek)
            ->count();

        // Reservasi Bulan Ini
        $currentMonthReservations = DB::table('penitipan')
            ->whereMonth('tanggal_masuk', $currentMonth)
            ->whereYear('tanggal_masuk', $currentYear)
            ->count();

        $lastMonthReservations = DB::table('penitipan')
            ->whereMonth('tanggal_masuk', $lastMonth)
            ->whereYear('tanggal_masuk', $lastMonthYear)
            ->count();

        $reservationPercentage = $lastMonthReservations > 0 
            ? round((($currentMonthReservations - $lastMonthReservations) / $lastMonthReservations) * 100, 1) 
            : 0;

        // Rating (placeholder - bisa diimplementasikan jika ada tabel rating)
        $avgRating = 4.8;
        $reviewsThisMonth = 0;

        // Data untuk chart pendapatan (6 bulan terakhir)
        $revenueData = [];
        $revenueLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenue = DB::table('pembayaran')
                ->whereMonth('tanggal_bayar', $month->month)
                ->whereYear('tanggal_bayar', $month->year)
                ->where('status_pembayaran', 'lunas')
                ->sum('jumlah_bayar');
            
            $revenueData[] = round($revenue / 1000000, 2); // Convert to millions
            $revenueLabels[] = $month->format('M');
        }

        // Data untuk chart layanan
        $serviceData = DB::table('detail_penitipan')
            ->join('paket_layanan', 'detail_penitipan.id_paket', '=', 'paket_layanan.id_paket')
            ->select('paket_layanan.nama_paket', DB::raw('COUNT(*) as total'))
            ->groupBy('paket_layanan.nama_paket')
            ->get();

        return view('owner.dashboard', compact(
            'currentMonthIncome',
            'incomePercentage',
            'activeAnimals',
            'newAnimalsThisWeek',
            'currentMonthReservations',
            'reservationPercentage',
            'avgRating',
            'reviewsThisMonth',
            'revenueData',
            'revenueLabels',
            'serviceData'
        ));
    }

    // Reservations
    public function reservations($tab = 'semua')
    {
        $validTabs = ['semua', 'today', 'upcoming', 'selesai'];
        if (!in_array($tab, $validTabs)) {
            $tab = 'semua';
        }

        $query = DB::table('penitipan')
            ->join('hewan', 'penitipan.id_hewan', '=', 'hewan.id_hewan')
            ->join('pengguna', 'penitipan.id_pemilik', '=', 'pengguna.id_pengguna')
            ->leftJoin('pembayaran', 'penitipan.id_penitipan', '=', 'pembayaran.id_penitipan')
            ->leftJoin('detail_penitipan', 'penitipan.id_penitipan', '=', 'detail_penitipan.id_penitipan')
            ->leftJoin('paket_layanan', 'detail_penitipan.id_paket', '=', 'paket_layanan.id_paket')
            ->select(
                'penitipan.*',
                'penitipan.id_penitipan as id',
                'penitipan.catatan_khusus as notes',
                'hewan.nama_hewan as pet_name',
                'hewan.ras as pet_breed',
                'pengguna.nama_lengkap as owner_name',
                'pengguna.no_telepon as phone',
                'paket_layanan.nama_paket as service',
                'pembayaran.jumlah_bayar as total'
            );

        // Filter berdasarkan tab
        if ($tab === 'today') {
            $query->whereDate('penitipan.tanggal_masuk', Carbon::today());
        } elseif ($tab === 'upcoming') {
            $query->whereDate('penitipan.tanggal_masuk', '>', Carbon::today());
        } elseif ($tab === 'selesai') {
            $query->where('penitipan.status', 'selesai');
        }

        $reservations = $query->orderBy('penitipan.tanggal_masuk', 'desc')->get();

        // Format data
        $reservations = $reservations->map(function($reservation) {
            $reservation->status_label = $this->getStatusLabel($reservation->status);
            $reservation->checkin_date = Carbon::parse($reservation->tanggal_masuk)->format('d M Y');
            $reservation->checkout_date = Carbon::parse($reservation->tanggal_keluar)->format('d M Y');
            $reservation->duration = Carbon::parse($reservation->tanggal_masuk)->diffInDays($reservation->tanggal_keluar);
            return $reservation;
        });

        return view('owner.reservations', compact('reservations', 'tab'));
    }

    // Finance
    public function finance()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $lastMonth = Carbon::now()->subMonth()->month;
        $lastMonthYear = Carbon::now()->subMonth()->year;

        // Total Income
        $totalIncome = DB::table('pembayaran')
            ->whereMonth('tanggal_bayar', $currentMonth)
            ->whereYear('tanggal_bayar', $currentYear)
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah_bayar');

        $lastMonthIncome = DB::table('pembayaran')
            ->whereMonth('tanggal_bayar', $lastMonth)
            ->whereYear('tanggal_bayar', $lastMonthYear)
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah_bayar');

        $incomeChange = $lastMonthIncome > 0 
            ? '+' . round((($totalIncome - $lastMonthIncome) / $lastMonthIncome) * 100, 1) . '%' 
            : '0%';

        // Total Expense (placeholder - bisa diimplementasikan jika ada tabel pengeluaran)
        $totalExpense = 0;
        $expenseChange = '0%';

        // Net Profit
        $netProfit = $totalIncome - $totalExpense;
        $profitChange = $incomeChange;

        // Profit Margin
        $profitMargin = $totalIncome > 0 ? round(($netProfit / $totalIncome) * 100, 1) : 0;

        // Transactions
        $transactions = DB::table('pembayaran')
            ->join('penitipan', 'pembayaran.id_penitipan', '=', 'penitipan.id_penitipan')
            ->join('hewan', 'penitipan.id_hewan', '=', 'hewan.id_hewan')
            ->join('pengguna', 'penitipan.id_pemilik', '=', 'pengguna.id_pengguna')
            ->select(
                'pembayaran.id_pembayaran as id',
                DB::raw("CONCAT('Pembayaran - ', hewan.nama_hewan, ' (', pengguna.nama_lengkap, ')') as description"),
                'pembayaran.tanggal_bayar as date',
                'pembayaran.metode_pembayaran as method',
                'pembayaran.jumlah_bayar as amount',
                DB::raw("'income' as type")
            )
            ->whereMonth('pembayaran.tanggal_bayar', $currentMonth)
            ->whereYear('pembayaran.tanggal_bayar', $currentYear)
            ->orderBy('pembayaran.tanggal_bayar', 'desc')
            ->get();

        $transactions = $transactions->map(function($transaction) {
            $transaction->date = Carbon::parse($transaction->date)->format('d M Y');
            return $transaction;
        });

        return view('owner.finance', compact(
            'totalIncome',
            'incomeChange',
            'totalExpense',
            'expenseChange',
            'netProfit',
            'profitChange',
            'profitMargin',
            'transactions'
        ));
    }

    // Pets
    public function pets()
    {
        // Okupansi Kandang
        $totalBasic = 50; // Placeholder - bisa dari config/database
        $totalPremium = 30; // Placeholder
        
        $basicOccupied = DB::table('penitipan')
            ->join('detail_penitipan', 'penitipan.id_penitipan', '=', 'detail_penitipan.id_penitipan')
            ->join('paket_layanan', 'detail_penitipan.id_paket', '=', 'paket_layanan.id_paket')
            ->where('penitipan.status', 'aktif')
            ->where('paket_layanan.nama_paket', 'LIKE', '%Basic%')
            ->count();

        $premiumOccupied = DB::table('penitipan')
            ->join('detail_penitipan', 'penitipan.id_penitipan', '=', 'detail_penitipan.id_penitipan')
            ->join('paket_layanan', 'detail_penitipan.id_paket', '=', 'paket_layanan.id_paket')
            ->where('penitipan.status', 'aktif')
            ->where('paket_layanan.nama_paket', 'LIKE', '%Premium%')
            ->count();

        $basicTotal = $totalBasic;
        $basicAvailable = $totalBasic - $basicOccupied;
        $basicPercentage = round(($basicOccupied / $totalBasic) * 100, 1);

        $premiumTotal = $totalPremium;
        $premiumAvailable = $totalPremium - $premiumOccupied;
        $premiumPercentage = round(($premiumOccupied / $totalPremium) * 100, 1);

        // Check-in Today
        $todayCheckin = DB::table('penitipan')
            ->whereDate('tanggal_masuk', Carbon::today())
            ->count();

        $todayCheckinDogs = DB::table('penitipan')
            ->join('hewan', 'penitipan.id_hewan', '=', 'hewan.id_hewan')
            ->whereDate('penitipan.tanggal_masuk', Carbon::today())
            ->where('hewan.jenis_hewan', 'Anjing')
            ->count();

        $todayCheckinCats = DB::table('penitipan')
            ->join('hewan', 'penitipan.id_hewan', '=', 'hewan.id_hewan')
            ->whereDate('penitipan.tanggal_masuk', Carbon::today())
            ->where('hewan.jenis_hewan', 'Kucing')
            ->count();

        // Check-out Today
        $todayCheckout = DB::table('penitipan')
            ->whereDate('tanggal_keluar', Carbon::today())
            ->count();

        $todayCheckoutDogs = DB::table('penitipan')
            ->join('hewan', 'penitipan.id_hewan', '=', 'hewan.id_hewan')
            ->whereDate('penitipan.tanggal_keluar', Carbon::today())
            ->where('hewan.jenis_hewan', 'Anjing')
            ->count();

        $todayCheckoutCats = DB::table('penitipan')
            ->join('hewan', 'penitipan.id_hewan', '=', 'hewan.id_hewan')
            ->whereDate('penitipan.tanggal_keluar', Carbon::today())
            ->where('hewan.jenis_hewan', 'Kucing')
            ->count();

        // Currently Boarded
        $currentTotal = DB::table('penitipan')
            ->where('status', 'aktif')
            ->count();

        $currentDogs = DB::table('penitipan')
            ->join('hewan', 'penitipan.id_hewan', '=', 'hewan.id_hewan')
            ->where('penitipan.status', 'aktif')
            ->where('hewan.jenis_hewan', 'Anjing')
            ->count();

        $currentCats = DB::table('penitipan')
            ->join('hewan', 'penitipan.id_hewan', '=', 'hewan.id_hewan')
            ->where('penitipan.status', 'aktif')
            ->where('hewan.jenis_hewan', 'Kucing')
            ->count();

        // Total Animals Currently
        $totalDogsCurrently = $currentDogs;
        $totalCatsCurrently = $currentCats;

        // Breed Distribution - Dogs
        $dogBreeds = DB::table('hewan')
            ->join('penitipan', 'hewan.id_hewan', '=', 'penitipan.id_hewan')
            ->where('penitipan.status', 'aktif')
            ->where('hewan.jenis_hewan', 'Anjing')
            ->select('hewan.ras as name', DB::raw('COUNT(*) as count'))
            ->groupBy('hewan.ras')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Breed Distribution - Cats
        $catBreeds = DB::table('hewan')
            ->join('penitipan', 'hewan.id_hewan', '=', 'penitipan.id_hewan')
            ->where('penitipan.status', 'aktif')
            ->where('hewan.jenis_hewan', 'Kucing')
            ->select('hewan.ras as name', DB::raw('COUNT(*) as count'))
            ->groupBy('hewan.ras')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Today's Check-ins List
        $todayCheckins = DB::table('penitipan')
            ->join('hewan', 'penitipan.id_hewan', '=', 'hewan.id_hewan')
            ->join('pengguna', 'penitipan.id_pemilik', '=', 'pengguna.id_pengguna')
            ->join('detail_penitipan', 'penitipan.id_penitipan', '=', 'detail_penitipan.id_penitipan')
            ->join('paket_layanan', 'detail_penitipan.id_paket', '=', 'paket_layanan.id_paket')
            ->whereDate('penitipan.tanggal_masuk', Carbon::today())
            ->select(
                'hewan.nama_hewan as pet_name',
                'hewan.ras as breed',
                'pengguna.nama_lengkap as owner_name',
                'penitipan.tanggal_masuk as time',
                'paket_layanan.nama_paket as room_type'
            )
            ->get();

        $todayCheckins = $todayCheckins->map(function($checkin) {
            $checkin->time = Carbon::parse($checkin->time)->format('H:i');
            $checkin->room_type = strpos($checkin->room_type, 'Premium') !== false ? 'Premium' : 'Basic';
            return $checkin;
        });

        // Today's Check-outs List
        $todayCheckouts = DB::table('penitipan')
            ->join('hewan', 'penitipan.id_hewan', '=', 'hewan.id_hewan')
            ->join('pengguna', 'penitipan.id_pemilik', '=', 'pengguna.id_pengguna')
            ->join('detail_penitipan', 'penitipan.id_penitipan', '=', 'detail_penitipan.id_penitipan')
            ->join('paket_layanan', 'detail_penitipan.id_paket', '=', 'paket_layanan.id_paket')
            ->whereDate('penitipan.tanggal_keluar', Carbon::today())
            ->select(
                'hewan.nama_hewan as pet_name',
                'hewan.ras as breed',
                'pengguna.nama_lengkap as owner_name',
                'penitipan.tanggal_keluar as time',
                'paket_layanan.nama_paket as room_type',
                'penitipan.status'
            )
            ->get();

        $todayCheckouts = $todayCheckouts->map(function($checkout) {
            $checkout->time = Carbon::parse($checkout->time)->format('H:i');
            $checkout->room_type = strpos($checkout->room_type, 'Premium') !== false ? 'Premium' : 'Basic';
            $checkout->status_label = $checkout->status === 'selesai' ? 'Selesai' : 'Pending';
            return $checkout;
        });

        return view('owner.pets', compact(
            'basicOccupied', 'basicTotal', 'basicAvailable', 'basicPercentage',
            'premiumOccupied', 'premiumTotal', 'premiumAvailable', 'premiumPercentage',
            'todayCheckin', 'todayCheckinDogs', 'todayCheckinCats',
            'todayCheckout', 'todayCheckoutDogs', 'todayCheckoutCats',
            'currentTotal', 'currentDogs', 'currentCats',
            'totalDogsCurrently', 'totalCatsCurrently',
            'dogBreeds', 'catBreeds',
            'todayCheckins', 'todayCheckouts'
        ));
    }

    // Services
    public function services()
    {
        $services = DB::table('paket_layanan')->get();
        return view('owner.services', compact('services'));
    }

    // Staff
    public function staff()
    {
        // Get all staff from pengguna table
        $employees = DB::table('pengguna')
            ->where('role', 'staff')
            ->orWhere('role', 'admin')
            ->get();

        $employees = $employees->map(function($emp) {
            return [
                'name' => $emp->nama_lengkap,
                'position' => $emp->role === 'admin' ? 'Administrator' : 'Staff',
                'status' => 'active',
                'department' => 'Operasional',
                'email' => $emp->email,
                'phone' => $emp->no_telepon ?? '-',
                'shift' => 'Pagi (08:00 - 16:00)',
                'specialization' => 'General',
                'experience' => '1 tahun',
                'joinDate' => Carbon::parse($emp->created_at)->format('d M Y'),
                'rating' => 4.5,
                'salary' => 5000000,
                'bonus' => 500000
            ];
        })->toArray();

        // Department Stats
        $departmentStats = [
            ['name' => 'Operasional', 'employees' => count($employees)],
            ['name' => 'Grooming', 'employees' => 0],
            ['name' => 'Veteriner', 'employees' => 0],
            ['name' => 'Customer Service', 'employees' => 0],
            ['name' => 'Administrasi', 'employees' => count(array_filter($employees, fn($e) => $e['position'] === 'Administrator'))]
        ];

        // Payroll Stats
        $totalPayroll = array_sum(array_column($employees, 'salary')) + array_sum(array_column($employees, 'bonus'));
        $totalEmployees = count($employees);
        $avgSalary = $totalEmployees > 0 ? $totalPayroll / $totalEmployees : 0;

        return view('owner.staff', compact('employees', 'departmentStats', 'totalPayroll', 'totalEmployees', 'avgSalary'));
    }

    // Reports
    public function reports()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Total Revenue
        $totalRevenue = DB::table('pembayaran')
            ->whereMonth('tanggal_bayar', $currentMonth)
            ->whereYear('tanggal_bayar', $currentYear)
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah_bayar');

        $revenueGrowth = '+15.2%'; // Placeholder

        // Total Bookings
        $totalBookings = DB::table('penitipan')
            ->whereMonth('tanggal_masuk', $currentMonth)
            ->whereYear('tanggal_masuk', $currentYear)
            ->count();

        $bookingsGrowth = '+12.5%'; // Placeholder

        // Active Customers
        $activeCustomers = DB::table('penitipan')
            ->whereMonth('tanggal_masuk', $currentMonth)
            ->whereYear('tanggal_masuk', $currentYear)
            ->distinct('id_pemilik')
            ->count('id_pemilik');

        $customersGrowth = '+8.3%'; // Placeholder

        // Average Rating
        $avgRating = 4.8; // Placeholder
        $ratingChange = '+0.2'; // Placeholder

        // Revenue Chart Data (6 months)
        $revenueChartData = [
            'labels' => [],
            'data' => []
        ];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenue = DB::table('pembayaran')
                ->whereMonth('tanggal_bayar', $month->month)
                ->whereYear('tanggal_bayar', $month->year)
                ->where('status_pembayaran', 'lunas')
                ->sum('jumlah_bayar');
            
            $revenueChartData['labels'][] = $month->format('M Y');
            $revenueChartData['data'][] = round($revenue / 1000000, 2);
        }

        // Booking Chart Data
        $bookingChartData = [
            'labels' => [],
            'bookings' => [],
            'customers' => []
        ];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $bookings = DB::table('penitipan')
                ->whereMonth('tanggal_masuk', $month->month)
                ->whereYear('tanggal_masuk', $month->year)
                ->count();
            
            $customers = DB::table('penitipan')
                ->whereMonth('tanggal_masuk', $month->month)
                ->whereYear('tanggal_masuk', $month->year)
                ->distinct('id_pemilik')
                ->count('id_pemilik');
            
            $bookingChartData['labels'][] = $month->format('M');
            $bookingChartData['bookings'][] = $bookings;
            $bookingChartData['customers'][] = $customers;
        }

        // Service Performance
        $servicePerformance = DB::table('detail_penitipan')
            ->join('paket_layanan', 'detail_penitipan.id_paket', '=', 'paket_layanan.id_paket')
            ->join('penitipan', 'detail_penitipan.id_penitipan', '=', 'penitipan.id_penitipan')
            ->join('pembayaran', 'penitipan.id_penitipan', '=', 'pembayaran.id_penitipan')
            ->whereMonth('penitipan.tanggal_masuk', $currentMonth)
            ->whereYear('penitipan.tanggal_masuk', $currentYear)
            ->select(
                'paket_layanan.nama_paket as name',
                DB::raw('SUM(pembayaran.jumlah_bayar) as revenue'),
                DB::raw('COUNT(detail_penitipan.id_detail) as bookings'),
                DB::raw('4.5 as rating'),
                DB::raw('"+10%" as growth')
            )
            ->groupBy('paket_layanan.nama_paket')
            ->get();

        return view('owner.reports', compact(
            'totalRevenue',
            'revenueGrowth',
            'totalBookings',
            'bookingsGrowth',
            'activeCustomers',
            'customersGrowth',
            'avgRating',
            'ratingChange',
            'revenueChartData',
            'bookingChartData',
            'servicePerformance'
        ));
    }

    // Helper function
    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'aktif' => 'Check-in',
            'selesai' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];

        return $labels[$status] ?? ucfirst($status);
    }
}

