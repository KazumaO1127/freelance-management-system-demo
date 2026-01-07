# フリーランス案件管理システム

[![Tests](https://github.com/KazumaO1127/freelance-fms/workflows/Test/badge.svg)](https://github.com/KazumaO1127/freelance-fms/actions)
[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel)](https://laravel.com)

![デプロイ済み](https://railway.app/badge/YOUR_SERVICE.svg)

ITフリーランス／エージェント向け案件・契約・請求を一元管理する業務ミニSaaS。
**完全Docker環境＋CI/CD＋テスト自動化済み**。

## �️ アーキテクチャの特徴
本プロジェクトは**ポートフォリオとして設計力を示すため**、Laravelの標準構成ではなく、**DDD（ドメイン駆動設計） + クリーンアーキテクチャ**を採用しています。

### ディレクトリ構成（要約）
このリポジトリは「実務的プラグマティックDDD」方針を採用しています。詳細は `docs/ddd-architecture.md` および `docs/domain-design.md` を参照してください。

```text
app/
├── Domain/         # コアドメイン（Entity, ValueObject, Domain Services）
├── Application/    # ユースケース（UseCases, DTOs）
├── Infrastructure/ # 実装（Eloquent Adapter 等）
└── Http/           # プレゼンテーション（Controller, Requests, Views）

# 互換レイヤー
app/Models/         # Eloquent ベースの既存モデル（当面の Adapter として保持）
```

### 設計ポリシー
- **Thin Controller**: コントローラーはUseCaseの呼び出しのみを担当
- **Rich Domain Model**: データコンテナではなく、振る舞いを持つエンティティ
- **Dependency Injection**: 依存性の逆転によるテスタビリティの確保

## �🎯 デモ
- **ローカル**: `http://localhost/projects`
- **公開**: `https://freelance-fms.up.railway.app/projects`

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
./vendor/bin/sail artisan migrate
```

### 確認
- `http://localhost` → Laravelトップ
- `http://localhost/projects` → 案件一覧
- `http://localhost:8025` → Mailpit（メールテスト）
- ./vendor/bin/sail test # テスト実行（80%カバレッジ）

## 🧪 ローカルテスト実行
### 全テスト
./vendor/bin/sail test

### 特定テストのみ
./vendor/bin/sail test --filter ProjectTest

### カバレッジレポート
./vendor/bin/sail test --coverage

### Dusk（ブラウザテスト）
./vendor/bin/sail dusk

### 主要コマンド
```bash
sail up -d # 起動
sail down # 停止
sail artisan migrate # DB更新
sail test # テスト実行
sail artisan tinker # REPL
sail logs # ログ確認
```

## 技術スタック
Frontend: Blade + Bootstrap 5 + Tailwind
Backend: Laravel 11 + PHP 8.3
DB: PostgreSQL 16
Cache/Session: Redis 7
Docker: Laravel Sail
Deployment: Railway（予定）
Email: Mailpit
Test: PHPUnit + Dusk (80%カバレッジ)
CI/CD: GitHub Actions
Deploy: Railway (本番想定: AWS EB/ECS)

## デプロイ予定
- Railway（無料枠）+ PostgreSQL
- `Railway CLI`で`git push`即反映

## 📚 ドキュメント
- [要件定義](docs/requirements.md)
- [DDD アーキテクチャ（概要）](docs/ddd-architecture.md)
- [Domain 設計（projects）](docs/domain-design.md)
- [ER図](docs/er-diagram.md)
- [画面ラフ](docs/wireframes/)
- [CI/CD](docs/ci-cd.md)

※ 詳細な設計ルールと移行手順は上記の `docs/ddd-architecture.md` と `docs/domain-design.md` にまとめられています。README は要約を提供します。
