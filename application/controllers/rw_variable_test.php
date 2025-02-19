<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rw_variable_test
 *
 * @author hp
 */
class rw_variable_test extends CI_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('model_rw_variable_test');
    }

    function index() {
        $data['action'] = explode('|', $this->model_user->get_action($this->session->userdata('id'), 80));
        $this->load->view('Variable Inspection/index', $data);
    }

    function get() {
        echo $this->model_rw_variable_test->get();
    }
    function selectall() {
        echo $this->model_rw_variable_test->selectAllResult();
    }

    function input() {
        $this->load->view('Variable Inspection/input');
    }

    function save() {
        $this->model_rw_variable_test->insert();
    }

    function update($id) {
        $this->model_rw_variable_test->update($id);
    }

    function delete() {
        $this->model_rw_variable_test->delete();
    }

}

?>
