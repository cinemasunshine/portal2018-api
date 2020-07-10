@echo off

cd D:\home\site\wwwroot

@rem env関数の呼び出し結果（環境変数）がキャッシュされます
php artisan config:cache

@rem コントローラベースのルート定義だけを使用してください
php artisan route:cache

php artisan view:cache
