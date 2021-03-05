<?php defined('BASEPATH') OR exit('No direct script access allowed!');
/**
 * undocumented class
 */
class Admin extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->model('admin_model');
        $this->load->model('user_model');
        $this->load->helper('paginate');
        if(!$this->session->userdata('username')){
            redirect('');
        }
    }
    // Load the dashboard.
    public function index($offset = null){
        $limit = 10;
        if(!empty($offset)){
            $this->uri->segment(3);
        }
        $data['title'] = 'Home | Admin & Procurement';
        $data['body'] = 'admin/dashboard';
        $data['total_isbd'] = $this->admin_model->expenses_isbd();
        $data['total_bln'] = $this->admin_model->expenses_bln();
        $data['total_kp'] = $this->admin_model->expenses_kp();
        $data['total_sindh'] = $this->admin_model->expenses_sindh();
        $data['pending'] = $this->admin_model->total_pending();
        $data['approved'] = $this->admin_model->total_approved();
        $data['rejected'] = $this->admin_model->total_rejected();
        $data['pending_requisitions'] = $this->admin_model->pending_requisitions($limit, $offset);
        $data['approved_requisitions'] = $this->admin_model->approved_requisitions($limit, $offset);
        $data['rejected_requisitions'] = $this->admin_model->rejected_requisitions($limit, $offset);
        $data['isbd_stats'] = $this->admin_model->overall_stats_isbd();
        $data['bln_stats'] = $this->admin_model->overall_stats_bln();
        $data['khyber_stats'] = $this->admin_model->overall_stats_khyber();
        $data['sindh_stats'] = $this->admin_model->overall_stats_sindh();
        $data['annual_expense'] = $this->admin_model->annual_expenses();
        $this->load->view('admin/commons/template', $data);
    }
    // Pending requests - listing
    public function pending_requests($offset = null){
        $limit = 10;
        if(!empty($offset)){
            $this->uri->segment(3);
        }
        $url = 'admin/pending_requests';
        $rowscount = $this->admin_model->total_pending();
        paginate($url, $rowscount, $limit);
        $data['title'] = 'Pending Requests | Admin & Procurement';
        $data['body'] = 'admin/pending-requests';
        $data['pending_requisitions'] = $this->admin_model->pending_requisitions($limit, $offset);
        $this->load->view('admin/commons/template', $data);
    }
    // Approved requests - listing
    public function approved_requests($offset = null){
        $limit = 10;
        if(!empty($offset)){
            $this->uri->segment(3);
        }
        $url = 'admin/approved_requests';
        $rowscount = $this->admin_model->total_approved();
        paginate($url, $rowscount, $limit);
        $data['title'] = 'Approved Requests | Admin & Procurement';
        $data['body'] = 'admin/approved-requests';
        $data['approved_requisitions'] = $this->admin_model->approved_requisitions($limit, $offset);
        $this->load->view('admin/commons/template', $data);
    }
    // Rejected requests - listing
    public function rejected_requests($offset = null){
        $limit = 10;
        if(!empty($offset)){
            $this->uri->segment(3);
        }
        $url = 'admin/rejected_requests';
        $rowscount = $this->admin_model->total_rejected();
        paginate($url, $rowscount, $limit);
        $data['title'] = 'Rejected Requests | Admin & Procurement';
        $data['body'] = 'admin/rejected-requests';
        $data['rejected_requisitions'] = $this->admin_model->rejected_requisitions($limit, $offset);
        $this->load->view('admin/commons/template', $data);
    }
    // Request detail - Filter by ID.
    public function request_detail($id){
        if(!$this->session->userdata('username')){
            redirect('');
        }
        $data['title'] = 'Request Detail | Admin & Procurement';
        $data['body'] = 'admin/request_detail';
        $data['request_detail'] = $this->admin_model->request_detail($id);
        $this->load->view('admin/commons/template', $data);
    }
    // Approve request.
    public function approve_request($id){
        if(!$this->session->userdata('username')){
            redirect('');
        }
        $data = array(
            'status' => 1,
            'updated_at' => date('Y-m-d')
        );
        if($this->admin_model->approve_request($id, $data)){
            $this->session->set_flashdata('success', '<strong>Success! </strong>Request approval was successful.');
            redirect('admin');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong in request approval. Please try again.');
            redirect('admin/request_detail/'.$id);
        }
    }
    // Reject request.
    public function reject_request($id){
        if(!$this->session->userdata('username')){
            redirect('');
        }
        $data = array(
            'status' => 2,
            'updated_at' => date('Y-m-d')
        );
        if($this->admin_model->approve_request($id, $data)){
            $this->session->set_flashdata('success', '<strong>Success! </strong>Request rejection was successful.');
            redirect('admin');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong in request rejection. Please try again.');
            redirect('admin/request_detail/'.$id);
        }
    }
    // Suppliers - Go to suppliers page.
    public function suppliers($offset = null){
        $limit = 10;
        if(!empty($offset)){
            $this->uri->segment(3);
        }
        $url = 'admin/suppliers';
        $rowscount = $this->admin_model->count_suppliers();
        paginate($url, $rowscount, $limit);
        $data['title'] = 'Suppliers | Admin & Procurement';
        $data['body'] = 'admin/suppliers';
        $data['suppliers'] = $this->admin_model->get_suppliers($limit, $offset);
        $this->load->view('admin/commons/template', $data);
    }
    // Suppliers - Add new supplier
    public function add_supplier(){
        $data = array(
            'name' => $this->input->post('name'),
            'category' => $this->input->post('category'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'region' => $this->input->post('region'),
            'address' => $this->input->post('address')
        );
        if($this->admin_model->add_supplier($data)){
            $this->session->set_flashdata('success', '<strong>Success! /strong>Supplier added successfully.');
            redirect('admin/suppliers');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong, please try again!');
            redirect('admin/suppliers');
        }
    }
    // Suppliers - Remove supplier
    public function delete_supplier($id){
        if($this->admin_model->delete_supplier($id)){
            $this->session->set_flashdata('success', '<strong>Success! </strong>Supplier removal was successful.');
            redirect('admin/suppliers');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong, please try again!');
            redirect('admin/suppliers');
        }
    }
    // Get single supplier by id
    public function edit_supplier($id){
        $supplier = $this->admin_model->edit_supplier($id);
        echo json_encode($supplier);
    }
    // Update supplier
    public function update_supplier(){
        $id = $this->input->post('sup_id');
        $data = array(
            'name' => $this->input->post('name'),
            'category' => $this->input->post('category'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'region' => $this->input->post('region'),
            'address' => $this->input->post('address')
        );
        if($this->admin_model->update_supplier($id, $data)){
            $this->session->set_flashdata('success', '<strong>Success! </strong>Supplier update was successful.');
            redirect('admin/suppliers');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong, please try again!');
            redirect('admin/suppliers');
        }
    }
    // Inventory - Go to inventory page.
    public function inventory($offset = null){
        $limit = 10;
        if(!empty($offset)){
            $this->uri->segment(3);
        }
        $url = 'admin/inventory';
        $rowscount = $this->admin_model->count_inventory();
        paginate($url, $rowscount, $limit);
        $data['title'] = 'Inventory | Admin & Procurement';
        $data['body'] = 'admin/inventory';
        $data['inventory'] = $this->admin_model->get_inventory($limit, $offset);
        $this->load->view('admin/commons/template', $data);
    }
    // Inventory - Add inventory.
    public function add_inventory(){
        $data = array(
            'item_name' => $this->input->post('item_name'),
            'item_desc' => $this->input->post('item_desc'),
            'unit_price' => $this->input->post('unit_price'),
            'item_qty' => $this->input->post('item_qty'),
            'item_category' => $this->input->post('item_cat')
        );
        if($this->admin_model->add_inventory($data)){
            $this->session->set_flashdata('success', '<strong>Success! </strong>Inventory was added successfully');
            redirect('admin/inventory');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong, please try again.');
            redirect('admin/inventory');
        }
    }
    // Invnetory - Edit inventory
    public function edit_inventory($id){
        $inventory = $this->admin_model->edit_inventory($id);
        echo json_encode($inventory);
    }
    // Inventory - update inventory info
    public function update_inventory(){
        $id = $this->input->post('id');
        $data = array(
            'item_name' => $this->input->post('item_name'),
            'item_desc' => $this->input->post('item_desc'),
            'unit_price' => $this->input->post('unit_price'),
            'item_qty' => $this->input->post('item_qty'),
            'item_category' => $this->input->post('item_cat')
        );
        if($this->admin_model->update_inventory($id, $data)){
            $this->session->set_flashdata('success', '<strong>Success! </strong>Inventory was updated successfully');
            redirect('admin/inventory');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong, please try again.');
            redirect('admin/inventory');
        }
    }
    // Inventory - Remove inventory
    public function delete_inventory($id){
        if($this->admin_model->delete_inventory($id)){
            $this->session->set_flashdata('success', '<strong>Success! </strong>Inventory removal was successful.');
            redirect('admin/inventory');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong, please try again!');
            redirect('admin/inventory');
        }
    }
    // Users - Go to users page where the users will be listed.
    public function users($offset = null){
        $limit = 10;
        if(!empty($offset)){
            $this->uri->segment(3);
        }
        $url = 'admin/users';
        $rowscount = $this->admin_model->count_users();
        paginate($url, $rowscount, $limit);
        $data['title'] = 'Users | Admin & Procurement';
        $data['body'] = 'admin/users';
        $data['users'] = $this->admin_model->get_users($limit, $offset);
        $this->load->view('admin/commons/template', $data);
    }
    // Users - Remove user.
    public function delete_user($id){
        if($this->admin_model->delete_user($id)){
            $this->session->set_flashdata('success', '<strong>Success! </strong>User removal was successful.');
            redirect('admin/users');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong, please try again!');
            redirect('admin/users');
        }
    }
    // Invoices - Go to the invoices list page.
    public function invoices($offset = null){
        $limit = 10;
        if(!empty($offset)){
            $this->uri->segment(3);
        }
        $url = 'admin/invoices';
        $rowscount = $this->admin_model->count_invoices();
        paginate($url, $rowscount, $limit);
        $data['title'] = 'Invoices | Admin & Procurement';
        $data['body'] = 'admin/invoices';
        $data['suppliers'] = $this->admin_model->get_suppliers_for_invoice();
        $data['invoices'] = $this->admin_model->get_invoices($limit, $offset);
        $data['projects'] = $this->admin_model->get_projects();
        $this->load->view('admin/commons/template', $data);
    }
    // Invoices - Add invoice into the database.
    public function add_invoice(){
        $data = array(
            'inv_no' => $this->input->post('inv_no'),
            'inv_date' => date('Y-m-d', strtotime($this->input->post('inv_date'))),
            'project' => $this->input->post('project'),
            'vendor' => $this->input->post('vendor_name'),
            'region' => $this->input->post('region'),
            'item' => $this->input->post('item_name'),
            'amount' => $this->input->post('amount'),
            'inv_desc' => $this->input->post('inv_desc')
        );
        if($this->admin_model->add_invoice($data)){
            $this->session->set_flashdata('success', '<strong>Success! </strong>Adding invoice was successful.');
            redirect('admin/invoices');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong, please try again.');
            redirect('admin/invoices');
        }
    }
    // Invoices - print invoice
    public function print_invoice($id){
        $data['title'] = 'Print Invoice | Admin & Procurement';
        $data['body'] = 'admin/print_invoice';
        $data['invoice'] = $this->admin_model->print_invoice($id);
        $this->load->view('admin/commons/template', $data);
    }
    // Invoices - Remove invoices
    public function delete_invoice($id){
        if($this->admin_model->delete_invoice($id)){
            $this->session->set_flashdata('success', '<strong>Success! </strong>Invoice removal was successful.');
            redirect('admin/invoices');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong, please try again!');
            redirect('admin/invoices');
        }
    }
    // Invoices - Changes invoice status to cleared
    public function invoice_status($id){
        $data = array(
            'status' => 1
        );
        if($this->admin_model->update_status($id, $data)){
            $this->session->set_flashdata('success', '<strong>Success! </strong>Invoice status change was successful.');
            redirect('admin/invoices');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong, please try again.');
            redirect('admin/invoices');
        }
    }
    // Projects - Go to projects page.
    public function projects(){
        $data['title'] = 'Projects | Admin & Procurement';
        $data['body'] = 'admin/projects';
        $data['projects'] = $this->admin_model->get_projects();
        $this->load->view('admin/commons/template', $data);
    }
    // Projects - Add new project
    public function add_project(){
        $data = array(
            'project_name' => $this->input->post('project_name'),
            'project_desc' => $this->input->post('project_desc')
        );
        if($this->admin_model->add_project($data)){
            $this->session->set_flashdata('success', '<strong>Success! </strong>Project was added successfully.');
            redirect('admin/projects');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong, please try again.');
            redirect('admin/projects');
        }
    }
    // Projects - Remove project
    public function delete_project($id){
        if($this->admin_model->delete_project()($id)){
            $this->session->set_flashdata('success', '<strong>Success! </strong>Project removal was successful.');
            redirect('admin/projects');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong, please try again!');
            redirect('admin/projects');
        }
    }
    // Maintenance - Office equipments
    public function maintenance($offset = null){
        $limit = 10;
        if(!empty($offset)){
            $this->uri->segment(3);
        }
        $url = 'admin/maintenance';
        $rowscount = $this->admin_model->count_maint_record();
        paginate($url, $rowscount, $limit);
        $data['title'] = 'Maintenance | Admin & Procurement';
        $data['body'] = 'admin/maintenance';
        $data['maintenance_items'] = $this->admin_model->get_maint_record($limit, $offset);
        $this->load->view('admin/commons/template', $data);
    }
    // Maintenance - Add new record
    public function add_maintenance(){
        $data = array(
            'region' => $this->input->post('maint_region'),
            'maint_date' => $this->input->post('maint_date'),
            'maint_cat' => $this->input->post('maint_cat'),
            'maint_desc' => $this->input->post('maint_desc'),
            'vendor' => $this->input->post('vendor'),
            'qty_size' => $this->input->post('qty_size'),
            'unit_price' => $this->input->post('unit_price'),
            'total_amount' => $this->input->post('total_amount'),
            'maint_remarks' => $this->input->post('remarks')
        );
        if($this->admin_model->add_maint_record($data)){
            $this->session->set_flashdata('success', '<strong>Success! </strong>Maintenance record was added successfully.');
            redirect('admin/maintenance');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong, please try again!');
            redirect('admin/maintenance');
        }
    }
    // Maintenance - Delete a record
    public function delete_record($id){
        if($this->admin_model->delete_record($id)){
            $this->session->set_flashdata('success', '<strong>Success! </strong>Record removal was successful.');
            redirect('admin/maintenace');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong, please try again!');
            redirect('admin/maintenance');
        }
    }
    // Asset register
    public function asset_register($offset = null){
        $limit = 15;
        if(!empty($offset)){
            $this->uri->segment(3);
        }
        $url = 'admin/asset_register';
        $rowscount = $this->admin_model->count_assets();
        paginate($url, $rowscount, $limit);
        $data['title'] = 'Asset Register | Admin & Procurement';
        $data['body'] = 'admin/asset-register';
        $data['assets'] = $this->admin_model->get_assets($limit, $offset);
        $this->load->view('admin/commons/template', $data);
    }
    // Asset register - add new item.
    public function add_asset(){
        $data['title'] = 'Asset Detail';
        $data['body'] = 'admin/asset-detail';
        $this->load->view('admin/commons/template', $data);
    }
    // Asset detail
    public function asset_detail($id){
        $data['title'] = 'Asset Detail';
        $data['body'] = 'admin/asset-detail';
        $data['edit'] = $this->admin_model->asset_detail($id);
        $this->load->view('admin/commons/template', $data);
    }
    // Add new asset into the database
    public function save_item(){
        $data = array(
            'year' => $this->input->post('year'),
            'project' => $this->input->post('project'),
            'category' => $this->input->post('category'),
            'item' => $this->input->post('item'),
            'description' => $this->input->post('description'),
            'model' => $this->input->post('model'),
            'asset_code' => $this->input->post('asset_code'),
            'serial_number' => $this->input->post('serial_no'),
            'custodian_location' => $this->input->post('custodian'),
            'designation' => $this->input->post('designation'),
            'department' => $this->input->post('department'),
            'quantity' => $this->input->post('quantity'),
            'district_region' => $this->input->post('district'),
            'status' => $this->input->post('status'),
            'po_no' => $this->input->post('po_no'),
            'contact' => $this->input->post('contact'),
            'purchase_date' => $this->input->post('purchase_date'),
            'receive_date' => $this->input->post('receive_date'),
            'created_at' => date('Y-m-d')
        );
        if($this->admin_model->add_item($data)){
            $this->session->set_flashdata('success', '<strong>Success! </strong>Item was added successfully.');
            redirect('admin/asset_register');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong, please try again later.');
            redirect('admin/asset_register');
        }
    }
    // Update an existing asset record
    public function update_item(){
        $id = $this->input->post('id');
        $data = array(
            'year' => $this->input->post('year'),
            'project' => $this->input->post('project'),
            'category' => $this->input->post('category'),
            'item' => $this->input->post('item'),
            'description' => $this->input->post('description'),
            'model' => $this->input->post('model'),
            'asset_code' => $this->input->post('asset_code'),
            'serial_number' => $this->input->post('serial_no'),
            'custodian_location' => $this->input->post('custodian'),
            'designation' => $this->input->post('designation'),
            'department' => $this->input->post('department'),
            'quantity' => $this->input->post('quantity'),
            'district_region' => $this->input->post('district'),
            'status' => $this->input->post('status'),
            'po_no' => $this->input->post('po_no'),
            'contact' => $this->input->post('contact'),
            'purchase_date' => $this->input->post('purchase_date'),
            'receive_date' => $this->input->post('receive_date'),
            'created_at' => date('Y-m-d')
        );
        if($this->admin_model->update_item($id, $data)){
            $this->session->set_flashdata('success', '<strong>Success! </strong>Item was updated successfully.');
            redirect('admin/asset_register');
        }else{
            $this->session->set_flashdata('failed', '<strong>Failed! </strong>Something went wrong, please try again.');
            redirect('admin/asset_register');
        }
    }
    //== ----------------------------------------- Search filters ---------------------------------------- ==\\
    // Search filters - search suppliers
    public function search_suppliers(){
        $search = $this->input->get('search');
        $data['title'] = 'Search Results > Suppliers';
        $data['body'] = 'admin/suppliers';
        $data['results'] = $this->admin_model->search_suppliers($search);
        $this->load->view('admin/commons/template', $data);
    }
    // Search filters - search inventory
    public function search_inventory(){
        $search = $this->input->get('search');
        $data['title'] = 'Search Results > Inventory';
        $data['body'] = 'admin/inventory';
        $data['results'] = $this->admin_model->search_inventory($search);
        $this->load->view('admin/commons/template', $data);
    }
    // Search filters - search users
    public function search_users(){
        $search = $this->input->get('search');
        $data['title'] = 'Search Results > Users';
        $data['body'] = 'admin/users';
        $data['results'] = $this->admin_model->search_users($search);
        $this->load->view('admin/commons/template', $data);
    }
    // Search filters - search invoices
    public function search_invoices(){
        $search = $this->input->get('search');
        $data['title'] = 'Search Results > Invoices';
        $data['body'] = 'admin/invoices';
        $data['results'] = $this->admin_model->search_invoices($search);
        $this->load->view('admin/commons/template', $data);
    }
    // Search filters - search asset register
    public function search_asset_register(){
        $search = $this->input->get('search');
        $data['title'] = 'Search Results > Asset Register';
        $data['body'] = 'admin/asset-register';
        $data['results'] = $this->admin_model->search_asset_register($search);
        $this->load->view('admin/commons/template', $data);
    }
    // Search filter - search equipment maintenance
    public function search_equip_maintenance(){
        $search = $this->input->get('search');
        $data['title'] = 'Search Results > Equipment Maintenance';
        $data['body'] = 'admin/maintenance';
        $data['results'] = $this->admin_model->search_equip_maintenance($search);
        $this->load->view('admin/commons/template', $data);
    }
    // 404 page.
    public function page_not_found(){
        echo "We're sorry but the page you're looking for could not be found.";
    }
}
