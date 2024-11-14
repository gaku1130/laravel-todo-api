# 認証機能付きTodoアプリケーション用APIサーバー

## 開発環境
- **使用言語**: PHP 8
- **フレームワーク**: Laravel 10
- **コンテナ**: Docker
- **データベース**: MySQL 8
- **API仕様書**: OpenAPI
- **認証**: Laravel Sanctum

## アプリケーションの機能

### 認証機能
Laravel Sanctumを使用して以下の認証機能を実装予定です：

- **ユーザー登録 (Sign Up)**  
- **ログイン (Login)**
- **ログアウト (Logout)**
- **APIトークンによる認証管理**

### Todo管理機能
認証済みユーザーが以下のTodo管理を行えるように実装予定です：

- **Todoの作成 (Create)**  
- **Todoの取得 (Read)**  
- **Todoの更新 (Update)**  
- **Todoの削除 (Delete)**  

## AWSで利用しているリソース
- **Route53**
- **VPC**
    - インターネットゲートウェイ
    - アベイラビリティーゾーン
    - サブネット
    - ルートテーブル
    - セキュリティグループ
- **EC2**
- **EIP**

## 追加予定のAWSリソース
- **ACM**
- **CloudFront**