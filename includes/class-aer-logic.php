<?php
if (!defined('ABSPATH')) exit;

class AER_Logic {

    public static function get_indicators() {
        $transient_key = 'aer_indicators_full_v3'; // Cambié la versión del caché para que se actualice inmediato
        $data = get_transient($transient_key);

        if (false === $data) {
            // 1. Obtener datos locales (Chile)
            $response_local = wp_remote_get('https://mindicador.cl/api');
            
            // 2. Obtener datos internacionales (Base USD)
            $response_intl = wp_remote_get('https://open.er-api.com/v6/latest/USD');

            if (is_wp_error($response_local) || is_wp_error($response_intl)) {
                return false;
            }

            $json_local = json_decode(wp_remote_retrieve_body($response_local), true);
            $json_intl  = json_decode(wp_remote_retrieve_body($response_intl), true);

            if (empty($json_local) || empty($json_intl)) {
                return false;
            }

            // Valor del Dólar en Chile (Base para cálculos)
            $dolar_clp = $json_local['dolar']['valor'];

            // Función helper para convertir a pesos
            $calc_rate = function($currency_code) use ($dolar_clp, $json_intl) {
                $rate_per_usd = $json_intl['rates'][$currency_code] ?? 1;
                return $dolar_clp / $rate_per_usd;
            };

            $data = [
                // Indicadores Nacionales (Mindicador)
                'uf' => [
                    'nombre' => 'UF',
                    'valor'  => $json_local['uf']['valor'],
                    'unit'   => '$'
                ],
                'utm' => [
                    'nombre' => 'UTM',
                    'valor'  => $json_local['utm']['valor'],
                    'unit'   => '$'
                ],
                'dolar' => [
                    'nombre' => 'Dólar (USD)',
                    'valor'  => $json_local['dolar']['valor'],
                    'unit'   => '$'
                ],
                'euro' => [
                    'nombre' => 'Euro (EUR)',
                    'valor'  => $json_local['euro']['valor'],
                    'unit'   => '$'
                ],
                
                // Monedas Internacionales (Calculadas)
                'yen' => [
                    'nombre' => 'Yen (JPY)',
                    'valor'  => $calc_rate('JPY'),
                    'unit'   => '$'
                ],
                'yuan' => [
                    'nombre' => 'Yuan (CNY)',
                    'valor'  => $calc_rate('CNY'),
                    'unit'   => '$'
                ],
                'dirham' => [
                    'nombre' => 'Dirham (AED)',
                    'valor'  => $calc_rate('AED'),
                    'unit'   => '$'
                ],
                'rupia' => [
                    'nombre' => 'Rupia (IDR)', // CAMBIO: IDR (Indonesia) en vez de INR (India)
                    'valor'  => $calc_rate('IDR'),
                    'unit'   => '$'
                ],
                
                'date' => date('d-m-Y')
            ];

            // Guardar en caché por 2 horas
            set_transient($transient_key, $data, 2 * HOUR_IN_SECONDS);
        }

        return $data;
    }
}