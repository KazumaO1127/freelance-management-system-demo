# DDD + クリーンアーキテクチャ（概要）

目的: テスト容易性と移植性の高い DDD + クリーンアーキテクチャの最小構成を Laravel 上に定義します。

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
4. 互換レイヤー（app/Models）を残して段階的に移行
