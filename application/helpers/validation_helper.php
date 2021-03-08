<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// FIXME tazkira_id duplication
if ( ! function_exists('tazkira_id_validation_on_create'))
{
	function tazkira_id_validation_on_create($tazkira_id){
		$ci=& get_instance();
		$num_rows = 0;
		$user_array = array('hr');
		$size = sizeof($user_array);

		for($i = 0; $i < $size; $i++){
			$ci->db->where('tazkira_id', $tazkira_id);
			$num_rows = $ci->db->get($user_array[$i])->num_rows();
			if($num_rows > 0){
				return 0;
			}
		}
		return 1;
	}
}

if ( ! function_exists('tazkira_id_validation_on_edit')){
	function tazkira_id_validation_on_edit($tazkira_id, $id, $type){
		$num_rows = 0;
		$ci=& get_instance();
		$user_array = array('hr');
		$size = sizeof($user_array);
		for($i = 0; $i < $size; $i++){
			if($type == $user_array[$i]){
				$ci->db->where_not_in($user_array[$i].'_id', $id);
				$ci->db->where('tazkira_id', $tazkira_id);
				$num_rows = $ci->db->get($user_array[$i])->num_rows();
				if($num_rows > 0){
					return 0;
				}
			}
			else{
				$ci->db->where('tazkira_id', $tazkira_id);
				$num_rows = $ci->db->get($user_array[$i])->num_rows();
				if($num_rows > 0){
					return 0;
				}
			}
		}
		return 1;
	}
}
// email
if ( ! function_exists('email_validation_on_create'))
{
	function email_validation_on_create($email){
		$ci=& get_instance();
		$num_rows = 0;
		$user_array = array('admin', 'hr');
		$size = sizeof($user_array);

		for($i = 0; $i < $size; $i++){
			$ci->db->where('email', $email);
			$num_rows = $ci->db->get($user_array[$i])->num_rows();
			if($num_rows > 0){
				return 0;
			}
		}
		return 1;
	}
}


if ( ! function_exists('email_validation_on_edit')){
	function email_validation_on_edit($email, $id, $type){
		$num_rows = 0;
		$ci=& get_instance();
		$user_array = array('admin', 'hr');
		$size = sizeof($user_array);
		for($i = 0; $i < $size; $i++){
			if($type == $user_array[$i]){
				$ci->db->where_not_in($user_array[$i].'_id', $id);
				$ci->db->where('email', $email);
				$num_rows = $ci->db->get($user_array[$i])->num_rows();
				if($num_rows > 0){
					return 0;
				}
			}
			else{
				$ci->db->where('email', $email);
				$num_rows = $ci->db->get($user_array[$i])->num_rows();
				if($num_rows > 0){
					return 0;
				}
			}
		}
		return 1;
	}
}

if (!function_exists('null_checking')) {
	function null_checking($data){
		$returned_array = array();
		$key_array      = array_keys($data);
		$size 		      = sizeof($key_array);
		for ($i = 0; $i < $size; $i++) {
			if (!empty($data[$key_array[$i]])) {
				$returned_array[$key_array[$i]] = $data[$key_array[$i]];
			}
			else{
				$returned_array[$key_array[$i]] = null;
			}
		}
		return $returned_array;
	}
}

