import type { VotosMunicipio2022RJView } from '../../../../../services/modules/V1/votosMunicipio2022RJService'

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

interface VotosMunicipio2022RJDataTableProps {
  items: VotosMunicipio2022RJView[]
  query: string
}

// ─── Componente ───────────────────────────────────────────────────────────────

function VotosMunicipio2022RJDataTable({ items, query }: VotosMunicipio2022RJDataTableProps) {
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
            <th>Nome Social</th>
            <th className="text-end">Votos Nominais</th>
            <th>Situação</th>
          </tr>
        </thead>
        <tbody>
          {items.length === 0 ? (
            <tr>
              <td colSpan={9} className="text-center text-muted py-3">
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
                <td>{fmt(item.NR_CANDIDATO)}</td>
                <td>{fmt(item.NM_CANDIDATO)}</td>
                <td>{fmt(item.NM_SOCIAL_CANDIDATO)}</td>
                <td className="text-end">{fmtNum(item.QT_VOTOS_NOMINAIS)}</td>
                <td>{fmt(item.DS_SIT_TOT_TURNO)}</td>
              </tr>
            ))
          )}
        </tbody>
      </table>
    </div>
  )
}

export default VotosMunicipio2022RJDataTable
