<script>
  export let name;
  export let home_url;
  export let is_home;
  export let header_nav_items;
</script>

<style lang="scss">
  @use "../core" as *;

  header {
    padding: 20px;
    background-color: #eee;
  }

  p {
    font-size: 20px;
  }

  ul {
    display: flex;
    flex-wrap: wrap;
    margin-top: 15px;
    margin-left: -20px;
  }

  li {
    margin-left: 20px;
  }

  ul a {
    text-decoration: underline;
  }

  a[aria-current="true"] {
    font-weight: bold;
  }
</style>

<header>
  <nav>
    <p><a href={is_home ? null : home_url}>{name}</a></p>
    <ul>
      {#each header_nav_items as item}
        <li>
          <a href={item.link} aria-current={item.current}>{item.label}</a>
        </li>
      {/each}
    </ul>
  </nav>
</header>
