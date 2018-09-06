<?php
    header("Content-Type: application/rss+xml; charset=ISO-8859-1");
    // Above is the header to genearte rss xml feed

    /* Links to facebook developers guide pages

    https://developers.facebook.com/docs/instant-articles/get-started/overview
    https://developers.facebook.com/docs/instant-articles/reference

    */
    include('include/config.php');
    include('include/connect.php');
    include('include/functions.php');

    // Replace Query according to database structure to get all required data.
    $sql = "SELECT * FROM articles ORDER BY datetime limit 25";

    $query = $mysqli->query($sql);
   
    $rssfeed = '<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">';
    $rssfeed .= '<channel>';
    $rssfeed .= '<title>Instant Article RSS feed</title>';
    $rssfeed .= '<link>http://yourwebsite.org</link>';
    $rssfeed .= '<description>Instant Article RSS feed</description>';
    $rssfeed .= '<language>en-us</language>';
    $rssfeed .= '<copyright>Copyright (C) '.date("Y").' yourwebsite.org</copyright>';

    // Change variables accoring to database table fields
	while ($row = $query->fetch_array()) {
	    extract($row);
	    $rssfeed .= '<item>';
        $rssfeed .= '<title>' . $title . '</title>';
        $rssfeed .= '<description>' . $details . '</description>';
        $rssfeed .= '<guid>' .GUID(). '</guid>';

        // Replace URL according to website url patterns
        $rssfeed .= '<link>http://yourwebsite.org/' . $id . '/'.slugify($title).'</link>';
        
        $rssfeed .= '<pubDate>' . date("D, d M Y H:i:s O", strtotime($datetime)) . '</pubDate>';
        $rssfeed .= '<author>' .$user_title. '</author>';

        // Here is the link https://developers.facebook.com/docs/instant-articles/example-articles which explains the differen options for content body
        $rssfeed .= '<content:encoded>';
        $rssfeed .= '<![CDATA[';
        $rssfeed .= '<!doctype html>';
        $rssfeed .= '<html lang="en" prefix="op: http://media.facebook.com/op#">';
        $rssfeed .= '<head>';
        $rssfeed .= '<meta charset="utf-8">';   
        $rssfeed .= '<link rel="canonical" href="http://yourwebsite.org/video/' . $id . '/'.slugify($title).'">';
        $rssfeed .= '<meta property="op:markup_version" content="v1.0">';
        $rssfeed .= '<meta property="og:title" content="' . $title . '">';
        $rssfeed .= '<meta property="og:description" content="' . $title . '">';
        $rssfeed .= '<meta property="og:image" content="http://yourwebsite.org/upload/videos/'.$thumbnail.'">';
        $rssfeed .= '</head>';
        $rssfeed .= '<body>';
        $rssfeed .= '<article>';
        $rssfeed .= '<header>';
        $rssfeed .= '<h1>' . $title . '</h1>';
        $rssfeed .= '<time class="op-published" datetime="' . date("D, d M Y H:i:s O", strtotime($datetime)) . '">' . date("D, d M Y H:i:s O", strtotime($datetime)) . '</time>';
        $rssfeed .= '<time class="op-modified" dateTime="' . date("D, d M Y H:i:s O", strtotime($datetime)) . '">' . date("D, d M Y H:i:s O", strtotime($datetime)) . '</time>';
        $rssfeed .= '<address>';
        $rssfeed .= '<a rel="facebook" href="https://www.facebook.com/yourwebsite.org">Video Lime</a>';
        $rssfeed .= 'Enjoy the videos and music you love, original content and share it all with friends, family and the world on YouTube.';
        $rssfeed .= '</address>';
        $rssfeed .= '<address>';
        $rssfeed .= '<a>Video Lime</a>';
        $rssfeed .= '</address>';
        $rssfeed .= '<figure>';

        // Change URL according to website url patterns
        $rssfeed .= '<img src="http://yourwebsite.org/upload/videos/'.$thumbnail.'" />';
        $rssfeed .= '<figcaption>' . $title . '</figcaption>';
        $rssfeed .= '</figure>';    
        $rssfeed .= '</header>';
        $rssfeed .= '<p> '.$details.' </p>';
        
        // Optional :  please reveiw Guide line https://developers.facebook.com/docs/instant-articles/reference/embeds
        $rssfeed .= '<figure class="op-interactive">';
        $rssfeed .= '<iframe class="column-width" height="180" width="320" src="https://www.youtube.com/embed/'.$youtube_id.'"></iframe>';

        $rssfeed .= '<figcaption>' . $title . '</figcaption>';
        $rssfeed .= '</figure>'; 

        $rssfeed .= '<footer>';
        $rssfeed .= '<aside>Managed By yourwebsite.org</aside>';
        $rssfeed .= '<small>Copyright (C) '.date("Y").' yourwebsite.org</small>';
        $rssfeed .= '</footer>';
        $rssfeed .= '</article>';
        $rssfeed .= '</body>';
        $rssfeed .= '</html>';
        $rssfeed .= ']]>';
        $rssfeed .= '</content:encoded>';
        $rssfeed .= '</item>';
	}
 
    $rssfeed .= '</channel>';
    $rssfeed .= '</rss>';
 
    echo $rssfeed;
?>