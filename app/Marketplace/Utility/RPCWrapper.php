<?php

namespace App\Marketplace\Utility;

class RPCWrapper
{
    // Configuration options
    private string $proto;
    private string $host;
    private int $port;
    private string $url;
    private $CACertificate;

    // Information and debugging
    public $status;
    public $error;
    public $raw_response;
    public $response;

    private $id = 0;
    public function __construct(private string $username, private string $password, $host = 'localhost', $port=8332,$url=null)
    {
        $this->host = $host;
        $this->port = $port;
        $this->url =$url;

        // Set some defaults
        $this->proto         = 'http';
        $this->CACertificate = null;
    }

    public function setSSL($certificate = null): void
    {
        $this->proto         = 'https'; // force HTTPS
        $this->CACertificate = $certificate;
    }

    public function __call($method, $params)
    {
        $this->status = null;
        $this->error = null;
        $this->raw_response = null;
        $this->response = null;

        // if no parameters passed this will be an empty array
        $params = array_values($params);

        //the id should be unique for each call
        $this->id++;

        //build the request, it,s ok that params might have any empt array

        $request = json_encode(array(
            'method' => $method,
            'params' => $params,
            'id'     => $this->id
        ));

        $curl = curl_init("{$this->proto}://{$this->host}:{$this->port}/{$this->url}");
        $options = array(
            CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
            CURLOPT_USERPWD        => $this->username . ':' . $this->password,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_HTTPHEADER     => array('Content-type: application/json'),
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $request
        );

        // This prevents users from getting the following warning when open_basedir is set:
        // Warning: curl_setopt() [function.curl-setopt]:
        //   CURLOPT_FOLLOWLOCATION cannot be activated when in safe_mode or an open_basedir is set

        if (ini_get('open_basedir')){
            unset($options[CURLOPT_FOLLOWLOCATION]);
        }
        if ($this->proto =='https'){
            // If the CA Certificate was specified we change CURL to look for it

            if (!empty($this->CACertificate))
            {
                $options[CURLOPT_CAINFO] = $this->CACertificate;
                $options[CURLOPT_CAPATH] = DIRNAME($this->CACertificate);
            }else {
                // If not we need to assume the SSL cannot be verified
                // so we set this flag FALSE to allow the connection

                $options[CURLOPT_SSL_VERIFYPEER] = false;
            }
        }

        curl_setopt_array($curl,$options);
        // Execute the request and decode to an array

        $this->raw_response = curl_exec($curl);
        $this->response     =json_decode($this->raw_response,true);

        // If the status is not 200, something is wrong
        $this->status = curl_getinfo($curl,CURLINFO_HTTP_CODE);

        // If there was no error, this will be an empty string
        $curl_error = curl_error($curl);
        curl_close($curl);

        if (!empty($curl_error))
        {
            $this->error = $curl_error;
        }

        if ($this->response['error'])
        {
            // If bitcoind returned an error, put that in $this->error
            $this->error = $this->response['error']['message'];
        }elseif ($this->status !=200)
        {
            // If bitcoind didn't return a nice error message, we need to make our own
            switch ($this->status) {
                case 400:
                    $this->error = 'HTTP_BAD_REQUEST';
                    break;
                case 401:
                    $this->error = 'HTTP_UNAUTHORIZED';
                    break;
                case 403:
                    $this->error = 'HTTP_FORBIDDEN';
                    break;
                case 404:
                    $this->error = 'HTTP_NOT_FOUND';
                    break;
            }
        }
        if ($this->error)
        {
            return false;
        }
        return $this->response['result'];
    }
}
