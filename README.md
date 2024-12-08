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
- **ACM**
- **CloudFront**


## APIの利用の仕方

### Open API
https://www.gaku-portfolio.com/swagger-ui/

### Postman URL
https://www.postman.com/todo-portfolio/workspace/portfolio

### ユーザー新規登録(emailが重複している場合は別のemailを利用する)

- リクエスト
```
curl -X POST https://www.gaku-portfolio.com/api/v1/auth/register \
-H "Content-Type: application/json" \
-d '{
  "name": "Taro",
  "email": "test@gmail.com",
  "password": "test123",
  "confirmPassword": "test123"
}'
```

- レスポンス
```
{"status":201,"data":{"accessToken":"7|HJEvYix5NmN1XRqbKzohU5nQ7kzMLYOdCppQPcQt29f9443f","userId":5},"message":"User Registeration successfully"}
```

### ログイン
- リクエスト
```
curl -X POST https://www.gaku-portfolio.com/api/v1/auth/login \
-H "Content-Type: application/json" \
-d '{
  "email": "test2@gmail.com",
  "password": "test123"
}'
```

- レスポンス
```
{"status":200,"data":{"token":"15|UNvjiay4r316EkqwNQrXOXSh12fYt4WvNqCmYnNb382910a5","userId":5},"message":"User Login Successfully"}
```

### ログアウト

- リクエスト
```
curl -X POST https://www.gaku-portfolio.com/api/v1/auth/logout \
-H "Content-Type: application/json" \
-H "Authorization: Bearer [Access token]"
```

- レスポンス
```
{"status":200,"message":"successfully logout"}%
```

### ユーザー取得

- リクエスト
```
curl -X GET https://www.gaku-portfolio.com/api/v1/users/5 \
-H "Content-Type: application/json" \
-H "Authorization: Bearer UNvjiay4r316EkqwNQrXOXSh12fYt4WvNqCmYnNb382910a5"
```

- レスポンス
```
{"status":200,"user":{"id":5,"name":"test user","email":"test2@gmail.com","email_verified_at":null,"created_at":"2024-12-08T06:48:46.000000Z","updated_at":"2024-12-08T06:48:46.000000Z"}}
```

### todo新規作成

- リクエスト
```
curl -X POST https://www.gaku-portfolio.com/api/v1/users/5/todos \
-H "Content-Type: application/json" \
-H "Authorization: Bearer UNvjiay4r316EkqwNQrXOXSh12fYt4WvNqCmYnNb382910a5" \
-d '{
  "content": "todoの新規作成テスト"
}'
```

- レスポンス
```
{"status":201,"message":"Create user`s todo successfully"}
```

### todo一覧取得

- リクエスト
```
curl -X GET https://www.gaku-portfolio.com/api/v1/users/5/todos \
-H "Content-Type: application/json" \
-H "Authorization: Bearer UNvjiay4r316EkqwNQrXOXSh12fYt4WvNqCmYnNb382910a5"
```

- レスポンス
```
{"status":200,"todos":[{"id":6,"user_id":5,"content":"todo\u306e\u65b0\u898f\u4f5c\u6210\u30c6\u30b9\u30c8","completed":0,"created_at":"2024-12-08T07:38:55.000000Z","updated_at":"2024-12-08T07:38:55.000000Z"},{"id":7,"user_id":5,"content":"todo\u306e\u65b0\u898f\u4f5c\u6210\u30c6\u30b9\u30c8","completed":0,"created_at":"2024-12-08T07:39:38.000000Z","updated_at":"2024-12-08T07:39:38.000000Z"}]}
```

### todo詳細取得

- リクエスト
```
curl -X GET https://www.gaku-portfolio.com/api/v1/users/5/todos/6 \
-H "Content-Type: application/json" \
-H "Authorization: Bearer UNvjiay4r316EkqwNQrXOXSh12fYt4WvNqCmYnNb382910a5"
```

- レスポンス
```
{"status":200,"todo":{"id":6,"user_id":5,"content":"todo\u306e\u65b0\u898f\u4f5c\u6210\u30c6\u30b9\u30c8","completed":0,"created_at":"2024-12-08T07:38:55.000000Z","updated_at":"2024-12-08T07:38:55.000000Z"}}
```


### todo更新

- リクエスト
```
curl -X PUT https://www.gaku-portfolio.com/api/v1/users/5/todos/7 \
-H "Content-Type: application/json" \
-H "Authorization: Bearer UNvjiay4r316EkqwNQrXOXSh12fYt4WvNqCmYnNb382910a5" \
-d '{
  "content": "Updated Todo Content",
  "completed": true
}'
```

- レスポンス
```
{"status":200,"todo":{"id":7,"user_id":5,"content":"Updated Todo Content","completed":0,"created_at":"2024-12-08T07:39:38.000000Z","updated_at":"2024-12-08T07:44:21.000000Z"}}
```

### todo削除

- リクエスト
```
curl -X DELETE https://www.gaku-portfolio.com/api/v1/users/5/todos/6 \
-H "Content-Type: application/json" \
-H "Authorization: Bearer UNvjiay4r316EkqwNQrXOXSh12fYt4WvNqCmYnNb382910a5"
```

- レスポンス
```
{"status":200,"message":"Delete user`s todo successfully"}
```