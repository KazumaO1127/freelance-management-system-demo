# DDD + Clean Architecture（概要）

目的: Laravel 上で「実務的に説得力のある」DDD を示すための最小構成を定義します。

アーキテクチャ要約
- Domain (純粋ビジネスロジック)
  - Models/
  - ValueObjects/
  - Services/
- Application (ユースケース)
  - UseCases/
  - DTOs/
- Infrastructure (実装詳細)
  - Repositories/ (Eloquent Adapter)
- Presentation / Http (Controller / Requests / Views)

ディレクトリ例
```
app/
├── Domain/
│   ├── Models/
│   ├── ValueObjects/
│   └── Services/
├── Application/
│   ├── UseCases/
│   └── DTOs/
├── Infrastructure/
│   └── Repositories/
└── Http/
    ├── Controllers/
    └── Requests/
```

主要ルール（実務的ガイドライン）
- Entity は振る舞いを持つ（Anemic Model 禁止）
- RepositoryInterface は Domain に配置し、Infrastructure が実装する（依存逆転）
- Controller は UseCase を呼ぶのみ
- ValueObject は不変（immutable）
- Infrastructure は Domain を参照するが逆は不可

移行方針（段階的）
1. Domain Entity と ValueObject を追加（DB 非依存で Unit テストを先行）
2. RepositoryInterface を定義し、Infrastructure に Eloquent Adapter を作成
3. UseCase を作成し Controller を薄く置き換える
4. 互換レイヤー（app/Models）を残して段階的に移行する
   - 4-1. 既存の Eloquent Model（例: `app/Models/Project`）を「互換レイヤー」と定義し、Domain Entity との境界を明文化する
     - 役割: 既存画面・既存機能からの呼び出しを受けつつ、内部では Infrastructure の Repository を経由して Domain Entity を扱う薄いアダプタとする
   - 4-2. 新規機能・新規画面では、`app/Models` を直接参照せず、必ず UseCase + Repository + Domain Entity を利用する
     - PR レビュー観点: Controller / Service から `app/Models` への新規依存が増えていないことを確認する
   - 4-3. 既存機能をルート/画面単位で順次リファクタリングする
     - 手順: Controller → UseCase → Repository → Domain Entity の呼び出しに書き換え、`app/Models` への直接依存を削減する
     - PR レビュー観点: 対象機能の Controller から Eloquent 直接呼び出し（`Model::find`, `Model::create` など）が消えていること
   - 4-4. 最終段階として、`app/Models` を「インフラ向け Eloquent 定義 + 最小限のアクセサ」に縮退させるか、完全削除する
     - 完了条件:
       - Presentation 層（Controller / Request / View）から `app/Models` を直接参照していない
       - Domain / Application 層が Eloquent に依存していない（Interface 経由のみ）
       - `sail test --coverage` で 80% 以上を維持した状態で、`app/Models` の削減/削除に伴うリグレッションがないことを PR 上で確認できる
