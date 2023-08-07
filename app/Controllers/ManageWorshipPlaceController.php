<?php

namespace App\Controllers;

use CodeIgniter\Files\File;

class ManageWorshipPlaceController extends BaseController
{
    protected $model, $modelPariangan, $validation, $helpers = ['auth', 'url', 'filesystem'];
    protected $title = 'Manage-Worship-Place| Tourism Village';
    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->model = new \App\Models\worshipPlaceModel();
        $this->modelPariangan = new \App\Models\parianganModel();
    }

    public function index()
    {
        $worshipPlaceData = $this->model->getWorshipPlaces();
        $data = [
            'title' => $this->title,
            'worshipPlaceData' => $worshipPlaceData
        ];

        return view('admin/manage_worship_place', $data);
    }
    public function detail($id = null)
    {
        $objectData = $this->model->getWorshipPlace($id)->getRow();
        $galleryData = $this->model->getGallery($id)->getResult();
        $parianganData = $this->modelPariangan->getPariangan();
        $data = [
            'title' => $this->title,
            'config' => config('Auth'),
            'url' => 'worship_place',
            'objectData' => $objectData,
            'galleryData' =>  $galleryData,
            'parianganData' => $parianganData
        ];
        return view('admin-detail/detail_worship_place', $data);
    }

    public function edit($id = null)
    {
        $objectData = $this->model->getWorshipPlace($id)->getRow();
        $galleryData = $this->model->getGallery($id)->getResult();
        $parianganData = $this->modelPariangan->getPariangan();
        $data = [
            'title' => $this->title,
            'config' => config('Auth'),
            'url' => 'worship_place',
            'objectData' => $objectData,
            'galleryData' => $galleryData,
            'parianganData' => $parianganData
        ];
        return view('admin-edit/edit_worship_place', $data);
    }

    public function save_update($id = null)
    {

        // ---------------------Data request------------------------------
        $request = $this->request->getPost();
        $updateRequest = [
            'name' => $this->request->getPost('name'),
            'category' => $this->request->getPost('category'),
            'building_size' => $this->request->getPost('building_size'),
            'capacity' => $this->request->getPost('capacity'),
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
        $update = $this->model->updateWp($id, $updateRequest, floatval($lng), floatval($lat), $geojson);
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
            session()->setFlashdata('success', 'Success! Worship place updated.');
            return redirect()->to(site_url('manage_worship_place/edit/' . $id));
        } else {
            session()->setFlashdata('failed', 'Failed! Failed to update Worship Place.');
            return redirect()->to(site_url('manage_worship_place/edit/' . $id));
        }
    }
    public function insert()
    {
        $parianganData = $this->modelPariangan->getPariangan();
        $data = [
            'title' => $this->title,
            'url' => 'worship_place',
            'parianganData' => $parianganData
        ];

        return view('admin-insert/insert_worship_place', $data);
    }
    public function save_insert()
    {
        // ---------------------Data request------------------------------
        $request = $this->request->getPost();
        $id = $this->model->get_new_id();
        $insertRequest = [
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'category' => $this->request->getPost('category'),
            'building_size' => $this->request->getPost('building_size'),
            'capacity' => $this->request->getPost('capacity'),
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
        $insert =  $this->model->addWorshipPlace($id, $insertRequest, floatval($lng), floatval($lat), $geojson);
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
            return redirect()->to(site_url('manage_worship_place'));
        } else {
            session()->setFlashdata('failed', 'Failed! Failed to update Worship Place');
            return redirect()->to(site_url('manage_worship_place/insert'));
        }
    }

    public function delete($id)
    {
        try {
            $this->model->deleteWorshipPlace($id);
        } catch (\Exception $e) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($e);
        } finally {
            session()->setFlashdata('success', 'Success! Worship Deleted.');
            return redirect()->to(site_url('manage_worship_place'));
        }
    }
}
