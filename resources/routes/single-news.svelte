<script>
  import { DateTime } from "luxon";
  import Layout from "../components/Layout.svelte";

  export let post;
  export let news_post_type;

  $: postDate = DateTime.fromSQL(post.post_date_gmt, { zone: "utc" }).setZone();
</script>

<style lang="scss">
  @use "../core" as *;

  h1 {
    font-size: rem(32);
    font-weight: bold;
  }

  .meta,
  .meta ul {
    display: flex;
    flex-wrap: wrap;
    margin-left: rem(-20);
  }

  .meta > *,
  .meta ul > * {
    margin-left: rem(20);
  }

  .stack > * + * {
    margin-top: rem(40);
  }

  a {
    text-decoration: underline;
  }
</style>

<Layout {...$$props}>
  <div class="stack">
    <footer>
      <p><a href={news_post_type.link}>← {news_post_type.label}</a></p>
    </footer>

    <article>
      <header>
        <h1>{post.post_title}</h1>
        <div class="meta">
          <p>
            <time datetime={postDate.toISO()}>
              {postDate.toFormat('yyyy/M/d')}
            </time>
          </p>
          {#if post.news_category_terms}
            <div>
              <ul>
                {#each post.news_category_terms as term}
                  <li><a href={term.link}>{term.name}</a></li>
                {/each}
              </ul>
            </div>
          {/if}
        </div>
      </header>

      <div>
        {@html post.post_content}
      </div>
    </article>
  </div>
</Layout>
