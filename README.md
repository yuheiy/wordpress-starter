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

`scripts/install-acf-pro.sh`:

```diff
# プラグインファイルのURLを入力
-curl "https://connect.advancedcustomfields.com/v2/plugins/download?p=pro&k=SET_YOUR_KEY" > "$root_dir/advanced-custom-fields-pro.zip"
+curl "https://connect.advancedcustomfields.com/v2/plugins/download?p=pro&k=XXXXXXXX" > "$root_dir/advanced-custom-fields-pro.zip"
```

`package.json`:

```diff
-   "//postinstall": "bash scripts/install-acf-pro.sh"
+   "postinstall": "bash scripts/install-acf-pro.sh"
```

### 依存パッケージのインストール

この手順の前に「ACF PROの設定」を完了させてください。

```sh
npm install -g @wordpress/env
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

WordPressのローカル環境における、現在の状態のデータベースとメディアファイルを`scripts/snapshot`ディレクトリに出力します。これによって別のローカル環境でも同様の状態を再現できるようになります。WordPressのローカル環境が起動されている必要があります。

```sh
bash scripts/wp-export.sh
```

### データベースおよびメディアファイルのインポート

前回にエクスポートされた際のデータベースとメディアファイルの状態を復元します。WordPressのローカル環境が起動されている必要があります。

```sh
bash scripts/wp-import.sh
```

## ディレクトリ構造

TODO

## Cache busting

TODO

## テンプレートファイルの作成

コードジェネレータを使ってファイルのテンプレートを生成できます。次のようなコマンドを実行すると、新しいルートと対応するファイルが出力されます。

```sh
npx plop route archive-product
```

## 本番用ビルド

次のコマンドを実行すると、ビルド済みのファイルが`my-theme/assets`ディレクトリに出力されます。WordPressのローカル環境が起動されている必要はありません。

```sh
npm run build
```

## デプロイ

「本番用ビルド」を実行した上で、`my-theme`ディレクトリを`wp-content/themes`ディレクトリにアップロードしてください。
