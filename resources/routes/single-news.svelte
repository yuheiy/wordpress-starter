<script>
  import { DateTime } from "luxon";
  import Layout from "../components/Layout.svelte";

  export let post;
  export let news_archive_url;

  $: postDate = DateTime.fromSQL(post.post_date_gmt, { zone: "utc" }).setZone();
</script>

<style lang="scss">
  @use "../core" as *;

  h1 {
    font-size: 32px;
    font-weight: bold;
  }

  .stack > * + * {
    margin-top: 40px;
  }

  a {
    text-decoration: underline;
  }
</style>

<Layout {...$$props}>
  <div class="stack">
    <footer>
      <p>
        <a href={news_archive_url}>← News</a>
      </p>
    </footer>

    <article>
      <header>
        <h1>{post.post_title}</h1>
        <p>
          <time datetime={postDate.toISO()}>
            {postDate.toFormat('yyyy/M/d')}
          </time>
        </p>
      </header>

      <div>
        {@html post.post_content}
      </div>
    </article>

    <footer>
      <p>
        <a href={news_archive_url}>← News</a>
      </p>
    </footer>
  </div>
</Layout>
