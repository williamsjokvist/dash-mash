<?php

function BBcode($text) {

    $parsed_text = htmlspecialchars($text);
    
    $super_parse = str_replace("&", " ", str_replace(";", " ", htmlspecialchars(  '$1' ))); // Prevents inline css injections and youtube embed extensions.
    
    // BBcode array
    $find = array(
        '~\\n~s',
        '~\[center\](.*?)\[/center\]~s',
        '~\[yt\](.*?)\[/yt\]~s',
        '~\[b\](.*?)\[/b\]~s',
        '~\[i\](.*?)\[/i\]~s',
        '~\[u\](.*?)\[/u\]~s',
        '~\[quote\](.*?)\[/quote\]~s',
        '~\[size=(.*?)\](.*?)\[/size\]~s',
        '~\[color=(.*?)\](.*?)\[/color\]~s',
        '~\[url\]((?:https?)://.*?)\[url]~s',
        '~\[img\](https?://.*?.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s',
        '~\[video\](https?://.*?\.(?:webm|mp4))\[/video\]~s',
        '~\[audio\](https?://.*?\.(?:mp3|wav|ogg))\[/audio\]~s'
    );
    // HTML tags to replace BBcode
    $replace = array(
        '<br>',
        '<div style="text-align:center; display:block;">$1</div>',
        '<iframe class="yt-player" src="https://youtube.com/embed/' . $super_parse . '" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>',
        '<b>$1</b>',
        '<i>$1</i>',
        '<span style="text-decoration:underline;">$1</span>',
        '<pre>$1</' . 'pre>',
        '<span style="font-size:' . $super_parse . 'px";>$2</span>',
        '<span style="color:' . $super_parse . '";>$2</span>',
        '<a href="$1">$1</a>',
        '<img src="$1" />',
        '<html5-player class="video"><video><source src="$1" type="video/webm"><source src="$1" type="video/mp4">Your browser does not support web videos</video><control-strip><button type="button"></button><player-time><time></time> / <time></time></player-time><control-range ><range-fill ></range-fill></control-range><button type="button"></button><control-range><range-fill></range-fill></control-range></control-strip></html5-player>',
        '<html5-player class="audio"><audio><source src="$1" type="audio/ogg"><source src="$1" type="audio/mp3"><source src="$1" type="audio/wav">Your browser does not support web audio</audio><control-strip><button type="button"></button><player-time><time></time> / <time></time></player-time><control-range ><range-fill ></range-fill></control-range><button type="button"></button><control-range><range-fill></range-fill></control-range></control-strip></html5-player>'
    );
    // Replacing the BBcodes with corresponding HTML tags
    return preg_replace($find, $replace, $parsed_text);
}
