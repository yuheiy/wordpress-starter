<script>
  import GlobalStyle from "./GlobalStyle.svelte";
  import Header from "./Header.svelte";
  import Footer from "./Footer.svelte";

  export let name;
  export let home_url;
  export let is_home;
  export let header_nav_items;
  export let footer_nav_items;

  if (process.env.NODE_ENV !== "production") {
    console.log($$props);
  }
</script>

<style lang="scss">
  @use "../core" as *;

  main {
    padding: 20px;
  }
</style>

<GlobalStyle />

<Header {name} {home_url} {is_home} {header_nav_items} />

<main>
  <slot />
</main>

<Footer {home_url} {footer_nav_items} />
