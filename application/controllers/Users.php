<?php
class Users extends CI_Controller{
	public function view($page = "index"){
		// if(!file_exists(APPPATH."views/users/".$page.".php")){
		// 	show_404();
		// }

		// $data["title"] = ucfirst($page);

		// $this->load->view("templates/header.php", $data);
		// $this->load->view("users/".$page.".php", $data);
		// $this->load->view("templates/footer.php", $data);
		
		print("Hello");
	}
}