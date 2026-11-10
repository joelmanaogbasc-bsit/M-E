<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Addon;


// HOME
Route::get('/', function () {
    return view('home');
});




// PUBLIC PACKAGES
Route::get('/packages', function () {
    $packages = Package::where('is_available', true)
        ->latest()
        ->get();

    return view('packages', [
        'packages' => $packages,
    ]);
});


Route::get('/contact', function () {
    return view('contact');
});



// REGISTER PAGE
Route::get('/register', function () {
    return view('register');
});


// PROCESS REGISTRATION
Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    return redirect('/login')->with(
        'success',
        'Registration successful! You can now log in.'
    );
});


// LOGIN PAGE
Route::get('/login', function () {
    return view('login');
})->name('login');


// PROCESS LOGIN
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        if (Auth::user()->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        return redirect('/dashboard');
    }

    return back()
        ->withErrors([
            'email' => 'Incorrect email or password.',
        ])
        ->onlyInput('email');
});


// CUSTOMER DASHBOARD
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role === 'admin') {
        return redirect('/admin/dashboard');
    }

    return view('customer_dashboard', [
        'user' => Auth::user(),
    ]);
});


// CUSTOMER: ACCOUNT SETTINGS PAGE
Route::get('/account/settings', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    return view('account-settings', [
        'user' => Auth::user(),
    ]);
});


// CUSTOMER: UPDATE PROFILE INFORMATION
Route::put('/account/settings', function (Request $request) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    $user = Auth::user();

    $validated = $request->validate([
        'name' => 'required|string|max:255',

        'email' => [
            'required',
            'email',
            'max:255',
            Rule::unique('users', 'email')->ignore($user->id),
        ],

        'phone' => 'nullable|string|max:20',
    ]);

    // Update only the logged-in user's information.
    $emailChanged = $user->email !== $validated['email'];

    $user->name = $validated['name'];
    $user->email = $validated['email'];
    $user->phone = $validated['phone'] ?? null;

    // Clear verification if the email address changes.
    if ($emailChanged) {
        $user->email_verified_at = null;
    }

    $user->save();

    return redirect('/account/settings')->with(
        'success',
        'Your profile information has been updated successfully!'
    );
});


// CUSTOMER: CHANGE PASSWORD
Route::put('/account/password', function (Request $request) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    $validated = $request->validate([
        'current_password' => 'required|string',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = Auth::user();

    // Verify the current password.
    if (!Hash::check($validated['current_password'], $user->password)) {
        return back()
            ->withErrors([
                'current_password' => 'Your current password is incorrect.',
            ])
            ->withInput();
    }

    // Save the new password.
    $user->password = $validated['password'];
    $user->save();

    return redirect('/account/settings')->with(
        'success',
        'Your password has been changed successfully!'
    );
});


// MY BOOKINGS
Route::get('/my-bookings', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    $bookings = Booking::with('addons')
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

    return view('my-bookings', [
        'bookings' => $bookings,
    ]);
});


// CUSTOMER: CANCEL OWN PENDING BOOKING
Route::post('/my-bookings/{id}/cancel', function ($id) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role === 'admin') {
        abort(403, 'Administrators cannot cancel customer bookings.');
    }

    $booking = Booking::where('user_id', Auth::id())
        ->findOrFail($id);

    if ($booking->status !== 'Pending') {
        return redirect('/my-bookings')->with(
            'error',
            'Only pending bookings can be cancelled.'
        );
    }

    $booking->status = 'Cancelled';
    $booking->save();

    return redirect('/my-bookings')->with(
        'success',
        'Your booking has been cancelled successfully!'
    );
});


// BOOKING FORM
Route::get('/booking', function (Request $request) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role === 'admin') {
        abort(403, 'Administrators cannot submit customer bookings.');
    }

    $packages = Package::where('is_available', true)
        ->orderBy('name')
        ->get();

    $addons = Addon::where('is_available', true)
        ->orderBy('name')
        ->get();

    return view('booking', [
        'packages' => $packages,
        'addons' => $addons,
        'selectedPackage' => $request->query('package'),
    ]);
});


// SAVE BOOKING
Route::post('/booking', function (Request $request) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role === 'admin') {
        abort(403, 'Administrators cannot submit customer bookings.');
    }

    $validated = $request->validate([
        'customer_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:30',

        'package' => [
            'required',
            'string',
            'max:255',
            Rule::exists('packages', 'name')
                ->where('is_available', true),
        ],

        'addons' => 'nullable|array',

        'addons.*' => [
            'integer',
            Rule::exists('addons', 'id')
                ->where('is_available', true),
        ],

        'payment_method' => 'required|in:Cash,GCash',

        'event_date' => [
            'required',
            'date',
            'after_or_equal:today',

            function ($attribute, $value, $fail) {
                $dateIsReserved = Booking::whereDate('event_date', $value)
                    ->whereIn('status', ['Pending', 'Approved'])
                    ->exists();

                if ($dateIsReserved) {
                    $fail(
                        'This event date is already reserved. Please select another date.'
                    );
                }
            },
        ],

        'event_location' => 'required|string|max:255',
        'notes' => 'nullable|string|max:1000',
    ], [
        'package.exists' => 'The selected package is unavailable. Please choose another package.',
        'addons.*.exists' => 'One of the selected add-ons is unavailable. Please refresh the page and try again.',
        'payment_method.required' => 'Please select a payment method.',
        'payment_method.in' => 'Please select Cash or GCash.',
    ]);

    // SAVE BOOKING AND CALCULATE PRICES
    $booking = DB::transaction(function () use ($validated) {

        // Get selected available package.
        $package = Package::where('name', $validated['package'])
            ->where('is_available', true)
            ->firstOrFail();

        // Save package price.
        $packagePrice = (float) $package->price;

        // Get selected add-ons.
        $selectedAddonIds = $validated['addons'] ?? [];

        $selectedAddons = collect();

        if (!empty($selectedAddonIds)) {
            $selectedAddons = Addon::whereIn('id', $selectedAddonIds)
                ->where('is_available', true)
                ->get();
        }

        // Calculate add-on cost.
        $addonTotal = 0;
        $pivotData = [];

        foreach ($selectedAddons as $addon) {
            $addonPrice = (float) $addon->price;

            $addonTotal += $addonPrice;

            $pivotData[$addon->id] = [
                'addon_name' => $addon->name,
                'price' => $addonPrice,
                'quantity' => 1,
            ];
        }

        // Calculate total rental cost.
        $totalAmount = $packagePrice + $addonTotal;

        // Create booking.
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'customer_name' => $validated['customer_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],

            'package' => $validated['package'],
            'package_price' => $packagePrice,
            'total_amount' => $totalAmount,

            'event_date' => $validated['event_date'],
            'event_location' => $validated['event_location'],
            'notes' => $validated['notes'] ?? null,

            'status' => 'Pending',

            'payment_method' => $validated['payment_method'],
            'payment_status' => 'Pending',
        ]);

        // Save selected add-ons.
        if (!empty($pivotData)) {
            $booking->addons()->attach($pivotData);
        }

        return $booking;
    });

    return redirect('/booking-confirmation/' . $booking->id)
        ->with('success', 'Your booking has been saved successfully!');
});


// BOOKING CONFIRMATION
Route::get('/booking-confirmation/{id}', function ($id) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    $booking = Booking::with('addons')
        ->where('user_id', Auth::id())
        ->findOrFail($id);

    return view('booking-confirmation', [
        'booking' => $booking,
    ]);
});


// ADMIN DASHBOARD
Route::get('/admin/dashboard', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to access this page.');
    }

    return view('admin_dashboard', [

        // Booking statistics
        'totalBookings' => Booking::count(),

        'pendingBookings' => Booking::where('status', 'Pending')->count(),

        'approvedBookings' => Booking::where('status', 'Approved')->count(),

        'rejectedBookings' => Booking::where('status', 'Rejected')->count(),

        'cancelledBookings' => Booking::where('status', 'Cancelled')->count(),

        // Booking value summaries
        'totalBookingValue' => Booking::sum('total_amount'),

        'approvedBookingValue' => Booking::where('status', 'Approved')
            ->sum('total_amount'),

        'pendingBookingValue' => Booking::where('status', 'Pending')
            ->sum('total_amount'),

        // Latest bookings
        'recentBookings' => Booking::with('user')
            ->latest()
            ->take(5)
            ->get(),
    ]);
});


// ADMIN: BOOKING CALENDAR
Route::get('/admin/calendar', function (Request $request) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to access this page.');
    }

    $monthInput = $request->query('month', now()->format('Y-m'));

    if (!preg_match('/^\d{4}-\d{2}$/', $monthInput)) {
        $monthInput = now()->format('Y-m');
    }

    try {
        $start = \Carbon\Carbon::createFromFormat('!Y-m', $monthInput);

        if (!$start || $start->format('Y-m') !== $monthInput) {
            $start = now()->startOfMonth();
        }
    } catch (\Exception $e) {
        $start = now()->startOfMonth();
    }

    $start->startOfMonth();
    $end = $start->copy()->endOfMonth();

    $bookings = Booking::whereBetween('event_date', [
        $start->toDateString(),
        $end->toDateString(),
    ])
        ->orderBy('event_date')
        ->get();

    $bookingsByDate = $bookings->groupBy(function ($booking) {
        return $booking->event_date->format('Y-m-d');
    });

    $calendarStart = $start->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
    $calendarEnd = $end->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);

    $calendarDays = [];

    for (
        $day = $calendarStart->copy();
        $day->lte($calendarEnd);
        $day->addDay()
    ) {
        $dateKey = $day->format('Y-m-d');

        $calendarDays[] = [
            'date' => $day->copy(),
            'bookings' => $bookingsByDate->get($dateKey, collect()),
        ];
    }

    $previousMonth = $start->copy()->subMonth()->format('Y-m');
    $nextMonth = $start->copy()->addMonth()->format('Y-m');

    return view('admin-calendar', [
        'start' => $start,
        'calendarDays' => $calendarDays,
        'previousMonth' => $previousMonth,
        'nextMonth' => $nextMonth,
        'totalBookings' => $bookings->count(),
    ]);
});


// ADMIN: VIEW ALL BOOKINGS
Route::get('/admin/bookings', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to access this page.');
    }

    $bookings = Booking::with(['user', 'addons'])
        ->latest()
        ->get();

    return view('admin-bookings', [
        'bookings' => $bookings,
    ]);
});


// ADMIN: EXPORT BOOKINGS TO CSV
Route::get('/admin/bookings/export', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to access this page.');
    }

    $bookings = Booking::with('addons')
        ->latest()
        ->get();

    return response()->streamDownload(function () use ($bookings) {
        $file = fopen('php://output', 'w');

        fputcsv($file, [
            'Booking ID',
            'Customer Name',
            'Email',
            'Phone',
            'Package',
            'Package Price',
            'Add-ons',
            'Total Rental Cost',
            'Event Date',
            'Event Location',
            'Payment Method',
            'Payment Status',
            'Booking Status',
            'Notes',
        ]);

        foreach ($bookings as $booking) {
            $addonNames = $booking->addons
                ->map(function ($addon) {
                    return $addon->pivot->addon_name;
                })
                ->implode(', ');

            fputcsv($file, [
                $booking->id,
                $booking->customer_name,
                $booking->email,
                $booking->phone,
                $booking->package,
                $booking->package_price,
                $addonNames,
                $booking->total_amount,
                $booking->event_date
                    ? $booking->event_date->format('Y-m-d')
                    : '',
                $booking->event_location,
                $booking->payment_method,
                $booking->payment_status,
                $booking->status,
                $booking->notes,
            ]);
        }

        fclose($file);

    }, 'me-rental-bookings.csv', [
        'Content-Type' => 'text/csv',
    ]);
});


// ADMIN: VERIFY BOOKING PAYMENT
Route::post('/admin/bookings/{id}/payment', function (Request $request, $id) {

    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to verify payments.');
    }

    $validated = $request->validate([
        'payment_reference' => 'nullable|string|max:255',
    ]);

    $booking = Booking::findOrFail($id);

    if (in_array($booking->status, ['Rejected', 'Cancelled'])) {
        return redirect('/admin/bookings')->with(
            'error',
            'Payment cannot be verified for rejected or cancelled bookings.'
        );
    }

    if ($booking->payment_status === 'Paid') {
        return redirect('/admin/bookings')->with(
            'error',
            'This booking has already been marked as Paid.'
        );
    }

    $booking->payment_status = 'Paid';
    $booking->paid_at = now();
    $booking->payment_reference = $validated['payment_reference'] ?? null;

    $booking->save();

    return redirect('/admin/bookings')->with(
        'success',
        'Payment successfully verified! Booking #' . $booking->id . ' is now marked as Paid.'
    );
});


// ADMIN: APPROVE OR REJECT BOOKING
Route::post('/admin/bookings/{id}/status', function (Request $request, $id) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to perform this action.');
    }

    $validated = $request->validate([
        'status' => 'required|in:Approved,Rejected',
    ]);

    $booking = Booking::findOrFail($id);

    // Check date availability before approval.
    if ($validated['status'] === 'Approved') {
        $dateIsReserved = Booking::whereDate(
                'event_date',
                $booking->event_date
            )
            ->whereIn('status', ['Pending', 'Approved'])
            ->where('id', '!=', $booking->id)
            ->exists();

        if ($dateIsReserved) {
            return redirect('/admin/bookings')->with(
                'error',
                'This booking cannot be approved because another active booking already uses this event date.'
            );
        }
    }

    $booking->status = $validated['status'];
    $booking->save();

    return redirect('/admin/bookings')->with(
        'success',
        'Booking status updated successfully!'
    );
});


// ADMIN: VIEW PACKAGE MANAGEMENT
Route::get('/admin/packages', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to access this page.');
    }

    $packages = Package::latest()->get();

    return view('admin-packages', [
        'packages' => $packages,
    ]);
});


// ADMIN: SHOW ADD PACKAGE FORM
Route::get('/admin/packages/create', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to access this page.');
    }

    return view('admin-package-create');
});


// ADMIN: SAVE NEW PACKAGE
Route::post('/admin/packages', function (Request $request) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to perform this action.');
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:2000',
        'price' => 'required|numeric|min:0',
        'duration' => 'nullable|string|max:255',
        'image' => 'nullable|url|max:2048',
        'is_available' => 'required|boolean',
    ]);

    Package::create($validated);

    return redirect('/admin/packages')->with(
        'success',
        'Package added successfully!'
    );
});


// ADMIN: SHOW EDIT PACKAGE FORM
Route::get('/admin/packages/{id}/edit', function ($id) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to access this page.');
    }

    $package = Package::findOrFail($id);

    return view('admin-package-edit', [
        'package' => $package,
    ]);
});


// ADMIN: UPDATE PACKAGE
Route::put('/admin/packages/{id}', function (Request $request, $id) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to perform this action.');
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:2000',
        'price' => 'required|numeric|min:0',
        'duration' => 'nullable|string|max:255',
        'image' => 'nullable|url|max:2048',
        'is_available' => 'required|boolean',
    ]);

    $package = Package::findOrFail($id);
    $package->update($validated);

    return redirect('/admin/packages')->with(
        'success',
        'Package updated successfully!'
    );
});


// ADMIN: DELETE PACKAGE
Route::delete('/admin/packages/{id}', function ($id) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to perform this action.');
    }

    $package = Package::findOrFail($id);

    // Prevent deleting packages with existing bookings.
    $hasBookings = Booking::where('package', $package->name)->exists();

    if ($hasBookings) {
        return redirect('/admin/packages')->with(
            'error',
            'This package cannot be deleted because it has existing bookings.'
        );
    }

    $package->delete();

    return redirect('/admin/packages')->with(
        'success',
        'Package deleted successfully!'
    );
});


// ADMIN: VIEW ADD-ONS
Route::get('/admin/addons', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to access this page.');
    }

    $addons = Addon::latest()->get();

    return view('admin-addons', [
        'addons' => $addons,
    ]);
});


// ADMIN: SHOW ADD-ON CREATE FORM
Route::get('/admin/addons/create', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to access this page.');
    }

    return view('admin-addon-create');
});


// ADMIN: SAVE NEW ADD-ON
Route::post('/admin/addons', function (Request $request) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to perform this action.');
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:addons,name',
        'description' => 'nullable|string|max:2000',
        'price' => 'required|numeric|min:0',
        'is_available' => 'required|boolean',
    ]);

    Addon::create($validated);

    return redirect('/admin/addons')->with(
        'success',
        'Add-on added successfully!'
    );
});


// ADMIN: SHOW EDIT ADD-ON FORM
Route::get('/admin/addons/{id}/edit', function ($id) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to access this page.');
    }

    $addon = Addon::findOrFail($id);

    return view('admin-addon-edit', [
        'addon' => $addon,
    ]);
});


// ADMIN: UPDATE ADD-ON
Route::put('/admin/addons/{id}', function (Request $request, $id) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to perform this action.');
    }

    $addon = Addon::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:addons,name,' . $addon->id,
        'description' => 'nullable|string|max:2000',
        'price' => 'required|numeric|min:0',
        'is_available' => 'required|boolean',
    ]);

    $addon->update($validated);

    return redirect('/admin/addons')->with(
        'success',
        'Add-on updated successfully!'
    );
});


// ADMIN: DELETE ADD-ON
Route::delete('/admin/addons/{id}', function ($id) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403, 'You are not authorized to perform this action.');
    }

    $addon = Addon::findOrFail($id);
    $addon->delete();

    return redirect('/admin/addons')->with(
        'success',
        'Add-on deleted successfully!'
    );
});


// LOGOUT
Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login')->with(
        'success',
        'You have logged out successfully.'
    );
});
