<?php declare ( strict_types = 1 );

namespace DDMS\Entities;

use Exception;

class Voice extends BaseEntity
{

    /**
     *
     * @return object
     * @throws Exception if file is empty
     */
    
    public static function getAudioTranscription( $file, $dock = '' ) {

        $language = 'ru-RU';
        $key= "220ddb56efe64c568ba753e3bb51a569";
        $azure_url = "https://northeurope.stt.speech.microsoft.com/speech/recognition/conversation/cognitiveservices/v1?language=" . $language;
        // $azure_url = "https://northeurope.api.cognitive.microsoft.com/sts/v1.0/issuetoken?language=" . $language;
        $curl = curl_init();

        if ( empty ($file) ) {
            throw new Exception( "File is required" );
        } else {
            // array(5) { 
            //     ["name"]=> string(27) "ddms-test-voice-record2.wav"
            //     ["type"]=> string(11) "audio/x-wav"
            //     ["tmp_name"]=> string(14) "/tmp/phpyuZoqH"
            //     ["error"]=> int(0)
            //     ["size"]=> int(1851436)
            // } 

            if ( $file["type"] !== "audio/x-wav" ) {
                throw new Exception( "Unsupported file type. Accept audio/x-wav only" );
            }

            // Check file size
            if ($file["size"] > 5000000) {
                echo "Sorry, your file more than 5MB";
            }

        }

        $date = date("Y-m-d-His");

        if ( empty ($dock) ) {
            $dock = "dockNull";
        } 

        $target_dir = get_template_directory() . "/voice-records/" . $dock . "/";
        $target_filename = $date . "-" . $dock . "-audio";
        $target_file = $target_dir . $target_filename . ".wav";

        $audioFile = '';

        if ( move_uploaded_file($file["tmp_name"], $target_file) ) {
            // echo "The file ". htmlspecialchars( basename( $file["name"])). " has been uploaded.";
            $audioFile = $target_file;
        } else {
            throw new Exception( "Sorry, there was an error uploading your file." );
        }

        $file_content = file_get_contents($audioFile);

        if ( empty ($file_content) ) {
            throw new Exception( "Couldn't read the file. No such file or directory." );
        }

        $header = [
            "Ocp-Apim-Subscription-Key: $key",
            "Content-Type: audio/wav",
            "Accept: application/json"
        ];

        curl_setopt($curl, CURLOPT_URL, $azure_url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $file_content);

        $response = curl_exec($curl);
        curl_close($curl);

        // error_log($response);

        if (! empty ($response) ) {
            $voice_json = json_decode($response);
            $voice_text = $voice_json->DisplayText;

            // $txt = "Date: $date\nAudio File: $target_filename\nText: $voice_text\nDock: $dock\n\n";
            $log_file = fopen( $target_dir . $target_filename . ".json", 'w' );

            fwrite($log_file, $response);
            fclose($log_file);

        } else {
            throw new Exception( "Couldn't get transcription!" );
        }
        
        return json_decode($response);
    }
}
