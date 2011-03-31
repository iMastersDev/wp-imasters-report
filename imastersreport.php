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

    $options    = get_option('imasters_report');

    print $args['before_widget'];
    print $args['before_title'] . "{$videos->usuario->videos[0]->titulo}" . $args['after_title'];
    print "<object id='playerFlash' classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='{$options['largura']}' height='{$options['altura']}'>
            <param name='movie' value='http://www.videolog.tv/ajax/codigoPlayer.php?id_video={$id_video}&amp;relacionados=S&amp;default=S&amp;lang=PT_BR&amp;cor_fundo=ffffff&amp;cor_titulo=00cce0&amp;hd=true&amp;swf=1&width={{$options['largura']}}&height={$options['altura']}' />
            <param name='flashvars' value='id_video={$id_video}' />
            <param name='allowScriptAccess' value='always' />
            <param name='allowFullScreen' value='true' />
            <param name='wmode' value='opaque' />
            <embed src=\"http://www.videolog.tv/ajax/codigoPlayer.php?id_video={$id_video}&amp;relacionados=S&amp;default=S&amp;lang=PT_BR&amp;cor_fundo=ffffff&amp;cor_titulo=00cce0&amp;hd=true&amp;swf=1&width={$options['largura']}&height={$options['altura']}\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"{$options['largura']}\" height=\"{$options['altura']}\">
            </embed></object>";
    print $args['after_widget'];
}

function imasters_report_widgets() {
    register_sidebar_widget('iMasters Report', 'imasters_report');
    register_widget_control('iMasters Report', 'configurar_imasters_report');
}

function configurar_imasters_report(){
    $options = array();

    if($_POST['salvar_config_report']){

        $options['largura'] = (int) $_POST['largura_player'];
        $options['altura']  = (int) $_POST['altura_player'];

        if (empty ($options['largura'])) {
            $options['largura'] = 200;
        }
        if (empty ($options['altura'])) {
            $options['altura'] = 150;
        }

        update_option('imasters_report',$options);
    }

    $options = get_option('imasters_report');

    ?>
        <input type="hidden" name="salvar_config_report" value="1" />
        <p>
            <label for="largura_player">Largura do Player<i>(defatult:200px)</i>:</label>
            <input type="text" name="largura_player" maxlength="4" value="<?php print ($options['largura'])?>" class="widefat" />
        </p>
        <p>
            <label for="altura_player">Altura do Player<i>(defatult:150px)</i>:</label>
            <input type="text" name="altura_player" maxlength="4" value="<?php print ($options['altura'])?>" class="widefat" />
        </p>
    <?php
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