<?php

class WinmedAPI {

    public $curl = null;
    public $curlarray = null;
    public $curlopturl = null;

    function __construct($lab_no, $nb_no, $pdf_path, $txt_path) {
        
        $this->curl = curl_init();
        
        $this->curlopturl = 'https://cytology-api.winnergy.co.th/result-nblab/upload/lst9rnd85tjy/colposcopy/' . $lab_no . '/' . $nb_no;

        $this->curlarray = array(
            CURLOPT_URL => $this->curlopturl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('upload_file_pdf' => new CURLFILE($pdf_path), 'upload_file_txt' => new CURLFILE($txt_path)),
        );
        curl_setopt_array($this->curl, $this->curlarray);
    }

    function __destruct() {
       curl_close($this->curl);
    }
    
    public function getcurlarray(){
        return $this->curlarray;
        
    }

    public function sentAPI() {
        $response = curl_exec($this->curl);
        return $response;

    }

}
