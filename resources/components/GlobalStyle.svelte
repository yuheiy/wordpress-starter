<style lang="scss" global>
  @use "../../node_modules/normalize.css/normalize";
  @use "../core" as *;

  // Base

  *,
  ::before,
  ::after {
    box-sizing: border-box;
  }

  :target {
    scroll-margin-top: rem(15);
  }

  html {
    font-family: sans-serif;
    line-height: 1.5;
    text-align: left;
    line-break: normal;
    word-wrap: break-word;
    text-underline-offset: 0.25em;
  }

  h1,
  h2,
  h3,
  h4,
  h5,
  h6,
  p,
  hr,
  blockquote,
  ol,
  ul,
  dl,
  dd,
  figure,
  pre {
    margin: 0;
  }

  h1,
  h2,
  h3,
  h4,
  h5,
  h6 {
    font-size: inherit;
    font-weight: inherit;
  }

  hr {
    border: 0;
  }

  ol,
  ul {
    padding: 0;
  }

  li {
    // https://standard.shiftbrain.com/blog/reset-and-restore-list-item-marker
    list-style-type: none;
  }

  a {
    color: currentcolor;
    text-decoration: none;
  }

  u,
  ins {
    text-decoration: none;
  }

  strong,
  b {
    font-weight: inherit;
  }

  i,
  cite,
  em,
  var,
  address,
  dfn {
    font-style: inherit;
  }

  code,
  kbd,
  samp {
    font-size: inherit;
  }

  mark {
    color: currentcolor;
    background-color: transparent;
  }

  small {
    font-size: inherit;
  }

  s,
  del {
    text-decoration: none;
  }

  img,
  svg,
  video,
  canvas,
  audio,
  iframe,
  embed,
  object {
    display: block;
    max-width: 100%;
  }

  img,
  svg,
  video,
  canvas {
    height: auto;
  }

  audio {
    width: 100%;
  }

  svg {
    fill: currentcolor;
  }

  table {
    border-color: currentcolor;
    border-spacing: 0;
    border-collapse: collapse;
  }

  td,
  th {
    padding: 0;
  }

  th {
    font-weight: inherit;
    text-align: inherit;
  }

  input,
  textarea,
  select,
  button {
    padding: 0;
    font: inherit;
    letter-spacing: inherit;
    color: currentcolor;
    background-color: transparent;
    border: 0;
  }

  input[type="search"] {
    -webkit-appearance: none;
  }

  button:not(:disabled):not([aria-disabled="true"]) {
    cursor: pointer;
  }

  select {
    -moz-appearance: none;
    -webkit-appearance: none;
    border-radius: 0;
  }

  textarea {
    resize: vertical;
  }

  fieldset {
    margin: 0;
    padding: 0;
    border: 0;
  }

  [hidden][hidden] {
    display: none !important;
  }

  // https://github.com/WICG/focus-visible
  [data-js-focus-visible] :focus:not([data-focus-visible-added]) {
    outline: 0;
  }

  // Utilities

  // https://github.com/ampproject/amphtml/blob/438fd47f265f7aff4b116a9498d8b0d661a980bb/css/ampshared.css#L186-L216
  .visually-hidden {
    position: fixed;
    top: 0px;
    left: 0px;
    width: 4px;
    height: 4px;
    opacity: 0;
    overflow: hidden;
    border: none;
    margin: 0;
    padding: 0;
    display: block;
    visibility: visible;
    pointer-events: none;
  }

  $displayers: (
    block: block,
    inline: inline,
    hidden: none,
    inline-block: inline-block,
  );

  // .block
  // .md:block
  @each $breakpoint-key, $breakpoint in $mq-breakpoints {
    $uses-media-query: $breakpoint != 0;
    $breakpoint-prefix: if($uses-media-query, "#{$breakpoint-key}\\:", null);

    @include breakpoint($breakpoint-key) {
      @each $name, $displayer in $displayers {
        .#{$breakpoint-prefix}#{$name} {
          display: $displayer;
        }
      }
    }
  }
</style>
