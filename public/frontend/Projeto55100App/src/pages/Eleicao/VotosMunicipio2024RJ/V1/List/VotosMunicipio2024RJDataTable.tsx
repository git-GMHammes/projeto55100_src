import type { VotosMunicipio2024RJView } from '../../../../../services/modules/V1/votosMunicipio2024RJService'

// ─── Helpers de formatação ────────────────────────────────────────────────────

function fmt(value: unknown): string {
  if (value === null || value === undefined || value === '') return '—'
  return String(value)
}

function fmtNum(value: number | null | undefined): string {
  if (value === null || value === undefined) return '—'
  return Number(value).toLocaleString('pt-BR')
}

// ─── Props ────────────────────────────────────────────────────────────────────

interface VotosMunicipio2024RJDataTableProps {
  items: VotosMunicipio2024RJView[]
  query: string
}

// ─── Componente ───────────────────────────────────────────────────────────────

function VotosMunicipio2024RJDataTable({ items, query }: VotosMunicipio2024RJDataTableProps) {
  return (
    <div className="table-responsive">
      <table className="table table-hover align-middle mb-0">
        <thead className="table-light">
          <tr>
            <th>Município</th>
            <th>Cargo</th>
            <th>Partido</th>
            <th>Nome do Partido</th>
            <th>Número</th>
            <th>Candidato</th>
            <th className="text-end">Votos</th>
          </tr>
        </thead>
        <tbody>
          {items.length === 0 ? (
            <tr>
              <td colSpan={7} className="text-center text-muted py-3">
                {query ? 'Nenhum resultado encontrado.' : 'Nenhum registro cadastrado.'}
              </td>
            </tr>
          ) : (
            items.map(item => (
              <tr key={item.ID}>
                <td>{fmt(item.NM_MUNICIPIO)}</td>
                <td>{fmt(item.DS_CARGO)}</td>
                <td>{fmt(item.SG_PARTIDO)}</td>
                <td>{fmt(item.NM_PARTIDO)}</td>
                <td>{fmt(item.NR_VOTAVEL)}</td>
                <td>{fmt(item.NM_VOTAVEL)}</td>
                <td className="text-end">{fmtNum(item.QT_VOTOS)}</td>
              </tr>
            ))
          )}
        </tbody>
      </table>
    </div>
  )
}

export default VotosMunicipio2024RJDataTable
