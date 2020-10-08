<script>
  import Layout from "../components/Layout.svelte";
</script>

<style lang="scss">
  @use "../core" as *;

  h1 {
    font-size: rem(32);
    font-weight: bold;
  }
</style>

<Layout {...$$props}>
  <h1>404</h1>
</Layout>
