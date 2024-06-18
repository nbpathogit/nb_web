<?php

class WinmedAPI
{
    public static function sentAPI($lab_no, $nb_no, $pdf_path, $txt_path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://cytology-api.winnergy.co.th/result-nblab/upload/lst9rnd85tjy/colposcopy/' . $lab_no . '/' . $nb_no,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('upload_file_pdf' => new CURLFILE($pdf_path), 'upload_file_txt' => new CURLFILE($txt_path)),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        // echo $response;

        return $response;
        // var_dump($response);
    }
}