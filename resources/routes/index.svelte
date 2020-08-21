<script>
  import Layout from "../components/Layout.svelte";
  import ogp from "../ogp.png";

  export let name;
  export let news_posts;
  export let news_post_type;
  export let sample_page;
</script>

<style lang="scss">
  @use "../core" as *;

  h2 {
    font-size: 32px;
    font-weight: bold;
  }

  * + section {
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
  <h1>
    <img src={ogp} alt={name} />
  </h1>

  <section>
    <h2>{sample_page.post_title}</h2>
    <p>
      これはサンプルページです。同じ位置に固定され、(多くのテーマでは)
      サイトナビゲーションメニューに含まれる点がブログ投稿とは異なります。まずは、サイト訪問者に対して自分のことを説明する自己紹介ページを作成するのが一般的です。たとえば以下のようなものです。
    </p>
    <p>
      <a href={sample_page.link}>さらに詳しく</a>
    </p>
  </section>

  <section>
    <h2>{news_post_type.label}</h2>
    <ol>
      {#each news_posts as post}
        <li>
          <a href={post.link}>{post.post_title}</a>
        </li>
      {/each}
    </ol>
    <p>
      <a href={news_post_type.link}>さらに表示</a>
    </p>
  </section>
</Layout>
