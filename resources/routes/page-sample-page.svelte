<script>
  import Layout from "../components/Layout.svelte";
  export let post;
</script>

<style lang="scss">
  @use "../core" as *;

  h1 {
    margin-bottom: 20px;
    font-size: 32px;
    font-weight: bold;
  }

  .body > :global(*) + :global(*) {
    margin-top: 20px;
  }
</style>

<Layout {...$$props}>
  <h1>{post.post_title}</h1>
  <div class="body">
    {@html post.post_content}
  </div>
</Layout>
