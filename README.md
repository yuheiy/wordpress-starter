# boilerplate-wordpress

## 必須環境

- [Dockerクライアント](https://hub.docker.com/editions/community/docker-ce-desktop-mac/)
- Node.js

## 推奨環境

- VS Code
  - [Svelte for VS Code](https://marketplace.visualstudio.com/items?itemName=svelte.svelte-vscode)
  - [Prettier - Code formatter](https://marketplace.visualstudio.com/items?itemName=esbenp.prettier-vscode)

## セットアップ

1. npmパッケージのインストール
    ```sh
    npm install
    npm install -g @wordpress/env
    ```
1. Dockerクライアントの起動

## WordPress開発環境

起動：

```sh
wp-env start
```

終了：

```sh
wp-env stop
```

[@wordpress/envの公式ドキュメント](https://ja.wordpress.org/team/handbook/block-editor/packages/packages-env/)

### ダッシュボード

http://localhost:8888/wp-admin/

- ID: `admin`
- パスワード: `password`

### データベースおよびメディアファイルのインポート

```sh
wp-env start
bash scripts/wp-import.sh
```

### データベースおよびメディアファイルのエクスポート

```sh
wp-env start
bash scripts/wp-export.sh
```

## フロントエンド開発環境

起動：

```sh
wp-env start
npm start
```

本番用ビルド：

```sh
npm run build
```

ビルド済みのファイルを`my-theme/assets`ディレクトリに出力します。
