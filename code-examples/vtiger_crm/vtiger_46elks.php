<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class SMSNotifier_46elks_Provider implements SMSNotifier_ISMSProvider_Model {

	private $userName;
	private $password;
	private $parameters = array();

	const SERVICE_URI = 'https://api.46elks.com/a1';
	private static $REQUIRED_PARAMETERS = array(
		array('name'=>'46elks_from','label'=>'From','type'=>'text'),
		array('name'=>'46elks_cc','label'=>'Default Country',
		      'type' => 'picklist', 'picklistvalues' => 
		        array('SE' => 'SE', 'FI' => 'FI','NO' => 'NO','DE' => 'DE')),
		      );
		
	function __construct() {
		
	}

	/**
	 * Function to get provider name
	 * @return <String> provider name
	 */
	public function getName() {
		return '46elks';
	}

	/**
	 * Function to get required parameters other than (userName, password)
	 * @return <array> required parameters list
	 */
	public function getRequiredParams() {
		return self::$REQUIRED_PARAMETERS;
	}

	/**
	 * Function to get service URL to use for a given type
	 * @param <String> $type like SEND, PING, QUERY
	 */
	public function getServiceURL($type = false) {
		if($type) {
			switch(strtoupper($type)) {
				case self::SERVICE_AUTH: return  self::SERVICE_URI . '/me';
				case self::SERVICE_SEND: return  self::SERVICE_URI . '/sms';
				case self::SERVICE_QUERY: return self::SERVICE_URI . '/sms/';
			}
		}
		return false;
	}

	/**
	 * Function to set authentication parameters
	 * @param <String> $userName
	 * @param <String> $password
	 */
	public function setAuthParameters($userName, $password) {
		$this->userName = $userName;
		$this->password = $password;
	}

	/**
	 * Function to set non-auth parameter.
	 * @param <String> $key
	 * @param <String> $value
	 */
	public function setParameter($key, $value) {
		$this->parameters[$key] = $value;
	}

	/**
	 * Function to get parameter value
	 * @param <String> $key
	 * @param <String> $defaultValue
	 * @return <String> value/$default value
	 */
	public function getParameter($key, $defvalue = false) {
		if(isset($this->parameters[$key])) {
			return $this->parameters[$key];
		}
		return $defvalue;
	}

	/**
	 * Function to handle SMS Send operation
	 * @param <String> $message
	 * @param <Mixed> $toNumbers One or Array of numbers
	 */
	public function send($message, $toNumbers) {
		if(!is_array($toNumbers)) {
			$toNumbers = array($toNumbers);
		}

		$params = array();
		$params['from'] = $this->getParameter('46elks_from','elks');
		$params['message'] = html_entity_decode($message);
		$params['to'] = implode(',', $toNumbers);

		$serviceURL = $this->getServiceURL(self::SERVICE_SEND);
		$httpClient = new Vtiger_Net_Client($serviceURL);
		$httpClient->setHeaders(array('Authorization' => 'Basic '.base64_encode($this->userName.':'.$this->password)));
		$response = $httpClient->doPost($params);
		$response = json_decode($response, ture);
		
		if(isset($response['id'])){
			$response = array($response);
		}

		$results = array();
		$i = 0;
		foreach($response as $responseLine) {
			$result['id'] = $responseLine['id'];
			$result['to'] = cleanNumber($toNumbers[$i]);
			$result['status'] = self::MSG_STATUS_PROCESSING;
			$i++;
			$results[] = $result;
		}
		return $results;
	}

	/**
	 * Function to get query for status using messgae id
	 * @param <Number> $messageId
	 */
	public function query($messageId) {
		$serviceURL = $this->getServiceURL(self::SERVICE_QUERY);
		$httpClient = new Vtiger_Net_Client($serviceURL.$messageId);
		$httpClient->setHeaders(array('Authorization' => 'Basic '.base64_encode($this->userName.':'.$this->password)));
		$response = $httpClient->doGet();
		$response = json_decode($response, true);
		// Capture the status code as message by default.
		$sending = array('created','sent');
		$done = array('failed','delivered');
		if(in_array($response['status'], $sending)) {
			$result['status'] = self::MSG_STATUS_PROCESSING;
			$result['needlookup'] = 1;
		} else if(in_array($response['status'], $done)) {
			$result['status'] = self::MSG_STATUS_DISPATCHED;
			$result['needlookup'] = 0;
		}
		return $result;
	}
	
	private function cleanNumber($number) {
		$pattern = '/[^\+\d]/';
		$replacement = '';
		return preg_replace($pattern, $replacement, $number);
	}
}
?>
