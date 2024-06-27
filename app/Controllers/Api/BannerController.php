<?php



namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

use CodeIgniter\API\ResponseTrait;

use App\Controllers\BaseController;

use App\Models\Banner;



class BannerController extends BaseController

{

    use ResponseTrait;


    public function index()

    {

        $model = new Banner();

        $data = $model->orderBy('banner_id', 'DESC')->findAll();

        $banner = [];

        for ($i = 0; $i < count($data); $i++) {

            $data[$i]['link'] = site_url() . $data[$i]['link'];

            $data[$i]['thumbnail'] = site_url() . 'image/banner/' . $data[$i]['thumbnail'];

            array_push($banner, $data[$i]);
        }



        if (count($banner) > 0) {

            return $this->respond($banner);
        } else {

            return $this->failNotFound('Tidak ada data');
        }
    }
}
