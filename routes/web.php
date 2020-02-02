<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// auth
Auth::routes();

Route::get('template', function() {
    return view('emails.welcome');
});


// wallet
Route::get('wallet', 'WalltetController@index')->name('agent.home.wallet.index');
Route::post('wallet', 'WalltetController@store')->name('wallet.store');
Route::get('wallet/{txn_id}/callback', 'WalltetController@callback')->name('wallet.callback');

// home
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home.home');
Route::get('/book', 'HomeController@book')->name('home.book-now')->middleware(['auth']);
Route::get('/submit', 'WalltetController@message')->name('agent.home.wallet.message');

// user payments
Route::get('payment', 'PaymentController@index')->name('agent.home.payment.index');

// upload transfer document
Route::get('upload/transfer', 'UploadTransferController@index')->name('admin.upload.transfer.index');
Route::post('upload/transfer', 'UploadTransferController@store')->name('admin.upload.transfer.store');

// upload hotel contract document
Route::get('upload/hotel-contract', 'UploadHotelContractController@index')->name('admin.upload.hotel-contract.index');
Route::post('upload/hotel-contract', 'UploadHotelContractController@store')->name('admin.upload.hotel-contract.store');

// search for tours
Route::any('/tours/search', 'TourAgentController@search')->name('tours.search');

// Tours Voucher
Route::get('/mytourvoucher/{id}/{booking_id}', 'HotelController@showTourVoucher')->name('booking.tourvoucher');
Route::get('/mytourvoucherpdf/{id}/{booking_id}', 'HotelController@pdfTourVoucher')->name('booking.tourvoucherpdf');

// Tours Invoice
Route::get('/mytourinvoice/{id}/{booking_id}', 'HotelController@showTourInvoice')->name('booking.tourinvoice');
Route::get('/mytourinvoicepdf/{id}/{booking_id}', 'HotelController@pdfTourInvoice')->name('booking.tourinvoicepdf');

// cancel Tour
Route::get('/mytourbooking/{id}/{booking_id}/cancel', 'HotelController@cancelTourIndex')->name('tourbooking.cancel.index');
Route::post('/mytourbooking/{id}/{booking_id}/update', 'HotelController@cancelTourUpdate')->name('tourbooking.cancel.update');

// tours search results page
Route::get('/tours/search/{search_id}/{tour_id}', 'TourAgentController@show')->name('tours.search.show');

// remove cart item
Route::get('/tourcart/{id}/remove', 'TourAgentController@removeCartItem')->name('tourcart.remove');

// add to cart i.e. prebook tour
Route::get('/tours/searchprebook/{search_id}/{tour_id}', 'TourAgentController@prebook')->name('tours.search.prebook');

// search for transfers
// Route::post('/transfers/search', 'TransferController@search')->name('transfers.search');
Route::get('/transfers/search/transport', 'TransferController@search')->name('transfers.search');

// transfers
Route::get('transfersvoucher/{id}/{booking_id}', 'TransferController@showVoucher')->name('booking.transfervoucher');
Route::get('transfersvoucher/pdf/{id}/{booking_id}', 'TransferController@pdfVoucher')->name('booking.transfervoucherpdf');
Route::get('transfersinvoice/{id}/{booking_id}', 'TransferController@showInvoice')->name('booking.transferinvoice');
Route::get('transfersinvoice/pdf/{id}/{booking_id}', 'TransferController@pdfInvoice')->name('booking.transferinvoicepdf');
Route::get('transfersbooking/{id}/{booking_id}/cancel', 'TransferController@cancelIndex')->name('transferbooking.cancel.index');
Route::post('transfersbooking/{id}/{booking_id}/update', 'TransferController@cancelUpdate')->name('transferbooking.cancel.update');

// transfers search results page
Route::get('/transfers/search/{search_id}/{tour_id}', 'TransferController@show')->name('transfers.search.show');

// remove transfer cart item
Route::get('/transfercart/{id}/remove', 'TransferController@removeCartItem')->name('transfercart.remove');

// add transfer to cart
Route::get('/transfers/searchprebook/{search_id}/{transfer_id}', 'TransferController@prebook')->name('transfers.search.prebook');

// search for hotels
Route::post('/hotels/search', 'HotelController@search')->name('hotels.search');

// hotel search results page
Route::get('/hotels/search/{search_id}', 'HotelController@index')->name('hotels.index');

// hotel search results page
Route::get('/hotels/search/{search_id}/{hotel_id}', 'HotelController@show')->name('hotels.show');

// add to cart i.e. prebook
Route::get('/hotels/search/{search_id}/{hotel_id}/{booking_id}', 'HotelController@prebook')->name('hotels.prebook');

// cart
Route::get('/cart', 'HotelController@cart')->name('cart.index');

// checkout
Route::get('/cart/checkout', 'HotelController@checkout')->name('cart.checkout');
Route::post('/cart/checkout', 'HotelController@book')->name('cart.book');
Route::get('/cart/checkout/{txn_id}/callback', 'HotelController@callback')->name('cart.callback');

// empty cart
Route::get('/cart/empty', 'HotelController@emptyCart')->name('cart.empty');

// remove cart item
Route::get('/cart/{id}/remove', 'HotelController@removeCartItem')->name('cart.remove');

// bookings
Route::get('/mybooking', 'HotelController@bookings')->name('booking.index');
Route::get('/mybooking/view/{id}/{booking_id}', 'HotelController@showMyBooking')->name('mybooking.show');
Route::get('/mybooking/details/{id}', 'HotelController@showMyBookingByID')->name('mybookingbyid.show');
Route::any('/mybooking/{id}', 'HotelController@showBooking')->name('booking.show');

Route::get('/myvoucher/{id}/{booking_id}', 'HotelController@showVoucher')->name('booking.voucher');
Route::get('/myvoucherpdf/{id}/{booking_id}', 'HotelController@pdfVoucher')->name('booking.voucherpdf');
Route::get('/myinvoice/{id}/{booking_id}', 'HotelController@showInvoice')->name('booking.invoice');
Route::get('/myinvoicepdf/{id}/{booking_id}', 'HotelController@pdfInvoice')->name('booking.invoicepdf');

Route::get('/myprofile', 'ProfileController@index')->name('profile.index');
Route::post('/myprofileupdate', 'ProfileController@update')->name('profile.update');

Route::get('/mybooking/{id}/{booking_id}/cancel', 'HotelController@cancelIndex')->name('booking.cancel.index');
Route::post('/mybooking/{id}/{booking_id}/cancel', 'HotelController@cancelUpdate')->name('booking.cancel.update');

// get request logout
Route::get('/logout', 'Auth\LoginController@logout');

// change currency
Route::post('/currency', 'HotelController@changeCurrency')->name('currency.change');

/*
Admin Routes
*/

// Members
Route::middleware(['auth', 'admin'])->resource('admin/agents', 'Admin\AgentController');
Route::middleware(['auth', 'admin'])->resource('admin/agents.wallets', 'Admin\WalletController');

//Tours
Route::middleware(['auth', 'admin'])->resource('admin/tours', 'Admin\TourController');

//Tours
Route::middleware(['auth', 'admin'])->resource('admin/transfers', 'Admin\TransferController');

//All Booking
// Route::middleware(['auth', 'admin'])->resource('admin/booking', 'Admin\BookingController');
Route::middleware(['auth', 'admin'])->get('admin/booking', 'Admin\BookingController@index')->name('admin.booking.index');
//Booking ststus
Route::middleware(['auth', 'admin'])->get('/changetourbookingstatus/{id}/{booking_id}', 'Admin\BookingController@bookingTourStatus')->name('changetourbooking.status');

//Booking ststus updated
Route::get('/updatebookingstatus/{id}', 'HotelController@bookingUpdateStatus')->name('updateourbooking.status');

//Admin Booking
Route::middleware(['auth', 'admin'])->get('/updatebookingstatusbyadmin/{id}', 'Admin\BookingController@bookingUpdateStatus')->name('updateourbookingbyadmin.status');
Route::middleware(['auth', 'admin'])->get('/mybookingshowbyidadmin/{id}', 'Admin\BookingController@showMyBookingByID')->name('mybookingbyidbyadmin.show');
Route::middleware(['auth', 'admin'])->get('/mybookingadminview/{id}', 'Admin\BookingController@showBooking')->name('bookingadmin.show');
Route::middleware(['auth', 'admin'])->get('/mytourbookingadmin/{id}/{booking_id}/cancel', 'Admin\BookingController@cancelTourIndex')->name('tourbookingadmin.cancel.index');
Route::middleware(['auth', 'admin'])->get('/mytransferbookingadmin/{id}/{booking_id}/cancel', 'Admin\BookingController@cancelTransferIndex')->name('transferbookingadmin.cancel.index');
Route::middleware(['auth', 'admin'])->get('/mybookingadmin/{id}/{booking_id}/cancel', 'Admin\BookingController@cancelIndex')->name('bookingadmin.cancel.index');
Route::middleware(['auth', 'admin'])->post('/mytourbookingadmin/{id}/{booking_id}/update', 'Admin\BookingController@cancelTourUpdate')->name('tourbookingadmin.cancel.update');
Route::middleware(['auth', 'admin'])->post('/mytransferbookingadmin/{id}/{booking_id}/update', 'Admin\BookingController@cancelTransferUpdate')->name('transferbookingadmin.cancel.update');
Route::middleware(['auth', 'admin'])->post('/mybookingadminupdate/{id}/{booking_id}/cancel', 'Admin\BookingController@cancelUpdate')->name('bookingadmin.cancel.update');
Route::middleware(['auth', 'admin'])->get('/mytourinvoiceadmin/{id}/{booking_id}', 'Admin\BookingController@showTourInvoice')->name('bookingadmin.tourinvoice');
Route::middleware(['auth', 'admin'])->get('/mytourvoucheradmin/{id}/{booking_id}', 'Admin\BookingController@showTourVoucher')->name('bookingadmin.tourvoucher');
Route::middleware(['auth', 'admin'])->get('/myvoucheradmin/{id}/{booking_id}', 'Admin\BookingController@showVoucher')->name('bookingadmin.voucher');
Route::middleware(['auth', 'admin'])->get('/myinvoiceadmin/{id}/{booking_id}', 'Admin\BookingController@showInvoice')->name('bookingadmin.invoice');
Route::middleware(['auth', 'admin'])->get('/myvoucherpdfadmin/{id}/{booking_id}', 'Admin\BookingController@pdfVoucher')->name('bookingadmin.voucherpdf');
Route::middleware(['auth', 'admin'])->get('/myinvoicepdfadmin/{id}/{booking_id}', 'Admin\BookingController@pdfInvoice')->name('bookingadmin.invoicepdf');
Route::middleware(['auth', 'admin'])->get('/mytourvoucherpdfadmin/{id}/{booking_id}', 'Admin\BookingController@pdfTourVoucher')->name('bookingadmin.tourvoucherpdf');
Route::middleware(['auth', 'admin'])->get('/mytourinvoicepdfadmin/{id}/{booking_id}', 'Admin\BookingController@pdfTourInvoice')->name('bookingadmin.tourinvoicepdf');

Route::middleware(['auth', 'admin'])->get('/mytransferinvoiceadmin/{id}/{booking_id}', 'Admin\BookingController@showTransferInvoice')->name('bookingadmin.transferinvoice');
Route::middleware(['auth', 'admin'])->get('/mytransfervoucheradmin/{id}/{booking_id}', 'Admin\BookingController@showTransferVoucher')->name('bookingadmin.transfervoucher');

Route::middleware(['auth', 'admin'])->get('/mytransfervoucherpdfadmin/{id}/{booking_id}', 'Admin\BookingController@pdfTransferVoucher')->name('bookingadmin.transfervoucherpdf');
Route::middleware(['auth', 'admin'])->get('/mytransferinvoicepdfadmin/{id}/{booking_id}', 'Admin\BookingController@pdfTransferInvoice')->name('bookingadmin.transferinvoicepdf');

// send cancellation expiration emails
Route::get('/notify/expiry', 'CancellationExpiryNotificationController');

Route::get('admin/types', 'Admin\TypeController@index')->name('admin.type.index');
Route::get('admin/types/create', 'Admin\TypeController@create')->name('admin.type.create');
Route::post('admin/types', 'Admin\TypeController@store')->name('admin.type.store');
Route::get('admin/types/{id}', 'Admin\TypeController@edit')->name('admin.type.edit');
Route::put('admin/types/{id}', 'Admin\TypeController@update')->name('admin.type.update');
Route::delete('admin/types/{id}', 'Admin\TypeController@destroy')->name('admin.type.destroy');

// Search History
Route::get('home/logs', 'HistoryController@index')->name('agent.home.index');


Route::get('upload', 'TourAgentController@create')->name('agent.home.hotelupload.create');

//Currency converter API
Route::get('post','DataController@postRequest');
Route::get('get','DataController@getRequest');


Route::get('convertcurrency', 'DataController@index')->name('agent.home.convertcurrency.index');
Route::get('convertcurrency', 'DataController@store');

Route::post('currency', 'CurrencyController@switch')->name('currency.switch');
//Route::get('currency','CurrencyController@storecurrency')->name('currency.storecurrency');




//Route::post('paypal', 'HotelController@checkout');

Route::post('payment', 'HotelController@paywithpaypal')->name('paywithpaypal');
Route::get('cancel', 'HotelController@cancel')->name('payment.cancel');
Route::get('payment/success', 'HotelController@success')->name('payment.success');

//  Route::get('success', 'HotelController@success')->name('agent.home.success.index');