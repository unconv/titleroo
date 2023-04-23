<?php
/**
 * This script creates a video title, description,
 * thumbnail image description and a thumbnail image
 * for the given video file
 * 
 * Usage: titleroo.php VIDEO_FILE
 */

if( $argc < 2 ) {
    echo "Usage: " . $argv[0] . " VIDEO_FILE\n";
    exit;
}

$video_file = $argv[1];

$settings = require( "settings.php" );
$api_key = $settings['api_key'];

require_once( __DIR__ . "/src/splitter.php" );
require_once( __DIR__ . "/src/chatgpt.php" );
require_once( __DIR__ . "/src/whisper.php" );
require_once( __DIR__ . "/src/summarizer.php" );
require_once( __DIR__ . "/src/image.php" );

echo "Transcribing audio...\n";
$transcript = transcribe( $video_file, $api_key );

echo "Summarizing transcription...\n";
$summary = summarize( $transcript, $api_key );

$title_prompt =
"### START SUMMARY ###
".$summary."
### END SUMMARY ###
The above is a summary of a YouTube video. Please create a title for the video that a potential viewer might want to click on.";

$description_prompt =
"### START SUMMARY ###
".$summary."
### END SUMMARY ###
The above is a summary of a YouTube video. Please create a video description for it. Create the description in the first person.";

$thumbnail_prompt =
"### START SUMMARY ###
".$summary."
### END SUMMARY ###
The above is a summary of a YouTube video. Please create a very descriptive description of a simple thumbnail image that could be used for the video. Refrain from describing any text in the image. Start your response with 'An image of'";

echo "Generating title...\n";
$title = send_chatgpt_message( $title_prompt, $api_key );

echo "Generating description...\n";
$description = send_chatgpt_message( $description_prompt, $api_key );

echo "Generating thumbnail description...\n";
$thumbnail_description = send_chatgpt_message( $thumbnail_prompt, $api_key );

echo "Generating thumbnail image...\n";
$image = create_image( $thumbnail_description, $api_key );

echo
"### Title ###
" . $title ."

### Description ###
" . $description . "

### Thumbnail description ###
" . $thumbnail_description . "

See thumbnail image in " . $image . "
";
