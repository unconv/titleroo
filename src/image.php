<?php
/**
 * Creates an image based on a prompt for the OpenAI Images API
 * 
 * @param string $prompt Description of the image to be generated
 * @param string $api_key OpenAI API-key
 * @param string $output_folder Output folder where to put generated image file
 * @param ImageSize $size The size of the image to be generated
 * 
 * @return string Path to generated image file
 */
function create_image(
    string $prompt,
    string $api_key,
    string $output_folder = "images/",
    ImageSize $size = ImageSize::Size1024,
): string {
    $ch = curl_init( "https://api.openai.com/v1/images/generations" );
    
    curl_setopt_array( $ch, [
        CURLOPT_HTTPHEADER => [
            "Content-type: application/json",
            "Authorization: Bearer $api_key"
        ],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode( [
            "prompt" => $prompt,
            "n" => 1,
            "size" => $size->value,
        ] ),
        CURLOPT_RETURNTRANSFER => true,
    ] );
    
    $response = curl_exec( $ch );

    $json = json_decode( $response );

    if( ! isset( $json->data[0]->url ) ) {
        throw new \Exception( "Error in OpenAI Request: " . $response );
    }

    $image_file = rtrim( $output_folder, "/" ) . "/" . uniqid() . ".png";
    copy( $json->data[0]->url, $image_file );

    $image = imagecreatefrompng( $image_file );

    $image = imagecrop( $image, [
        "x" => 0,
        "y" => 224,
        "width" => 1024,
        "height" => 576
    ] );

    imagepng( $image, $image_file."_cropped.png" );

    return $image_file."_cropped.png";
}

enum ImageSize: string {
    case Size1024 = "1024x1024";
    case Size512 = "512x512";
    case Size256 = "256x256";
}
