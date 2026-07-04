# Review & Win — Marketing UI (Figma v2)

Redesign estrutural com **paleta original do sistema** (roxo `#8b5cf6`, azul `#3b82f6`, verde `#10b981`, Inter).

## Figma

- **Arquivo:** [Review & Win — Marketing UI](https://www.figma.com/design/tTXQsXNTvW545fiSt8ha6F)
- **File key:** `tTXQsXNTvW545fiSt8ha6F`
- **Página:** `🎨 Marketing Redesign v2`
- **Variáveis:** coleção `Review & Win Tokens` (primary, secondary, success, neutral)

## Frames

| Frame | Descrição |
|-------|-----------|
| `01 — Landing Desktop 1440` | Landing completa com nova estrutura |
| `02 — Login` | Split: painel roxo (marca) + formulário |
| `03 — Register` | Mesmo padrão do login |
| `04 — Public Review Page` | Mobile 390px — hero branded + form |

## Nova estrutura da landing (referências)

Inspirado em padrões de **Trustpilot Business**, **Birdeye** e landing pages B2B SaaS de review management:

1. **Header** — logo, navegação ancora, CTA roxo "Access Panel"
2. **Hero split** — copy + trust stack (10k+, 4.9★, 10x) à esquerda; **preview do fluxo de review** à direita
3. **Problem → Solution** — comparativo vermelho/verde (sem nós / com nós)
4. **How it works** — 4 passos numerados com badges gradiente roxo→azul
5. **Prize draw** — faixa full-width gradiente com **£10,000** em destaque
6. **Features** — grid 3×3 com cards neutros e bullets roxos
7. **Benefits / ROI** — checklist + card métrica gradiente
8. **Final CTA** — fundo roxo claro, botões primário/secundário
9. **Footer** — fundo ink `#111827`, colunas Product / Account

## Paleta (inalterada)

Fonte: `DOCS/09-DESIGN/design-tokens.json` e `layouts/admin.blade.php`

| Token | Valor |
|-------|-------|
| Primary | `#8b5cf6` / `#7c3aed` |
| Secondary | `#3b82f6` |
| Success | `#10b981` |
| Neutral bg | `#f9fafb` |
| Text | `#111827` / `#4b5563` |
| Font | Inter |

## Próximo passo (código)

Após aprovação dos frames no Figma, implementar no código:

- Reverter tokens editoriais (Instrument Serif, ink monocromático) para paleta acima
- Reconstruir `MarketingPage.jsx` seguindo a estrutura dos frames
- Auth e review page alinhados aos frames 02–04
