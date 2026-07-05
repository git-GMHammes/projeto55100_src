import React, { useState, useEffect } from 'react'
import { useNavigate } from 'react-router-dom'
import FormGrid, { type FormGridSchema } from '../../../../../components/ui/FormGrid/Input'
import { getActiveTheme } from '../../../../../themes/global'
import { clearSession, getUser } from '../../../../../services/modules/V1/authService/session'
import { PrivateTopbar } from '../../../../../components/layout/PrivateTopbar'
import { Pagination } from '../../../../../components/ui'
import {
  getAllView,
  searchView,
  type VotosMunicipio2024RJView,
  type PaginationInfo,
} from '../../../../../services/modules/V1/votosMunicipio2024RJService'
import VotosMunicipio2024RJDataTable from './VotosMunicipio2024RJDataTable'

// ─── Schema de busca ──────────────────────────────────────────────────────────

function buildSearchSchema(
  query: string,
  onChange: React.ChangeEventHandler<HTMLInputElement>,
): FormGridSchema {
  return {
    rows: [
      {
        fields: [
          {
            col: 12,
            id: 'votos-municipio-2024-search',
            name: 'votos-municipio-2024-search',
            placeholder: 'Buscar por município, cargo, partido ou candidato…',
            value: query,
            onChange,
          },
        ],
      },
    ],
  }
}

// ─── Componente principal ─────────────────────────────────────────────────────

function VotosMunicipio2024RJList() {
  const navigate = useNavigate()
  const { login: theme } = getActiveTheme()
  const user = getUser()

  const [items, setItems] = useState<VotosMunicipio2024RJView[]>([])
  const [query, setQuery] = useState('')
  const [page, setPage] = useState(1)
  const [pagination, setPagination] = useState<PaginationInfo | null>(null)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)

  // ── Reset de página ao mudar a busca ───────────────────────────────────────

  useEffect(() => {
    setPage(1)
  }, [query])

  // ── Debounce de busca / paginação ──────────────────────────────────────────

  useEffect(() => {
    const timer = setTimeout(() => {
      void load(query, page)
    }, query ? 400 : 0)
    return () => clearTimeout(timer)
  }, [query, page])

  async function load(q: string, p: number) {
    setLoading(true)
    setError(null)
    try {
      const res = q.trim()
        ? await searchView(q.trim(), p)
        : await getAllView(p)
      if (res.success && Array.isArray(res.data)) {
        setItems(res.data)
        setPagination(res.pagination ?? null)
      } else {
        setError(res.message ?? 'Erro ao carregar votos por município')
      }
    } catch {
      setError('Erro de conexão com o servidor')
      console.error('Erro ao carregar votos por município', q)
    } finally {
      setLoading(false)
    }
  }

  // ── Logout ─────────────────────────────────────────────────────────────────

  function handleLogout() {
    clearSession()
    navigate('/v1/login', { replace: true })
  }

  // ── Render ─────────────────────────────────────────────────────────────────

  const searchSchema = buildSearchSchema(query, e => setQuery(e.target.value))

  return (
    <div
      style={{
        minHeight: '100vh',
        background: `linear-gradient(135deg, ${theme.bgStart} 0%, ${theme.bgMid} 55%, ${theme.bgEnd} 100%)`,
        padding: '1.5rem',
      }}
    >
      <PrivateTopbar
        username={user?.um_user ?? '—'}
        onLogout={handleLogout}
        theme={theme}
      />

      {/* ── Card principal ── */}
      <div className="card shadow-lg border-0" style={{ borderRadius: '1rem', overflow: 'hidden' }}>

        {/* Cabeçalho */}
        <div
          style={{
            background: `linear-gradient(135deg, ${theme.headerStart} 0%, ${theme.headerEnd} 100%)`,
            padding: '1rem 1.5rem',
          }}
        >
          <h1 className="mb-0 fw-semibold h5" style={{ color: theme.headerText, letterSpacing: '0.03em' }}>
            Eleições 2024 — Votos por Município
          </h1>
        </div>

        <div className="card-body p-3">

          {/* ── Busca ── */}
          <div className="mb-3">
            <FormGrid schema={searchSchema} />
          </div>

          {/* ── Feedback ── */}
          {error && (
            <div className="alert alert-danger py-2 mb-3" role="alert">{error}</div>
          )}

          {loading && (
            <div className="d-flex justify-content-center py-4">
              <div className="spinner-border text-primary" role="status">
                <span className="visually-hidden">Carregando…</span>
              </div>
            </div>
          )}

          {/* ── Tabela ── */}
          {!loading && (
            <>
              <VotosMunicipio2024RJDataTable items={items} query={query} />
              {pagination && (
                <Pagination
                  currentPage={pagination.page}
                  totalPages={pagination.pages}
                  onPageChange={setPage}
                />
              )}
            </>
          )}
        </div>
      </div>
    </div>
  )
}

export default VotosMunicipio2024RJList
