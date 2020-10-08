<script>
  import Layout from "../components/Layout.svelte";
  export let post;
</script>

<style lang="scss">
  @use "../core" as *;

  h1 {
    margin-bottom: rem(20);
    font-size: rem(32);
    font-weight: bold;
  }

  .body > :global(*) + :global(*) {
    margin-top: rem(20);
  }
</style>

<Layout {...$$props}>
  <h1>{post.post_title}</h1>
  <div class="body">
    {@html post.post_content}
  </div>
</Layout>
