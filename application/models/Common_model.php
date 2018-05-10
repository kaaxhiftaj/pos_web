<?php
/**
*
*/
class Common_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}
	function InsertData($table,$Data)
	{
		$Insert = $this->db->insert($table,$Data);
		if ($Insert):
			return true;
		endif;
	}
	function getAllData($table,$specific='',$Where='',$order='',$limit='',$groupBy='',$like = '')
	{
		// If Condition
		if (!empty($Where)):
			$this->db->where($Where);
		endif;
		// If Specific Columns are require
		if (!empty($specific)):
			$this->db->select($specific);
		else:
			$this->db->select('*');
		endif;

		if (!empty($groupBy)):
			$this->db->group_by($groupBy);
		endif;
		// if Order
		if (!empty($order)):
			$this->db->order_by($order);
		endif;
		// if limit
		if (!empty($limit)):
			$this->db->limit($limit);
		endif;

		//if like
		if(!empty($like)):
			$this->db->like('title', $like);
		endif;	
		// get Data
		$GetData = $this->db->get($table);
		return $GetData->result();
	}
	function UpdateDB($table,$Where,$Data)
	{
		$this->db->where($Where);
		$Update = $this->db->update($table,$Data);
		if ($Update):
			return true;
		else:
			return false;
		endif;
	}
	function Authentication($table,$data)
	{
		$this->db->where($data);
		
		$query = $this->db->get($table);

		if ($query) {
			return $query->row();
		}
		else
		{
			return false;
		}
	}
	function DJoin($field,$tbl,$jointbl1,$Joinone,$jointbl3='',$Where='',$order='',$groupy = '',$limit = '',$like = '')
    {
        $this->db->select($field);
        $this->db->from($tbl);
        $this->db->join($jointbl1,$Joinone);
        if (!empty($jointbl3)):
            foreach ($jointbl3 as $Table => $On):
                $this->db->join($Table,$On);
            endforeach;
        endif;
        // if Group
		if (!empty($groupy)):
			$this->db->group_by($groupy);
		endif;
        if(!empty($order)):
            $this->db->order_by($order);
        endif;
        if(!empty($Where)):
            $this->db->where($Where);
        endif;
        if(!empty($limit)):
            $this->db->limit($limit);
        endif;
        
        if(!empty($like)):
            $this->db->like('title', $like);
        endif;

        $query=$this->db->get();
        return $query->result();
    }
    function DeleteDB($table,$where)
    {
    	$this->db->where($where);
    	$done = $this->db->delete($table);
    	if ($done) {
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }

	function Encode_html($str) {
    return trim(stripslashes(htmlentities($str)));
	}

	function Encode($str) {
	    return trim(  htmlentities( $str, ENT_QUOTES ) ) ;
	}

	function Decode($str) {
	    return html_entity_decode(stripslashes($str));
	}

	function Encrypt($password) {
	    return crypt(md5($password), md5($password));
	}

	function fileUpload($param,$temp,$location)
	{
	  	$allow_ext = array("png","jpg","jpeg","gif");
        $uploadPath = 'assets/uploads/'.$location.'/';
        $FileReturn = '';
		if(!empty($param))
        {
            if($param !=''){
				$Ext = end(explode(".", $param));
                $ext = strtolower($Ext);
		        $temps = explode(".",$param);
				$newfilename = rand(1,100).date("Yis").round(microtime(true)) . '.' . end($temps);
                if(in_array($ext, $allow_ext)){
                    move_uploaded_file($temp,$uploadPath.$newfilename);
                    $FileReturn = $newfilename;
                    return $FileReturn;
                }
                else{
                    $data['error_msg'] = "Please upload valid File";
                }
            }
        }
	}
	function fileUploads($param,$temp,$location)
	{
        $uploadPath = 'assets/uploads/'.$location.'/';
        $FileReturn = '';
				if(!empty($param))
        {
            if($param !=''){
							$Ext = end(explode(".", $param));
                $ext = strtolower($Ext);
		        $temps = explode(".",$param);
						$newfilename = rand(1,100).date("Yis").round(microtime(true)) . '.' . end($temps);
          move_uploaded_file($temp,$uploadPath.$newfilename);
          $FileReturn = $newfilename;
          return $FileReturn;
            }
        }
	}
	public function socail_login($data,$username,$email,$table)
	{
	  $this->db->where('email',$email);
	  $this->db->limit(1);
	  $users = $this->db->count_all_results($table);

	  if(!isset($users) || $users < 1)
	  {
	    $this->load->helper('string');

	    $password = random_string('alnum',10); // we create a random password for the user...

	    $register_id = $this->ion_auth->register($username,$password,$email,$data,array('2'));

	    // pr($register_id);die();

	    if($register_id)
	    {
	      $this->ion_auth->activate($register_id);
	      $this->ion_auth->login($email,$password, TRUE);
	    }
	  }
	  else
	  {
	    $user = $this->db->where(array('email'=>$email))->limit(1)->get($table)->row();

	    $sess_data = array('identity' => $user->username, 
	    				   'username' => $user->username,
	    				   'email'    => $user->email,
	    				   'user_id'  => $user->id,
	    				   'old_last_login' => $user->last_login);

	    $this->session->set_userdata($sess_data);
	  }
	  return TRUE;
	}
}
?>
