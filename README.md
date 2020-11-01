# boilerplate-wordpress

WordPressテーマ構築のための開発環境です。[wp-env](https://ja.wordpress.org/team/handbook/block-editor/packages/packages-env/)と[webpack-dev-server](https://webpack.js.org/configuration/dev-server/)によるローカル開発環境が組み込まれています。[Timber](https://www.upstatement.com/timber/)の採用によって[Twig](https://twig.symfony.com/)でのテンプレートの記述ができるようになっています。

## 導入

### 必須環境

次の環境を用意してください。

- [Dockerクライアント](https://hub.docker.com/editions/community/docker-ce-desktop-mac/)
- Node.js v12以降

### 推奨環境

次の環境での開発を推奨します。

- VS Code
  - [Twig Language](https://marketplace.visualstudio.com/items?itemName=mblode.twig-language)
  - [Prettier - Code formatter](https://marketplace.visualstudio.com/items?itemName=esbenp.prettier-vscode)

### [ACF PRO](https://www.advancedcustomfields.com/pro/)の設定

ソースコードを次のように変更してください。

`.wp-env.json`:

```diff
- "plugins": [],
+ "plugins": ["./plugins/advanced-custom-fields-pro"],
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

### データベースおよびメディアファイルの初期化

`scripts/snapshot`ディレクトリに前回の状態が保存されていれば復元ができます。[データベースおよびメディアファイルのインポート](#データベースおよびメディアファイルのインポート)を参照してください。

`scripts/snapshot`ディレクトリが存在しない場合には、[データベースの初期化](#データベースの初期化)を行ってください。

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

次にフロントエンドの開発用サーバーを起動します。URLはターミナルに出力されます。

```sh
npm start
```

### WordPressのダッシュボード

WordPressのローカル環境を起動すればダッシュボードにアクセスできます。

http://localhost:8888/wp-admin/

- ID: `admin`
- パスワード: `password`

### データベースおよびメディアファイルのエクスポート

WordPressのローカル環境における現在の状態のデータベースとメディアファイルを`scripts/snapshot`ディレクトリに出力できます。これによって別のローカル環境でも同様の状態を再現できるようになります。

```sh
bash scripts/wp-export.sh
```

### データベースおよびメディアファイルのインポート

`scripts/snapshot`ディレクトリ内のデータから、前回にエクスポートされた際のデータベースとメディアファイルの状態を復元できます。

```sh
bash scripts/wp-import.sh
```

## Dockerの初期化

Dockerを初期化した上でデータベースを再セットアップできます。

```sh
wp-env destroy
wp-env start
bash scripts/wp-setup.sh
```

続けて、ダッシュボードから手動で次の操作を行う必要があります。

- リライトルールをフラッシュするために、[パーマリンク設定](http://localhost:8888/wp-admin/options-permalink.php)の「変更を保存」を実行する
- ニュース投稿に任意のカテゴリーを入力する

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
│   ├── templates/
│   │   ├── archive-news.twig
│   │   ├── index.twig
│   │   └── single-news.twig
│   ├── archive.php
│   ├── functions.php
│   ├── index.php
│   ├── single.php
│   └── style.css
├── resources/
│   ├── components/
│   │   ├── footer.scss
│   │   ├── header-modal.scss
│   │   ├── header-modal.ts
│   │   └── header.scss
│   ├── styles/
│   │   └── base.scss
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

### `my-theme/templates`ディレクトリ

Twigテンプレートを配置します。

### `resources`ディレクトリ

webpackのビルド対象にするソースファイルを配置します。画像ファイルなどもこのディレクトリに含めることで、PHPファイルやTypeScriptファイルからフィンガープリント付きのパスを読み込めるようになります。読み込み方法については[Cache Busting](#cache-busting)を参照してください。

### `resources/components`ディレクトリ

コンポーネントのCSSファイルおよびStimulusコントローラーのTypeScriptファイルを配置します。

- `resources/components/header-modal.scss`
- `resources/components/header-modal.ts`

特定のコンポーネントに関係するそれ以外のファイルは、コンポーネントと同名のディレクトリを作成の上でその中に配置します。

- `resources/components/header-modal/background.svg`
- `resources/components/header-modal/sub-module.ts`

### `.wp-env.json`

wp-envの設定ファイルです。WordPressのバージョンやプラグインのリストなどを記述します。

## Cache busting

`resources`ディレクトリに配置されたファイルは、`main.bb785f51.js`のようにファイル名にフィンガープリントが付与された状態で出力されます。これはソースファイルの内容が変更された際に出力するファイル名を変更することで、ブラウザに保存された前回のキャッシュを無効化するためです。

参考: [アセットパイプライン - Railsガイド § 1.2 フィンガープリントと注意点](https://railsguides.jp/asset_pipeline.html#%E3%83%95%E3%82%A3%E3%83%B3%E3%82%AC%E3%83%BC%E3%83%97%E3%83%AA%E3%83%B3%E3%83%88%E3%81%A8%E6%B3%A8%E6%84%8F%E7%82%B9)

ソースファイル内では次のようにしてファイル名を参照します。存在しないファイルを指定した場合はエラーが出力されます。

Twig:

```twig
<img src="{{ asset_path('components/header/background.svg') }}" alt="">
{# -> /wp-content/themes/my-theme/assets/components/header/background.[contenthash].svg #}
```

Sass:

```scss
// resources/components/header.scss

.header {
  background-image: url("./header/background.svg");
  // -> /wp-content/themes/my-theme/assets/components/header/background.[contenthash].svg
}
```

TypeScript:

```typescript
// resources/components/header.ts

import background from "./header/background.svg";

const img = document.createElement("img");
img.src = background;
// -> /wp-content/themes/my-theme/assets/components/header/background.[contenthash].svg
```

PHP:

```php
$manifest = webpack_manifest();
echo $manifest['components/header/background.svg'];
```

## テンプレートファイルの作成

コードジェネレータを使ってソースファイルのテンプレートを生成できます。次のようなコマンドを実行すると、新しいコンポーネントに対応するファイルが出力されます。

```sh
npx plop c my-component
```

## 本番用ビルド

次のコマンドを実行すると、ビルド済みのファイルが`my-theme/assets`ディレクトリに出力されます。WordPressのローカル環境が起動されている必要はありません。

```sh
npm run build
```

## デプロイ

[本番用ビルド](#本番用ビルド)を実行した上で、`my-theme`ディレクトリを`wp-content/themes`ディレクトリ内にアップロードしてください。
