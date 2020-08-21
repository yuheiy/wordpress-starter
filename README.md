# boilerplate-wordpress

WordPressテーマ構築のための開発環境です。[wp-env](https://ja.wordpress.org/team/handbook/block-editor/packages/packages-env/)と[webpack-dev-server](https://webpack.js.org/configuration/dev-server/)によるローカル開発環境が組み込まれています。WordPressテーマ内での[Svelte](https://svelte.dev/)を利用した[クライアントサイドレンダリング](https://developers.google.com/web/updates/2019/02/rendering-on-the-web?hl=ja#csr)を前提にしています。

## 導入

### 必須環境

次の環境を用意してください。

- [Dockerクライアント](https://hub.docker.com/editions/community/docker-ce-desktop-mac/)
- Node.js v12以降

### 推奨環境

次の環境での開発を推奨します。

- VS Code
  - [Svelte for VS Code](https://marketplace.visualstudio.com/items?itemName=svelte.svelte-vscode)
  - [Prettier - Code formatter](https://marketplace.visualstudio.com/items?itemName=esbenp.prettier-vscode)

### [ACF PRO](https://www.advancedcustomfields.com/pro/)の設定

ソースコードを次のように変更してください。

`.wp-env.json`:

```diff
{
- "plugins": [],
+ "plugins": ["./plugins/advanced-custom-fields-pro"],
  "themes": ["./my-theme"]
}
```

`scripts/install-acf-pro.sh`:

```diff
-curl "https://connect.advancedcustomfields.com/v2/plugins/download?p=pro&k=SET_YOUR_KEY" > "$root_dir/advanced-custom-fields-pro.zip"
+curl "https://connect.advancedcustomfields.com/v2/plugins/download?p=pro&k=XXXXXXXX" > "$root_dir/advanced-custom-fields-pro.zip"
```

`package.json`:

```diff
-   "//postinstall": "bash scripts/install-acf-pro.sh"
+   "postinstall": "bash scripts/install-acf-pro.sh"
```

### 依存パッケージのインストール

`wp-env`コマンドを実行するためにグローバル環境にインストールします。

```sh
npm install -g @wordpress/env
```

ローカルインストールの前に[ACF PROの設定](#acf-proの設定)を完了させてください。

```sh
npm install
```

## 開発用サーバーの起動

最初にWordPressのローカル環境を起動します。あらかじめDockerクライアントが起動されている必要があります。`http://localhost:8888`から確認できます。

```sh
wp-env start
```

終了は次のコマンドで行えます。

```sh
wp-env stop
```

データベースの操作・環境の再構築などについては[@wordpress/envの公式ドキュメント](https://ja.wordpress.org/team/handbook/block-editor/packages/packages-env/)を参照してください。

次にフロントエンドの開発用サーバーを起動します。WordPressのローカル環境が起動されている必要があります。URLはターミナルに出力されます。

```sh
npm start
```

### WordPressのダッシュボード

WordPressのローカル環境を起動すればダッシュボードにアクセスできます。

http://localhost:8888/wp-admin/

- ID: `admin`
- パスワード: `password`

### データベースおよびメディアファイルのエクスポート

WordPressのローカル環境における、現在の状態のデータベースとメディアファイルを`scripts/snapshot`ディレクトリに出力できます。これによって別のローカル環境でも同様の状態を再現できるようになります。WordPressのローカル環境が起動されている必要があります。

```sh
bash scripts/wp-export.sh
```

### データベースおよびメディアファイルのインポート

前回にエクスポートされた際のデータベースとメディアファイルの状態を復元できます。WordPressのローカル環境が起動されている必要があります。

```sh
bash scripts/wp-import.sh
```

## ディレクトリ構造

```
├── my-theme/
│   ├── acf-json/
│   ├── assets/
│   │   ├── favicon.[contenthash].svg
│   │   ├── main.[contenthash].js
│   │   ├── main.[contenthash].css
│   │   └── webpack-manifest.json
│   ├── inc/
│   │   └── news.php
│   ├── archive-news.php
│   ├── functions.php
│   ├── index.php
│   ├── single-news.php
│   └── style.css
├── resources/
│   ├── components/
│   │   ├── GlobalStyle.svelte
│   │   ├── Header.svelte
│   │   └── Layout.svelte
│   ├── routes/
│   │   ├── index/
│   │   │   └── Button.svelte
│   │   ├── archive-news.svelte
│   │   ├── index.svelte
│   │   ├── index.ts
│   │   └── single-news.svelte
│   ├── favicon.svg
│   └── main.ts
├── .wp-env.json
└── package.json
```

### `my-theme`ディレクトリ

WordPressのテーマディレクトリです。ビルドされたファイルもこのディレクトリに出力されます。

### `my-theme/acf-json`ディレクトリ

このディレクトリが存在しているとACF PROの[Synchronized JSON](https://www.advancedcustomfields.com/resources/synchronized-json/)が有効化され、ダッシュボードから入力された内容がこのディレクトリ内のJSONファイルとして自動的に同期されるようになります。

### `my-theme/assets`ディレクトリ

webpackでビルドされたファイルが出力されます。

### `my-theme/inc`ディレクトリ

`functions.php`から読み込むファイルを配置します。

### `resources`ディレクトリ

webpackがビルドする対象にするソースファイルを配置します。画像ファイルなどもこのディレクトリに含めることで、JavaScriptファイルやWordPressテーマファイルのPHPから読み込めるようになります。読み込み方法については[Cache Busting](#cache-busting)を参照してください。

### `resources/components`ディレクトリ

複数のページで再利用されるコンポーネントを配置します。

### `resources/routes`ディレクトリ

[WordPressのテンプレートファイル](https://wpdocs.osdn.jp/%E3%83%86%E3%83%B3%E3%83%97%E3%83%AC%E3%83%BC%E3%83%88%E9%9A%8E%E5%B1%A4)に対応するSvelteのルートコンポーネントを配置します。

特定のルート固有のコンポーネントや画像ファイルなどは、当ディレクトリ内にテンプレートファイルと同名のディレクトリを作成して格納することを推奨します（例：`resources/routes/index/Button.svelte`）。

### `.wp-env.json`

wp-envの設定ファイルです。WordPressプラグインの情報などを記述します。

## Cache busting

同じ名前のファイルの内容が変更された際に、ブラウザに保存された前回のキャッシュを無効化するため、`resources`ディレクトリに配置されたファイルは`main.bb785f51.js`のようにファイル名にフィンガープリントが付与された状態で出力されます。

参考：[アセットパイプライン - Railsガイド § 1.2 フィンガープリントと注意点](https://railsguides.jp/asset_pipeline.html#%E3%83%95%E3%82%A3%E3%83%B3%E3%82%AC%E3%83%BC%E3%83%97%E3%83%AA%E3%83%B3%E3%83%88%E3%81%A8%E6%B3%A8%E6%84%8F%E7%82%B9)

ソースファイル内では次のようにしてファイル名を参照します。

TypeScript：

```typescript
import cover from "../cover.jpg";
// -> /wp-content/themes/my-theme/assets/cover.[contenthash].jpg

const img = document.createElement("img");
img.src = cover;
```

Svelteのテンプレート：

```svelte
<script>
  import cover from "../cover.jpg";
  // -> /wp-content/themes/my-theme/assets/cover.[contenthash].jpg
</script>

<img src={cover} alt="">
```

Svelteの`style`要素：

```svelte
<style lang="scss">
  section {
    background-image: url("../cover.jpg");
    // -> /wp-content/themes/my-theme/assets/cover.[contenthash].jpg
  }
</style>

<section>
  ...
</section>
```

WordPressテーマのPHPファイル：

```php
$manifest = webpack_manifest();
echo $manifest['cover.jpg'];

function webpack_manifest()
{
  return json_decode(
    file_get_contents(get_theme_file_path('/assets/webpack-manifest.json')),
    true
  );
}
```

## テンプレートファイルの作成

コードジェネレータを使ってソースファイルのテンプレートを生成できます。次のようなコマンドを実行すると、新しいルートに対応するファイルが出力されます。

```sh
npx plop route archive-product
```

## 本番用ビルド

次のコマンドを実行すると、ビルド済みのファイルが`my-theme/assets`ディレクトリに出力されます。WordPressのローカル環境が起動されている必要はありません。

```sh
npm run build
```

## デプロイ

[本番用ビルド](#本番用ビルド)を実行した上で、`my-theme`ディレクトリを`wp-content/themes`ディレクトリ内にアップロードしてください。
