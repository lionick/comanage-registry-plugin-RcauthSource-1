<?php
App::uses('HttpSocket', 'Network/Http');
class mpCfgUrl {
  private static $AUTH_ENDPOINT = 'authorization_endpoint';
  private static $REGISTER_ENDPOINT = 'registration_endpoint';
  private static $JWKS_URI = 'jwks_uri';
  private static $ISSUER = 'issuer';
  private static $TOKEN_ENDPOINT = 'token_endpoint';
  private static $USERINFO_ENDPOINT = 'userinfo_endpoint';
  private static $TOKEN_ENDPOINT_AM_SUP = 'token_endpoint_auth_methods_supported';
  private static $SUBJECT_TYP_SUP = 'subject_types_supported';
  private static $SCOPES_SUP = 'scopes_supported';
  private static $RESPONSE_TYP_SUP = 'response_types_supported';
  private static $CLAIMS_SUP = 'claims_supported';
  private static $ID_TOKEN_SIG_ALG_VAL_SUP = 'id_token_signing_alg_values_supported';

  private $authorizationEndpoint;
  private $registrationEndpoint;
  private $jwksUri;
  private $issuer;
  private $tokenEndpoint;
  private $userinfoEndpoint;
  private $tokenEndpointAuthMethodsSupported = array();
  private $subjectTypesSupported = array();
  private $scopesSupported = array();
  private $responseTypesSupported = array();
  private $claimsSupported = array();
  private $idTokenSigningAlgValuesSupported = array();
  private $httpClient = null;

  // parse json and fill the variables
  function __construct($url){
    // if the url variable has not been defined then do nothing
    if(isset($url) and $url != "") {
      $response = $this->httpClient()->get($url, array(), array());
      if($response->isOk()) {
        $openIdCfgJson = json_decode($response);
        $this->authorizationEndpoint = $openIdCfgJson->{self::$AUTH_ENDPOINT};
        $this->registrationEndpoint = $openIdCfgJson->{self::$REGISTER_ENDPOINT};
        $this->jwksUri = $openIdCfgJson->{self::$JWKS_URI};
        $this->issuer = $openIdCfgJson->{self::$ISSUER};
        $this->tokenEndpoint = $openIdCfgJson->{self::$TOKEN_ENDPOINT};
        $this->userinfoEndpoint = $openIdCfgJson->{self::$USERINFO_ENDPOINT};

        // token_endpoint_auth_methods_supported
        foreach ($openIdCfgJson->{self::$TOKEN_ENDPOINT_AM_SUP} as $data) {
          $this->tokenEndpointAuthMethodsSupported[] = $data;
        }

        // subject_types_supported
        foreach ($openIdCfgJson->{self::$SUBJECT_TYP_SUP} as $data) {
          $this->subjectTypesSupported[] = $data;
        }

        // scopes_supported
        foreach ($openIdCfgJson->{self::$SCOPES_SUP} as $data) {
          $this->scopesSupported[] = $data;
        }

        // response_types_supported
        foreach ($openIdCfgJson->{self::$RESPONSE_TYP_SUP} as $data) {
          $this->responseTypesSupported[] = $data;
        }

        // claims_supported
        foreach ($openIdCfgJson->{self::$CLAIMS_SUP} as $data) {
          $this->claimsSupported[] = $data;
        }

        // id_token_signing_alg_values_supported
        foreach ($openIdCfgJson->{self::$ID_TOKEN_SIG_ALG_VAL_SUP} as $data) {
          $this->idTokenSigningAlgValuesSupported[] = $data;
        }
      }
      else {
        $error = json_decode($response->body);
        // There should be an error in the response
        $ret_msg = $error->error . '(' . $response->code . ')';
        throw new RuntimeException(_txt('er.rcauthsource.mpCfgUrl', $ret_msg));
      }
    }
  }

  public function httpClient () {
    if($this->httpClient === null) {
      $this->httpClient = new HttpSocket(array(
        'timeout' => 3
     ));
    }
    return $this->httpClient;
  }

  /**
   * Get the value of authorizationEndpoint
   */
  public function getAuthorizationEndpoint()
  {
    return $this->authorizationEndpoint;
  }

  /**
   * Get the value of registrationEndpoint
   */
  public function getRegistrationEndpoint()
  {
    return $this->registrationEndpoint;
  }

  /**
   * Get the value of jwksUri
   */
  public function getJwksUri()
  {
    return $this->jwksUri;
  }

  /**
   * Get the value of scopesSupported
   */
  public function getScopesSupported()
  {
    return $this->scopesSupported;
  }

  /**
   * Get the value of issuer
   */
  public function getIssuer()
  {
    return $this->issuer;
  }

  /**
   * Get the value of tokenEndpoint
   */
  public function getTokenEndpoint()
  {
    return $this->tokenEndpoint;
  }

  /**
   * Get the value of userinfoEndpoint
   */
  public function getUserinfoEndpoint()
  {
    return $this->userinfoEndpoint;
  }

  /**
   * Get the value of tokenEndpointAuthMethodsSupported
   */
  public function getTokenEndpointAuthMethodsSupported()
  {
    return $this->tokenEndpointAuthMethodsSupported;
  }

  /**
   * Get the value of subjectTypesSupported
   */
  public function getSubjectTypesSupported()
  {
    return $this->subjectTypesSupported;
  }

  /**
   * Get the value of responseTypesSupported
   */
  public function getResponseTypesSupported()
  {
    return $this->responseTypesSupported;
  }

  /**
   * Get the value of claimsSupported
   */
  public function getClaimsSupported()
  {
    return $this->claimsSupported;
  }

  /**
   * Get the value of idTokenSigningAlgValuesSupported
   */
  public function getIdTokenSigningAlgValuesSupported()
  {
    return $this->idTokenSigningAlgValuesSupported;
  }

  public function printOpenIdCfg(){
    print $this->getAuthorizationEndpoint()."\n";
    print $this->getRegistrationEndpoint()."\n";
    print $this->getJwksUri()."\n";
    print $this->getIssuer()."\n";
    print $this->getTokenEndpoint()."\n";
    print $this->getUserinfoEndpoint()."\n";
    print_r($this->getTokenEndpointAuthMethodsSupported());
    print_r($this->getSubjectTypesSupported());
    print_r($this->getScopesSupported());
    print_r($this->getResponseTypesSupported());
    print_r($this->getClaimsSupported());
    print_r($this->getIdTokenSigningAlgValuesSupported());
  }
}
