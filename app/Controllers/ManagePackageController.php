<?php

namespace App\Controllers;

use CodeIgniter\Files\File;

class ManagePackageController extends BaseController
{
    protected $model, $modelservices, $modelPariangan,  $modelFp, $validation, $helpers = ['auth', 'url', 'filesystem'];
    protected $title = 'Manage-Packages | Tourism Village';
    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->model = new \App\Models\packageModel();
        $this->modelPariangan = new \App\Models\parianganModel();
        $this->modelservices = new \App\Models\serviceModel();
        $this->modelFp = new \App\Models\facilityPackageModel();
    }
    public function index()
    {
        $objectData = $this->model->getPackages();
        $data = [
            'title' => $this->title,
            'objectData' => $objectData
        ];
        return view('admin/manage_package', $data);
    }
    public function detail($id = null)
    {
        $objectData = $this->model->getPackage($id)->getRow();
        $parianganData = $this->modelPariangan->getPariangan();
        $facilityPackage = $this->modelFp->getFacilityPackage($id)->getResult();
        $servicesData = $this->modelservices->getPackageActivity($id)->getResult();
        $data = [
            'title' => $this->title,
            'objectData' => $objectData,
            'parianganData' => $parianganData,
            'facilityPackage' => $facilityPackage,
            'servicesData' => $servicesData

        ];
        return view('admin-detail/detail_package', $data);
    }

    public function edit($id = null)
    {
        $objectData = $this->model->getPackage($id)->getRow();
        $servicesData  = $this->modelservices->getservices()->getResult();
        $servicesPackage = $this->modelservices->getPackageActivity($id)->getResult();
        $facilityPackages = $this->modelFp->getFacilityPackage($id)->getResult();
        $data = [
            'title' => $this->title,
            'config' => config('Auth'),
            'objectData' => $objectData,
            'servicesData' => $servicesData,
            'servicesPackage' => $servicesPackage,
            'facilityPackages' => $facilityPackages
        ];
        return view('admin-edit/edit_package', $data);
    }

    public function save_update($id = null)
    {
        // ---------------------Data request------------------------------------
        $request = $this->request->getPost();

        foreach ($request['gallery'] as $key => $value) {
            if (!strlen($value)) {
                unset($request['gallery'][$key]);
            }
        }
        if ($request['gallery']) {
            $folders = $request['gallery'];
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/package');
                delete_files($filepath);
                rmdir($filepath);
                $gallery = $fileImg->getFilename();
            }
        } else {
            $gallery = '';
        }
        $updateRequest = [
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'min_capacity' => $this->request->getPost('min_capacity'),
            'contact_person' => $this->request->getPost('contact_person'),
            'brosur_url' => $gallery,
            'description' => $this->request->getPost('description')
        ];

        // unset empty value
        foreach ($updateRequest as $key => $value) {
            if (empty($value)) {
                unset($updateRequest[$key]);
            }
        }
        $insert =  $this->model->updatePackage($id, $updateRequest);
        if ($insert) {
            // insert services
            $deletePackage = $this->modelservices->deleteDetailPackage($id);
            if ($deletePackage) {
                $services_id = $this->request->getPost('services');
                if ($services_id) {
                    foreach ($services_id as $activity_id) {
                        $this->modelservices->updatePackageservices($id, $activity_id);
                    }
                }
            }
            // inserrt facility package
            $deleteFacilityPackages = $this->modelFp->deleteFacilityPackage($id);
            if ($deleteFacilityPackages) {
                $facility_packages = $this->request->getPost('facility_package');
                if ($facility_packages) {
                    foreach ($facility_packages as $fp) {
                        $id_fp = $this->modelFp->get_new_id();
                        $this->modelFp->addFacilityPackage(['id' => $id_fp, 'package_id' => $id, 'name' => $fp]);
                    }
                }
            }
        }
        if ($insert) {
            session()->setFlashdata('success', 'Success! Data updated.');
            return redirect()->to(site_url('manage_package/edit/') . $id);
        } else {
            session()->setFlashdata('failed', 'Failed! Failed to update data.');
            return redirect()->to(site_url('manage_package/edit/') . $id);
        }
    }
    public function insert()
    {
        $servicesData = $this->modelservices->getServices()->getResult();
        $data = [
            'title' => $this->title,
            'servicesData' => $servicesData
        ];
        return view('admin-insert/insert_package', $data);
    }
    public function save_insert()
    {

        // ---------------------Data request------------------------------------
        $request = $this->request->getPost();
        $id = $this->model->get_new_id();

        foreach ($request['gallery'] as $key => $value) {
            if (!strlen($value)) {
                unset($request['gallery'][$key]);
            }
        }
        if ($request['gallery']) {
            $folders = $request['gallery'];
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/package');
                delete_files($filepath);
                rmdir($filepath);
                $gallery = $fileImg->getFilename();
            }
        } else {
            $gallery = '';
        }
        $insertRequest = [
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'min_capacity' => $this->request->getPost('min_capacity'),
            'contact_person' => $this->request->getPost('contact_person'),
            'brosur_url' => $gallery,
            'description' => $this->request->getPost('description')
        ];

        // unset empty value
        foreach ($insertRequest as $key => $value) {
            if (empty($value)) {
                unset($insertRequest[$key]);
            }
        }
        $insert =  $this->model->addPackage($insertRequest);
        if ($insert) {
            // insert services
            $services_id = $this->request->getPost('services');
            if ($services_id) {
                foreach ($services_id as $activity_id) {
                    $this->modelservices->addPackageservices(['package_id' => $id, 'services_id' => $activity_id]);
                }
            }

            // inserrt facility package
            $facility_packages = $this->request->getPost('facility_package');
            if ($facility_packages) {
                foreach ($facility_packages as $fp) {
                    $id_fp = $this->modelFp->get_new_id();
                    $this->modelFp->addFacilityPackage(['id' => $id_fp, 'package_id' => $id, 'name' => $fp]);
                }
            }
        }
        if ($insert) {
            session()->setFlashdata('success', 'Success! Data Added.');
            return redirect()->to(site_url('manage_package'));
        } else {
            session()->setFlashdata('failed', 'Failed! Failed to add data.');
            return redirect()->to(site_url('manage_package/insert'));
        }
    }
    public function save_activity($id_package = null)
    {
        // ---------------------Data request------------------------------------
        $request = $this->request->getPost();
        $id = $this->modelservices->get_new_id();
        $insertRequest = [
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description')
        ];

        $insert =  $this->modelservices->addservices($insertRequest);
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
                    $fileImg->move(FCPATH . 'media/photos/services');
                    delete_files($filepath);
                    rmdir($filepath);
                    $gallery[] = $fileImg->getFilename();
                }
                $insertGallery =  $this->modelservices->addGallery($id, $gallery);
            } else {
                $insertGallery = true;
            }
        }
        $url = $this->request->getPost('url');
        if ($url == 'edit') {
            if ($insert && $insertGallery) {
                session()->setFlashdata('success', 'Success! New activity added.');
                return redirect()->to(site_url('manage_package/edit/') . $id_package);
            } else {
                session()->setFlashdata('failed', 'Failed! Failed to add new activity.');
                return redirect()->to(site_url('manage_package/edit/') . $id_package);
            }
        }
        if ($insert && $insertGallery) {
            session()->setFlashdata('success', 'Success! New activity added.');
            return redirect()->to(site_url('manage_package/insert'));
        } else {
            session()->setFlashdata('failed', 'Failed! Failed to add new activity.');
            return redirect()->to(site_url('manage_package/insert'));
        }
    }
    public function delete($id)
    {
        $delete =  $this->model->deletePackage($id);
        if ($delete) {
            session()->setFlashdata('success', 'Success! package Deleted.');
            return redirect()->to(site_url('manage_package'));
        } else {
            session()->setFlashdata('failed', 'Failed to delete package ');
            return redirect()->to(site_url('manage_package'));
        }
    }
}
