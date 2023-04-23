<?php
/**
 * Splits a string into chunks, keeping sentences intact
 * 
 * @param string $text The text to split
 * @param int $length The length of the chunks
 * 
 * @return array An array of the string chunks
 */
function str_split_nice( string $text, int $length ): array {
    $chunks = str_split( $text, $length );
    $extra_text = "";

    $return = [];
    
    foreach( $chunks as $chunk ) {
        $dot_pos = strrpos( $chunk, ". " );
        $main_text = substr( $chunk, 0, $dot_pos+1 );
    
        $return[] = $extra_text.$main_text;
    
        $extra_text = substr( $chunk, $dot_pos+2 );
    }

    return $return;
}
