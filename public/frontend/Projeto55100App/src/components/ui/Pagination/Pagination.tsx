import type { PaginationProps } from './Pagination.types'

const WINDOW = 2

function buildPageWindow(currentPage: number, totalPages: number): number[] {
  const start = Math.max(1, currentPage - WINDOW)
  const end = Math.min(totalPages, currentPage + WINDOW)
  const pages: number[] = []
  for (let p = start; p <= end; p++) pages.push(p)
  return pages
}

function Pagination({ currentPage, totalPages, onPageChange }: PaginationProps) {
  if (totalPages <= 1) return null

  const pages = buildPageWindow(currentPage, totalPages)

  function goTo(page: number) {
    if (page < 1 || page > totalPages || page === currentPage) return
    onPageChange(page)
  }

  return (
    <nav aria-label="Paginação">
      <ul className="pagination justify-content-center mb-0 mt-3">
        <li className={`page-item ${currentPage === 1 ? 'disabled' : ''}`}>
          <button type="button" className="page-link" onClick={() => goTo(1)}>«</button>
        </li>
        <li className={`page-item ${currentPage === 1 ? 'disabled' : ''}`}>
          <button type="button" className="page-link" onClick={() => goTo(currentPage - 1)}>‹</button>
        </li>

        {pages[0] > 1 && <li className="page-item disabled"><span className="page-link">…</span></li>}

        {pages.map(p => (
          <li key={p} className={`page-item ${p === currentPage ? 'active' : ''}`}>
            <button type="button" className="page-link" onClick={() => goTo(p)}>{p}</button>
          </li>
        ))}

        {pages[pages.length - 1] < totalPages && <li className="page-item disabled"><span className="page-link">…</span></li>}

        <li className={`page-item ${currentPage === totalPages ? 'disabled' : ''}`}>
          <button type="button" className="page-link" onClick={() => goTo(currentPage + 1)}>›</button>
        </li>
        <li className={`page-item ${currentPage === totalPages ? 'disabled' : ''}`}>
          <button type="button" className="page-link" onClick={() => goTo(totalPages)}>»</button>
        </li>
      </ul>
    </nav>
  )
}

export default Pagination
