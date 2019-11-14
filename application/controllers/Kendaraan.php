<?php

use Restserver\Libraries\REST_Controller;

class Kendaraan extends REST_Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length,Accept-Encoding");
        parent::__construct();
        $this->load->model('KendaraanModel');
        $this->load->library('form_validation');
    }

    public function index_get()
    {
        return $this->returnData($this->db->get('vehicles')->result(), false);
    }

    public function index_post($id = null)
    {
        $validation = $this->form_validation;
        $rule = $this->KendaraanModel->rules();
        if ($id == null) {
            array_push(
                $rule,
                [
                    'field' => 'merk',
                    'label' => 'merk',
                    'rules' => 'required'
                ],
                [
                    'field' => 'type',
                    'label' => 'type',
                    'rules' => 'required'
                ],
                [
                    'field' => 'licensePlate',
                    'label' => 'licensePlate',
                    'rules' => 'required'
                ]
            );
        } else {
            array_push(
                $rule,
                [
                    'field' => 'merk',
                    'label' => 'merk',
                    'rules' => 'required',
                    'field' => 'type',
                    'label' => 'type',
                    'rules' => 'required'
                ]
            );
        }
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $Kendaraan = new UserData();
        $Kendaraan->merk = $this->post('merk');
        $Kendaraan->type = $this->post('type');
        $Kendaraan->licensePlate = $this->post('licensePlate');
        if ($id == null) {
            $response = $this->KendaraanModel->store($Kendaraan);
        } else {
            $response = $this->KendaraanModel->update($Kendaraan, $id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null)
    {
        if ($id == null) {
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->KendaraanModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error)
    {
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}
class UserData
{
    public $merk;
    public $type;
    public $licensePlate;
    public $created_at;
}
