<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of model_rw_variable_test
 *
 * @author hp
 */
class model_rw_variable_test extends CI_Model {

    //put your view_position here
    public function __construct() {
        parent::__construct();
    }

    function select_result() {
        $this->db->order_by("id", 'asc');
        return $this->db->get('rw_variable_test')->result();
    }

    function get() {
        $page = $this->input->post('page');
        $rows = $this->input->post('rows');
        $sort = $this->input->post('sort');
        $order = $this->input->post('order');



        if (!empty($sort)) {
            $arr_sort = explode(',', $sort);
            $arr_order = explode(',', $order);
            if (count($arr_sort) == 1) {
                $order_specification = " $arr_sort[0] $arr_order[0] ";
            } else {
                $order_specification = " $arr_sort[0] $arr_order[0] ";
                for ($i = 1; $i < count($arr_sort); $i++) {
                    $order_specification .=", $arr_sort[$i] $arr_order[$i] ";
                }
            }
        } else {
            $order_specification = " id asc";
        }
        $query = "select * from rw_variable_test where true ";

        //----------- search parameter for grid ----------------------
        $q = $this->input->post('q');
        if (!empty($q)) {
            $query .= " and (view_position ilike '%$q%' or description ilike '%$q%') ";
        }
        //----------------------
        $query .= " order by $order_specification";
        //echo $query;
        $result = array();
        $data = "";
        if (!empty($page) && !empty($rows)) {
            $offset = ($page - 1) * $rows;
            $result['total'] = $this->db->query($query)->num_rows();
            $query .= " limit $rows offset $offset";
            $result = array_merge($result, array('rows' => $this->db->query($query)->result()));
            $data = json_encode($result);
        } else {
            $data = json_encode($this->db->query($query)->result());
        }
        return $data;
    }

    function selectAllResult() {
        return $this->db->get('rw_variable_test')->result();
    }

    function select_by_id($id) {
        return $this->db->get_where('rw_variable_test', array('id' => $id))->row();
    }

    function insert() {
        //return $this->db->insert('rw_variable_test', $data);
        $view_position = $this->input->post('view_position');
        $description = $this->input->post('description');

        $data = array(
            "view_position" => $view_position,
            "description" => $description,
            "mandatory" => $this->input->post('mandatory')
        );
        if ($this->db->insert('rw_variable_test', $data)) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('msg' => $this->db->_error_message()));
        }
    }

    function update($id) {
        $view_position = $this->input->post('view_position');
        $description = $this->input->post('description');
        $data = array(
            "view_position" => $view_position,
            "description" => $description,
            "mandatory" => $this->input->post('mandatory')
        );
        $where = array("id" => $id);
        if ($this->db->update('rw_variable_test', $data, $where)) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('msg' => $this->db->_error_message()));
        }
    }

    function delete() {
        //return $this->db->delete('rw_variable_test', $where);
        $id = $this->input->post('id');
        $where = array("id" => $id);
        if ($this->db->delete('rw_variable_test', $where)) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('msg' => $this->db->_error_message()));
        }
    }

}

?>