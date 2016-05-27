<?php
class Person_m extends CI_Model {
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getPerson($person_id) {
		$this->db->select('person_id, name, email, phone, address');
		if ($person_id != null) {
			$this->db->where('person_id',$person_id);
		}
		$query = $this->db->get('person');
		return $query->result_array();
	}

	function addPerson($name, $email, $phone, $address) {
		$data = array(
			'name' => $name,
			'email' => $email,
			'phone' => $phone,
			'address' => $address
		);
		$result = $this->db->insert('person', $data);
		return $result;
	}

	function person_exists($person_id) {
		$this->db->where('person_id',$person_id);
		$query = $this->db->get('person');
		if ($query->num_rows() > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	function editPerson($person_id, $name, $email, $phone, $address) {
		$data = array(
			'name' => $name,
			'email' => $email,
			'phone' => $phone,
			'address' => $address
		);
		$this->db->where('person_id',$person_id);
		$result = $this->db->update('person', $data);
		return $result;
	}

	function deletePerson($person_id) {
		$this->db->where('person_id',$person_id);
		$this->db->delete('person');
		return true;
	}
}