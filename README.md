# Another Exchange Rates

Widget de indicadores económicos (UF, Dólar, Euro) usando la API oficial de Chile.

Se usa en: chisi.cl.

## Instalación

1. Descarga `another-exchange-rates.zip` desde la
   [última release](https://github.com/chilehalal-spa/another-exchange-rates-wp/releases/latest).
   No uses el botón "Code → Download ZIP": ese zip trae otro nombre de carpeta.
2. En WordPress: *Plugins → Añadir nuevo → Subir plugin*, elige el zip y activa.

## Actualizaciones

El plugin se actualiza desde las releases de este repositorio, como cualquier
plugin de wordpress.org: aparecen en *Escritorio → Actualizaciones* y se pueden
dejar automáticas en *Plugins → Activar actualizaciones automáticas*.

WordPress revisa cada 12 horas; la respuesta de GitHub se guarda 6 horas.

## Publicar una versión nueva

1. Sube `Version:` en `another-exchange-rates.php` (por ejemplo `1.2.0`).
2. Haz commit y push.
3. Crea y sube el tag con el mismo número:

   ```bash
   git tag v1.2.0
   git push origin v1.2.0
   ```

La Action [Release](.github/workflows/release.yml) verifica que el tag coincida
con la versión, arma `another-exchange-rates.zip` y publica la release. Desde ese
momento los sitios ven la actualización.

## Licencia

GPL-2.0-or-later. Ver [LICENSE](LICENSE).
