<script>
  import Layout from "../components/Layout.svelte";
  export let news_posts;
  export let news_archive_url;
  export let sample_page;
  export let sample_url;
</script>

<style lang="scss">
  @use "../core" as *;

  h2 {
    font-size: 32px;
    font-weight: bold;
  }

  section + section {
    margin-top: 40px;
  }

  section > * + * {
    margin-top: 20px;
  }

  a {
    text-decoration: underline;
  }
</style>

<Layout {...$$props}>
  <section>
    <h2>{sample_page.post_title}</h2>
    <p>
      Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore, odit,
      sint cupiditate ratione autem praesentium et unde quas nulla vel sequi
      eaque laudantium id officia! Dolores ex expedita ratione. Hic.
    </p>
    <p>
      <a href={sample_url}>Learn more</a>
    </p>
  </section>

  <section>
    <h2>News</h2>
    <ol>
      {#each news_posts as post}
        <li>
          <a href={post.link}>{post.post_title}</a>
        </li>
      {/each}
    </ol>
    <p>
      <a href={news_archive_url}>View all</a>
    </p>
  </section>
</Layout>
