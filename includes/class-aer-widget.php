<?php
if (!defined('ABSPATH')) exit;

class AER_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'aer_widget', 
            'Indicadores Económicos (AER)', 
            array('description' => 'Muestra UF, UTM y divisas internacionales.')
        );
    }

    public function widget($args, $instance) {
        $indicators = AER_Logic::get_indicators();
        $title = !empty($instance['title']) ? $instance['title'] : 'Indicadores Económicos';

        echo $args['before_widget'];
        
        echo '<div class="aer-widget-container">';
        echo '<h4 class="aer-title">' . esc_html($title) . '</h4>';

        if ($indicators) {
            echo '<div class="aer-date"><small>Actualizado: ' . $indicators['date'] . '</small></div>';
            echo '<ul class="aer-list">';
            
            // Lista completa de lo que queremos mostrar
            $show = ['uf', 'utm', 'dolar', 'euro', 'yen', 'yuan', 'dirham', 'rupia'];

            foreach ($show as $key) {
                if (isset($indicators[$key])) {
                    $item = $indicators[$key];
                    
                    // Formateo inteligente:
                    // Si es menor a 100 pesos (como el Yen o Rupia), mostramos 2 decimales
                    // Si es mayor (UF, Dolar), mostramos con separador de miles y 2 decimales
                    $val = $item['valor'];
                    if ($val < 1000) {
                        $valor_fmt = number_format($val, 2, ',', '.');
                    } else {
                        $valor_fmt = number_format($val, 2, ',', '.');
                    }
                    
                    echo '<li>';
                    echo '<span class="aer-label">' . esc_html($item['nombre']) . '</span>';
                    echo '<span class="aer-value">' . $item['unit'] . ' ' . $valor_fmt . '</span>';
                    echo '</li>';
                }
            }
            echo '</ul>';
            echo '<div class="aer-footer">Fuente: Banco Central & OpenRates</div>';
        } else {
            echo '<p>Cargando indicadores...</p>';
        }

        echo '</div>'; 
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Indicadores Económicos';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Título:</label> 
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}