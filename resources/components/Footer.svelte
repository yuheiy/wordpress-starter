<script>
  export let home_url;
  export let footer_nav_items;
</script>

<style lang="scss">
  @use "../core" as *;

  footer {
    padding: 20px;
    background-color: #eee;
  }

  ul {
    display: flex;
    flex-wrap: wrap;
    margin-left: -20px;
  }

  li {
    margin-left: 20px;
  }

  a {
    text-decoration: underline;
  }
</style>

<footer>
  <ul>
    <li>
      <a href={home_url}>Home</a>
    </li>
    {#each footer_nav_items as item}
      <li>
        <a href={item.link}>{item.label}</a>
      </li>
    {/each}
  </ul>
</footer>
