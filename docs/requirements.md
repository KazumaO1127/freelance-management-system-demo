# 要件定義書 (Requirements)

## 1. プロジェクト概要
**ITフリーランス/クライアント様向け案件管理SaaS（FMS）**
クライアント様面談時のポートフォリオとして即デモ可能なレベルの完成度を目指す。

*   **目的**: アーキテクチャ設計力（DDD + Clean Architecture）と実装力の向上
*   **ターゲット**: ITフリーランス、クライアント様
*   **主要機能**: 案件管理、契約管理、請求管理、売上予測

## 2. 変更サマリ（今回の方針変更）

このプロジェクトは「プラグマティックDDD」を採用します。コアドメイン（projects）に対して DDD + Clean Architecture を厳格に適用し、周辺機能（認証や単純CRUDの管理UI等）は既存の Laravel 慣習を維持する方針です。

目的:
- クライアントに対して設計思想と品質を短時間で示せること
- レガシーなフレームワーク依存を段階的に削減し、テスト容易性と移植性を高めること

適用範囲（Pragmatic）:
- 厳格適用: `app/Domain` に置くべきコアエンティティ（Project 等）と ValueObject、RepositoryInterface、UseCase
- 慣習維持: `app/Models` や factories 等のフレームワーク依存コードは互換層（Adapter）として当面維持

受け入れ基準（追記）:
- Domain の Unit テスト（Project の振る舞い）が通ること
- `app/Domain` / `app/Application` / `app/Infrastructure` の雛形がリポジトリに追加されていること
- 移行手順が docs に記載され、PR にて検証可能であること

## 3. 機能要件 (Functional Requirements)

### 3.1 機能一覧とステータス
| ID | カテゴリ | 機能名 | 詳細 | ステータス |
| :--- | :--- | :--- | :--- | :--- |
| **P01** | **案件管理** | **案件一覧** | 案件の表形式表示、検索フィルタ、ページネーション | 🚧 **開発中** |
| P02 | 案件管理 | 案件登録 | 新規案件の作成（クライアント、単価、期間等） | 🚧 **開発中** |
| P03 | 案件管理 | 案件編集 | 登録済み案件情報の修正、ステータス変更 | 🚧 **開発中** |
| P04 | 案件管理 | 案件削除 | 誤登録の削除（論理削除/確認ダイアログ） | 📅 次スプリント |
| **D01** | **ダッシュボード** | **KPI表示** | 売上予測、稼働率の可視化 | 📅 次スプリント |
| **B01** | **請求管理** | 請求書発行 | 契約情報に基づく請求書PDF発行 | 📅 次スプリント |
| **C01** | **共通** | 認証機能 | ログイン/ログアウト (Laravel Breeze/Jetstream) | 📅 次スプリント |
| C02 | 共通 | レイアウト | Bootstrap 5 + レスポンシブ対応共通ヘッダー | 🚧 **開発中** |

*   **ステータス凡例**:
    *   ✅ 実装済み: 動作確認完了
    *   🚧 開発中: 現在のSprintで対応中
    *   📅 次スプリント: 次回以降の実装予定

### 3.2 ドメインモデル要件 (DDD)
*   **Project (Entity)**
    *   属性: タイトル, クライアント名, 単価, 期間, ステータス, メモ
    *   振る舞い: `calculateRevenue()`(売上計算), `changeStatus()`(ステータス遷移ロジック)
    *   ステータス定義: `contact`(問い合わせ) → `negotiation`(商談) → `contracted`(契約) → `working`(稼働) → `completed`(完了)
*   **ValueObjects**
    *   `Money`: 金額計算、通貨、不変性の保証
    *   `Period`: 期間計算（開始日〜終了日）、有効性チェック

## 4. 非機能要件 (Non-Functional Requirements)

### 4.1 技術スタックとアーキテクチャ
*   **Backend**: Laravel 11 + PHP 8.3
*   **Architecture**: Domain-Driven Design (DDD) + Clean Architecture
    *   `Domain`: 純粋なビジネスロジック (依存なし)
    *   `Application`: ユースケース制御
    *   `Infrastructure`: 実装詳細 (DB, 外部API)
    *   `Presentation`: HTTP I/O (Controller)
*   **Database**: PostgreSQL 16
*   **Cache/Session**: Redis 7

### 4.2 品質・運用
*   **テストカバレッジ**: 80%以上必須 (Branch Coverage)
*   **CI/CD**: GitHub Actionsによる自動テスト・Lint実行
*   **コード品質**: PHP CS Fixer + Laravel Pint準拠
*   **デプロイ**: Railway (本番運用想定)
*   **コンテナ化**: Laravel Sail (Docker) による完全な開発環境再現性

## 5. テスト戦略 (Test Strategy)

### 5.1 テストレベルとスコープ
| レベル | 対象 (Layer) | ツール | 目的・範囲 | 目標カバレッジ |
| :--- | :--- | :--- | :--- | :--- |
| **Unit** | **Domain** | PHPUnit | Entity/ValueObjectの振る舞い、ビジネスロジックの正確性検証 | 100% |
| **Unit** | **Application** | PHPUnit | UseCaseのフロー検証 (RepoはMock化) | 80% |
| **Integration** | **Infrastructure** | PHPUnit | Repository (Eloquent) のDB操作検証 | 80% |
| **Feature** | **Presentation** | PHPUnit | Controllerのエンドポイント検証、リクエスト/レスポンス形式、HTTPステータス | 100% |
| **E2E** | **UI** | Laravel Dusk | 重要なユーザーシナリオ (ログイン〜案件作成等) のブラウザ操作検証 | 主要シナリオ |

### 5.2 テスト自動化ルール
*   PR作成時: GitHub Actionsにて全テスト実行
*   マージ条件: テスト全通過 (Green) かつ カバレッジ80%以上
*   命名規則: `test_[method]_[scenario]_can_[expected_result]`
    *   例: `test_store_validation_fails_can_return_errors`

### 5.3 テストデータ
*   Laravel Factory/Seederを使用し、再現可能なテストデータを管理する。
*   `DatabaseSeeder` にデモ用初期データを完備し、`sail up` 直後にデモ可能な状態にする。
