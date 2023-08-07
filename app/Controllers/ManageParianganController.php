<?php

namespace App\Controllers;

use CodeIgniter\Files\File;

class ManageParianganController extends BaseController
{
    protected $model, $validation;
    protected $title = 'Manage-Pariangan | Tourism Village';
    protected $helpers = ['auth', 'url', 'filesystem'];
    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->model = new \App\Models\parianganModel();
    }

    public function index()
    {
        $objectData = $this->model->getPariangan();
        $galleryData = $this->model->getGallery('1')->getResult();
        $data = [
            'title' => $this->title,
            'url' => 'pariangan',
            'objectData' => $objectData,
            'galleryData' => $galleryData
        ];


        return view('admin/manage_pariangan', $data);
    }
    public function edit($id = null)
    {
        $parianganData = $this->model->editPariangan($id);
        $galleryData = $this->model->getGallery($id)->getResult();
        $data = [
            'title' => $this->title,
            'config' => config('Auth'),
            'url' => 'pariangan',
            'parianganData' => $parianganData,
            'galleryData' => $galleryData,
            'validation' =>  $this->validation
        ];
        return view('admin-edit/edit_pariangan', $data);
    }

    public function save_update($id = null)
    {

        // ---------------------Data request
        $request = $this->request->getPost();
        $updateRequest = [
            'name' => $this->request->getPost('name'),
            'type_of_tourism' => $this->request->getPost('type_of_tourism'),
            'address' => $this->request->getPost('address'),
            'open' => $this->request->getPost('open'),
            'close' => $this->request->getPost('close'),
            'status' => $this->request->getPost('status'),
            'ticket_price' => $this->request->getPost('ticket'),
            'contact_person' => $this->request->getPost('contact_person'),
            'facebook' => $this->request->getPost('facebook'),
            'tiktok' => $this->request->getPost('tiktok'),
            'instagram' => $this->request->getPost('instagram'),
            'youtube' => $this->request->getPost('youtube'),
            'description' => $this->request->getPost('description')
        ];
        $geojson = $this->request->getPost('geojson');
        if (!$geojson) {
            $geojson = 'null';
        }
        $lat = $this->request->getPost('latitude');
        $lng = $this->request->getPost('longitude');

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
            $this->model->updateGallery($id, $gallery);
        } else {
            $this->model->deleteGallery($id);
        }
        // ------------------Video----------------------
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
        // ----------------------------------UPDATE DATA--------------------------
        $update =  $this->model->updatePariangan($id, $updateRequest, floatval($lng), floatval($lat), $geojson);
        if ($update) {
            session()->setFlashdata('success', 'Success! Village updated.');
            return redirect()->to(site_url('manage_pariangan/edit/' . $id));
        } else {
            session()->setFlashdata('failed', 'Failed! Failed to update pariangan.');
            return redirect()->to(site_url('manage_pariangan/edit/' . $id));
        }
    }
    public function insert()
    {
        $data = [
            'title' => $this->title,
        ];

        return view('admin-insert/insert_pariangan', $data);
    }
}
