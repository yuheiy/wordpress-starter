# wordpress-starter

WordPress テーマ構築のための開発環境です。[wp-env](https://ja.wordpress.org/team/handbook/block-editor/reference-guides/packages/packages-env/) と [wp-scripts](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/) を利用したローカル開発環境が組み込まれています。[Timber](https://upstatement.com/timber/) の採用によって、[Twig](https://twig.symfony.com/) を利用したテンプレートの記述ができるようになっています。

## 導入

要求環境:

- [Docker クライアント](https://hub.docker.com/editions/community/docker-ce-desktop-mac/)
- Node.js 16
- [Composer](https://getcomposer.org/)
- [WP-CLI](https://wp-cli.org/)

`.env` の作成:

```bash
ACF_PRO_KEY=PUT_YOUR_LISENCE_KEY
```

[ACF Pro](https://www.advancedcustomfields.com/pro/) の[ライセンスキー](https://www.advancedcustomfields.com/resources/how-to-activate/)を入力します。

`auth.json` の作成:

```json
{
	"bearer": {
		"composer.admincolumns.com": "PUT_YOUR_AUTHENTICATION_TOKEN"
	}
}
```

[Admin Columns Pro](https://www.admincolumns.com/) の [Authentication Token](https://docs.admincolumns.com/article/95-installing-via-composer#authentication-token) を入力します。

依存パッケージのインストール:

```bash
npm ci
```

ローカルサーバーの起動:

```bash
npx wp-env start
npm run dev
```

## wp-env の使い方

wp-env は、Docker を使った WordPress 環境を簡単に構築するためのツールです。基本的な利用方法については[公式ドキュメント](https://ja.wordpress.org/team/handbook/block-editor/reference-guides/packages/packages-env/)を参照してください。

### セットアップ

WordPress の初期設定は自動で行うことができます。これまでにエクスポートされたデータがあれば、それを基にして復元します。

```bash
npx wp-env start
npm run setup
```

設定方法を変更する場合は、`env/setup.sh` を編集してください。

### コンテンツと uploads ディレクトリのエクスポート

WordPress のコンテンツおよび uploads ディレクトリは、`env` ディレクトリにエクスポートすることができます。

```bash
npx wp-env start
npm run export
```

### ダッシュボードへのアクセス

wp-env の起動後に次の URL を開いてください。

http://localhost:8888/wp-admin/

- ユーザー名: `admin`
- パスワード: `password`

## 本番用ビルド

次のコマンドを実行すると、ビルド済みのファイルが `source/wp-content/themes/mytheme/build` ディレクトリに出力されます。

```bash
npm run build
```

## 関連リソース

- [@wordpress/env](https://ja.wordpress.org/team/handbook/block-editor/reference-guides/packages/packages-env/): wp-env の公式ドキュメント
- [Timber Docs for v1 – Timber Documentation](https://timber.github.io/docs/): Timber の公式ドキュメント
- [The Timber Starter Theme](https://github.com/timber/starter-theme): Timber の公式スターターテーマ
- [Twig - The flexible, fast, and secure PHP template engine](https://twig.symfony.com/): Twig の公式ドキュメント
- [shifted](https://github.com/yuheiy/shifted): 静的サイト構築のためのフロントエンド開発環境
