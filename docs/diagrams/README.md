Mermaid 図の出力手順

推奨: `@mermaid-js/mermaid-cli`（`mmdc`）で SVG/PNG を生成します。

インストール（一時）:
```bash
npx -y @mermaid-js/mermaid-cli@10.3.0 -v
```

SVG 生成例:
```bash
npx @mermaid-js/mermaid-cli -i docs/diagrams/sequence.mmd -o docs/diagrams/sequence.svg
```

PNG 生成例:
```bash
npx @mermaid-js/mermaid-cli -i docs/diagrams/sequence.mmd -o docs/diagrams/sequence.png
```

代替: Docker を使う場合
```bash
docker run --rm -v "$PWD":/data minlag/mermaid-cli -i /data/docs/diagrams/sequence.mmd -o /data/docs/diagrams/sequence.svg
```

オンラインレンダリング（Kroki）を利用する場合は、Markdown プレビュー拡張の設定で `mermaid` を有効にしてください。
