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

Auth::routes();

Route::get('/', 'HomeController@index')->name('menu');
Route::get('/user', 'UserController@view')->name('user');

Route::prefix('/user/create')->middleware('can:create_users')->group(function () {
    Route::get('/', 'UserController@insertForm')->name('create-user');
    Route::post('/', 'UserController@insertAction');
});

Route::prefix('/admin')->middleware('can:update_users')->group(function () {
    Route::get('/', 'UserController@admin')->name('manage-users');
});

Route::prefix('/workers-amount')->middleware('can:update_users')->group(function () {
    Route::get('/', 'UserController@workersAmount')->name('workers-amount');
});

Route::prefix('/all-invoices-list')->middleware('can:update_users')->group(function () {
    Route::get('/', 'InvoiceController@allInvoicesList')->name('all-invoices-list');
});

Route::prefix('/user/update-password/{id}')->group(function () {
    Route::get('/', 'UserController@updatePasswordForm')->name('update-password');
    Route::put('/', 'UserController@updatePasswordAction');
});

Route::prefix('//user/update/{id}')->middleware('can:update_users')->group(function () {
    Route::get('/', 'UserController@updateForm')->name('update-password');
    Route::put('/', 'UserController@updateAction');
});

Route::prefix('/user/list')->middleware('can:update_users')->group(function () {
    Route::get('/', 'UserController@list')->name('users-list');
});

Route::prefix('/customer/create')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'CustomerController@insertForm')->name('create-customer');
    Route::post('/', 'CustomerController@insertAction');
});
Route::prefix('/customer/update/{customer}')->middleware('can:update_customers')->group(function () {
    Route::get('/', 'CustomerController@updateForm')->name('customer-update');
    Route::put('/', 'CustomerController@updateAction');
});

Route::prefix('/customer/list')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'CustomerController@list')->name('customers-list');
});

Route::prefix('/contact-person/create/{customer}')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'CustomerController@insertContactPersonForm')->name('create-contact-person');
    Route::post('/', 'CustomerController@insertContactPersonAction');
});
Route::prefix('/contact-person/update/{id}')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'CustomerController@updateContactPersonForm');
    Route::put('/', 'CustomerController@updateContactPersonAction');
});
Route::prefix('/customer-event/create/{customer}')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'CustomerController@insertCustomerEventForm')->name('create-customer-event');
    Route::post('/', 'CustomerController@insertCustomerEventAction');
});
Route::prefix('/customer-event/update/{id}')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'CustomerController@updateCustomerEventForm');
    Route::put('/', 'CustomerController@updateCustomerEventAction');
});

Route::prefix('/product/create')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'ProductController@insertProductForm')->name('create-product');
    Route::post('/', 'ProductController@insertProductAction');
});

Route::prefix('/product/update/{id}')->middleware('can:update_customers')->group(function () {
    Route::get('/', 'ProductController@updateProductForm')->name('update-product');
    Route::put('/', 'ProductController@updateProductAction');
});

Route::get('/product/list', 'ProductController@list')->middleware('can:create_customers')->name('products-list');
Route::get('/products/search', 'ProductController@searchProduct')->middleware('can:create_customers')->name('product-search');

Route::prefix('/customer/product/add/{customerID}/{productID}')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'CustomerController@insertCustomerProductForm')->name('add-customer-product');
    Route::post('/', 'CustomerController@insertCustomerProductAction');
});

Route::get('/customer/product/update/{customer}/', 'CustomerController@updateCustomerProductForm')->middleware('can:create_customers')->name('update-customer-product');

Route::prefix('/customer/invoice/create/{customer}')->group(function () {
    Route::get('/', 'InvoiceController@insertForm')->name('create-invoice');
    Route::post('/', 'InvoiceController@insertAction');
});
Route::prefix('/customer/invoice/pro_forma/create/{customer}')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'InvoiceController@insertProFormaForm')->name('create-pro-forma-invoice');
    Route::post('/', 'InvoiceController@insertProFormaAction');
});

Route::get('/invoice/{id}', 'InvoiceController@view')->name('invoice-view');
Route::get('/invoice/duplicat/{id}', 'InvoiceController@viewDuplicat')->name('invoice-duplicat-view');

Route::prefix('/customer/invoice/update/{id}')->middleware('can:update_customers')->group(function () {
    Route::get('/', 'InvoiceController@updateInvoiceForm')->name('update-invoice');
    Route::put('/', 'InvoiceController@updateInvoiceAction');
});

Route::prefix('/customer/invoice/proforma/update/{id}')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'InvoiceController@makeInvoiceFromProforma')->name('update-invoice-proforma');
});

Route::prefix('/customer/invoice/correction/create/{id}')->middleware('can:update_customers')->group(function () {
    Route::get('/', 'InvoiceController@insertCorrectionForm')->name('create-correction');
    Route::post('/', 'InvoiceController@insertCorrectionAction');
});

Route::get('/correction/{id}', 'InvoiceController@correctionView')->name('correction-view');

Route::prefix('/customer/offer/create/{customerID}')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'OfferController@insertOfferForm')->name('create-offer');
    Route::post('/', 'OfferController@insertOfferAction');
});

Route::prefix('/customer/offer/update/{id}')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'OfferController@updateOfferForm')->name('update-offer');
    Route::post('/', 'OfferController@updateOfferAction');
});

Route::get('/search', 'CustomerController@search')->middleware('can:create_customers');
Route::get('/customer/search', 'CustomerController@searchCustomer')->middleware('can:create_customers')->name('search');

Route::get('/offer/{id}', 'OfferController@view')->name('offer-view');
Route::get('/invoices/list/{customer}', 'InvoiceController@list')->name('invoices-list');
Route::get('/offers/list/{customer}', 'OfferController@list')->middleware('can:create_customers')->name('offers-list');

Route::get('/customer-events/list/{customer}', 'CustomerController@customerEventslist')->middleware('can:create_customers')->name('events-list');
Route::get('/contact-person/list/{customer}', 'CustomerController@contactPersonlist')->middleware('can:create_customers')->name('contact-person-list');
Route::get('/contact-person/delete/{id}', 'CustomerController@deleteContactPerson')->middleware('can:create_customers')->name('delete-contact-person');

Route::get('/user-events-to-do', 'UserController@userEventsToDolist')->middleware('can:create_customers')->name('to-do-events-list');
Route::get('/user-events-to-do/search', 'UserController@searchUserEventsToDolist')->middleware('can:create_customers')->name('search-to-do-events-list');
Route::get('/today-user-events-to-do', 'UserController@todayUserEventsToDolist')->middleware('can:create_customers')->name('today-to-do-events-list');
Route::get('/old-user-events-to-do', 'UserController@oldUserEventsToDolist')->middleware('can:create_customers')->name('old-to-do-events-list');

Route::prefix('/customer/order/create/{customerID}')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'InvoiceController@insertOrderForm')->name('create-order');
    Route::post('/', 'InvoiceController@insertOrderAction');
});

Route::get('/order/{id}', 'InvoiceController@orderView')->name('order-view');

Route::get('/orderProduction/{id}', 'InvoiceController@orderProductionView')->name('order-view');

Route::get('/orders/list/{customer}', 'InvoiceController@orderList')->middleware('can:create_customers')->name('orders-list');

Route::prefix('/customer/order/update/{id}')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'InvoiceController@updateOrderForm')->name('update-offer');
    Route::post('/', 'InvoiceController@updateOrderAction');
});

Route::prefix('/customer/release/create/{customerID}')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'InvoiceController@insertReleaseForm')->name('create-relase');
    Route::post('/', 'InvoiceController@insertReleaseAction');
});

Route::get('/releases/list/{customer}', 'InvoiceController@releaseList')->middleware('can:create_customers')->name('releases-list');
Route::get('/release/{id}', 'InvoiceController@releaseView')->middleware('can:create_customers')->name('release-view');

Route::get('/invoices/listNotPaid/', 'InvoiceController@listNotPaid')->middleware('can:create_customers')->name('listNotPaid-list');

Route::prefix('/invoice/create/comment/{id}')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'InvoiceController@createInvoiceCommentForm')->name('create-invoice-comment');
    Route::post('/', 'InvoiceController@createInvoiceCommentAction');
});

Route::get('/invoice/create/demand/{id}', 'InvoiceController@createInvoiceDemandForm')->middleware('can:create_customers')->name('create-invoice-demand');

Route::get('/invoices/search', 'InvoiceController@searchDocuments')->middleware('can:create_customers')->name('search-documents');

Route::prefix('/customer/complaint/create/{customer}')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'InvoiceController@insertComplaintForm')->name('create-complaint');
    Route::post('/', 'InvoiceController@insertComplaintAction');

});

Route::prefix('/customer/complaint/update/{id}')->middleware('can:create_customers')->group(function () {
    Route::get('/', 'InvoiceController@updateComplaintForm')->name('update-complaint');
});

Route::get('/complaints/list/{customer}', 'InvoiceController@complaintList')->middleware('can:create_customers')->name('complaints-list');
Route::get('/complaint/{id}', 'InvoiceController@complaintView')->name('complaint-view');

Route::get('/complaint/made/{id}', 'InvoiceController@settleComplaintForm')->name('made-complaint');
Route::post('/complaint/made/{id}', 'InvoiceController@settleComplaintAction');

Route::get('/complaint/update/made/{id}', 'InvoiceController@updateSettleComplaintForm')->name('update-complaint');
Route::post('/complaint/update/made/{id}', 'InvoiceController@updateSettleComplaintAction');

Route::get('/customer/test/create/{customer}', 'InvoiceController@insertTestForm')->name('create-test');
Route::post('/customer/test/create/{customer}', 'InvoiceController@insertTestAction');

Route::get('/test/{id}', 'InvoiceController@testView')->name('test-view');

Route::get('/tests/list/{customer}', 'InvoiceController@testList')->middleware('can:create_customers')->name('tests-list');

Route::get('/test/made/{id}', 'InvoiceController@negativeTestForm')->name('made-test');
Route::post('/test/made/{id}', 'InvoiceController@negativeTestAction');

Route::get('/test/protocol/{id}', 'InvoiceController@testProtocolView')->name('test-protocol');

Route::get('/orders/listAllTest/', 'InvoiceController@listAllTests')->middleware('can:create_customers')->name('listAllTests');

Route::get('/invoice/settlement/list', function () {
    return view('invoices.settlement');})->name('settlement');

Route::get('/invoice/made/{id}', 'InvoiceController@madeInvoiceForm')->name('made-invoice-protocol');

Route::get('/invoice/unmade/{id}', 'InvoiceController@unmadeInvoiceForm')->name('unmade-invoice-protocol');

Route::get('/orders/listAllOrders/', 'InvoiceController@listAllOrders')->middleware('can:create_customers')->name('listAllOrders');

Route::get('/orders/listAllOrdersWarehouse/', 'InvoiceController@listAllWarehouseOrders')->middleware('can:send_packages')->name('listAllWarehouseOrders');

Route::get('/gift/create/{id}', 'InvoiceController@addGiftForm')->name('addGift');
Route::post('/gift/create/{id}', 'InvoiceController@addGiftAction');
Route::get('/gifts/listAllGifts/', 'InvoiceController@listAllGifts')->middleware('can:create_customers')->name('listAllGifts');
Route::get('/gifts/listAllGiftsWarehouse/', 'InvoiceController@listAllGiftsWarehouse')->middleware('can:send_packages')->name('listAllGiftsWarehouse');

Route::get('/gift/update/{id}', 'InvoiceController@updateGiftForm')->name('updateGift');

Route::get('/gifts/protocol/{id}', 'InvoiceController@giftProtocolView')->name('gift-protocol');
Route::get('/gift/made/{id}', 'InvoiceController@madeGiftProtocolForm')->name('made-gift-protocol');
Route::post('/gift/made/{id}', 'InvoiceController@madeGiftProtocolAction');
Route::get('/gifts/list/{customer}', 'InvoiceController@listGifts')->middleware('can:create_customers')->name('gifts-list');

Route::get('/customer/rubbish/create/{customer}', 'InvoiceController@insertRubbishForm')->name('create-rubbish');
Route::post('/customer/rubbish/create/{customer}', 'InvoiceController@insertRubbishAction');
Route::get('/rubbishes/listAllRubbishes/', 'InvoiceController@listAllRubbishes')->middleware('can:create_rubbishes')->name('listAllRubbishes');

Route::get('/rubbishes/made/{id}', 'InvoiceController@madeRubbishForm')->name('madeRubbish');
Route::get('/rubbishes/protocol/{id}', 'InvoiceController@rubbishProtocolView')->name('rubbish-protocol');
Route::get('/rubbishes/list/{customer}', 'InvoiceController@rubbishList')->middleware('can:create_customers')->name('rubbish-list');

Route::get('/invoices-incoming/search', 'InvoiceController@searchIncomingDocuments')->middleware('can:update_invoices, regulation_invoices')->name('search-incoming-documents');

Route::post('/invoices-incoming/search', 'InvoiceController@searchIncomingDocuments')->middleware('can:update_invoices, send_packages')->name('add-incoming-invoice');

Route::get('/invoices-incoming/made', 'InvoiceController@madeIncomingDocuments')->middleware('can:update_invoices, regulation_invoices')->name('made-incoming-documents');

Route::get('/invoices-incoming/add', 'InvoiceController@incomingDocuments')->name('add-incoming-documents');
Route::post('/invoices-incoming/add', 'InvoiceController@incomingDocuments');

Route::get('/customer/incoming/create', 'CustomerController@insertIncomingForm')->name('create-incoming-customer');
Route::post('/customer/incoming/create', 'CustomerController@insertIncomingAction');

Route::get('/customer/incoming/update/{id}', 'CustomerController@updateIncomingForm')->name('customer-incoming-update');
Route::put('/customer/incoming/update/{id}', 'CustomerController@updateIncomingAction');

Route::get('/hours', 'UserController@insertHours')->name('hours');
Route::post('/hours', 'UserController@insertHoursAction');

Route::get('/working-hours/list/{id}', 'UserController@hoursList')->name('hours-list');

Route::get('/all-working-hours/list/{id}', 'UserController@allHoursList')->name('all-working-hours');

Route::get('/complaints/listAllComplaints/', 'InvoiceController@listAllComplaints')->middleware('can:create_customers')->name('listAllComplaints');

Route::get('/complaints/listAllComplaintsProduction/', 'InvoiceController@listAllComplaintsProduction')->middleware('can:manage_complaints')->name('listAllComplaintsProduction');

Route::get('/order/made/{id}', 'InvoiceController@madeOrder')->name('made-order');

Route::get('/order/warehouse/{id}', 'InvoiceController@warehouse')->middleware('can:send_packages')->name('warehouse-order');

Route::get('/order/production/{id}', 'InvoiceController@production')->middleware('can:product')->name('production-order');

Route::get('/customer/acquired/{id}', 'CustomerController@resetAcquired')->name('reset-acquired');

Route::get('/invoice/incoming/made/', 'InvoiceController@invoiceIncomingMade')->name('invoice-incoming-made');

Route::get('/invoices/listWarehouse/', 'InvoiceController@listWarehouse')->middleware('can:send_packages')->name('invoices-list-warehouse');

Route::get('/order/create/waybillWarehouse/{id}', 'InvoiceController@createOrderWaybillWForm')->name('create-order-waybillW');
Route::post('/order/create/waybillWarehouse/{id}', 'InvoiceController@createOrderWaybillWAction');

Route::get('/order/create/waybill/{id}', 'InvoiceController@createOrderWaybillForm')->name('create-order-waybill');
Route::post('/order/create/waybill/{id}', 'InvoiceController@createOrderWaybillAction');

Route::get('/order/create/message/{id}', 'InvoiceController@createOrderWaybillForm')->name('create-order-message');
Route::post('/order/create/message/{id}', 'InvoiceController@createOrderWaybillAction');

Route::get('/complaint/create/messageProduction/{id}', 'InvoiceController@createComplaintMessagePForm')->name('create-complaint-messageP');
Route::post('/complaint/create/messageProduction/{id}', 'InvoiceController@createComplaintMessagePAction');

Route::get('/complaint/create/message/{id}', 'InvoiceController@createComplaintMessageForm')->name('create-complaint-message');
Route::post('/complaint/create/message/{id}', 'InvoiceController@createComplaintMessageAction');

Route::get('/orders/listAllOrdersProduction/', 'InvoiceController@listAllProductionOrders')->middleware('can:product')->name('listAllProductionOrders');

Route::get('/offer/email/{id}', 'InvoiceController@createEmailAction')->name('create-offer-email');
Route::get('/offer/email_promotion/{id}', 'InvoiceController@createEmailPromotionAction')->name('create-offer-promotion-email');

Route::get('generate-pdf/{id}', 'InvoiceController@generateInvoicePDF');
Route::get('generate-demand-pdf/{id}', 'InvoiceController@generateDemandPDF');
Route::get('generate-hours-pdf/', 'InvoiceController@generateHoursPDF')->name('create-hours-pdf');

Route::post('add-incoming-invoices/', 'InvoiceController@addIncomingInvoice')->name('add-incoming-invoices');

Route::get('/complaints/search', 'InvoiceController@searchComplaints')->middleware('can:search_complaints')->name('search-complaints');

Route::get('/order/waybill/{id}', 'InvoiceController@sendWaybill')->name('send-waybill');

Route::get('/invoices/not-paid/', 'InvoiceController@sendOldInvoices')->name('send-old-invoices');
Route::get('/invoices/xml/', 'InvoiceController@generateXml')->name('generate-xml');

Route::get('/complaints/pdf/', 'InvoiceController@generateComplaintPDF');
Route::get('/invoice/pay-reminder/{id}', 'InvoiceController@sendPayReminder')->name('send-pay-reminder');

Route::get('/products/customers/form_search', 'ProductController@searchCustomersProductForm')->middleware('can:update_invoices')->name('form-product-customer-search');

Route::get('/products/customers/search', 'ProductController@searchCustomersProduct')->middleware('can:update_invoices')->name('product-customer-search');

Route::get('/dayoff', 'UserController@dayOffListForm')->middleware('can:update_invoices')->name('users-dayoff-list');

Route::put('/dayoff', 'UserController@dayOffListAction')->middleware('can:update_invoices');
