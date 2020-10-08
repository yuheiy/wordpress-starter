<?php get_header(); ?>

<div id="app" data-route="archive-news" data-props="<?php
if (
  $pagination_links = paginate_links([
    'prev_next' => false,
    'type' => 'array',
  ])
) {
  $pagination_links = array_map(function (string $html): array {
    $node = get_first_element_child($html);
    $label = $node->textContent;
    $href = strip_origin_from_url($node->getAttribute('href'));
    $current = (bool) $node->getAttribute('aria-current');
    return [
      'label' => $label,
      'href' => $href ? $href : null,
      'current' => $current,
    ];
  }, $pagination_links);
}

echo esc_attr(
  json_encode(
    array_merge(default_app_props(), [
      'posts' => array_map(function (WP_Post $post): WP_Post {
        set_post_link($post);
        set_post_acf($post);
        return $post;
      }, $wp_query->posts),

      'news_category_terms' => array_map(
        function (object $term): object {
          set_term_link($term);
          set_term_queried($term);
          return $term;
        },
        get_terms([
          'taxonomy' => 'news_category',
        ])
      ),

      'pagination_links' => $pagination_links,
    ])
  )
);

function get_first_element_child(string $html): DOMNode
{
  $dom = new DOMDocument();
  @$dom->loadHTML(
    mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'),
    LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
  );
  $node = $dom->getElementsByTagName('*')[0];
  return $node;
}

function set_term_queried(object $term): void
{
  $term->queried = $term->slug === get_query_var($term->taxonomy);
}
?>"></div>

<?php get_footer();
