<?php
/**
 * Summarizes a long text with ChatGPT by splitting it
 * in parts and summarizing the parts separately, and
 * then making a summary of the summaries
 * 
 * @param string $text The text to summarize
 * @param string $api_key OpenAI API-key
 * 
 * @return string The final summary of the whole text
 */
function summarize( string $text, string $api_key ): string {
    $chunks = str_split_nice( $text, 10_000 );
    $prev_summary = "";
    $extra_text = "";
    $combined_summary = "";
    
    foreach( $chunks as $chunk ) {
        $prompt = $prev_summary."### START TRANSCRIPT ###\n" .
                    $chunk .
                  "\n### END TRANSCRIPT ##\n" .
                  "Please create a summarization of the above transcript. Use 5 sentences or less." . $extra_text;
        
        $response = send_chatgpt_message( $prompt, $api_key );
    
        $prev_summary = "### PREVIOUS SUMMARY ###\n" . $response . "\n### END PREVIOUS SUMMARY ###";
        $extra_text = " Use the previous summary as context for the new summary, but don't include anything from the previous summary in the new summary.";
    
        $combined_summary .= $response."\n";
    }
    
    $prompt = "### START SUMMARY ###\n".$combined_summary."\n### END SUMMARY ###\nPlease create a new summary of the above summary, but make it more natural.";
    
    $response = send_chatgpt_message( $prompt, $api_key );
    
    return $response;
}
