# ER図 (Entity Relationship Diagram)

```mermaid
erDiagram
    users ||--o{ projects : "所有"
    projects ||--o{ invoices : "請求発行"

    users {
        bigint id PK
        string name
        string email
        string password
        timestamp email_verified_at "メール確認日時(nullable)"
        string remember_token "ログイン保持トークン"
        timestamp created_at
        timestamp updated_at
    }

    projects {
        bigint id PK
        bigint user_id FK "所有者ID"
        string title "案件名"
        string client_name "クライアント名"
        integer unit_price "単価(円)"
        date start_date "開始日"
        date end_date "終了日"
        enum status "contact, negotiation, contracted, working, completed"
        text memo "メモ"
        timestamp created_at
        timestamp updated_at
    }

    invoices {
        bigint id PK
        bigint project_id FK "案件ID"
        date issue_date "発行日"
        date due_date "支払期限"
        integer amount "請求金額(円)"
        enum status "draft, sent, paid, overdue"
        timestamp created_at
        timestamp updated_at
    }
```

## テーブル設計詳細

### 1. users (ユーザー)
Laravel標準の認証用テーブル。
- エージェント/フリーランス個人を識別します。
- 全てのリソース（Projects, Invoices）はこのユーザーIDに紐付きます（マルチテナント対応）。

### 2. projects (案件)
案件管理のコアとなるエンティティ。
- **client_name**: 今回はデモ用ポートフォリオのため、`clients`テーブルへの正規化を行わず、Projectエンティティ内に値を保持します（Aggregate Rootとして完結させるDDD的判断も含む）。
- **status**: ステータス遷移ロジックを持ちます。
  - `contact`: 問い合わせ
  - `negotiation`: 商談中
  - `contracted`: 契約締結
  - `working`: 稼働中
  - `completed`: 完了

### 3. invoices (請求書)
将来実装予定 (`B01`) の請求管理用テーブル。
- Projectと 1:N の関係（1つの案件で毎月請求が発生するため）。
- **amount**: 実際の請求額（稼働時間などで変動する可能性を考慮し、Projectの単価とは別に保持）。
