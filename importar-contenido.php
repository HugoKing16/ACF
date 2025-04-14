<?php
// Cargar WordPress si es externo
require_once('wp-load.php');

// Leer JSON exportado
$json = file_get_contents('export.json');
$data = json_decode($json, true);

foreach ($data as $item) {
    // Crear post
    $post_id = wp_insert_post([
        'post_title' => $item['post_title'],
        'post_content' => $item['post_content'],
        'post_type' => $item['post_type'],
        'post_status' => $item['post_status']
    ]);

    // Añadir campos personalizados
    foreach ($item['meta'] as $key => $values) {
        foreach ($values as $value) {
            update_post_meta($post_id, $key, maybe_unserialize($value));
        }
    }

    // Añadir taxonomías
    foreach ($item['taxonomies'] as $taxonomy => $terms) {
        wp_set_post_terms($post_id, $terms, $taxonomy);
    }
}

echo "Importación completada.";
?>
