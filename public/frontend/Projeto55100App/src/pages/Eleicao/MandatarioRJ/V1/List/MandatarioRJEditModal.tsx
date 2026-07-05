import React, { useEffect, useState } from 'react'
import FormGrid, { type FormGridSchema } from '../../../../../components/ui/FormGrid/Input'
import type { SelectOptionItem } from '../../../../../components/ui/FormGrid/select'
import type { MandatarioRJTable } from '../../../../../services/modules/V1/mandatarioRJService'
import type { UseMandatarioRJEditReturn } from './useMandatarioRJEdit'
import { getToken } from '../../../../../services/modules/V1/authService/session'
import { APP_BASE_HOST, APP_VERSION } from '../../../../../config/constants'

const s = (v: string | number | null | undefined) =>
  v !== null && v !== undefined ? String(v) : ''

// ─── Auto-preenchimento a partir do candidato selecionado ────────────────────

interface AutoFillValues {
  cargoPolitico: string
  partidoPolitico: string
  municipioMandato: string
  cargoInstituicao: string
  qtdVotos2022: string
  qtdVotos2024: string
}

const EMPTY_AUTOFILL: AutoFillValues = {
  cargoPolitico: '',
  partidoPolitico: '',
  municipioMandato: '',
  cargoInstituicao: '',
  qtdVotos2022: '',
  qtdVotos2024: '',
}

function autoFillFromData(data: MandatarioRJTable | null): AutoFillValues {
  return {
    cargoPolitico: s(data?.cargo_politico),
    partidoPolitico: s(data?.partido_politico),
    municipioMandato: s(data?.municipio_mandato),
    cargoInstituicao: s(data?.cargo_instituicao),
    qtdVotos2022: s(data?.qtd_votos_2022),
    qtdVotos2024: s(data?.qtd_votos_2024),
  }
}

// ─── Schema do formulário ─────────────────────────────────────────────────────

function buildEditSchema(
  data: MandatarioRJTable | null,
  authToken: string | null,
  candidato2022Id: string,
  setCandidato2022Id: (v: string) => void,
  candidato2024Id: string,
  setCandidato2024Id: (v: string) => void,
  autoFill: AutoFillValues,
  setAutoFill: React.Dispatch<React.SetStateAction<AutoFillValues>>,
  onCandidatoSelected: (year: '2022' | '2024', item: SelectOptionItem | null) => void,
): FormGridSchema {
  const v = APP_VERSION.toLowerCase()
  const municipioBase      = `${APP_BASE_HOST}/api/${v}/municipio-rj`
  const municipioSrc       = `${municipioBase}/get-no-pagination?sort=nome_cidade&order=asc`
  const municipioGetSrc    = `${municipioBase}/get`
  const municipioFindSrc   = `${municipioBase}/find`
  const candidato2022Base  = `${APP_BASE_HOST}/api/${v}/candidato-2022-rj`
  const candidato2024Base  = `${APP_BASE_HOST}/api/${v}/candidato-2024-rj`
  const mandatarioViewBase = `${APP_BASE_HOST}/api/${v}/mandatario-rj-view`

  return {
    rows: [
      {
        sectionTitle: 'Identificação',
        fields: [
          { col: 8, label: 'Nome Político', id: 'nome_politico', name: 'nome_politico', value: s(data?.nome_politico), required: true },
          { col: 4, label: 'Data de Nascimento', id: 'dt_nascimento', name: 'dt_nascimento', type: 'data' as const, value: s(data?.dt_nascimento) },
        ],
      },
      {
        fields: [
          {
            col: 4,
            label: 'Cargo Político',
            id: 'cargo_politico',
            name: 'cargo_politico',
            value: autoFill.cargoPolitico,
            onChange: (e: React.ChangeEvent<HTMLInputElement>) =>
              setAutoFill(prev => ({ ...prev, cargoPolitico: e.target.value })),
          },
          {
            col: 4,
            label: 'Partido Político',
            id: 'partido_politico',
            name: 'partido_politico',
            value: autoFill.partidoPolitico,
            onChange: (e: React.ChangeEvent<HTMLInputElement>) =>
              setAutoFill(prev => ({ ...prev, partidoPolitico: e.target.value })),
          },
          {
            type: 'select' as const,
            col: 4,
            label: 'Município do Mandato',
            id: 'municipio_mandato',
            name: 'municipio_mandato',
            src: municipioSrc,
            getSrc: municipioGetSrc,
            findSrc: municipioFindSrc,
            findColumn: 'nome_cidade',
            valueKey: 'cd_tse',
            labelKey: ['cd_tse', 'nome_cidade'],
            maxVisible: 100,
            value: autoFill.municipioMandato,
            onChange: (value: string) => setAutoFill(prev => ({ ...prev, municipioMandato: value })),
            authToken: authToken ?? undefined,
          },
        ],
      },
      {
        sectionTitle: 'Instituição',
        fields: [
          { col: 6, label: 'Ocupa Instituição', id: 'ocupa_instituicao', name: 'ocupa_instituicao', value: s(data?.ocupa_instituicao) },
          {
            col: 6,
            label: 'Cargo na Instituição',
            id: 'cargo_instituicao',
            name: 'cargo_instituicao',
            value: autoFill.cargoInstituicao,
            onChange: (e: React.ChangeEvent<HTMLInputElement>) =>
              setAutoFill(prev => ({ ...prev, cargoInstituicao: e.target.value })),
          },
        ],
      },
      {
        sectionTitle: 'Referências eleitorais',
        fields: [
          {
            type: 'select' as const,
            col: 4,
            label: 'Cand. 2022 RJ (id)',
            id: 'candidato_2022_RJ_id',
            name: 'candidato_2022_RJ_id',
            value: candidato2022Id,
            onChange: (value: string, item: SelectOptionItem | null) => {
              setCandidato2022Id(value)
              onCandidatoSelected('2022', item)
            },
            src: `${candidato2022Base}/get-no-pagination?sort=QT_VOTOS_NOMINAIS&order=desc&limit=200`,
            findSrc: `${candidato2022Base}/find`,
            findColumn: 'NM_CANDIDATO',
            valueKey: 'id',
            labelKey: ['NM_CANDIDATO', 'NM_MUNICIPIO', 'SG_PARTIDO'],
            maxVisible: 200,
            authToken: authToken ?? undefined,
          },
          {
            type: 'select' as const,
            col: 4,
            label: 'Cand. 2024 RJ (id)',
            id: 'candidato_2024_RJ_id',
            name: 'candidato_2024_RJ_id',
            value: candidato2024Id,
            onChange: (value: string, item: SelectOptionItem | null) => {
              setCandidato2024Id(value)
              onCandidatoSelected('2024', item)
            },
            src: `${candidato2024Base}/get-no-pagination?sort=QT_VOTOS_NOMINAIS&order=desc&limit=200`,
            findSrc: `${candidato2024Base}/find`,
            findColumn: 'NM_CANDIDATO',
            valueKey: 'id',
            labelKey: ['NM_CANDIDATO', 'NM_MUNICIPIO', 'SG_PARTIDO'],
            maxVisible: 200,
            authToken: authToken ?? undefined,
          },
          {
            type: 'select' as const,
            col: 4,
            label: 'Suplente (id)',
            id: 'suplente_candidato_RJ_id',
            name: 'suplente_candidato_RJ_id',
            value: s(data?.suplente_candidato_RJ_id),
            src: `${mandatarioViewBase}/get-no-pagination`,
            valueKey: 'id',
            labelKey: 'nome_politico',
            authToken: authToken ?? undefined,
          },
        ],
      },
      {
        fields: [
          {
            col: 4,
            label: 'Qtd. Votos 2022',
            id: 'qtd_votos_2022',
            name: 'qtd_votos_2022',
            inputMode: 'numeric' as const,
            value: autoFill.qtdVotos2022,
            onChange: (e: React.ChangeEvent<HTMLInputElement>) =>
              setAutoFill(prev => ({ ...prev, qtdVotos2022: e.target.value })),
          },
          {
            col: 4,
            label: 'Qtd. Votos 2024',
            id: 'qtd_votos_2024',
            name: 'qtd_votos_2024',
            inputMode: 'numeric' as const,
            value: autoFill.qtdVotos2024,
            onChange: (e: React.ChangeEvent<HTMLInputElement>) =>
              setAutoFill(prev => ({ ...prev, qtdVotos2024: e.target.value })),
          },
        ],
      },
      {
        sectionTitle: 'Contato / Redes Sociais',
        fields: [
          { col: 4, label: 'WhatsApp', id: 'whatsapp', name: 'whatsapp', value: s(data?.whatsapp) },
          { col: 8, label: 'E-mail', id: 'email', name: 'email', value: s(data?.email) },
        ],
      },
      {
        fields: [
          { col: 4, label: 'YouTube', id: 'youtube', name: 'youtube', value: s(data?.youtube) },
          { col: 4, label: 'Facebook', id: 'facebook', name: 'facebook', value: s(data?.facebook) },
          { col: 4, label: 'Instagram', id: 'instagram', name: 'instagram', value: s(data?.instagram) },
        ],
      },
    ],
  }
}

// ─── Props ────────────────────────────────────────────────────────────────────

interface ModalTheme {
  headerStart: string
  headerEnd: string
  headerText: string
}

type MandatarioRJEditModalProps = Omit<UseMandatarioRJEditReturn, 'handleNew' | 'handleEdit'> & {
  theme: ModalTheme
}

// ─── Componente ───────────────────────────────────────────────────────────────

function MandatarioRJEditModal({
  mode,
  editId,
  editData,
  loadingEdit,
  saving,
  saveError,
  saveSuccess,
  saveDebug,
  modalRef,
  handleSave,
  theme,
}: MandatarioRJEditModalProps) {
  const authToken = getToken()
  const isCreate = mode === 'create'

  const [candidato2022Id, setCandidato2022Id] = useState('')
  const [candidato2024Id, setCandidato2024Id] = useState('')
  const [autoFill, setAutoFill] = useState<AutoFillValues>(EMPTY_AUTOFILL)
  const [debugOpen, setDebugOpen] = useState(false)

  // Ressincroniza os campos controlados sempre que o registro em edição muda
  useEffect(() => {
    setCandidato2022Id(s(editData?.candidato_2022_RJ_id))
    setCandidato2024Id(s(editData?.candidato_2024_RJ_id))
    setAutoFill(autoFillFromData(editData))
    setDebugOpen(false)
  }, [editData, editId, mode])

  function handleCandidatoSelected(year: '2022' | '2024', item: SelectOptionItem | null) {
    if (!item) return
    const qtdVotosKey = year === '2022' ? 'qtdVotos2022' : 'qtdVotos2024'
    setAutoFill(prev => ({
      ...prev,
      cargoPolitico: s(item.DS_CARGO as string | number | null | undefined),
      cargoInstituicao: s(item.DS_CARGO as string | number | null | undefined),
      partidoPolitico: s(item.SG_PARTIDO as string | number | null | undefined),
      municipioMandato: s(item.CD_MUNICIPIO as string | number | null | undefined),
      [qtdVotosKey]: s(item.QT_VOTOS_NOMINAIS as string | number | null | undefined),
    }))
  }

  const editSchema = buildEditSchema(
    editData,
    authToken,
    candidato2022Id,
    setCandidato2022Id,
    candidato2024Id,
    setCandidato2024Id,
    autoFill,
    setAutoFill,
    handleCandidatoSelected,
  )

  return (
    <div
      ref={modalRef}
      className="modal fade"
      id="mandatarioEditModal"
      tabIndex={-1}
      aria-labelledby="mandatarioEditModalLabel"
      aria-hidden="true"
    >
      <div className="modal-dialog modal-xl modal-dialog-scrollable">
        <div className="modal-content">

          <div
            className="modal-header"
            style={{
              background: `linear-gradient(135deg, ${theme.headerStart} 0%, ${theme.headerEnd} 100%)`,
            }}
          >
            <h5
              className="modal-title fw-semibold"
              id="mandatarioEditModalLabel"
              style={{ color: theme.headerText, letterSpacing: '0.03em' }}
            >
              {isCreate
                ? 'Novo Candidato'
                : `Editar Candidato${editData ? ` — ${editData.nome_politico ?? ''}` : ''}`}
            </h5>
            <button type="button" className="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar" />
          </div>

          <div className="modal-body">
            {loadingEdit && (
              <div className="d-flex justify-content-center py-4">
                <div className="spinner-border text-primary" role="status">
                  <span className="visually-hidden">Carregando…</span>
                </div>
              </div>
            )}

            {!loadingEdit && (
              <form id="mandatarioEditForm" onSubmit={handleSave} noValidate autoComplete="off">
                {saveError && (
                  <div className="alert alert-danger py-2 mb-3">{saveError}</div>
                )}
                {saveSuccess && (
                  <div className="alert alert-success py-2 mb-3">
                    {isCreate ? 'Candidato criado com sucesso.' : 'Salvo com sucesso.'}
                  </div>
                )}
                {import.meta.env.DEV && debugOpen && (
                  <pre
                    className="bg-dark text-light p-3 mb-3 rounded"
                    style={{ maxHeight: '260px', overflow: 'auto', fontSize: '0.75rem' }}
                  >
                    {saveDebug ? JSON.stringify(saveDebug, null, 2) : 'Nenhuma resposta registrada ainda — salve o formulário para ver o JSON.'}
                  </pre>
                )}
                <FormGrid key={isCreate ? 'new' : (editId ?? 0)} schema={editSchema} />
              </form>
            )}
          </div>

          <div className="modal-footer">
            {import.meta.env.DEV && (
              <button
                type="button"
                className="btn btn-outline-warning me-auto"
                onClick={() => setDebugOpen(prev => !prev)}
              >
                {debugOpen ? 'Ocultar Debug' : 'Debug'}
              </button>
            )}
            <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">
              Cancelar
            </button>
            <button
              type="submit"
              form="mandatarioEditForm"
              className="btn btn-primary fw-semibold"
              disabled={saving || loadingEdit}
            >
              {saving ? (
                <>
                  <span className="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" />
                  Salvando…
                </>
              ) : isCreate ? 'Criar' : 'Salvar'}
            </button>
          </div>

        </div>
      </div>
    </div>
  )
}

export default MandatarioRJEditModal
