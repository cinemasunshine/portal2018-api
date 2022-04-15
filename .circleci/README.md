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
|AZURE_TENANT |Azure テナントID [circleci/azure-cli orb](https://circleci.com/developer/orbs/orb/circleci/azure-cli) |
|AZURE_USERNAME |Azure ユーザ名 [circleci/azure-cli orb](https://circleci.com/developer/orbs/orb/circleci/azure-cli) |
|AZURE_PASSWORD |Azure パスワード [circleci/azure-cli orb](https://circleci.com/developer/orbs/orb/circleci/azure-cli) |
