# 🎯 Freelance Management System Demo - Copilot Instructions

## 📋 プロジェクト概要
**FMS（フリーランスマネジメントシステム）ミニ版**をLaravel 11 + pragmatic DDD + クリーンアーキテクチャで構築。
ITフリーランス/エージェント向け案件管理SaaSデモ。

**ポートフォリオ目的**: エージェント面談で即デモ可能な完成品。

## 🛠️ 技術スタック（厳守）
Backend: Laravel 11 + PHP 8.3 + pragmatic DDD/Clean Architecture
DB: PostgreSQL 16（Sail）
Cache/Session: Redis 7
Frontend: Blade + Bootstrap 5
Test: PHPUnit（カバレッジ80%必須）
Docker: Laravel Sail
CI/CD: GitHub Actions
Deploy: Railway（AWS EB想定）

## 🎯 開発方針（必ず守る）
✅ Trunk Based Development
✅ main = 常にデモ可能状態
✅ feature/* ブランチ（1-3日完結）
✅ 全てPR経由マージ
✅ テスト80% + Actions緑必須
✅ pragmatic DDD規約厳守（Entity/ValueObject/Service）

注: 本プロジェクトでは「プラグマティックDDD」を採用します。つまり、DDD/クリーンアーキテクチャの原則はコアドメイン（projects 等）に厳格に適用し、認証や単純CRUDのような周辺機能は既存の Laravel 慣習を維持して段階的に移行してください。`app/Models` は互換レイヤー（Adapter）として当面保持することを許容します。

## 🗄️ pragmatic DDD設計規約（Copilot厳守）
※ 注意: 以下の規約は「コアドメイン」に適用します。周辺機能は簡素化して Laravel 標準のままにすることを許容します。
  - 単なるデータコンテナNG
  - calculateRevenue(), changeStatus()実装必須

1. ValueObject: Money, Period, ProjectStatus
  - 不変性保証
  - 値オブジェクト同士演算

1. Repository: ProjectRepositoryInterface + Eloquent実装
  - Controller直DBアクセス禁止
   - Eloquent 実装は `app/Infrastructure` に限定し、既存の `app/Models` は互換性保持のための Adapter として当面残す。

1. UseCase: CreateProjectUseCase
  - ControllerはUseCase呼び出しのみ

## 📂 ブランチ命名規則
feature/{issue番号}-{概要} # 新機能
spec/{issue番号}-{概要} # ドキュメント
fix/{issue番号}-{概要}
hotfix/{概要}
test/crud-coverage # テスト追加

## 📋 Issueテンプレート（厳守形式）
完了条件
✅ http://localhost/xxx 動作確認
✅ Domain/Entity振る舞いテスト通過
✅ sail test 緑
✅ カバレッジ 80%+
✅ GitHub Actions 緑

## 🗄️ モデル設計（projects実装済み）
```php
// app/Models/Project.php
fillable: ['title', 'client_name', 'unit_price', 'start_date', 'end_date', 'status', 'memo']
status enum: ['contact', 'negotiation', 'contracted', 'working', 'completed']

🧪 テストコード規約
Feature: Controller全メソッド（100%）
Unit: Modelバリデーション、アクセサ（80%）
命名: test_[メソッド]_[シナリオ]_can
例: test_store_validation_fails_can_return_errors

📱 UI/UX規約
Bootstrap 5 + tailwind + レスポンシブ必須
案件一覧: 表形式 + 分頁 + 検索フィルタ
共通ヘッダー: [ロゴ][ダッシュ][案件][請求]
ボタン: [新規][編集][削除]（確認ダイアログ）

🚀 コマンド規約（必ず使う）
./vendor/bin/sail up -d           # 起動
./vendor/bin/sail test --coverage # テスト＋カバレッジ
./vendor/bin/sail artisan make:... # 生成

📚 ドキュメント構成
docs/
├── requirements.md     # 要件定義（実装済/開発中/次）
├── er-diagram.md       # Mermaid ER図
├── wireframes/         # 画面ラフ（txt）
├── ci-cd.md           # GitHub Actions
├── ddd-architecture.md    # クリーンアーキテクチャ図
├── domain-design.md       # Entity/ValueObject一覧
└── repository-pattern.md  # Repository実装例

🎨 コードスタイル
PHP CS Fixer + Laravel Pint厳守
コントローラ: 超薄（UseCase委譲のみ）
リクエストクラス必須（StoreRequest/UpdateRequest）
Repository優先（EloquentはInfrastructure限定）

詳細規約
✅ PHP CS Fixer + Laravel Pint厳守
   ./vendor/bin/pint

✅ コントローラ: 単一責任（UseCase呼び出しのみ）
   ❌ ビジネスロジック禁止
   ❌ DBアクセス禁止
   ✅ 入力検証 → UseCase実行 → レスポンス

✅ リクエストクラス必須
   ProjectStoreRequest
   ProjectUpdateRequest
   → DTO変換 → UseCase実行

✅ Repository優先（依存逆転）
   Domain/Repositories/ProjectRepositoryInterface
   Infrastructure/Repositories/EloquentProjectRepository
   ❌ Controller直Eloquent::find()
   
✅ Entityに振る舞い実装必須
   Project::changeStatus()
   Project::calculateRevenue()
   
✅ UseCaseでオーケストレーション
   CreateProjectUseCase::execute()

🚫 禁止事項
❌ APP_DEBUG=true（本番想定）
❌ vendor/直編集（composer require）
❌ 直接mainコミット（PR必須）
❌ テスト未実装でのマージ
❌ MySQL使用（PostgreSQL固定）
❌ Controller直DBアクセス（Eloquent::find）
❌ ModelにControllerロジック
❌ Anemic Domain Model（データコンテナ）
❌ Repositoryなし直SQL

🎯 Copilot生成時の前提
常に「実務仕様FMSポートフォリオ」を意識
エージェントが5分で理解→デモ可能なコード
テスト＋CI/CD＋ドキュメント全て含める
Laravel 11最新規約厳守＋pragmatic DDDの純粋性を両立