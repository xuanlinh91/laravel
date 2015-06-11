@extends ("main")
@section ('title')
<title>Shaphira</title>
@endsection
@section('content')
<?php
$client_id              = '1021297808134-or5shn9n3gda203o0ldlaf2s45qr8ev3.apps.googleusercontent.com';
$service_account_name   = '1021297808134-or5shn9n3gda203o0ldlaf2s45qr8ev3@developer.gserviceaccount.com';
$key_file_location      ='/var/www/laravel/app/views/admin/AWS-Gutlo-Tokyo-5f05c57f388e.p12';

$client = new Google_Client();
$client->setApplicationName("Client_Library_Examples");

if (isset($_SESSION['service_token'])) {
$client->setAccessToken($_SESSION['service_token']);
}

$key = file_get_contents($key_file_location);
$cred = new Google_Auth_AssertionCredentials(
$service_account_name,
array('https://www.googleapis.com/auth/analytics'),
$key
);

$client->setAssertionCredentials($cred);
if($client->getAuth()->isAccessTokenExpired()) {
$client->getAuth()->refreshTokenWithAssertion($cred);
} 

$_SESSION['service_token'] = $client->getAccessToken();
echo $_SESSION['service_token'];
$service = new Google_Service_Analytics($client);


/**
 * 1.Create and Execute a Real Time Report
 * An application can request real-time data by calling the get method on the Analytics service object.
 * The method requires an ids parameter which specifies from which view (profile) to retrieve data.
 * For example, the following code requests real-time data for view (profile) ID 56789.
 */
$optParams = array(
    'dimensions' => 'rt:pagePath');
$results= array();
try {
  $results = $service->data_realtime->get(
      'ga:101458898',
      'rt:pageviews',
      $optParams);
  // Success.
} catch (apiServiceException $e) {
  // Handle API service exceptions.
  $error = $e->getMessage();
}


$optParams = array(
    'dimensions' => 'rt:latitude,rt:longitude');
$results2= array();
try {
  $results2 = $service->data_realtime->get(
      'ga:101458898',
      'rt:activeUsers',
      $optParams);
  // Success.
} catch (apiServiceException $e) {
  // Handle API service exceptions.
  $error = $e->getMessage();
}
?>
<style type="text/css">
  .abc {
    padding: 15px;
    border-bottom: 1px solid #ccc;
  }
</style>
@if(!empty($results->rows))
@foreach ($results->rows as $n)
<div class="abc">
  <div>path: {{$n[0]}}</div>
  <div>pageviews: {{$n[1]}}</div>
</div>
@endforeach
@endif

@if(!empty($results2->rows))
@foreach ($results2->rows as $n)
<div class="abc">
  <div>la: {{$n[0]}}</div>
  <div>long: {{$n[1]}}</div>

</div>
@endforeach
@endif
@endsection