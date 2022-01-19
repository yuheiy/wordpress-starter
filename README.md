# boilerplate-wordpress

WordPress テーマ構築のための開発環境です。[wp-env](https://ja.wordpress.org/team/handbook/block-editor/reference-guides/packages/packages-env/) と [wp-scripts](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/) を利用したローカル開発環境が組み込まれています。[Timber](https://upstatement.com/timber/) の採用によって、[Twig](https://twig.symfony.com/) を利用したテンプレートの記述ができるようになっています。

## 導入

要求環境:

- [Docker クライアント](https://hub.docker.com/editions/community/docker-ce-desktop-mac/)
- Node.js 16

依存パッケージのインストール:

```bash
npm install
```

ローカルサーバーの起動:

```bash
npx wp-env start
npm run dev -w mytheme
```

### [ACF Pro](https://www.advancedcustomfields.com/pro/) の設定

ソースコードを次のように変更してください。

`.wp-env.override.json`:

```diff
+{
+	"config": {
+		"ACF_PRO_LICENSE": "SET_YOUR_KEY"
+	}
+}
```

`package.json`:

```diff
-	"//postinstall": "bin/install-acf-pro.mjs"
+	"postinstall": "bin/install-acf-pro.mjs"
```

`.wp-env.json`:

```diff
{
	"plugins": [
+		"./plugins/advanced-custom-fields-pro",
		"..."
	],
	...
}
```

設定後に次のコマンドを実行します。

```bash
npm install
```

## wp-env の使い方

wp-env は、Docker を使った WordPress 環境を簡単に構築するためのツールです。基本的な利用方法については[公式ドキュメント](https://ja.wordpress.org/team/handbook/block-editor/reference-guides/packages/packages-env/)を参照してください。

### セットアップ

次のコマンドを実行すると、自動的に WordPress の初期設定が行われます:

```bash
npx wp-env start
bin/wp-setup.mjs
```

### データベースおよびメディアファイルのエクスポート

WordPress ローカル環境のデータベースとメディアファイルの状態を `bin/snapshot` ディレクトリに出力できます。これを Git リポジトリにコミットすることで、別のローカル環境でも同様の状態を再現できるようになります。

```bash
npx wp-env start
bin/wp-export.mjs
```

### データベースおよびメディアファイルのインポート

`bin/snapshot` ディレクトリに前回の状態が保存されていれば、データベースとメディアファイルを復元できます。

```bash
npx wp-env start
bin/wp-import.mjs
```

### ダッシュボードへのアクセス

wp-env の起動後に次の URL を開いてください。

http://localhost:8888/wp-admin/

- ユーザー名: `admin`
- パスワード: `password`

## 本番用ビルド

次のコマンドを実行すると、ビルド済みのファイルが `mytheme/build` ディレクトリに出力されます。

```bash
npm run build -w mytheme
```

## 関連リソース

- [@wordpress/env](https://ja.wordpress.org/team/handbook/block-editor/reference-guides/packages/packages-env/): wp-env の公式ドキュメント
- [Timber Docs for v1 – Timber Documentation](https://timber.github.io/docs/): Timber の公式ドキュメント
- [The Timber Starter Theme](https://github.com/timber/starter-theme): Timber の公式スターターテーマ
- [Twig - The flexible, fast, and secure PHP template engine](https://twig.symfony.com/): Twig の公式ドキュメント
- [shifted](https://github.com/yuheiy/shifted): CSS や JavaScript など、フロントエンドのリソースの運用方法についてのより詳細な例
