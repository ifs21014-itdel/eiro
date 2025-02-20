<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of purchase_order
 *
 * @author user
 */
class rw_inspection extends CI_Controller {

    //put your code here

    public function __construct() {
        parent::__construct();
        $this->load->model('model_rw_inspection');
        $this->load->model('model_products');
        $this->load->model('model_rw_image_category');
    }

    function index() {
        $this->load->view('rw_inspection/index');
    }

    function create_from_po_editorial() {
        $this->model_rw_inspection->create_from_po_editorial();
    }

    function input() {
        $this->load->view('rw_inspection/input');
    }

    function get() {

        echo $this->model_rw_inspection->get();
    }

    function get_item_po() {
        echo $this->model_rw_inspection->get_item_po();
    }

    function view() {
        $data['action'] = explode('|', $this->model_user->get_action($this->session->userdata('id'), 51));
        $this->load->view('rw_inspection/rw_inspection', $data);
    }

    function save($id) {
        $id_po_item_rw_inspection = $this->input->post('id_po_item_rw_inspection');
        $datatemp = explode("#", $id_po_item_rw_inspection);

        //var_dump($datatemp);
        //exit;
        $data = array(
            "rw_inspection_date" => date('Y-m-d'),
            "purchaseorder_item_id" => $datatemp[0],
            "po_client_no" => $datatemp[1],
            "ebako_code" => $datatemp[2],
            "customer_code" => $datatemp[3],
            "client_id" => $datatemp[4],
            "client_name" => $datatemp[5]
        );

        if ($id == 0) {
            $data['user_added'] = $this->session->userdata('name');
            $data['added_time'] = date("Y-m-d H:i:s");
            $data['user_updated'] = $this->session->userdata('name');
            $data['updated_time'] = date("Y-m-d H:i:s");
            //var_dump($data);
            if ($this->model_rw_inspection->insert($data)) {
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('msg' => $this->db->_error_message()));
            }
        } else {
            $data['user_updated'] = $this->session->userdata('name');
            $data['updated_time'] = date("Y-m-d H:i:s");
            if ($this->model_rw_inspection->update($data, array("id" => $id))) {
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('msg' => $this->db->_error_message()));
            }
        }
    }

    function outstanding_release() {
        $this->load->view('rw_inspection/outstanding_release');
    }

    function get_outstanding_release() {
        echo $this->model_rw_inspection->get_outstanding_release();
    }

    function rw_inspection_detail() {
        $this->load->view('rw_inspection/rw_inspection_detail');
    }

    function product_edit() {
        $this->load->view('rw_inspection/product_edit');
    }

    function product_update($id) {
        $data = array(
            "price" => $this->input->post('price'),
            "discount" => (double) $this->input->post('discount')
        );
        if ($this->db->update('rw_inspection_detail', $data, array("id" => $id))) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('msg' => $this->db->_error_message()));
        }
    }

    function rw_inspection_detail_get() {
        echo $this->model_rw_inspection->rw_inspection_detail_get();
    }

    function delete() {
        // $this->db->query("delete from product_order_detail where rw_inspection_detail_id in (select id from rw_inspection_detail where rw_inspection_id=$id)");
        //$this->db->query("delete from rw_inspection_detail where rw_inspection_id=$id");
        if ($this->db->delete('rw_inspection', array("id" => $this->input->post('id')))) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('msg' => $this->db->_error_message()));
        }
    }

    //----------

    function product_input($rw_inspection_id, $id, $rw_image_category_id) {
        $data = array();
        $data['rw_inspection_id'] = $rw_inspection_id;
        $data['id'] = $id;
        $data['rw_image_category_id'] = $rw_image_category_id;
    
        // Load view tanpa batasan perangkat
        $this->load->view('rw_inspection/input_file', $data);
    }
    

    function product_save($rw_inspectionid, $id, $rw_image_category_id) {
        error_log("Debug: product_save dipanggil dengan rw_inspectionid=$rw_inspectionid, id=$id, rw_image_category_id=$rw_image_category_id");

        $data = array();
        $data['user_added'] = $this->session->userdata('name');
        $data['added_time'] = date("Y-m-d H:i:s");
        $data['user_updated'] = $this->session->userdata('name');
        $data['updated_time'] = date("Y-m-d H:i:s");
    
        $directory = 'files/rw_inspection/' . $rw_inspectionid;
        if (!file_exists($directory)) {
            $oldumask = umask(0);
            mkdir($directory, 0777, true);
            umask($oldumask);
        }
    
        $allowedImageType = array('jpg', 'png', 'jpeg', 'JPG', 'JPEG', 'PNG');
        $nametemp = 'image-1';
        
        // Cek apakah file diunggah
        if (!isset($_FILES[$nametemp])) {
            error_log("Error: File dengan key '$nametemp' tidak ditemukan di \$_FILES.");
            return;
        }
    
        if ($_FILES[$nametemp]['error'] !== UPLOAD_ERR_OK) {
            error_log("Upload error: " . $_FILES[$nametemp]['error']);
            return;
        }
    
        $imageName = $_FILES[$nametemp]['name'];
        $tempPath = $_FILES[$nametemp]["tmp_name"];
        $imageType = pathinfo($imageName, PATHINFO_EXTENSION);
    
        if (!in_array($imageType, $allowedImageType)) {
            error_log("Error: Tipe file '$imageType' tidak diperbolehkan.");
            return;
        }
    
        $basename = $id . '-' . $rw_inspectionid . '-' . $rw_image_category_id . "." . $imageType;
        $originalPath = $directory . '/' . $basename;
    
        if (move_uploaded_file($tempPath, $originalPath)) {
            $data['filename'] = $basename;
            if ($this->model_rw_inspection->product_update($data, array("id" => $id))) {
                echo json_encode(array('success' => true));
            } else {
                error_log("Database update error: " . $this->db->_error_message());
            }
        } else {
            error_log("Error: Gagal mengupload file ke '$originalPath'.");
        }
    }
    
    

    function product_delete() {
        $id = $this->input->post('id');
        if ($this->model_rw_inspection->product_delete(array("id" => $id))) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('msg' => $this->db->_error_message()));
        }
    }

    function prints() {
        $id = $this->input->post('id');
        $this->load->library('pdf');
        $data['rw_inspection'] = $this->model_rw_inspection->select_by_id($id);
        $data['rw_inspection_detail'] = $this->model_rw_inspection->select_rw_image_category_by_rw_inspection_id($id);
        //--------- UNtuk EXCEL ----
        /*
          header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
          header("Content-Disposition: inline; filename=\"rw_inspection.xls\"");
          header("Pragma: no-cache");
          header("Expires: 0");
         */

        //--------- UNtuk WORD ----
        //    header("Content-Type: application/vnd.ms-word; charset=UTF-8");
        //   header("Content-Disposition: inline; filename=\"rw_inspection.doc\"");
        //    header("Pragma: no-cache");
        //    header("Expires: 0");
        $this->load->view('rw_inspection/print', $data);
    }

    function print_summary() {
        $id = $this->input->post('id');
        $this->load->library('pdf');
        $data['shipment'] = $this->model_rw_inspection->select_by_id($id);
        $data['shipment_item'] = $this->model_rw_inspection->select_summarize_by_shipment_id($id);
        $this->load->view('shipment/print_summary', $data);
    }

    function product_image_detail($rw_inspection_id, $id, $rw_image_category_id) {
        $data['ins_detail'] = $this->model_rw_inspection->rw_inspection_detil_get_byid($rw_inspection_id, $id, $rw_image_category_id);
        // var_dump($data);
        $this->load->view('rw_inspection/show_detail', $data);
    }

    function submit() {
        $id = $this->input->post('id');
        $purchaseorder_item_id = $this->input->post('purchaseorder_item_id');

        $data = array(
        );
        $data['user_added'] = $this->session->userdata('name');
        $data['added_time'] = date("Y-m-d H:i:s");
        $data['user_updated'] = $this->session->userdata('name');
        $data['updated_time'] = date("Y-m-d H:i:s");
        $data['submited'] = 'TRUE';
        $data['purchaseorder_item_id'] = $purchaseorder_item_id;
        $data['rw_inspection_date'] = date('Y-m-d');
        // echo 'rw_inspection id='.$id.' dan po itemid='.$purchaseorder_item_id;
        // exit;
        $error_message = "";
        if ($this->model_rw_inspection->update($data, array("id" => $id))) {
            $this->model_rw_inspection->update_po_item_status($purchaseorder_item_id);
            echo json_encode(array('success' => true));
        } else {
            $error_message = $this->db->_error_message();
            echo json_encode(array('msg' => $error_message));
        }
    }

    function isMobile() {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

    function isPC() {
        return preg_match("/(windows|linux|)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

}

?>