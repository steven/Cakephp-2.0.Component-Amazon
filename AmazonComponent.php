<?php
/**
 * --- Amazon S3 Component ---
 * Github: https://github.com/steven/Cakephp-2.0.Component-Amazon
 * Connects to Amazons AWS S3 servers using Amazons PHP SDK
 * Requirements: Amazon AWS S3 account
 *
 * @author Steven Thompson <steven@fantasmagorical.co.uk>
 */
 
require_once 'amazon/sdk.class.php';
// http://docs.amazonwebservices.com/AWSSDKforPHP/latest/#i=AmazonS3
App::uses('Sanitize', 'Utility');
App::uses('CakeSession', 'Model/Datasource');

class AmazonComponent extends Component{	
	
	var $bucket = 'BUCKETNAME';
	
	// Create an instance
	function __construct(){
		$this->s3 = new AmazonS3();
	}
	
	// Uploads to S3 by using a standard HTML POST data array
	// generated a unique filename if exists based
	// Private or public ACL's can be used by simply passing a true flag to lock down your object
	function upload($data, $private = false){
		if(is_array($data)){
			if($data['error'] == 0){
				$filename = $data['name'];
				$file = $data['tmp_name'];
				if($this->exists($filename)){
					$filename = date("YmdHis").'_'.$filename;
				}
				if($private){
					$response = $this->s3->create_object($this->bucket, $filename, array(
					'fileUpload' => $file,
					'acl' => AmazonS3::ACL_PRIVATE
					));
				} else {
						$response = $this->s3->create_object($this->bucket, $filename, array(
						'fileUpload' => $file,
						'acl' => AmazonS3::ACL_PUBLIC
					));
				}
				if ($response->isOK()){
					return $filename;
				}
			}
			return false;
		} else {
			return $data;
		}
	}
			
	// Delete the object
	function delete($location){
		$response = $this->s3->delete_object($this->bucket, $location);
		if($response->isOK()){
			return true;
		} 
		return false;
	}
	
	// Check if the object exists
	function exists($location){
		return $this->s3->if_object_exists($this->bucket, $location);
	}
	
	// Download the file to the apps TMP folder
	function download($location, $filename){
		$response = $this->s3->get_object($this->bucket, $location, array('fileDownload' => TMP . $filename));
		if($response->isOK()){
			return true;
		} 
		return false;
	}
	
	// Read the file with secure access for 1 minute 
	// For example reading a PDF in the browser
	function read($location){
		return $this->s3->get_object_url($this->bucket, $location, strtotime('+1 minute'), array('https' => true));
	}
}