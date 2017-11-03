<?php
class Articles_model extends CI_Model{
	/**
	 * The name of the table this model connects to.
	 * 
	 * @var string
	 */
	public $tableName = "articles";

	private $rules = [
		[
			"field" => "title",
			"label" => "Title",
			"rules" => "required"
		]
	];

	public function __construct(){
		$this->load->database();
	}

	/**
	 * Checks if the data passes the validation rules
	 * 
	 * @param  Array   $data The data to evalue against the rules
	 * @return Boolean       Whether the validation is passed
	 */
	private function validate($data){
		$this->load->library("form_validation");

		$this->form_validation->set_rules($this->rules);
		$this->form_validation->set_data($data);
		$this->form_validation->reset_validation();

		return $this->form_validation->run();
	}

	/**
	 * Fetches one or more records from the database
	 * 
	 * @param  Number $id The id of the record to select if provided
	 * @return Array      The list of the matching criteria records
	 */
	public function find($id = NULL){
		$query = null;

		if($id == NULL){
			$query = $this->db->get($this->tableName);
		}

		else{
			$query = $this->db->get_where($this->tableName, [
				"id" => $id
			]);
		}

		return $query->result();
	}

	/**
	 * Stores a new article record in the database
	 * 
	 * @param  Array $data The data to store in the database
	 * @return Array       The result of storing the record
	 */
	public function create($data){
		$result = [
			"valid" => false,
			"saved" => false,
			"id"    => null
		];

		// Check if the passed data is valid
		if($this->validate($data)){
			$result["valid"] = true;

			if($this->db->insert($this->tableName, $data)){
				$result["saved"] = true;
				$result["id"]    = $this->db->insert_id();
			}
		}

		return $result;
	}

	/**
	 * Updates the data of the selected article
	 * 
	 * @param  Number $id   The id of the article record
	 * @param  Array  $data The data to update in the record
	 * @return Array        The result of updating the record
	 */
	public function update($id, $data){
		$result = [
			"found" => false,
			'valid' => false,
			"saved" => false
		];

		if($this->find($id)){
			$result["found"] = true;

			if($this->validate($data)){
				$result["valid"] = true;

				$query = $this->db->update($this->tableName, 
					$data,
					["id" => $id]
				);

				if($query){
					$result["saved"] = true;
				}
			}
		}

		return $result;
	}

	/**
	 * Deletes the record from the database
	 * 
	 * @param  Number $id The id of the article record
	 * @return Array      The result of deleting the record
	 */
	public function delete($id){
		$result = [
			"found" => false,
			"saved" => false
		];

		if($this->find($id)){
			$result["found"] = true;

			$query = $this->db->delete($this->tableName, [
				"id" => $id
			]);

			if($query){
				$result["saved"] = true;
			}
		}

		return $result;
	}
}