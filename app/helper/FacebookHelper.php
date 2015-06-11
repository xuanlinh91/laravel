<?php

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequestException;
use Facebook\FacebookRequest;

class FacebookHelper {

	private $helper;
	private $share;
	private $session_login;
	private $session_share;

	public function __construct(){
		FacebookSession::setDefaultApplication(Config::get('facebook')['app_Id'],Config::get('facebook')['app_secret']);

		$this->helper = new FacebookRedirectLoginHelper(url('/login/fb/callback'));
		$this->share = new FacebookRedirectLoginHelper(url('/shareFbCallback'));
	} 

	public function getUrlLogin () {
		return $this->helper->getLoginUrl(Config::get('facebook')['app_scope']);
	}

	public function getUrlShare() {

		return $this->share->getLoginUrl(Config::get('facebook')['app_scope']);
	}

	public function ganeraSessionFromToken($token) {
		$this->session_login = new FacebookSession($token);
		return $this->session_login;
	}
	public function ganeraSessionFromRedirect() {
		$this->session_login = null;
		try {
			$this->session_login = $this->helper->getSessionFromRedirect();
		} catch(FacebookRequestException $ex) {

		} catch(\Exception $ex) {
		
		}
		return $this->session_login;
	}

	public function ganeraSessionFromRedirectShare() {
		$this->session_share = null;
		
			$this->session_share = $this->share->getSessionFromRedirect();
		return $this->session_share;
	}
	

	public function getGraph() {
		$request = new FacebookRequest($this->session_login,'GET','/me');
		$response = $request->execute();
		return $response->getGraphObject();
	}

	public function share_facebook($link,$msg) {
		try {
			$response = (new FacebookRequest(
		      $this->session_share, 'POST', '/me/feed', array(
		        'link' => $link,
		        'message' => $msg,
		        'image'=>''
		      )
		    ))->execute()->getGraphObject();
		} catch(FacebookRequestException $ex) {

		} catch(\Exception $ex) {
		
		}
	}
} 