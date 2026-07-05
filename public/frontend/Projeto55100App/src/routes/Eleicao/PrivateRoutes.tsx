import { Navigate } from 'react-router-dom'
import MunicipioRJList from '../../pages/Eleicao/MunicipioRJ/V1/List'
import MandatarioRJList from '../../pages/Eleicao/MandatarioRJ/V1/List'
import MunicipioIbgeTseList from '../../pages/MunicipioIbgeTse/V1/List'
import VotosMunicipio2022RJList from '../../pages/Eleicao/VotosMunicipio2022RJ/V1/List'
import VotosMunicipio2024RJList from '../../pages/Eleicao/VotosMunicipio2024RJ/V1/List'
import { isAuthenticated } from '../../services/modules/V1/authService/session'

function PrivateMunicipioRJ() {
  return isAuthenticated() ? <MunicipioRJList /> : <Navigate to="/v1/login" replace />
}

function PrivateMandatarioRJ() {
  return isAuthenticated() ? <MandatarioRJList /> : <Navigate to="/v1/login" replace />
}

function PrivateMunicipioIbgeTse() {
  return isAuthenticated() ? <MunicipioIbgeTseList /> : <Navigate to="/v1/login" replace />
}

function PrivateVotosMunicipio2022RJ() {
  return isAuthenticated() ? <VotosMunicipio2022RJList /> : <Navigate to="/v1/login" replace />
}

function PrivateVotosMunicipio2024RJ() {
  return isAuthenticated() ? <VotosMunicipio2024RJList /> : <Navigate to="/v1/login" replace />
}

const eleicaoPrivateRoutes = [
  {
    path: '/v1/municipio-rj',
    element: <PrivateMunicipioRJ />,
  },
  {
    path: '/v1/mandatario-rj',
    element: <PrivateMandatarioRJ />,
  },
  {
    path: '/v1/municipio-ibge-tse',
    element: <PrivateMunicipioIbgeTse />,
  },
  {
    path: '/v1/votos-municipio-2022-rj',
    element: <PrivateVotosMunicipio2022RJ />,
  },
  {
    path: '/v1/votos-municipio-2024-rj',
    element: <PrivateVotosMunicipio2024RJ />,
  },
]

export default eleicaoPrivateRoutes
