<?php
/**
 * Transcribes a video file by converting it into an mp3 file first
 * with ffmpeg and then transcribing the audio with OpenAI Audio API
 * 
 * @param string $video_file The video file to transcribe
 * @param string $api_key OpenAI API-key
 * 
 * @return string Transcribed text
 */
function transcribe( string $video_file, string $api_key ): string {
    $audio_file = "audio/" . basename( $video_file ) . ".mp3";

    if( ! file_exists( $audio_file ) ) {
        exec( "ffmpeg -i " . escapeshellarg( $video_file ) . " -b:a 32k " . escapeshellarg( $audio_file.".part.mp3" ) . " >/dev/null 2>/dev/null" );
        rename( $audio_file.".part.mp3", $audio_file );
    }
    
    $ch = curl_init( "https://api.openai.com/v1/audio/transcriptions" );
    
    $file = curl_file_create( $audio_file, "audio/mp3", "audio.mp3" );
    
    curl_setopt_array( $ch, [
        CURLOPT_HTTPHEADER => [
            "Content-type: multipart/form-data",
            "Authorization: Bearer $api_key"
        ],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            "file" => $file,
            "model" => "whisper-1",
            "language" => "en",
            "response_format" => "text",
        ],
        CURLOPT_RETURNTRANSFER => true,
    ] );
    
    $response = curl_exec( $ch );
    
    return $response;
}

