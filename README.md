# Titleroo
This is a YouTube title, description and thumbnail generator. It uses the OpenAI Whisper API to transcribe the video, the ChatGPT API to create a summary of it and then create a title, description and thumbnail description based on the summary. Then it uses the Images API to (poorly) create an actual thumbnail image.

## Usage

You can use the `titleroo.php` script from the command line to create a title, description, thumbnail description and a thumbnail image from a video file. 

*Make sure to rename `settings.sample.php` to `settings.php` and add your OpenAI API key there*
```console
$ php titleroo.php VIDEO_FILE
```

You can also use the functions in the files of the `src/` folder to do all the things searately. For example, with the `summarize()` function in `summarizer.php` you can create a summary of a long text with ChatGPT (text can be longer than ChatGPT token limit)

## Support
If you find this helpful, consider supporting me at https://buymeacoffee.com/unconv

Subscribe to my YouTube channel, where I have a video of me creating this repository live: https://www.youtube.com/@unconv
