# boilerplate-wordpress

WordPress テーマ構築のための開発環境です。[wp-env](https://ja.wordpress.org/team/handbook/block-editor/reference-guides/packages/packages-env/) と [Vite](https://vitejs.dev/) を利用したローカル開発環境が組み込まれています。[Timber](https://upstatement.com/timber/) の採用によって、[Twig](https://twig.symfony.com/) を利用したテンプレートの記述ができるようになっています。

## 導入

要求開発環境:

- [Docker クライアント](https://hub.docker.com/editions/community/docker-ce-desktop-mac/)
- Node.js 16

推奨開発環境:

- [Visual Studio Code](https://code.visualstudio.com/)
  - [PHP Debug](https://marketplace.visualstudio.com/items?itemName=felixfbecker.php-debug)
  - [Prettier - Code formatter](https://marketplace.visualstudio.com/items?itemName=esbenp.prettier-vscode)
  - [TWIG pack](https://marketplace.visualstudio.com/items?itemName=bajdzis.vscode-twig-pack)

依存パッケージのインストール:

```sh
$ npm install --global @wordpress/env
$ npm install
```

ローカルサーバーの起動:

```sh
$ wp-env start
$ npm run dev
```

WordPress 環境が http://localhost:8888 に起動されます。

### [ACF Pro](https://www.advancedcustomfields.com/pro/) の設定

ソースコードを次のように変更してください。

`.env`:

```diff
+ACF_KEY=SET_YOUR_KEY
```

`package.json`:

```diff
-	"//postinstall": "bash scripts/install-acf-pro.sh"
+	"postinstall": "bash scripts/install-acf-pro.sh"
```

`.wp-env.json`:

```diff
{
	"plugins": [
		"https://downloads.wordpress.org/plugin/timber-library.1.18.2.zip"
+		"./plugins/advanced-custom-fields-pro"
	],
	...
}
```

設定後に次のコマンドを実行します。

```bash
npm install
```

## wp-env の使い方

wp-env は、WordPress の Docker 環境を簡単に構築するためのツールです。基本的な利用方法については[公式ドキュメント](https://ja.wordpress.org/team/handbook/block-editor/reference-guides/packages/packages-env/)を参照してください。

### セットアップ

次のコマンドを実行すると、自動的に WordPress の初期設定が行われます:

```sh
$ wp-env start
$ bash scripts/wp-setup.sh
```

すでに初期化済みの Docker コンテナを破棄した上で再セットアップを行いたい場合は、事前に次のコマンドを実行します:

```sh
wp-env destroy
```

設定後に、ダッシュボードから手動で次の操作を行う必要があります。

- リライトルールをフラッシュするために、[パーマリンク設定](http://localhost:8888/wp-admin/options-permalink.php)の「変更を保存」を実行

### データベースおよびメディアファイルのエクスポート

WordPress ローカル環境のデータベースとメディアファイルの状態を `scripts/snapshot` ディレクトリに出力できます。これを Git リポジトリにコミットすることによって、別のローカル環境でも同様の状態を再現できるようになります。

```sh
wp-env start
bash scripts/wp-export.sh
```

### データベースおよびメディアファイルのインポート

`scripts/snapshot` ディレクトリに前回の状態が保存されていれば、データベースとメディアファイルを復元できます。

```sh
wp-env start
bash scripts/wp-import.sh
```

### ダッシュボードへのアクセス

http://localhost:8888/wp-admin/

- ユーザー名: `admin`
- パスワード: `password`

## 本番用ビルド

次のコマンドを実行すると、ビルド済みのファイルが `mytheme/assets/build` ディレクトリに出力されます。

```sh
$ npm run build
```

デプロイの際には、必要に応じて不要なソースファイルを削除してください。

## 関連リソース

- [@wordpress/env](https://ja.wordpress.org/team/handbook/block-editor/reference-guides/packages/packages-env/): wp-env の公式ドキュメント
- [Timber Docs for v1 – Timber Documentation](https://timber.github.io/docs/): Timber の公式ドキュメント
- [The Timber Starter Theme](https://github.com/timber/starter-theme): Timber の公式スターターテーマ
- [Twig - The flexible, fast, and secure PHP template engine](https://twig.symfony.com/): Twig の公式ドキュメント
- [shifted](https://github.com/yuheiy/shifted): CSS や JavaScript など、フロントエンドのリソースの運用方法についてのより詳細な例
