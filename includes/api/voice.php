<?php declare ( strict_types = 1 );

namespace DDMS\API;

class Voice
{
 
    public static function getAudioTranscription()
    {

        // $url = \Fotki\Fields::get_text_by_name('url');
        $dock = \Fotki\Fields::post_text_by_name('dock');
        
        // $file = $_FILES["audioFile"]["tmp_name"];
        $file = $_FILES["audioFile"];

        // var_dump($file);
        // echo ( mime_content_type($file['tmp_name']) );
        // die();

        if ( empty( $file ) ) {
            \Fotki\Response::json( [
                'ok' => false,
                'error' => "audio file is required"
            ] );
        }

        $res = '';

        try {
            $res = \DDMS\Entities\Voice::getAudioTranscription($file, $dock);
        } catch ( \Exception $e ) {
            \Fotki\Response::json( [
                'ok' => false,
                'error' => $e->getMessage(),
            ] );
        }

        \Fotki\Response::json( [
            'ok' => true,
            'text' => $res->DisplayText
        ] );
    }


}