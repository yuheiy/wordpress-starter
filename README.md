# wordpress-starter

WordPress テーマ構築のための開発環境です。[wp-env](https://ja.wordpress.org/team/handbook/block-editor/reference-guides/packages/packages-env/) と [wp-scripts](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/) を利用したローカル開発環境が組み込まれています。[Timber](https://upstatement.com/timber/) の採用によって、[Twig](https://twig.symfony.com/) を利用したテンプレートの記述ができるようになっています。

## 導入

要求環境:

- [Docker クライアント](https://hub.docker.com/editions/community/docker-ce-desktop-mac/)
- Node.js 16
- [Composer](https://getcomposer.org/)
- [WP-CLI](https://wp-cli.org/)

依存パッケージのインストール:

```bash
npm ci
```

ローカルサーバーの起動:

```bash
npx wp-env start
npm run dev
```

### [ACF Pro](https://www.advancedcustomfields.com/pro/) のインストール

`.env` の作成:

```bash
ACF_PRO_KEY=PUT_YOUR_LISENCE_KEY
```

[ライセンスキー](https://www.advancedcustomfields.com/resources/how-to-activate/)を入力します。

パッケージの追加:

```bash
composer require advanced-custom-fields/advanced-custom-fields-pro
```

### [Admin Columns Pro](https://www.admincolumns.com/) のインストール

`auth.json` の作成:

```json
{
	"bearer": {
		"composer.admincolumns.com": "PUT_YOUR_AUTHENTICATION_TOKEN"
	}
}
```

[Authentication Token](https://docs.admincolumns.com/article/95-installing-via-composer#authentication-token) を入力します。

パッケージの追加:

```bash
composer require admin-columns/admin-columns-pro admin-columns/ac-addon-acf
```

## wp-env の使い方

wp-env は、Docker を使った WordPress 環境を簡単に構築するためのツールです。基本的な利用方法については[公式ドキュメント](https://ja.wordpress.org/team/handbook/block-editor/reference-guides/packages/packages-env/)を参照してください。

### セットアップ

次のコマンドを実行すると、自動的に WordPress の初期設定が行われます:

```bash
npx wp-env start
bin/wp-setup.mjs
```

### データベースと uploads ディレクトリのバックアップ

WordPress のデータベースと uploads ディレクトリは、プロジェクトの `.wp-backup` ディレクトリにバックアップできます。デフォルトでは、wp-env の development 環境がバックアップされます。

```bash
npx wp-env start
bin/wp-backup.mjs save
```

指定したリモート環境をバックアップすることもできます。

```bash
npx wp-env start
bin/wp-backup.mjs save remote production
```

`.wp-backup` ディレクトリに出力されたファイルを Git リポジトリにコミットすることで、WordPress の状態を別の開発者とも共有できるようになります。

次のようなコマンドを実行すれば、バックアップした状態を復元できます。これによって、現在の環境は上書きされます。

```bash
bin/wp-backup.mjs restore
```

### ダッシュボードへのアクセス

wp-env の起動後に次の URL を開いてください。

http://localhost:8888/wp-admin/

- ユーザー名: `admin`
- パスワード: `password`

## 本番用ビルド

次のコマンドを実行すると、ビルド済みのファイルが `themes/mytheme/build` ディレクトリに出力されます。

```bash
npm run build
```

## 関連リソース

- [@wordpress/env](https://ja.wordpress.org/team/handbook/block-editor/reference-guides/packages/packages-env/): wp-env の公式ドキュメント
- [Timber Docs for v1 – Timber Documentation](https://timber.github.io/docs/): Timber の公式ドキュメント
- [The Timber Starter Theme](https://github.com/timber/starter-theme): Timber の公式スターターテーマ
- [Twig - The flexible, fast, and secure PHP template engine](https://twig.symfony.com/): Twig の公式ドキュメント
- [shifted](https://github.com/yuheiy/shifted): 静的サイト構築のためのフロントエンド開発環境
