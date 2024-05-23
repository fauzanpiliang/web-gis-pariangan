<?php

namespace App\Controllers;

use CodeIgniter\Files\File;

use function PHPSTORM_META\map;

class ManageFacilityController extends BaseController
{
    protected $model, $modelPariangan, $validation, $helpers = ['auth', 'url', 'filesystem'];
    protected $title = 'Manage-Facility | Tourism Village';
    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->model = new \App\Models\facilityModel();
        $this->modelPariangan = new \App\Models\parianganModel();
    }

    public function index()
    {
        $facilityData = $this->model->getFacilities();

        $data = [
            'title' => $this->title,
            'facilityData' => $facilityData
        ];

        return view('admin/manage_facility', $data);
    }
    public function detail($id = null)
    {
        $objectData = $this->model->getfacility($id)->getRow();
        $galleryData = $this->model->getGallery($id)->getResult();
        $parianganData = $this->modelPariangan->getPariangan();
        $data = [
            'title' => $this->title,
            'config' => config('Auth'),
            'url' => 'facility',
            'objectData' => $objectData,
            'galleryData' => $galleryData,
            'parianganData' => $parianganData
        ];
        return view('admin-detail/detail_facility', $data);
    }

    public function edit($id = null)
    {
        $objectData = $this->model->getFacility($id)->getRow();
        $galleryData = $this->model->getGallery($id)->getResult();
        $parianganData = $this->modelPariangan->getPariangan();
        $data = [
            'title' => $this->title,
            'config' => config('Auth'),
            'url' => 'facility',
            'objectData' => $objectData,
            'galleryData' => $galleryData,
            'parianganData' => $parianganData
        ];
        return view('admin-edit/edit_facility', $data);
    }

    public function save_update($id = null)
    {

        // ---------------------Data request------------------------------
        $request = $this->request->getPost();
        $updateRequest = [
            'name' => $this->request->getPost('name'),
            'area_size' => $this->request->getPost('area_size'),
            'open' => $this->request->getPost('open'),
            'close' => $this->request->getPost('close'),
            'description' => $this->request->getPost('description')
        ];
        $geojson = $this->request->getPost('geojson');
        if (!$geojson) {
            $geojson = 'null';
        }
        $lat = $this->request->getPost('latitude');
        $lng = $this->request->getPost('longitude');

        // unset empty value
        foreach ($updateRequest as $key => $value) {
            if (empty($value)) {
                unset($updateRequest[$key]);
            }
        }
        // ----------------------------------UPDATE DATA--------------------------
        $update = $this->model->updateF($id, $updateRequest, floatval($lng), floatval($lat), $geojson);
        if ($update) {
            // --------------------------------Gallery------------------------------
            // check if gallery have empty string then make it become empty array
            foreach ($request['gallery'] as $key => $value) {
                if (!strlen($value)) {
                    unset($request['gallery'][$key]);
                }
            }
            if ($request['gallery']) {
                $folders = $request['gallery'];
                $gallery = array();
                foreach ($folders as $folder) {
                    $filepath = WRITEPATH . 'uploads/' . $folder;
                    $filenames = get_filenames($filepath);
                    $fileImg = new File($filepath . '/' . $filenames[0]);
                    $fileImg->move(FCPATH . 'media/photos');
                    delete_files($filepath);
                    rmdir($filepath);
                    $gallery[] = $fileImg->getFilename();
                }
                $updateGallery = $this->model->updateGallery($id, $gallery);
            } else {
                $updateGallery = $this->model->deleteGallery($id);
            }
        }
        if ($update && $updateGallery) {
            session()->setFlashdata('success', 'Success! Facility updated.');
            return redirect()->to(site_url('manage_facility/edit/' . $id));
        } else {
            session()->setFlashdata('failed', 'Failed! Failed to update Facility');
            return redirect()->to(site_url('manage_facility/edit/' . $id));
        }
    }
    public function insert()
    {
        $parianganData = $this->modelPariangan->getPariangan();
        $data = [
            'title' => $this->title,
            'url' => 'facility',
            'parianganData' => $parianganData
        ];
        return view('admin-insert/insert_facility', $data);
    }
    public function save_insert()
    {

        // ---------------------Data request------------------------------
        $request = $this->request->getPost();
        $id = $this->model->get_new_id();
        $insertRequest = [
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'area_size' => $this->request->getPost('area_size'),
            'open' => $this->request->getPost('open'),
            'close' => $this->request->getPost('close'),
            'description' => $this->request->getPost('description')
        ];
        $geojson = $this->request->getPost('geojson');
        if (!$geojson) {
            $geojson = 'null';
        }
        $lat = $this->request->getPost('latitude');
        $lng = $this->request->getPost('longitude');

        // unset empty value
        foreach ($insertRequest as $key => $value) {
            if (empty($value)) {
                unset($insertRequest[$key]);
            }
        }
        $insert =  $this->model->addFacility($id, $insertRequest, floatval($lng), floatval($lat), $geojson);
        if ($insert) {
            // ----------------Gallery-----------------------------------------
            // check if gallery have empty string then make it become empty array
            foreach ($request['gallery'] as $key => $value) {
                if (!strlen($value)) {
                    unset($request['gallery'][$key]);
                }
            }
            if ($request['gallery']) {
                $folders = $request['gallery'];
                $gallery = array();
                foreach ($folders as $folder) {
                    $filepath = WRITEPATH . 'uploads/' . $folder;
                    $filenames = get_filenames($filepath);
                    $fileImg = new File($filepath . '/' . $filenames[0]);
                    $fileImg->move(FCPATH . 'media/photos');
                    delete_files($filepath);
                    rmdir($filepath);
                    $gallery[] = $fileImg->getFilename();
                }
                $insertGallery =  $this->model->addGallery($id, $gallery);
            } else {
                $insertGallery = true;
            }
        }
        if ($insert && $insertGallery) {
            session()->setFlashdata('success', 'Success! Data Added.');
            return redirect()->to(site_url('manage_facility'));
        } else {
            session()->setFlashdata('failed', 'Failed! Failed to add data.');
            return redirect()->to(site_url('manage_facility/insert'));
        }
    }

    public function delete($id)
    {
        $delete =  $this->model->deleteFacility($id);
        if ($delete) {
            session()->setFlashdata('success', 'Success! Facility Deleted.');
            return redirect()->to(site_url('manage_facility'));
        } else {
            session()->setFlashdata('failed', 'Failed to delete facility.');
            return redirect()->to(site_url('manage_facility'));
        }
    }
}
