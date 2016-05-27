<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('template/main');
	}

	public function getPerson() {
		$this->load->model('Person_m', 'person');
		$post = $this->input->post();
		$data = $this->person->getPerson((isset($post["person_id"])) ? $post["person_id"] : null);
		$this->output->set_output(json_encode(array("status"=> "OK", "data"=> $data)));
	}

	public function savePerson() {
		$this->load->model('Person_m', 'person');
		$post = $this->input->post();
		$result = array();
		$person_id = (isset($post["person_id"]) ? $post["person_id"] : null);
		$name = (isset($post["name"]) ? $post["name"] : null);
		$email = (isset($post["email"]) ? $post["email"] : null);
		$phone = (isset($post["phone"]) ? $post["phone"] : null);
		$address = (isset($post["address"]) ? $post["address"] : null);
		
		$checkPerson = $this->person->person_exists($person_id);
		if (!$checkPerson)
			$result = $this->person->addPerson($name, $email, $phone, $address);
		else 
			$result = $this->person->editPerson($person_id, $name, $email, $phone, $address);
		$this->output->set_output(json_encode(array("status"=> "OK", "data"=> $result)));
	}

	public function deletePerson() {
		$this->load->model('Person_m', 'person');
		$post = $this->input->post();
		$person_id = (isset($post["person_id"]) ? $post["person_id"] : null);
		
		$checkPerson = $this->person->person_exists($person_id);
		if ($checkPerson)
			$result = $this->person->deletePerson($person_id);
	
		$this->output->set_output(json_encode(array("status"=> (isset($result) ? $result : false))));
	}
}
