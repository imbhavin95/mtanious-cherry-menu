<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pagenotfound extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->output->set_status_header('404'); // setting header to 404
        $this->load->view('Backend/templates/show_404');
    }
}

?> 