<?php defined('BASEPATH') OR exit('No direct script access allowed!');
/**
 * undocumented class
 */
class Supervisor_model extends CI_Model{
    function __construct()
    {
        parent::__construct();
    }
    // Get leave applications by employees.
    public function get_leave_applications(){
        $this->db->select('employee_leaves.id,
                            employee_leaves.emp_id,
                            employee_leaves.leave_from,
                            employee_leaves.leave_to,
                            employee_leaves.no_of_days,
                            employee_leaves.leave_reason,
                            employee_leaves.leave_status,
                            employee_leaves.created_at,
                            users.id as user_id,
                            users.fullname,
                            users.department,
                            users.supervisor');
        $this->db->from('employee_leaves');
        $this->db->join('users', 'employee_leaves.emp_id = users.id', 'left');
        $this->db->where('users.supervisor', $this->session->userdata('id'));
        return $this->db->get()->result();
    }
    // Get pending requisitions. > Only pending requisitions to be displayed on supervisor's dashboard.
    public function get_requisitions($limit, $offset){
        $this->db->select('item_requisitions.id,
                            item_requisitions.item_name,
                            item_requisitions.item_desc,
                            item_requisitions.item_qty,
                            item_requisitions.requested_by,
                            item_requisitions.status,
                            item_requisitions.created_at,
                            item_requisitions.updated_at,
                            users.id as user_id,
                            users.fullname,
                            users.location,
                            inventory.id as inv_id,
                            inventory.item_name as inv_name');
        $this->db->from('item_requisitions');
        $this->db->join('users', 'item_requisitions.requested_by = users.id', 'left');
        $this->db->join('inventory', 'item_requisitions.item_name = inventory.id', 'left');
        $this->db->where('item_requisitions.status', 0);
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }
    // Get leave info
    public function get_leave_info($id){
        $this->db->select('id, emp_id');
        $this->db->from('employee_leaves');
        $this->db->where('id', $id);
        return $this->db->get()->row();
    }
    // Approve / disapprove leave request.
    public function leave_actions($id, $data){
        $this->db->where('id', $id);
        $this->db->update('employee_leaves', $data);
        return true;
    }
}