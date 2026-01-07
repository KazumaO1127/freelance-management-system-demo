# Domain Design（projects）

## Entity: Project

属性（例）
- id: int
- title: string
- client_name: string
- unit_price: Money (ValueObject)
- start_date: DateTime
- end_date: DateTime
- status: ProjectStatus (ValueObject / enum)
- memo: ?string

必須振る舞い（実装必須）
- `calculateRevenue(int $quantity): Money`
- `changeStatus(ProjectStatus $newStatus): void`（遷移ルールを内包）

## ValueObjects
- `Money`
  - 不変、整数表現（セント単位）
  - `add(Money): Money`, `multiply(int): Money`, `toInt(): int`
- `Period`
  - 開始／終了日、不変、検証ロジック（start <= end）
  - `lengthInDays(): int`
- `ProjectStatus`
  - enum ライク（contact, negotiation, contracted, working, completed）
  - `canTransitionTo(ProjectStatus): bool`

## Repository (Domain)
インターフェイスを Domain に定義します。

例:
```
interface ProjectRepositoryInterface
{
    public function save(Project $project): void;
    public function findById(int $id): ?Project;
    public function findAll(array $criteria = []): array;
}
```

## Application UseCases（最低実装）
- `CreateProjectUseCase::execute(ProjectCreateDTO): Project`
- `ListProjectsUseCase::execute(PageableDTO): Project[]`

## テスト方針
- Unit: Domain の振る舞いを中心に高いカバレッジ（Entity/VO は 100% を目標）
- Application: UseCase のフローを Mock を使って検証
