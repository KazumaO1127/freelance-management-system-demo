# フリーランス案件管理システム（デモ）

ITフリーランス／エージェント向け案件・契約・請求を一元管理する業務ミニSaaS。

## 機能概要
- 案件管理（CRUD、ステータス管理、検索）
- ダッシュボード（売上予測、稼働率）
- Redisセッション、PostgreSQL、Mailpitメールテスト対応
- 完全Docker環境（Laravel Sail）

## 環境構築（5分）

### 前提条件
- Windows + WSL2 + Docker Desktop
- Git

### 手順
```bash
git clone <リポジトリURL>
cd freelance-fms
cp .env.example .env
./vendor/bin/sail up -d --build # 初回10分
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrategit clone <リポジトリURL>
cd freelance-fms
cp .env.example .env
./vendor/bin/sail up -d --build # 初回10分
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

### 確認
- `http://localhost` → Laravelトップ
- `http://localhost/projects` → 案件一覧
- `http://localhost:8025` → Mailpit（メールテスト）

### 主要コマンド
```bash
sail up -d # 起動
sail down # 停止
sail artisan migrate # DB更新
sail test # テスト実行
```

## 技術スタック
Frontend: Blade + Bootstrap 5
Backend: Laravel 11 + PHP 8.3
DB: PostgreSQL 16
Cache/Session: Redis 7
Docker: Laravel Sail
Deployment: Railway（予定）

## デプロイ予定
- Railway（無料枠）+ PostgreSQL
- `Railway CLI`で`git push`即反映
