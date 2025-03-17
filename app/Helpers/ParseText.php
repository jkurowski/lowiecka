<?php
if (! function_exists('parse_text')) {
    function parse_text($string)
    {
        $output = preg_replace_callback('/\[galeria=(.*)](.*)\[\/galeria\]/', 'makeGallery', $string);
        return str_replace(
            array("</div>\n</p>","<p><div"),
            array("</div>", "<div"),
            $output
        );
    }
}
if (! function_exists('makeGallery')) {
    function makeGallery($input)
    {
        $photos = \App\Models\Image::all()->sortBy("sort")->where('gallery_id', $input[2]);

        if ($input[1] == 'gallery') {
            return view('front.parse.gallery', ['list' => $photos])->render();
        }

        if ($input[1] == 'slider') {
            return view('front.parse.slider', ['list' => $photos])->render();
        }
    }
}
