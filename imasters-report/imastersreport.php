<?php
/*
Plugin Name: iMasters Report
Plugin URI: http://youtube.com/imasters
Description: Plugin para exibir o iMasters Report
Version: 0.4
Author: Alê Borba
Author URI: http://universidadelivre.aleborba.com.br
*/

include_once 'YouTube.inc.php';

function imasters_report($args) {

    $videolog   = new YouTube("http://gdata.youtube.com/feeds/api/users/imasters/uploads");

    $id_video   = $videolog->getID();

    $options    = get_option('imasters_report');

    print $args['before_widget'];
    print $args['before_title'] . "{$videolog->getTitle()}" . $args['after_title'];
    print "<object width='{$options['largura']}' height='{$options['altura']}'>
            <param name='movie' value='http://www.youtube.com/v/{$id_video}?version=3&amp;hl=pt_BR'></param>
            <param name='allowFullScreen' value='true'></param>
            <param name='allowscriptaccess' value='always'></param>
            <embed src='http://www.youtube.com/v/{$id_video}?version=3&amp;hl=pt_BR' type='application/x-shockwave-flash' width='{$options['largura']}' height='{$options['altura']}' allowscriptaccess='always' allowfullscreen='true'></embed>
        </object>";
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

// Carregar o widget
add_action('widgets_init', 'imasters_report_widgets');
?>
