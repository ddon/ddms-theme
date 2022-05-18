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

        // $file structure example 
        // array(5) { 
        //     ["name"]=> string(27) "ddms-test-voice-record2.wav"
        //     ["type"]=> string(11) "audio/x-wav"
        //     ["tmp_name"]=> string(14) "/tmp/phpyuZoqH"
        //     ["error"]=> int(0)
        //     ["size"]=> int(1851436)
        // } 

        if ( empty ($file) ) {
            throw new Exception( "File is required" );
        } else {
            $supported_types = ["audio/x-wav", "audio/wav", "audio/ogg"];

            if ( ! in_array( $file["type"], $supported_types) ) {
                throw new Exception( "Unsupported file type. wav and ogg audio files only" );
            }

            // Check file size
            if ( $file["size"] > 5000000 ) {
                throw new Exception( "Sorry, your file more than 5MB" );
            }
        }

        $file_type = $file["type"];
        $file_ext = '';

        // trying to generate file extension dinamicaly, based on $file_type
        if ( ! empty ($file_type) && strpos($file_type, "audio/") !== false) {
            $file_ext = str_replace("audio/","" , $file_type);
        }
        
        // trying to generate directory name to sort audio files.
        $dock_names = [ 15 => 'dock34', 16 => 'dock2', 17 => 'dock3'];
        if ( empty ( $dock ) || ! array_key_exists( $dock, $dock_names ) ) {
            $dock = "dockNull";
        } else {
            $dock = $dock_names[$dock];
        }
     
        //cunstructing new file name and path
        $date = date("Y-m-d-His");
        $target_dir = get_template_directory() . "/voice-records/" . $dock . "/";
        $target_filename = $date . "-" . $dock;
        $target_file_path = $target_dir . $target_filename . ".$file_ext";

        $temp_file_path = $file["tmp_name"];

        // error_log(var_export($temp_file_path, true));
        
        $original_audio = '';

        //moving temporary file to a new location, converting it to correct format suitable for azure
        if ( move_uploaded_file( $temp_file_path, $target_file_path ) ) {
            $original_audio = $target_file_path;

            $converted_audio_filename = $date . "-" . $dock . "-FFMPEG.wav";
            $converted_audio = $target_dir . $converted_audio_filename;
            exec( "ffmpeg -i $original_audio $converted_audio" );

            //removig original file if converted ffpmeg exists
            if ( file_exists( $converted_audio ) ) {
                // error_log( "\ndeleting original file...\n" );
                unlink( $original_audio );
            }
        } else {
            throw new Exception( "Sorry, there was an error moving temp file." );
        }

        $file_content = file_get_contents( $converted_audio );

        if ( empty ( $file_content ) ) {
            throw new Exception( "Couldn't read the file. No such file or directory." );
        }
    
        //sending file content to azure for speech to text transcription
        $header = [
            "Ocp-Apim-Subscription-Key: $key",
            "Content-Type: $file_type",
            "Accept: application/json"
        ];

        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $azure_url );
        curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt( $curl, CURLOPT_HTTPHEADER, $header );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $file_content );

        $response = curl_exec($curl);
        curl_close($curl);

        // analyzing response from azure
        if (! empty ($response) ) {

            $json_response = json_decode($response);
            $voice_text = $json_response->DisplayText;

            // saving original azure response to log file
            $log_file = fopen( $target_dir . $target_filename . ".json", 'w' );
            fwrite($log_file, $response);
            fclose($log_file);

            // error if no transcription text found in the response from azure
            if ( empty( $voice_text ) ) {
                throw new Exception( $response );
            }

            return $json_response;

        } else {
            throw new Exception( "Couldn't get transcription!" );
        }

    }
}
