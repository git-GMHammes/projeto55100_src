import { APP_BASE_HOST, APP_VERSION } from '../../../config/constants'
import { getAuthHeader } from './authService/session'
import type { ApiEnvelope } from './authService'

const v = APP_VERSION.toLowerCase()
const BASE_VIEW = `${APP_BASE_HOST}/api/${v}/votos-municipio-2024-rj-view`

// ─── Tipo da View (view_votos_municipio_2024_RJ) ──────────────────────────────

export interface VotosMunicipio2024RJView {
  ID: number
  CD_MUNICIPIO: string | null
  NM_MUNICIPIO: string | null
  DS_CARGO: string | null
  CD_CARGO: string | null
  SG_PARTIDO: string | null
  NM_PARTIDO: string | null
  NR_VOTAVEL: string | null
  NM_VOTAVEL: string | null
  QT_VOTOS: number | null
}

export interface PaginationInfo {
  page: number
  limit: number
  total: number
  pages: number
}

export interface ApiPageEnvelope<T> extends ApiEnvelope<T> {
  pagination?: PaginationInfo
}

// ─── HTTP helpers ─────────────────────────────────────────────────────────────

function buildHeaders(): Record<string, string> {
  const headers: Record<string, string> = { 'Content-Type': 'application/json' }
  const auth = getAuthHeader()
  if (auth) headers['Authorization'] = auth
  return headers
}

async function httpGet<T>(url: string): Promise<T> {
  const res = await fetch(url, { headers: buildHeaders() })
  return res.json() as Promise<T>
}

// ─── View — leitura (lista e busca) ──────────────────────────────────────────

export async function getAllView(
  page = 1,
  limit = 50,
): Promise<ApiPageEnvelope<VotosMunicipio2024RJView[]>> {
  const url = `${BASE_VIEW}/get-all?page=${page}&limit=${limit}&sort=NM_MUNICIPIO&order=asc`
  return httpGet(url)
}

export async function searchView(
  q: string,
  page = 1,
  limit = 50,
): Promise<ApiPageEnvelope<VotosMunicipio2024RJView[]>> {
  const url = `${BASE_VIEW}/search?q=${encodeURIComponent(q)}&page=${page}&limit=${limit}&sort=NM_MUNICIPIO&order=asc`
  return httpGet(url)
}
