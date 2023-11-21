<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

//  check db

$routes->get('base_controller', 'BaseController::dbCheck');
// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get('/', 'Pages::index');
$routes->get('/index', 'Pages::index');
$routes->get('/landing_page', 'Pages::landing_page');
$routes->get('/about', 'Pages::about');
$routes->get('/admin', 'Admin::users', ['filter' => 'role:admin']);

// upload file 
$routes->group('upload', function ($routes) {
    $routes->post('photo', 'UploadController::photo', ['filter' => 'role:user,admin']);
    $routes->post('avatar', 'UploadController::avatar', ['filter' => 'role:user,admin']);
    $routes->post('video', 'UploadController::video', ['filter' => 'role:user,admin']);
    $routes->delete('photo', 'UploadController::remove', ['filter' => 'role:user,admin']);
    $routes->delete('avatar', 'UploadController::remove', ['filter' => 'role:user,admin']);
    $routes->delete('video', 'UploadController::remove', ['filter' => 'role:user,admin']);
});

// Routes untuk authentikasi
$routes->get('/login', 'AuthController::login');
$routes->get('/register', 'AuthController::register');


// Route user menu
// Route untuk manage user profile
$routes->group('user', function ($routes) {
    $routes->get('getUser/(:segment)', 'User::getUser/$1', ['filter' => 'role:user,admin']);
    $routes->get('profile', 'User::profile', ['filter' => 'role:user,admin']);
    $routes->get('edit_profile', 'User::edit_profile', ['filter' => 'role:user,admin']);
    $routes->get('reservation/(:segment)', 'User::reservation/$1', ['filter' => 'role:user']);
    $routes->get('checkout/(:segment)', 'User::checkout/$1', ['filter' => 'role:user']);
    $routes->get('save_update/(:segment)', 'User::save_update/$1', ['filter' => 'role:user,admin']);
    $routes->post('save_update/(:segment)', 'User::save_update/$1', ['filter' => 'role:user,admin']);
    $routes->get('change_password', 'User::change_password', ['filter' => 'role:user,admin']);
    $routes->get('save_password/(:segment)', 'User::save_password/$1', ['filter' => 'role:user,admin']);
    $routes->post('save_password/(:segment)', 'User::save_password/$1', ['filter' => 'role:user,admin']);
});

// Menu list Object
$routes->group('list_object', function ($routes) {
    $routes->get('index', 'ListObjectController::index');
    $routes->get('/', 'ListObjectController::index');
    $routes->get('atraction', 'ListObjectController::atraction');
    $routes->get('atraction/(:segment)', 'ListObjectController::atraction/$1');
    $routes->get('atraction_by_name/(:segment)', 'ListObjectController::atraction_by_name/$1');
    $routes->get('atraction_by_rate/(:segment)', 'ListObjectController::atraction_by_rate/$1');
    $routes->get('atraction_by_category/(:segment)', 'ListObjectController::atraction_by_category/$1');
    $routes->get('atraction_by_category', 'ListObjectController::atraction_by_category');
    $routes->get('atraction_stone', 'ListObjectController::atraction_stone');
    $routes->get('atraction_ordinary', 'ListObjectController::atraction_ordinary');


    $routes->get('event', 'ListObjectController::event');
    $routes->get('event/(:segment)', 'ListObjectController::event/$1');
    $routes->get('event_by_name/(:segment)', 'ListObjectController::event_by_name/$1');
    $routes->get('event_by_date/(:segment)/(:segment)', 'ListObjectController::event_by_date/$1/$2');
    $routes->get('event_by_rate/(:segment)', 'ListObjectController::event_by_rate/$1');

    $routes->get('souvenir_place', 'ListObjectController::souvenir_place');
    $routes->get('souvenir_place/(:segment)', 'ListObjectController::souvenir_place/$1');

    $routes->get('culinary_place', 'ListObjectController::culinary_place');
    $routes->get('culinary_place/(:segment)', 'ListObjectController::culinary_place/$1');

    $routes->get('worship_place', 'ListObjectController::worship_place');
    $routes->get('worship_place/(:segment)', 'ListObjectController::worship_place/$1');

    $routes->get('facility', 'ListObjectController::facility');
    $routes->get('facility/(:segment)', 'ListObjectController::facility/$1');

    $routes->get('homestay', 'ListObjectController::homestay');
    $routes->get('homestay/(:segment)', 'ListObjectController::homestay/$1');

    $routes->get('detail_object/(:segment)', 'ListObjectController::detail_object/$1');

    $routes->get('search_main_nearby/(:segment)', 'ListObjectController::search_main_nearby/$1');
    $routes->get('search_support_nearby/(:segment)', 'ListObjectController::search_support_nearby/$1');
});


// Menu detail object
$routes->group('detail_object', function ($routes) {
    $routes->get('atraction/(:segment)', 'DetailObjectController::atraction/$1');
    $routes->get('event/(:segment)', 'DetailObjectController::event/$1');

    $routes->get('souvenir_place/(:segment)', 'DetailObjectController::souvenir_place/$1');

    $routes->get('culinary_place/(:segment)', 'DetailObjectController::culinary_place/$1');

    $routes->get('worship_place/(:segment)', 'DetailObjectController::worship_place/$1');

    $routes->get('facility/(:segment)', 'DetailObjectController::facility/$1');
});

// Menu detail object
$routes->group('review', function ($routes) {
    $routes->post('atraction', 'RatingReviewController::rating_atraction');
    $routes->post('comment_atraction', 'RatingReviewController::comment_atraction');
    $routes->get('get_atraction_comment', 'RatingReviewController::get_atraction_comment');
    $routes->post('event', 'RatingReviewController::rating_event');
    $routes->post('comment_event', 'RatingReviewController::comment_event');
    $routes->get('get_event_comment', 'RatingReviewController::get_event_comment');
    $routes->post('package', 'RatingReviewController::rating_comment_package');
});

// Menu package
$routes->group('package', function ($routes) {
    $routes->get('/', 'packageController::packages');
    $routes->get('costum/new', 'packageController::newCostume');
    $routes->get('package_api/(:segment)', 'packageController::package_api/$1');
    $routes->post('saveCostume', 'packageController::saveCostume', ['filter' => 'role:user']);
    $routes->get('detail/(:segment)', 'packageController::package/$1');
    $routes->get('costumExisting/(:segment)', 'packageController::costumExisting/$1');
    $routes->post('saveCostumExisting', 'packageController::saveCostumExisting');
    $routes->get('getActivityGallery/(:segment)', 'packageController::getActivityGallery/$1');
    $routes->get('objects/package_day/(:segment)', 'packageController::getObjectsByPackageDayId/$1');
});

// Menu product
$routes->group('product', function ($routes) {
    $routes->get('culinary', 'productController::culinary');
    $routes->get('souvenir', 'productController::souvenir');
});


// Route Admin menu

//  0. Dashboard
$routes->get('dashboard', 'Pages::dashboard', ['filter' => 'role:admin']);

//  1. Route Users
$routes->group('manage_users', function ($routes) {
    $routes->get('/', 'ManageUsersController::index', ['filter' => 'role:admin']);
    $routes->get('index', 'ManageUsersController::index', ['filter' => 'role:admin']);
    $routes->get('admin', 'ManageUsersController::admin', ['filter' => 'role:admin']);
    $routes->get('detail/(:num)', 'ManageUsersController::detail/$1',  ['filter' => 'role:admin']);
    $routes->get('edit/(:num)', 'ManageUsersController::edit/$1',  ['filter' => 'role:admin']);
    $routes->post('save_update/(:segment)', 'ManageUsersController::save_update/$1', ['filter' => 'role:user,admin']);
    $routes->get('insert', 'ManageUsersController::insert', ['filter' => 'role:admin']);
    $routes->post('save_insert', 'ManageUsersController::save_insert', ['filter' => 'role:admin']);
    $routes->get('delete/(:segment)', 'ManageUsersController::delete/$1', ['filter' => 'role:admin']);
});


//  2. Route manage atraction

$routes->group('manage_atraction', function ($routes) {
    $routes->get('/', 'ManageAtractionController::index', ['filter' => 'role:admin']);
    $routes->get('index', 'ManageAtractionController::index', ['filter' => 'role:admin']);
    $routes->get('detail/(:segment)', 'ManageAtractionController::detail/$1', ['filter' => 'role:admin']);
    $routes->get('edit/(:segment)', 'ManageAtractionController::edit/$1', ['filter' => 'role:admin']);
    $routes->post('save_update/(:segment)', 'ManageAtractionController::save_update/$1', ['filter' => 'role:admin']);
    $routes->get('insert', 'ManageAtractionController::insert', ['filter' => 'role:admin']);
    $routes->post('save_insert', 'ManageAtractionController::save_insert', ['filter' => 'role:admin']);
    $routes->get('delete/(:segment)', 'ManageAtractionController::delete/$1', ['filter' => 'role:admin']);
});


//  3. Route manage pariangan
$routes->group('manage_pariangan', function ($routes) {
    $routes->get('/', 'ManageParianganController::index', ['filter' => 'role:admin']);
    $routes->get('index', 'ManageParianganController::index', ['filter' => 'role:admin']);
    $routes->get('edit/(:segment)', 'ManageParianganController::edit/$1', ['filter' => 'role:admin']);
    $routes->post('save_update/(:segment)', 'ManageParianganController::save_update/$1', ['filter' => 'role:admin']);
    $routes->get('insert', 'ManageParianganController::insert', ['filter' => 'role:admin']);
    $routes->post('save_insert', 'ManageParianganController::save_insert', ['filter' => 'role:admin']);
});

// 4. Route manage event
$routes->group('manage_event', function ($routes) {
    $routes->get('/', 'ManageEventController::index', ['filter' => 'role:admin']);
    $routes->get('index', 'ManageEventController::index', ['filter' => 'role:admin']);
    $routes->get('detail/(:segment)', 'ManageEventController::detail/$1', ['filter' => 'role:admin']);
    $routes->get('edit/(:segment)', 'ManageEventController::edit/$1', ['filter' => 'role:admin']);
    $routes->post('save_update/(:segment)', 'ManageEventController::save_update/$1', ['filter' => 'role:admin']);
    $routes->get('insert', 'ManageEventController::insert', ['filter' => 'role:admin']);
    $routes->post('save_insert', 'ManageEventController::save_insert', ['filter' => 'role:admin']);
    $routes->get('delete/(:segment)', 'ManageEventController::delete/$1', ['filter' => 'role:admin']);
});


// 5. Route manage souvenir place
$routes->group('manage_souvenir_place', function ($routes) {
    $routes->get('/', 'ManageSouvenirPlaceController::index', ['filter' => 'role:admin']);
    $routes->get('index', 'ManageSouvenirPlaceController::index', ['filter' => 'role:admin']);
    $routes->get('detail/(:segment)', 'ManageSouvenirPlaceController::detail/$1', ['filter' => 'role:admin']);
    $routes->get('edit/(:segment)', 'ManageSouvenirPlaceController::edit/$1', ['filter' => 'role:admin']);
    $routes->post('save_update/(:segment)', 'ManageSouvenirPlaceController::save_update/$1', ['filter' => 'role:admin']);
    $routes->get('insert', 'ManageSouvenirPlaceController::insert', ['filter' => 'role:admin']);
    $routes->post('save_insert', 'ManageSouvenirPlaceController::save_insert', ['filter' => 'role:admin']);
    $routes->get('delete/(:segment)', 'ManageSouvenirPlaceController::delete/$1', ['filter' => 'role:admin']);
});



// 6. Route manage culinary place
$routes->group('manage_culinary_place', function ($routes) {
    $routes->get('/', 'ManageCulinaryPlaceController::index', ['filter' => 'role:admin']);
    $routes->get('index', 'ManageCulinaryPlaceController::index', ['filter' => 'role:admin']);
    $routes->get('detail/(:segment)', 'ManageCulinaryPlaceController::detail/$1', ['filter' => 'role:admin']);
    $routes->get('edit/(:segment)', 'ManageCulinaryPlaceController::edit/$1', ['filter' => 'role:admin']);
    $routes->post('save_update/(:segment)', 'ManageCulinaryPlaceController::save_update/$1', ['filter' => 'role:admin']);
    $routes->get('insert', 'ManageCulinaryPlaceController::insert', ['filter' => 'role:admin']);
    $routes->post('save_insert', 'ManageCulinaryPlaceController::save_insert', ['filter' => 'role:admin']);
    $routes->get('delete/(:segment)', 'ManageCulinaryPlaceController::delete/$1', ['filter' => 'role:admin']);
});


// 7. Route manage worship place
$routes->group('manage_worship_place', function ($routes) {
    $routes->get('/', 'ManageWorshipPlaceController::index', ['filter' => 'role:admin']);
    $routes->get('index', 'ManageWorshipPlaceController::index', ['filter' => 'role:admin']);
    $routes->get('detail/(:segment)', 'ManageWorshipPlaceController::detail/$1', ['filter' => 'role:admin']);
    $routes->get('edit/(:segment)', 'ManageWorshipPlaceController::edit/$1', ['filter' => 'role:admin']);
    $routes->post('save_update/(:segment)', 'ManageWorshipPlaceController::save_update/$1', ['filter' => 'role:admin']);
    $routes->get('insert', 'ManageWorshipPlaceController::insert', ['filter' => 'role:admin']);
    $routes->post('save_insert', 'ManageWorshipPlaceController::save_insert', ['filter' => 'role:admin']);
    $routes->get('delete/(:segment)', 'ManageWorshipPlaceController::delete/$1', ['filter' => 'role:admin']);
});


// 8. Route manage facility
$routes->group('manage_facility', function ($routes) {
    $routes->get('/', 'ManageFacilityController::index', ['filter' => 'role:admin']);
    $routes->get('index', 'ManageFacilityController::index', ['filter' => 'role:admin']);
    $routes->get('detail/(:segment)', 'ManageFacilityController::detail/$1', ['filter' => 'role:admin']);
    $routes->get('edit/(:segment)', 'ManageFacilityController::edit/$1', ['filter' => 'role:admin']);
    $routes->post('save_update/(:segment)', 'ManageFacilityController::save_update/$1', ['filter' => 'role:admin']);
    $routes->get('insert', 'ManageFacilityController::insert', ['filter' => 'role:admin']);
    $routes->post('save_insert', 'ManageFacilityController::save_insert', ['filter' => 'role:admin']);
    $routes->get('delete/(:segment)', 'ManageFacilityController::delete/$1', ['filter' => 'role:admin']);
});

// 9. Route manage facility
$routes->group('manage_homestay', function ($routes) {
    $routes->get('/', 'ManageHomestayController::index', ['filter' => 'role:admin']);
    $routes->get('index', 'ManageHomestayController::index', ['filter' => 'role:admin']);
    $routes->get('detail/(:segment)', 'ManageHomestayController::detail/$1', ['filter' => 'role:admin']);
    $routes->get('edit/(:segment)', 'ManageHomestayController::edit/$1', ['filter' => 'role:admin']);
    $routes->post('save_update/(:segment)', 'ManageHomestayController::save_update/$1', ['filter' => 'role:admin']);
    $routes->get('insert', 'ManageHomestayController::insert', ['filter' => 'role:admin']);
    $routes->post('save_insert', 'ManageHomestayController::save_insert', ['filter' => 'role:admin']);
    $routes->get('delete/(:segment)', 'ManageHomestayController::delete/$1', ['filter' => 'role:admin']);
});

// 10. Route manage package
$routes->group('manage_package', function ($routes) {
    $routes->get('/', 'ManagePackageController::index', ['filter' => 'role:admin']);
    $routes->get('index', 'ManagePackageController::index', ['filter' => 'role:admin']);
    $routes->get('detail/(:segment)', 'ManagePackageController::detail/$1', ['filter' => 'role:admin']);
    $routes->get('edit/(:segment)', 'ManagePackageController::edit/$1', ['filter' => 'role:admin']);
    $routes->post('save_update/(:segment)', 'ManagePackageController::save_update/$1', ['filter' => 'role:admin']);
    $routes->get('insert', 'ManagePackageController::insert', ['filter' => 'role:admin']);
    $routes->post('save_insert', 'ManagePackageController::save_insert', ['filter' => 'role:admin']);
    $routes->get('delete/(:segment)', 'ManagePackageController::delete/$1', ['filter' => 'role:admin']);
});

// 11. Route manage product
$routes->group('manage_product', function ($routes) {
    $routes->get('/', 'ManageProductController::index', ['filter' => 'role:admin']);
    $routes->get('index', 'ManageProductController::index', ['filter' => 'role:admin']);
    $routes->get('detail/(:segment)', 'ManageProductController::detail/$1', ['filter' => 'role:admin']);
    $routes->get('edit/(:segment)', 'ManageProductController::edit/$1', ['filter' => 'role:admin']);
    $routes->post('save_update/(:segment)', 'ManageProductController::save_update/$1', ['filter' => 'role:admin']);
    $routes->get('insert', 'ManageProductController::insert', ['filter' => 'role:admin']);
    $routes->post('save_insert', 'ManageProductController::save_insert', ['filter' => 'role:admin']);
    $routes->get('delete/(:segment)', 'ManageProductController::delete/$1', ['filter' => 'role:admin']);
});
// 12. Route service package
$routes->group('manage_service', function ($routes) {
    $routes->get('/', 'ManageServiceController::index', ['filter' => 'role:admin']);
    $routes->get('index', 'ManageServiceController::index', ['filter' => 'role:admin']);
    $routes->post('save_update/(:segment)', 'ManageServiceController::save_update/$1', ['filter' => 'role:admin']);
    $routes->get('insert', 'ManageServiceController::insert', ['filter' => 'role:admin']);
    $routes->post('save_insert', 'ManageServiceController::save_insert', ['filter' => 'role:admin']);
    $routes->get('delete/(:segment)', 'ManageServiceController::delete/$1', ['filter' => 'role:admin']);
});

// 13. menu  reservation
$routes->group('reservation', function ($routes) {
    $routes->get('show/(:segment)/(:segment)/(:segment)', 'ReservationController::show/$1/$2/$3', ['filter' => 'role:user,admin']);
    $routes->post('create', 'ReservationController::create', ['filter' => 'role:user,admin']);
    $routes->put('update/(:segment)/(:segment)/(:segment)', 'ReservationController::update/$1/$2/$3', ['filter' => 'role:user,admin']);
    $routes->delete('delete/(:segment)/(:segment)/(:segment)', 'ReservationController::delete/$1/$2/$3', ['filter' => 'role:user,admin']);
    $routes->get('check/(:segment)/(:segment)/(:segment)', 'ReservationController::check/$1/$2/$3', ['filter' => 'role:user,admin']);
});

// 14. Manage reservation
$routes->group('manage_reservation', function ($routes) {
    $routes->get('/', 'ManageReservationController::index', ['filter' => 'role:admin']);
    $routes->get('index', 'ManageReservationController::index', ['filter' => 'role:admin']);
    $routes->patch('save_update/(:segment)/(:segment)/(:segment)', 'ManageReservationController::save_update/$1/$2/$3', ['filter' => 'role:admin']);
});

// 14. Manage reservation
$routes->group('pdf', function ($routes) {
    $routes->get('/(:segment)', 'PdfGenerator::index/$1', ['filter' => 'role:user,admin']);
    $routes->post('invoice-data', 'PdfGenerator::getInvoiceData', ['filter' => 'role:user,admin']);
    $routes->get('invoice/(:segment)', 'PdfGenerator::invoice/$1', ['filter' => 'role:user,admin']);
    $routes->post('ticket-data', 'PdfGenerator::getTicketData', ['filter' => 'role:user,admin']);
    $routes->get('ticket/(:segment)', 'PdfGenerator::ticket/$1', ['filter' => 'role:user,admin']);
});

// Mobile route
$routes->group('mobile', function ($routes) {
    $routes->post('login', 'MobileController::login');
    $routes->get('logout', 'MobileController::logout');

    $routes->post('profile', 'MobileController::profile');

    $routes->get('index', 'MobileController::index');
    $routes->get('/', 'MobileController::index');
    $routes->get('atraction', 'MobileController::atraction');
    $routes->get('atraction/(:segment)', 'MobileController::atraction/$1');
    $routes->get('atraction_by_name/(:segment)', 'MobileController::atraction_by_name/$1');
    $routes->get('atraction_by_rate/(:segment)', 'MobileController::atraction_by_rate/$1');
    $routes->get('atraction_by_category/(:segment)', 'MobileController::atraction_by_category/$1');
    $routes->get('atraction_by_category', 'MobileController::atraction_by_category');
    $routes->get('detail_atraction/(:segment)', 'MobileController::detail_atraction/$1');

    $routes->get('package', 'MobileController::package');
    $routes->get('package/(:segment)', 'MobileController::package/$1');
    $routes->get('detail_package/(:segment)', 'MobileController::detail_package/$1');

    $routes->get('products', 'MobileController::products');


    $routes->get('event', 'MobileController::event');
    $routes->get('event/(:segment)', 'MobileController::event/$1');
    $routes->get('event_by_name/(:segment)', 'MobileController::event_by_name/$1');
    $routes->get('event_by_date/(:segment)/(:segment)', 'MobileController::event_by_date/$1/$2');
    $routes->get('event_by_rate/(:segment)', 'MobileController::event_by_rate/$1');
    $routes->get('detail_event/(:segment)', 'MobileController::detail_event/$1');


    $routes->get('souvenir_place', 'MobileController::souvenir_place');
    $routes->get('souvenir_place/(:segment)', 'MobileController::souvenir_place/$1');

    $routes->get('culinary_place', 'MobileController::culinary_place');
    $routes->get('culinary_place/(:segment)', 'MobileController::culinary_place/$1');

    $routes->get('worship_place', 'MobileController::worship_place');
    $routes->get('worship_place/(:segment)', 'MobileController::worship_place/$1');

    $routes->get('facility', 'MobileController::facility');
    $routes->get('facility/(:segment)', 'MobileController::facility/$1');

    $routes->get('detail_object/(:segment)', 'MobileController::detail_object/$1');

    $routes->get('search_main_nearby/(:segment)', 'MobileController::search_main_nearby/$1');
    $routes->get('search_support_nearby/(:segment)', 'MobileController::search_support_nearby/$1');
});


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
