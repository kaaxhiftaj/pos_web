<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pos_apis extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		//Do your magic here

		$this->load->library('email');
	}

	public function mobile_verify()
	{
		if (!empty($mobile_no)) 
		{
			$check = $this->common_model->getAllData('users','*',array('mobile_no' => $mobile_no));

			if (!empty($check[0]->mobile_no)) 
			{
				$response = array(
				
							'success' 			 => true, 
							'status'  			 => 200, 
							'verification_code'  => 00000, 
							'message' 			 => 'Verification Code'
						);

				echo json_encode($response);
			}
			else
			{
				$data = array('mobile_no' => $mobile_no );

				$insert = $this->common_model->InsertData('users',$data);

				$last_id = $this->db->insert_id();

				$user_data = $this->common_model->getAllData('users','*',array('id' => $last_id));

				print_r($user_data);
			}
		}
		else
		{
			$response = array(
				
				'success' => false, 
				'status'  => 422, 
				'message' => 'Please Enter Mobile Number');

			echo json_encode($response);
		}
	}

	/**
	 * [New User Registeration]
	 * @return [void] 
	 */
	
	public function sign_up()
	{
		$first_name	= $this->input->post('first_name');
		$last_name 	= $this->input->post('last_name');
		$mobile_no  = $this->input->post('mobile_no');

		if (!empty($first_name) && !empty($last_name) && !empty($mobile_no)) 
		{
			$verify = $this->common_model->getAllData('users','*',array('mobile_no' => $mobile_no));

			if (!empty($verify)):
				
				$data = array(
								'mobile_no' => $mobile_no, 
							 );

				$auth = $this->common_model->Authentication('users',$data);

				if (!empty($auth)):
					
					$afterlogin = array(
											'user_id'    => (int)$auth->id, 
											'first_name' => $auth->first_name, 
											'last_name'  => $auth->last_name, 
											'mobile_no'  => $auth->mobile_no,
											'user_img'   => $auth->user_img,
											'api_token'  => $auth->api_token,
										);


					$response = array(
										'success'    => true,
										'status'     => 200 ,
										'message'    => 'User Login Successfull' , 
										'user_data'  => $afterlogin);

					echo json_encode($response);

				else:

					$response = array(
										'success' => false,
										'status'  => 401 ,
										'message' => 'Password or Eamil is incorrect'
									 );

					echo json_encode($response);

				endif;

			else:

				$key = implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));

				$data = array(
								'first_name' => $first_name, 
								'last_name'  => $last_name, 
								'mobile_no'  => $mobile_no, 
								'api_token'  => $key 
							 );

				$insert = $this->common_model->InsertData('users',$data);

				$last_id = $this->db->insert_id();

				$user_data = $this->common_model->getAllData('users','*',array('id' => $last_id));


				$user = array(
								'user_id'     => $user_data[0]->id, 
								'first_name'  => $user_data[0]->first_name, 
								'last_name'   => $user_data[0]->last_name, 
								'mobile_no'  => $user_data[0]->mobile_no,
								'api_token'  => $user_data[0]->api_token,
								
							  );

				$response = array(
									'success'   => true,
									'status'    => 200,
									'user_data' => $user, 
									'message'   => 'User Added Successfully');

				echo json_encode($response);

			endif;
		}
		else
		{
			$response = array(
								'success' => false,
								'status'  => 422 ,
								'message' => 'Please fill All Input Fields');

			echo json_encode($response);
		}	
	}

	/**
	 * [Logged the User In]
	 * @return [user data]
	 */
	public function login()
	{
		$first_name	= $this->input->post('first_name');
		$last_name 	= $this->input->post('last_name');
		$mobile_no  = $this->input->post('mobile_no');

		if (!empty($mobile_no)) 
		{
			$verify = $this->common_model->getAllData('users','*',array('mobile_no' => $mobile_no));

			if (!empty($verify)):
				
				$data = array(
								'mobile_no' => $mobile_no, 
							 );

				$auth = $this->common_model->Authentication('users',$data);

				if (!empty($auth)):
					
					$afterlogin = array(
											'user_id'    => (int)$auth->id, 
											'first_name' => $auth->first_name, 
											'last_name'  => $auth->last_name, 
											'mobile_no'  => $auth->mobile_no,
											'user_img'   => $auth->user_img,
											'api_token'  => $auth->api_token,
										);


					$response = array(
										'success'    => true,
										'status'     => 200 ,
										'message'    => 'User Login Successfull' , 
										'user_data'  => $afterlogin);

					echo json_encode($response);

				else:

					$response = array(
										'success' => false,
										'status'  => 401 ,
										'message' => 'Incorrect Phone Number'
									 );

					echo json_encode($response);

				endif;

			else:

				if (!empty($first_name) && !empty($last_name)) 
				{
					$key = implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));

					$data = array(
									'first_name' => $first_name, 
									'last_name'  => $last_name, 
									'mobile_no'  => $mobile_no, 
									'api_token'  => $key 
								 );

					$insert = $this->common_model->InsertData('users',$data);

					$last_id = $this->db->insert_id();

					$user_data = $this->common_model->getAllData('users','*',array('id' => $last_id));


					$user = array(
									'user_id'     => $user_data[0]->id, 
									'first_name'  => $user_data[0]->first_name, 
									'last_name'   => $user_data[0]->last_name, 
									'mobile_no'   => $user_data[0]->mobile_no,
									'api_token'   => $user_data[0]->api_token,
									
								  );

					$response = array(
										'success'   => true,
										'status'    => 200,
										'user_data' => $user, 
										'message'   => 'User Added Successfully');

					echo json_encode($response);
				}
				else
				{
					$response = array(
										'success'   => false,
										'status'    => 422,
										'message'   => 'Firstname and Lastname required');

					echo json_encode($response);
				}

				

			endif;
		}
		else
		{
			$response = array(
								'success' => false,
								'status'  => 422 ,
								'message' => 'Phone Number is Required');

			echo json_encode($response);
		}
	}

	/**
	 * [User Forgot Password]
	 * @return [void]
	 */
	function forgotpassword()
	{
		$email = $this->input->post('email');

		if (!empty($email)) 
		{
			$verify = $this->common_model->getAllData('users','*',array('email' => $email));

			if (!empty($verify)) 
			{
				$id = $verify[0]->id;
				
				$random = mt_rand(100000, 999999);

				$updateRandomValue = $this->common_model->UpdateDB('users',array('id' => $id),array('fotgot_pass_verify' => $random));


				if ($updateRandomValue) 
				{
					$Message = 'Hi Dear, <br> Your Verification code is: <br> <h3>'.$random.'</h3> <br><br> Thanks';
					
					$this->email->set_mailtype("html");
					
					$config = array(
										'protocol' => 'sendmail',
										'mailtype' => 'html',
										'charset' => 'utf-8',
										'wordwrap' => TRUE
									);

					$this->load->library('email', $config);

					$this->email->set_newline("\r\n");
					$this->email->subject('Email Reset');
					$this->email->message($Message);
					$this->email->from('Pos@gmail.com','Pos');
					$this->email->to($email);
					$this->email->send();

					echo json_encode(
										array(
												'success'  => true,
												'status'   => 200,
												 "message" => "Verification code is sent to you via email."
											 )
									);
				}
			}
			else
			{
				echo json_encode(array(
										'success'  => false,
										'status'   => 401,
										 "message" => "Error Either Email is not correct or not found"));
			}
		}
		else
		{
			$response = array(
								'success' => false,
								'status'  => 422 ,
								'message' => 'Please fill the input field');

			echo json_encode($response);
		}	
	}

	/**
	 * [forgot password verification]
	 * @return [user verified]
	 */
	public function forgot_code_verify()
	{
		$code = $this->input->post('code');

		if (!empty($code)) 
		{
			$check = $this->common_model->getAllData('users','*',array('fotgot_pass_verify' => $code));

			if (!empty($check)) 
			{
				echo json_encode(
									array(
											'success'=> true,
											'status' => 200,
											'message' => 'verified'
										)
								);
			}
			else
			{
				echo json_encode(	
									array(
											'success'=> false,
											'status' => 401,
											'message' => 'Your Code did not Match'
										)
								);
			}
		}
		else
		{
			echo json_encode(array(
									'success'  => false,
									'status'   => 422,
									'message'  => 'Please Enter Varificaiton Code')
								   );
		}
	}

	function sociallogin()
	{
		$provider     = $this->input->post('provider');
		$provider_id  = $this->input->post('provider_id');
		// $name         = $this->input->post('name');
		$device_type  = $this->input->post('device_type');
		$device_token = $this->input->post('device_token');
		$email        = $this->input->post('email');
		// $gender       = $this->input->post('gender');

		if (!empty($provider) && !empty($provider_id)) {
			
			$token_id = md5(uniqid($email, true));

			$check = $this->common_model->getAllData('users','*',array('email' => $email));

			if (!empty($check[0]->email)) 
			{
				$getUser  = $this->common_model->getAllData('users','*',array('email' => $email));

				if (!empty($getUser)) 
				{
					
					$user = array(
									'id'        => (int)$getUser[0]->id,
									'email'     => $getUser[0]->email,
									'api_token' => $getUser[0]->api_token,
								);

					$response = array(
										'success' => true,
										'status'  => 202, 
										'message' => 'User already Registered', 
										'user'    => $user
									);

					echo json_encode($response);
					die;	
				}
			}

			$string = mt_rand(100000, 999999);

			$paswrd = mt_rand(100000, 999999);

			$key = implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));
		
			$data = array(
							  'email'       => $email,
							  'password' 	=> $paswrd,
							  'api_token' 	=> $key,
					     );

			$result = $this->common_model->InsertData('users',$data);
			
			$lastuserid   = $this->db->insert_id();			

			$RegisterData = array('Provider'     => $provider, 
							      'provider_id'  => $provider_id,
							      'user_id'      => $lastuserid,
							      'device_type'  => $device_type,
							      'device_token' => $device_token
							     );

			$save    = $this->common_model->InsertData('socail_logins',$RegisterData);


			$getUser = $this->common_model->getAllData('users','*',array('id' => $lastuserid));

			
			$afterRegistration = array(
										'id'        => (int)$getUser[0]->id,
										'email'     => $getUser[0]->email,
										'api_token' => $getUser[0]->api_token,
									  );

			if ($save) 
			{

				$response = array(
									'success' => true,
									'status'  => 200, 
									'message' => "User Registered Successfully!",
									'user'    => $afterRegistration);

				echo json_encode($response);

			}
			else
			{

				$response = array(
									'success' => false,
									'status'  => 500,
									'message' => 'Database Error');

				echo json_encode($response);

			}
		}
		else
		{
			$response = array(
								'success' => false,
								'status'  => 422,
								'message' => 'Please Fill All Input Fields');
							
						echo json_encode($response);
		}
	}

	/**
	 * [All Jobs]
	 * @return [All jobs]
	 */
	public function jobs()
	{
		$api_token = $this->input->post('api_token');

		$check_token = $this->common_model->getAllData('users','api_token',array('api_token' => $api_token));

		if (!empty($check_token)) 
		{
			
			$All_jobs = array();

			$jobs = $this->common_model->getAllData('jobs','*',array('job_accepted' => 0));

			foreach ($jobs as $job) 
			{

				$job_arr = array(

									'job_id'        => $job->id, 
									'job_title'     => $job->mission_title, 
									'company_name'  => $job->company_name, 
									'shot_desc'     => $job->short_desc, 
									'brief_desc'    => $job->brief_desc, 
									'pay_per_job'   => $job->pay_per_job, 
									'featured_img'  => $job->company_logo, 
									'latitude'      => $job->latitude, 
									'longitude'     => $job->longitude, 
									'date'          => date("d-m", strtotime($job->date)),  
							    );

				array_push($All_jobs, $job_arr);
			}


			$job_images = array();

			$get_job_images = $this->common_model->DJoin('job_id,images','jobs','job_images','jobs.id = job_images.job_id','',array('job_accepted' => 0));

			foreach ($get_job_images as $get_job) 
			{

				$job_image = array(

								'job_id'        => $get_job->job_id, 
								'job_images'    => $get_job->images,  
							 );

				array_push($job_images, $job_image);
			}
			

			if (!empty($All_jobs)) 
			{
				$response = array(
									'success'    => true,
									'status'     => 200,
									'message'    => 'All Jobs',
									'job_images' => $job_images,
									'All_jobs'   => $All_jobs,
								 );

				echo json_encode($response);
			}
			else
			{
				$response = array(
									'success' => false,
									'status'  => 403,
									'message' => 'All Jobs',
									'jobs'    => 'Jobs Not Available'
								 );

				echo json_encode($response);
			}
		}
		else
		{
			$response = array(
								'success' => false,
								'status'  => 401,
								'message' => 'Unauthorized Token',
							 );

				echo json_encode($response);
		}	
	}

	/**
	 * [created time]
	 * @param  [timestamp] 
	 * @param   boolean $full     
	 * @return  time ago     
	 */
	private function time_elapsed_string($datetime, $full = false) 
	{
	    $now = new DateTime;
	    $ago = new DateTime($datetime);
	    $diff = $now->diff($ago);

	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;

	    $string = array(
	        'y' => 'year',
	        'm' => 'month',
	        'w' => 'week',
	        'd' => 'day',
	        'h' => 'hour',
	        'i' => 'minute',
	        's' => 'second',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
	        } else {
	            unset($string[$k]);
	        }
	    }

	    if (!$full) $string = array_slice($string, 0, 1);
	    return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

	/**
	 * [show user profile]
	 * @return [user data]
	 */
	public function user_profile()
	{
		$api_token = $this->input->post('api_token');

		$check_token = $this->common_model->getAllData('users','api_token',array('api_token' => $api_token));

		if (!empty($check_token)) 
		{
			$user_id = $this->input->post('user_id');

			$check_user = $this->common_model->getAllData('users','*',array('id' => $user_id));

			if (!empty($check_user)) 
			{
				
				$user_data = array(
									'first_name' => $check_user[0]->first_name, 
									'last_name'  => $check_user[0]->last_name, 
									'email'      => $check_user[0]->email, 
									'user_img'   => $check_user[0]->user_img,
									'mobile_no'  => $check_user[0]->mobile_no, 
								  );

				$response = array(

									'success'    => true,
									'status'     => 200,
									'user_data'  => $user_data,
									'message'    => 'User Profile Data',
							 	);

				echo json_encode($response);
			}
			else
			{
				$response = array(
								'success' => false,
								'status'  => 401,
								'message' => 'User ID not Found',
							 );

				echo json_encode($response);
			}

		}
		else
		{
			$response = array(
								'success' => false,
								'status'  => 401,
								'message' => 'Unauthorized Token',
							 );

			echo json_encode($response);
		}
	}

	public function edit_profile()
	{
		$api_token = $this->input->post('api_token');

		$check_token = $this->common_model->getAllData('users','api_token',array('api_token' => $api_token));

		if (!empty($check_token)) 
		{
			$user_id = $this->input->post('user_id');

			$check_user = $this->common_model->getAllData('users','*',array('id' => $user_id));

			if (!empty($check_user)) 
			{
				if (!empty($_FILES)) 
				{
					$config['upload_path']          = './uploads/user_images/';
	                $config['allowed_types']        = 'gif|jpg|png';

	                $this->load->library('upload', $config);

	                if ( ! $this->upload->do_upload('userfile'))
	                {
	                        $error = array('error' => $this->upload->display_errors());

	                        print_r($error);die;
	                }
	                else
	                {
	                        $data = array('upload_data' => $this->upload->data());

	                        $img = $data['upload_data']['file_name'];
	                }


					$first_name = $this->input->post('first_name');
					$last_name  = $this->input->post('last_name');
					$email      = $this->input->post('email');
					$mobile_no  = $this->input->post('mobile_no');


					$data = array(
									'first_name' => $first_name, 
									'last_name ' => $last_name, 
									'email '     => $email , 
									'mobile_no ' => $mobile_no, 
									// 'user_img '  => base_url().'/uploads/user_images/'.$img, 
								  );

					$user_update = $this->common_model->UpdateDB('users',array('id' => $user_id),$data);

					if ($user_update) 
					{
						$response = array(
											'success'    => true,
											'status'     => 200,
											'message'    => 'User Updated',
								 		 );

						echo json_encode($response);
					}
					else
					{
						$response = array(

										'success'    => true,
										'status'     => 500,
										'message'    => 'User Not Updated database error',
								 	);

						echo json_encode($response);
					}
				}
				else
				{
					$response = array(
									'success'    => false,
									'status'     => 400,
									'message'    => 'User image Required',
							 	);

					echo json_encode($response);
				}	
			}
			else
			{
				$response = array(
								'success' => false,
								'status'  => 401,
								'message' => 'User ID not Found',
							 );

				echo json_encode($response);
			}

		}
		else
		{
			$response = array(
								'success' => false,
								'status'  => 401,
								'message' => 'Unauthorized Token',
							 );

			echo json_encode($response);
		}
	}

	/**
	 * [User Accepted Job]
	 * @return [bolean]
	 */
	public function jobs_accepted()
	{
		$api_token = $this->input->post('api_token');

		$check_token = $this->common_model->getAllData('users','api_token',array('api_token' => $api_token));

		if (!empty($check_token)) 
		{
			$job_id  = $this->input->post('job_id');
			$user_id = $this->input->post('users_id');

			$check_job = $this->common_model->getAllData('jobs','*',array('id' => $job_id));

			if (!empty($check_job)) 
			{
				$data = array(
								'job_accepted' => 1, 
								'users_id'     => $user_id
							  );

				$job_update = $this->common_model->UpdateDB('jobs',array('id' => $job_id),$data);

				if ($job_update) 
				{
					$response = array(
										'success'    => true,
										'status'     => 200,
										'message'    => 'Job Accepted',
							 		 );

					echo json_encode($response);
				}
				else
				{
					$response = array(

									'success'    => true,
									'status'     => 500,
									'message'    => 'Database Error',
							 	);

					echo json_encode($response);
				}
			}
			else
			{
				$response = array(
								'success' => false,
								'status'  => 401,
								'message' => 'Job ID not Found',
							 );

				echo json_encode($response);
			}

		}
		else
		{
			$response = array(
								'success' => false,
								'status'  => 401,
								'message' => 'Unauthorized Token',
							 );

			echo json_encode($response);
		}
	}

	/**
	 * [Specific user jobs]
	 * @return [User Jobs]
	 * 	 
	*/
	public function user_job_accepted()
	{
		$api_token = $this->input->post('api_token');

		$check_token = $this->common_model->getAllData('users','api_token',array('api_token' => $api_token));

		if (!empty($check_token)) 
		{
			$user_id = $this->input->post('users_id');

			$accepted_jobs = $this->common_model->DJoin('*','users','jobs','jobs.users_id = users.id','',array('jobs.users_id' => $user_id));

			// $user_not_completed_Jobs = $this->common_model->DJoin('*','jobs_details','jobs','jobs.id = jobs_details.job_id','',array('jobs.users_id' => $user_id,'job_accepted' => 1));

			// print_r($user_not_completed_Jobs);die;

			if (!empty($accepted_jobs))
			{
				$completed_job_arr = array();

				foreach ($accepted_jobs as $value) 
				{
					$completed_jobs = $this->common_model->getAllData('jobs_details','*',array('job_id' => $value->id));
				
					if (count($completed_jobs) > 0) 
					{
						array_push($completed_job_arr,$completed_jobs );
					}

				}

				$new_arr = array();

				foreach ($completed_job_arr as $arr) 
				{
    				$array = $arr[0]->job_id;
					
					array_push($new_arr,$array );
				}

				$where = array(
								'users_id' => $user_id
									 
							   );

				$user_completed_jobs = $this->common_model->getAllData('jobs','*',$where);

				$return_arr = array();

				for ($i=0; $i < count($new_arr); $i++) 
				{ 
					foreach ($user_completed_jobs as $item => $index) 
					{
						if ($index->id == $new_arr[$i]) 
						{
							unset($user_completed_jobs[$item]);
							$return_arr = $user_completed_jobs;
						}
					}
				}

				if (!empty($return_arr)) 
				{
					$jobs_accepted = array();

					foreach ($return_arr as $job) 
					{
						$all_job = array(
											'job_id'          => $job->id, 
											// 'first_name'      => $job->first_name, 
											// 'last_name'       => $job->last_name, 
											'users_id'        => $job->users_id, 
											'mission_title'   => $job->mission_title, 
											'description'     => $job->short_desc, 
											'latitude'        => $job->latitude, 
											'longitude'       => $job->longitude, 
											'image'           => $job->company_logo, 
											'company_name'    => $job->company_name, 
										 );

						array_push($jobs_accepted,$all_job );
					}

					if (!empty($jobs_accepted)) 
					{
						$response = array(
											'success'      => true,
											'status'       => 200,
											'message'   => "All User Accepted Jobs",
											'user_jobs'    => $jobs_accepted
								 		 );

						echo json_encode($response);
					}
					else
					{
						$response = array(
											'success'   => true,
											'status'    => 200,
											'message'   => "This user Have Completed All jobs",
								 		 );

						echo json_encode($response);
					}
				} 
				else
				{
					// $accepted_jobs = $this->common_model->DJoin('*','users','jobs','jobs.users_id = users.id','',array('jobs.users_id' => $user_id));

					
					// $jobs_accepted = array();


					// foreach ($accepted_jobs as $job) 
					// {
					// 	$all_job = array(
					// 						'job_id'          => $job->id, 
					// 						'first_name'      => $job->first_name, 
					// 						'last_name'       => $job->last_name, 
					// 						'users_id'        => $job->users_id, 
					// 						'mission_title'   => $job->mission_title, 
					// 						'description'     => $job->short_desc, 
					// 						'latitude'        => $job->latitude, 
					// 						'longitude'       => $job->longitude, 
					// 						'image'           => $job->company_logo, 
					// 						'company_name'    => $job->company_name, 
					// 					 );

					// 	array_push($jobs_accepted,$all_job );
					// }

					// $response = array(
					// 					'success'      => true,
					// 					'status'       => 200,
					// 					'message'   => "All User Accepted Jobs",
					// 					'user_jobs'    => $jobs_accepted
					// 		 		 );

					// echo json_encode($response);
					$response = array(
											'success'   => true,
											'status'    => 200,
											'message'   => "This user Have Completed All jobs",
								 		 );

						echo json_encode($response);
				}	

			}
			else
			{
				$response = array(
									'success' => false,
									'status'  => 401,
									'message' => 'This User ID Have no Jobs',
							 	 );

				echo json_encode($response);
			}
		}
		else
		{
			$response = array(
								'success' => false,
								'status'  => 401,
								'message' => 'Unauthorized Token',
							 );

			echo json_encode($response);
		}
	}

	


	/**
	 * [job_completed description]
	 * @return [type] [description]
	 */
	public function job_completed()
	{
		$api_token    = $this->input->post('api_token');
		$job_id       = $this->input->post('job_id');
		$comment      = $this->input->post('comment');
		$latitude     = $this->input->post('latitude');
		$longitude 	  = $this->input->post('longitude');
		$current_time = $this->input->post('current_time');

		$check_token = $this->common_model->getAllData('users','api_token',array('api_token' => $api_token));

		if (!empty($check_token)) 
		{

			$check_job = $this->common_model->getAllData('jobs','*',array('id' => $job_id));

			if (!empty($check_job)) 
			{

				$check_completed_job = $this->common_model->getAllData('jobs_details','*',array('job_id' => $job_id));

				if (empty($check_completed_job)) 
				{
					
					$datetime1 = new DateTime($check_job[0]->time);  //start time
					$datetime2 = new DateTime($current_time);   //end time

					$interval = $datetime1->diff($datetime2);

					//00 years 0 months 0 days 08 hours 0 minutes 0 seconds
					$check_time = $interval->format('%i minutes');
					
					if ($check_time > '10 minutes') 
					{
						$distance = $this->getDistance($latitude, $longitude, $check_job[0]->latitude, $check_job[0]->longitude);
						
						if ($distance <= 200) 
						{

							if (!empty($_FILES)) 
							{
								$data = array(
												'job_id' 		=> $job_id , 
												'comments' 		=> $comment, 
												'latitude' 		=> $latitude, 
												'longitude' 	=> $longitude , 
												'current_time' 	=> $current_time, 
											  );
								

								$this->common_model->InsertData('jobs_details',$data);
								

								$insert_id = $this->db->insert_id();


				                if (!empty($_FILES['img1']["name"]))  
								{
								    $image1 = $this->FileUpload('img1');

								    $data = array(
			                        				'job_details_id' => $insert_id, 
			                        				'images' 		 => base_url().'uploads/'. $image1, 
			                        			 );

                        			$this->common_model->InsertData('completed_job_multiple_images',$data);

								}

								if (!empty($_FILES['img2']["name"]))  
								{
								   $image2 = $this->FileUpload('img2');

								   $data = array(
			                        				'job_details_id' => $insert_id, 
			                        				'images' 		 => base_url().'uploads/'. $image2, 
			                        			 );

                        			$this->common_model->InsertData('completed_job_multiple_images',$data);

								}

								if (!empty($_FILES['img3']["name"]))  
								{
								   $image3 = $this->FileUpload('img3');

								   $data = array(
			                        				'job_details_id' => $insert_id, 
			                        				'images' 		 => base_url().'uploads/'. $image3, 
			                        			 );

                        			$this->common_model->InsertData('completed_job_multiple_images',$data);
								}

								if (!empty($_FILES['img4']["name"]))  
								{
								   $image4 = $this->FileUpload('img4');

								   $data = array(
			                        				'job_details_id' => $insert_id, 
			                        				'images' 		 => base_url().'uploads/'. $image4, 
			                        			 );

                        			$this->common_model->InsertData('completed_job_multiple_images',$data);
								}

								if (!empty($_FILES['img5']["name"]))  
								{
								   $image5 = $this->FileUpload('img5');

								    $data = array(
			                        				'job_details_id' => $insert_id, 
			                        				'images' 		 => base_url().'uploads/'. $image5, 
			                        			 );

                        			$this->common_model->InsertData('completed_job_multiple_images',$data);

								}


				                $response = array(
													'success' => true,
													'status'  => 200,
													'message' => 'Job Completed Successfully',
												 );

								echo json_encode($response);

							}
							else
							{
								$response = array(
													'success' => false,
													'status'  => 500,
													'message' => 'Please Select At least one File to Upload',
												 );

								echo json_encode($response);
							}

						}
							
						else
						{
							$response = array(
										'success' => false,
										'status'  => 406,
										'message' => 'Distance is out of 200 meter',
									 );

							echo json_encode($response);
						}
					}
					else
					{
						$response = array(
										'success' => false,
										'status'  => 406,
										'message' => 'Time Interval is Greater than 10 minutes',
									 );

						echo json_encode($response);
					}
				}
				else
				{
					$response = array(
									'success' => false,
									'status'  => 400,
									'message' => 'This job already completed',
								 );

					echo json_encode($response);
				}	
			}
			else
			{
				$response = array(
									'success' => false,
									'status'  => 401,
									'message' => 'Job id Not Found',
								 );

				echo json_encode($response);
			}
		}
		else
		{
			$response = array(
								'success' => false,
								'status'  => 401,
								'message' => 'Unauthorized Token',
							 );

			echo json_encode($response);
		}
	}

	/**
	 * [Get the Job Distance]
	 * @param  [decimal] $latitude1  
	 * @param  [decimal] $longitude1 
	 * @param  [decimal] $latitude2  
	 * @param  [decimal] $longitude2 
	 * @return [distance]             
	 */
	public function getDistance($latitude1, $longitude1, $latitude2, $longitude2) 
	{
    
	    $earth_radius = 6371000;
	 
	    $dLat = deg2rad($latitude2 - $latitude1);
	    $dLon = deg2rad($longitude2 - $longitude1);
	 
	    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
	    $c = 2 * asin(sqrt($a));
	    $d = $earth_radius * $c;
	 
	    return $d;
    
	}

	public function get_job_images()
	{
		$job_id = $this->input->post('job_id');

		if (!empty($job_id)) 
		{
			$check = $this->common_model->getAllData('job_images','*',array('job_id' => $job_id));

			if (!empty($check[0]->job_id)) 
			{
				$All_images = array();

				foreach ($check as $value) 
				{
					$images = array (
										'job_id' => $value->job_id,
										'images' => $value->images,
									 );

					array_push($All_images, $images);
				}

				$response = array(
								'success' => true,
								'status'  => 200,
								'message' => 'Job Images',
								'images'  => $All_images,
							 );

				echo json_encode($response);
				
			}
			else
			{
				$response = array(
								'success' => false,
								'status'  => 422,
								'message' => 'This Job Id has no images',
							 );

				echo json_encode($response);
			}
		}
		else
		{
			$response = array(
								'success' => false,
								'status'  => 401,
								'message' => 'Unauthorized Job ID',
							 );

			echo json_encode($response);
		}
	}

	public function jobs_details($value='')
	{
		$api_token = $this->input->post('api_token');
		$user_id   = $this->input->post('user_id');

		$check_token = $this->common_model->getAllData('users','api_token',array('api_token' => $api_token));

		if (!empty($check_token)) 
		{
			$check_user_id = $this->common_model->getAllData('jobs','users_id',array('users_id' => $user_id));

			if (!empty($check_user_id)) 
			{
				$jobs_details = $this->common_model->DJoin('*,jobs_details.id AS job_completed_id','jobs_details','jobs','jobs.id=jobs_details.job_id','',array('jobs.users_id' =>  $user_id ));


				$jobs_detail = array();

				foreach ($jobs_details as $job) 
				{
					$all_job = array(
							            'job_id' 			=> $job->job_id,
							            'job_completed_id'	=> $job->job_completed_id,
							            'comments' 			=> $job->comments,
							            'latitude' 			=> $job->latitude,
							            'longitude' 		=> $job->longitude,
							            'current_time' 		=> $job->current_time,
							            'users_id' 			=> $job->users_id,
							            'company_name' 		=> $job->company_name,
							            'mission_title' 	=> $job->mission_title,
							            'pay_per_job' 		=> $job->pay_per_job,
							            'short_desc' 		=> $job->short_desc,  
							            'company_logo' 		=> $job->company_logo,
							            'job_accepted' 		=> $job->job_accepted,
							            'date' 				=> $job->date,
							            'brief_desc' 		=> $job->brief_desc,
							            'time' 				=> $job->time,
									 );

					array_push($jobs_detail,$all_job );
				}


				if (!empty($jobs_detail)) 
				{

					$response = array(
										'success'      => true,
										'status'       => 200,
										'message'      => "Job Details",
										'Job_details'  => $jobs_detail
							 		 );

					echo json_encode($response);
				}
				else
				{
					$response = array(
										'success'      => false,
										'status'       => 401,
										'message'      => "This user id have no jobs",
							 		 );

					echo json_encode($response);
				}
			}
			else
			{
				$response = array(
									'success'      => false,
									'status'       => 401,
									'message'      => "User Id Not Found",
						 		 );

				echo json_encode($response);
			}	
		}
		else
		{
			$response = array(
								'success' => false,
								'status'  => 401,
								'message' => 'Unauthorized Token',
							 );

			echo json_encode($response);
		}
	}

	public function job_completed_images($value='')
	{
		$api_token = $this->input->post('api_token');

		$job_id = $this->input->post('job_completed_id');

		$check_token = $this->common_model->getAllData('users','api_token',array('api_token' => $api_token));

		if (!empty($check_token)) 
		{
			$check_job_id = $this->common_model->getAllData('completed_job_multiple_images','*',array('job_details_id' => $job_id));

			if (!empty($check_job_id)) 
			{
				$job_completed = array();

				foreach ($check_job_id as $job) 
				{
					$img_arr= array(
										'job_completed_images_id'    => $job->id, 
										'job_completed_id'     => $job->job_details_id, 
										'job_completed_images' => $job->images, 
									 );

					array_push($job_completed,$img_arr);
				}

				if (!empty($job_completed)) 
				{
					$response = array(
										'success'      => true,
										'status'       => 200,
										'message'   => "All User Accepted Jobs",
										'user_jobs'    => $job_completed
							 		 );

					echo json_encode($response);
				}
				else
				{
					$response = array(
										'success'   => true,
										'status'    => 200,
										'message'   => "This user Have Completed All jobs",
							 		 );

					echo json_encode($response);
				}
			}
			else
			{
				$response = array(
									'success'      => false,
									'status'       => 401,
									'message'      => "Job Completed ID Not Found",
						 		 );

				echo json_encode($response);
			}	
		}
		else
		{
			$response = array(
								'success' => false,
								'status'  => 401,
								'message' => 'Unauthorized Token',
							 );

			echo json_encode($response);
		}

	}
	private function FileUpload($filename)
	{
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		// $config['max_size']	= '10000';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload($filename))
		{
			$error = array('error' => $this->upload->display_errors());

			print_r($error);die;
		}
		else
		{	
			$data = array('upload_data' => $this->upload->data());
			// pr($data);
		    return $image_name = $data['upload_data']['file_name'];
		} 

	}
}

/* End of file Pos_apis.php */
/* Location: ./application/controllers/Pos_apis.php */