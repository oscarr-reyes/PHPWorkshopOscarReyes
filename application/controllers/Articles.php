<?php
class Articles extends CI_Controller{
	public function __construct(){
		parent::__construct();

		$this->load->model("articles_model");
	}

	/**
	 * Fetches one or multiple records from Articles model
	 * 
	 * @param  Number $id The id of the record if provided
	 */
	public function get($id = NULL){
		$result = $this->articles_model->find($id);

		// Turn the result into a single object
		if(sizeof($result) == 1){
			$result = $result[0];
		}

		$this->output
			->set_content_type("application/json")
			->set_output(
				json_encode($result)
			);
	}
	
	/**
	 * Creates a new article using Articles model
	 */
	public function create(){
		$errorMessages = [
			"invalid" => "Invalid data payload, please check your body",
			"unsaved" => "Unexpected error occurred"
		];

		// Transform JSON data into POST
		$payload = $this->getPayload();

		// Store the data in the database
		$save = $this->articles_model->create($payload);

		// Storage success
		if($save["saved"] == true){
			$this->output->set_status_header(201);

			$this->get($save["id"]);
		}

		else{
			$message = $save["valid"] == false ? $errorMessages["invalid"] : $errorMessages["unsaved"];

			$this->output
				->set_status_header(409)
				->set_content_type("application/json")
				->set_output(
					json_encode([
						"error" => $message 
					])
				);
		}
	}

	/**
	 * Updates the data of selected record
	 * 
	 * @param  Number $id The id of the article record
	 */
	public function update($id = NULL){
		$errorMessages = [
			"notFound" => "Article #$id not found",
			"invalid"  => "Invalid data payload, please check your body",
			"unsaved"  => "Unexpected error occurred"
		];

		// Transform JSON data into POST
		$payload = $this->getPayload();

		$save = $this->articles_model->update($id, $payload);

		if($save["found"] == false){
			$this->output
				->set_status_header(404)
				->set_content_type("application/json")
				->set_output([
					json_encode([
						"error" => $errorMessages["notFound"]
					])
				]);
		}

		// Storage success
		else if($save["saved"] == true){
			$this->output->set_status_header(201);

			$this->get($id);
		}

		else{
			$message = $save["valid"] == false ? $errorMessages["invalid"] : $errorMessages["unsaved"];

			$this->output
				->set_status_header(409)
				->set_content_type("application/json")
				->set_output(
					json_encode([
						"error" => $message 
					])
				);
		}

	}

	/**
	 * Deletes the selected record
	 * 
	 * @param  Number $id The id of the article record
	 */
	public function delete($id = NULL){
		$errorMessages = [
			"notFound" => "Article #$id not found",
			"unsaved"  => "Unexpected error occurred"
		];

		$save = $this->articles_model->delete($id);

		if($save["found"]){
			if($save["saved"]){
				$this->output
					->set_content_type("application/json")
					->set_output(
						json_encode([
							"passed" => true
						])
					);
			}

			else{
				$this->output
					->set_status_header(500)
					->set_content_type("application/json")
					->set_output(
						json_encode([
							"error" => $errorMessages["unsaved"]
						])
					);
			}
		}

		else{
			$this->output
				->set_status_header(404)
				->set_content_type("application/json")
				->set_output(
					json_encode([
						"error" => $errorMessages["notFound"]
					])
				);
		}
	}

	/**
	 * Transforms the payload to POST data
	 * 
	 * @return Array The data transformed into POST
	 */
	private function getPayload(){
		$requestType   = $this->input->get_request_header("content-type");
		$data = $_POST;

		if($requestType == "application/json"){
			$data = json_decode($this->input->raw_input_stream, true);
		}

		return $data;
	}
}