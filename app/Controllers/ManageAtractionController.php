<?php

namespace App\Controllers;

use CodeIgniter\Files\File;

class ManageAtractionController extends BaseController
{
    protected $model, $modelPariangan, $validation, $helpers = ['auth', 'url', 'filesystem'];
    protected $title = 'Manage-Atractions | Tourism Village';
    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->model = new \App\Models\atractionModel();
        $this->modelPariangan = new \App\Models\parianganModel();
    }
    public function index()
    {
        $objectData = $this->model->getAtractions();
        $data = [
            'title' => $this->title,
            'objectData' => $objectData
        ];
        return view('admin/manage_atraction', $data);
    }
    public function detail($id = null)
    {

        $objectData = $this->model->getAtraction($id)->getRow();

        $galleryData = $this->model->getGallery($id)->getResult();
        $parianganData = $this->modelPariangan->getPariangan();
        $data = [
            'title' => $this->title,
            'config' => config('Auth'),
            'url' => 'atraction',
            'objectData' => $objectData,
            'galleryData' => $galleryData,
            'parianganData' => $parianganData
        ];

        return view('admin-detail/detail_atraction', $data);
    }

    public function edit($id = null)
    {
        $objectData = $this->model->getAtraction($id)->getRow();
        $categoryData = $this->model->getCategory()->getResult();
        $galleryData = $this->model->getGallery($id)->getResult();
        $parianganData = $this->modelPariangan->getPariangan();
        $data = [
            'title' => $this->title,
            'config' => config('Auth'),
            'url' => 'atraction',
            'objectData' => $objectData,
            'galleryData' => $galleryData,
            'categoryData' => $categoryData,
            'parianganData' => $parianganData,
            'validation' =>  $this->validation
        ];
        return view('admin-edit/edit_atraction', $data);
    }

    public function save_update($id = null)
    {

        // ---------------------Data request-----------------------------
        $request = $this->request->getPost();
        $updateRequest = [
            'name' => $this->request->getPost('name'),
            'category_atraction_id' => $this->request->getPost('category'),
            'open' => $this->request->getPost('open'),
            'close' => $this->request->getPost('close'),
            'employe' => $this->request->getPost('employe'),
            'price' => $this->request->getPost('price'),
            'contact_person' => $this->request->getPost('contact_person'),
            'description' => $this->request->getPost('description')
        ];
        $geojson = $this->request->getPost('geojson');
        if (!$geojson) {
            $geojson = 'null';
        }
        $lat = $this->request->getPost('latitude');
        $lng = $this->request->getPost('longitude');


        // ------------------Video-----------------------------------------
        if ($request['video']) {
            $folder = $request['video'];
            $filepath = WRITEPATH . 'uploads/' . $folder;
            $filenames = get_filenames($filepath);
            $vidFile = new File($filepath . '/' . $filenames[0]);
            $vidFile->move(FCPATH . 'media/videos');
            delete_files($filepath);
            rmdir($filepath);
            $updateRequest['video_url'] = $vidFile->getFilename();
        } else {
            $updateRequest['video_url'] = null;
        }

        // unset empty value
        foreach ($updateRequest as $key => $value) {
            if (empty($value)) {
                unset($updateRequest[$key]);
            }
        }

        // ---------------------------------Update---------------------
        $update =  $this->model->updateAtraction($id, $updateRequest, floatval($lng), floatval($lat), $geojson);
        if ($update) {
            // -----------------------------Gallery-----------------------------------------
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
                $updateGallery =  $this->model->updateGallery($id, $gallery);
            } else {
                $updateGallery =   $this->model->deleteGallery($id);
            }
        }

        if ($update && $updateGallery) {
            session()->setFlashdata('success', 'Success! Atraction updated.');
            return redirect()->to(site_url('manage_atraction/edit/' . $id));
        } else {
            session()->setFlashdata('failed', 'Failed! Failed to update atraction.');
            return redirect()->to(site_url('manage_atraction/edit/' . $id));
        }
    }
    public function insert()
    {
        $parianganData = $this->modelPariangan->getPariangan();
        $categoryData = $this->model->getCategory()->getResult();
        $data = [
            'title' => $this->title,
            'categoryData' => $categoryData,
            'url' => 'atraction',
            'parianganData' => $parianganData
        ];
        return view('admin-insert/insert_atraction', $data);
    }
    public function save_insert()
    {

        // ---------------------Data request------------------------------------
        $request = $this->request->getPost();
        $id = $this->model->get_new_id();
        $insertRequest = [
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'category_id' => $this->request->getPost('category'),
            'open' => $this->request->getPost('open'),
            'close' => $this->request->getPost('close'),
            'employe' => $this->request->getPost('employe'),
            'price' => $this->request->getPost('price'),
            'contact_person' => $this->request->getPost('contact_person'),
            'description' => $this->request->getPost('description'),
        ];
        $geojson = $this->request->getPost('geojson');

        if (!$geojson) {
            $geojson = 'null';
        }
        $lat = $this->request->getPost('latitude');
        $lng = $this->request->getPost('longitude');

        // ------------------Video----------------------
        if ($request['video']) {
            $folder = $request['video'];
            $filepath = WRITEPATH . 'uploads/' . $folder;
            $filenames = get_filenames($filepath);
            $vidFile = new File($filepath . '/' . $filenames[0]);
            $vidFile->move(FCPATH . 'media/videos');
            delete_files($filepath);
            rmdir($filepath);
            $insertRequest['video_url'] = $vidFile->getFilename();
        } else {
            $insertRequest['video_url'] = null;
        }

        // unset empty value
        foreach ($insertRequest as $key => $value) {
            if (empty($value)) {
                unset($insertRequest[$key]);
            }
        }
        $insert =  $this->model->addAtraction($id, $insertRequest, floatval($lng), floatval($lat), $geojson);
        // ----------------Gallery-----------------------------------------
        if ($insert) {
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
            return redirect()->to(site_url('manage_atraction'));
        } else {
            session()->setFlashdata('failed', 'Failed! Failed to add data.');
            return redirect()->to(site_url('manage_atraction/insert'));
        }
    }
    public function delete($id)
    {
        $delete =  $this->model->deleteAtraction($id);
        if ($delete) {
            session()->setFlashdata('success', 'Success! Atraction Deleted.');
            return redirect()->to(site_url('manage_atraction'));
        } else {
            session()->setFlashdata('failed', 'Failed to delete atraction ');
            return redirect()->to(site_url('manage_atraction'));
        }
    }
}
