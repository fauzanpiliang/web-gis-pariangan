<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DetailPackageModel;
use App\Models\detailServicePackageModel;
use App\Models\packageDayModel;
use App\Models\packageModel;
use App\Models\reservationModel;
use Dompdf\Dompdf;

class PdfGenerator extends BaseController
{
    protected $modelPackage, $packageDayModel, $detailPackageModel, $detailServicePackageModel;
    protected $reservationModel;
    public function __construct()
    {
        $this->modelPackage = new packageModel();
        $this->packageDayModel = new packageDayModel();
        $this->detailPackageModel = new DetailPackageModel();
        $this->detailServicePackageModel = new detailServicePackageModel();
        $this->reservationModel = new reservationModel();
    }
    public function index($parse)
    {
        $invoiceData = json_decode($parse, true);
        $dompdf = new Dompdf();
        $data = [
            'imageSrc'    => $this->imageToBase64(ROOTPATH . '\public\assets\images\pariangan.jpg'),
            'packageData' => $invoiceData
        ];
        // dd($data);
        $html = view('user-menu/invoice', $data);
        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream('invoice.pdf', ['Attachment' => false]);
    }
    public function getInvoiceData()
    {
        if ($this->request->isAJAX()) {
            $request = $this->request->getPost('id_reservation');
            $packages = array();
            foreach ($request as $id_reservation) {
                $reservation  = $this->reservationModel->get_r_by_id_api($id_reservation)->getRowArray();
                $id = $reservation['id_package'];
                // each package
                $package = $this->modelPackage->getPackage($id)->getRowArray();

                // service
                $list_service = $this->detailServicePackageModel->get_service_by_package_api($id)->getResultArray();
                $services = array();
                foreach ($list_service as $service) {
                    $services[] = $service['name'];
                }

                $package_day = $this->packageDayModel->get_pd_by_package_id_api($id)->getResultArray();

                for ($i = 0; $i < count($package_day); $i++) {
                    $package_day[$i]['package_day_detail'] = $this->detailPackageModel->get_detail_package_by_dp_api($package_day[$i]['day'])->getResultArray();
                }

                $package['reservation'] = $reservation;
                $package['services'] = $services;
                $package['package_day'] = $package_day;
                array_push($packages, $package);
            }
            return json_encode($packages);
        }
    }
    private function imageToBase64($path)
    {
        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
}
