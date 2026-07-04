import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import path from 'node:path'
import fs from 'node:fs'

const maparjDir = path.resolve(__dirname, '../../maparj')

function maparjMime(file: string): string {
  if (file.endsWith('.json'))    return 'application/json'
  if (file.endsWith('.prj'))     return 'text/plain'
  if (file.endsWith('.cpg'))     return 'text/plain'
  return 'application/octet-stream'
}

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    react(),
    {
      name: 'serve-maparj',
      configureServer(server) {
        server.middlewares.use('/maparj', (req, res, next) => {
          const file = path.join(maparjDir, req.url ?? '/')
          if (fs.existsSync(file) && fs.statSync(file).isFile()) {
            res.setHeader('Content-Type', maparjMime(file))
            fs.createReadStream(file).pipe(res)
          } else {
            next()
          }
        })
      },
    },
  ],
  base: './',
  server: {
    port: 5173,
    proxy: {
      '/maparj': 'http://localhost:55100',
    },
  },
  build: {
    outDir: '../dist',
  },
})