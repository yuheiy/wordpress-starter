# boilerplate-wordpress

WordPressテーマ構築のための開発環境です。[wp-env](https://ja.wordpress.org/team/handbook/block-editor/packages/packages-env/)と[webpack-dev-server](https://webpack.js.org/configuration/dev-server/)によるローカル開発環境が組み込まれています。[Timber](https://www.upstatement.com/timber/)の採用によって[Twig](https://twig.symfony.com/)を使ったテンプレートの記述ができるようになっています。

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
-ACF_KEY="SET_YOUR_KEY"
+ACF_KEY="XXXXXXXX"
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

## Dockerコンテナのセットアップ

[開発用サーバーの起動](#開発用サーバーの起動)の前に、Dockerコンテナの初期化およびデータベースのセットアップを行ってください。あらかじめDockerクライアントが起動されている必要があります。

```sh
wp-env start
bash scripts/wp-setup.sh
```

すでに初期化済みのDockerコンテナを破棄した上で再セットアップを行いたい場合は、前のコマンドより先に次のコマンドを実行してください。

```sh
wp-env destroy
```

続けて、ダッシュボードから手動で次の操作を行う必要があります。

- リライトルールをフラッシュするために、[パーマリンク設定](http://localhost:8888/wp-admin/options-permalink.php)の「変更を保存」を実行する
- ニュース投稿に任意のカテゴリーを入力する

### データベースおよびメディアファイルのエクスポート

WordPressローカル環境のデータベースとメディアファイルの状態を`scripts/snapshot`ディレクトリに出力できます。これをGitリポジトリにコミットすることによって、別のローカル環境でも同様の状態を再現できるようになります。

```sh
wp-env start
bash scripts/wp-export.sh
```

### データベースおよびメディアファイルのインポート

`scripts/snapshot`ディレクトリに前回の状態が保存されていれば、それを基としてデータベースとメディアファイルを復元できます。あらかじめ[データベースおよびメディアファイルのエクスポート](#データベースおよびメディアファイルのエクスポート)が実行されている必要があります。

```sh
wp-env start
bash scripts/wp-import.sh
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

データベースの操作や環境の再構築などについては[@wordpress/envの公式ドキュメント](https://ja.wordpress.org/team/handbook/block-editor/packages/packages-env/)を参照してください。

次にフロントエンドの開発用サーバーを起動します。URLはターミナルに出力されます。

```sh
npm start
```

### WordPressのダッシュボード

WordPressのローカル環境を起動すればダッシュボードにアクセスできます。

http://localhost:8888/wp-admin/

- ID: `admin`
- パスワード: `password`

## ディレクトリ構造

```
.
├── my-theme/
│   ├── assets/
│   │   ├── favicon.[contenthash].ico
│   │   ├── main.[contenthash].css
│   │   ├── main.[contenthash].js
│   │   ├── ogp.[contenthash].png
│   │   └── webpack-manifest.json
│   ├── inc/
│   │   └── news.php
│   ├── templates/
│   │   ├── base.twig
│   │   └── index.twig
│   ├── acf-json/
│   ├── functions.php
│   ├── index.php
│   └── style.css
├── resources/
│   └── assets/
│       ├── components/
│       │   ├── index.scss
│       │   ├── index.scss.hbs
│       │   └── my-component.scss
│       ├── controllers/
│       │   ├── index.js
│       │   ├── index.js.hbs
│       │   └── my-controller.js
│       ├── styles/
│       │   ├── utilities/
│       │   │   ├── index.scss
│       │   │   ├── index.scss.hbs
│       │   │   └── my-utility.scss
│       │   ├── abstracts.scss
│       │   └── base.scss
│       ├── favicon.ico
│       ├── main.scss
│       ├── main.js
│       └── ogp.png
├── scripts/
│   ├── snapshot/
│   │   ├── uploads/
│   │   └── wordpress.sql
│   ├── wp-export.sh
│   ├── wp-import.sh
│   └── wp-setup.sh
├── .wp-env.js
├── drygen.config.js
├── package.json
└── webpack.config.js
```

### `my-theme`ディレクトリ

WordPressのテーマディレクトリです。ビルドされたファイルも同ディレクトリに出力されます。

### `my-theme/acf-json`ディレクトリ

このディレクトリが存在しているとACF PROの[Synchronized JSON](https://www.advancedcustomfields.com/resources/synchronized-json/)が有効化されます。それによって、ダッシュボードから入力された内容がこのディレクトリ内のJSONファイルとして自動的に同期されるようになります。

### `my-theme/assets`ディレクトリ

webpackでビルドされたファイルが出力されます。

### `my-theme/inc`ディレクトリ

`functions.php`から読み込むファイルを配置します。

### `my-theme/templates`ディレクトリ

Twigテンプレートを配置します。

### `resources/assets`ディレクトリ

webpackのビルド対象にするソースファイルを配置します。画像ファイルなども同ディレクトリに含めることで、PHPファイルやJavaScriptファイルからフィンガープリント付きのパスを取得できるようになります。読み込み方法については[Cache Busting](#cache-busting)を参照してください。

### `resources/assets/components`ディレクトリ

コンポーネントのCSSファイルおよび関連する画像ファイル等を配置します。コンポーネントのCSSファイル以外は、コンポーネントと同名のディレクトリを作成の上でその中に配置します。

- `resources/assets/components/header-modal.scss`
- `resources/assets/components/header-modal/background.svg`

### `resources/assets/controllers`ディレクトリ

Stimulusコントローラーおよびそれに関連するファイルを配置します。Stimulusコントローラー以外のファイルは、コントローラーと同名のディレクトリを作成の上でその中に配置します。

- `resources/assets/controllers/header-modal.js`
- `resources/assets/controllers/header-modal/sub-module.js`

### `.wp-env.json`

wp-envの設定ファイルです。WordPressのバージョンやプラグインのリストなどを記述します。

## Cache busting

`resources/assets`ディレクトリに配置されたファイルは、`main.bb785f51.js`のようにファイル名にフィンガープリントが付与された状態で出力されます。これはソースファイルの内容が変更された際に出力するファイル名を変更することで、ブラウザに保存された前回のキャッシュを無効化するためです。

参考: [アセットパイプライン - Railsガイド § 1.2 フィンガープリントと注意点](https://railsguides.jp/asset_pipeline.html#%E3%83%95%E3%82%A3%E3%83%B3%E3%82%AC%E3%83%BC%E3%83%97%E3%83%AA%E3%83%B3%E3%83%88%E3%81%A8%E6%B3%A8%E6%84%8F%E7%82%B9)

ソースファイル内では次のようにしてファイル名を参照します。存在しないファイルを指定した場合はエラーが出力されます。

Twig:

```twig
<img src="{{ asset_path('components/header/background.svg') }}" alt="">
{# -> /wp-content/themes/my-theme/assets/components/header/background.[contenthash].svg #}
```

Sass:

```scss
// resources/assets/components/header.scss

.header {
  background-image: url("./header/background.svg");
  // -> /wp-content/themes/my-theme/assets/components/header/background.[contenthash].svg
}
```

JavaScript:

```javascript
// resources/assets/controllers/header.js

import background from "../components/header/background.svg";

const img = document.createElement("img");
img.src = background;
// -> /wp-content/themes/my-theme/assets/components/header/background.[contenthash].svg
```

PHP:

```php
$manifest = webpackManifest();
echo $manifest['components/header/background.svg'];
```

## テンプレートファイルの生成

次のコマンドを実行すると、コードジェネレータを使ってソースファイルのテンプレートを生成できます。

```sh
npx scaffdog generate
```

テンプレートは`.scaffdog`ディレクトリに配置されています。

## 本番用ビルド

次のコマンドを実行すると、ビルド済みのファイルが`my-theme/assets`ディレクトリに出力されます。WordPressのローカル環境が起動されている必要はありません。

```sh
npm run build
```

## デプロイ

[本番用ビルド](#本番用ビルド)を実行した上で、`my-theme`ディレクトリを`wp-content/themes`ディレクトリ直下にアップロードしてください。
