<script>
  import Layout from "../components/Layout.svelte";

  export let posts;
  export let news_category_terms;
  export let pagination_links;
  export let news_post_type;
</script>

<style lang="scss">
  @use "../core" as *;

  h1 {
    font-size: 32px;
    font-weight: bold;
  }

  a {
    text-decoration: underline;
  }

  a[aria-current="true"] {
    font-weight: bold;
  }

  .main-list,
  .sub-list {
    margin-top: 20px;
  }

  .sub-list {
    display: flex;
    flex-wrap: wrap;
    margin-left: -20px;
  }

  .sub-list li {
    margin-left: 20px;
  }
</style>

<Layout {...$$props}>
  <h1>{news_post_type.label}</h1>

  <ul class="sub-list">
    <li>
      <a
        href={news_post_type.link}
        aria-current={news_category_terms.every((term) => !term.queried)}>
        すべて
      </a>
    </li>
    {#each news_category_terms as term}
      <li><a href={term.link} aria-current={term.queried}>{term.name}</a></li>
    {/each}
  </ul>

  <ol class="main-list">
    {#each posts as post}
      <li><a href={post.link}>{post.post_title}</a></li>
    {/each}
  </ol>

  {#if pagination_links}
    <ol class="sub-list">
      {#each pagination_links as link}
        <li>
          <a href={link.href} aria-current={link.current}>{link.label}</a>
        </li>
      {/each}
    </ol>
  {/if}
</Layout>
