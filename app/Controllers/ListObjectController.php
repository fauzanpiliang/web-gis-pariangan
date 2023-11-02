<?php

namespace App\Controllers;

class ListObjectController extends BaseController
{
    protected $modelPariangan, $modelEvent, $modelAtraction, $modelSouvenir, $modelCulinary, $modelWorship, $modelFacility, $modelHomestay;
    protected $title =  'List Object | Tourism Village';
    public function __construct()
    {
        $this->modelPariangan = new \App\Models\parianganModel();
        $this->modelAtraction = new \App\Models\atractionModel();
        $this->modelEvent = new \App\Models\eventModel();
        $this->modelSouvenir = new \App\Models\souvenirPlaceModel();
        $this->modelCulinary = new \App\Models\culinaryPlaceModel();
        $this->modelWorship = new \App\Models\worshipPlaceModel();
        $this->modelFacility = new \App\Models\facilityModel();
        $this->modelHomestay = new \App\Models\homestayModel();
    }

    public function index()
    {
        //direct biasa

        $parianganData = $this->modelPariangan->getPariangan();

        $objectData = null;
        $data = [
            'title' => $this->title,
            'panelList' => 'uniqe atraction',
            'config' => config('Auth'),
            'url' => 'index',
            'objectData' => $objectData,
            'parianganData' => $parianganData
        ];

        return view('user-menu/list_object', $data);
    }

    public function atraction($id = null)
    {
        if ($id) {
            $objectData = $this->modelAtraction->getAtraction($id)->getResult();
        } else {
            $objectData = $this->modelAtraction->getAtractions();
        }
        $data = [
            'objectData' =>  $objectData,
            'url' => 'atraction'
        ];

        return json_encode($data);
    }

    public function atraction_stone()
    {
        $objectData = $this->modelAtraction->getAtractionStone();

        $data = [
            'objectData' =>  $objectData,
            'url' => 'atraction'
        ];
        return json_encode($data);
    }
    public function atraction_ordinary()
    {
        $objectData = $this->modelAtraction->getAtractionByCategory('2')->getResult();

        $data = [
            'objectData' =>  $objectData,
            'url' => 'atraction'
        ];
        return json_encode($data);
    }

    public function atraction_by_name($name = null)
    {
        if ($name) {
            $objectData = $this->modelAtraction->getAtractionByName($name)->getResult();
        }
        $data = [
            'objectData' => $objectData,
            'url' => 'atraction'
        ];
        return json_encode($data);
    }

    public function atraction_by_rate($rate = null)
    {
        if ($rate) {
            $objectData = $this->modelAtraction->getAtractionByRate($rate)->getResult();
        }
        $data = [
            'objectData' => $objectData,
            'url' => 'atraction'
        ];
        return json_encode($data);
    }
    public function atraction_by_category($category = null)
    {
        if ($category) {
            $objectData = $this->modelAtraction->getAtractionByCategory($category)->getResult();
        } else {
            $objectData = $this->modelAtraction->getCategory()->getResult();
        }
        $data = [
            'objectData' => $objectData,
            'url' => 'atraction'
        ];
        return json_encode($data);
    }

    public function event($id = null)
    {
        if ($id) {
            $objectData = $this->modelEvent->getEvent($id)->getResult();
        } else {
            $objectData = $this->modelEvent->getEvents();
        }
        $data = [
            'objectData' => $objectData,
            'url' => 'event'
        ];
        return json_encode($data);
    }

    public function event_by_name($name = null)
    {
        if ($name) {
            $objectData = $this->modelEvent->getEventByName($name)->getResult();
        }
        $data = [
            'objectData' => $objectData,
            'url' => 'event'
        ];
        return json_encode($data);
    }
    public function event_by_date($date_1 = null, $date_2 = null)
    {

        if ($date_1 && $date_2) {
            $objectData = $this->modelEvent->getEventByDate($date_1, $date_2)->getResult();
        }
        $data = [
            'objectData' => $objectData,
            'url' => 'event'
        ];
        return json_encode($data);
    }
    public function event_by_rate($rate = null)
    {
        if ($rate) {
            $objectData = $this->modelEvent->getEventByRate($rate)->getResult();
        }
        $data = [
            'objectData' => $objectData,
            'url' => 'event'
        ];
        return json_encode($data);
    }

    public function souvenir_place($id = null)
    {
        if ($id) {
            $objectData = $this->modelSouvenir->getSouvenirPlace($id)->getResult();
            $galleryData = $this->modelSouvenir->getGallery($id)->getResult();
            // $productData =  $this->modelSouvenir->getProduct($id)->getResult();
            $data['objectData'] = $objectData;
            $data['url'] = 'souvenir_place';
            $data['galleryData'] = $galleryData;
            // $data['productData'] = $productData;
        } else {
            $objectData = $this->modelSouvenir->getSouvenirPlaces();
            $data = [
                'objectData' => $objectData,
                'url' => 'souvenir_place'
            ];
        }
        return json_encode($data);
    }
    public function culinary_place($id = null)
    {
        if ($id) {
            $objectData = $this->modelCulinary->getCulinaryPlace($id)->getResult();
            $galleryData = $this->modelCulinary->getGallery($id)->getResult();
            // $menuData =  $this->modelCulinary->getMenu($id)->getResult();
            $data['objectData'] = $objectData;
            $data['url'] = 'culinary_place';
            $data['galleryData'] = $galleryData;
            // $data['menuData'] = $menuData;
        } else {
            $objectData = $this->modelCulinary->getCulinaryPlaces();
            $data = [
                'objectData' => $objectData,
                'url' => 'culinary_place'
            ];
        }
        return json_encode($data);
    }
    public function worship_place($id = null)
    {
        if ($id) {
            $objectData = $this->modelWorship->getWorshipPlace($id)->getResult();
            $galleryData = $this->modelWorship->getGallery($id)->getResult();
            $data['objectData'] = $objectData;
            $data['url'] = 'worship_place';
            $data['galleryData'] = $galleryData;
        } else {
            $objectData = $this->modelWorship->getWorshipPlaces();
            $data = [
                'objectData' => $objectData,
                'url' => 'worship_place'
            ];
        }
        return json_encode($data);
    }
    public function facility($id = null)
    {
        if ($id) {
            $objectData = $this->modelFacility->getFacility($id)->getResult();
            $galleryData = $this->modelFacility->getGallery($id)->getResult();
            $data['objectData'] = $objectData;
            $data['url'] = 'facility';
            $data['galleryData'] = $galleryData;
        } else {
            $objectData = $this->modelFacility->getFacilities();
            $data = [
                'objectData' => $objectData,
                'url' => 'facility'
            ];
        }
        return json_encode($data);
    }
    public function homestay($id = null)
    {
        if ($id) {
            $objectData = $this->modelHomestay->getHomestay($id)->getResult();
            $galleryData = $this->modelHomestay->getGallery($id)->getResult();
            $data['objectData'] = $objectData;
            $data['url'] = 'homestay';
            $data['galleryData'] = $galleryData;
        } else {
            $objectData = $this->modelHomestay->getHomestays();
            $data = [
                'objectData' => $objectData,
                'url' => 'homestay'
            ];
        }
        return json_encode($data);
    }
    public function package($id = null)
    {
        if ($id) {
            $objectData = $this->modelFacility->getFacility($id)->getResult();
            $galleryData = $this->modelFacility->getGallery($id)->getResult();
            $data['objectData'] = $objectData;
            $data['url'] = 'package';
            $data['galleryData'] = $galleryData;
        } else {
            $objectData = $this->modelFacility->getFacilities();
            $data = [
                'objectData' => $objectData,
                'url' => 'package'
            ];
        }
        return json_encode($data);
    }

    public function search_main_nearby($distance = null)
    {
        $main = $_GET['main'];
        $lat = $_GET['lat'];
        $lng = $_GET['lng'];

        if ($main == 'atraction') {
            $data['atData']  = $this->modelAtraction->getRadiusValue($lng, $lat, $distance)->getResult();
            $data['atUrl'] = 'atraction';
        } else if ($main == 'event') {
            $data['evData']  = $this->modelEvent->getRadiusValue($lng, $lat, $distance)->getResult();
            $data['evUrl'] = 'event';
        }

        return json_encode($data);
    }
    public function search_support_nearby($distance = null)
    {
        $cp = $_GET['cp'];
        $wp = $_GET['wp'];
        $sp = $_GET['sp'];
        $f =  $_GET['f'];
        $h =  $_GET['h'];

        $lat = $_GET['lat'];
        $lng = $_GET['lng'];

        if ($cp == 'true') {
            $data['cpData']  = $this->modelCulinary->getRadiusValue($lng, $lat, $distance)->getResult();
            $data['cpUrl'] = 'culinary_place';
        }
        if ($sp == 'true') {
            $data['spData']  = $this->modelSouvenir->getRadiusValue($lng, $lat, $distance)->getResult();
            $data['spUrl'] = 'souvenir_place';
        }
        if ($wp == 'true') {
            $data['wpData']  = $this->modelWorship->getRadiusValue($lng, $lat, $distance)->getResult();
            $data['wpUrl'] = 'worship_place';
        }
        if ($f == 'true') {
            $data['fData']  = $this->modelFacility->getRadiusValue($lng, $lat, $distance)->getResult();
            $data['fUrl'] = 'facility';
        }
        if ($h == 'true') {
            $data['hData']  = $this->modelHomestay->getRadiusValue($lng, $lat, $distance)->getResult();
            $data['hUrl'] = 'homestay';
        }
        if ($data) {
            return json_encode($data);
        } else {
            return json_encode('salah');
        }
    }
}
