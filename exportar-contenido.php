<?php
// Cargar WordPress si es externo
require_once('wp-load.php');

// Tipo de contenido a exportar
$post_type = 'tu_post_type'; // cambia esto

$posts = get_posts([
    'post_type' => $post_type,
    'posts_per_page' => -1
]);

$export_data = [];

foreach ($posts as $post) {
    // Obtener meta campos
    $meta = get_post_meta($post->ID);

    // Obtener taxonomías
    $terms = wp_get_post_terms($post->ID, get_object_taxonomies($post->post_type));

    $tax_data = [];
    foreach ($terms as $term) {
        $tax_data[$term->taxonomy][] = $term->slug;
    }

    // Crear estructura
    $export_data[] = [
        'post_title' => $post->post_title,
        'post_content' => $post->post_content,
        'post_type' => $post->post_type,
        'post_status' => $post->post_status,
        'meta' => $meta,
        'taxonomies' => $tax_data
    ];
}

// Guardar como JSON
file_put_contents('export.json', json_encode($export_data, JSON_PRETTY_PRINT));

echo "Exportación completada.";
?>
