# CircleCI

[ドキュメント](https://circleci.com/docs/ja/)

## Environment Variables

cinemasunshine (Organizations) > portal2018-api (Projects) > Project Settings > Environment Variables

### Docker Hub

こちらは[コンテキスト](https://circleci.com/docs/ja/2.0/contexts/)として設定中。

| Name | Value |
|:---|:---|
|DOCKERHUB_ID |Docker Hub ユーザ |
|DOCKERHUB_ACCESS_TOKEN |Docker Hub アクセストークン |

### デプロイ

| Name | Value |
|:---|:---|
| GCLOUD_SERVICE_KEY_DEVELOPMENT | Googleプロジェクトのフルサービス・キーJSONファイル （development用） |
| GOOGLE_COMPUTE_REGION_DEVELOPMENT | gcloud CLI のデフォルトとして設定する Google compute region （development用） |
| GOOGLE_PROJECT_ID_DEVELOPMENT | gcloud CLIのデフォルトとして設定するGoogleプロジェクトID （development用） |
