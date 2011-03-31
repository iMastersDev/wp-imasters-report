<?php

/*
Plugin Name: iMasters Report
Plugin URI: http://videolog.tv/imasters
Description: Plugin para exibir o iMasters Report
Version: 0.1
Author: Alê Borba
Author URI: http://universidadelivre.aleborba.com.br
*/

function imasters_report($args) {

    $videolog   = new videologAPI();
    $videos     = $videolog->getVideos();
    $id_video   = $videos->usuario->videos[0]->id;

    print $args['before_widget'];
    print $args['before_title'] . "{$videos->usuario->videos[0]->titulo}" . $args['after_title'];
    print "<object id='playerFlash' classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='200' height='150'>
            <param name='movie' value='http://www.videolog.tv/ajax/codigoPlayer.php?id_video={$id_video}&amp;relacionados=S&amp;default=S&amp;lang=PT_BR&amp;cor_fundo=ffffff&amp;cor_titulo=00cce0&amp;hd=true&amp;swf=1&width=200&height=150' />
            <param name='flashvars' value='id_video={$id_video}' />
            <param name='allowScriptAccess' value='always' />
            <param name='allowFullScreen' value='true' />
            <param name='wmode' value='opaque' />
            <embed src=\"http://www.videolog.tv/ajax/codigoPlayer.php?id_video={$id_video}&amp;relacionados=S&amp;default=S&amp;lang=PT_BR&amp;cor_fundo=ffffff&amp;cor_titulo=00cce0&amp;hd=true&amp;swf=1&width=200&height=150\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"200\" height=\"150\">
            </embed></object>";
    print $args['after_widget'];
}

function imasters_report_widgets() {
    register_sidebar_widget('iMasters Report', 'imasters_report');
}

class videologAPI{

    private $videos;

    public function  __construct() {
        $url = "http://api.videolog.tv/usuario/336169/videos.json";
        $curl = curl_init();

        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_HTTPHEADER,array('Token:3e7de16a-33f6-45cd-8547-f24fc5ec7f2a'));
        curl_setopt($curl,CURLOPT_TIMEOUT,30);
        curl_setopt($curl,CURLOPT_MAXREDIRS,4);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_FOLLOWLOCATION,true);
        $result = curl_exec($curl);
        $erro = curl_errno($curl);
        curl_close($curl);
        
        $this->videos = json_decode($result);

    }

    public function getVideos(){
        return $this->videos;
    }

}

// Carregar o widget
add_action('widgets_init', 'imasters_report_widgets');
?>